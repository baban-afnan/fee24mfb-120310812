<x-app-layout>
 
    <div class="support-dashboard">
        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card elegant-shadow border-0">
                        <div class="card-header bg-transparent pb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="mb-1"><i class="bi bi-graph-up me-2 text-gradient-primary"></i>Fee24mfb Analytics Dashboard</h3>
                                    <p class="text-muted mb-0">Comprehensive overview of system metrics and transactions</p>
                                </div>
                                <div class="d-flex">
                                    <button class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-calendar-range"></i> Date Range
                                    </button>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="bi bi-download"></i> Export
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                @php
                                    // Fetch all metrics at once for better performance
                                    $totalUsers = \App\Models\User::count();
                                    $totalCredit = \App\Models\Transaction::where('type', 'credit')->sum('amount');
                                    $totalDebit = \App\Models\Transaction::where('type', 'debit')->sum('amount');
                                    $totalTransactions = \App\Models\Transaction::count();
                                    $activeServices = \App\Models\ModificationField::where('is_active', 1)->count();
                                    $bvnModifications = \App\Models\BVNmodification::count();
                                    
                                    // Calculate trends (you would replace these with your actual trend logic)
                                    $userTrend = ($totalUsers > 0) ? round(($totalUsers / ($totalUsers + rand(10, 50))) * 100, 2) : 0;
                                    $creditTrend = ($totalCredit > 0) ? round(($totalCredit / ($totalCredit + rand(1000, 5000))) * 100, 2) : 0;
                                    $debitTrend = ($totalDebit > 0) ? round(($totalDebit / ($totalDebit + rand(1000, 5000))) * 100, 2) : 0;
                                    $transactionTrend = ($totalTransactions > 0) ? round(($totalTransactions / ($totalTransactions + rand(50, 200))) * 100, 2) : 0;
                                    $serviceTrend = ($activeServices > 0) ? round(($activeServices / ($activeServices + rand(2, 5))) * 100, 2) : 0;
                                    $bvnTrend = ($bvnModifications > 0) ? round(($bvnModifications / ($bvnModifications + rand(5, 15))) * 100, 2) : 0;
                                    
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
                                            'description' => 'Wallet deposits',
                                            'formatted' => '₦' . number_format($totalCredit, 2)
                                        ],
                                        [
                                            'label' => 'Total Debit', 
                                            'count' => $totalDebit, 
                                            'color' => 'danger', 
                                            'icon' => 'bi-arrow-up-circle', 
                                            'trend' => $debitTrend > 50 ? 'up' : 'down', 
                                            'trend_value' => $debitTrend . '%',
                                            'description' => 'Wallet withdrawals',
                                            'formatted' => '₦' . number_format($totalDebit, 2)
                                        ],
                                        [
                                            'label' => 'Transactions', 
                                            'count' => $totalTransactions, 
                                            'color' => 'info', 
                                            'icon' => 'bi-currency-exchange', 
                                            'trend' => $transactionTrend > 50 ? 'up' : 'down', 
                                            'trend_value' => $transactionTrend . '%',
                                            'description' => 'Total transactions'
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
                                            'description' => 'Modification requests'
                                        ],
                                    ];
                                @endphp

                                @foreach ($widgets as $index => $widget)
                                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6">
                                        <div class="stat-card hover-lift bg-white rounded-4 overflow-hidden h-100">
                                            <div class="card-body p-4 position-relative">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <h6 class="text-uppercase text-muted mb-1 fw-semibold small">{{ $widget['label'] }}</h6>
                                                        <h2 class="fw-bold counter mb-0" id="counter-{{ $index }}" 
                                                            data-target="{{ $widget['count'] }}">
                                                            @isset($widget['formatted'])
                                                                {{ $widget['formatted'] }}
                                                            @else
                                                                {{ $widget['count'] }}
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
                                                    <div class="progress-bar bg-{{ $widget['color'] }} progress-animate"
                                                        role="progressbar" style="width: 0%;" 
                                                        aria-valuenow="{{ $widget['trend'] == 'up' ? rand(60, 90) : rand(30, 50) }}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                
                                                <!-- Animated wave effect in the background -->
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
            
            <!-- Transaction Summary Section -->
            <div class="row">
                <div class="col-xl-8">
                    <div class="card elegant-shadow border-0 h-100">
                        <div class="card-header bg-transparent border-0 pb-2">
                            <h5 class="mb-0"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Transaction Analytics</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="chart-container" style="height: 300px;">
                                <canvas id="transactionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card elegant-shadow border-0 h-100">
                        <div class="card-header bg-transparent border-0 pb-2">
                            <h5 class="mb-0"><i class="bi bi-pie-chart me-2 text-info"></i>Transaction Types</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="chart-container" style="height: 300px;">
                                <canvas id="transactionTypeChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 @include('modal.notification')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Animate counters with formatting
            const counters = document.querySelectorAll(".counter");
            const animationDuration = 2000;
            const frameDuration = 1000 / 60; // 60 fps
            
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const isCurrency = counter.textContent.includes('₦');
                const totalFrames = Math.round(animationDuration / frameDuration);
                let frame = 0;
                
                // Update counter text
                const updateCounter = () => {
                    frame++;
                    const progress = frame / totalFrames;
                    const currentCount = Math.round(target * progress);
                    
                    if (isCurrency) {
                        counter.textContent = '₦' + currentCount.toLocaleString('en-US') + '.00';
                    } else {
                        counter.textContent = currentCount.toLocaleString();
                    }
                    
                    if (frame < totalFrames) {
                        requestAnimationFrame(updateCounter);
                    }
                };
                
                // Start animation
                requestAnimationFrame(updateCounter);
            });
            
            // Animate progress bars
            const progressBars = document.querySelectorAll(".progress-animate");
            progressBars.forEach(bar => {
                const targetWidth = bar.getAttribute("aria-valuenow") + "%";
                bar.style.width = targetWidth;
                
                // Add animation class
                bar.classList.add("progress-bar-animated");
                
                // Remove animation after completion
                setTimeout(() => {
                    bar.classList.remove("progress-bar-animated");
                }, 1000);
            });
            
            // Transaction Line Chart
            const transactionCtx = document.getElementById('transactionChart').getContext('2d');
            const transactionChart = new Chart(transactionCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Credits',
                        data: [12000, 19000, 15000, 20000, 17000, 22000, 25000],
                        borderColor: 'rgba(16, 185, 129, 1)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Debits',
                        data: [8000, 12000, 10000, 15000, 13000, 18000, 20000],
                        borderColor: 'rgba(239, 68, 68, 1)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ₦' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₦' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
            
            // Transaction Type Pie Chart
            const typeCtx = document.getElementById('transactionTypeChart').getContext('2d');
            const typeChart = new Chart(typeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Deposits', 'Withdrawals', 'Transfers', 'Payments', 'Fees'],
                    datasets: [{
                        data: [35, 25, 20, 15, 5],
                        backgroundColor: [
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            'rgba(99, 102, 241, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw + '%';
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>

    <!-- Custom CSS -->
    <style>
        .support-dashboard {
            --bs-primary-rgb: 99, 102, 241;
            --bs-secondary-rgb: 107, 114, 128;
            --bs-success-rgb: 16, 185, 129;
            --bs-info-rgb: 59, 130, 246;
            --bs-warning-rgb: 245, 158, 11;
            --bs-danger-rgb: 239, 68, 68;
        }
        
        .elegant-shadow {
            box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.03);
            border: 0 !important;
            border-radius: 0.75rem !important;
        }
        
        .stat-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.03);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            overflow: hidden;
        }
        
        .stat-card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: rgba(0, 0, 0, 0.05);
        }
        
        .stat-icon {
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
        }
        
        .text-gradient-primary {
            background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .wave-effect {
            width: 100px;
            height: 40px;
            opacity: 0.7;
            bottom: -1px;
            right: -1px;
            overflow: hidden;
        }
        
        .wave-svg {
            width: 100%;
            height: 100%;
            animation: wave-animation 8s linear infinite;
        }
        
        @keyframes wave-animation {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        .progress-bar {
            transition: width 1.5s ease-in-out;
        }
        
        .bg-soft-primary { background-color: rgba(var(--bs-primary-rgb), 0.1) !important; }
        .bg-soft-secondary { background-color: rgba(var(--bs-secondary-rgb), 0.1) !important; }
        .bg-soft-success { background-color: rgba(var(--bs-success-rgb), 0.1) !important; }
        .bg-soft-info { background-color: rgba(var(--bs-info-rgb), 0.1) !important; }
        .bg-soft-warning { background-color: rgba(var(--bs-warning-rgb), 0.1) !important; }
        .bg-soft-danger { background-color: rgba(var(--bs-danger-rgb), 0.1) !important; }
        
        .chart-container {
            position: relative;
            min-height: 250px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 1199.98px) {
            .stat-icon {
                width: 42px;
                height: 42px;
                font-size: 1rem !important;
            }
        }
        
        @media (max-width: 767.98px) {
            .card-header .btn {
                margin-top: 0.5rem;
                width: 100%;
            }
            
            .stat-card {
                margin-bottom: 1rem;
            }
            
            .chart-container {
                min-height: 200px;
            }
        }
    </style>
</x-app-layout>