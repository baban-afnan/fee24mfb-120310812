<x-app-layout>
  <x-slot name="title">Validation Request Form</x-slot>

    <x-status-cards :statusCounts="$statusCounts" />




<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Validation Request Form</h6>
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-three-dots-vertical text-gray-400"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                <div class="dropdown-header">Export Options:</div>
                <a class="dropdown-item" href="#"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Export as CSV</a>
                <a class="dropdown-item" href="#"><i class="bi bi-file-excel me-2"></i>Export as Excel</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Print Records</a>
            </div>
        </div>
    </div>

    <div class="card-body">
        {{-- Search and Filters --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" class="form-inline search-full col">
                    <div class="input-group">
                        <input type="text" name="search_nin" class="form-control" placeholder="Search by nin..." value="{{ request('search_nin') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <input type="hidden" name="status" value="{{ request('status') }}">
                </form>
            </div>

            <div class="col-md-6 text-md-end">
                <div class="btn-group">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-funnel"></i>
                        {{ request('status') ? 'Filter: ' . ucfirst(request('status')) : 'Filters' }}
                    </button>

                    @if(request('status') || request('search_nin'))
                        <a href="{{ route('sendvnin.index') }}" class="btn btn-outline-danger">
                            <i class="bi bi-x-circle"></i> Clear
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Errors --}}
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Reference</th>
                        <th>Field</th>
                        <th>NIN</th>
                        <th>Status</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->id }}</td>
                            <td>{{ $enrollment->reference }}</td>
                            <td>{{ $enrollment->modification_field_name }}</td>
                            <td>{{ $enrollment->nin }}</td>
                            <td>
                                @php
                                    $statusColor = match($enrollment->status) {
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'resolved' => 'success',
                                        'rejected' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($enrollment->submission_date)->format('M j, Y g:i A') }}</td>
                            <td>
                                <a href="{{ route('validation.show', $enrollment->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No enrollment records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $enrollments->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>

{{-- Filter Modal --}}
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="GET">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Enrollments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select class="form-select" id="statusFilter" name="status">
                            <option value="">All Statuses</option>
                            @foreach(['pending', 'processing', 'resolved', 'rejected'] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="search_nin" value="{{ request('search_nin') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let searchTimer;
        const searchInput = document.querySelector('input[name="search_nin"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    this.closest('form').submit();
                }, 800);
            });
        }
    });
</script>
@endpush
</x-app-layout>
