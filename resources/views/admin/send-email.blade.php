<x-app-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-primary text-white fw-bold text-center py-3">
                        <i class="bi bi-envelope-check me-2"></i> Send Email to All Users
                    </div>
                    <div class="card-body p-4">

                        {{-- Success Message --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.email.send') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-teal">Subject</label>
                                <input type="text" name="subject" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-teal">Title (inside email)</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-teal">Message Content</label>
                                <textarea name="content" class="form-control" rows="5" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-teal">
                                    Button URL <span class="text-muted fw-normal">(optional)</span>
                                </label>
                                <input type="url" name="button_url" class="form-control" placeholder="https://your-link.com">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-teal">
                                    Button Text <span class="text-muted fw-normal">(optional)</span>
                                </label>
                                <input type="text" name="button_text" class="form-control" placeholder="e.g. View Details">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary fw-bold">
                                    <i class="bi bi-send-fill me-1"></i> Send Email
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
