{{-- Agent Info Modal --}}
@if(!empty($user))
    <div class="modal fade" id="agentInfoModal" tabindex="-1" aria-labelledby="agentInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="agentInfoModalLabel">
                        <i class="bi bi-person-badge me-2"></i> Agent Information
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body bg-light">
                    <div class="text-center mb-4">
                        @php
                            $profileImage = $user->profile_photo_url ?: asset('assets/images/avtar/1.jpg');
                        @endphp
                        <img src="{{ $profileImage }}" alt="Profile Photo" class="rounded-circle shadow" width="120" height="120">
                    </div>

                    <div class="row g-3">
                        @php
                            $fields = [
                                'Full Name' => $user->first_name . ' ' . $user->last_name,
                                'Email' => $user->email,
                                'Phone' => $user->phone_no ?? 'N/A',
                                'Role' => ucfirst($user->role ?? 'N/A'),
                                'Address' => $user->address ?? 'N/A',
                            ];
                        @endphp

                        @foreach ($fields as $label => $value)
                            <div class="col-md-{{ $label === 'Address' ? '12' : '6' }}">
                                <div class="p-3 bg-white rounded-3 shadow-sm">
                                    <h6 class="mb-1 text-muted">{{ $label }}</h6>
                                    <p class="m-0 fw-bold text-primary">{{ $value }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer bg-white rounded-bottom-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
