<x-app-layout>
    <x-slot name="title">Transaction History</x-slot>

    <div class="page-body">
        <div class="container-fluid">

            {{-- Error Alert --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Error:</strong> {{ session('error') }}
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            <div class="card shadow-sm border-0">

                {{-- Card Header --}}
                <div class="card-header bg-white border-0 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div>
                        <h5 class="fw-bold text-primary mb-1">
                            <i class="fas fa-exchange-alt me-2"></i> Transaction History
                        </h5>
                        <p class="text-muted small mb-0">Your complete financial activity history</p>
                    </div>
                </div>

                <div class="card-body">

                    {{-- Stats Overview --}}
                    <div class="row g-3 mb-4">
                        {{-- Wallet Balance --}}
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-white-50 mb-0">Wallet Balance</h6>
                                        <i class="fas fa-wallet fa-lg opacity-50"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0">₦{{ number_format($walletBalance, 2) }}</h4>
                                </div>
                            </div>
                        </div>

                        {{-- Total Funded (Credits) --}}
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm bg-success-subtle text-success h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-success-emphasis mb-0">Total Funded</h6>
                                        <i class="fas fa-arrow-down fa-lg opacity-50"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0">₦{{ number_format($totalCredits, 2) }}</h4>
                                    <small class="text-success-emphasis opacity-75">Based on filter</small>
                                </div>
                            </div>
                        </div>

                        {{-- Total Spent (Debits) --}}
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm bg-danger-subtle text-danger h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-danger-emphasis mb-0">Total Spent</h6>
                                        <i class="fas fa-arrow-up fa-lg opacity-50"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0">₦{{ number_format($totalDebits, 2) }}</h4>
                                    <small class="text-danger-emphasis opacity-75">Based on filter</small>
                                </div>
                            </div>
                        </div>

                        {{-- Total Refunds --}}
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm bg-info-subtle text-info-emphasis h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="text-info-emphasis mb-0">Total Refunds</h6>
                                        <i class="fas fa-undo fa-lg opacity-50"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0">₦{{ number_format($totalRefunds, 2) }}</h4>
                                    <small class="text-info-emphasis opacity-75">Based on filter</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Section --}}
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small text-muted">Transaction Reference</label>
                                    <input type="text" name="search_transaction_id" class="form-control form-control-sm"
                                           placeholder="Search by Transaction Ref..."
                                           value="{{ request('search_transaction_id') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">From Date</label>
                                    <input type="date" name="from_date" class="form-control form-control-sm"
                                           value="{{ request('from_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">To Date</label>
                                    <input type="date" name="to_date" class="form-control form-control-sm"
                                           value="{{ request('to_date') }}">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <div class="d-flex gap-2 w-100">
                                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                            <i class="fas fa-filter me-1"></i> Filter
                                        </button>
                                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-sync-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Tabs --}}
                    <div class="mb-4">
                        <ul class="nav nav-tabs nav-tabs-custom" id="transactionTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ is_null($recordType) ? 'active' : '' }}"
                                   href="{{ route('transactions.index') }}">
                                    All Transactions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $recordType === 'credit' ? 'active' : '' }}"
                                   href="{{ route('transactions.index', array_merge(request()->except('record_type'), ['record_type' => 'credit'])) }}">
                                    <i class="fas fa-arrow-down text-success me-1"></i> Credits
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $recordType === 'debit' ? 'active' : '' }}"
                                   href="{{ route('transactions.index', array_merge(request()->except('record_type'), ['record_type' => 'debit'])) }}">
                                    <i class="fas fa-arrow-up text-danger me-1"></i> Debits
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $recordType === 'refund' ? 'active' : '' }}"
                                   href="{{ route('transactions.index', array_merge(request()->except('record_type'), ['record_type' => 'refund'])) }}">
                                    <i class="fas fa-undo text-primary me-1"></i> Refunds
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Transactions Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Reference</th>
                                    <th>Performed By</th>
                                    <th class="text-end">Amount</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $txn)
                                    <tr class="transaction-row">
                                        <td class="text-muted">#{{ $txn->id }}</td>
                                        <td><span class="text-monospace">{{ $txn->transaction_ref }}</span></td>
                                        <td>{{ Str::limit($txn->performed_by, 40) }}</td>
                                        <td class="text-end fw-bold 
                                            {{ in_array($txn->type, ['credit','refund']) ? 'text-success' : 'text-danger' }}">
                                            ₦{{ number_format($txn->amount, 2) }}
                                        </td>
                                        <td>{{ Str::limit($txn->description, 40) }}</td>
                                        <td>
                                            @if ($txn->type === 'credit')
                                                <span class="badge bg-success-light text-success">
                                                    <i class="fas fa-arrow-down me-1"></i> Credit
                                                </span>
                                            @elseif ($txn->type === 'debit')
                                                <span class="badge bg-danger-light text-danger">
                                                    <i class="fas fa-arrow-up me-1"></i> Debit
                                                </span>
                                            @elseif ($txn->type === 'refund')
                                                <span class="badge bg-primary-light text-primary">
                                                    <i class="fas fa-undo me-1"></i> Refund
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge 
                                                {{ in_array($txn->status, ['successful','completed']) ? 'bg-success-light text-success' :
                                                   ($txn->status === 'pending' ? 'bg-warning-light text-warning' : 'bg-danger-light text-danger') }}">
                                                {{ ucfirst($txn->status) }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">{{ $txn->created_at->format('M j, Y g:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No transactions found</h5>
                                                <p class="text-muted small">Your transaction history will appear here</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($transactions->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $transactions->onEachSide(1)->withQueryString()->links('vendor.pagination.custom') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        /* Custom Nav Tabs */
        .nav-tabs-custom .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin-right: 0.5rem;
            border-radius: 0.375rem;
        }
        .nav-tabs-custom .nav-link.active {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
            border-bottom: 2px solid #0d6efd;
        }
        .nav-tabs-custom .nav-link:hover:not(.active) {
            background-color: #f8f9fa;
        }

        /* Table hover effect */
        .transaction-row:hover { background-color: #f8fafc; }

        /* Badge variants */
        .bg-success-light { background-color: rgba(25, 135, 84, 0.1); }
        .bg-danger-light { background-color: rgba(220, 53, 69, 0.1); }
        .bg-warning-light { background-color: rgba(255, 193, 7, 0.1); }
        .bg-primary-light { background-color: rgba(13, 110, 253, 0.1); }

        /* Reference monospace */
        .text-monospace {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Courier New", monospace;
            font-size: 0.875rem;
        }
    </style>
    @endpush
</x-app-layout>
