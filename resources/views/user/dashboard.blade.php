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

                                    <!-- Dynamic Alert berdasarkan tahap pendaftaran -->
                                    <div id="welcomeAlert">
                                        @if(!$pendaftar)
                                            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-info-circle-fill me-2"></i>
                                                <div>
                                                    <strong>Mulai Pendaftaran!</strong> Silakan isi data pendaftar terlebih dahulu untuk memulai proses pendaftaran.
                                                </div>
                                            </div>
                                        @elseif($currentStage == 'formulir_payment')
                                            <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-credit-card me-2"></i>
                                                <div>
                                                    <strong>Pembayaran Formulir Diperlukan!</strong> Silakan lakukan pembayaran formulir sebesar Rp {{ number_format($paymentAmount, 0, ',', '.') }} untuk melanjutkan proses pendaftaran.
                                                    @if(!empty($nextSteps))
                                                        <br><small class="text-muted">Langkah selanjutnya: {{ implode(', ', $nextSteps) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($currentStage == 'data_entry')
                                            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-form me-2"></i>
                                                <div>
                                                    <strong>Lengkapi Data Pendaftaran!</strong> Formulir sudah dibayar. Silakan lengkapi semua data yang diperlukan.
                                                    @if(!empty($nextSteps))
                                                        <br><small class="text-muted">Yang perlu dilakukan: {{ implode(', ', $nextSteps) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($currentStage == 'admin_verification')
                                            <div class="alert alert-primary d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-hourglass-split me-2"></i>
                                                <div>
                                                    <strong>Data Sedang Diverifikasi!</strong> Data pendaftaran Anda sedang diverifikasi oleh admin. Harap menunggu.
                                                    @if(!empty($nextSteps))
                                                        <br><small class="text-muted">{{ implode(', ', $nextSteps) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($currentStage == 'test_scheduling')
                                            <div class="alert alert-primary d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-calendar-check me-2"></i>
                                                <div>
                                                    <strong>Data Terverifikasi!</strong> Data Anda telah diverifikasi. Menunggu penjadwalan tes seleksi.
                                                    @if(!empty($nextSteps))
                                                        <br><small class="text-muted">{{ implode(', ', $nextSteps) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($currentStage == 'test_phase')
                                            <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-pencil-square me-2"></i>
                                                <div>
                                                    <strong>Tes Terjadwal!</strong> Silakan ikuti tes sesuai jadwal yang telah ditentukan.
                                                    @if($pendaftar && $pendaftar->test_date)
                                                        <br><small class="text-muted">Jadwal tes: {{ \Carbon\Carbon::parse($pendaftar->test_date)->format('d M Y, H:i') }}</small>
                                                    @endif
                                                    @if(!empty($nextSteps))
                                                        <br><small class="text-muted">{{ implode(', ', $nextSteps) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($currentStage == 'evaluation')
                                            <div class="alert alert-primary d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-hourglass me-2"></i>
                                                <div>
                                                    <strong>Tes Selesai!</strong> Anda telah menyelesaikan tes. Menunggu hasil evaluasi dari tim seleksi.
                                                    @if(!empty($nextSteps))
                                                        <br><small class="text-muted">{{ implode(', ', $nextSteps) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($currentStage == 'uang_pangkal_payment')
                                            <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-trophy me-2"></i>
                                                <div>
                                                    <strong>Selamat! Anda Diterima!</strong> Silakan lakukan pembayaran uang pangkal untuk mengkonfirmasi penerimaan.
                                                    @if(!empty($nextSteps))
                                                        <br><small class="text-muted">{{ implode(', ', $nextSteps) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($currentStage == 'regular_billing')
                                            <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-check-circle-fill me-2"></i>
                                                <div>
                                                    <strong>Pendaftaran Selesai!</strong> Selamat! Anda telah resmi menjadi siswa. Pantau tagihan rutin di menu pembayaran.
                                                    @if($paymentDate)
                                                        <br><small>Terakhir dibayar: {{ $paymentDate }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($registrationStatus == 'rejected')
                                            <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-x-circle-fill me-2"></i>
                                                <div>
                                                    <strong>Hasil Seleksi</strong> Mohon maaf, Anda belum dapat diterima pada periode ini. Silakan coba lagi di periode selanjutnya.
                                                    @if($pendaftar && $pendaftar->rejection_reason)
                                                        <br><small class="text-muted">Keterangan: {{ $pendaftar->rejection_reason }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-secondary d-flex align-items-center mb-4" role="alert">
                                                <i class="bi bi-info-circle-fill me-2"></i>
                                                <div>
                                                    <strong>Selamat Datang!</strong> Silakan mulai proses pendaftaran dengan mengisi data lengkap.
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="d-flex flex-wrap gap-3">
                                        <a href="{{ route('profile.edit') }}" class="btn btn-light btn-lg px-4 shadow-sm">
                                            <i class="bi bi-person-gear me-2"></i>Edit Profile
                                        </a>
                                        @if($pendaftar)
                                            <a href="{{ route('user.payments.index') }}" class="btn btn-outline-light btn-lg px-4">
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

        <!-- Registration Flow Progress -->
        @if($pendaftar)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="bi bi-diagram-3 me-2 text-primary"></i>Progres Pendaftaran
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="progress-flow">
                                @php
                                    $stages = [
                                        'formulir_payment' => ['icon' => 'credit-card', 'title' => 'Bayar Formulir', 'desc' => 'Pembayaran formulir pendaftaran'],
                                        'data_entry' => ['icon' => 'form', 'title' => 'Isi Data', 'desc' => 'Lengkapi data pendaftaran'],
                                        'admin_verification' => ['icon' => 'shield-check', 'title' => 'Verifikasi', 'desc' => 'Verifikasi admin'],
                                        'test_phase' => ['icon' => 'pencil-square', 'title' => 'Tes Seleksi', 'desc' => 'Mengikuti tes'],
                                        'evaluation' => ['icon' => 'hourglass', 'title' => 'Evaluasi', 'desc' => 'Menunggu hasil'],
                                        'uang_pangkal_payment' => ['icon' => 'cash-coin', 'title' => 'Uang Pangkal', 'desc' => 'Bayar uang pangkal'],
                                        'regular_billing' => ['icon' => 'check-circle', 'title' => 'Terdaftar', 'desc' => 'Siswa terdaftar'],
                                        'student_status' => ['icon' => 'person-check', 'title' => 'Status Siswa', 'desc' => 'Status keaktifan siswa']
                                    ];

                                    $stageOrder = array_keys($stages);
                                    $currentStageIndex = array_search($currentStage, $stageOrder);
                                    if ($currentStageIndex === false) $currentStageIndex = 0;
                                @endphp

                                <div class="row">
                                    @foreach($stages as $stageKey => $stage)
                                        @php
                                            $stageIndex = array_search($stageKey, $stageOrder);
                                            $isCompleted = $stageIndex < $currentStageIndex;
                                            $isCurrent = $stageKey === $currentStage;
                                            $isPending = $stageIndex > $currentStageIndex;

                                            // Handle special case for rejected
                                            if ($registrationStatus === 'rejected' && $stageKey === 'regular_billing') {
                                                $isCompleted = false;
                                                $isCurrent = false;
                                                $isPending = true;
                                            }
                                        @endphp

                                        <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                            <div class="progress-stage {{ $isCompleted ? 'completed' : ($isCurrent ? 'current' : 'pending') }}">
                                                @if($stageKey === 'student_status')
                                                    <!-- Special Status Siswa card -->
                                                    <div class="stage-wrapper p-3 border rounded {{ $isActiveStudent ? 'border-success bg-success bg-opacity-10' : 'border-secondary bg-light' }}">
                                                        <div class="stage-icon text-center mb-2">
                                                            @if($isActiveStudent)
                                                                <i class="bi bi-person-check-fill text-success fs-1"></i>
                                                            @else
                                                                <i class="bi bi-person-dash text-muted fs-1"></i>
                                                            @endif
                                                        </div>
                                                        <div class="stage-content text-center">
                                                            <h6 class="stage-title {{ $isActiveStudent ? 'text-success' : 'text-muted' }}">
                                                                {{ $stage['title'] }}
                                                            </h6>
                                                            <small class="stage-desc text-muted">{{ $stage['desc'] }}</small>
                                                            <div class="stage-action mt-2">
                                                                @if($isActiveStudent)
                                                                    <small class="text-success fw-semibold">
                                                                        <i class="bi bi-check-circle me-1"></i>Siswa Aktif
                                                                    </small>
                                                                @else
                                                                    <small class="text-muted fw-semibold">
                                                                        <i class="bi bi-dash-circle me-1"></i>Belum Aktif
                                                                    </small>
                                                                @endif
                                                            </div>
                                                            @if($studentStatus && $studentStatus !== 'inactive')
                                                                <div class="mt-1">
                                                                    <small class="text-muted">Status: {{ ucfirst($studentStatus) }}</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @elseif($stageKey === 'data_entry' && $isPaid)
                                                    <!-- Make data_entry stage clickable if paid (regardless of completion status) -->
                                                    <a href="{{ route('user.data.index') }}" class="text-decoration-none">
                                                        <div class="stage-wrapper p-3 border rounded {{ $isCompleted ? 'border-success bg-success bg-opacity-10' : ($isCurrent ? 'border-info bg-info bg-opacity-10' : 'border-secondary bg-light') }} stage-clickable">
                                                            <div class="stage-icon text-center mb-2">
                                                                @if($isCompleted)
                                                                    <i class="bi bi-check-circle-fill text-success fs-1"></i>
                                                                @elseif($isCurrent)
                                                                    <i class="bi bi-{{ $stage['icon'] }} text-info fs-1"></i>
                                                                @else
                                                                    <i class="bi bi-circle text-muted fs-1"></i>
                                                                @endif
                                                            </div>
                                                            <div class="stage-content text-center">
                                                                <h6 class="stage-title {{ $isCompleted ? 'text-success' : ($isCurrent ? 'text-info' : 'text-muted') }}">
                                                                    {{ $stage['title'] }}
                                                                </h6>
                                                                <small class="stage-desc text-muted">{{ $stage['desc'] }}</small>
                                                                @if($isCurrent && !empty($nextSteps))
                                                                    <div class="stage-action mt-2">
                                                                        <small class="text-info fw-semibold">
                                                                            <i class="bi bi-arrow-right me-1"></i>{{ $nextSteps[0] }}
                                                                        </small>
                                                                    </div>
                                                                @endif
                                                                <div class="stage-action mt-2">
                                                                    <small class="text-info fw-semibold">
                                                                        <i class="bi bi-cursor me-1"></i>Klik untuk melengkapi
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @elseif($stageKey === 'formulir_payment' && !$isPaid)
                                                    <!-- Make formulir_payment stage clickable if not paid -->
                                                    <a href="{{ route('user.payments.index') }}" class="text-decoration-none">
                                                        <div class="stage-wrapper p-3 border rounded {{ $isCompleted ? 'border-success bg-success bg-opacity-10' : ($isCurrent ? 'border-warning bg-warning bg-opacity-10' : 'border-secondary bg-light') }} stage-clickable">
                                                            <div class="stage-icon text-center mb-2">
                                                                @if($isCompleted)
                                                                    <i class="bi bi-check-circle-fill text-success fs-1"></i>
                                                                @elseif($isCurrent)
                                                                    <i class="bi bi-{{ $stage['icon'] }} text-warning fs-1"></i>
                                                                @else
                                                                    <i class="bi bi-circle text-muted fs-1"></i>
                                                                @endif
                                                            </div>
                                                            <div class="stage-content text-center">
                                                                <h6 class="stage-title {{ $isCompleted ? 'text-success' : ($isCurrent ? 'text-warning' : 'text-muted') }}">
                                                                    {{ $stage['title'] }}
                                                                </h6>
                                                                <small class="stage-desc text-muted">{{ $stage['desc'] }}</small>
                                                                @if($isCurrent && !empty($nextSteps))
                                                                    <div class="stage-action mt-2">
                                                                        <small class="text-warning fw-semibold">
                                                                            <i class="bi bi-arrow-right me-1"></i>{{ $nextSteps[0] }}
                                                                        </small>
                                                                    </div>
                                                                @endif
                                                                <div class="stage-action mt-2">
                                                                    <small class="text-warning fw-semibold">
                                                                        <i class="bi bi-cursor me-1"></i>Klik untuk bayar
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @else
                                                    <!-- Regular non-clickable stage -->
                                                    <div class="stage-wrapper p-3 border rounded {{ $isCompleted ? 'border-success bg-success bg-opacity-10' : ($isCurrent ? 'border-primary bg-primary bg-opacity-10' : 'border-secondary bg-light') }}">
                                                        <div class="stage-icon text-center mb-2">
                                                            @if($isCompleted)
                                                                <i class="bi bi-check-circle-fill text-success fs-1"></i>
                                                            @elseif($isCurrent)
                                                                <i class="bi bi-{{ $stage['icon'] }} text-primary fs-1"></i>
                                                            @else
                                                                <i class="bi bi-circle text-muted fs-1"></i>
                                                            @endif
                                                        </div>
                                                        <div class="stage-content text-center">
                                                            <h6 class="stage-title {{ $isCompleted ? 'text-success' : ($isCurrent ? 'text-primary' : 'text-muted') }}">
                                                                {{ $stage['title'] }}
                                                            </h6>
                                                            <small class="stage-desc text-muted">{{ $stage['desc'] }}</small>
                                                            @if($isCurrent && !empty($nextSteps))
                                                                <div class="stage-action mt-2">
                                                                    <small class="text-primary fw-semibold">
                                                                        <i class="bi bi-arrow-right me-1"></i>{{ $nextSteps[0] }}
                                                                    </small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Current Stage Status -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">
                                    Tahap Saat Ini
                                </div>
                                <div class="h4 fw-bold mb-2" id="currentStageText">
                                    @if($currentStage == 'formulir_payment')
                                        <span class="text-warning">PEMBAYARAN FORMULIR</span>
                                    @elseif($currentStage == 'data_entry')
                                        <span class="text-info">PENGISIAN DATA</span>
                                    @elseif($currentStage == 'admin_verification')
                                        <span class="text-primary">VERIFIKASI ADMIN</span>
                                    @elseif($currentStage == 'test_scheduling')
                                        <span class="text-primary">PENJADWALAN TES</span>
                                    @elseif($currentStage == 'test_phase')
                                        <span class="text-primary">TAHAP TES</span>
                                    @elseif($currentStage == 'evaluation')
                                        <span class="text-primary">EVALUASI</span>
                                    @elseif($currentStage == 'uang_pangkal_payment')
                                        <span class="text-success">BAYAR UANG PANGKAL</span>
                                    @elseif($currentStage == 'regular_billing')
                                        <span class="text-success">TERDAFTAR</span>
                                    @elseif($currentStage == 'completed' && $registrationStatus == 'rejected')
                                        <span class="text-danger">TIDAK LULUS</span>
                                    @else
                                        <span class="text-secondary">PENDAFTARAN</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center small" id="currentStageDetail">
                                    @if(!empty($nextSteps))
                                        <i class="bi bi-arrow-right me-1 text-primary"></i>
                                        <span class="fw-semibold text-primary">{{ $nextSteps[0] }}</span>
                                    @else
                                        <i class="bi bi-check-circle me-1 text-success"></i>
                                        <span class="fw-semibold text-success">Tahap selesai</span>
                                    @endif
                                </div>
                            </div>
                            <div class="stat-icon bg-opacity-10 rounded-3 p-3" id="currentStageIcon">
                                @if($currentStage == 'formulir_payment')
                                    <i class="bi bi-credit-card text-warning fs-2"></i>
                                @elseif($currentStage == 'data_entry')
                                    <i class="bi bi-form text-info fs-2"></i>
                                @elseif($currentStage == 'admin_verification')
                                    <i class="bi bi-shield-check text-primary fs-2"></i>
                                @elseif($currentStage == 'test_scheduling')
                                    <i class="bi bi-calendar-check text-primary fs-2"></i>
                                @elseif($currentStage == 'test_phase')
                                    <i class="bi bi-pencil-square text-primary fs-2"></i>
                                @elseif($currentStage == 'evaluation')
                                    <i class="bi bi-hourglass text-primary fs-2"></i>
                                @elseif($currentStage == 'uang_pangkal_payment')
                                    <i class="bi bi-cash-coin text-success fs-2"></i>
                                @elseif($currentStage == 'regular_billing')
                                    <i class="bi bi-check-circle text-success fs-2"></i>
                                @elseif($currentStage == 'completed' && $registrationStatus == 'rejected')
                                    <i class="bi bi-x-circle text-danger fs-2"></i>
                                @else
                                    <i class="bi bi-person-plus text-secondary fs-2"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Status -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">
                                    Tagihan Aktif
                                </div>
                                <div class="h4 fw-bold mb-2" id="billingStatusText">
                                    @if($totalUnpaidAmount > 0)
                                        <span class="text-warning">Rp {{ number_format($totalUnpaidAmount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-success">LUNAS</span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center small" id="billingStatusDetail">
                                    @if($totalUnpaidAmount > 0)
                                        <i class="bi bi-exclamation-triangle me-1 text-warning"></i>
                                        <span class="fw-semibold text-warning">{{ count($activeBills) }} tagihan belum dibayar</span>
                                    @else
                                        <i class="bi bi-check-circle me-1 text-success"></i>
                                        <span class="fw-semibold text-success">Semua tagihan lunas</span>
                                    @endif
                                </div>
                            </div>
                            <div class="stat-icon bg-opacity-10 rounded-3 p-3" id="billingStatusIcon">
                                @if($totalUnpaidAmount > 0)
                                    <i class="bi bi-receipt text-warning fs-2"></i>
                                @else
                                    <i class="bi bi-check-circle-fill text-success fs-2"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body p-4 d-flex align-items-center justify-content-center">
                        @if($currentStage == 'formulir_payment' && !$isPaid)
                            <div class="btn btn-warning btn-lg w-100 d-flex align-items-center justify-content-center" disabled>
                                <i class="bi bi-credit-card me-2 fs-4"></i>
                                <div>
                                    <div class="fw-bold">Bayar Formulir</div>
                                    <small class="opacity-75">Rp {{ number_format($formulirBill->total_amount ?? ($pendaftar ? $pendaftar->getFormulirAmountByUnit() : 0), 0, ',', '.') }}</small>
                                </div>
                            </div>
                        @elseif($currentStage == 'data_entry' && $isPaid)
                            <div class="btn btn-info btn-lg w-100 d-flex align-items-center justify-content-center" disabled>
                                <i class="bi bi-form me-2 fs-4"></i>
                                <div>
                                    <div class="fw-bold">Lengkapi Data</div>
                                    <small class="opacity-75">Isi Kelengkapan Data</small>
                                </div>
                            </div>
                        @elseif($currentStage == 'data_entry' && !$isPaid)
                            <div class="btn btn-warning btn-lg w-100 d-flex align-items-center justify-content-center" disabled>
                                <i class="bi bi-credit-card me-2 fs-4"></i>
                                <div>
                                    <div class="fw-bold">Bayar Formulir Dulu</div>
                                    <small class="opacity-75">Pembayaran diperlukan</small>
                                </div>
                            </div>
                        @elseif($isPaid && $currentStage != 'data_entry')
                            <div class="btn btn-info btn-lg w-100 d-flex align-items-center justify-content-center" disabled>
                                <i class="bi bi-form me-2 fs-4"></i>
                                <div>
                                    <div class="fw-bold">Lengkapi Data</div>
                                    <small class="opacity-75">Data sudah bisa diisi</small>
                                </div>
                            </div>
                        @elseif($currentStage == 'uang_pangkal_payment')
                            <div class="btn btn-success btn-lg w-100 d-flex align-items-center justify-content-center" disabled>
                                <i class="bi bi-cash-coin me-2 fs-4"></i>
                                <div>
                                    <div class="fw-bold">Bayar Uang Pangkal</div>
                                    <small class="opacity-75">Konfirmasi Penerimaan</small>
                                </div>
                            </div>
                        @elseif(!$pendaftar)
                            <div class="btn btn-primary btn-lg w-100 d-flex align-items-center justify-content-center" disabled>
                                <i class="bi bi-person-plus me-2 fs-4"></i>
                                <div>
                                    <div class="fw-bold">Mulai Daftar</div>
                                    <small class="opacity-75">Isi Data Pendaftar</small>
                                </div>
                            </div>
                        @else
                            <!-- Fallback: If user has paid but logic is unclear, provide both options -->
                            @if($isPaid && $pendaftar)
                                <div class="d-grid gap-2">
                                    <div class="btn btn-info btn-lg d-flex align-items-center justify-content-center" disabled>
                                        <i class="bi bi-form me-2 fs-4"></i>
                                        <div>
                                            <div class="fw-bold">Lengkapi Data</div>
                                            <small class="opacity-75">Formulir sudah dibayar</small>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="btn btn-light btn-lg w-100 d-flex align-items-center justify-content-center" disabled>
                                    <i class="bi bi-hourglass me-2 fs-4 text-muted"></i>
                                    <div>
                                        <div class="fw-bold text-muted">Menunggu</div>
                                        <small class="text-muted">Proses sedang berjalan</small>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Billing Section -->
        @if($pendaftar && count($activeBills) > 0)
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="600">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fw-bold">
                                    <i class="bi bi-receipt me-2 text-primary"></i>Tagihan Aktif
                                </h5>
                                <span class="badge bg-warning fs-6">{{ count($activeBills) }} tagihan belum dibayar</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <div class="row g-3">
                                    @foreach($activeBills as $bill)
                                        <div class="col-lg-4 col-md-6">
                                            <div class="card border border-warning">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <span class="badge bg-warning text-dark">{{ ucfirst($bill->bill_type) }}</span>
                                                        @if($bill->due_date && \Carbon\Carbon::parse($bill->due_date)->isPast())
                                                            <small class="text-danger fw-semibold">Terlambat</small>
                                                        @endif
                                                    </div>
                                                    <h6 class="fw-semibold">{{ $bill->description ?? ucfirst($bill->bill_type) }}</h6>
                                                    <div class="mb-2">
                                                        <div class="text-muted small">Jumlah Tagihan</div>
                                                        <div class="h6 text-warning">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</div>
                                                    </div>
                                                    @if($bill->remaining_amount < $bill->total_amount)
                                                        <div class="mb-2">
                                                            <div class="text-muted small">Sisa Pembayaran</div>
                                                            <div class="h6 text-danger">Rp {{ number_format($bill->remaining_amount, 0, ',', '.') }}</div>
                                                        </div>
                                                        <div class="progress mb-2" style="height: 6px;">
                                                            <div class="progress-bar bg-info"
                                                                 style="width: {{ (($bill->total_amount - $bill->remaining_amount) / $bill->total_amount) * 100 }}%"></div>
                                                        </div>
                                                        <small class="text-muted">
                                                            Dibayar: Rp {{ number_format($bill->total_amount - $bill->remaining_amount, 0, ',', '.') }}
                                                        </small>
                                                    @endif
                                                    @if($bill->due_date)
                                                        <div class="mt-2">
                                                            <small class="text-muted">
                                                                <i class="bi bi-calendar3 me-1"></i>
                                                                Jatuh tempo: {{ \Carbon\Carbon::parse($bill->due_date)->format('d M Y') }}
                                                            </small>
                                                        </div>
                                                    @endif
                                                    <div class="mt-3">
                                                        <a href="{{ route('user.payments.index') }}" class="btn btn-warning btn-sm w-100">
                                                            <i class="bi bi-credit-card me-1"></i>Bayar Sekarang
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('user.payments.index') }}" class="btn btn-warning">
                                    <i class="bi bi-credit-card me-1"></i>Bayar Tagihan
                                </a>
                                <a href="{{ route('user.transactions.index') }}" class="btn btn-outline-primary ms-2">
                                    <i class="bi bi-clock-history me-1"></i>Riwayat Transaksi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
                            <strong>Biaya Formulir:</strong> Rp {{ number_format($paymentAmount ?? ($pendaftar ? $pendaftar->getFormulirAmountByUnit() : 0), 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('user.payments.index') }}" class="btn btn-primary">
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

        /* Stage clickable hover effects */
        .stage-clickable {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stage-clickable:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
            border-color: var(--bs-primary) !important;
        }

        .stage-clickable:hover .stage-icon i {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }

        .stage-clickable:hover .stage-title {
            color: var(--bs-primary) !important;
        }

        /* Disable hover effects for disabled buttons */
        .btn:disabled:hover {
            transform: none !important;
            box-shadow: none !important;
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
        let currentStage = '{{ $currentStage }}';

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
            const chartElement = document.getElementById('progressChart');
            if (!chartElement) {
                console.log('Progress chart element not found, skipping chart initialization');
                return;
            }

            const ctx = chartElement.getContext('2d');
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

                // Update data completion
                if (data.dataCompletion !== currentDataCompletion) {
                    updateDataCompletion(data.dataCompletion);
                    currentDataCompletion = data.dataCompletion;
                }

                // Update registration status and stage
                if (data.currentStage !== currentRegistrationStatus) {
                    updateRegistrationStatus(data.registrationStatus, data.currentStage);
                    currentRegistrationStatus = data.currentStage;
                }

                // Update billing status
                if (data.totalUnpaidAmount !== undefined) {
                    updateBillingStatus(data.totalUnpaidAmount, data.activeBills);
                }

                // Update student status
                if (data.studentStatus !== undefined) {
                    updateStudentStatus(data.studentStatus);
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
                case 'Diverifikasi':
                    statusText = 'VERIFIED';
                    statusClass = 'text-success';
                    statusDetail = '<i class="bi bi-shield-check me-1 text-success"></i><span class="fw-semibold text-success">Terverifikasi</span>';
                    statusIcon = '<i class="bi bi-shield-check text-success fs-2"></i>';
                    break;
                case 'Sudah Bayar':
                    statusText = 'SUDAH BAYAR';
                    statusClass = 'text-info';
                    statusDetail = '<i class="bi bi-credit-card-check me-1 text-info"></i><span class="fw-semibold text-info">Pembayaran Diterima</span>';
                    statusIcon = '<i class="bi bi-credit-card-check text-info fs-2"></i>';
                    break;
                case 'Observasi':
                    statusText = 'OBSERVASI';
                    statusClass = 'text-primary';
                    statusDetail = '<i class="bi bi-eyeglasses me-1 text-primary"></i><span class="fw-semibold text-primary">Dalam Observasi</span>';
                    statusIcon = '<i class="bi bi-eyeglasses text-primary fs-2"></i>';
                    break;
                case 'Tes Tulis':
                    statusText = 'TES TULIS';
                    statusClass = 'text-primary';
                    statusDetail = '<i class="bi bi-pencil-square me-1 text-primary"></i><span class="fw-semibold text-primary">Tes Tulis</span>';
                    statusIcon = '<i class="bi bi-pencil-square text-primary fs-2"></i>';
                    break;
                case 'Praktek Shalat & BTQ':
                    statusText = 'PRAKTEK';
                    statusClass = 'text-primary';
                    statusDetail = '<i class="bi bi-book me-1 text-primary"></i><span class="fw-semibold text-primary">Praktek Shalat & BTQ</span>';
                    statusIcon = '<i class="bi bi-book text-primary fs-2"></i>';
                    break;
                case 'Wawancara':
                    statusText = 'WAWANCARA';
                    statusClass = 'text-primary';
                    statusDetail = '<i class="bi bi-chat-dots me-1 text-primary"></i><span class="fw-semibold text-primary">Wawancara</span>';
                    statusIcon = '<i class="bi bi-chat-dots text-primary fs-2"></i>';
                    break;
                case 'Psikotest':
                    statusText = 'PSIKOTEST';
                    statusClass = 'text-primary';
                    statusDetail = '<i class="bi bi-brain me-1 text-primary"></i><span class="fw-semibold text-primary">Psikotest</span>';
                    statusIcon = '<i class="bi bi-brain text-primary fs-2"></i>';
                    break;
                case 'Lulus':
                    statusText = 'LULUS';
                    statusClass = 'text-success';
                    statusDetail = '<i class="bi bi-award me-1 text-success"></i><span class="fw-semibold text-success">Lulus Seleksi</span>';
                    statusIcon = '<i class="bi bi-award text-success fs-2"></i>';
                    break;
                case 'Tidak Lulus':
                    statusText = 'TIDAK LULUS';
                    statusClass = 'text-danger';
                    statusDetail = '<i class="bi bi-x-circle me-1 text-danger"></i><span class="fw-semibold text-danger">Tidak Lulus Seleksi</span>';
                    statusIcon = '<i class="bi bi-x-circle text-danger fs-2"></i>';
                    break;
                default:
                    statusText = 'DRAFT';
                    statusClass = 'text-secondary';
                    statusDetail = '<i class="bi bi-pencil me-1 text-secondary"></i><span class="fw-semibold text-secondary">Draft</span>';
                    statusIcon = '<i class="bi bi-pencil text-secondary fs-2"></i>';
                    break;
            }

            document.getElementById('registrationStatusText').innerHTML = `<span class="${statusClass}">${statusText}</span>`;
            document.getElementById('registrationStatusDetail').innerHTML = statusDetail;
            document.getElementById('registrationStatusIcon').innerHTML = statusIcon;
        }

        function updateBillingStatus(totalUnpaidAmount, activeBills) {
            const billingStatusText = document.getElementById('billingStatusText');
            const billingStatusDetail = document.getElementById('billingStatusDetail');
            const billingStatusIcon = document.getElementById('billingStatusIcon');

            if (totalUnpaidAmount > 0) {
                billingStatusText.innerHTML = `<span class="text-warning">Rp ${totalUnpaidAmount.toLocaleString('id-ID')}</span>`;
                billingStatusDetail.innerHTML = `<i class="bi bi-exclamation-triangle me-1 text-warning"></i><span class="fw-semibold text-warning">${activeBills.length} tagihan belum dibayar</span>`;
                billingStatusIcon.innerHTML = '<i class="bi bi-receipt text-warning fs-2"></i>';
            } else {
                billingStatusText.innerHTML = '<span class="text-success">LUNAS</span>';
                billingStatusDetail.innerHTML = '<i class="bi bi-check-circle me-1 text-success"></i><span class="fw-semibold text-success">Semua tagihan lunas</span>';
                billingStatusIcon.innerHTML = '<i class="bi bi-check-circle-fill text-success fs-2"></i>';
            }
        }

        function updateStudentStatus(studentStatus) {
            const studentStatusText = document.getElementById('studentStatusText');
            const studentStatusDetail = document.getElementById('studentStatusDetail');
            const studentStatusIcon = document.getElementById('studentStatusIcon');

            let statusText, statusClass, statusDetail, statusIcon;

            switch(studentStatus) {
                case 'active':
                    statusText = 'AKTIF';
                    statusClass = 'text-success';
                    statusDetail = '<i class="bi bi-check-circle me-1 text-success"></i><span class="fw-semibold text-success">Siswa aktif terdaftar</span>';
                    statusIcon = '<i class="bi bi-person-check text-success fs-2"></i>';
                    break;
                case 'graduated':
                    statusText = 'LULUS';
                    statusClass = 'text-primary';
                    statusDetail = '<i class="bi bi-mortarboard me-1 text-primary"></i><span class="fw-semibold text-primary">Telah menyelesaikan studi</span>';
                    statusIcon = '<i class="bi bi-mortarboard text-primary fs-2"></i>';
                    break;
                case 'dropped_out':
                    statusText = 'KELUAR';
                    statusClass = 'text-danger';
                    statusDetail = '<i class="bi bi-x-circle me-1 text-danger"></i><span class="fw-semibold text-danger">Siswa tidak aktif</span>';
                    statusIcon = '<i class="bi bi-person-x text-danger fs-2"></i>';
                    break;
                case 'transferred':
                    statusText = 'PINDAH';
                    statusClass = 'text-warning';
                    statusDetail = '<i class="bi bi-arrow-right-circle me-1 text-warning"></i><span class="fw-semibold text-warning">Pindah ke sekolah lain</span>';
                    statusIcon = '<i class="bi bi-arrow-right-square text-warning fs-2"></i>';
                    break;
                default:
                    statusText = 'BELUM AKTIF';
                    statusClass = 'text-secondary';
                    statusDetail = '<i class="bi bi-hourglass me-1 text-secondary"></i><span class="fw-semibold text-secondary">Menunggu aktivasi</span>';
                    statusIcon = '<i class="bi bi-person-dash text-secondary fs-2"></i>';
                    break;
            }

            studentStatusText.innerHTML = `<span class="${statusClass}">${statusText}</span>`;
            studentStatusDetail.innerHTML = statusDetail;
            studentStatusIcon.innerHTML = statusIcon;
        }

        function updateRegistrationStatus(newStatus, currentStage = null) {
            let statusText, statusClass, statusDetail, statusIcon;

            // Use currentStage if provided, otherwise derive from newStatus
            const stage = currentStage || newStatus;

            switch(stage) {
                case 'formulir_payment':
                    statusText = 'PEMBAYARAN FORMULIR';
                    statusClass = 'text-warning';
                    statusDetail = '<i class="bi bi-credit-card me-1 text-warning"></i><span class="fw-semibold text-warning">Perlu bayar formulir</span>';
                    statusIcon = '<i class="bi bi-credit-card text-warning fs-2"></i>';
                    break;
                case 'data_entry':
                    statusText = 'PENGISIAN DATA';
                    statusClass = 'text-info';
                    statusDetail = '<i class="bi bi-form me-1 text-info"></i><span class="fw-semibold text-info">Lengkapi data pendaftaran</span>';
                    statusIcon = '<i class="bi bi-form text-info fs-2"></i>';
                    break;
                case 'admin_verification':
                    statusText = 'VERIFIKASI ADMIN';
                    statusClass = 'text-primary';
                    statusDetail = '<i class="bi bi-hourglass-split me-1 text-primary"></i><span class="fw-semibold text-primary">Menunggu verifikasi</span>';
                    statusIcon = '<i class="bi bi-shield-check text-primary fs-2"></i>';
                    break;
                case 'test_scheduling':
                    statusText = 'PENJADWALAN TES';
                    statusClass = 'text-primary';
                    statusDetail = '<i class="bi bi-calendar-check me-1 text-primary"></i><span class="fw-semibold text-primary">Menunggu jadwal tes</span>';
                    statusIcon = '<i class="bi bi-calendar-check text-primary fs-2"></i>';
                    break;
                case 'test_phase':
                    statusText = 'TAHAP TES';
                    statusClass = 'text-primary';
                    statusDetail = '<i class="bi bi-pencil-square me-1 text-primary"></i><span class="fw-semibold text-primary">Mengikuti tes</span>';
                    statusIcon = '<i class="bi bi-pencil-square text-primary fs-2"></i>';
                    break;
                case 'evaluation':
                    statusText = 'EVALUASI';
                    statusClass = 'text-primary';
                    statusDetail = '<i class="bi bi-hourglass me-1 text-primary"></i><span class="fw-semibold text-primary">Menunggu hasil</span>';
                    statusIcon = '<i class="bi bi-hourglass text-primary fs-2"></i>';
                    break;
                case 'uang_pangkal_payment':
                    statusText = 'BAYAR UANG PANGKAL';
                    statusClass = 'text-success';
                    statusDetail = '<i class="bi bi-cash-coin me-1 text-success"></i><span class="fw-semibold text-success">Diterima, bayar uang pangkal</span>';
                    statusIcon = '<i class="bi bi-cash-coin text-success fs-2"></i>';
                    break;
                case 'regular_billing':
                    statusText = 'TERDAFTAR';
                    statusClass = 'text-success';
                    statusDetail = '<i class="bi bi-check-circle me-1 text-success"></i><span class="fw-semibold text-success">Siswa terdaftar</span>';
                    statusIcon = '<i class="bi bi-check-circle text-success fs-2"></i>';
                    break;
                case 'rejected':
                    statusText = 'TIDAK LULUS';
                    statusClass = 'text-danger';
                    statusDetail = '<i class="bi bi-x-circle me-1 text-danger"></i><span class="fw-semibold text-danger">Tidak lulus seleksi</span>';
                    statusIcon = '<i class="bi bi-x-circle text-danger fs-2"></i>';
                    break;
                default:
                    statusText = 'DRAFT';
                    statusClass = 'text-secondary';
                    statusDetail = '<i class="bi bi-pencil me-1 text-secondary"></i><span class="fw-semibold text-secondary">Belum dimulai</span>';
                    statusIcon = '<i class="bi bi-pencil text-secondary fs-2"></i>';
                    break;
            }
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
