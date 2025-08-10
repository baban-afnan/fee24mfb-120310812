<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletFundingController extends Controller
{
    public function showForm(Request $request)
    {
        $user = null;
        $wallet = null;
        $transactions = [];

        if ($request->filled('email') && str_ends_with($request->email, '.com')) {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                $wallet = Wallet::where('user_id', $user->id)->first();
                $transactions = Transaction::where('user_id', $user->id)->latest()->take(10)->get();
            }
        }

        return view('manual-funding', compact('user', 'wallet', 'transactions'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'transaction_type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['wallet_balance' => 0.00]
        );

        $amount = $request->amount;
        $type = $request->transaction_type;
        $description = $request->description ?? ucfirst($type) . ' Wallet';

        DB::beginTransaction();

        try {
            // Store old balance for metadata
            $oldBalance = $wallet->wallet_balance;

            // Apply credit or debit
            if ($type === 'credit') {
                $wallet->wallet_balance += $amount;
            } else {
                if ($wallet->wallet_balance < $amount) {
                    return back()->with('error', 'Insufficient wallet balance.');
                }
                $wallet->wallet_balance -= $amount;
            }


              

            $wallet->save();

             // Generate a unique reference
            $transactionRef = 'MFD-' . (time() % 1000000000) . '-' . mt_rand(100, 999);


            // Save transaction record
Transaction::create([
    'transaction_ref' => $transactionRef,
    'user_id' => $user->id,
    'amount' => $amount,
    'description' => $description,
    'type' => $type,
    'status' => 'completed',
    'metadata' => json_encode([
        'manual_funding' => true,
        'admin_id' => auth()->id() ?? null,
        'old_balance' => $oldBalance,
        'new_balance' => $wallet->wallet_balance,
        'performed_by' => auth()->check() ? auth()->user()->first_name . ' ' . auth()->user()->last_name : 'System',
    ]),
]);

            DB::commit();
           return back()->with('success', 'Wallet ' . $type . ' successful. Reference: ' . $transactionRef);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }
}
