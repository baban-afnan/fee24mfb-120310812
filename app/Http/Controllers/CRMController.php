<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CRMrequest;
use App\Models\User;
use App\Models\ModificationField;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CRMController extends Controller
{
    public function index(Request $request)
    {
        $searchbatch_id = $request->input('search_batch_id');
        $statusFilter = $request->input('status');

        $query = CRMrequest::query();

        if ($searchbatch_id) {
            $query->where('batch_id', 'like', "%$searchbatch_id%");
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $enrollments = $query->orderByDesc('submission_date')->paginate(10);

        $statusCounts = [
            'pending' => CRMrequest::where('status', 'pending')->count(),
            'processing' => CRMrequest::where('status', 'processing')->count(),
            'resolved' => CRMrequest::where('status', 'resolved')->count(),
            'rejected' => CRMrequest::where('status', 'rejected')->count(),
        ];

        return view('crmreg', compact('enrollments', 'searchbatch_id', 'statusFilter', 'statusCounts'));
    }

    public function show($id)
    {
        $enrollmentInfo = CRMrequest::findOrFail($id);
        $user = User::find($enrollmentInfo->user_id);

        $statusHistory = collect([
            [
                'status' => $enrollmentInfo->status,
                'comment' => $enrollmentInfo->comment,
                'submission_date' => $enrollmentInfo->created_at,
                'updated_at' => $enrollmentInfo->updated_at,
            ]
        ]);

        return view('crmreg-view', compact('enrollmentInfo', 'statusHistory', 'user'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,processing,resolved,rejected',
        'comment' => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {
        $enrollment = CRMrequest::findOrFail($id);
        $oldStatus = $enrollment->status;
        $messageParts = [];

        // Update status
        if ($request->status !== $oldStatus) {
            $enrollment->status = $request->status;
            $messageParts[] = "status to {$request->status}";
        }

        // Update comment
        if ($request->filled('comment') && $request->comment !== $enrollment->comment) {
            $enrollment->comment = $request->comment;
            $messageParts[] = "comment updated";
        }

        $enrollment->save();

        // Refund logic on rejection
        if ($request->status === 'rejected' && $oldStatus !== 'rejected') {
            $modificationFieldId = $enrollment->modification_field_id;
            $user = User::find($enrollment->user_id);

            if (!$user) {
                throw new \Exception('User not found.');
            }
            if (!$modificationFieldId) {
                throw new \Exception('Modification field ID is missing.');
            }

            $modField = ModificationField::find($modificationFieldId);
            if (!$modField) {
                throw new \Exception('Modification field not found.');
            }

            $fieldCode = $modField->service_id;
            if (!$fieldCode) {
                throw new \Exception('Field code is missing in modification field.');
            }

            $role = strtolower($user->role ?? 'default');

            // Get service price for this role or use base price
            $servicePrice = DB::table('service_prices')
                ->where('service_id', $fieldCode)
                ->where('user_type', $role)
                ->value('price');

            $basePrice = $servicePrice ?? $modField->base_price;

            if (!$basePrice) {
                throw new \Exception("No price found for role '{$role}' and field code '{$fieldCode}', and base price is missing.");
            }

            // Refund 80% logic
            $refundAmount = round($basePrice * 0.8, 2);
            $debitAmount = round($basePrice * 0.2, 2);

            $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();
            if (!$wallet) {
                throw new \Exception('Wallet not found for user.');
            }

            $wallet->wallet_balance += $refundAmount;
            $wallet->save();

            Transaction::create([
                'transaction_ref' => strtoupper(Str::random(12)),
                'user_id' => $user->id,
                'performed_by' => Auth::user()->first_name . ' ' . (Auth::user()->last_name ?? ''),
                'amount' => $refundAmount,
                'fee' => 0.00,
                'net_amount' => $refundAmount,
                'description' => "Refund 80% for rejected service [{$modField->field_name}], Enrollment ID #{$enrollment->id}",
                'type' => 'refund',
                'status' => 'completed',
                'metadata' => json_encode([
                    'service_id' => $fieldCode,
                    'field_name' => $modField->field_name ?? null,
                    'user_role' => $role,
                    'base_price' => $basePrice,
                    'percentage_refunded' => 80,
                    'amount_debited_by_system' => $debitAmount,
                ]),
            ]);

            $messageParts[] = "80% refund issued";
        }

        DB::commit();

        $successMessage = $messageParts
            ? 'Successfully updated ' . implode(', ', $messageParts)
            : 'No changes were made';

        return redirect()->route('crmreg.index')->with('successMessage', $successMessage);

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('crmreg.index')->with('errorMessage', 'Failed to update: ' . $e->getMessage());
    }
}
}