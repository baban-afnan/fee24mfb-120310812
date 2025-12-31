<aside class="page-sidebar">
  <div class="main-sidebar" id="main-sidebar">
    <ul class="sidebar-menu" id="simple-bar">
      <li class="sidebar-main-title">
        <div><h5 class="sidebar-title f-w-700">General</h5></div>
      </li>

      <li class="sidebar-list">
        <a class="sidebar-link sidebar-link-active" href="{{ route('dashboard') }}">
          <i class="fas fa-home fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text">Dashboard</h6>
        </a>
      </li>

      <li class="sidebar-main-title">
        <div><h5 class="sidebar-title f-w-700">Menu</h5></div>
      </li>

      <!-- Wallet Section -->
      <li class="sidebar-main-title">
        <div>
          <h5 class="f-w-700 sidebar-title pt-3">Wallet</h5>
        </div>
      </li>
      {{-- Wallet Services Submenu --}}
      <li class="sidebar-list">
        <a class="sidebar-link sidebar-title" href="#walletServices" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="walletServices">
          <i class="fas fa-wallet fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">Wallet</h6>
          <i class="fas fa-chevron-down ms-auto small text-muted"></i>
        </a>
        <div class="collapse" id="walletServices">
           <ul class="list-unstyled ps-4 py-2">
            <li class="mb-2">
                <a class="text-decoration-none text-muted" href="{{ route('manual.funding.form') }}">
                    <i class="fas fa-plus-circle me-2"></i>Manual Funding
                </a>
            </li>
            <li class="mb-2">
                <a class="text-decoration-none text-muted" href="{{ route('general.funding.form') }}">
                    <i class="fas fa-coins me-2"></i>General Wallet
                </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="sidebar-list">
        <a class="sidebar-link" href="{{ route('admin.services.index') }}">
          <i class="fas fa-money-bill-wave fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">Services</h6>
        </a>
      </li>
      
      <!-- Services Section -->
      <li class="sidebar-main-title">
        <div>
          <h5 class="f-w-700 sidebar-title pt-3">Services</h5>
        </div>
      </li>
      {{-- BVN Services Submenu --}}
      <li class="sidebar-list">
        <a class="sidebar-link sidebar-title" href="#bvnServices" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="bvnServices">
          <i class="fas fa-user fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">BVN Services</h6>
          <i class="fas fa-chevron-down ms-auto small text-muted"></i>
        </a>
        <div class="collapse" id="bvnServices">
          <ul class="list-unstyled ps-4 py-2">
            <li class="mb-2">
                <a class="text-decoration-none text-muted" href="{{ route('bvnmod.index') }}">
                    <i class="fas fa-edit me-2"></i>BVN Modification
                </a>
            </li>
            <li class="mb-2">
                <a class="text-decoration-none text-muted" href="{{ route('crmreg.index') }}">
                     <i class="fas fa-users me-2"></i>CRM
                </a>
            </li>
            <li class="mb-2">
                <a class="text-decoration-none text-muted" href="{{ route('bvnuser.index') }}">
                    <i class="fas fa-user-plus me-2"></i>Enrolment User
                </a>
            </li>
            <li class="mb-2">
                <a class="text-decoration-none text-muted" href="{{ route('sendvnin.index') }}">
                    <i class="fas fa-share-square me-2"></i>VNIN to NIBSS
                </a>
            </li>
             <li class="mb-2">
                <a class="text-decoration-none text-muted" href="{{ route('bvnsearch.index') }}">
                    <i class="fas fa-search-plus me-2"></i>Manual Search
                </a>
            </li>
          </ul>
        </div>
      </li>

      {{-- NIN Services Submenu --}}
      <li class="sidebar-list">
        <a class="sidebar-link sidebar-title" href="#ninServices" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="ninServices">
          <i class="fas fa-id-card fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">NIN Services</h6>
          <i class="fas fa-chevron-down ms-auto small text-muted"></i>
        </a>
        <div class="collapse" id="ninServices">
           <ul class="list-unstyled ps-4 py-2">
            <li class="mb-2">
                <a class="text-decoration-none text-muted" href="{{ route('ninmod.index') }}">
                    <i class="fas fa-edit me-2"></i>NIN Modification
                </a>
            </li>
            <li class="mb-2">
                <a class="text-decoration-none text-muted" href="{{ route('ipe.index') }}">
                    <i class="fas fa-check-circle me-2"></i>IPE Clearance
                </a>
            </li>
             <li class="mb-2">
                <a class="text-decoration-none text-muted" href="{{ route('validation.index') }}">
                    <i class="fas fa-search me-2"></i>Validation
                </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="sidebar-list">
        <a class="sidebar-link" href="#">
          <i class="fas fa-paper-plane fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">VIP Services</h6>
        </a>
      </li>
      
      <!-- Account Section -->
      <li class="sidebar-main-title">
        <div>
          <h5 class="f-w-700 sidebar-title pt-3">Account</h5>
        </div>
      </li>
      {{-- Management Submenu --}}
      <li class="sidebar-list">
         <a class="sidebar-link sidebar-title" href="#managementServices" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="managementServices">
          <i class="fas fa-person fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">Management</h6>
          <i class="fas fa-chevron-down ms-auto small text-muted"></i>
        </a>
        <div class="collapse" id="managementServices">
           <ul class="list-unstyled ps-4 py-2">
            <li class="mb-2">
                <a class="text-decoration-none text-muted" href="{{ route('users.index') }}">
                    <i class="fas fa-users me-2"></i>Users
                </a>
            </li>
             <li class="mb-2">
                <a class="text-decoration-none text-muted" href="{{ route('notification.index') }}">
                    <i class="fas fa-bell me-2"></i>Notifications
                </a>
            </li>
             <li class="mb-2">
                <a class="text-decoration-none text-muted" href="{{ route('admin.email.create') }}">
                    <i class="fas fa-envelope me-2"></i>Email
                </a>
            </li>
          </ul>
        </div>
      </li>
       <li class="sidebar-list">
         <a class="sidebar-link" href="">
          <i class="fas fa-cog fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">Settings</h6>
        </a>
      </li>
      <li class="sidebar-list">
        <a class="sidebar-link" href="{{route('transactions.index')}}">
          <i class="fas fa-list-alt fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">Transactions</h6>
        </a>
      </li>
      <li class="sidebar-list">
        <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="sidebar-link d-flex align-items-center bg-transparent border-0 w-100 text-start">
            <i class="fas fa-sign-out-alt fa-lg sidebar-icon"></i>
            <h6 class="sidebar-text f-w-600 mb-0">Log Out</h6>
          </button>
        </form>
      </li>
    </ul>
  </div>
</aside>

