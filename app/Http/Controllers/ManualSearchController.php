<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\bvnsearch;
use App\Models\User;
use App\Models\ModificationField;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ManualSearchController extends Controller
{
    public function index(Request $request)
    {
        $searchnumber = $request->input('search_number');
        $statusFilter = $request->input('status');

        $query = bvnsearch::query();

        if ($searchnumber) {
            $query->where('number', 'like', "%$searchnumber%");
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $enrollments = $query->orderByDesc('submission_date')->paginate(10);

        $statusCounts = [
            'pending' => bvnsearch::where('status', 'pending')->count(),
            'processing' => bvnsearch::where('status', 'processing')->count(),
            'resolved' => bvnsearch::where('status', 'resolved')->count(),
            'rejected' => bvnsearch::where('status', 'rejected')->count(),
        ];

        return view('bvnsearch', compact('enrollments', 'searchnumber', 'statusFilter', 'statusCounts'));
    }

    public function show($id)
    {
        $enrollmentInfo = bvnsearch::findOrFail($id);
        $user = User::find($enrollmentInfo->user_id);

        $statusHistory = collect([
            [
                'status' => $enrollmentInfo->status,
                'comment' => $enrollmentInfo->comment,
                'submission_date' => $enrollmentInfo->created_at,
                'updated_at' => $enrollmentInfo->updated_at,
            ]
        ]);

        return view('bvnsearch-view', compact('enrollmentInfo', 'statusHistory', 'user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,resolved,rejected',
            'comment' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $enrollment = bvnsearch::findOrFail($id);
            $oldStatus = $enrollment->status;

            // Update status and comment
            $enrollment->status = $request->status;
            $enrollment->comment = $request->comment;
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

                // Try to get price for this role and service_id
                $servicePrice = DB::table('service_prices')
                    ->where('service_id', $fieldCode)
                    ->where('user_type', $role)
                    ->value('price');

                // Fallback to base_price if no service price found
                $basePrice = $servicePrice ?? $modField->base_price;

                if (!$basePrice) {
                    throw new \Exception("No price found for role '{$role}' and field code '{$fieldCode}', and base price is also missing.");
                }

                // Refund 80% of the base price
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
            }

            DB::commit();
            return redirect()->route('bvnsearch.index')->with('successMessage', 'Status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('bvnsearch.index')->with('errorMessage', 'Failed to update status: ' . $e->getMessage());
        }
    }
}
