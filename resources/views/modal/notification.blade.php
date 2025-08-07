@php
    use Illuminate\Support\Facades\DB;

    $pendingServicesCount = DB::table('bvn_modification')->where('status', 'pending')->count();
    $pendingServicesCount1 = DB::table('bvn_crm')->where('status', 'pending')->count();
    $pendingServicesCount2 = DB::table('bvn_user')->where('status', 'pending')->count();
    $pendingServicesCount3 = DB::table('nin_modifications')->where('status', 'pending')->count();
    $pendingServicesCount4 = DB::table('nin_ipe')->where('status', 'pending')->count();
    $pendingServicesCount5 = DB::table('bvn_search')->where('status', 'pending')->count();
    $pendingServicesCount6 = DB::table('send_vnin')->where('status', 'pending')->count();
    $pendingServicesCount7 = DB::table('nin_validation')->where('status', 'pending')->count();

    $totalPending = $pendingServicesCount + $pendingServicesCount1 + $pendingServicesCount2 + 
                    $pendingServicesCount3 + $pendingServicesCount4 + $pendingServicesCount5 + 
                    $pendingServicesCount6 + $pendingServicesCount7;

    $systemSize = number_format((disk_total_space("/") - disk_free_space("/")) / 1048576, 2); // MB used
    $totalSize = number_format(disk_total_space("/") / 1048576, 2); // MB total
@endphp

@if($totalPending > 0)
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
                        <strong>Urgent Action Required:</strong> You have a total of 
                        <strong>{{ $totalPending }}</strong> pending request(s) across all services.
                        <br><span class="text-primary fw-semibold">Act now to avoid delays and ensure a smooth workflow.</span>
                    </div>
                </div>
                
                <div class="row">
                    @if($pendingServicesCount > 0)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-history fs-2 text-secondary me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Pending BVN Modifications</h6>
                                        <small>{{ $pendingServicesCount }} Service(s) in queue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pendingServicesCount1 > 0)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-history fs-2 text-secondary me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Pending BVN CRM</h6>
                                        <small>{{ $pendingServicesCount1 }} Service(s) in queue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pendingServicesCount2 > 0)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-history fs-2 text-secondary me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Pending BVN User</h6>
                                        <small>{{ $pendingServicesCount2 }} Service(s) in queue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pendingServicesCount3 > 0)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-history fs-2 text-secondary me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Pending NIN Modification</h6>
                                        <small>{{ $pendingServicesCount3 }} Service(s) in queue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pendingServicesCount4 > 0)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-history fs-2 text-secondary me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Pending IPE</h6>
                                        <small>{{ $pendingServicesCount4 }} Service(s) in queue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pendingServicesCount5 > 0)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-history fs-2 text-secondary me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Pending BVN Search</h6>
                                        <small>{{ $pendingServicesCount5 }} Service(s) in queue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pendingServicesCount6 > 0)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-history fs-2 text-secondary me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Pending Send VNIN to NIBSS</h6>
                                        <small>{{ $pendingServicesCount6 }} Service(s) in queue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($pendingServicesCount7 > 0)
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock-history fs-2 text-secondary me-3"></i>
                                    <div>
                                        <h6 class="mb-0">Pending NIN Validation</h6>
                                        <small>{{ $pendingServicesCount7 }} Service(s) in queue</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <ul class="list-group list-group-flush mt-3">
                    <li class="list-group-item d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                        know that only 80% of the above service Fee will be refunded, follow up on processing request
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
    if (window.location.pathname.includes('dashboard')) {
        setTimeout(() => {
            const welcomeModal = new bootstrap.Modal(document.getElementById('welcomeModal'));
            welcomeModal.show();
        }, 3000); 
    }
});
</script>
@endif