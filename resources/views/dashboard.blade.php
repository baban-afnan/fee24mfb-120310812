<x-app-layout>
    @php
        
        // Date range filtering (optional via query params)
        $fromInput = request('from');
        $toInput = request('to');
        $from = $fromInput ? \Carbon\Carbon::parse($fromInput)->startOfDay() : null;
        $to = $toInput ? \Carbon\Carbon::parse($toInput)->endOfDay() : null;

        // Base queries
        $txBaseQuery = \App\Models\Transaction::query();
        if ($from && $to) {
            $txBaseQuery->whereBetween('created_at', [$from, $to]);
        }

        // Metrics (some filtered by range, some global)
        $totalUsers = \App\Models\User::count();
        $totalCredit = (clone $txBaseQuery)->where('type', 'credit')->sum('amount');
        $totalDebit = (clone $txBaseQuery)->where('type', 'debit')->sum('amount');
        $totalTransactions = (clone $txBaseQuery)->count();
        $activeServices = \App\Models\ModificationField::where('is_active', 1)->count();
        $bvnModifications = \App\Models\BVNmodification::count();
        $avgTransaction = (float) (clone $txBaseQuery)->avg('amount') ?: 0;

        // Trends (placeholder logic)
        $userTrend = ($totalUsers > 0) ? round(($totalUsers / ($totalUsers + rand(10, 50))) * 100, 2) : 0;
        $creditTrend = ($totalCredit > 0) ? round(($totalCredit / ($totalCredit + rand(1000, 5000))) * 100, 2) : 0;
        $debitTrend = ($totalDebit > 0) ? round(($totalDebit / ($totalDebit + rand(1000, 5000))) * 100, 2) : 0;
        $transactionTrend = ($totalTransactions > 0) ? round(($totalTransactions / ($totalTransactions + rand(50, 200))) * 100, 2) : 0;
        $serviceTrend = ($activeServices > 0) ? round(($activeServices / ($activeServices + rand(2, 5))) * 100, 2) : 0;
        $bvnTrend = ($bvnModifications > 0) ? round(($bvnModifications / ($bvnModifications + rand(5, 15))) * 100, 2) : 0;

        // Last 7 days time-series for line chart (based on filter if provided, else last 7 days)
        $periodDays = 7;
        $lineLabels = [];
        $creditsData = [];
        $debitsData = [];
        for ($i = $periodDays - 1; $i >= 0; $i--) {
            $day = \Carbon\Carbon::today()->subDays($i);
            $lineLabels[] = $day->format('M d');

            $dayQuery = clone $txBaseQuery;
            // If no range provided, restrict to this day; if range provided, still show the last 7 calendar days
            $dayCredit = (clone $dayQuery)->whereDate('created_at', $day->toDateString())->where('type', 'credit')->sum('amount');
            $dayDebit  = (clone $dayQuery)->whereDate('created_at', $day->toDateString())->where('type', 'debit')->sum('amount');
            $creditsData[] = (float) $dayCredit;
            $debitsData[] = (float) $dayDebit;
        }

        // Doughnut: transaction distribution by amount (credit vs debit)
        $creditSum = (float) (clone $txBaseQuery)->where('type', 'credit')->sum('amount');
        $debitSum = (float) (clone $txBaseQuery)->where('type', 'debit')->sum('amount');

        // Recent transactions (in current filter)
        $recentTransactions = (clone $txBaseQuery)->latest()->take(10)->get();

        // Widgets configuration
        $widgets = [
            [
                'label' => 'Total Users',
                'count' => $totalUsers,
                'color' => 'primary',
                'icon' => 'bi-people',
                'trend' => $userTrend > 50 ? 'up' : 'down',
                'trend_value' => $userTrend . '%',
                'description' => 'Registered users'
            ],
            [
                'label' => 'Total Credit',
                'count' => $totalCredit,
                'color' => 'success',
                'icon' => 'bi-arrow-down-circle',
                'trend' => $creditTrend > 50 ? 'up' : 'down',
                'trend_value' => $creditTrend . '%',
                'description' => 'In selected range',
                'formatted' => '₦' . number_format($totalCredit, 2)
            ],
            [
                'label' => 'Total Debit',
                'count' => $totalDebit,
                'color' => 'danger',
                'icon' => 'bi-arrow-up-circle',
                'trend' => $debitTrend > 50 ? 'up' : 'down',
                'trend_value' => $debitTrend . '%',
                'description' => 'In selected range',
                'formatted' => '₦' . number_format($totalDebit, 2)
            ],
            [
                'label' => 'Transactions',
                'count' => $totalTransactions,
                'color' => 'info',
                'icon' => 'bi-currency-exchange',
                'trend' => $transactionTrend > 50 ? 'up' : 'down',
                'trend_value' => $transactionTrend . '%',
                'description' => 'In selected range'
            ],
            [
                'label' => 'Active Services',
                'count' => $activeServices,
                'color' => 'warning',
                'icon' => 'bi-gear',
                'trend' => $serviceTrend > 50 ? 'up' : 'down',
                'trend_value' => $serviceTrend . '%',
                'description' => 'Available services'
            ],
            [
                'label' => 'BVN Modifications',
                'count' => $bvnModifications,
                'color' => 'secondary',
                'icon' => 'bi-credit-card',
                'trend' => $bvnTrend > 50 ? 'up' : 'down',
                'trend_value' => $bvnTrend . '%',
                'description' => 'Total requests'
            ],
            [
                'label' => 'Avg Tx Amount',
                'count' => $avgTransaction,
                'color' => 'primary',
                'icon' => 'bi-cash-coin',
                'trend' => 'up',
                'trend_value' => '—',
                'description' => 'In selected range',
                'formatted' => '₦' . number_format($avgTransaction, 2)
            ],
        ];
    @endphp

    <div class="support-dashboard">
        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card elegant-shadow border-0">
                        <div class="card-header bg-transparent pb-3">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div class="mb-2 mb-md-0">
                                    <h3 class="mb-1"><i class="bi bi-graph-up me-2 text-gradient-primary"></i>Fee24mfb Analytics Dashboard</h3>
                                    <p class="text-muted mb-0">Interactive metrics, charts, and recent activity</p>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <button id="refreshBtn" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrow-clockwise"></i> Refresh
                                    </button>
                                    <button id="exportCsvBtn" class="btn btn-sm btn-primary">
                                        <i class="bi bi-download"></i> Export CSV
                                    </button>
                                    <a href="{{route('enrollments.index')}}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-upload"></i> Upload Enrollment
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                @foreach ($widgets as $index => $widget)
                                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6">
                                        <div class="stat-card hover-lift bg-white rounded-4 overflow-hidden h-100">
                                            <div class="card-body p-4 position-relative">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <h6 class="text-uppercase text-muted mb-1 fw-semibold small">{{ $widget['label'] }}</h6>
                                                        <h2 class="fw-bold counter mb-0" id="counter-{{ $index }}" data-target="{{ $widget['count'] }}">
                                                            @isset($widget['formatted'])
                                                                {{ $widget['formatted'] }}
                                                            @else
                                                                {{ number_format((float) $widget['count']) }}
                                                            @endisset
                                                        </h2>
                                                        <p class="text-muted small mb-0">{{ $widget['description'] }}</p>
                                                    </div>
                                                    <div class="stat-icon bg-soft-{{ $widget['color'] }} text-{{ $widget['color'] }} rounded-3 p-3 fs-4">
                                                        <i class="bi {{ $widget['icon'] }}"></i>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center mt-3">
                                                    <span class="badge bg-soft-{{ $widget['color'] }} text-{{ $widget['color'] }} me-2">
                                                        <i class="bi bi-graph-{{ $widget['trend'] }}-{{ $widget['trend'] == 'up' ? 'up' : 'down' }} me-1"></i>
                                                        {{ $widget['trend_value'] }}
                                                    </span>
                                                    <span class="text-muted small">vs last period</span>
                                                </div>
                                                <div class="progress mt-3" style="height: 6px;">
                                                    <div class="progress-bar bg-{{ $widget['color'] }} progress-animate" role="progressbar" style="width: 0%;" aria-valuenow="{{ $widget['trend'] == 'up' ? rand(60, 90) : rand(30, 50) }}" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <div class="position-absolute bottom-0 end-0 wave-effect">
                                                    <svg viewBox="0 0 100 40" class="wave-svg">
                                                        <path fill="rgba(var(--bs-{{ $widget['color'] }}-rgb), 0.05)" d="M0,20 Q25,30 50,20 T100,20 V40 H0 Z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8">
                    <div class="card elegant-shadow border-0 h-100">
                        <div class="card-header bg-transparent border-0 pb-2">
                            <h5 class="mb-0"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Transaction Analytics</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="chart-container" style="height: 320px;">
                                <canvas id="transactionChart"
                                        data-labels='@json($lineLabels)'
                                        data-credits='@json($creditsData)'
                                        data-debits='@json($debitsData)'></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card elegant-shadow border-0 h-100">
                        <div class="card-header bg-transparent border-0 pb-2">
                            <h5 class="mb-0"><i class="bi bi-pie-chart me-2 text-info"></i>Transaction Mix</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="chart-container" style="height: 320px;">
                                <canvas id="transactionTypeChart"
                                        data-creditsum='{{ $creditSum }}'
                                        data-debitsum='{{ $debitSum }}'></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card elegant-shadow border-0">
                        <div class="card-header bg-transparent border-0 pb-2 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-clock-history me-2 text-secondary"></i>Recent Transactions</h5>
                            <span class="text-muted small">Latest 10 in current filter</span>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table id="recentTxTable" class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentTransactions as $tx)
                                            <tr>
                                                <td>#{{ $tx->id }}</td>
                                                <td>
                                                    @php $t = strtolower((string) ($tx->type ?? '')); @endphp
                                                    <span class="badge rounded-pill bg-{{ $t === 'credit' ? 'success' : ($t === 'debit' ? 'danger' : 'secondary') }}">
                                                        {{ ucfirst($tx->type ?? 'n/a') }}
                                                    </span>
                                                </td>
                                                <td>₦{{ number_format((float) ($tx->amount ?? 0), 2) }}</td>
                                                <td>{{ optional($tx->created_at)->format('Y-m-d H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No transactions found for the selected range.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modal.notification')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Refresh
            const refreshBtn = document.getElementById('refreshBtn');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() { window.location.reload(); });
            }

            // Animate counters with formatting
            const counters = document.querySelectorAll(".counter");
            const animationDuration = 1500; // shorter for snappier UI
            const frameDuration = 1000 / 60; // 60 fps

            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const isCurrency = (counter.textContent || '').trim().startsWith('₦');
                const totalFrames = Math.round(animationDuration / frameDuration);
                let frame = 0;

                const updateCounter = () => {
                    frame++;
                    const progress = Math.min(frame / totalFrames, 1);
                    const currentCount = Math.round(target * progress);
                    counter.textContent = isCurrency
                        ? '₦' + currentCount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                        : currentCount.toLocaleString();
                    if (frame < totalFrames) requestAnimationFrame(updateCounter);
                };
                requestAnimationFrame(updateCounter);
            });

            // Animate progress bars
            const progressBars = document.querySelectorAll(".progress-animate");
            progressBars.forEach(bar => {
                const targetWidth = bar.getAttribute("aria-valuenow") + "%";
                bar.style.width = targetWidth;
                bar.classList.add("progress-bar-animated");
                setTimeout(() => bar.classList.remove("progress-bar-animated"), 1000);
            });

            // Transaction Line Chart (dynamic from data attributes)
            const transactionCanvas = document.getElementById('transactionChart');
            if (transactionCanvas) {
                const labels = JSON.parse(transactionCanvas.dataset.labels || '[]');
                const credits = JSON.parse(transactionCanvas.dataset.credits || '[]');
                const debits = JSON.parse(transactionCanvas.dataset.debits || '[]');
                new Chart(transactionCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Credits',
                            data: credits,
                            borderColor: 'rgba(16, 185, 129, 1)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.35,
                            fill: true
                        }, {
                            label: 'Debits',
                            data: debits,
                            borderColor: 'rgba(239, 68, 68, 1)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.35,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = Number(context.raw || 0);
                                        return context.dataset.label + ': ₦' + value.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) { return '₦' + Number(value).toLocaleString(); }
                                }
                            }
                        }
                    }
                });
            }

            // Transaction Type Doughnut (credit vs debit by amount)
            const typeCanvas = document.getElementById('transactionTypeChart');
            if (typeCanvas) {
                const csum = Number(typeCanvas.dataset.creditsum || 0);
                const dsum = Number(typeCanvas.dataset.debitsum || 0);
                const total = csum + dsum;
                new Chart(typeCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Credit', 'Debit'],
                        datasets: [{
                            data: [csum, dsum],
                            backgroundColor: [
                                'rgba(16, 185, 129, 0.85)',
                                'rgba(239, 68, 68, 0.85)'
                            ],
                            borderColor: [
                                'rgba(16, 185, 129, 1)',
                                'rgba(239, 68, 68, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'right' },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const val = Number(context.raw || 0);
                                        const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                        return context.label + ': ₦' + val.toLocaleString() + ' (' + pct + '%)';
                                    }
                                }
                            }
                        },
                        cutout: '70%'
                    }
                });
            }

            // Export Recent Transactions table to CSV
            const exportBtn = document.getElementById('exportCsvBtn');
            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    const table = document.getElementById('recentTxTable');
                    if (!table) return;
                    const rows = Array.from(table.querySelectorAll('tr'));
                    const csv = rows.map(row => Array.from(row.querySelectorAll('th,td')).map(cell => '"' + (cell.innerText || '').replace(/"/g, '""') + '"').join(',')).join('\n');
                    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    const date = new Date().toISOString().slice(0,19).replace(/[:T]/g,'-');
                    link.download = `recent-transactions-${date}.csv`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(url);
                });
            }
        });
    </script>

    <style>
        .support-dashboard {
            --bs-primary-rgb: 99, 102, 241;
            --bs-secondary-rgb: 107, 114, 128;
            --bs-success-rgb: 16, 185, 129;
            --bs-info-rgb: 59, 130, 246;
            --bs-warning-rgb: 245, 158, 11;
            --bs-danger-rgb: 239, 68, 68;
        }
        .elegant-shadow { box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.06); border: 0 !important; border-radius: 0.75rem !important; }
        .stat-card { transition: all 0.3s ease; border: 1px solid rgba(0, 0, 0, 0.03); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02); overflow: hidden; }
        .stat-card:hover { box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); border-color: rgba(0, 0, 0, 0.05); }
        .stat-icon { transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; }
        .hover-lift:hover { transform: translateY(-5px); }
        .text-gradient-primary { background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .wave-effect { width: 100px; height: 40px; opacity: 0.7; bottom: -1px; right: -1px; overflow: hidden; }
        .wave-svg { width: 100%; height: 100%; animation: wave-animation 8s linear infinite; }
        @keyframes wave-animation { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .progress-bar { transition: width 1.5s ease-in-out; }
        .bg-soft-primary { background-color: rgba(var(--bs-primary-rgb), 0.1) !important; }
        .bg-soft-secondary { background-color: rgba(var(--bs-secondary-rgb), 0.1) !important; }
        .bg-soft-success { background-color: rgba(var(--bs-success-rgb), 0.1) !important; }
        .bg-soft-info { background-color: rgba(var(--bs-info-rgb), 0.1) !important; }
        .bg-soft-warning { background-color: rgba(var(--bs-warning-rgb), 0.1) !important; }
        .bg-soft-danger { background-color: rgba(var(--bs-danger-rgb), 0.1) !important; }
        .chart-container { position: relative; min-height: 250px; }
        .table > :not(caption) > * > * { padding: 0.85rem 0.75rem; }
        .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.02); }
        @media (max-width: 1199.98px) { .stat-icon { width: 42px; height: 42px; font-size: 1rem !important; } }
        @media (max-width: 767.98px) { .card-header .btn { margin-top: 0.5rem; width: 100%; } .stat-card { margin-bottom: 1rem; } .chart-container { min-height: 220px; } }
    </style>
</x-app-layout>
