<x-app-layout>
    <x-slot name="title">Manage Service: {{ $service->name }}</x-slot>

    <div class="container-fluid py-4">
        <div class="mb-4">
            <a href="{{ route('admin.services.index') }}" class="text-decoration-none text-muted small fw-bold text-uppercase">
                <i class="fas fa-arrow-left me-1"></i> Back to Services
            </a>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <div>
                    <h3 class="fw-bold text-dark mb-1">{{ $service->name }}</h3>
                    <p class="text-muted mb-0">{{ $service->description }}</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                     @if($service->is_active)
                        <span class="badge bg-soft-success text-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i> Active</span>
                    @else
                        <span class="badge bg-soft-secondary text-secondary px-3 py-2 rounded-pill"><i class="fas fa-ban me-1"></i> Inactive</span>
                    @endif
                    
                    <button class="btn btn-outline-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#basePricingModal">
                        <i class="fas fa-tag me-2"></i> Base Pricing
                    </button>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Fields Management -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-bottom-0 py-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-0">Modification Fields</h5>
                            <small class="text-muted">Define the fields available for this service.</small>
                        </div>
                        <button class="btn btn-primary btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createFieldModal">
                            <i class="fas fa-plus"></i> Add Field
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 text-uppercase text-muted small fw-bold">Name</th>
                                        <th class="text-uppercase text-muted small fw-bold">Code</th>
                                        <th class="text-uppercase text-muted small fw-bold">Base Price</th>
                                        <th class="text-uppercase text-muted small fw-bold">Status</th>
                                        <th class="text-end pe-4 text-uppercase text-muted small fw-bold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($service->modificationFields as $field)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold text-dark">{{ $field->field_name }}</span>
                                                </div>
                                            </td>
                                             <td class="font-monospace text-muted small">{{ $field->field_code }}</td>
                                            <td class="fw-bold text-dark">
                                                ₦{{ number_format($field->base_price, 2) }}
                                            </td>
                                            <td>
                                                @if($field->is_active)
                                                    <span class="text-success small fw-bold"><i class="fas fa-dot-circle me-1"></i> Active</span>
                                                @else
                                                    <span class="text-secondary small fw-bold"><i class="fas fa-dot-circle me-1"></i> Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-icon btn-sm btn-outline-light text-success border-0 me-1" data-bs-toggle="modal" data-bs-target="#priceFieldModal{{ $field->id }}" title="Configure Prices">
                                                    <i class="fas fa-tags"></i>
                                                </button>
                                                <button class="btn btn-icon btn-sm btn-outline-light text-primary border-0" data-bs-toggle="modal" data-bs-target="#editFieldModal{{ $field->id }}" title="Edit Field">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.services.fields.destroy', [$service, $field]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this field?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-sm btn-outline-light text-danger border-0"><i class="fas fa-trash-alt"></i></button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Pricing Modal for Specific Field -->
                                        <div class="modal fade" id="priceFieldModal{{ $field->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg">
                                                    <div class="modal-header border-bottom-0 pb-0">
                                                        <div>
                                                            <h5 class="modal-title fw-bold">Pricing: {{ $field->field_name }}</h5>
                                                            <p class="text-muted small mb-0">Set specific prices for this field per role.</p>
                                                        </div>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="bg-light rounded-3 p-3">
                                                            <div class="d-flex flex-column gap-2">
                                                                @foreach($userTypes as $role)
                                                                    @php
                                                                        $fPrice = $service->servicePrices->where('modification_field_id', $field->id)->where('user_type', $role)->first();
                                                                    @endphp
                                                                    <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded shadow-sm border">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar avatar-xs bg-soft-primary text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                                                <small class="fw-bold">{{ strtoupper(substr($role, 0, 1)) }}</small>
                                                                            </div>
                                                                            <span class="fw-semibold text-capitalize">{{ $role }}</span>
                                                                        </div>
                                                                        <form action="{{ route('admin.services.prices.store', $service) }}" method="POST" class="d-flex align-items-center gap-2">
                                                                            @csrf
                                                                            <input type="hidden" name="user_type" value="{{ $role }}">
                                                                            <input type="hidden" name="modification_field_id" value="{{ $field->id }}">
                                                                            <div class="input-group input-group-sm">
                                                                                <span class="input-group-text border-end-0 bg-transparent text-muted">₦</span>
                                                                                <input type="number" name="price" step="0.01" class="form-control border-start-0 ps-0 fw-bold" style="width: 100px;" value="{{ $fPrice ? $fPrice->price : '' }}" placeholder="Default">
                                                                            </div>
                                                                            <button type="submit" class="btn btn-sm btn-soft-success"><i class="fas fa-check"></i></button>
                                                                        </form>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top-0 pt-0">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit Field Modal -->
                                        <div class="modal fade" id="editFieldModal{{ $field->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg">
                                                    <form action="{{ route('admin.services.fields.update', [$service, $field]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header border-bottom-0 pb-0">
                                                            <h5 class="modal-title fw-bold">Edit Field</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label small text-muted fw-bold">Field Name</label>
                                                                <input type="text" name="field_name" class="form-control" value="{{ $field->field_name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small text-muted fw-bold">Unique Code</label>
                                                                <input type="text" name="field_code" class="form-control font-monospace" value="{{ $field->field_code }}" required>
                                                            </div>
                                                             <div class="mb-3">
                                                                <label class="form-label small text-muted fw-bold">Description</label>
                                                                <textarea name="description" class="form-control" rows="2">{{ $field->description }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small text-muted fw-bold">Base Price (₦)</label>
                                                                <input type="number" step="0.01" name="base_price" class="form-control fw-bold" value="{{ $field->base_price }}" required>
                                                            </div>
                                                            <div class="form-check form-switch d-flex align-items-center gap-2 p-0">
                                                                <input type="hidden" name="is_active" value="0">
                                                                <input class="form-check-input ms-0" type="checkbox" name="is_active" value="1"  {{ $field->is_active ? 'checked' : '' }} style="width: 2.5em; height: 1.25em;">
                                                                <label class="form-check-label fw-semibold">Is Active</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top-0 pt-0">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-clipboard-list fa-2x mb-2 opacity-25"></i>
                                            <p class="mb-0 small">No modification fields added yet.</p>
                                        </td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Base Pricing Modal -->
    <div class="modal fade" id="basePricingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold">Base Service Pricing</h5>
                        <p class="text-muted small mb-0">Set default prices per user role for this service.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="bg-light rounded-3 p-3">
                        <div class="d-flex flex-column gap-2">
                            @foreach($userTypes as $role)
                                @php
                                    $price = $service->servicePrices->where('modification_field_id', null)->where('user_type', $role)->first();
                                @endphp
                                <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded shadow-sm border">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs bg-soft-primary text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                            <small class="fw-bold">{{ strtoupper(substr($role, 0, 1)) }}</small>
                                        </div>
                                            <span class="fw-semibold text-capitalize">{{ $role }}</span>
                                    </div>
                                    <form action="{{ route('admin.services.prices.store', $service) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="user_type" value="{{ $role }}">
                                        <input type="hidden" name="modification_field_id" value="">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text border-end-0 bg-transparent text-muted">₦</span>
                                            <input type="number" name="price" step="0.01" class="form-control border-start-0 ps-0 fw-bold" style="width: 100px;" value="{{ $price ? $price->price : '' }}" placeholder="0.00">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-soft-success"><i class="fas fa-check"></i></button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Field Modal -->
    <div class="modal fade" id="createFieldModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('admin.services.fields.store', $service) }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Add Modification Field</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Field Name</label>
                            <input type="text" name="field_name" class="form-control" required placeholder="e.g. Change of Name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Field Code</label>
                            <input type="text" name="field_code" class="form-control font-monospace" required placeholder="e.g. NAME_CHANGE_01">
                            <div class="form-text small">Must be unique across the system.</div>
                        </div>
                         <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Describe this field"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted fw-bold">Base Price</label>
                            <input type="number" step="0.01" name="base_price" class="form-control" value="0.00" required>
                        </div>
                        <div class="form-check form-switch d-flex align-items-center gap-2 p-0">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input ms-0" type="checkbox" name="is_active" value="1" checked style="width: 2.5em; height: 1.25em;">
                            <label class="form-check-label fw-semibold">Initially Active</label>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Add Field</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
