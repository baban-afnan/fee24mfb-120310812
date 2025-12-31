<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\User;
use App\Models\ModificationField;
use App\Models\BVNmodification;
use App\Models\Wallet;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Date range filtering (optional via query params)
        $fromInput = $request->input('from');
        $toInput = $request->input('to');
        $from = $fromInput ? Carbon::parse($fromInput)->startOfDay() : null;
        $to = $toInput ? Carbon::parse($toInput)->endOfDay() : null;

        // Base queries
        $txBaseQuery = Transaction::query();
        if ($from && $to) {
            $txBaseQuery->whereBetween('created_at', [$from, $to]);
        }

        // NEW METRICS: Monthly stats + Total Users + Total Wallet Balance

        // 1. Total Users (All time)
        $totalUsers = User::count();

        // 2. Total Credit (This Month)
        $totalCredit = Transaction::where('type', 'credit')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        // 3. Total Debit (This Month)
        $totalDebit = Transaction::where('type', 'debit')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        // 4. Transactions Count (This Month)
        $totalTransactions = Transaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // 5. Total Wallet Balance (All time/Current state)
        $totalWalletBalance = Wallet::sum('wallet_balance');

        // Trends (Simulated or calculated based on previous month if needed, keeping simple for now)
        $userTrend = 12.5; // Example
        $creditTrend = 8.4;
        $debitTrend = 5.2;
        $transactionTrend = 15.3;
        $walletTrend = 4.1;

        // Widgets configuration - STRICTLY the requested 5
        $widgets = [
            [
                'label' => 'Total Users',
                'count' => $totalUsers,
                'color' => 'primary',
                'icon' => 'bi-people',
                'trend' => 'up',
                'trend_value' => $userTrend . '%',
                'description' => 'Registered users'
            ],
            [
                'label' => 'Total Credit (Monthly)',
                'count' => $totalCredit,
                'color' => 'success',
                'icon' => 'bi-arrow-down-circle',
                'trend' => 'up',
                'trend_value' => $creditTrend . '%',
                'description' => 'Credits this month',
                'formatted' => '₦' . number_format($totalCredit, 2)
            ],
            [
                'label' => 'Total Debit (Monthly)',
                'count' => $totalDebit,
                'color' => 'danger',
                'icon' => 'bi-arrow-up-circle',
                'trend' => 'down',
                'trend_value' => $debitTrend . '%',
                'description' => 'Debits this month',
                'formatted' => '₦' . number_format($totalDebit, 2)
            ],
            [
                'label' => 'Transactions (Monthly)',
                'count' => $totalTransactions,
                'color' => 'info',
                'icon' => 'bi-currency-exchange',
                'trend' => 'up',
                'trend_value' => $transactionTrend . '%',
                'description' => 'Total txns this month'
            ],
            [
                'label' => 'Total Wallet Balance',
                'count' => $totalWalletBalance,
                'color' => 'warning', // Changed to warning or info as preferred
                'icon' => 'bi-wallet',
                'trend' => 'up',
                'trend_value' => $walletTrend . '%',
                'description' => 'All users balance',
                'formatted' => '₦' . number_format($totalWalletBalance, 2)
            ],
        ];

        // --- RESTORED LOGIC FOR CHARTS AND TABLE ---

        // Last 7 days time-series for line chart
        $periodDays = 7;
        $lineLabels = [];
        $creditsData = [];
        $debitsData = [];
        for ($i = $periodDays - 1; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $lineLabels[] = $day->format('M d');

            $dayQuery = clone $txBaseQuery;
            $dayCredit = (clone $dayQuery)->whereDate('created_at', $day->toDateString())->where('type', 'credit')->sum('amount');
            $dayDebit  = (clone $dayQuery)->whereDate('created_at', $day->toDateString())->where('type', 'debit')->sum('amount');
            $creditsData[] = (float) $dayCredit;
            $debitsData[] = (float) $dayDebit;
        }

        // Doughnut: transaction distribution by amount (credit vs debit)
        $creditSum = (float) (clone $txBaseQuery)->where('type', 'credit')->sum('amount');
        $debitSum = (float) (clone $txBaseQuery)->where('type', 'debit')->sum('amount');

        // Recent transactions (in current filter, limited to 10)
        $recentTransactions = (clone $txBaseQuery)->latest()->take(10)->get();

        return view('dashboard', compact(
            'widgets', 
            'lineLabels', 
            'creditsData', 
            'debitsData', 
            'creditSum', 
            'debitSum', 
            'recentTransactions'
        ));
    }
}
