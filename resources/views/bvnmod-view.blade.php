<x-app-layout>
    <x-slot name="title">BVN - Modification</x-slot>
    <div class="page-body">
        <div class="container-fluid">
            <div class="card shadow-sm rounded-lg mb-4 mt-4">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h4 class="mb-1 font-weight-bold text-primary">BVN Modification Details</h4>
                        <p class="text-muted mb-0">View and manage BVN modification request</p>
                    </div>
                    <div>
                        <!-- Secondary Button -->
                        <a href="{{ route('bvnmod.index') }}" 
                           class="btn btn-outline-secondary fw-bold me-2 px-4">
                            <i class="fas fa-arrow-left me-2"></i> Back to List
                        </a>
                        
                        <!-- Primary Button -->
                        <button type="button" 
                                class="btn btn-primary fw-bold px-4" 
                                data-bs-toggle="modal" 
                                data-bs-target="#statusUpdateModal">
                            <i class="fas fa-edit me-2"></i> Update Request
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <main class="main-content">
            <div class="container-fluid">
                {{-- Alerts --}}
                @if (session('errorMessage'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('errorMessage') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('successMessage') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    {{-- Left Column --}}
                    <div class="col-lg-8">
                        {{-- Enrollment Info --}}
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Enrollment Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <tbody>
                                            <tr>
                                                <th>Agent ID</th>
                                                <td>
                                                    {{ $enrollmentInfo->user_id }}
                                                    @if (!empty($user))
                                                        <!-- Secondary Outline Button -->
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-secondary ms-2"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#agentInfoModal">
                                                            View Agent Info
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr><th>Request ID</th><td>{{ $enrollmentInfo->id }}</td></tr>
                                            <tr><th>Transaction ID</th><td>{{ $enrollmentInfo->reference }}</td></tr>
                                            <tr><th>NIN</th><td>{{ $enrollmentInfo->nin }}</td></tr>
                                            <tr><th>BVN</th><td>{{ $enrollmentInfo->bvn }}</td></tr>
                                            <tr>
                                                <th>Affidavit</th>
                                                <td>
                                                    {{ $enrollmentInfo->affidavit }}
                                                    @if (!empty($enrollmentInfo->affidavit_file_url))
                                                        <!-- Secondary Outline Button -->
                                                        <a href="{{ $enrollmentInfo->affidavit_file_url }}" 
                                                           target="_blank"
                                                           class="btn btn-sm btn-outline-secondary ms-2">
                                                            Open File
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr><th>New Information</th><td>{{ $enrollmentInfo->description }}</td></tr>
                                            <tr><th>Enrolment Bank</th><td>{{ $enrollmentInfo->service_name }}</td></tr>
                                            <tr><th>Modification Field</th><td>{{ $enrollmentInfo->modification_field_name }}</td></tr> 
                                            <tr>
                                                <th>Current Status</th>
                                                <td>
                                                    <span class="badge bg-{{ 
                                                        $enrollmentInfo->status === 'pending' ? 'info' : 
                                                        ($enrollmentInfo->status === 'processing' ? 'primary' : 
                                                        ($enrollmentInfo->status === 'resolved' ? 'success' : 
                                                        ($enrollmentInfo->status === 'rejected' ? 'danger' : 'secondary'))) 
                                                    }}">
                                                        {{ ucfirst($enrollmentInfo->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr><th>Comment</th><td>{{ $enrollmentInfo->comment ?? 'N/A' }}</td></tr>
                                            <tr>
                                                <th>Date Created</th>
                                                <td>
                                                    {{ $enrollmentInfo->submission_date 
                                                        ? \Carbon\Carbon::parse($enrollmentInfo->submission_date)->format('M j, Y g:i A') 
                                                        : 'N/A' }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-lg-4">
                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Status History</h6>
                            </div>
                            <div class="card-body">
                                @if ($statusHistory->isNotEmpty())
                                    <div class="timeline">
                                        @foreach ($statusHistory as $history)
                                            <div class="timeline-item mb-3">
                                                <div class="card border-left-{{ 
                                                    $history['status'] === 'pending' ? 'warning' :
                                                    ($history['status'] === 'processing' ? 'primary' :
                                                    ($history['status'] === 'resolved' ? 'success' :
                                                    ($history['status'] === 'rejected' ? 'danger' : 'secondary'))) 
                                                }} shadow-sm">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <small class="text-muted">
                                                                {{ \Carbon\Carbon::parse($history['submission_date'])->format('M j, Y g:i A') }}
                                                            </small>
                                                            <span class="badge bg-{{ 
                                                                $history['status'] === 'pending' ? 'warning' :
                                                                ($history['status'] === 'processing' ? 'info' :
                                                                ($history['status'] === 'resolved' ? 'success' :
                                                                ($history['status'] === 'rejected' ? 'danger' : 'secondary'))) 
                                                            }}">
                                                                {{ ucfirst($history['status']) }}
                                                            </span>
                                                        </div>
                                                        @if (!empty($history['comment']))
                                                            <p class="mb-0">{{ $history['comment'] }}</p>
                                                        @endif
                                                        <div class="text-end mt-2">
                                                            <small class="text-muted">
                                                                Updated: {{ \Carbon\Carbon::parse($history['updated_at'])->format('M j, Y g:i A') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3 text-muted">
                                        No status history available
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('modal.user')
        @include('modal.comment')

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Set the form action dynamically when the modal is opened
                const statusUpdateModal = document.getElementById('statusUpdateModal');
                const statusUpdateForm = document.getElementById('statusUpdateForm');
                const updateUrl = "{{ route('bvnmod.update', $enrollmentInfo->id) }}";

                statusUpdateModal.addEventListener('show.bs.modal', function (event) {
                    statusUpdateForm.action = updateUrl;
                    
                    // Pre-select current status
                    const currentStatus = "{{ $enrollmentInfo->status }}";
                    const statusSelect = document.getElementById('status');
                    if(statusSelect) {
                        statusSelect.value = currentStatus;
                        // Trigger change event to update comment/refund options
                        statusSelect.dispatchEvent(new Event('change'));
                    }
                });
            });
        </script>
    </div>
</x-app-layout>