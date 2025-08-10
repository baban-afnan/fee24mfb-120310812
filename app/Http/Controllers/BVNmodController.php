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

        $enrollments = $query->orderByDesc('submission_date')->paginate(15);

        $statusCounts = [
            'pending'    => BVNmodification::where('status', 'pending')->count(),
            'processing' => BVNmodification::where('status', 'processing')->count(),
            'resolved'   => BVNmodification::where('status', 'resolved')->count(),
            'rejected'   => BVNmodification::where('status', 'rejected')->count(),
        ];

        return view('bvnmod', compact('enrollments', 'searchbvn', 'statusFilter', 'statusCounts'));
    }

    public function show($id)
    {
        // Load modification field and related service
        $enrollmentInfo = BVNmodification::with(['modificationField.service'])->findOrFail($id);
        $user = User::find($enrollmentInfo->user_id);

        // Extract names
        $fieldName = $enrollmentInfo->modificationField->field_name ?? null;
        $serviceName = $enrollmentInfo->modificationField->service->name ?? null;

        $statusHistory = collect([
            [
                'status'          => $enrollmentInfo->status,
                'comment'         => $enrollmentInfo->comment,
                'submission_date' => $enrollmentInfo->created_at,
                'updated_at'      => $enrollmentInfo->updated_at,
            ]
        ]);

        return view('bvnmod-view', compact('enrollmentInfo', 'statusHistory', 'user', 'fieldName', 'serviceName'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status'  => 'required|in:pending,processing,resolved,rejected',
            'comment' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $enrollment = BVNmodification::findOrFail($id);
            $oldStatus = $enrollment->status;

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

                $modField = ModificationField::with('service')->find($modificationFieldId);
                if (!$modField) {
                    throw new \Exception('Modification field not found.');
                }

                $serviceId = $modField->service_id;
                if (!$serviceId) {
                    throw new \Exception('Service ID is missing in modification field.');
                }

                $role = strtolower($user->role ?? 'default');

                // Try to get price for this role and service_id
                $servicePrice = DB::table('service_prices')
                    ->where('service_id', $serviceId)
                    ->where('user_type', $role)
                    ->value('price');

                $basePrice = $servicePrice ?? $modField->base_price;

                if (!$basePrice) {
                    throw new \Exception("No price found for role '{$role}' and service ID '{$serviceId}', and base price is also missing.");
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
                    'user_id'         => $user->id,
                    'performed_by'    => Auth::user()->first_name . ' ' . (Auth::user()->last_name ?? ''),
                    'amount'          => $refundAmount,
                    'fee'             => 0.00,
                    'net_amount'      => $refundAmount,
                    'description'     => "Refund 80% for rejected service [{$modField->field_name} - {$modField->service->name}], Enrollment ID #{$enrollment->id}",
                    'type'            => 'refund',
                    'status'          => 'completed',
                    'metadata'        => json_encode([
                        'service_id'                => $serviceId,
                        'service_name'              => $modField->service->name ?? null,
                        'field_name'                => $modField->field_name ?? null,
                        'user_role'                 => $role,
                        'base_price'                => $basePrice,
                        'percentage_refunded'       => 80,
                        'amount_debited_by_system'  => $debitAmount,
                    ]),
                ]);
            }

            DB::commit();
            return redirect()->route('bvnmod.index')->with('successMessage', 'Status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('bvnmod.index')->with('errorMessage', 'Failed to update status: ' . $e->getMessage());
        }
    }
}
