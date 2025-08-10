<x-app-layout>
<main class="main-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-8">
                {{-- Notification Information --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Notification Review</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr><th>ID</th><td>{{ $enrollmentInfo->id }}</td></tr>
                                    <tr><th>Title</th><td>{{ $enrollmentInfo->title }}</td></tr>
                                    <tr><th>Message</th><td>{{ $enrollmentInfo->content }}</td></tr>
                                    <tr><th>Link</th><td>{{ $enrollmentInfo->link }}</td></tr>
                                    <tr>
                                        <th>Current Status</th>
                                        <td>
                                            <span class="badge bg-{{
                                                $enrollmentInfo->status === 'inactive' ? 'warning' :
                                                ($enrollmentInfo->status === 'active' ? 'success' : 'secondary')
                                            }}">
                                                {{ ucfirst($enrollmentInfo->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Date Created</th>
                                        <td>
                                            {{ $enrollmentInfo->created_at
                                                ? \Carbon\Carbon::parse($enrollmentInfo->created_at)->format('M j, Y g:i A')
                                                : 'N/A' }}
                                        </td>
                                    </tr>
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
                        <form method="POST" action="{{ route('notification.update', $enrollmentInfo->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="status" class="form-label">New Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="inactive" {{ $enrollmentInfo->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="active" {{ $enrollmentInfo->status === 'active' ? 'selected' : '' }}>Active</option>
                                     </select>
                               </div>
                               <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="3">{{ old('content', $enrollmentInfo->content) }}</textarea>
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
                                            $history['status'] === 'inactive' ? 'warning' :
                                            ($history['status'] === 'active' ? 'success' : 'secondary')
                                        }} shadow-sm">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($history['created_at'])->format('M j, Y g:i A') }}
                                                    </small>
                                                    <span class="badge bg-{{
                                                        $history['status'] === 'inactive' ? 'warning' :
                                                        ($history['status'] === 'active' ? 'success' : 'secondary')
                                                    }}">
                                                        {{ ucfirst($history['status']) }}
                                                    </span>
                                                </div>
                                                @if (!empty($history['content']))
                                                    <p class="mb-0">{{ $history['content'] }}</p>
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
@include('modal.user')

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
