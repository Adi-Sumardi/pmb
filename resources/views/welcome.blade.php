<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-touch-fullscreen" content="yes">
    <title>Pendaftaran Murid Baru - YAPI</title>
        <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-yapi.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d6efd" />

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #fd7e14;
            --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            background: var(--gradient-bg);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .hero-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }

        .form-header {
            background: var(--gradient-bg);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .form-header::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: white;
            border-radius: 2px;
        }

        .logo-container {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            font-size: 16px; /* Prevent zoom on iOS */
            background-color: white;
            -webkit-user-select: text;
            -webkit-touch-callout: default;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
            transform: translateY(-2px);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .btn-submit {
            background: var(--gradient-bg);
            border: none;
            border-radius: 50px;
            padding: 1rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px 0 0 10px;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            transition: all 0.3s ease;
        }

        .file-input-wrapper:hover {
            border-color: var(--primary-color);
            background: rgba(13, 110, 253, 0.05);
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-upload-btn {
            display: block;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            color: #6c757d;
            font-weight: 500;
        }

        .preview-image {
            border-radius: 10px;
            border: 3px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .preview-image:hover {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        .section-divider {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
            margin: 2rem 0;
        }

        .floating-label {
            position: relative;
        }

        .floating-label .form-control:focus + .form-label,
        .floating-label .form-control:not(:placeholder-shown) + .form-label {
            transform: translateY(-1.5rem) scale(0.8);
            color: var(--primary-color);
        }

        .login-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .btn-login {
            background: rgba(255, 255, 255, 0.9);
            color: #495057;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .btn-login:hover {
            background: white;
            color: var(--primary-color);
            border-color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .info-section {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-left: 5px solid var(--primary-color);
        }

        .cambridge-logo-container {
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 2px solid #f8f9fa;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 80px;
            position: relative;
            overflow: hidden;
        }

        .cambridge-logo-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(13, 110, 253, 0.05), transparent);
            transition: left 0.8s ease;
        }

        .cambridge-logo-container:hover::before {
            left: 100%;
        }

        .cambridge-logo-container:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-color);
        }

        .cambridge-logo {
            max-width: 100%;
            height: auto;
            max-height: 60px;
            object-fit: contain;
            transition: transform 0.3s ease;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.1));
        }

        .cambridge-logo-container:hover .cambridge-logo {
            transform: scale(1.05);
        }

        .cambridge-logo-fallback {
            display: none;
            background: linear-gradient(135deg, #003f7f, #0066cc);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 4px 15px rgba(0, 63, 127, 0.3);
        }

        .logo-slider-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin: 1.5rem 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
            overflow: hidden;
        }

        .logo-slider-title {
            text-align: center;
            margin-bottom: 20px;
            color: #495057;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .logo-slider {
            overflow: hidden;
            position: relative;
            background: linear-gradient(90deg, rgba(255,255,255,0.9) 0%, transparent 15%, transparent 85%, rgba(255,255,255,0.9) 100%);
            height: 70px;
        }

        .logo-track {
            display: flex;
            align-items: center;
            animation: slide 25s linear infinite;
            /* Width untuk 12 logo (6 asli + 6 duplikat) */
            width: calc(12 * 120px);
            height: 100%;
        }

        .logo-item {
            flex: 0 0 auto;
            width: 100px;
            height: 60px;
            margin: 0 10px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #f0f0f0;
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .logo-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .logo-item img {
            max-width: 85%;
            max-height: 85%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .logo-item:hover img {
            transform: scale(1.1);
        }

        .logo-fallback {
            font-size: 0.7rem;
            font-weight: bold;
            color: var(--primary-color);
            text-align: center;
            padding: 5px;
            display: none;
        }

        @keyframes slide {
            0% {
                transform: translateX(0);
            }
            100% {
                /* Move exactly half the width (6 logo positions) */
                transform: translateX(calc(-6 * 120px));
            }
        }

        .logo-slider:hover .logo-track {
            animation-play-state: paused;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        /* Enhanced mobile devices optimization */
        @media screen and (max-width: 768px) {
            .hero-section {
                margin: 0.5rem;
                border-radius: 15px;
                padding: 1rem;
            }

            .form-card {
                margin: 0.5rem;
                border-radius: 15px;
            }

            .form-header {
                padding: 1.5rem 1rem;
            }

            .logo-container {
                width: 100px;
                height: 100px;
                margin-bottom: 0.5rem;
            }

            .cambridge-logo-container {
                padding: 12px;
                min-height: 70px;
                margin-top: 15px;
            }

            .cambridge-logo {
                max-height: 50px;
            }

            .cambridge-logo-fallback {
                font-size: 0.8rem;
                padding: 10px 16px;
            }

            .logo-slider-section {
                padding: 15px;
                margin: 1rem 0;
            }

            .logo-slider {
                height: 60px;
            }

            .logo-item {
                width: 80px;
                height: 50px;
                margin: 0 8px;
            }

            .logo-track {
                width: calc(12 * 96px);
                animation-duration: 20s;
            }

            @keyframes slide {
                0% {
                    transform: translateX(0);
                }
                100% {
                    transform: translateX(calc(-6 * 96px));
                }
            }

            .logo-slider-title {
                font-size: 0.8rem;
                margin-bottom: 15px;
            }

            /* Enhanced form controls for mobile */
            .form-control,
            .form-select {
                font-size: 16px !important; /* Prevent zoom on iOS */
                padding: 1rem 0.75rem;
                min-height: 48px; /* Touch-friendly minimum height */
                border-width: 1px;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
            }

            /* Enhanced button styling for mobile */
            .btn-submit {
                width: 100%;
                padding: 1.25rem 2rem;
                font-size: 1.1rem;
                min-height: 56px; /* Touch-friendly height */
            }

            .btn-login {
                padding: 1rem 1.5rem;
                min-height: 48px;
                font-size: 0.9rem;
            }

            /* File input optimization for mobile */
            .file-input-wrapper {
                min-height: 80px;
                border-width: 2px;
            }

            .file-upload-btn {
                padding: 1.5rem 1rem;
                min-height: 80px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            .preview-image {
                max-width: 100%;
                height: auto;
            }

            /* Enhanced spacing for mobile */
            .container {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .col-12, .col-md-6 {
                margin-bottom: 1.5rem;
            }

            /* Touch-friendly form validation messages */
            .invalid-feedback {
                font-size: 0.9rem;
                margin-top: 0.5rem;
            }

            .is-invalid {
                border-color: #dc3545 !important;
                border-width: 2px !important;
            }
        }

        @media (max-width: 576px) {
            .hero-section,
            .form-card {
                margin: 0.25rem;
                border-radius: 12px;
            }

            .form-header {
                padding: 1rem;
            }

            .logo-container {
                width: 80px;
                height: 80px;
            }

            .cambridge-logo-container {
                padding: 10px;
                min-height: 60px;
            }

            .cambridge-logo {
                max-height: 40px;
            }

            .cambridge-logo-fallback {
                font-size: 0.75rem;
                padding: 8px 12px;
            }

            .logo-slider {
                height: 50px;
            }

            .logo-item {
                width: 70px;
                height: 45px;
                margin: 0 6px;
            }

            .logo-track {
                width: calc(12 * 82px);
                animation-duration: 18s;
            }

            @keyframes slide {
                0% {
                    transform: translateX(0);
                }
                100% {
                    transform: translateX(calc(-6 * 82px));
                }
            }

            .logo-slider-title {
                font-size: 0.75rem;
            }

            /* Extra small screens optimizations */
            .form-control,
            .form-select {
                font-size: 16px !important;
                padding: 1rem 0.5rem;
            }

            .btn-submit {
                padding: 1rem 1.5rem;
                font-size: 1rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            h4 {
                font-size: 1.1rem;
            }

            h5 {
                font-size: 1rem;
            }

            .container {
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }
        }

        /* Enhanced iOS Safari Fixes dengan Android compatibility */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            .form-control:focus,
            .form-select:focus {
                font-size: 16px; /* Prevent zoom */
                -webkit-user-select: text;
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
            }

            input[type="text"],
            input[type="email"],
            input[type="tel"],
            input[type="number"],
            input[type="date"],
            select,
            textarea {
                font-size: 16px !important; /* Prevent zoom on iOS */
                -webkit-appearance: none;
                border-radius: 10px;
                -webkit-touch-callout: none;
                -webkit-user-select: text;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
            }

            /* Enhanced button styling for iOS */
            button {
                -webkit-appearance: none;
                border-radius: 10px;
                -webkit-touch-callout: none;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
                cursor: pointer;
            }

            /* Enhanced file input for iOS */
            input[type="file"] {
                -webkit-appearance: none;
                -webkit-touch-callout: none;
            }

            /* iOS specific form validation styling */
            .form-control:invalid,
            .form-select:invalid {
                border-color: #dc3545;
                box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
            }

            .form-control:valid,
            .form-select:valid {
                border-color: #198754;
            }

            /* Touch-friendly clickable areas */
            .file-upload-btn,
            label {
                -webkit-touch-callout: none;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
                cursor: pointer;
                min-height: 44px; /* iOS recommended minimum touch target */
                display: flex;
                align-items: center;
            }

            /* iOS textarea resize fix */
            textarea {
                resize: vertical;
                -webkit-appearance: none;
                min-height: 100px;
            }

            /* iOS select dropdown styling */
            select {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
                background-position: right 0.5rem center;
                background-repeat: no-repeat;
                background-size: 1.5em 1.5em;
                padding-right: 2.5rem;
            }
        }

        /* Android specific optimizations */
        @media screen and (max-width: 768px) and (-webkit-min-device-pixel-ratio: 1) {
            /* Android Chrome optimizations */
            input, select, textarea, button {
                font-size: 16px !important; /* Prevent zoom */
                -webkit-text-size-adjust: 100%;
                text-size-adjust: 100%;
            }

            /* Android touch improvements */
            .form-control,
            .form-select,
            .btn {
                touch-action: manipulation;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
            }

            /* Android keyboard optimization */
            input[type="tel"] {
                pattern: "[0-9]*";
                inputmode: "numeric";
            }

            input[type="email"] {
                inputmode: "email";
            }
        }

        /* Universal mobile fixes */
        @media screen and (max-width: 768px) {
            .form-control,
            .form-select,
            input,
            select,
            textarea {
                font-size: 16px !important; /* Prevent zoom */
                -webkit-text-size-adjust: 100%;
                text-size-adjust: 100%;
                min-height: 48px; /* Touch-friendly minimum */
                padding: 0.75rem;
            }

            /* Enhanced touch targets */
            .btn,
            button,
            .file-upload-btn,
            label[for] {
                min-height: 48px;
                min-width: 48px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
            }

            /* Loading state for form submission */
            .submitting {
                pointer-events: none;
                opacity: 0.7;
            }

            .submitting button {
                cursor: not-allowed;
            }

            /* Mobile-friendly validation messages */
            .invalid-feedback {
                display: block;
                width: 100%;
                margin-top: 0.25rem;
                font-size: 0.875rem;
                color: #dc3545;
                background: rgba(220, 53, 69, 0.1);
                padding: 0.5rem;
                border-radius: 0.375rem;
                border-left: 3px solid #dc3545;
            }

            .is-invalid {
                border-color: #dc3545 !important;
                box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15) !important;
                animation: shake 0.5s ease-in-out;
            }

            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }

            /* Mobile viewport fix */
            .container-fluid {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            /* Improve scrolling on mobile */
            body {
                -webkit-overflow-scrolling: touch;
                overflow-scrolling: touch;
            }
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <div class="container mt-4">
        <div class="hero-section animate-on-scroll">
            <div class="login-section p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-2 text-primary fw-bold">
                            <i class="bi bi-mortarboard-fill me-2"></i>
                            Sudah pernah mendaftar?
                        </h5>
                        <p class="text-muted mb-0">Login untuk melihat status pendaftaran dan melakukan pembayaran</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('login') }}" class="btn btn-login">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Login Disini
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="form-card animate-on-scroll">
                    <!-- Form Header -->
                    <div class="form-header">
                        <div class="logo-container pulse-animation">
                            <i class="bi bi-mortarboard-fill text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h1 class="mb-2 fw-bold">Formulir Pendaftaran Murid Baru</h1>
                        <h4 class="mb-3 opacity-75">Tahun Ajaran 2026/2027</h4>
                        <p class="mb-0 fw-semibold">
                            <i class="bi bi-geo-alt-fill me-2"></i>
                            Kampus YAPI Al Azhar Rawamangun & Jatimakmur
                        </p>
                    </div>

                    <!-- Update bagian HTML Logo Partners Slider -->
                    <div class="container-fluid p-4 pb-0">
                        <div class="logo-slider-section animate-on-scroll">
                            <div class="logo-slider-title">
                                <i class="bi bi-award me-1"></i>
                                Partner Pendidikan & Unit Sekolah
                            </div>
                            <div class="logo-slider">
                                <div class="logo-track">
                                    <!-- Set pertama (original) -->
                                    <div class="logo-item">
                                        <img src="{{ asset('images/cambridge.png') }}"
                                            alt="Cambridge Assessment"
                                            onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div class="logo-fallback">Cambridge</div>
                                    </div>

                                    <div class="logo-item">
                                        <img src="{{ asset('images/logo-ypi.png') }}"
                                            alt="Yayasan Pendidikan Islam"
                                            onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div class="logo-fallback">YPI</div>
                                    </div>

                                    <div class="logo-item">
                                        <img src="{{ asset('images/logo-yapi.png') }}"
                                            alt="YAPI Al Azhar"
                                            onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div class="logo-fallback">YAPI</div>
                                    </div>

                                    <div class="logo-item">
                                        <img src="{{ asset('images/pg.png') }}"
                                            alt="Playgroup"
                                            onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div class="logo-fallback">PG</div>
                                    </div>

                                    <div class="logo-item">
                                        <img src="{{ asset('images/ra.png') }}"
                                            alt="Raudhatul Athfal"
                                            onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div class="logo-fallback">RA</div>
                                    </div>

                                    <div class="logo-item">
                                        <img src="{{ asset('images/igs.png') }}"
                                            alt="Islamic Global School"
                                            onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div class="logo-fallback">IGS</div>
                                    </div>

                                    <!-- Set kedua (duplikat untuk seamless loop) -->
                                    <div class="logo-item">
                                        <img src="{{ asset('images/cambridge.png') }}"
                                            alt="Cambridge Assessment"
                                            onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div class="logo-fallback">Cambridge</div>
                                    </div>

                                    <div class="logo-item">
                                        <img src="{{ asset('images/logo-ypi.png') }}"
                                            alt="Yayasan Pendidikan Islam"
                                            onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div class="logo-fallback">YPI</div>
                                    </div>

                                    <div class="logo-item">
                                        <img src="{{ asset('images/logo-yapi.png') }}"
                                            alt="YAPI Al Azhar"
                                            onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div class="logo-fallback">YAPI</div>
                                    </div>

                                    <div class="logo-item">
                                        <img src="{{ asset('images/pg.png') }}"
                                            alt="Playgroup"
                                            onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div class="logo-fallback">PG</div>
                                    </div>

                                    <div class="logo-item">
                                        <img src="{{ asset('images/ra.png') }}"
                                            alt="Raudhatul Athfal"
                                            onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div class="logo-fallback">RA</div>
                                    </div>

                                    <div class="logo-item">
                                        <img src="{{ asset('images/igs.png') }}"
                                            alt="Islamic Global School"
                                            onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div class="logo-fallback">IGS</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Information Section -->
                    <div class="container-fluid p-4">
                        <div class="info-section animate-on-scroll">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="fw-bold text-primary mb-2">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        Informasi Pendaftaran
                                    </h6>
                                    <small class="text-muted">
                                        Silakan lengkapi formulir dengan data yang benar.
                                    </small>
                                </div>
                                <div class="col-md-4 text-md-center mt-3 mt-md-0">
                                    <div class="cambridge-logo-container">
                                        <img src="{{ asset('images/cambridge.png') }}"
                                            alt="Cambridge Assessment International Education"
                                            class="cambridge-logo"
                                            onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="cambridge-logo-fallback">
                                            Cambridge Assessment
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <div class="p-4 pt-0">
                        <form action="{{ route('pendaftaran.store') }}" method="POST" class="row g-4" enctype="multipart/form-data" id="registrationForm">
                            @csrf

                            <!-- Data Murid Section -->
                            <div class="col-12">
                                <h5 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-person-circle me-2"></i>Data Calon Murid
                                </h5>
                                <hr class="section-divider">
                            </div>

                            <!-- Nama Calon Murid -->
                            <div class="col-12 animate-on-scroll">
                                <label class="form-label">
                                    <i class="bi bi-person me-1"></i>Nama Lengkap Calon Murid
                                </label>
                                <input type="text" name="nama_murid" class="form-control" required
                                       placeholder="Masukkan nama lengkap calon murid">
                            </div>

                            <!-- NISN & Tanggal Lahir -->
                            <div class="col-md-6 animate-on-scroll">
                                <label class="form-label">
                                    <i class="bi bi-credit-card-2-front me-1"></i>NISN (opsional)
                                </label>
                                <input type="text" name="nisn" id="nisn" class="form-control"
                                    placeholder="Nomor Induk Siswa Nasional (10 digit)"
                                    maxlength="10"
                                    pattern="[0-9]{10}"
                                    title="NISN harus terdiri dari 10 digit angka">
                                <div class="invalid-feedback" id="nisn-error"></div>
                                <small class="text-muted">NISN harus tepat 10 digit angka. Kosongkan jika tidak ada.</small>
                            </div>

                            <div class="col-md-6 animate-on-scroll">
                                <label class="form-label">
                                    <i class="bi bi-calendar-event me-1"></i>Tanggal Lahir
                                </label>
                                <input type="date" name="tanggal_lahir" class="form-control" required>
                            </div>

                            <!-- Alamat -->
                            <div class="col-12 animate-on-scroll">
                                <label class="form-label">
                                    <i class="bi bi-geo-alt me-1"></i>Alamat Lengkap
                                </label>
                                <textarea name="alamat" rows="3" class="form-control" required
                                          placeholder="Masukkan alamat lengkap tempat tinggal"></textarea>
                            </div>

                            <!-- Pendidikan Section -->
                            <div class="col-12 mt-5">
                                <h5 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-mortarboard me-2"></i>Informasi Pendidikan
                                </h5>
                                <hr class="section-divider">
                            </div>

                            <!-- Jenjang & Unit -->
                            <div class="col-md-6 animate-on-scroll">
                                <label class="form-label">
                                    <i class="bi bi-diagram-3 me-1"></i>Pilih Jenjang Pendidikan
                                </label>
                                <select id="jenjang" name="jenjang" class="form-select" required>
                                    <option value="">-- Pilih Jenjang --</option>
                                    <option value="sanggar">Sanggar Bermain</option>
                                    <option value="kelompok">Kelompok Bermain</option>
                                    <option value="tka">TK A</option>
                                    <option value="tkb">TK B</option>
                                    <option value="sd">SD</option>
                                    <option value="smp">SMP</option>
                                    <option value="sma">SMA</option>
                                </select>
                            </div>

                            <div class="col-md-6 animate-on-scroll">
                                <label class="form-label">
                                    <i class="bi bi-building me-1"></i>Pilih Unit Sekolah
                                </label>
                                <select id="unit" name="unit" class="form-select" required>
                                    <option value="">-- Pilih Unit Sekolah --</option>
                                </select>
                            </div>

                            <!-- Asal Sekolah Fields -->
                            <div class="col-md-6 d-none animate-on-scroll" id="asal_sekolah_group">
                                <label class="form-label">
                                    <i class="bi bi-arrow-left-right me-1"></i>Asal Sekolah
                                </label>
                                <select id="asal_sekolah" name="asal_sekolah" class="form-select">
                                    <option value="">-- Pilih Asal Sekolah --</option>
                                    <option value="dalam">Dari Sekolah YAPI</option>
                                    <option value="luar">Dari Sekolah Luar</option>
                                    <option value="pindahan">Pindahan</option>
                                    <option value="tidak_ada">Tidak Ada</option>
                                </select>
                            </div>

                            <div class="col-md-6 d-none animate-on-scroll" id="nama_sekolah_group">
                                <label class="form-label">
                                    <i class="bi bi-bank me-1"></i>Nama Sekolah
                                </label>
                                <div id="nama_sekolah_wrapper">
                                    <select id="nama_sekolah" name="nama_sekolah" class="form-select">
                                        <option value="">-- Pilih Nama Sekolah --</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 d-none animate-on-scroll" id="kelas_group">
                                <label class="form-label">
                                    <i class="bi bi-layers me-1"></i>Kelas
                                </label>
                                <input type="text" name="kelas" id="kelas" class="form-control"
                                       placeholder="Contoh: Kelas 7A">
                            </div>

                            <!-- Data Orang Tua Section -->
                            <div class="col-12 mt-5">
                                <h5 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-people me-2"></i>Data Orang Tua/Wali
                                </h5>
                                <hr class="section-divider">
                            </div>

                            <!-- Data Ayah -->
                            <div class="col-md-6 animate-on-scroll">
                                <label class="form-label">
                                    <i class="bi bi-person-badge me-1"></i>Nama Lengkap Ayah
                                </label>
                                <input type="text" name="nama_ayah" class="form-control" required
                                    placeholder="Masukkan nama lengkap ayah">
                            </div>

                            <div class="col-md-6 animate-on-scroll">
                                <label class="form-label">
                                    <i class="bi bi-whatsapp me-1"></i>No. WhatsApp Ayah
                                </label>
                                <input type="tel" name="telp_ayah" class="form-control" required
                                    placeholder="6281234567890" pattern="628[0-9]{8,11}" maxlength="13"
                                    title="Format: 6281234567890">
                                <small class="text-muted">Contoh: 6281234567890</small>
                            </div>

                            <!-- Data Ibu -->
                            <div class="col-md-6 animate-on-scroll">
                                <label class="form-label">
                                    <i class="bi bi-person-heart me-1"></i>Nama Lengkap Ibu
                                </label>
                                <input type="text" name="nama_ibu" class="form-control" required
                                    placeholder="Masukkan nama lengkap ibu">
                            </div>

                            <div class="col-md-6 animate-on-scroll">
                                <label class="form-label">
                                    <i class="bi bi-whatsapp me-1"></i>No. WhatsApp Ibu
                                </label>
                                <input type="tel" name="telp_ibu" class="form-control" required
                                    placeholder="6281234567890" pattern="628[0-9]{8,11}" maxlength="13"
                                    title="Format: 6281234567890">
                                <small class="text-muted">Contoh: 6281234567890</small>
                            </div>

                            <!-- Upload Documents Section -->
                            <div class="col-12 mt-5">
                                <h5 class="text-primary fw-bold mb-3">
                                    <i class="bi bi-file-earmark-arrow-up me-2"></i>Upload Dokumen
                                </h5>
                                <hr class="section-divider">
                            </div>

                            <!-- Foto Murid -->
                            <div class="col-12 animate-on-scroll">
                                <label class="form-label">
                                    <i class="bi bi-camera me-1"></i>Foto Calon Murid (3x4 cm) <span class="text-danger">*</span>
                                </label>
                                <div class="file-input-wrapper">
                                    <input type="file" name="foto_murid" id="foto_murid" accept="image/*" required>
                                    <label for="foto_murid" class="file-upload-btn">
                                        <i class="bi bi-cloud-upload fs-2 d-block mb-2"></i>
                                        <span>Klik untuk upload foto 3x4</span>
                                        <small class="d-block text-muted">Format: JPG, PNG (Max: 2MB)</small>
                                    </label>
                                </div>
                                <div class="mt-3 text-center">
                                    <img id="preview_foto" src="" alt="Preview Foto" class="preview-image"
                                        style="width: 113px; height: 151px; object-fit: cover; display: none;">
                                </div>
                            </div>

                            <!-- Akta & KK -->
                            <div class="col-md-6 animate-on-scroll">
                                <label class="form-label">
                                    <i class="bi bi-file-earmark-text me-1"></i>Akta Kelahiran <span class="text-danger">*</span>
                                </label>
                                <div class="file-input-wrapper">
                                    <input type="file" name="akta_kelahiran" id="akta_kelahiran"
                                           accept=".jpg,.jpeg,.png,.pdf" required>
                                    <label for="akta_kelahiran" class="file-upload-btn">
                                        <i class="bi bi-file-earmark-pdf fs-3 d-block mb-1"></i>
                                        <span>Upload Akta Kelahiran</span>
                                        <small class="d-block text-muted">PDF, JPG, PNG (Max: 2MB)</small>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 animate-on-scroll">
                                <label class="form-label">
                                    <i class="bi bi-file-earmark-person me-1"></i>Kartu Keluarga <span class="text-danger">*</span>
                                </label>
                                <div class="file-input-wrapper">
                                    <input type="file" name="kartu_keluarga" id="kartu_keluarga"
                                           accept=".jpg,.jpeg,.png,.pdf" required>
                                    <label for="kartu_keluarga" class="file-upload-btn">
                                        <i class="bi bi-file-earmark-pdf fs-3 d-block mb-1"></i>
                                        <span>Upload Kartu Keluarga</span>
                                        <small class="d-block text-muted">PDF, JPG, PNG (Max: 2MB)</small>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12 mt-5 text-center animate-on-scroll">
                                <button type="submit" class="btn btn-submit btn-lg px-5 py-3 text-white">
                                    <i class="bi bi-send me-2"></i>
                                    Daftar Sekarang
                                </button>
                                <p class="text-muted small mt-3">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Data Anda akan dienkripsi dan disimpan dengan aman
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="container mt-5 pb-4">
        <div class="text-center text-white">
            <p class="mb-1">© 2026 YAPI Al Azhar. Hak cipta dilindungi.</p>
            <p class="small opacity-75">
                <i class="bi bi-telephone me-1"></i>Hubungi kami: +62 21 1234567 |
                <i class="bi bi-envelope me-1"></i>info@yapi-alazhar.sch.id
            </p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Service Worker untuk PWA - Disabled to prevent form submission conflicts -->
    <script>
        // Global error handling for production
        window.addEventListener('error', function(event) {
            console.error('Global JavaScript error:', event.error);
            // Prevent error from breaking the form functionality
            return true;
        });

        window.addEventListener('unhandledrejection', function(event) {
            console.error('Unhandled promise rejection:', event.reason);
            // Prevent error from breaking the form functionality
            event.preventDefault();
        });

        // Service Worker temporarily disabled to prevent form submission interference
        // TODO: Re-implement with proper form handling
        /*
        if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js').then(() => {
                    console.log("Service Worker registered!");
                });
            }
        */

        // Animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Form logic dengan mobile compatibility
        const jenjangSelect = document.getElementById("jenjang");
        const unitSelect = document.getElementById("unit");
        const asalSekolahGroup = document.getElementById("asal_sekolah_group");
        const asalSekolahSelect = document.getElementById("asal_sekolah");
        const namaSekolahGroup = document.getElementById("nama_sekolah_group");
        const namaSekolahWrapper = document.getElementById("nama_sekolah_wrapper");
        const kelasGroup = document.getElementById("kelas_group");

        const options = {
            sanggar: ["Playgroup Sakinah - Rawamangun", "RA Sakinah - Kebayoran"],
            kelompok: ["Playgroup Sakinah - Rawamangun", "RA Sakinah - Kebayoran"],
            tka: ["TK Islam Al Azhar 13 - Rawamangun"],
            tkb: ["TK Islam Al Azhar 13 - Rawamangun"],
            sd: ["SD Islam Al Azhar 13 - Rawamangun"],
            smp: ["SMP Islam Al Azhar 12 - Rawamangun", "SMP Islam Al Azhar 55 - Jatimakmur"],
            sma: ["SMA Islam Al Azhar 33 - Jatimakmur"]
        };

        // Mobile-friendly event handling untuk jenjang select
        function handleJenjangChange() {
            const selected = jenjangSelect.value;

            // Reset form
            unitSelect.innerHTML = '<option value="">-- Pilih Unit Sekolah --</option>';
            asalSekolahGroup.classList.add("d-none");
            namaSekolahGroup.classList.add("d-none");
            kelasGroup.classList.add("d-none");

            // Clear validation states
            unitSelect.classList.remove('is-invalid');
            asalSekolahSelect.classList.remove('is-invalid');

            // Populate unit options
            if (options[selected]) {
                options[selected].forEach(unit => {
                    const opt = document.createElement("option");
                    opt.value = unit;
                    opt.textContent = unit;
                    unitSelect.appendChild(opt);
                });
            }

            // Show asal sekolah for certain jenjang
            if (["tka", "tkb", "sd", "smp", "sma"].includes(selected)) {
                asalSekolahGroup.classList.remove("d-none");
            }
        }

        // Cross-browser event handling
        if (jenjangSelect) {
            // Primary event
            jenjangSelect.addEventListener("change", handleJenjangChange);

            // Fallback events for mobile devices
            jenjangSelect.addEventListener("touchend", function(e) {
                setTimeout(handleJenjangChange, 100);
            });

            // Additional fallback for older mobile browsers
            jenjangSelect.addEventListener("blur", function(e) {
                setTimeout(handleJenjangChange, 50);
            });
        }

        // Mobile-friendly event handling untuk asal sekolah select
        function handleAsalSekolahChange() {
            const selectedAsal = asalSekolahSelect.value;
            const selectedJenjang = jenjangSelect.value;

            // Reset kelas group
            kelasGroup.classList.add("d-none");

            // Clear validation states
            const namaSekolahInput = namaSekolahWrapper.querySelector('select, input');
            if (namaSekolahInput) {
                namaSekolahInput.classList.remove('is-invalid');
            }

            // Handle "tidak_ada" case - hide nama sekolah group and set value to null
            if (selectedAsal === "tidak_ada") {
                namaSekolahGroup.classList.add("d-none");
                namaSekolahWrapper.innerHTML = "";
                // Create hidden input with null value
                const hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "nama_sekolah";
                hiddenInput.value = "";
                namaSekolahWrapper.appendChild(hiddenInput);
                return;
            }

            // Show nama sekolah group for other options
            namaSekolahGroup.classList.remove("d-none");
            namaSekolahWrapper.innerHTML = "";

            if (selectedAsal === "dalam") {
                const select = document.createElement("select");
                select.name = "nama_sekolah";
                select.className = "form-select";
                select.innerHTML = '<option value="">-- Pilih Sekolah YAPI --</option>';

                let sekolahDalam = [];
                if (selectedJenjang === "sd") {
                    sekolahDalam = ["TK Islam Al Azhar 13 - Rawamangun"];
                } else if (selectedJenjang === "smp") {
                    sekolahDalam = ["SD Islam Al Azhar 13 - Rawamangun"];
                } else if (selectedJenjang === "sma") {
                    sekolahDalam = ["SMP Islam Al Azhar 12 - Rawamangun", "SMP Islam Al Azhar 55 - Jatimakmur"];
                } else {
                    sekolahDalam = ["Playgroup Sakinah", "RA Sakinah"];
                }

                sekolahDalam.forEach(school => {
                    const opt = document.createElement("option");
                    opt.value = school;
                    opt.textContent = school;
                    select.appendChild(opt);
                });

                namaSekolahWrapper.appendChild(select);
            } else if (selectedAsal === "luar" || selectedAsal === "pindahan") {
                const input = document.createElement("input");
                input.type = "text";
                input.name = "nama_sekolah";
                input.className = "form-control";
                input.placeholder = "Masukkan nama sekolah asal";

                // Mobile optimization
                input.setAttribute('autocomplete', 'off');
                input.setAttribute('autocorrect', 'off');
                input.setAttribute('autocapitalize', 'words');

                namaSekolahWrapper.appendChild(input);

                if (selectedAsal === "pindahan") {
                    kelasGroup.classList.remove("d-none");
                }
            }
        }

        // Cross-browser event handling
        if (asalSekolahSelect) {
            // Primary event
            asalSekolahSelect.addEventListener("change", handleAsalSekolahChange);

            // Fallback events for mobile devices
            asalSekolahSelect.addEventListener("touchend", function(e) {
                setTimeout(handleAsalSekolahChange, 100);
            });

            // Additional fallback for older mobile browsers
            asalSekolahSelect.addEventListener("blur", function(e) {
                setTimeout(handleAsalSekolahChange, 50);
            });
        }

        // Mobile-friendly file upload previews
        const fotoInput = document.getElementById("foto_murid");
        const previewFoto = document.getElementById("preview_foto");

        if (fotoInput && previewFoto) {
            function handlePhotoChange() {
                const file = fotoInput.files[0];
                if (file) {
                    // Validate file size (2MB limit)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                        fotoInput.value = '';
                        previewFoto.style.display = "none";
                        return;
                    }

                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('Format file tidak didukung. Gunakan JPG atau PNG.');
                        fotoInput.value = '';
                        previewFoto.style.display = "none";
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewFoto.src = e.target.result;
                        previewFoto.style.display = "block";

                        // Remove any error styling
                        fotoInput.classList.remove('is-invalid');
                    };
                    reader.onerror = function() {
                        alert('Error membaca file. Silakan coba lagi.');
                        fotoInput.value = '';
                        previewFoto.style.display = "none";
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewFoto.src = "";
                    previewFoto.style.display = "none";
                }
            }

            // Cross-browser event handling
            fotoInput.addEventListener("change", handlePhotoChange);

            // Additional events for mobile devices
            fotoInput.addEventListener("input", handlePhotoChange);
        }

        // Mobile-friendly file input labels update
        document.querySelectorAll('input[type="file"]').forEach(input => {
            function updateFileLabel() {
                const label = input.nextElementSibling;
                const fileName = input.files[0]?.name;
                if (fileName && label) {
                    const icon = label.querySelector('i');
                    const span = label.querySelector('span');
                    if (icon && span) {
                        icon.className = 'bi bi-check-circle-fill text-success fs-3 d-block mb-1';
                        span.textContent = fileName;
                    }

                    // Remove error styling
                    input.classList.remove('is-invalid');
                }
            }

            // Cross-browser event handling
            input.addEventListener('change', updateFileLabel);
            input.addEventListener('input', updateFileLabel);
        });

        // Enhanced mobile-friendly form validation with comprehensive error handling
        function validateForm() {
            let isValid = true;
            const errors = [];

            // Clear all previous validation states
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });

            // Validate required fields
            const requiredFields = document.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                const value = field.type === 'file' ? field.files.length > 0 : field.value.trim();

                if (!value) {
                    field.classList.add('is-invalid');
                    const fieldName = field.getAttribute('name') || field.id || 'Field';
                    errors.push(`${fieldName} wajib diisi`);
                    isValid = false;

                    // Create error message if doesn't exist
                    let errorMsg = field.parentNode.querySelector('.invalid-feedback');
                    if (!errorMsg) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'invalid-feedback';
                        field.parentNode.appendChild(errorMsg);
                    }
                    errorMsg.textContent = 'Field ini wajib diisi.';
                } else {
                    // Remove error message if field is valid
                    const errorMsg = field.parentNode.querySelector('.invalid-feedback');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            });

            // Validate phone numbers
            const phoneFields = document.querySelectorAll('input[name="telp_ayah"], input[name="telp_ibu"]');
            phoneFields.forEach(field => {
                const value = field.value.trim();
                if (value && !/^628[0-9]{8,11}$/.test(value)) {
                    field.classList.add('is-invalid');
                    errors.push(`${field.getAttribute('name')} harus format 628xxxxxxxxx`);
                    isValid = false;

                    // Create error message
                    let errorMsg = field.parentNode.querySelector('.invalid-feedback');
                    if (!errorMsg) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'invalid-feedback';
                        field.parentNode.appendChild(errorMsg);
                    }
                    errorMsg.textContent = 'Format: 6281234567890 (dimulai 628 dan 10-13 digit total)';
                }
            });

            // Validate NISN if filled (enhanced validation)
            const nisnField = document.getElementById('nisn');
            if (nisnField && nisnField.value.trim()) {
                const nisnValue = nisnField.value.trim();
                if (nisnValue.length !== 10 || !/^[0-9]{10}$/.test(nisnValue)) {
                    nisnField.classList.add('is-invalid');
                    errors.push('NISN harus 10 digit angka');
                    isValid = false;

                    // Show NISN specific error
                    showNISNError('NISN harus tepat 10 digit angka atau kosongkan jika tidak ada.');
                } else {
                    clearNISNError();
                }
            }

            // Validate file sizes and types
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                if (input.files.length > 0) {
                    const file = input.files[0];

                    // Check file size (2MB limit)
                    if (file.size > 2 * 1024 * 1024) {
                        input.classList.add('is-invalid');
                        errors.push(`File ${input.getAttribute('name')} terlalu besar (maksimal 2MB)`);
                        isValid = false;
                    }

                    // Check file type
                    const allowedTypes = {
                        'foto_murid': ['image/jpeg', 'image/jpg', 'image/png'],
                        'akta_kelahiran': ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'],
                        'kartu_keluarga': ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png']
                    };

                    const fieldName = input.getAttribute('name');
                    if (allowedTypes[fieldName] && !allowedTypes[fieldName].includes(file.type)) {
                        input.classList.add('is-invalid');
                        errors.push(`File ${fieldName} harus berformat yang diizinkan`);
                        isValid = false;
                    }
                }
            });

            // Show errors if any with mobile-friendly display
            if (!isValid && errors.length > 0) {
                const errorMessage = 'Mohon perbaiki kesalahan berikut:\n\n' + errors.join('\n');
                alert(errorMessage);

                // Scroll to first error field
                const firstError = document.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    // Focus on the field after scroll (with delay for mobile)
                    setTimeout(() => {
                        if (firstError.focus) {
                            firstError.focus();
                        }
                    }, 500);
                }
            }

            return isValid;
        }

        // Enhanced form submission with mobile compatibility (SINGLE EVENT LISTENER)
        const registrationForm = document.getElementById('registrationForm');
        if (registrationForm) {
            let isSubmitting = false; // Prevent double submission

            function handleFormSubmit(e) {
                // Prevent double submission
                if (isSubmitting) {
                    console.log('Form already submitting, ignoring duplicate attempt');
                    e.preventDefault();
                    return false;
                }

                // Validate form
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }

                // Set submitting flag
                isSubmitting = true;

                // Show loading state
                const submitBtn = registrationForm.querySelector('button[type="submit"]');
                const originalContent = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';
                submitBtn.disabled = true;

                // Add loading class to form
                registrationForm.classList.add('submitting');

                // Allow normal form submission to proceed
                console.log('Submitting form normally...');
                return true; // Let the form submit naturally
            }

            // SINGLE event listener for form submission
            registrationForm.addEventListener('submit', handleFormSubmit, { once: false });
        }

        function setupPhoneValidation() {
            try {
                const phoneInputs = document.querySelectorAll('input[name="telp_ayah"], input[name="telp_ibu"]');

                phoneInputs.forEach(input => {
                    try {
                        if (!input) return;

                        // Set input attributes for better mobile experience
                        input.setAttribute('inputmode', 'numeric');
                        input.setAttribute('pattern', '[0-9]*');

                        // Only allow numbers with better iOS compatibility
                        input.addEventListener('input', function(e) {
                            try {
                                let value = e.target.value.replace(/[^0-9]/g, '');

                                // Auto format to 62 format
                                if (value.length > 0) {
                                    // If starts with 0, replace with 62
                                    if (value.startsWith('0')) {
                                        value = '62' + value.substring(1);
                                    }
                                    // If starts with 8, add 62 prefix
                                    else if (value.startsWith('8')) {
                                        value = '62' + value;
                                    }
                                    // If doesn't start with 62, add 62 prefix
                                    else if (!value.startsWith('62')) {
                                        value = '62' + value;
                                    }
                                }

                                // Limit length (62 + 10-11 digits = 12-13 total)
                                if (value.length > 13) {
                                    value = value.substring(0, 13);
                                }

                                e.target.value = value;
                            } catch (inputError) {
                                console.warn('Phone input error:', inputError);
                            }
                        });

                        // Use 'beforeinput' for better iOS compatibility instead of 'keypress'
                        input.addEventListener('beforeinput', function(e) {
                            try {
                                // Allow numeric input, backspace, delete for iOS
                                if (e.inputType === 'insertText' && e.data && !/[0-9]/.test(e.data)) {
                                    e.preventDefault();
                                }
                            } catch (beforeInputError) {
                                console.warn('Before input error:', beforeInputError);
                            }
                        });

                        // Fallback for older browsers
                        input.addEventListener('keypress', function(e) {
                            try {
                                // Allow numbers, backspace, delete, tab, arrow keys
                                const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'];
                                if (allowedKeys.includes(e.key) || /[0-9]/.test(e.key)) {
                                    return true;
                                }
                                e.preventDefault();
                                return false;
                            } catch (keypressError) {
                                console.warn('Keypress error:', keypressError);
                            }
                        });

                        // Validation on blur with timeout for iOS
                        input.addEventListener('blur', function(e) {
                            try {
                                // Add small delay for iOS to process input
                                setTimeout(() => {
                                    try {
                                        const value = e.target.value;
                                        // Pattern for Indonesian phone number: 62 + 8xxxxxxxx (10-11 digits after 62)
                                        const isValid = /^628[0-9]{8,11}$/.test(value);

                                        if (value && !isValid) {
                                            e.target.classList.add('is-invalid');
                                            // Show error message
                                            let errorMsg = e.target.parentNode.nextElementSibling;
                                            if (!errorMsg || !errorMsg.classList.contains('invalid-feedback')) {
                                                errorMsg = document.createElement('div');
                                                errorMsg.className = 'invalid-feedback';
                                                e.target.parentNode.parentNode.appendChild(errorMsg);
                                            }
                                            errorMsg.textContent = 'Format: 6281234567890 (dimulai 628 dan 10-13 digit total)';
                                        } else {
                                            e.target.classList.remove('is-invalid');
                                            const errorMsg = e.target.parentNode.parentNode.querySelector('.invalid-feedback');
                                            if (errorMsg) {
                                                errorMsg.remove();
                                            }
                                        }
                                    } catch (validationError) {
                                        console.warn('Phone validation error:', validationError);
                                    }
                                }, 100);
                            } catch (blurError) {
                                console.warn('Phone blur error:', blurError);
                            }
                        });
                    } catch (phoneInputError) {
                        console.warn('Error setting up phone input:', phoneInputError);
                    }
                });
            } catch (error) {
                console.error('Error in setupPhoneValidation:', error);
            }
        }

        function setupNISNValidation() {
            const nisnInput = document.getElementById('nisn');

            if (nisnInput) {
                // Set input attributes for better mobile experience
                nisnInput.setAttribute('inputmode', 'numeric');
                nisnInput.setAttribute('pattern', '[0-9]*');
                nisnInput.setAttribute('maxlength', '10');

                // Only allow numbers with iOS compatibility
                nisnInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^0-9]/g, '');

                    // Limit to 10 digits
                    if (value.length > 10) {
                        value = value.substring(0, 10);
                    }

                    e.target.value = value;

                    // Real-time validation only if user has started typing
                    if (value.length > 0) {
                        // Clear any previous errors during typing
                        clearNISNError();
                    } else {
                        clearNISNError();
                    }
                });

                // Use 'beforeinput' for better iOS compatibility
                nisnInput.addEventListener('beforeinput', function(e) {
                    if (e.inputType === 'insertText' && e.data && !/[0-9]/.test(e.data)) {
                        e.preventDefault();
                    }
                });

                // Prevent non-numeric input (fallback for older browsers)
                nisnInput.addEventListener('keypress', function(e) {
                    const allowedKeys = ['Backspace', 'Delete', 'Tab', 'Escape', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'];
                    if (allowedKeys.includes(e.key) || /[0-9]/.test(e.key)) {
                        return true;
                    }
                    e.preventDefault();
                    return false;
                });

                // Validation on blur (when user leaves the field) with timeout for iOS
                nisnInput.addEventListener('blur', function(e) {
                    setTimeout(() => {
                        const value = e.target.value.trim();

                        // Only validate if user has entered something
                        if (value.length > 0 && value.length !== 10) {
                            showNISNError('NISN harus tepat 10 digit angka atau kosongkan jika tidak ada.');
                        } else {
                            clearNISNError();
                        }
                    }, 100);
                });

                // Validation on focus (when user clicks on the field)
                nisnInput.addEventListener('focus', function(e) {
                    // Clear errors when user focuses to try again
                    clearNISNError();
                });
            }
        }

        function showNISNError(message) {
            try {
                const nisnInput = document.getElementById('nisn');
                const errorDiv = document.getElementById('nisn-error');

                if (nisnInput && nisnInput.classList) {
                    nisnInput.classList.add('is-invalid');
                }
                if (errorDiv) {
                    errorDiv.textContent = message;
                    errorDiv.style.display = 'block';
                }
            } catch (error) {
                console.warn('Error showing NISN error:', error);
            }
        }

        function clearNISNError() {
            try {
                const nisnInput = document.getElementById('nisn');
                const errorDiv = document.getElementById('nisn-error');

                if (nisnInput && nisnInput.classList) {
                    nisnInput.classList.remove('is-invalid');
                }
                if (errorDiv) {
                    errorDiv.textContent = '';
                    errorDiv.style.display = 'none';
                }
            } catch (error) {
                console.warn('Error clearing NISN error:', error);
            }
        }

        // REMOVED DUPLICATE EVENT LISTENER - Form validation now handled in single handleFormSubmit function above

        // Initialize all validations when page loads with enhanced mobile support
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing mobile-friendly form...');

            try {
                // Setup validations
                setupNISNValidation();
                setupPhoneValidation();

                // Enhanced mobile form initialization
                initializeMobileFormEnhancements();

                // Initialize touch improvements for mobile devices
                if (window.innerWidth <= 768 || /Mobi|Android/i.test(navigator.userAgent)) {
                    initializeMobileTouchEnhancements();
                }

                // Viewport height fix for mobile browsers
                const setViewportHeight = () => {
                    const vh = window.innerHeight * 0.01;
                    document.documentElement.style.setProperty('--vh', `${vh}px`);
                };

                setViewportHeight();
                window.addEventListener('resize', setViewportHeight);
                window.addEventListener('orientationchange', setViewportHeight);

            } catch (error) {
                console.error('Error initializing form:', error);
                // Graceful degradation - form will still work without enhancements
            }
        });

        // Enhanced mobile form initialization with comprehensive error handling
        function initializeMobileFormEnhancements() {
            try {
                // Add mobile-friendly classes
                if (document.body) {
                    document.body.classList.add('mobile-optimized');
                }

                // Enhance all form inputs for mobile
                const allInputs = document.querySelectorAll('input, select, textarea');
                allInputs.forEach(input => {
                    try {
                        // Prevent zoom on iOS
                        if (input && input.type !== 'file') {
                            input.style.fontSize = '16px';
                            input.setAttribute('autocapitalize', 'off');
                            input.setAttribute('autocorrect', 'off');
                            input.setAttribute('spellcheck', 'false');
                        }

                        // Add touch-friendly classes
                        if (input && input.classList) {
                            input.classList.add('mobile-input');
                        }

                        // Enhanced focus handling for mobile
                        if (input && input.addEventListener) {
                            input.addEventListener('focus', function() {
                                try {
                                    this.classList.add('mobile-focused');

                                    // Scroll to input on mobile
                                    if (window.innerWidth <= 768 && this.scrollIntoView) {
                                        setTimeout(() => {
                                            try {
                                                this.scrollIntoView({
                                                    behavior: 'smooth',
                                                    block: 'center',
                                                    inline: 'nearest'
                                                });
                                            } catch (scrollError) {
                                                console.warn('Scroll error:', scrollError);
                                            }
                                        }, 300);
                                    }
                                } catch (focusError) {
                                    console.warn('Focus handler error:', focusError);
                                }
                            });

                            input.addEventListener('blur', function() {
                                try {
                                    this.classList.remove('mobile-focused');
                                } catch (blurError) {
                                    console.warn('Blur handler error:', blurError);
                                }
                            });
                        }
                    } catch (inputError) {
                        console.warn('Error enhancing input:', inputError);
                    }
                });

                // Enhance file inputs for mobile
                const fileInputs = document.querySelectorAll('input[type="file"]');
                fileInputs.forEach(input => {
                    try {
                        if (input && input.setAttribute) {
                            input.setAttribute('accept', input.getAttribute('accept') || 'image/*,.pdf');
                        }

                        // Enhanced change handling
                        if (input && input.addEventListener) {
                            input.addEventListener('change', function() {
                                try {
                                    const file = this.files && this.files[0];
                                    if (file) {
                                        console.log(`File selected: ${file.name}, size: ${file.size}, type: ${file.type}`);
                                    }
                                } catch (fileError) {
                                    console.warn('File change handler error:', fileError);
                                }
                            });
                        }
                    } catch (fileInputError) {
                        console.warn('Error enhancing file input:', fileInputError);
                    }
                });
            } catch (error) {
                console.error('Error in initializeMobileFormEnhancements:', error);
                // Graceful degradation - form will still work without enhancements
            }
        }

        // Mobile touch enhancements with comprehensive error handling
        function initializeMobileTouchEnhancements() {
            try {
                // Enhance button touch targets
                const buttons = document.querySelectorAll('button, .btn');
                buttons.forEach(btn => {
                    try {
                        if (btn && btn.style) {
                            btn.style.minHeight = '48px';
                            btn.style.minWidth = '48px';
                            btn.style.touchAction = 'manipulation';
                        }
                    } catch (btnError) {
                        console.warn('Error enhancing button:', btnError);
                    }
                });

                // Enhance select dropdowns for mobile
                const selects = document.querySelectorAll('select');
                selects.forEach(select => {
                    try {
                        if (select && select.addEventListener) {
                            select.addEventListener('touchstart', function() {
                                try {
                                    if (this.focus) {
                                        this.focus();
                                    }
                                } catch (focusError) {
                                    console.warn('Select focus error:', focusError);
                                }
                            });
                        }
                    } catch (selectError) {
                        console.warn('Error enhancing select:', selectError);
                    }
                });

                // Add visual feedback for touch
                if (document.addEventListener) {
                    document.addEventListener('touchstart', function(e) {
                        try {
                            if (e.target && e.target.matches && e.target.matches('button, .btn, input, select, textarea, label')) {
                                e.target.classList.add('touch-active');
                            }
                        } catch (touchStartError) {
                            console.warn('Touch start error:', touchStartError);
                        }
                    });

                    document.addEventListener('touchend', function(e) {
                        try {
                            if (e.target && e.target.matches && e.target.matches('button, .btn, input, select, textarea, label')) {
                                setTimeout(() => {
                                    try {
                                        e.target.classList.remove('touch-active');
                                    } catch (removeError) {
                                        console.warn('Touch class remove error:', removeError);
                                    }
                                }, 150);
                            }
                        } catch (touchEndError) {
                            console.warn('Touch end error:', touchEndError);
                        }
                    });
                }
            } catch (error) {
                console.error('Error in initializeMobileTouchEnhancements:', error);
                // Graceful degradation - touch enhancements will be skipped
            }
        }

        // Enhanced error handling for mobile
        function showMobileError(message, element = null) {
            // Create mobile-friendly error alert
            const errorDiv = document.createElement('div');
            errorDiv.className = 'mobile-error-alert';
            errorDiv.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            // Insert at top of form
            const form = document.getElementById('registrationForm');
            if (form) {
                form.insertBefore(errorDiv, form.firstChild);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (errorDiv.parentNode) {
                        errorDiv.remove();
                    }
                }, 5000);

                // Scroll to error
                errorDiv.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            // Focus on problematic element
            if (element && element.focus) {
                setTimeout(() => {
                    element.focus();
                }, 500);
            }
        }
    }
    </script>
</body>

</html>
