<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    /**
     * Show all transactions with filters + pagination
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Filters
        $search     = $request->input('search');
        $recordType = $request->input('type');
        $status     = $request->input('status');
        $fromDate   = $request->input('start_date');
        $toDate     = $request->input('end_date');

        // 1. Base Query for Stats (Apply Search & Date only, IGNORE Type/Status for cards usually, but depends on requirement. 
        //    Actually, often stats should reflect the search/date range, but maybe not type/status specific if cards break them down.
        //    However, usually we want stats to reflect the "current view" or just date range. 
        //    Let's keep stats filtering by Search and Date as before.
        $statsQuery = Transaction::with('user');

        if ($search) {
            $statsQuery->where(function($q) use ($search) {
                $q->where('transaction_ref', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('email', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($fromDate && $toDate) {
            $statsQuery->whereBetween('created_at', [$fromDate, $toDate]);
        } elseif ($fromDate) {
            $statsQuery->whereDate('created_at', '>=', $fromDate);
        } elseif ($toDate) {
            $statsQuery->whereDate('created_at', '<=', $toDate);
        }

        // Calculate Stats for Cards (Clone query to avoid mutating for next calc)
        $totalCredits = (clone $statsQuery)->where('type', 'credit')->sum('amount');
        $totalDebits  = (clone $statsQuery)->where('type', 'debit')->sum('amount');
        $totalRefunds = (clone $statsQuery)->where('type', 'refund')->sum('amount');

        // New Stats for Dashboard
        $totalVolume = (clone $statsQuery)->sum('amount');
        $totalCount  = (clone $statsQuery)->count();
        
        // 2. Query for Table List (Apply Search, Date, AND Type, AND Status)
        $listQuery = (clone $statsQuery); // Start with the same date/search filters

        if ($recordType && $recordType !== 'all') {
            $listQuery->where('type', $recordType);
        }

        if ($status && $status !== 'all') {
            $listQuery->where('status', $status);
        }
        
        $transactions = $listQuery->orderBy('created_at', 'desc')->paginate(10);

        // 3. Admin Wallet Balance (Total credits - Total debits of current filter maybe, or just 0 since it's admin viewing all)
        // Or if you want to show the current logged in admin's balance, keep it.
        $walletBalance = $user->wallet ? $user->wallet->balance : 0.00;

        return view('transactions', [
            'transactions' => $transactions,
            'walletBalance' => $walletBalance,
            'totalCredits' => $totalCredits,
            'totalDebits'  => $totalDebits,
            'totalRefunds' => $totalRefunds,
            'totalVolume'  => $totalVolume,
            'totalCount'   => $totalCount,
            'search'       => $search,
            'recordType'   => $recordType,
            'fromDate'     => $fromDate,
            'toDate'       => $toDate,
        ]);
    }

    /**
     * Export transactions to PDF (all transactions)
     */
    public function exportPdf(Request $request)
    {
        // Filter logic
        $transactionsQuery = Transaction::query();

        if ($request->filled('search_transaction_id')) {
            $transactionsQuery->where('transaction_ref', 'like', '%' . $request->input('search_transaction_id') . '%');
        }

        if ($request->filled('record_type')) {
            $transactionsQuery->where('type', $request->input('record_type'));
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $transactionsQuery->whereBetween('created_at', [$request->input('from_date'), $request->input('to_date')]);
        } elseif ($request->filled('from_date')) {
            $transactionsQuery->whereDate('created_at', '>=', $request->input('from_date'));
        } elseif ($request->filled('to_date')) {
            $transactionsQuery->whereDate('created_at', '<=', $request->input('to_date'));
        }

        $transactions = $transactionsQuery->latest()->get();

        // Balance summary
        $totalAmount = $transactions->sum(function ($txn) {
            return $txn->type === 'credit' ? $txn->amount : -$txn->amount;
        });

        // Generate PDF
        $pdf = Pdf::loadView('pdf', [
            'transactions' => $transactions,
            'totalAmount'  => $totalAmount,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('transaction_history.pdf');
    }
}
