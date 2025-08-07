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
                            <div style="text-align: center;">
                            <img src="assets/images/logo/logo.png" alt="Logo" style="max-width: 30px; margin-bottom: 30px;">
                            <div class="body-text">You are not allow to register</div>
                        </div>
                        <div style="text-align: center;">
                       <img src="assets/images/logo/jerry.jpg" alt="Logo" style="max-width: 300px; margin-bottom: 50px;">
                         <div class="text-tiny mb-16 text-center">For any complain contact system administrator</div>
                       </div>
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

<style>
    body.body {
        background-image: url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
    }

    .login-container {
        display: flex;
        min-height: 100vh;
    }

    

    .login-content {
        width: 70%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.9);
    }

    .login-box {
        width: 100%;
        max-width: 450px;
        padding: 40px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .password-wrapper {
        position: relative;
    }

    .password-input {
        padding-right: 40px;
        width: 100%;
    }

    .show-pass {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #777;
    }

    .hide {
        display: none;
    }

    @media (max-width: 768px) {
        .login-container {
            flex-direction: column;
        }

        .advert-container {
            display: none;
        }

        .login-content {
            width: 100%;
            padding: 20px;
        }

        .login-box {
            padding: 30px;
        }
    }
</style>
