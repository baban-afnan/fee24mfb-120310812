<header class="page-header row">
  <div class="logo-wrapper d-flex align-items-center col-auto">
    <img class="light-logo img-fluid" src="{{ asset('assets/images/logo/logo.png') }}" alt="logo" style="max-width: 30px; height: auto;"/>
    <img class="dark-logo img-fluid" src="{{ asset('assets/images/logo/logo-dark.png') }}" alt="logo" style="max-width: 30px; height: auto;"/>
    <a class="lose-btn toggle-sidebar" href="javascript:void(0)">
      <i class="fas fa-times"></i>
    </a>
  </div>

  <div class="page-main-header col">
    <div class="header-left">
      <form class="form-inline search-full col" action="#" method="get">
        <div class="form-group w-100">
          <div class="Typeahead Typeahead--twitterUsers">
            <div class="u-posRelative">
              <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search..." name="q" />
              <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div>
              <i class="close-search" data-feather="x"></i>
            </div>
          </div>
        </div>
      </form>
    </div>

    <div class="nav-right">
      <ul class="header-right">
        <!-- Language Dropdown -->
        <li class="custom-dropdown">
          <div class="translate_wrapper">
            <div class="current_lang">
              <a class="lang" href="javascript:void(0)">
                <i class="flag-icon flag-icon-us"></i>
                <h6 class="lang-txt f-w-700">ENG</h6>
              </a>
            </div>
          </div>
        </li>

        <li class="search d-lg-none d-flex">
          <a href="javascript:void(0)"><i class="fas fa-search"></i></a>
        </li>
        <li><a class="dark-mode" href="javascript:void(0)"><i class="fas fa-moon"></i></a></li>
        <li><a class="full-screen" href="javascript:void(0)"><i class="fas fa-expand"></i></a></li>

        <!-- Profile Dropdown -->
        <li class="profile-nav custom-dropdown">
          <div class="user-wrap">
           <div class="user-img">
              @if(Auth::user()->photo && file_exists(public_path(Auth::user()->photo)))
            <img class="img-70 rounded-circle border border-2 shadow-sm" src="{{ asset(Auth::user()->photo) }}" alt="User Photo">
          @else
           <img class="img-70 rounded-circle border border-2 shadow-sm" src="{{ asset('assets/images/avtar/1.jpg') }}" alt="Photo">
              @endif
             </div>
            <div class="user-content">
              <h6>{{ Auth::user()->first_name ?? 'User' }}</h6>
              <p class="mb-0">{{ Auth::user()->role ?? 'Role' }}<i class="fas fa-chevron-down"></i></p>
            </div>
          </div>
          <div class="custom-menu overflow-hidden">
            <ul class="profile-body">
              <li class="d-flex">
                <i class="fas fa-user"></i>
                <a class="ms-2" href="#">Profile</a>
              </li>
              <li class="d-flex">
                <i class="fas fa-envelope"></i>
                <a class="ms-2" href="#">Inbox</a>
              </li>
              <li class="d-flex">
                <i class="fas fa-tasks"></i>
                <a class="ms-2" href="#">Task</a>
              </li>
              <li class="d-flex">
                <i class="fas fa-sign-out-alt"></i>
                <a class="ms-2" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
              </li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </li>
      </ul>
    </div>
  </div>
</header>


<style>

  /* Base Styles */
.icon-main {
    padding: 20px 0;
}

.card-header h3 {
    color: #2c3e50;
    font-weight: 700;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
    position: relative;
    padding-bottom: 15px;
}

.card-header h3::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #3498db, #9b59b6);
    border-radius: 3px;
}

.icon-lists {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.icons-item {
    perspective: 1000px;
    transition: all 0.3s ease;
}

.icons-item a {
    display: block;
    padding: 25px 15px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.2);
    color: #34495e;
    text-decoration: none !important;
}

.icons-item a::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(90deg, #3498db, #9b59b6);
    transition: all 0.3s ease;
}

.icons-item a:hover {
    transform: translateY(-10px) scale(1.03);
    box-shadow: 0 15px 30px rgba(0,0,0,0.12);
}

.icons-item a:hover::before {
    height: 10px;
}

.icons-item a:active {
    transform: translateY(-5px) scale(0.98);
}

.icons-item img {
    transition: all 0.3s ease;
    filter: grayscale(30%);
}

.icons-item a:hover img {
    transform: scale(1.1) rotate(5deg);
    filter: grayscale(0%);
}

.icons-item i {
    color: #3498db;
    transition: all 0.3s ease;
}

.icons-item a:hover i {
    color: #9b59b6;
    transform: translateY(-5px);
}

.icons-item h5 {
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
}

.icons-item a:hover h5 {
    color: #2c3e50;
}

.icons-item h5::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background: #3498db;
    transition: all 0.3s ease;
}

.icons-item a:hover h5::after {
    width: 40px;
}

/* Click Animation */
.icons-item a:active {
    animation: clickEffect 0.4s ease;
}

@keyframes clickEffect {
    0% { transform: translateY(-10px) scale(1.03); }
    50% { transform: translateY(-10px) scale(0.95); }
    100% { transform: translateY(-10px) scale(1.03); }
}

/* Responsive Styles */
@media (max-width: 1199.98px) {
    .icons-item {
        flex: 0 0 calc(25% - 20px);
    }
}

@media (max-width: 991.98px) {
    .icons-item {
        flex: 0 0 calc(33.333% - 20px);
    }
    
    .icons-item a {
        padding: 20px 10px;
    }
}

@media (max-width: 767.98px) {
    .icons-item {
        flex: 0 0 calc(50% - 20px);
    }
    
    .card-header h3 {
        font-size: 1.5rem;
    }
    
    .icons-item h5 {
        font-size: 1rem;
    }
}

@media (max-width: 575.98px) {
    .icons-item {
        flex: 0 0 calc(100% - 20px);
    }
    
    .icon-lists {
        gap: 15px;
    }
    
    .icons-item a {
        padding: 25px 15px;
    }
    
    .icons-item a:hover {
        transform: translateY(-5px) scale(1.02);
    }
}

/* Special Effects for Different Services */
.icons-item:nth-child(1) a::before { background: linear-gradient(90deg, #3498db, #2ecc71); }
.icons-item:nth-child(2) a::before { background: linear-gradient(90deg, #e74c3c, #f39c12); }
.icons-item:nth-child(3) a::before { background: linear-gradient(90deg, #9b59b6, #3498db); }
.icons-item:nth-child(4) a::before { background: linear-gradient(90deg, #1abc9c, #2ecc71); }
.icons-item:nth-child(5) a::before { background: linear-gradient(90deg, #f1c40f, #e67e22); }
.icons-item:nth-child(6) a::before { background: linear-gradient(90deg, #e74c3c, #9b59b6); }
.icons-item:nth-child(7) a::before { background: linear-gradient(90deg, #34495e, #3498db); }
.icons-item:nth-child(8) a::before { background: linear-gradient(90deg, #16a085, #27ae60); }
.icons-item:nth-child(9) a::before { background: linear-gradient(90deg, #d35400, #f39c12); }
.icons-item:nth-child(10) a::before { background: linear-gradient(90deg, #c0392b, #e74c3c); }

.icons-item:nth-child(odd) a:hover {
    background: linear-gradient(135deg, #f9f9f9, #ffffff);
}

.icons-item:nth-child(even) a:hover {
    background: linear-gradient(135deg, #ffffff, #f9f9f9);
}

</style>