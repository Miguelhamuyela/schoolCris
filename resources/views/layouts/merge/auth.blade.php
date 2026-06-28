<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Instituto Politécnico Privado Pitágoras de Samos - @yield('title')</title>
    <meta content="Sistema de Gestão Escolar do Instituto Politécnico Privado Pitágoras de Samos" name="description">
    <meta content="escola, gestão escolar, educação, instituto politécnico" name="keywords">

    <!-- Favicons -->
    <link rel="shortcut icon" type="image/x-icon" href="/dashboard/assets/img/favicon.ico">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,400,500,600,700"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/site/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="/site/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/site/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/site/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="/site/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="/site/assets/css/style.css" rel="stylesheet">
    <!-- Custom Theme CSS - Tema Laranja -->
    <link href="/site/assets/css/custom-theme.css" rel="stylesheet">

    <style>
        body {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 0;
            font-weight: 400;
            background: url('/dashboard/assets/img/login-bg.jpg') center center no-repeat;
            background-size: cover;
            overflow: hidden;
        }

        /* Overlay escuro para opacidade */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at top, rgba(0,0,0,0.65), rgba(0,0,0,0.95));
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 1;
            background: radial-gradient(circle at top left, rgba(255,255,255,0.55), rgba(255,255,255,0.35));
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-radius: 18px;
            box-shadow:
                0 24px 60px rgba(15, 23, 42, 0.45),
                0 0 0 1px rgba(148, 163, 184, 0.3);
            padding: 32px 28px;
            max-width: 460px;
            width: 96%;
            animation: fadeIn 0.55s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }
        
        .login-logo img {
            max-height: 82px;
            margin: 0 auto;
            transition: transform 0.18s ease, filter 0.18s ease;
            filter: drop-shadow(0 10px 25px rgba(15,23,42,0.55));
        }
        
        .login-logo img:hover {
            transform: translateY(-1px) scale(1.02);
            filter: drop-shadow(0 16px 35px rgba(15,23,42,0.7));
        }

        .btn-primary {
            transition: all 0.18s ease;
            border-radius: 999px;
            font-weight: 600;
            padding: 10px 22px;
            letter-spacing: 0.02em;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.35);
        }
        
        .btn-primary:hover {
            transform: translateY(-1px) scale(1.01);
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.45);
        }
        
        .form-control {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid rgba(148, 163, 184, 0.6);
            box-shadow: none;
            transition: all 0.16s ease;
            font-size: 0.95rem;
            background-color: rgba(255,255,255,0.9);
        }
        
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 1px rgba(79, 70, 229, 0.35);
            background-color: #ffffff;
        }
        
        .input-group-text {
            border-radius: 999px 0 0 999px;
            padding: 0 14px;
            font-size: 0.9rem;
        }
        
        .input-group .form-control {
            border-radius: 0 999px 999px 0;
        }
        
        .alert {
            border-radius: 6px;
            font-size: 0.9rem;
        }
        
        .card {
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        label {
            font-size: 0.9rem;
            color: #555;
        }

        .text-primary {
            color: #4e73df !important;
        }

        h4 {
            font-size: 1.3rem;
            letter-spacing: -0.02rem;
        }

        .small {
            font-size: 0.8rem;
        }

        h1 {
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.4);
        }
    </style>
</head>

<body>
    <div class="container login-container">
        @yield('content')
        <div class="text-center mt-3">
            <p class="text-dark fw-bold mb-0" style="font-size: 0.8rem; text-shadow: none;">Instituto Politécnico Privado Pitágoras de Samos &copy; {{ date('Y') }}</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var input = document.getElementById("form1example23");
            var eye = document.getElementById("eye");

            window.visualizarSenha = function() {
                if (input.type == "password") {
                    eye.src = "/eye-open.png";
                    input.type = "text";
                } else {
                    eye.src = "/eye.png";
                    input.type = "password";
                }
            };
        });
    </script>

</body>

</html>
