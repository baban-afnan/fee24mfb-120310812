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
                <!-- Charts Area (Existing) -->
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
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2 text-secondary"></i>Recent Transactions
                </h5>
                <span class="text-muted small">Latest 10 in current filter</span>
            </div>

            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table id="recentTxTable" class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Performed By</th>
                                <th scope="col">Description</th>
                                <th scope="col">Type</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($recentTransactions as $tx)
                                <tr>
                                    <td>#{{ $tx->id }}</td>

                                    {{-- Performed By --}}
                                    <td>{{ $tx->performed_by ?? 'N/A' }}</td>


                                    {{-- discription --}}
                                    <td>{{ $tx->description ?? 'N/A' }}</td>

                                    {{-- Type --}}
                                    <td>
                                        @php
                                            $t = strtolower((string) ($tx->type ?? ''));
                                        @endphp
                                        <span class="badge rounded-pill bg-{{ $t === 'credit' ? 'success' : ($t === 'debit' ? 'danger' : 'secondary') }}">
                                            {{ ucfirst($tx->type ?? 'n/a') }}
                                        </span>
                                    </td>

                                    {{-- Amount --}}
                                    <td>₦{{ number_format((float) ($tx->amount ?? 0), 2) }}</td>

                                    {{-- Date --}}
                                    <td>{{ optional($tx->created_at)->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No transactions found for the selected range.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
