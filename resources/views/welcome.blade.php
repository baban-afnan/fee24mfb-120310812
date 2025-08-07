<!DOCTYPE html>
<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <title>Fee24 advance system management</title>

    <meta name="author" content="themesflat.com">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Theme Style -->
    <link rel="stylesheet" type="text/css" href="css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="css/animation.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">



    <!-- Font -->
    <link rel="stylesheet" href="font/fonts.css">

    <!-- Icon -->
    <link rel="stylesheet" href="icon/style.css">

    <!-- Favicon icon-->
    <link rel="icon" href="images/logo/logo-dark.png" type="image/x-icon">
    <link rel="shortcut icon" href="images/logo/logo-dark.png" type="image/x-icon">

</head>

<body class="body">

    <!-- #wrapper -->
    <div id="wrapper">
        <!-- #page -->
        <div id="page" class="">
            <!-- layout-wrap -->
           <div class="layout-wrap">
                <!-- preload -->
                <div id="preload" class="preload-container">
                    <div class="preloading">
                        <span></span>
                    </div>
                </div>
                <!-- /preload -->
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Nunito Sans', sans-serif;
        }
        
        body {
            color: var(--dark);
            line-height: 1.6;
            background-color: #f8fafc;
            perspective: 1000px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        /* 3D Card Effect */
        .card-3d {
            transform-style: preserve-3d;
            transition: transform 0.5s ease, box-shadow 0.5s ease;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        }
        
        .card-3d:hover {
            transform: rotateY(-5deg) rotateX(5deg) translateY(-10px);
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.2);
        }
        
        /* Button Effects */
        .btn-3d {
            transition: all 0.3s ease;
            transform: translateZ(0);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .btn-3d:hover {
            transform: translateY(-3px) translateZ(10px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
        }
        
        .btn-3d:active {
            transform: translateY(1px) translateZ(5px);
        }
        
        /* Glass Effect */
        .glass-effect {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, rgba(240, 249, 255, 0.9) 0%, rgba(224, 242, 254, 0.9) 100%), 
            url('https://images.unsplash.com/photo-1639762681057-408e52192e55?q=80&w=2232&auto=format&fit=crop') no-repeat center center;
            background-size: cover;
            position: relative;
            padding: 8rem 0 5rem;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 0;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 3rem;
        }
        
        .hero-text {
            flex: 1;
            color: white;
        }
        
        .hero-image {
            flex: 1;
        }
        
        .hero-image img {
            width: 100%;
            border-radius: 0.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            transform-style: preserve-3d;
            transition: transform 0.5s ease;
        }
        
        .hero-image:hover img {
            transform: rotateY(-10deg) rotateX(5deg);
        }
        
        h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            max-width: 600px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        
        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            margin-top: 2.5rem;
        }
        
        .primary-button {
            background-color: var(--primary);
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .primary-button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
        }
        
        .secondary-button {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s;
            backdrop-filter: blur(5px);
        }
        
        .secondary-button:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Auth Buttons */
        .auth-buttons {
            position: absolute;
            top: 2rem;
            right: 2rem;
            display: flex;
            gap: 1rem;
            z-index: 10;
        }
        
        .login-button {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s;
            backdrop-filter: blur(5px);
        }
        
        .login-button:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .register-button {
            background-color: var(--primary);
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .register-button:hover {
            background-color: var(--primary-dark);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .hero-content {
                flex-direction: column;
                text-align: center;
            }
            
            .hero-text {
                margin-bottom: 3rem;
            }
            
            .hero-buttons {
                justify-content: center;
            }
            
            h1 {
                font-size: 2.8rem;
            }
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 2.2rem;
            }
            
            .subtitle {
                font-size: 1.1rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .auth-buttons {
                top: 1rem;
                right: 1rem;
            }
        }
    </style>
</head>
<body>
  
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Welcome to FEE24MFB Management</h1>
                    <p class="subtitle">Streamline your operations with our powerful management system designed for efficiency and productivity.</p>
                    <div class="hero-buttons">
                        <a href="{{ route('login') }}" class="primary-button">
                            Get Started <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                        <a href="#features" class="secondary-button">
                            Learn More <i class="fas fa-info-circle ml-2"></i>
                        </a>
                    </div>
                </div>
                <div class="hero-image card-3d">
                    <img src="{{ asset('assets/images/login/001.png') }}" alt="FEE24MFB Dashboard Preview">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section (Optional) -->
    <!-- <section id="features" class="features-section">
        ... feature cards ...
    </section> -->

    <script>
        // Add subtle mouse movement parallax effect
        document.addEventListener('mousemove', function(e) {
            const hero = document.querySelector('.hero-content');
            const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
            hero.style.transform = `translateY(${yAxis}px) translateX(${xAxis}px)`;
        });
    </script>
 <!-- Javascript -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/zoom.js"></script>
    <script src="js/apexcharts/apexcharts.js"></script>
    <script src="js/apexcharts/line-chart-1.js"></script>
    <script src="js/apexcharts/line-chart-2.js"></script>
    <script src="js/apexcharts/line-chart-3.js"></script>
    <script src="js/apexcharts/line-chart-4.js"></script>
    <script src="js/apexcharts/line-chart-5.js"></script>
    <script src="js/apexcharts/line-chart-6.js"></script>
    <script src="js/switcher.js"></script>
    <script src="js/theme-settings.js"></script>
    <script src="js/main.js"></script>

</body>


</html>