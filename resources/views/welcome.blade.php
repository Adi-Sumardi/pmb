<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        @media (max-width: 768px) {
            .hero-section {
                margin: 1rem;
                border-radius: 15px;
            }

            .form-card {
                margin: 1rem;
                border-radius: 15px;
            }

            .form-header {
                padding: 1.5rem;
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

        @media (max-width: 576px) {
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
                                <input type="text" name="nisn" class="form-control"
                                       placeholder="Nomor Induk Siswa Nasional">
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
                                    <i class="bi bi-camera me-1"></i>Foto Calon Murid (3x4 cm)
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
                                    <i class="bi bi-file-earmark-text me-1"></i>Akta Kelahiran
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
                                    <i class="bi bi-file-earmark-person me-1"></i>Kartu Keluarga
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

    <!-- Service Worker untuk PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').then(() => {
                console.log("Service Worker registered!");
            });
        }

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

        // Form logic
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

        jenjangSelect.addEventListener("change", function() {
            const selected = this.value;

            // Reset form
            unitSelect.innerHTML = '<option value="">-- Pilih Unit Sekolah --</option>';
            asalSekolahGroup.classList.add("d-none");
            namaSekolahGroup.classList.add("d-none");
            kelasGroup.classList.add("d-none");

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
        });

        asalSekolahSelect.addEventListener("change", function() {
            const selectedAsal = this.value;
            const selectedJenjang = jenjangSelect.value;
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
                kelasGroup.classList.add("d-none");
            } else {
                const input = document.createElement("input");
                input.type = "text";
                input.name = "nama_sekolah";
                input.className = "form-control";
                input.placeholder = "Masukkan nama sekolah asal";
                namaSekolahWrapper.appendChild(input);

                if (selectedAsal === "pindahan") {
                    kelasGroup.classList.remove("d-none");
                } else {
                    kelasGroup.classList.add("d-none");
                }
            }
        });

        // File upload previews
        const fotoInput = document.getElementById("foto_murid");
        const previewFoto = document.getElementById("preview_foto");

        fotoInput.addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewFoto.src = e.target.result;
                    previewFoto.style.display = "block";
                };
                reader.readAsDataURL(file);
            } else {
                previewFoto.src = "";
                previewFoto.style.display = "none";
            }
        });

        // File input labels update
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const label = this.nextElementSibling;
                const fileName = this.files[0]?.name;
                if (fileName) {
                    const icon = label.querySelector('i');
                    const span = label.querySelector('span');
                    icon.className = 'bi bi-check-circle-fill text-success fs-3 d-block mb-1';
                    span.textContent = fileName;
                }
            });
        });

        // Form validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';
            submitBtn.disabled = true;
        });

        function setupPhoneValidation() {
        const phoneInputs = document.querySelectorAll('input[name="telp_ayah"], input[name="telp_ibu"]');

        phoneInputs.forEach(input => {
            // Only allow numbers
            input.addEventListener('input', function(e) {
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
            });

            // Prevent non-numeric input
            input.addEventListener('keypress', function(e) {
                if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab') {
                    e.preventDefault();
                }
            });

            // Validation on blur
            input.addEventListener('blur', function(e) {
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
            });
        });
    }
    </script>
</body>

</html>
