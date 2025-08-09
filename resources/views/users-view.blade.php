<x-app-layout>
<main class="main-content">
    <div class="container-fluid">

      @if (session('errorMessage'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Error!</strong> {{ session('errorMessage') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      @if (session('successMessage'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Success!</strong> {{ session('successMessage') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      <div class="row">
          <div class="col-lg-8">
              {{-- Enrollment Information --}}
              <div class="card shadow mb-4">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Enrollment Information</h6>
                  </div>
                  <div class="card-body">
                      <div class="table-responsive">
                          <table class="table table-bordered table-hover">
                              <tbody>
                                  <tr>
                                      <th>Transactions</th>
                                      <td>
                                          {{ $user->user_id }}
                                          @if(!empty($user))
                                              <button type="button" class="btn btn-sm btn-outline-info ms-2" data-bs-toggle="modal" data-bs-target="#agentInfoModal">
                                                  View User Transactions
                                              </button>
                                          @endif
                                      </td>
                                  </tr>
                                  <tr><th>User ID</th><td>{{ $user->id }}</td></tr>
                                  <tr><th>Full Name</th><td>{{ $user->first_name }}  {{ $user->last_name }}  {{ $user->middle_name }}</td></tr>
                                  <tr><th>Phone Number</th><td>{{ $user->phone_no }}</td></tr>
                                  <tr><th>Email</th><td>{{ $user->email }}</td></tr>
                                  <tr><th>Address</th><td>{{ $user->address }}</td></tr>
                                  <tr><th>BVN</th><td>{{ $user->bvn }}</td></tr>
                                  <tr><th>NIN</th><td>{{ $user->nin }}</td></tr>
                                  <tr><th>Role</th><td>{{ $user->role }}</td></tr>
                                  
                                  <tr>
                                      <th>Current Status</th>
                                      <td>
                                          <span class="badge bg-{{
                                              $user->status === 'active' ? 'success' :
                                              ($user->status === 'inactive' ? 'info' :
                                              ($user->status === 'suspended' ? 'danger' :
                                              'secondary'))
                                          }}">
                                              {{ ucfirst($user->status) }}
                                          </span>
                                      </td>
                                  </tr>
                                  <tr><th>Comment</th><td>{{ $user->comment ?? 'N/A' }}</td></tr>
                                  <tr><th>Date Created</th><td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('M j, Y g:i A') : 'N/A' }}</td></tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>

              {{-- Wallet Information --}}
              @if(isset($wallet))
              <div class="card shadow mb-4">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Wallet Information</h6>
                  </div>
                  <div class="card-body">
                      <div class="table-responsive">
                          <table class="table table-bordered table-hover">
                              <tbody>
                                  <tr><th>Wallet ID</th><td>{{ $wallet->wallet_number }}</td></tr>
                                  <tr><th>Balance</th><td>{{ number_format($wallet->wallet_balance, 2) }}</td></tr>
                                  <tr>
                                      <th>Status</th>
                                      <td>
                                          <span class="badge bg-{{
                                              $wallet->status === 'active' ? 'success' :
                                              ($wallet->status === 'inactive' ? 'secondary' :
                                              ($wallet->status === 'suspended' ? 'danger' : 'success'))
                                          }}">
                                              {{ ucfirst($wallet->status) }}
                                          </span>
                                      </td>
                                  </tr>
                                  <tr><th>Created At</th><td>{{ \Carbon\Carbon::parse($wallet->created_at)->format('M j, Y g:i A') }}</td></tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
              @endif

              {{-- Virtual Account Information --}}
              @if(isset($virtualAccount))
              <div class="card shadow mb-4">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Virtual Account Information</h6>
                  </div>
                  <div class="card-body">
                      <div class="table-responsive">
                          <table class="table table-bordered table-hover">
                              <tbody>
                                  <tr><th>Account Number</th><td>{{ $virtualAccount->accountNo}}</td></tr>
                                  <tr><th>Bank Name</th><td>{{ $virtualAccount->bankName}}</td></tr>
                                  <tr><th>Account Name</th><td>{{ $virtualAccount->accountName }}</td></tr>
                                  <tr><th>Status</th><td>{{ ucfirst($virtualAccount->status) }}</td></tr>
                                  <tr><th>Created At</th><td>{{ \Carbon\Carbon::parse($virtualAccount->created_at)->format('M j, Y g:i A') }}</td></tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
              @endif

              {{-- Update Status Form --}}
              <div class="card shadow mb-4">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Update Status</h6>
                  </div>
                  <div class="card-body">
                      <form method="POST" action="{{ route('users.update', $user->id) }}">
                          @csrf
                          @method('PUT')
                          <div class="mb-3">
                              <label for="status" class="form-label">New Status</label>
                              <select class="form-select" id="status" name="status" required>
                                  <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                                  <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                  <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                              </select>
                          </div>
                          <div class="mb-3">
                              <label for="role" class="form-label">New Role</label>
                              <select class="form-select" id="role" name="role" required>
                                  <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                  <option value="agent" {{ $user->role === 'agent' ? 'selected' : '' }}>Agent</option>
                                  <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                              </select>
                          </div>
                          <div class="mb-3">
                              <label for="status" class="form-label">Wallet Status</label>
                              <select class="form-select" id="status" name="status" required>
                                  <option value="active" {{ isset($wallet) && $wallet->status === 'active' ? 'selected' : '' }}>Active</option>
                                  <option value="inactive" {{ isset($wallet) && $wallet->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                  <option value="suspended" {{ isset($wallet) && $wallet->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                  <option value="suspended" {{ isset($wallet) && $wallet->status === 'close' ? 'selected' : '' }}>Close</option>
                              </select>
                          </div>
                          <div class="mb-3">
                              <label for="comment" class="form-label">Comment</label>
                              <textarea class="form-control" id="comment" name="comment" rows="3">{{ old('comment') }}</textarea>
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
                                          $history['status'] === 'active' ? 'success' :
                                          ($history['status'] === 'inactive' ? 'primary' :
                                          ($history['status'] === 'suspended' ? 'success' :
                                          ($history['status'] === 'rejected' ? 'danger' : 'secondary')))
                                      }} shadow-sm">
                                          <div class="card-body p-3">
                                              <div class="d-flex justify-content-between align-items-center mb-1">
                                                  <small class="text-muted">
                                                      {{ \Carbon\Carbon::parse($history['created_at'])->format('M j, Y g:i A') }}
                                                  </small>
                                                  <span class="badge bg-{{ 
                                                      $history['status'] === 'active' ? 'success' :
                                                      ($history['status'] === 'inactive' ? 'info' :
                                                      ($history['status'] === 'suspended' ? 'danger' :
                                                      ($history['status'] === 'rejected' ? 'danger' : 'secondary')))
                                                  }}">
                                                      {{ ucfirst($history['status']) }}
                                                  </span>
                                              </div>
                                              @if (!empty($history['comment']))
                                                  <p class="mb-0">{{ $history['comment'] }}</p>
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

 {{-- Transactions Modal --}}
<div class="modal fade" id="agentInfoModal" tabindex="-1" aria-labelledby="agentInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="transactionsModalLabel">Recent Transactions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              @if(!empty($transactions))
              @foreach($transactions as $transaction)
              <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->description }}</td>
                <td>
                  <span class="badge bg-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                    {{ ucfirst($transaction->type) }}
                  </span>
                                  <td>{{ number_format($transaction->amount, 2) }}</td>
                </td>
                <td>{{ $transaction->created_at->format('M j, Y g:i A') }}</td>
              </tr>
              @endforeach
              @else
              <tr>
                <td colspan="6" class="text-center">No transactions available</td>
              </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

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