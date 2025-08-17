@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Enrollments</h3>
                    <div class="card-tools">
                        <form action="{{ route('enrollments.index') }}" method="GET" class="d-flex align-items-center flex-wrap">
                            <div class="input-group input-group-sm me-2" style="width: 260px;">
                                <input type="text" name="search" class="form-control" placeholder="Search by Ticket/bvn/Agent" value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <select name="status" class="form-control form-control-sm me-2">
                                <option value="">All Statuses</option>
                                @foreach(['pending','ongoing','successful','failed','rejected'] as $s)
                                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                   <!-- Status Summary Cards - Fee24MFB Style -->
<div class="row mb-4">
    <!-- Total Card -->
    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Enrollments</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $status['total'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Successful Card -->
    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Successful</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $status['successful'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejected Card -->
    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Rejected</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $status['rejected'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Failed Card -->
    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Failed</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $status['failed'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Card -->
    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Pending</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $status['pending'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ongoing Card -->
    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Ongoing</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $status['ongoing'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-spinner fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                    <!-- File Upload Form -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ route('enrollments.upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input type="file" class="form-control" id="file" name="file" accept=".csv, .xlsx, .xls">
                                    <button type="submit" class="btn btn-primary ms-2">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Enrollments Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Ticket Number</th>
                                    <th>bvn</th>
                                    <th>Agent Name</th>
                                    <th>amount</th>
                                    <th>Status</th>
                                    <th>Validation Message</th>
                                    <th>Capture Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->ticket_number }}</td>
                                    <td>{{ $enrollment->bvn }}</td>
                                    <td>{{ $enrollment->agent_name }}</td>
                                    <td>{{ number_format($enrollment->amount, 2) }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($enrollment->validation_status == 'successful') bg-success
                                            @elseif($enrollment->validation_status == 'rejected') bg-danger
                                            @elseif($enrollment->validation_status == 'failed') bg-danger
                                            @elseif($enrollment->validation_status == 'ongoing') bg-warning
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($enrollment->validation_status) }}
                                        </span>
                                    </td>
                                    <td>{{ $enrollment->validation_message }}</td>
                                    <td>{{ $enrollment->capture_date }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#statusModal{{ $enrollment->id }}">
                                                        Change Status
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $enrollment->id }}">
                                                        View Details
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Status Change Modal -->
                                        <div class="modal fade" id="statusModal{{ $enrollment->id }}" tabindex="-1" aria-labelledby="statusModalLabel{{ $enrollment->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('enrollments.update-status', $enrollment->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="statusModalLabel{{ $enrollment->id }}">Change Status for Ticket #{{ $enrollment->ticket_number }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="status{{ $enrollment->id }}" class="form-label">Select Status</label>
                                                                <select class="form-select" id="status{{ $enrollment->id }}" name="validation_status" required>
                                                                    <option value="pending" {{ $enrollment->validation_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                    <option value="ongoing" {{ $enrollment->validation_status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                                    <option value="successful" {{ $enrollment->validation_status == 'successful' ? 'selected' : '' }}>Successful</option>
                                                                    <option value="failed" {{ $enrollment->validation_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                                                    <option value="rejected" {{ $enrollment->validation_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="message{{ $enrollment->id }}" class="form-label">Validation Message</label>
                                                                <textarea class="form-control" id="message{{ $enrollment->id }}" name="validation_message" rows="3">{{ $enrollment->validation_message }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update Status</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Details Modal -->
                                        <div class="modal fade" id="detailsModal{{ $enrollment->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $enrollment->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="detailsModalLabel{{ $enrollment->id }}">Details for Ticket #{{ $enrollment->ticket_number }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-bordered">
                                                                <tbody>
                                                                    <tr><th>Ticket Number</th><td>{{ $enrollment->ticket_number }}</td></tr>
                                                                    <tr><th>bvn</th><td>{{ $enrollment->bvn }}</td></tr>
                                                                    <tr><th>Agent Mgt Inst Name</th><td>{{ $enrollment->AGT_MGT_INST_NAME }}</td></tr>
                                                                    <tr><th>Agent Mgt Inst Code</th><td>{{ $enrollment->AGT_MGT_INST_CODE }}</td></tr>
                                                                    <tr><th>Agent Name</th><td>{{ $enrollment->agent_name }}</td></tr>
                                                                    <tr><th>Agent Code</th><td>{{ $enrollment->agent_code }}</td></tr>
                                                                    <tr><th>Enroller Code</th><td>{{ $enrollment->enroller_code }}</td></tr>
                                                                    <tr><th>latitude</th><td>{{ $enrollment->latitude }}</td></tr>
                                                                    <tr><th>Longitude</th><td>{{ $enrollment->LONGITUDE }}</td></tr>
                                                                    <tr><th>Fingerprint Scanner</th><td>{{ $enrollment->FINGER_PRINT_SCANNER }}</td></tr>
                                                                    <tr><th>BMS Import ID</th><td>{{ $enrollment->bms_import_id }}</td></tr>
                                                                    <tr><th>Status</th><td>{{ ucfirst($enrollment->validation_status) }}</td></tr>
                                                                    <tr><th>Validation Message</th><td>{{ $enrollment->validation_message }}</td></tr>
                                                                    <tr><th>amount</th><td>{{ number_format($enrollment->amount, 2) }}</td></tr>
                                                                    <tr><th>Capture Date</th><td>{{ $enrollment->capture_date }}</td></tr>
                                                                    <tr><th>Sync Date</th><td>{{ $enrollment->SYNC_DATE }}</td></tr>
                                                                    <tr><th>Validation Date</th><td>{{ $enrollment->validation_date}}</td></tr>
                                                                    <tr><th>Agent State</th><td>{{ $enrollment->AGENT_STATE }}</td></tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">No enrollment records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-4">
                        <div class="col-md-12 d-flex justify-content-center">
                            {{ $data->onEachSide(1)->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if(session('success')) toastr.success('{{ session('success') }}'); @endif
    @if(session('error')) toastr.error('{{ session('error') }}'); @endif
</script>
@endpush