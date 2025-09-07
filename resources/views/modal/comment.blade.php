                  {{-- Update Status Form --}}
<div class="card shadow mb-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Update Status</h6>
    </div>
    <div class="card-body">
            @csrf
            @method('PUT')

            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label">New Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="pending" {{ old('status', $enrollmentInfo->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ old('status', $enrollmentInfo->status) === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="resolved" {{ old('status', $enrollmentInfo->status) === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="query" {{ old('status', $enrollmentInfo->status) === 'query' ? 'selected' : '' }}>Query</option>
                    <option value="rejected" {{ old('status', $enrollmentInfo->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="remark" {{ old('status', $enrollmentInfo->status) === 'remark' ? 'selected' : '' }}>Remark</option>
                </select>
                @error('status')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            {{-- Comment --}}
            <div class="mb-3">
                <label for="comment" class="form-label">Comment</label>
                <textarea class="form-control" id="comment" name="comment" rows="3">{{ old('comment', $enrollmentInfo->comment) }}</textarea>
                @error('comment')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Update Status
            </button>
        </form>
    </div>
</div>
</div>
{{-- JavaScript for Auto Messages --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const statusField = document.getElementById("status");
        const commentField = document.getElementById("comment");

        const messages = {
            pending: "Your request is pending. Our team will review it shortly.",
            processing: "Your request is currently being processed. Please hold on.",
            resolved: "✅ Your Request Has Been Successfully Treated!\n\nHello 👋,\n\nWe’re glad to inform you that your recent request has been successfully processed. Thank you for trusting us!\n🎯 Don’t stop here — we’re always ready to serve you better. Feel free to send in more requests anytime.\n\nYour satisfaction is our priority!",
            query: "We require additional information regarding your request. Kindly respond promptly.",
            rejected: "Unfortunately, your request has been rejected. Please review and try again.",
            remark: "A remark has been added to your request. Kindly review the details provided."
        };

        // Auto update comment when status changes
        statusField.addEventListener("change", function () {
            const selectedStatus = statusField.value;
            if (messages[selectedStatus]) {
                commentField.value = messages[selectedStatus];
            } else {
                commentField.value = "";
            }
        });
    });
</script>
