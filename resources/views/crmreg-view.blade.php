<x-app-layout>
      <x-slot name="title">BVN CRM Request Form</x-slot>
      <div class="page-body">
    <div class="container-fluid">
      <div class="page-title">
        <div class="row">
          <div class="col-sm-6 col-12">
          </div>
        </div>
      </div>
    </div>
<main class="main-content">
    <div class="container-fluid">

      @if (session('errorMessage'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('errorMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('successMessage'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('successMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


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
                                    <tr><th>Batch ID</th><td>{{ $enrollmentInfo->batch_id }}</td></tr>
                                    <tr><th>Ticket_id</th><td>{{ $enrollmentInfo->ticket_id }}</td></tr>
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
                <form method="POST" action="{{ route('crmreg.update', $enrollmentInfo->id) }}">
                @include ('modal.comment')

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
@include ('modal.user')

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
