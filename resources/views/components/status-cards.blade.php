@props(['statusCounts'])

@push('styles')
<x-crm-card-styles />
@endpush

<div class="row g-4 mb-4">
    <!-- Pending -->
    <div class="col-md-3">
        <div class="card crm-card bg-gradient-pending">
            <div class="card-body crm-card-body">
                <div>
                    <h6 class="card-title-text">Pending Requests</h6>
                    <h4 class="card-amount">{{ $statusCounts['pending'] ?? 0 }}</h4>
                    <small class="text-white-50" style="font-size: 0.7rem;">ACTION NEEDED</small>
                </div>
                <div class="card-icon-box">
                    <i class="bi bi-hourglass-split fs-4 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Processing -->
    <div class="col-md-3">
        <div class="card crm-card bg-gradient-processing">
            <div class="card-body crm-card-body">
                <div>
                    <h6 class="card-title-text">Processing</h6>
                    <h4 class="card-amount">{{ $statusCounts['processing'] ?? 0 }}</h4>
                    <small class="text-white-50" style="font-size: 0.7rem;">IN PROGRESS</small>
                </div>
                <div class="card-icon-box">
                    <i class="bi bi-gear-fill fs-4 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Resolved -->
    <div class="col-md-3">
        <div class="card crm-card bg-gradient-resolved">
            <div class="card-body crm-card-body">
                <div>
                    <h6 class="card-title-text">Resolved</h6>
                    <h4 class="card-amount">{{ $statusCounts['resolved'] ?? 0 }}</h4>
                    <small class="text-white-50" style="font-size: 0.7rem;">COMPLETED</small>
                </div>
                <div class="card-icon-box">
                    <i class="bi bi-check-circle-fill fs-4 text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejected -->
    <div class="col-md-3">
        <div class="card crm-card bg-gradient-rejected">
            <div class="card-body crm-card-body">
                <div>
                    <h6 class="card-title-text">Rejected</h6>
                    <h4 class="card-amount">{{ $statusCounts['rejected'] ?? 0 }}</h4>
                    <small class="text-white-50" style="font-size: 0.7rem;">DECLINED</small>
                </div>
                <div class="card-icon-box">
                    <i class="bi bi-x-octagon-fill fs-4 text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>
