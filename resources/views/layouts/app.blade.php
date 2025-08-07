<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Fee24mfb - {{ $title ?? 'Dashboard' }}</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Admiro admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities." />
    <meta name="keywords" content="admin template, Admiro admin template, best javascript admin, dashboard template, bootstrap admin template, responsive admin template, web app" />
    <meta name="author" content="pixelstrap" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/logo/logo.png') }}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/logo.png') }}" type="image/x-icon" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200..1000&display=swap" rel="stylesheet" />

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/flag-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/iconly-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bulk-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/themify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome-min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/weather-icons/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendors/slick-theme.css') }}">

    <!-- App CSS -->
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- Bootstrap Icons & Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <!-- tap on top starts -->
    <div class="tap-top"><i class="iconly-Arrow-Up icli"></i></div>

    <!-- loader -->
    <div class="loader-wrapper">
        <div class="loader"><span></span><span></span><span></span><span></span><span></span></div>
    </div>

    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        @include('layouts.partials.header')

        <div class="page-body-wrapper">
            @include('layouts.partials.sidebar')

            <div class="page-body">
                <div class="container-fluid">
                    @isset($header)
                        <div class="page-title">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h2>{{ $header }}</h2>
                                </div>
                            </div>
                        </div>
                    @endisset

                    <main>
                        {{ $slot ?? '' }}
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 footer-copyright">
                        <p class="mb-0">Copyright 2024 © Fee24mfb.</p>
                    </div>
                    <div class="col-md-6">
                        <svg class="svg-color footer-icon">
                            <use href="https://admin.pixelstrap.net/admiro/assets/svg/iconly-sprite.svg#heart"></use>
                        </svg>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- JS Vendors -->
    <script src="{{ asset('assets/js/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/vendors/bootstrap/dist/js/popper.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/vendors/font-awesome/fontawesome-min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors/feather-icon/custom-script.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar.js') }}"></script>
    <script src="{{ asset('assets/js/height-equal.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/scrollbar/simplebar.js') }}"></script>
    <script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>
    <script src="{{ asset('assets/js/slick/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/slick/slick.js') }}"></script>
    <script src="{{ asset('assets/js/js-datatables/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/js-datatables/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/js-datatables/datatables/datatable.custom1.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/theme-customizer/customizer.js') }}"></script>
    <script src="{{ asset('assets/js/animation/tilt/tilt.jquery.js') }}"></script>
    <script src="{{ asset('assets/js/animation/tilt/tilt-custom.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/dashboard_1.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

    @stack('scripts')
</body>
</html>
