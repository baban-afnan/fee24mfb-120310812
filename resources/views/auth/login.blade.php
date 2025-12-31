<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiyaNow - Agency Admin Login</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/logo/logo.png') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,300..900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2A9D8F; /* Project Teal */
            --primary-hover: #238478;
            --bg-light: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Nunito Sans', sans-serif;
            background-color: var(--bg-light);
            min-height: 100vh;
            margin: 0;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            width: 100%;
        }

        /* Left Side - Visual */
        .visual-side {
            flex: 1;
            background: linear-gradient(135deg, #1e293b 0%, #2A9D8F 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 3rem;
            overflow: hidden;
            height: 100vh;
            position: sticky;
            top: 0;
        }

        .visual-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("{{ asset('assets/images/login/login_bg.jpg') }}") center/cover no-repeat;
            opacity: 0.1;
            mix-blend-mode: overlay;
        }

        .circles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .circles li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.2);
            animation: animate 25s linear infinite;
            bottom: -150px;
            border-radius: 50%;
        }
        
        /* Animation for background circles */
        .circles li:nth-child(1){ left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .circles li:nth-child(2){ left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .circles li:nth-child(3){ left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .circles li:nth-child(4){ left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .circles li:nth-child(5){ left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
        .circles li:nth-child(6){ left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
        .circles li:nth-child(7){ left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
        .circles li:nth-child(8){ left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
        .circles li:nth-child(9){ left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
        .circles li:nth-child(10){ left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }
        @keyframes animate {
            0%{ transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; }
            100%{ transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }

        .visual-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 500px;
        }

        .feature-img {
            max-width: 100%;
            height: auto;
            filter: drop-shadow(0 20px 30px rgba(0,0,0,0.3));
            margin-top: 2rem;
            border-radius: 12px;
            transform: perspective(1000px) rotateY(-10deg) rotateX(5deg);
            transition: transform 0.5s ease;
        }
        
        .feature-img-container:hover .feature-img {
             transform: perspective(1000px) rotateY(-5deg) rotateX(2deg) scale(1.02);
        }

        /* Right Side - Form */
        .form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 10;
            background: white;
            min-height: 100vh;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
        }

        .logo-area {
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .logo-area img {
            height: 60px;
            margin-bottom: 1rem;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--primary-color);
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem 0.75rem;
            height: calc(3.5rem + 2px);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(42, 157, 143, 0.1);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.8rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(42, 157, 143, 0.3);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .visual-side {
                display: none;
            }
            .form-side {
                background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
            }
            .login-card {
                background: white;
                padding: 2.5rem;
                border-radius: 20px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            }
        }

        @media (max-width: 576px) {
            .form-side {
                padding: 1rem;
                align-items: center; /* Center vertically on mobile too */
            }
            .login-card {
                padding: 1.5rem;
                border-radius: 16px;
                box-shadow: none;
                background: transparent;
            }
            .logo-area img {
                height: 50px;
            }
            .btn-primary {
                padding: 0.7rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Visual Side -->
        <div class="visual-side">
            <ul class="circles">
                <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
            </ul>
            
            <div class="visual-content">
                <h1 class="display-4 fw-bold mb-3">Welcome Back!</h1>
                <p class="lead opacity-75 mb-4">Access the BiyaNow Admin Dashboard to manage your operations efficiently.</p>
                <div class="feature-img-container">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" class="feature-img" alt="Dashboard Preview" width="300" height="200">
                </div>
            </div>
        </div>

        <!-- Form Side -->
        <div class="form-side">
            <div class="login-card">
                <div class="logo-area">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="BiyaNow Logo">
                    <h4 class="fw-bold text-dark">Sign In</h4>
                    <p class="text-muted small">Enter your credentials to continue</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    @if($errors->any())
                        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger mb-4 rounded-3 small">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- Email Input -->
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                        <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
                    </div>

                    <!-- Password Input -->
                    <div class="form-floating mb-4 position-relative">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                        <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted text-decoration-none me-2" id="togglePassword">
                            <i class="bi bi-eye-slash" id="toggleIcon"></i>
                        </button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label text-muted small" for="remember">
                                Remember me
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="small text-decoration-none" style="color: var(--primary-color);">Forgot Password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 shadow-sm">
                        Sign In Now <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                    
                    <div class="text-center mt-4">
                        <p class="small text-muted mb-0">Don't have an account? <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: var(--primary-color);">Create Account</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/vendors/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Password Toggle
            $('#togglePassword').click(function() {
                const passwordInput = $('#password');
                const icon = $('#toggleIcon');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                }
            });
            
            // Subtle tilt for hero image
            $(document).on('mousemove', function(e) {
                const img = $('.feature-img');
                if (img.length && window.innerWidth > 992) {
                    const xAxis = (window.innerWidth / 2 - e.pageX) / 40;
                    const yAxis = (window.innerHeight / 2 - e.pageY) / 40;
                    img.css('transform', `perspective(1000px) rotateY(${xAxis}deg) rotateX(${yAxis}deg)`);
                }
            });
        });
    </script>
</body>
</html>
