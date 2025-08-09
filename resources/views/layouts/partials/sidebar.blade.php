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
      <li class="sidebar-list">
        <a class="sidebar-link" href="">
          <i class="fas fa-wallet fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">Fund Wallet</h6>
        </a>
      </li>

      <li class="sidebar-list">
        <a class="sidebar-link" href="#">
          <i class="fas fa-money-bill-wave fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">Withdraw</h6>
        </a>
      </li>
      
      <!-- Services Section -->
      <li class="sidebar-main-title">
        <div>
          <h5 class="f-w-700 sidebar-title pt-3">Services</h5>
        </div>
      </li>
      <li class="sidebar-list">
        <a class="sidebar-link sidebar-link-active" href="{{ route('services.bvn') }}">
          <i class="fas fa-user fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">BVN Services</h6>
        </a>
      </li>
      <li class="sidebar-list">
        <a class="sidebar-link sidebar-link-active" href="{{ route('services.nin') }}">
          <i class="fas fa-id-card fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">NIN Services</h6>
        </a>
      </li>
      <li class="sidebar-list">
        <a class="sidebar-link sidebar-link-active" href="{{ route('services.verification') }}">
          <i class="fas fa-file-alt fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">Verifications</h6>
        </a>
      </li>
      <li class="sidebar-list">
        <a class="sidebar-link sidebar-link-active" href="{{ route('services.vip') }}">
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
      <li class="sidebar-list">
         <a class="sidebar-link" href="{{route('services.management')}}">
          <i class="fas fa-person fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">Management</h6>
        </a>
      </li>
       <li class="sidebar-list">
         <a class="sidebar-link" href="">
          <i class="fas fa-cog fa-lg sidebar-icon"></i>
          <h6 class="sidebar-text f-w-600">Settings</h6>
        </a>
      </li>
      <li class="sidebar-list">
        <a class="sidebar-link" href="">
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

