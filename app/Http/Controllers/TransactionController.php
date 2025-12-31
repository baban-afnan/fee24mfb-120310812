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
        $search     = $request->input('search_transaction_id');
        $recordType = $request->input('record_type');
        $fromDate   = $request->input('from_date');
        $toDate     = $request->input('to_date');

        // 1. Base Query for Stats (Apply Search & Date only, IGNORE Type)
        //    This ensures cards show "Total Credits" for the selected date range, 
        //    even if the user is currently viewing the "Debits" tab.
        $statsQuery = Transaction::where('user_id', $user->id);

        if ($search) {
            $statsQuery->where('transaction_ref', 'like', "%{$search}%");
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
        
        // 2. Query for Table List (Apply Search, Date, AND Type)
        $listQuery = (clone $statsQuery); // Start with the same date/search filters

        if ($recordType) {
            $listQuery->where('type', $recordType);
        }

        $transactions = $listQuery->orderBy('created_at', 'desc')->paginate(10);

        // 3. User Wallet Balance (Actual Balance)
        $walletBalance = $user->wallet ? $user->wallet->balance : 0.00;

        return view('transactions', [
            'transactions' => $transactions,
            'walletBalance' => $walletBalance,
            'totalCredits' => $totalCredits,
            'totalDebits'  => $totalDebits,
            'totalRefunds' => $totalRefunds,
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
