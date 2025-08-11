<x-app-layout>

<x-slot name="title">NIN MOD - Control Form</x-slot>
<main class="main-content">
    <div class="container-fluid">

    
        <div class="row">
            <div class="col-lg-8">
                {{-- Enrollment Information --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
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
                                            @if(!empty($user))
                                                <button type="button" class="btn btn-sm btn-outline-info ms-2" data-bs-toggle="modal" data-bs-target="#agentInfoModal">
                                                    View Agent Info
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr><th>Request ID</th><td>{{ $enrollmentInfo->id }}</td></tr>
                                    <tr><th>Transaction ID</th><td>{{ $enrollmentInfo->reference }}</td></tr>
                                    <tr><th>NIN</th><td>{{ $enrollmentInfo->nin }}</td></tr>
                                    <tr><th>New Information</th><td>{{ $enrollmentInfo->description }}</td></tr>
                                    <tr><th>Correction Field</th><td>{{ $enrollmentInfo->modification_field_name  }}</td></tr>
                                    <tr>
                                        <th>Current Status</th>
                                        <td>
                                            <span class="badge bg-{{
                                                $enrollmentInfo->status === 'pending' ? 'warning' :
                                                ($enrollmentInfo->status === 'processing' ? 'info' :
                                                ($enrollmentInfo->status === 'resolved' ? 'success' :
                                                ($enrollmentInfo->status === 'rejected' ? 'danger' : 'secondary')))
                                            }}">
                                                {{ ucfirst($enrollmentInfo->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr><th>Comment</th><td>{{ $enrollmentInfo->comment ?? 'N/A' }}</td></tr>
                                    <tr><th>Date Created</th><td>{{ $enrollmentInfo->submission_date ? \Carbon\Carbon::parse($enrollmentInfo->submission_date)->format('M j, Y g:i A') : 'N/A' }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Update Status Form --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Update Status</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('ninmod.update', $enrollmentInfo->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="status" class="form-label">New Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending" {{ $enrollmentInfo->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $enrollmentInfo->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="resolved" {{ $enrollmentInfo->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="rejected" {{ $enrollmentInfo->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Comment</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3">{{ old('comment', $enrollmentInfo->comment) }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Status History --}}
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
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

{{-- Agent Info Modal --}}
@if(!empty($user))
<div class="modal fade" id="agentInfoModal" tabindex="-1" aria-labelledby="agentInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agentInfoModalLabel">Agent Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tbody>
            <tr><th>Full Name</th><td>{{ $user->first_name }} {{ $user->last_name }}</td></tr>
            <tr><th>Email</th><td>{{ $user->email }}</td></tr>
            <tr><th>Phone</th><td>{{ $user->phone_no?? 'N/A' }}</td></tr>
            <tr><th>Role</th><td>{{ $user->role?? 'N/A' }}</td></tr>
            <tr><th>Address</th><td>{{ $user->address ?? 'N/A' }}</td></tr>
            {{-- Add more fields if needed --}}
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endif

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
