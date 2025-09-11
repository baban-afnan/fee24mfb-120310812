<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BVNmodification;
use App\Models\User;
use App\Models\ModificationField;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BVNmodController extends Controller
{
    /**
     * List BVN modifications with filters and pagination
     */
    public function index(Request $request)
    {
        $searchbvn = $request->input('search_bvn');
        $statusFilter = $request->input('status');

        $query = BVNmodification::query();

        if ($searchbvn) {
            $query->where('bvn', 'like', "%$searchbvn%");
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        // ⚡ Apply custom status order + submission_date
        $enrollments = $query
            ->orderByRaw("CASE status
                WHEN 'pending' THEN 1
                WHEN 'processing' THEN 2
                WHEN 'query' THEN 3
                WHEN 'resolved' THEN 4
                WHEN 'rejected' THEN 5
                WHEN 'remark' THEN 6
                ELSE 999 END")
            ->orderByDesc('submission_date')
            ->paginate(10);

        $statusCounts = [
            'pending'    => BVNmodification::where('status', 'pending')->count(),
            'processing' => BVNmodification::where('status', 'processing')->count(),
            'resolved'   => BVNmodification::where('status', 'resolved')->count(),
            'rejected'   => BVNmodification::where('status', 'rejected')->count(),
        ];

        return view('bvnmod', compact('enrollments', 'searchbvn', 'statusFilter', 'statusCounts'));
    }

    /**
     * Show details of a single BVN modification
     */
    public function show($id)
    {
        $enrollmentInfo = BVNmodification::findOrFail($id);
        $user = User::find($enrollmentInfo->user_id);

        $statusHistory = collect([
            [
                'status' => $enrollmentInfo->status,
                'comment' => $enrollmentInfo->comment,
                'submission_date' => $enrollmentInfo->created_at,
                'updated_at' => $enrollmentInfo->updated_at,
            ]
        ]);

        return view('bvnmod-view', compact('enrollmentInfo', 'statusHistory', 'user'));
    }

    /**
     * Update the status of a BVN modification
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,resolved,rejected,query,remark',
            'comment' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $enrollment = BVNmodification::findOrFail($id);
            $oldStatus = $enrollment->status;

            $enrollment->status = $request->status;
            $enrollment->comment = $request->comment;
            $enrollment->save();

            // Handle refund logic if rejected
            if ($request->status === 'rejected' && $oldStatus !== 'rejected') {
                $this->processRefund($enrollment);
            }

            DB::commit();
            return redirect()->route('bvnmod.index')
                ->with('successMessage', 'Status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('bvnmod.index')
                ->with('errorMessage', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Handle refund when an enrollment is rejected
     */
    private function processRefund($enrollment)
    {
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

        // ✅ Check if refund already exists
        $refundExists = Transaction::where('type', 'refund')
            ->where('description', 'LIKE', "%Enrollment ID #{$enrollment->id}%")
            ->exists();

        if ($refundExists) {
            throw new \Exception('Refund already processed for this enrollment.');
        }

        // Fetch price for role, fallback to base price
        $servicePrice = DB::table('service_prices')
            ->where('service_id', $serviceId)
            ->where('user_type', $role)
            ->value('price');

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

        // Update wallet balance
        $wallet->wallet_balance += $refundAmount;
        $wallet->save();

        // Create refund transaction
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
}
