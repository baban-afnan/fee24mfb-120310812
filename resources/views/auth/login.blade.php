<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee24mfb - Agency Admin</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/logo/logo.png') }}" type="image/x-icon">

    <!-- Project Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}" id="color" media="screen">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/flag-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/themify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/iconly-icon.css') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="loader-wrapper">
        <div class="loader"><span></span><span></span><span></span><span></span><span></span></div>
    </div>

    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-12 p-0">
                <div class="login-card login-dark">
                    <div>
                        <div class="text-center">
                        </div>
                        <div class="login-main">
                            <form method="POST" action="{{ route('login') }}" class="theme-form">
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                @csrf
                              
                                
                                
                              <div style="text-align: center;">
                            <img src="assets/images/logo/logo.png" alt="Logo" style="max-width: 30px; margin-bottom: 30px;">
                            </div>

                              @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                <!-- Email -->
                                <div class="form-group">
                                    <label class="col-form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                        <input class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com">
                                    </div>
                                  
                                </div>

                                <!-- Password -->
                                <div class="form-group">
                                    <label class="col-form-label">Password</label>
                                    <div class="input-group position-relative">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input class="form-control" type="password" name="password" required placeholder="********">
                                    </div>
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Remember Me and Forgot Password -->
                                <div class="form-group mb-0 d-flex justify-content-between align-items-center">
                                    <div class="form-check checkbox-solid-info">
                                        <input class="form-check-input" id="remember_me" type="checkbox" name="remember">
                                        <label class="form-check-label" for="remember_me">Remember password</label>
                                    </div>
                                </div>

                                <div class="text-end mt-3">
                                    <button class="btn btn-primary btn-block w-100" type="submit">Sign in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/password.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
