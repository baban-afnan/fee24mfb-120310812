<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\validation;
use App\Models\User;
use App\Models\ModificationField;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ValidationController extends Controller
{
    public function index(Request $request)
    {
        $searchnin = $request->input('search_nin');
        $statusFilter = $request->input('status');

        $query = validation::query();

        if ($searchnin) {
            $query->where('nin', 'like', "%$searchnin%");
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $enrollments = $query->orderByDesc('submission_date')->paginate(10);

        $statusCounts = [
            'pending' => validation::where('status', 'pending')->count(),
            'processing' => validation::where('status', 'processing')->count(),
            'resolved' => validation::where('status', 'resolved')->count(),
            'rejected' => validation::where('status', 'rejected')->count(),
        ];

        return view('validation', compact('enrollments', 'searchnin', 'statusFilter', 'statusCounts'));
    }

    public function show($id)
    {
        $enrollmentInfo = validation::findOrFail($id);
        $user = User::find($enrollmentInfo->user_id);

        $statusHistory = collect([
            [
                'status' => $enrollmentInfo->status,
                'comment' => $enrollmentInfo->comment,
                'submission_date' => $enrollmentInfo->created_at,
                'updated_at' => $enrollmentInfo->updated_at,
            ]
        ]);

        return view('validation-view', compact('enrollmentInfo', 'statusHistory', 'user'));
    }

   public function update(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,processing,resolved,rejected',
        'comment' => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {
        $enrollment = validation::findOrFail($id);
        $oldStatus = $enrollment->status;

        $enrollment->status = $request->status;
        $enrollment->comment = $request->comment;
        $enrollment->save();

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

            $serviceId = $modField->service_id;
            $role = strtolower($user->role ?? 'default');

            // ✅ Check if refund already exists for this enrollment
            $refundExists = Transaction::where('type', 'refund')
                ->where('description', 'LIKE', "%Enrollment ID #{$enrollment->id}%")
                ->exists();

            if ($refundExists) {
                throw new \Exception('Refund already processed for this enrollment.');
            }

            // Try to fetch role-specific price
            $servicePrice = DB::table('service_prices')
                ->where('service_id', $serviceId)
                ->where('user_type', $role)
                ->value('price');

            // Fall back to base price if no role-specific price found
            $basePrice = $servicePrice ?: $modField->base_price;

            if (!$basePrice || $basePrice <= 0) {
                throw new \Exception('No valid price found for refund.');
            }

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
                    'service_id' => $serviceId,
                    'field_name' => $modField->field_name ?? null,
                    'user_role' => $role,
                    'base_price' => $basePrice,
                    'percentage_refunded' => 80,
                    'amount_debited_by_system' => $debitAmount,
                ]),
            ]);
        }

        DB::commit();
        return redirect()->route('validation.index')->with('successMessage', 'Status updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('validation.index')->with('errorMessage', 'Failed to update status: ' . $e->getMessage());
    }
}

}
