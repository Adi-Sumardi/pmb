<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-house-door me-2 text-primary"></i>
                Dashboard Pendaftar
            </h2>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center text-muted">
                    <i class="bi bi-calendar3 me-2"></i>
                    <span id="currentDate"></span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Alert jika belum ada data pendaftar -->
        @if(!$pendaftar)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-info border-0 shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                            <div>
                                <h6 class="mb-1">Data Pendaftar Belum Ada</h6>
                                <p class="mb-0">Silakan lengkapi data pendaftar terlebih dahulu untuk dapat melakukan pembayaran dan melihat status pendaftaran.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg bg-gradient-primary text-white overflow-hidden position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body p-5">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="welcome-content" data-aos="fade-right" data-aos-delay="100">
                                    <h1 class="display-6 fw-bold mb-3">
                                        Selamat Datang, {{ $user->name }}! 👋
                                    </h1>
                                    <p class="lead mb-4 opacity-90">
                                        Kelola profil dan pantau status pendaftaran Anda dengan mudah melalui dashboard ini.
                                    </p>

                                    <!-- Dynamic Alert berdasarkan status -->
                                    <div id="welcomeAlert">
                                        @if(!$pendaftar)
                                            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-info-circle-fill me-2"></i>
                                                <div>
                                                    <strong>Lengkapi Data Pendaftar!</strong> Silakan isi data pendaftar terlebih dahulu sebelum melakukan pembayaran.
                                                </div>
                                            </div>
                                        @elseif(!$isPaid)
                                            <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                <div>
                                                    <strong>Pembayaran Diperlukan!</strong> Silakan lakukan pembayaran formulir terlebih dahulu untuk mengakses kelengkapan data.
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-check-circle-fill me-2"></i>
                                                <div>
                                                    <strong>Pembayaran Lunas!</strong> Anda dapat mengakses semua fitur kelengkapan data.
                                                    @if($paymentDate)
                                                        <br><small>Dibayar pada: {{ $paymentDate }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="d-flex flex-wrap gap-3">
                                        <a href="{{ route('profile.edit') }}" class="btn btn-light btn-lg px-4 shadow-sm">
                                            <i class="bi bi-person-gear me-2"></i>Edit Profile
                                        </a>
                                        @if($pendaftar)
                                            <a href="{{ route('payment.index') }}" class="btn btn-outline-light btn-lg px-4">
                                                <i class="bi bi-credit-card me-2"></i>Pembayaran
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 text-center">
                                <div class="welcome-illustration" data-aos="fade-left" data-aos-delay="200">
                                    <i class="bi bi-mortarboard display-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative elements -->
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="bi bi-stars display-4"></i>
                    </div>
                    <div class="position-absolute bottom-0 start-0 p-3 opacity-10">
                        <i class="bi bi-book display-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Payment Status -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">
                                    Status Pembayaran
                                </div>
                                <div class="h4 fw-bold mb-2" id="paymentStatusText">
                                    @if($isPaid)
                                        <span class="text-success">LUNAS</span>
                                    @else
                                        <span class="text-warning">PENDING</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center small" id="paymentStatusDetail">
                                    @if($isPaid)
                                        <i class="bi bi-check-circle me-1 text-success"></i>
                                        <span class="fw-semibold text-success">Sudah Dibayar</span>
                                    @else
                                        <i class="bi bi-clock me-1 text-warning"></i>
                                        <span class="fw-semibold text-warning">Belum Dibayar</span>
                                    @endif
                                </div>
                            </div>
                            <div class="stat-icon bg-opacity-10 rounded-3 p-3" id="paymentStatusIcon">
                                @if($isPaid)
                                    <i class="bi bi-check-circle-fill text-success fs-2"></i>
                                @else
                                    <i class="bi bi-clock text-warning fs-2"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overall Data Completion -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">
                                    Total Kelengkapan
                                </div>
                                <div class="h4 fw-bold text-info mb-2 counter" data-target="{{ $dataCompletion }}" id="dataCompletionText">
                                    {{ $dataCompletion }}%
                                </div>
                                <div class="d-flex align-items-center text-info small">
                                    <i class="bi bi-graph-up me-1"></i>
                                    <span class="fw-semibold" id="dataCompletionDetail">{{ $dataCompletion }}% Lengkap</span>
                                </div>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-clipboard-data text-info fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Status -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">
                                    Status Pendaftaran
                                </div>
                                <div class="h4 fw-bold mb-2" id="registrationStatusText">
                                    @if($registrationStatus == 'verified')
                                        <span class="text-success">VERIFIED</span>
                                    @elseif($registrationStatus == 'pending')
                                        <span class="text-warning">REVIEW</span>
                                    @else
                                        <span class="text-secondary">DRAFT</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center small" id="registrationStatusDetail">
                                    @if($registrationStatus == 'verified')
                                        <i class="bi bi-shield-check me-1 text-success"></i>
                                        <span class="fw-semibold text-success">Terverifikasi</span>
                                    @elseif($registrationStatus == 'pending')
                                        <i class="bi bi-hourglass-split me-1 text-warning"></i>
                                        <span class="fw-semibold text-warning">Dalam Review</span>
                                    @else
                                        <i class="bi bi-pencil me-1 text-secondary"></i>
                                        <span class="fw-semibold text-secondary">Belum Submit</span>
                                    @endif
                                </div>
                            </div>
                            <div class="stat-icon bg-opacity-10 rounded-3 p-3" id="registrationStatusIcon">
                                @if($registrationStatus == 'verified')
                                    <i class="bi bi-shield-check text-success fs-2"></i>
                                @elseif($registrationStatus == 'pending')
                                    <i class="bi bi-hourglass-split text-warning fs-2"></i>
                                @else
                                    <i class="bi bi-pencil text-secondary fs-2"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card bg-gradient-success text-white" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-body p-4 d-flex align-items-center justify-content-center">
                        @if($isPaid && $pendaftar)
                            <a href="{{ route('user.data') }}" class="btn btn-light btn-lg w-100 d-flex align-items-center justify-content-center text-decoration-none">
                                <i class="bi bi-plus-circle me-2 fs-4"></i>
                                <div>
                                    <div class="fw-bold">Lengkapi Data</div>
                                    <small class="opacity-75">Akses Form</small>
                                </div>
                            </a>
                        @elseif(!$pendaftar)
                            <a href="{{ route('user.data') }}" class="btn btn-light btn-lg w-100 d-flex align-items-center justify-content-center text-decoration-none">
                                <i class="bi bi-person-plus me-2 fs-4"></i>
                                <div>
                                    <div class="fw-bold">Isi Data</div>
                                    <small class="opacity-75">Pendaftar</small>
                                </div>
                            </a>
                        @else
                            <button class="btn btn-light btn-lg w-100 d-flex align-items-center justify-content-center opacity-50" disabled>
                                <i class="bi bi-lock me-2 fs-4"></i>
                                <div>
                                    <div class="fw-bold">Bayar Dulu</div>
                                    <small class="opacity-75">Akses Terkunci</small>
                                </div>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Data Completion Section -->
        @if($isPaid && $pendaftar)
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="500">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fw-bold">
                                    <i class="bi bi-list-check me-2 text-primary"></i>Detail Kelengkapan Data
                                </h5>
                                <span class="badge bg-primary fs-6">{{ $completedSections }}/{{ $totalSections }} Selesai</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Student Details -->
                                <div class="col-lg-4 col-md-6">
                                    <div class="completion-item {{ $studentDetailComplete ? 'completed' : 'incomplete' }}">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="completion-icon me-3">
                                                @if($studentDetailComplete)
                                                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                                @else
                                                    <i class="bi bi-circle text-muted fs-4"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">Data Siswa</h6>
                                                <small class="text-muted">Informasi pribadi siswa</small>
                                            </div>
                                        </div>
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar {{ $studentDetailComplete ? 'bg-success' : 'bg-warning' }}"
                                                 style="width: {{ $studentDetailComplete ? '100' : '50' }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                {{ $studentDetailComplete ? 'Lengkap' : 'Perlu dilengkapi' }}
                                            </small>
                                            @if($studentDetailComplete)
                                                <small class="text-success fw-semibold">✓ Selesai</small>
                                            @else
                                                <small class="text-warning fw-semibold">⚠ Belum</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Parent Details -->
                                <div class="col-lg-4 col-md-6">
                                    <div class="completion-item {{ $parentDetailComplete ? 'completed' : 'incomplete' }}">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="completion-icon me-3">
                                                @if($parentDetailComplete)
                                                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                                @else
                                                    <i class="bi bi-circle text-muted fs-4"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">Data Orang Tua</h6>
                                                <small class="text-muted">Informasi ayah, ibu, wali</small>
                                            </div>
                                        </div>
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar {{ $parentDetailComplete ? 'bg-success' : 'bg-warning' }}"
                                                 style="width: {{ $parentDetailComplete ? '100' : '30' }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                {{ $parentDetailComplete ? 'Lengkap' : 'Perlu dilengkapi' }}
                                            </small>
                                            @if($parentDetailComplete)
                                                <small class="text-success fw-semibold">✓ Selesai</small>
                                            @else
                                                <small class="text-warning fw-semibold">⚠ Belum</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Academic History -->
                                <div class="col-lg-4 col-md-6">
                                    <div class="completion-item {{ $academicHistoryComplete ? 'completed' : 'incomplete' }}">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="completion-icon me-3">
                                                @if($academicHistoryComplete)
                                                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                                @else
                                                    <i class="bi bi-circle text-muted fs-4"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">Riwayat Akademik</h6>
                                                <small class="text-muted">Sekolah sebelumnya, nilai</small>
                                            </div>
                                        </div>
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar {{ $academicHistoryComplete ? 'bg-success' : 'bg-warning' }}"
                                                 style="width: {{ $academicHistoryComplete ? '100' : '20' }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                {{ $academicHistoryComplete ? 'Lengkap' : 'Perlu dilengkapi' }}
                                            </small>
                                            @if($academicHistoryComplete)
                                                <small class="text-success fw-semibold">✓ Selesai</small>
                                            @else
                                                <small class="text-warning fw-semibold">⚠ Belum</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Health Records -->
                                <div class="col-lg-4 col-md-6">
                                    <div class="completion-item {{ $healthRecordComplete ? 'completed' : 'incomplete' }}">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="completion-icon me-3">
                                                @if($healthRecordComplete)
                                                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                                @else
                                                    <i class="bi bi-circle text-muted fs-4"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">Data Kesehatan</h6>
                                                <small class="text-muted">Riwayat kesehatan, imunisasi</small>
                                            </div>
                                        </div>
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar {{ $healthRecordComplete ? 'bg-success' : 'bg-info' }}"
                                                 style="width: {{ $healthRecordComplete ? '100' : '0' }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                {{ $healthRecordComplete ? 'Lengkap' : 'Opsional' }}
                                            </small>
                                            @if($healthRecordComplete)
                                                <small class="text-success fw-semibold">✓ Selesai</small>
                                            @else
                                                <small class="text-info fw-semibold">○ Opsional</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Documents -->
                                <div class="col-lg-4 col-md-6">
                                    <div class="completion-item {{ $documentsComplete ? 'completed' : 'incomplete' }}">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="completion-icon me-3">
                                                @if($documentsComplete)
                                                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                                @else
                                                    <i class="bi bi-circle text-muted fs-4"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">Dokumen</h6>
                                                <small class="text-muted">Upload dokumen pendukung</small>
                                            </div>
                                        </div>
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar {{ $documentsComplete ? 'bg-success' : 'bg-danger' }}"
                                                 style="width: {{ $documentsComplete ? '100' : '10' }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                {{ $documentsComplete ? 'Lengkap' : 'Perlu diupload' }}
                                            </small>
                                            @if($documentsComplete)
                                                <small class="text-success fw-semibold">✓ Selesai</small>
                                            @else
                                                <small class="text-danger fw-semibold">✗ Belum</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Grade Reports -->
                                <div class="col-lg-4 col-md-6">
                                    <div class="completion-item {{ $gradeReportsComplete ? 'completed' : 'incomplete' }}">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="completion-icon me-3">
                                                @if($gradeReportsComplete)
                                                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                                @else
                                                    <i class="bi bi-circle text-muted fs-4"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">Raport & Nilai</h6>
                                                <small class="text-muted">Upload raport dan nilai</small>
                                            </div>
                                        </div>
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar {{ $gradeReportsComplete ? 'bg-success' : 'bg-warning' }}"
                                                 style="width: {{ $gradeReportsComplete ? '100' : '15' }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                {{ $gradeReportsComplete ? 'Lengkap' : 'Perlu diupload' }}
                                            </small>
                                            @if($gradeReportsComplete)
                                                <small class="text-success fw-semibold">✓ Selesai</small>
                                            @else
                                                <small class="text-warning fw-semibold">⚠ Belum</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="text-center mt-4">
                                <a href="{{ route('user.data') }}" class="btn btn-primary btn-lg px-5">
                                    <i class="bi bi-pencil-square me-2"></i>
                                    @if($dataCompletion >= 100)
                                        Review Data Lengkap
                                    @elseif($dataCompletion > 0)
                                        Lanjutkan Melengkapi Data
                                    @else
                                        Mulai Melengkapi Data
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Progress Overview & Quick Actions -->
        <div class="row g-4">
            <!-- Progress Chart -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="600">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-pie-chart me-2 text-primary"></i>Progress Overview
                        </h5>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="position-relative d-inline-block mb-4">
                            <canvas id="progressChart" width="200" height="200"></canvas>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <div class="h2 fw-bold text-primary mb-0" id="progressPercentage">
                                    {{ $dataCompletion }}%
                                </div>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>

                        <!-- Progress Details -->
                        <div class="row g-3 text-start">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                    <small class="text-muted">Selesai: <strong>{{ $completedSections }}</strong></small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning rounded-circle me-2" style="width: 12px; height: 12px;"></div>
                                    <small class="text-muted">Tersisa: <strong>{{ $totalSections - $completedSections }}</strong></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="700">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-lightning me-2 text-warning"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-3">
                            <!-- Edit Profile -->
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-start">
                                <i class="bi bi-person-gear me-3 fs-4"></i>
                                <div class="text-start">
                                    <div class="fw-semibold">Edit Profile</div>
                                    <small class="text-muted">Perbarui informasi profil Anda</small>
                                </div>
                            </a>

                            <!-- Payment Status -->
                            @if($pendaftar)
                                <a href="{{ route('payment.index') }}" class="btn btn-outline-{{ $isPaid ? 'success' : 'warning' }} btn-lg d-flex align-items-center justify-content-start">
                                    <i class="bi bi-credit-card me-3 fs-4"></i>
                                    <div class="text-start">
                                        <div class="fw-semibold">{{ $isPaid ? 'Riwayat' : 'Status' }} Pembayaran</div>
                                        <small class="text-muted">{{ $isPaid ? 'Lihat riwayat pembayaran' : 'Bayar formulir pendaftaran' }}</small>
                                    </div>
                                    @if($isPaid)
                                        <span class="badge bg-success ms-auto">LUNAS</span>
                                    @endif
                                </a>
                            @endif

                            <!-- Data Completion -->
                            <div id="dataCompletionButton">
                                @if($isPaid && $pendaftar)
                                    <a href="{{ route('user.data') }}" class="btn btn-outline-info btn-lg d-flex align-items-center justify-content-start">
                                        <i class="bi bi-clipboard-data me-3 fs-4"></i>
                                        <div class="text-start">
                                            <div class="fw-semibold">Kelengkapan Data</div>
                                            <small class="text-muted">Lengkapi data pendaftaran Anda</small>
                                        </div>
                                        <span class="badge bg-info ms-auto">{{ $dataCompletion }}%</span>
                                    </a>
                                @elseif(!$pendaftar)
                                    <a href="{{ route('user.data') }}" class="btn btn-outline-info btn-lg d-flex align-items-center justify-content-start">
                                        <i class="bi bi-clipboard-data me-3 fs-4"></i>
                                        <div class="text-start">
                                            <div class="fw-semibold">Isi Data Pendaftar</div>
                                            <small class="text-muted">Lengkapi data pendaftar terlebih dahulu</small>
                                        </div>
                                    </a>
                                @else
                                    <button class="btn btn-outline-secondary btn-lg d-flex align-items-center justify-content-start"
                                            onclick="showPaymentRequired()" disabled>
                                        <i class="bi bi-clipboard-data me-3 fs-4"></i>
                                        <div class="text-start">
                                            <div class="fw-semibold">Kelengkapan Data</div>
                                            <small class="text-muted">Bayar formulir untuk mengakses</small>
                                        </div>
                                        <i class="bi bi-lock ms-auto fs-5"></i>
                                    </button>
                                @endif
                            </div>

                            <!-- Help -->
                            <button class="btn btn-outline-info btn-lg d-flex align-items-center justify-content-start" onclick="showHelp()">
                                <i class="bi bi-question-circle me-3 fs-4"></i>
                                <div class="text-start">
                                    <div class="fw-semibold">Bantuan</div>
                                    <small class="text-muted">Butuh bantuan? Hubungi support</small>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity (if any) -->
        @if($isPaid && $pendaftar && $dataCompletion > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="800">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="card-title mb-0 fw-bold">
                                <i class="bi bi-clock-history me-2 text-secondary"></i>Aktivitas Terbaru
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="timeline-compact">
                                @if($pendaftar->updated_at)
                                    <div class="timeline-item-compact">
                                        <div class="timeline-marker-compact bg-primary"></div>
                                        <div class="timeline-content-compact">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1 fs-6">Data Pendaftar Diperbarui</h6>
                                                    <p class="text-muted mb-0 small">Data pendaftar telah diperbarui</p>
                                                </div>
                                                <small class="text-muted">{{ $pendaftar->updated_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($isPaid && $paymentDate)
                                    <div class="timeline-item-compact">
                                        <div class="timeline-marker-compact bg-success"></div>
                                        <div class="timeline-content-compact">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1 fs-6">Pembayaran Berhasil</h6>
                                                    <p class="text-muted mb-0 small">Pembayaran formulir pendaftaran telah dikonfirmasi</p>
                                                </div>
                                                <small class="text-muted">{{ $paymentDate }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="timeline-item-compact">
                                    <div class="timeline-marker-compact bg-info"></div>
                                    <div class="timeline-content-compact">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 fs-6">Akun Dibuat</h6>
                                                <p class="text-muted mb-0 small">Akun berhasil didaftarkan di sistem PPDB</p>
                                            </div>
                                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Payment Required Modal -->
    <div class="modal fade" id="paymentRequiredModal" tabindex="-1" aria-labelledby="paymentRequiredModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentRequiredModalLabel">
                        <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Pembayaran Diperlukan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="bi bi-credit-card display-1 text-warning mb-3"></i>
                        <h6 class="fw-bold mb-3">Akses Terbatas</h6>
                        <p class="text-muted mb-4">
                            Anda perlu melakukan pembayaran formulir pendaftaran terlebih dahulu untuk mengakses menu kelengkapan data.
                        </p>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Biaya Formulir:</strong> Rp {{ number_format($paymentAmount ?? 150000, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('payment.index') }}" class="btn btn-primary">
                        <i class="bi bi-credit-card me-1"></i>Bayar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="helpModalLabel">
                        <i class="bi bi-question-circle me-2"></i>Bantuan & Support
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="bi bi-envelope display-4 text-primary mb-3"></i>
                                    <h6 class="fw-bold">Email Support</h6>
                                    <p class="text-muted small mb-3">Kirim email untuk bantuan</p>
                                    <a href="mailto:support@ppdb-yapi.com" class="btn btn-primary btn-sm">
                                        support@ppdb-yapi.com
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="bi bi-whatsapp display-4 text-success mb-3"></i>
                                    <h6 class="fw-bold">WhatsApp</h6>
                                    <p class="text-muted small mb-3">Chat langsung dengan admin</p>
                                    <a href="https://wa.me/6281234567890" class="btn btn-success btn-sm" target="_blank">
                                        +62 812-3456-7890
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3">FAQ</h6>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        Bagaimana cara melakukan pembayaran?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Klik menu "Pembayaran" dan ikuti petunjuk untuk melakukan pembayaran melalui payment gateway yang tersedia.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        Kapan bisa mengakses kelengkapan data?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Setelah pembayaran formulir dikonfirmasi, Anda dapat langsung mengakses menu kelengkapan data untuk melengkapi informasi pendaftaran.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        Berapa lama proses verifikasi?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Proses verifikasi biasanya memakan waktu 1-3 hari kerja setelah data lengkap disubmit.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .stat-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .stat-icon {
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        .welcome-content {
            z-index: 2;
            position: relative;
        }

        .welcome-illustration {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .tracking-wide {
            letter-spacing: 0.05em;
        }

        .counter {
            font-family: 'Inter', sans-serif;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .completion-item {
            padding: 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .completion-item.completed {
            background-color: rgba(25, 135, 84, 0.1);
            border-color: rgba(25, 135, 84, 0.2);
        }

        .completion-item.incomplete {
            background-color: rgba(255, 193, 7, 0.1);
            border-color: rgba(255, 193, 7, 0.2);
        }

        .completion-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline-compact {
            position: relative;
            padding-left: 1.5rem;
        }

        .timeline-compact::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
        }

        .timeline-item-compact {
            position: relative;
            margin-bottom: 1rem;
        }

        .timeline-item-compact:last-child {
            margin-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: -2.25rem;
            top: 0.25rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            border: 3px solid #fff;
            box-shadow: 0 0 0 3px currentColor;
        }

        .timeline-marker-compact {
            position: absolute;
            left: -1.75rem;
            top: 0.125rem;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px currentColor;
        }

        .timeline-content {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            border-left: 3px solid #007bff;
        }

        .timeline-content-compact {
            background: #f8f9fa;
            border-radius: 0.375rem;
            padding: 0.75rem;
            border-left: 2px solid #007bff;
            min-height: auto;
        }

        .timeline-content h6 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #495057;
        }

        .timeline-content-compact h6 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #495057;
        }

        .timeline-content p {
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .timeline-content-compact p {
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .timeline-content small {
            font-size: 0.7rem;
            white-space: nowrap;
        }

        .timeline-content-compact small {
            font-size: 0.7rem;
            white-space: nowrap;
        }

        /* Responsive untuk mobile */
        @media (max-width: 576px) {
            .timeline-content-compact {
                padding: 0.5rem;
            }

            .timeline-content-compact .d-flex {
                flex-direction: column;
                gap: 0.25rem;
            }

            .timeline-content-compact small {
                white-space: normal;
                font-size: 0.65rem;
            }
        }

        .btn:disabled {
            opacity: 0.6;
            transform: none !important;
            box-shadow: none !important;
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let currentDataCompletion = {{ $dataCompletion }};
        let currentIsPaid = {{ $isPaid ? 'true' : 'false' }};
        let currentRegistrationStatus = '{{ $registrationStatus }}';

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });

            // Update current date
            updateCurrentDate();

            // Initialize counter animation
            initCounterAnimation();

            // Initialize progress chart
            initProgressChart();

            // Auto refresh dashboard data setiap 30 detik
            setInterval(refreshDashboardData, 30000);
        });

        function updateCurrentDate() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);
        }

        function initCounterAnimation() {
            const counters = document.querySelectorAll('.counter');
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            });

            counters.forEach(counter => observer.observe(counter));
        }

        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 16);
        }

        function initProgressChart() {
            const ctx = document.getElementById('progressChart').getContext('2d');
            const percentage = currentDataCompletion;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [percentage, 100 - percentage],
                        backgroundColor: ['#0dcaf0', '#e9ecef'],
                        borderWidth: 0,
                        cutout: '75%'
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        function showPaymentRequired() {
            const paymentModal = new bootstrap.Modal(document.getElementById('paymentRequiredModal'));
            paymentModal.show();
        }

        function showHelp() {
            const helpModal = new bootstrap.Modal(document.getElementById('helpModal'));
            helpModal.show();
        }

        // Refresh dashboard data
        async function refreshDashboardData() {
            try {
                const response = await fetch('{{ route("user.dashboard.data") }}');
                const data = await response.json();

                // Update jika ada perubahan
                if (data.dataCompletion !== currentDataCompletion) {
                    updateDataCompletion(data.dataCompletion);
                    currentDataCompletion = data.dataCompletion;
                }

                if (data.registrationStatus !== currentRegistrationStatus) {
                    updateRegistrationStatus(data.registrationStatus);
                    currentRegistrationStatus = data.registrationStatus;
                }

            } catch (error) {
                console.error('Error refreshing dashboard data:', error);
            }
        }

        function updateDataCompletion(newCompletion) {
            // Update counter
            const counterElement = document.getElementById('dataCompletionText');
            animateCounterUpdate(counterElement, newCompletion);

            // Update detail
            document.getElementById('dataCompletionDetail').innerHTML = `${newCompletion}% Lengkap`;

            // Update chart percentage
            document.getElementById('progressPercentage').textContent = newCompletion + '%';

            // Re-initialize chart with new data
            initProgressChart();
        }

        function updateRegistrationStatus(newStatus) {
            let statusText, statusClass, statusDetail, statusIcon;

            switch(newStatus) {
                case 'verified':
                    statusText = 'VERIFIED';
                    statusClass = 'text-success';
                    statusDetail = '<i class="bi bi-shield-check me-1 text-success"></i><span class="fw-semibold text-success">Terverifikasi</span>';
                    statusIcon = '<i class="bi bi-shield-check text-success fs-2"></i>';
                    break;
                case 'pending':
                    statusText = 'REVIEW';
                    statusClass = 'text-warning';
                    statusDetail = '<i class="bi bi-hourglass-split me-1 text-warning"></i><span class="fw-semibold text-warning">Dalam Review</span>';
                    statusIcon = '<i class="bi bi-hourglass-split text-warning fs-2"></i>';
                    break;
                default:
                    statusText = 'DRAFT';
                    statusClass = 'text-secondary';
                    statusDetail = '<i class="bi bi-pencil me-1 text-secondary"></i><span class="fw-semibold text-secondary">Belum Submit</span>';
                    statusIcon = '<i class="bi bi-pencil text-secondary fs-2"></i>';
                    break;
            }

            document.getElementById('registrationStatusText').innerHTML = `<span class="${statusClass}">${statusText}</span>`;
            document.getElementById('registrationStatusDetail').innerHTML = statusDetail;
            document.getElementById('registrationStatusIcon').innerHTML = statusIcon;
        }

        function animateCounterUpdate(element, newValue) {
            const currentValue = parseInt(element.textContent);
            const difference = newValue - currentValue;
            const duration = 1000;
            const steps = 60;
            const stepValue = difference / steps;
            let current = currentValue;
            let step = 0;

            const timer = setInterval(() => {
                step++;
                current += stepValue;

                if (step >= steps) {
                    current = newValue;
                    clearInterval(timer);
                }

                element.textContent = Math.floor(current);
            }, duration / steps);
        }
    </script>

    <!-- CSRF Token Meta -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</x-app-layout>
