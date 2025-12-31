<x-app-layout>
    <x-slot name="title">Services Management</x-slot>

    <div class="container-fluid py-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h5 class="mb-1 fw-bold text-dark">Services Management</h5>
                <p class="text-muted small mb-0">Manage system services, modification fields, and pricing matrices.</p>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary btn-wave d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createServiceModal">
                    <i class="fas fa-plus"></i>
                    <span>Add New Service</span>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        @if(session('success'))
                            <div class="alert alert-success m-4 mb-0 alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th class="ps-4 text-uppercase text-muted small fw-bold" style="width: 5%;">#</th>
                                        <th class="text-uppercase text-muted small fw-bold" style="width: 25%;">Service Name</th>
                                        <th class="text-uppercase text-muted small fw-bold" style="width: 35%;">Description</th>
                                        <th class="text-uppercase text-muted small fw-bold" style="width: 15%;">Status</th>
                                        <th class="text-end pe-4 text-uppercase text-muted small fw-bold" style="width: 20%;">Actions</th>
                                    </tr>
                                </thead>
                               <tbody>
                                    @forelse($services as $service)
                                        <tr>
                                            <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm bg-secondary-primary text-primary rounded-circle me-3 d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-layer-group"></i>
                                                    </div>
                                                    <span class="fw-semibold text-dark">{{ $service->name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-muted small">{{ Str::limit($service->description, 60) ?: 'No description provided.' }}</td>
                                            <td>
                                                @if($service->is_active)
                                                    <span class="badge bg-soft-success text-success rounded-pill px-3 py-2">
                                                        <i class="fas fa-circle small me-1"></i> Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-soft-secondary text-secondary rounded-pill px-3 py-2">
                                                        <i class="fas fa-circle small me-1"></i> Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.services.show', $service) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Manage details">
                                                        <i class="fas fa-cog"></i> Configure
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-secondary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editServiceModal{{ $service->id }}" 
                                                            title="Edit Service">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editServiceModal{{ $service->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg">
                                                    <form action="{{ route('admin.services.update', $service) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header border-bottom-0 pb-0">
                                                            <h5 class="modal-title fw-bold">Edit Service</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label small text-muted fw-bold">Service Name</label>
                                                                <input type="text" name="name" class="form-control form-control-lg" value="{{ $service->name }}" required>
                                                            </div>
                                                            <div class="mb-4">
                                                                <label class="form-label small text-muted fw-bold">Description</label>
                                                                <textarea name="description" class="form-control" rows="3">{{ $service->description }}</textarea>
                                                            </div>
                                                            <div class="form-check form-switch p-0 d-flex align-items-center gap-2">
                                                                <input type="hidden" name="is_active" value="0">
                                                                <input class="form-check-input ms-0" type="checkbox" name="is_active" value="1" id="activeCheck{{ $service->id }}" {{ $service->is_active ? 'checked' : '' }} style="width: 2.5em; height: 1.25em;">
                                                                <label class="form-check-label fw-semibold" for="activeCheck{{ $service->id }}">Active Status</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0 pt-0">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="empty-state">
                                                    <div class="mb-3 text-muted opacity-25">
                                                        <i class="fas fa-box-open fa-4x"></i>
                                                    </div>
                                                    <h6 class="text-muted">No services found</h6>
                                                    <p class="text-muted small">Get started by adding a new service.</p>
                                                    <button class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#createServiceModal">
                                                        Create Service
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($services->count() > 0)
                            <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Showing {{ $services->count() }} services</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('admin.services.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Add New Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Service Name</label>
                            <input type="text" name="name" class="form-control form-control-lg" required placeholder="e.g. NIN Modification">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small text-muted fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Briefly describe this service..."></textarea>
                        </div>
                        <div class="form-check form-switch p-0 d-flex align-items-center gap-2">
                             <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input ms-0" type="checkbox" name="is_active" value="1" id="createActiveCheck" checked style="width: 2.5em; height: 1.25em;">
                            <label class="form-check-label fw-semibold" for="createActiveCheck">Immediately Active</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Create Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
