@php
    use Illuminate\Support\Facades\DB;

    $pendingServicesCount = DB::table('bvn_user')
        ->where('status', 'pending')
        ->count();


         $pendingServicesCount = DB::table('bvn_modification')
        ->where('status', 'pending')
        ->count();

    $systemSize = number_format((disk_total_space("/") - disk_free_space("/")) / 1048576, 2); // MB used
    $totalSize = number_format(disk_total_space("/") / 1048576, 2); // MB total
@endphp

<!-- Welcome Modal -->
<div class="modal fade" id="welcomeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-gradient text-white bg-primary rounded-top">
                <h5 class="modal-title">
                    <i class="bi bi-person-circle me-2"></i>
                    Welcome Back, {{ Auth::user()->first_name ?? 'User' }} {{ Auth::user()->last_name ?? '' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-4"></i>
                    <div>
                        <strong>Urgent Action Required:</strong> There are <strong>{{ $pendingServicesCount }}</strong> pending service(s) waiting for your response.
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-hdd-network fs-2 text-info me-3"></i>
                                    <div>
                                        <h6 class="mb-0">System Usage</h6>
                                        <small>{{ $systemSize }} MB used of {{ $totalSize }} MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-history fs-2 text-secondary me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Pending Requests</h6>
                                        <small>{{ $pendingServicesCount }} Service(s) in queue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                        Server Maintenance Scheduled
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="bi bi-moon-stars-fill text-dark me-2"></i>
                        New Feature: Dark Mode
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="bi bi-bell-fill text-primary me-2"></i>
                        System Update Available
                    </li>
                </ul>
            </div>

            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Close
                </button>
                <a href="" class="btn btn-primary">
                    <i class="bi bi-list-check me-1"></i> View Pending Services
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Show Modal Script -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Simple check for dashboard page (customize as needed)
    if (window.location.pathname.includes('dashboard')) {
        setTimeout(() => {
            // Create modal instance and show
            const welcomeModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
            welcomeModal.show();
            
            // Optional: Store in localStorage to prevent showing again today
            localStorage.setItem('welcomeModalShown', new Date().toDateString());
        }, 300); // 30 seconds = 30000 milliseconds
    }
});
</script>

<!-- Modal Styles -->
<style>
    #welcomeModal .modal-content {
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .modal-header.bg-gradient {
        background: linear-gradient(90deg, #0062cc, #0056b3);
    }

    .list-group-item {
        transition: background-color 0.2s ease;
        cursor: default;
    }

    .list-group-item:hover {
        background-color: #f1f3f5;
    }

    .card-body h6 {
        font-weight: 600;
    }

    .modal-footer .btn i {
        vertical-align: middle;
    }
</style>
