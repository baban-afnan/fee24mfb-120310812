<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BiyaNow - Amin Section</title>
    <meta name="description" content="Biyanow Management System - Efficiency and Productivity for your operations.">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/logo/logo.png') }}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/logo.png') }}" type="image/x-icon" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200..1000&display=swap" rel="stylesheet">

    <!-- CSS Assets -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome-min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/iconly-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
            --landing-primary: #2A9D8F; /* Matches project teal color */
            --landing-secondary: #0d6efd;
            --landing-dark: #1e293b;
            --landing-light: #f8fafc;
        }

        body {
            font-family: 'Nunito Sans', sans-serif;
            overflow-x: hidden;
            background-color: #f3f4f6; /* Light gray background like dashboard */
        }

        /* Glassmorphic Navbar */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand img {
            height: 40px;
        }

        .nav-link {
            font-weight: 600;
            color: var(--landing-dark) !important;
            margin: 0 0.5rem;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--landing-primary) !important;
        }

        /* Hero Section */
        .hero-section {
            padding: 8rem 0 5rem;
            position: relative;
            background: linear-gradient(135deg, rgba(240, 253, 250, 1) 0%, rgba(204, 251, 241, 0.5) 100%);
            overflow: hidden;
        }
        
        /* Abstract Background Shapes */
        .shape-blob {
            position: absolute;
            background: linear-gradient(45deg, var(--landing-primary), #2dd4bf);
            opacity: 0.1;
            border-radius: 50%;
            filter: blur(60px);
            z-index: 0;
            animation: float 10s infinite ease-in-out;
        }
        
        .shape-1 { top: -10%; left: -5%; width: 500px; height: 500px; animation-delay: 0s; }
        .shape-2 { bottom: 10%; right: -5%; width: 400px; height: 400px; animation-delay: 5s; }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(10deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #1e293b 0%, #2A9D8F 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: #64748b;
            margin-bottom: 2.5rem;
            font-weight: 400;
            line-height: 1.6;
        }

        .hero-image-wrapper {
            position: relative;
            perspective: 1000px;
        }

        .hero-img {
            max-width: 100%;
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transition: transform 0.5s ease;
            transform-style: preserve-3d;
        }

        /* Feature Cards */
        .feature-card {
            background: white;
            border-radius: 1rem;
            padding: 2.5rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: rgba(42, 157, 143, 0.3);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            background: rgba(42, 157, 143, 0.15);
            color: var(--landing-primary);
        }

        .feature-card:hover .feature-icon {
            background: var(--landing-primary);
            color: white;
            transform: scale(1.1);
        }

        /* Buttons */
        .btn-lg-custom {
            padding: 0.8rem 2rem;
            font-size: 1.1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary-custom {
            background-color: var(--landing-primary);
            color: white;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(42, 157, 143, 0.4);
        }

        .btn-primary-custom:hover {
            background-color: #238478;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(42, 157, 143, 0.5);
        }
        
        .btn-outline-custom {
            background-color: transparent;
            color: var(--landing-dark);
            border: 2px solid #e2e8f0;
        }
        
        .btn-outline-custom:hover {
            border-color: var(--landing-dark);
            background-color: var(--landing-dark);
            color: white;
            transform: translateY(-2px);
        }

        /* Footer */
        .footer-section {
            background-color: #fff;
            padding: 4rem 0 2rem;
            border-top: 1px solid #e5e7eb;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3f4f6;
            color: #4b5563;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .social-btn:hover {
            background-color: var(--landing-primary);
            color: white;
        }
        
        @media (max-width: 991.98px) {
            .hero-title { font-size: 2.5rem; }
            .hero-section { padding-top: 6rem; text-align: center; }
            .hero-image-wrapper { margin-top: 3rem; }
            .hero-buttons { justify-content: center; }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Biyanow Logo">
                <span class="fw-bold text-dark d-none d-sm-block">Biyanow</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
                <div class="d-flex gap-3 mt-3 mt-lg-0">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary-custom btn-lg-custom fs-6 py-2">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-custom btn-lg-custom fs-6 py-2 px-3">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary-custom btn-lg-custom fs-6 py-2 px-3">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section d-flex align-items-center min-vh-100">
        <div class="shape-blob shape-1"></div>
        <div class="shape-blob shape-2"></div>
        
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title mb-4 animate-up">
                        Advance System Management for <span class="text-primary">Reliable Modern Banking</span>
                    </h1>
                    <p class="hero-subtitle animate-up delay-100">
                        Experience the next generation of financial management. Streamline operations, enhance security, and scale your business with Biyanow's comprehensive solution.
                    </p>
                    <div class="d-flex flex-wrap gap-3 hero-buttons animate-up delay-200">
                        <a href="{{ route('register') }}" class="btn btn-primary-custom btn-lg-custom">
                            Start Free Trial <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#features" class="btn btn-outline-custom btn-lg-custom">
                            Explore Features
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 hero-image-wrapper">
                    <div class="hero-img-container js-tilt" data-tilt-max="5" data-tilt-speed="400" data-tilt-perspective="1000">
                        <img src="{{ asset('assets/images/login/001.png') }}" alt="Dashboard Preview" class="hero-img img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5 max-w-700 mx-auto">
                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill mb-3 fw-bold">POWERFUL FEATURES</span>
                <h2 class="fw-bold mb-3 display-6">Everything you need to <span class="text-primary">succeed</span></h2>
                <p class="text-muted lead">Our platform provides comprehensive tools designed to boost productivity and ensure security.</p>
            </div>

            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Secure Transactions</h4>
                        <p class="text-muted mb-0">Enterprise-grade security ensuring your data and transactions are protected with the highest standards of encryption.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Real-time Analytics</h4>
                        <p class="text-muted mb-0">Gain actionable insights with our powerful dashboard. Monitor performance, track growth, and make data-driven decisions.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h4 class="fw-bold mb-3">User Management</h4>
                        <p class="text-muted mb-0">Effortlessly manage roles, permissions, and user profiles. Streamline onboarding and administration.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About / Stats Section -->
    <section id="about" class="py-5" style="background-color: #f8fafc;">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <img src="{{ asset('assets/images/login/001.png') }}" class="img-fluid rounded-4 shadow-lg" alt="About Us" style="transform: scale(0.9) rotate(-2deg);">
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <h2 class="fw-bold mb-4 display-6">Why Choose Biyanow?</h2>
                    <p class="text-muted mb-4 lead">We are committed to providing the best financial management solutions for modern businesses.</p>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <h2 class="fw-bold text-primary mb-0 me-2">99%</h2>
                                <span class="text-muted small lh-sm">Uptime<br>Guarantee</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <h2 class="fw-bold text-primary mb-0 me-2">24/7</h2>
                                <span class="text-muted small lh-sm">Customer<br>Support</span>
                            </div>
                        </div>
                    </div>
                    
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center text-muted">
                            <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i> Advanced Fraud Detection
                        </li>
                        <li class="mb-3 d-flex align-items-center text-muted">
                            <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i> Seamless Integration
                        </li>
                        <li class="mb-3 d-flex align-items-center text-muted">
                            <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i> Automated Reporting
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer-section">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none mb-3">
                        <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" height="40">
                        <span class="fw-bold text-dark fs-4">Biyanow</span>
                    </a>
                    <p class="text-muted mb-4">Empowering businesses with advanced financial tools. Reliable, secure, and efficient.</p>
                    <div class="d-flex gap-2">
                        <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <h5 class="fw-bold mb-3">Product</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Features</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Pricing</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Security</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <h5 class="fw-bold mb-3">Company</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Careers</a></li>
                        <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5 class="fw-bold mb-3">Newsletter</h5>
                    <p class="text-muted mb-3">Subscribe to get the latest news and updates.</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Enter your email">
                        <button class="btn btn-primary-custom" type="button">Subscribe</button>
                    </div>
                </div>
            </div>
            <div class="border-top pt-4 text-center text-muted">
                <p class="mb-0">&copy; {{ date('Y') }} Biyanow. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/animation/tilt/tilt.jquery.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Tilt
            $('.js-tilt').tilt({
                scale: 1.05,
                glare: true,
                maxGlare: 0.5
            });

            // Smooth Scroll
            $('a[href*="#"]').not('[href="#"]').not('[href="#0"]').click(function(event) {
                if (
                    location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
                    && 
                    location.hostname == this.hostname
                ) {
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    if (target.length) {
                        event.preventDefault();
                        $('html, body').animate({
                            scrollTop: target.offset().top - 80
                        }, 1000);
                    }
                }
            });
            
            // Navbar scroll effect
            $(window).scroll(function() {
                if ($(this).scrollTop() > 50) {
                    $('.navbar-glass').addClass('shadow-sm');
                } else {
                    $('.navbar-glass').removeClass('shadow-sm');
                }
            });
        });
    </script>
</body>
</html>