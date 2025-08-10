<x-app-layout>
    <x-slot name="title">Notification and Advert Form</x-slot>

    <div class="row g-4 mb-4">
        <div class="card shadow mb-4">
            {{-- Card Header --}}
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Notification Form</h6>
                <div class="d-flex align-items-center">
                  

                    {{-- Dropdown Menu --}}
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                             aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Export Options:</div>
                            <a class="dropdown-item" href="#"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Export as CSV</a>
                            <a class="dropdown-item" href="#"><i class="bi bi-file-excel me-2"></i>Export as Excel</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Print Records</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="card-body">
                {{-- Search & Filters --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" class="form-inline search-full col">
                            <div class="input-group">
                                <input type="text" name="search_title" class="form-control"
                                       placeholder="Search by title..."
                                       value="{{ request('search_title') }}">
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

                             {{-- Add Notification Button --}}
                             <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addNotificationModal">
                             <i class="bi bi-plus-circle"></i> Add Notification
                             </button>

                            @if(request('status') || request('search_title'))
                                <a href="{{ route('notification.index') }}" class="btn btn-outline-danger">
                                    <i class="bi bi-x-circle"></i> Clear
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Alerts --}}
               @include('modal.alart')

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->id }}</td>
                                    <td>{{ $enrollment->title }}</td>
                                    <td>
                                        @php
                                            $statusColor = match($enrollment->status) {
                                                'inactive' => 'warning',
                                                'active' => 'success',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($enrollment->submission_date)->format('M j, Y g:i A') }}</td>
                                    <td>
                                        <a href="{{ route('notification.show', $enrollment->id) }}" class="btn btn-sm btn-primary">
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

                    {{-- Pagination --}}
                    @if ($enrollments->lastPage() > 1)
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item {{ $enrollments->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $enrollments->previousPageUrl() }}">
                                        <i class="bi bi-chevron-left"></i> Previous
                                    </a>
                                </li>
                                @for ($i = 1; $i <= $enrollments->lastPage(); $i++)
                                    <li class="page-item {{ $enrollments->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $enrollments->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item {{ !$enrollments->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $enrollments->nextPageUrl() }}">
                                        Next <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
          @include('modal.notification-form')

   
    {{-- Scripts --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let searchTimer;
            const searchInput = document.querySelector('input[name="search_title"]');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
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
