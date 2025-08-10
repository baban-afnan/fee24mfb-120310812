<x-app-layout>
    <x-slot name="title">General wallet - Control Form</x-slot>


<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-cash-coin me-2 fs-4"></i>
                    <h5 class="mb-0">General Wallet Funding</h5>
                </div>

                <div class="card-body">
                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success:</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Error Message --}}
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Whoops!</strong> Please fix the following:
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Funding Form --}}
                    <form method="POST" action="{{ route('general-funding.preview') }}" id="fundingForm">
                        @csrf

                        <div class="mb-3">
                            <label for="transaction_type" class="form-label fw-semibold">Transaction Type</label>
                            <select class="form-control" id="transaction_type" name="transaction_type" required>
                                <option value="">Select transaction type</option>
                                <option value="credit" {{ old('transaction_type') == 'credit' ? 'selected' : '' }}>Credit</option>
                                <option value="debit" {{ old('transaction_type') == 'debit' ? 'selected' : '' }}>Debit</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label fw-semibold">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">₦</span>
                                <input type="number" class="form-control" id="amount" name="amount"
                                       value="{{ old('amount') }}" min="0.01" step="0.01" required placeholder="0.00">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                      placeholder="Enter transaction description">{{ old('description') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-bold py-2">
                            <i class="bi bi-eye me-2"></i> Review Transaction
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
