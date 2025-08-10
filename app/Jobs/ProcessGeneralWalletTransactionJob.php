<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessGeneralWalletTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type, $amount, $description, $userIds, $performedBy, $adminId;

    public function __construct($type, $amount, $description, $userIds, $adminUser)
    {
        $this->type = $type;
        $this->amount = $amount;
        $this->description = $description ?? ucfirst($type) . ' Wallet';
        $this->userIds = $userIds;
        $this->performedBy = $adminUser ? $adminUser->first_name . ' ' . $adminUser->last_name : 'System';
        $this->adminId = $adminUser ? $adminUser->id : null;
    }

    public function handle()
    {
        foreach ($this->userIds as $userId) {
            DB::beginTransaction();

            try {
                $user = User::find($userId);

                if (!$user) {
                    DB::rollBack();
                    continue;
                }

                $wallet = Wallet::firstOrCreate(
                    ['user_id' => $user->id],
                    ['wallet_balance' => 0.00]
                );

                $oldBalance = $wallet->wallet_balance;

                // Skip if debit and insufficient balance
                if ($this->type === 'debit' && $wallet->wallet_balance < $this->amount) {
                    DB::rollBack();
                    continue;
                }

                // Adjust wallet balance
                $wallet->wallet_balance += $this->type === 'credit' ? $this->amount : -$this->amount;
                $wallet->save();

                // Generate unique transaction reference
                $transactionRef = 'MFD-' . (time() % 1000000000) . '-' . mt_rand(100, 999);

                // Save transaction
                Transaction::create([
                    'transaction_ref' => $transactionRef,
                    'user_id' => $user->id,
                    'amount' => $this->amount,
                    'fee' => 0.00,
                    'net_amount' => $this->amount,
                    'description' => $this->description,
                    'type' => $this->type,
                    'status' => 'completed',
                    'performed_by' => $this->performedBy,
                    'metadata' => json_encode([
                        'manual_funding' => true,
                        'admin_id' => $this->adminId,
                        'old_balance' => $oldBalance,
                        'new_balance' => $wallet->wallet_balance,
                        'performed_by' => $this->performedBy,
                    ]),
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                // You may want to log this
            }
        }
    }
}
