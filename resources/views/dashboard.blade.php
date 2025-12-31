<x-app-layout>

    <div class="support-dashboard">
        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card elegant-shadow border-0">
                        <div class="card-header bg-transparent pb-3">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <div class="mb-2 mb-md-0">
                                    <h3 class="mb-1"><i class="bi bi-graph-up me-2 text-gradient-primary"></i>BiyaNow Analytics Dashboard</h3>
                                    <p class="text-muted mb-0">Interactive metrics, charts, and recent activity</p>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <button id="refreshBtn" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrow-clockwise"></i> Refresh
                                    </button>
                                    <button id="exportCsvBtn" class="btn btn-sm btn-primary">
                                        <i class="bi bi-download"></i> Export CSV
                                    </button>

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach ($widgets as $index => $widget)
                                    <div class="col-xl col-md-6">
                                        <div class="card widget-card h-100 border-0 shadow-sm bg-gradient-{{ $widget['color'] }}">
                                            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                                <div>
                                                    <p class="text-white-50 mb-1 fw-bold fs-7 text-uppercase">{{ $widget['label'] }}</p>
                                                    <h4 class="text-white fw-bold mb-0 counter" id="counter-{{ $index }}" data-target="{{ $widget['count'] }}">
                                                        @isset($widget['formatted'])
                                                            {{ $widget['formatted'] }}
                                                        @else
                                                            {{ number_format((float) $widget['count']) }}
                                                        @endisset
                                                    </h4>
                                                </div>
                                                <div class="icon-box rounded-3 bg-white-20 d-flex align-items-center justify-content-center">
                                                    <i class="bi {{ $widget['icon'] }} text-white fs-4"></i>
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
                {{-- Redesigned Dashboard Content: Today's Tx & Daily Stats --}}
                <div class="row g-4 mt-2">
                    <!-- Left Column: Today's Transactions -->
                    <div class="col-xl-8">
                        <div class="card border-0 shadow-sm h-100 rounded-4">
                             <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold"><i class="ti ti-calendar-event me-2 text-primary"></i>Today's Transactions</h5>
                                <span class="badge bg-light text-dark border">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                             </div>
                             <div class="card-body p-0">
                                 <div class="table-responsive">
                                     <table class="table table-hover align-middle mb-0">
                                         <thead class="bg-light text-muted small text-uppercase">
                                             <tr>
                                                 <th class="ps-4">#</th>
                                                 <th>Ref ID</th>
                                                 <th>Type</th>
                                                 <th>Amount</th>
                                                 <th>Time</th>
                                                 <th class="text-end pe-4">Status</th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                            @forelse($todayTransactions as $index => $tx)
                                                <tr>
                                                    <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                                                    <td class="fw-bold text-dark">{{ Str::limit($tx->transaction_ref, 10) }}...</td>
                                                    <td>
                                                        @if($tx->type == 'credit')
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2">
                                                                <i class="ti ti-arrow-down-left me-1"></i>Credit
                                                            </span>
                                                        @elseif($tx->type == 'debit')
                                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2">
                                                                <i class="ti ti-arrow-up-right me-1"></i>Debit
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2">{{ ucfirst($tx->type) }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="fw-bold {{ $tx->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                                        {{ $tx->type == 'credit' ? '+' : '-' }}₦{{ number_format($tx->amount, 2) }}
                                                    </td>
                                                    <td class="text-muted">{{ $tx->created_at->format('h:i A') }}</td>
                                                    <td class="text-end pe-4">
                                                        @if($tx->status == 'completed' || $tx->status == 'success')
                                                            <span class="badge bg-success rounded-pill px-3">Success</span>
                                                        @elseif($tx->status == 'pending')
                                                            <span class="badge bg-warning text-white rounded-pill px-3">Pending</span>
                                                        @else
                                                            <span class="badge bg-danger rounded-pill px-3">Failed</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5 text-muted">
                                                        No transactions yet today.
                                                    </td>
                                                </tr>
                                            @endforelse
                                         </tbody>
                                     </table>
                                 </div>
                             </div>
                        </div>
                    </div>

                    <!-- Right Column: Daily Statistics -->
                    <div class="col-xl-4">
                        <div class="card border-0 shadow-sm h-100 rounded-4">
                            <div class="card-header bg-white border-0 py-3">
                                <h5 class="mb-0 fw-bold"><i class="ti ti-chart-pie me-2 text-dark"></i>Daily Statistics</h5>
                            </div>
                            <div class="card-body">
                                <!-- Donut Chart -->
                                <div class="position-relative d-flex justify-content-center align-items-center mb-4" style="height: 200px;">
                                    <canvas id="dailyStatsChart"></canvas>
                                    <div class="position-absolute text-center" style="pointer-events: none;">
                                        <div class="text-muted small">Total</div>
                                        <div class="h3 fw-bold mb-0">{{ $dailyStats['total'] }}</div>
                                    </div>
                                </div>

                                <!-- Stats Grid -->
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="p-3 rounded-3 text-center" style="background-color: #d1fae5;">
                                            <h4 class="fw-bold text-success mb-0">{{ $dailyStats['success'] }}</h4>
                                            <small class="text-success text-uppercase fw-bold" style="font-size: 0.7rem;">Success</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 rounded-3 text-center" style="background-color: #fef3c7;">
                                            <h4 class="fw-bold text-warning mb-0">{{ $dailyStats['pending'] }}</h4>
                                            <small class="text-warning text-uppercase fw-bold" style="font-size: 0.7rem;">Pending</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 rounded-3 text-center" style="background-color: #fee2e2;">
                                            <h4 class="fw-bold text-danger mb-0">{{ $dailyStats['failed'] }}</h4>
                                            <small class="text-danger text-uppercase fw-bold" style="font-size: 0.7rem;">Failed</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 rounded-3 text-center" style="background-color: #e0f2fe;">
                                            <h4 class="fw-bold text-info mb-0">{{ $dailyStats['refund'] }}</h4>
                                            <small class="text-info text-uppercase fw-bold" style="font-size: 0.7rem;">Refund</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const ctx = document.getElementById('dailyStatsChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Success', 'Pending', 'Failed', 'Refund'],
                                datasets: [{
                                    data: [
                                        {{ $dailyStats['success'] }}, 
                                        {{ $dailyStats['pending'] }}, 
                                        {{ $dailyStats['failed'] }},
                                        {{ $dailyStats['refund'] }}
                                    ],
                                    backgroundColor: [
                                        '#22c55e', // Success Green
                                        '#f59e0b', // Pending Warning
                                        '#ef4444', // Failed Danger
                                        '#3b82f6'  // Refund Info
                                    ],
                                    borderWidth: 0,
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                cutout: '75%',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                         callbacks: {
                                            label: function(context) {
                                                return ' ' + context.label + ': ' + context.raw;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>

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
        .widget-card {
            border-radius: 15px !important;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .bg-gradient-success {
            background: linear-gradient(135deg, #2af598 0%, #009efd 100%);
        }
        .bg-gradient-danger {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%); /* Adjusted red */
            background: linear-gradient(135deg, #ff512f 0%, #dd2476 100%);
        }
        .bg-gradient-info {
            background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
        }
        .bg-gradient-warning {
             background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .bg-gradient-secondary {
            background: linear-gradient(135deg, #8E2DE2 0%, #4A00E0 100%);
        }
        
        .bg-white-20 {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .icon-box {
            width: 48px;
            height: 48px;
            min-width: 48px;
        }
        .fs-7 {
            font-size: 0.75rem;
        }
        .hover-lift { transition: transform 0.3s; }
        .hover-lift:hover { transform: translateY(-3px); }

        .elegant-shadow { box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03); border: 0 !important; border-radius: 1rem !important; }
        .chart-container { position: relative; min-height: 250px; }
        .table > :not(caption) > * > * { padding: 1rem 0.75rem; }
    </style>
</x-app-layout>
