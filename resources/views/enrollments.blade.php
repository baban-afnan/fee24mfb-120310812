<x-app-layout>
<div class="main-content app-content">
    <div class="container-fluid py-4">

        <!-- Page header -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
            <div>
                <h4 class="fw-bold mb-1">User Enrollments</h4>
                <p class="text-muted mb-0">Upload, view and manage user enrollment data</p>
            </div>
            <div>
                <form action="{{ route('enrollments.upload') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-wrap gap-2">
                    @csrf
                    <input type="file" name="file" class="form-control form-control-sm" accept=".csv,.xlsx,.xls" required>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-upload me-1"></i> Upload
                    </button>
                </form>
            </div>
        </div>

        <!-- Summary Widgets -->
<div class="row g-3 mb-4">
    <!-- Total -->
    <div class="col-md-2">
        <div class="card elegant-shadow border-0 rounded-4 h-100">
            <div class="card-body text-center">
                <h6 class="text-muted">Total Enrollments</h6>
                <h3 class="fw-bold mb-0">{{ number_format((int) ($status['total'] ?? 0)) }}</h3>
            </div>
        </div>
    </div>

    <!-- Successful -->
    <div class="col-md-2">
        <div class="card elegant-shadow border-0 rounded-4 h-100">
            <div class="card-body text-center">
                <h6 class="text-success">Successful</h6>
                <h3 class="fw-bold text-success mb-0">{{ number_format((int) ($status['successful'] ?? 0)) }}</h3>
            </div>
        </div>
    </div>

    <!-- Rejected -->
    <div class="col-md-2">
        <div class="card elegant-shadow border-0 rounded-4 h-100">
            <div class="card-body text-center">
                <h6 class="text-danger">Rejected</h6>
                <h3 class="fw-bold text-danger mb-0">{{ number_format((int) ($status['rejected'] ?? 0)) }}</h3>
            </div>
        </div>
    </div>

    <!-- Failed -->
    <div class="col-md-2">
        <div class="card elegant-shadow border-0 rounded-4 h-100">
            <div class="card-body text-center">
                <h6 class="text-dark">Failed</h6>
                <h3 class="fw-bold text-dark mb-0">{{ number_format((int) ($status['failed'] ?? 0)) }}</h3>
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="col-md-2">
        <div class="card elegant-shadow border-0 rounded-4 h-100">
            <div class="card-body text-center">
                <h6 class="text-warning">Pending</h6>
                <h3 class="fw-bold text-warning mb-0">{{ number_format((int) ($status['pending'] ?? 0)) }}</h3>
            </div>
        </div>
    </div>

    <!-- Ongoing -->
    <div class="col-md-2">
        <div class="card elegant-shadow border-0 rounded-4 h-100">
            <div class="card-body text-center">
                <h6 class="text-primary">Ongoing</h6>
                <h3 class="fw-bold text-primary mb-0">{{ number_format((int) ($status['ongoing'] ?? 0)) }}</h3>
            </div>
        </div>
    </div>
</div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Data Table -->
        <div class="card elegant-shadow border-0 rounded-4">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Enrollment Records</h5>
                <form method="GET" class="d-flex">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search ticket/bvn/agent...">
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Ticket No.</th>
                                <th>bvn</th>
                                <th>Agent</th>
                                <th>Institution</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                @php
                                    $status = strtolower((string)($item->validation_status ?? ''));
                                    $badgeClass = $status === 'successful' ? 'success' : ($status === 'failed' ? 'danger' : 'warning');
                                    $statusLabel = $status === 'successful' ? 'Successful' : ($status === 'failed' ? 'Failed' : 'Pending');
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                                    <td class="fw-semibold">{{ $item->ticket_number }}</td>
                                    <td>{{ $item->bvn }}</td>
                                    <td>{{ $item->agent_name ?? 'N/A' }}</td>
                                    <td>{{ $item->agt_mgt_inst_name ?? 'N/A' }}</td>
                                    <td>₦{{ number_format((float) ($item->amount ?? 0), 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $details = [
                                                ['Ticket No.', (string)($item->ticket_number ?? '-')],
                                                ['bvn', (string)($item->bvn ?? '-')],
                                                ['Agent', (string)($item->agent_name ?? '-')],
                                                ['Agent Code', (string)($item->agent_code ?? '-')],
                                                ['Institution', (string)($item->agt_mgt_inst_name ?? '-')],
                                                ['Institution Code', (string)($item->agt_mgt_inst_code ?? '-')],
                                                ['Enroller Code', (string)($item->ENROLLER_CODE ?? '-')],
                                                ['Amount', '₦' . number_format((float)($item->amount ?? 0), 2)],
                                                ['Status', (string)($item->validation_status ?? '-')],
                                                ['Message', (string)($item->validation_message ?? '-')],
                                                ['Capture Date', (string)($item->capture_date ?? '-')],
                                                ['Sync Date', (string)($item->sync_date ?? '-')],
                                                ['Validation Date', (string)($item->validation_date ?? '-')],
                                                ['Latitude', (string)($item->latitude ?? '-')],
                                                ['Longitude', (string)($item->longitude ?? '-')],
                                                ['Fingerprint Scanner', (string)($item->FINGER_PRINT_SCANNER ?? '-')],
                                                ['BMS Import ID', (string)($item->bms_import_id ?? '-')],
                                            ];
                                        @endphp
                                        <button type="button" class="btn btn-sm btn-outline-primary view-details"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailsModal"
                                            data-details='@json($details, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT)'>
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <img src="{{ asset('assets/images/no-data.svg') }}" width="120" alt="No data">
                                        <p class="mt-3 mb-0 text-muted">No enrollment records found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($data->hasPages())
                <div class="card-footer bg-transparent border-0 d-flex justify-content-center">
                    {{ $data->links('vendor.pagination.bootstrap-5') }}
                </div>
            @endif
        </div>

    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header">
        <h5 class="modal-title">Enrollment Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <tbody id="details-table"></tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.view-details');
        const tbody = document.getElementById('details-table');
        if (!tbody) return;
        buttons.forEach(btn => {
            btn.addEventListener('click', function() {
                let rowsHtml = '';
                try {
                    const details = JSON.parse(this.getAttribute('data-details') || '[]');
                    details.forEach(function(pair) {
                        const label = String(pair[0] ?? '-');
                        let value = pair[1];
                        if (value === null || value === undefined || value === '') value = '-';
                        rowsHtml += '<tr><th class="text-muted" style="width: 35%;">' + label + '</th><td>' + value + '</td></tr>';
                    });
                } catch (e) {
                    rowsHtml = '<tr><td colspan="2" class="text-danger">Unable to load details.</td></tr>';
                }
                tbody.innerHTML = rowsHtml;
            });
        });
    });
})();
</script>

<style>
    .elegant-shadow { box-shadow: 0 0.75rem 1.5rem rgba(18, 38, 63, 0.06); border: 0 !important; border-radius: 0.75rem !important; }
    .table > :not(caption) > * > * { padding: 0.85rem 0.75rem; }
    .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.02); }
</style>
</x-app-layout>
