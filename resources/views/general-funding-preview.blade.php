<x-app-layout>
    <x-slot name="title">General funding review</x-slot>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-eye me-2"></i> Preview Wallet {{ ucfirst($type) }}</h5>
                    <span class="badge bg-light text-dark">{{ count($eligibleUsers) }} Eligible Users</span>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-semibold">Transaction Summary</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Type:</strong> <span class="text-capitalize">{{ $type }}</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Amount:</strong> ₦{{ number_format($amount, 2) }}
                            </li>
                            <li class="list-group-item">
                                <strong>Description:</strong> {{ $description ?? ucfirst($type) . ' Wallet' }}
                            </li>
                            <li class="list-group-item">
                                <strong>Skipped Users (due to insufficient balance):</strong>
                                <span class="text-danger">{{ $skippedCount }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="mb-1">
                        <h6 class="fw-semibold">Eligible Users</h6>
                        @if($eligibleUsers->isEmpty())
                            <div class="alert alert-warning">
                                No eligible users for this transaction.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Wallet Balance (₦)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($eligibleUsers as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->first_name }}{{ $user->last_name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ number_format($user->wallet->wallet_balance ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('general-funding.queue') }}" method="POST" class="d-flex justify-content-between">
                        @csrf
                        <a href="{{ route('general.funding.form') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-cloud-upload"></i> Queue Transaction
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
