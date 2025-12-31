<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-primary">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- The form action will be set dynamically or use the existing context if included inside the loop/view -->
            <!-- Since this is an include, we assume $enrollmentInfo is available or we adjust the form action in the specific view -->
            <!-- Ideally, if this is a shared modal, the action should be set via JS. But for now, let's assume it's included in a single-view context like 'show' page. -->
            <!-- If used in Index, we need JS. The user asked for "All my view and show". "Show" pages usually have one item. -->
            <div class="modal-body">
                <form id="statusUpdateForm" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="status" class="form-label fw-bold text-dark">Select New Status</label>
                        <select class="form-select form-select-lg shadow-sm" id="status" name="status" required style="border-radius: 10px;">
                            <option value="" selected disabled>Choose status...</option>
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="processing">Processing</option>
                            <option value="query">Query</option>
                            <option value="remark">Remark</option>
                            <option value="resolved">Resolved</option>
                            <option value="successful">Successful</option>
                            <option value="failed">Failed</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="comment" class="form-label small fw-bold text-muted">Comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Enter comment..."></textarea>
                    </div>

                    <div class="mb-3 form-check" id="forceRefundContainer" style="display: none;">
                        <input type="checkbox" class="form-check-input" id="forceRefund" name="force_refund" value="1">
                        <label class="form-check-label text-danger fw-bold" for="forceRefund">
                            Force Refund (Bypass double-refund check)
                        </label>
                        <div class="form-text small text-danger">Check this ONLY if you need to refund again manually.</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const statusField = document.getElementById("status");
        const commentField = document.getElementById("comment");
        const forceRefundContainer = document.getElementById("forceRefundContainer");

        const messages = {
            'pending': "Your request is pending. Our team will review it shortly.",
            'in-progress': "Your request is now in progress.",
            'processing': "Your request is currently being processed. Please hold on.",
            'query': "We require additional information regarding your request. Kindly respond promptly.",
            'remark': "A remark has been added to your request. Kindly review the details provided.",
            'resolved': "✅ Your Request Has Been Successfully Treated!\n\nHello 👋,\n\nWe’re glad to inform you that your recent request has been successfully processed. Thank you for trusting us!\n🎯 Don’t stop here — we’re always ready to serve you better. Feel free to send in more requests anytime.\n\nYour satisfaction is our priority!",
            'successful': "✅ Transaction Successful!\n\nYour request has been completed successfully.",
            'failed': "Transaction Failed. Please try again or contact support.",
            'rejected': "Unfortunately, your request has been rejected. Please review and try again."
        };

        statusField.addEventListener("change", function () {
            const selectedStatus = statusField.value;
            
            // Auto-fill comment
            if (messages[selectedStatus]) {
                commentField.value = messages[selectedStatus];
            } else {
                commentField.value = "";
            }

            // Show Force Refund option only for Rejected or Failed
            if (selectedStatus === 'rejected' || selectedStatus === 'failed') {
                forceRefundContainer.style.display = 'block';
            } else {
                forceRefundContainer.style.display = 'none';
                document.getElementById("forceRefund").checked = false;
            }
        });
    });
</script>
