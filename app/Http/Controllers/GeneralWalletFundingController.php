<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Jobs\ProcessGeneralWalletTransactionJob;
use Illuminate\Http\Request;

class GeneralWalletFundingController extends Controller
{
    public function showForm()
    {
        return view('general-funding');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'transaction_type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $type = $request->transaction_type;
        $amount = $request->amount;

        $users = User::with('wallet')->get();

        // Filter only for debits — for credit, all users are eligible
        if ($type === 'debit') {
            $eligibleUsers = $users->filter(function ($user) use ($amount) {
                $balance = $user->wallet->wallet_balance ?? 0;
                return $balance >= $amount;
            });
        } else {
            $eligibleUsers = $users;
        }

        session([
            'wallet_transaction_preview' => [
                'type' => $type,
                'amount' => $amount,
                'description' => $request->description,
                'eligible_user_ids' => $eligibleUsers->pluck('id')->toArray(),
            ]
        ]);

        return view('general-funding-preview', [
            'type' => $type,
            'amount' => $amount,
            'description' => $request->description,
            'eligibleUsers' => $eligibleUsers,
            'skippedCount' => $users->count() - $eligibleUsers->count(),
        ]);
    }

    public function queue(Request $request)
    {
        $data = session('wallet_transaction_preview');

        if (!$data) {
            return redirect()->route('general.funding.form')->with('error', 'No transaction data found. Please preview again.');
        }

        dispatch(new ProcessGeneralWalletTransactionJob(
            $data['type'],
            $data['amount'],
            $data['description'],
            $data['eligible_user_ids'],
            auth()->user()
        ));

        session()->forget('wallet_transaction_preview');

        return redirect()->route('general.funding.form')->with('success', 'Wallet ' . $data['type'] . ' has been queued for processing.');
    }
}
