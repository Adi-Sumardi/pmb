<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-house-door me-2 text-primary"></i>
                Dashboard Pendaftar
            </h2>
            <div class="d-flex align-items-center gap-3">
                <!-- Demo Payment Button (untuk testing) -->
                @if(!$isPaid && $pendaftar)
                    <button class="btn btn-warning btn-sm" onclick="demoPayment()" id="demoPaymentBtn">
                        <i class="bi bi-credit-card me-1"></i>Demo Payment
                    </button>
                @endif
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

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Payment Status -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">
                                    Status Pembayaran
                                </div>
                                <div class="display-6 fw-bold mb-2" id="paymentStatusText">
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
                                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                @else
                                    <i class="bi bi-clock text-warning fs-3"></i>
                                @endif
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar" id="paymentProgress"
                                 role="progressbar"
                                 style="width: {{ $isPaid ? '100' : '50' }}%"
                                 class="{{ $isPaid ? 'bg-success' : 'bg-warning' }}"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Completion -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">
                                    Kelengkapan Data
                                </div>
                                <div class="display-6 fw-bold text-info mb-2 counter" data-target="{{ $dataCompletion }}" id="dataCompletionText">
                                    {{ $dataCompletion }}
                                </div>
                                <div class="d-flex align-items-center text-info small">
                                    <i class="bi bi-graph-up me-1"></i>
                                    <span class="fw-semibold" id="dataCompletionDetail">{{ $dataCompletion }}% Lengkap</span>
                                </div>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-clipboard-data text-info fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-info" id="dataProgress" role="progressbar" style="width: {{ $dataCompletion }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Status -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">
                                    Status Pendaftaran
                                </div>
                                <div class="display-6 fw-bold mb-2" id="registrationStatusText">
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
                                    <i class="bi bi-shield-check text-success fs-3"></i>
                                @elseif($registrationStatus == 'pending')
                                    <i class="bi bi-hourglass-split text-warning fs-3"></i>
                                @else
                                    <i class="bi bi-pencil text-secondary fs-3"></i>
                                @endif
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar" id="registrationProgress"
                                 role="progressbar"
                                 style="width: @if($registrationStatus == 'verified') 100% @elseif($registrationStatus == 'pending') 75% @else 25% @endif"
                                 class="@if($registrationStatus == 'verified') bg-success @elseif($registrationStatus == 'pending') bg-warning @else bg-secondary @endif"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Status Overview -->
        <div class="row g-4">
            <!-- Quick Actions -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="500">
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
                                    <a href="{{ route('pendaftar.form') }}" class="btn btn-outline-info btn-lg d-flex align-items-center justify-content-start">
                                        <i class="bi bi-clipboard-data me-3 fs-4"></i>
                                        <div class="text-start">
                                            <div class="fw-semibold">Kelengkapan Data</div>
                                            <small class="text-muted">Lengkapi data pendaftaran Anda</small>
                                        </div>
                                        <span class="badge bg-info ms-auto">{{ $dataCompletion }}%</span>
                                    </a>
                                @elseif(!$pendaftar)
                                    <a href="{{ route('pendaftar.form') }}" class="btn btn-outline-info btn-lg d-flex align-items-center justify-content-start">
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

            <!-- Status Overview -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="600">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-bar-chart me-2 text-primary"></i>Progress Overview
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Progress Chart -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <canvas id="progressChart" width="200" height="200"></canvas>
                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                    <div class="h3 fw-bold text-primary mb-0" id="progressPercentage">
                                        {{ $dataCompletion }}%
                                    </div>
                                    <small class="text-muted">Completed</small>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Steps -->
                        <div class="d-flex flex-column gap-3" id="progressSteps">
                            <div class="d-flex align-items-center">
                                <div class="status-dot {{ $pendaftar ? 'bg-success' : 'bg-secondary' }} me-3"></div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">Data Pendaftar</div>
                                    <div class="text-muted small">{{ $pendaftar ? 'Sudah diisi' : 'Belum diisi' }}</div>
                                </div>
                                @if($pendaftar)
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @else
                                    <i class="bi bi-circle text-secondary"></i>
                                @endif
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="status-dot {{ $isPaid ? 'bg-success' : 'bg-warning' }} me-3"></div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">Pembayaran Formulir</div>
                                    <div class="text-muted small">{{ $isPaid ? 'Selesai' : 'Pending' }}</div>
                                </div>
                                @if($isPaid)
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @else
                                    <i class="bi bi-clock text-warning"></i>
                                @endif
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="status-dot {{ $dataCompletion > 0 ? 'bg-info' : 'bg-secondary' }} me-3"></div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">Kelengkapan Data</div>
                                    <div class="text-muted small">{{ $dataCompletion }}% selesai</div>
                                </div>
                                @if($dataCompletion >= 100)
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @elseif($dataCompletion > 0)
                                    <i class="bi bi-hourglass-split text-info"></i>
                                @else
                                    <i class="bi bi-circle text-secondary"></i>
                                @endif
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="status-dot {{ $registrationStatus == 'verified' ? 'bg-success' : ($registrationStatus == 'pending' ? 'bg-warning' : 'bg-secondary') }} me-3"></div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">Verifikasi Admin</div>
                                    <div class="text-muted small">
                                        @if($registrationStatus == 'verified') Terverifikasi
                                        @elseif($registrationStatus == 'pending') Dalam review
                                        @else Belum submit @endif
                                    </div>
                                </div>
                                @if($registrationStatus == 'verified')
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @elseif($registrationStatus == 'pending')
                                    <i class="bi bi-hourglass-split text-warning"></i>
                                @else
                                    <i class="bi bi-circle text-secondary"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">
                        <i class="bi bi-check-circle-fill me-2"></i>Pembayaran Berhasil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-check-circle-fill display-1 text-success mb-3"></i>
                    <h6 class="fw-bold mb-3">Demo Payment Berhasil!</h6>
                    <p class="text-muted mb-4">
                        Status pembayaran Anda telah diubah menjadi <strong>PAID</strong>.
                        Sekarang Anda dapat mengakses menu Kelengkapan Data.
                    </p>
                    <div class="alert alert-success">
                        <i class="bi bi-unlock me-2"></i>
                        Menu <strong>Kelengkapan Data</strong> sekarang terbuka!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('pendaftar.form') }}" class="btn btn-primary">
                        <i class="bi bi-clipboard-data me-1"></i>Akses Kelengkapan Data
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

        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .btn-outline-primary:hover,
        .btn-outline-success:hover,
        .btn-outline-info:hover,
        .btn-outline-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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

        // Demo Payment Function
        async function demoPayment() {
            try {
                // Show loading
                const btn = document.getElementById('demoPaymentBtn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
                btn.disabled = true;

                const response = await fetch('{{ route("user.demo.payment") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Show success modal
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();

                    // Update dashboard data
                    setTimeout(() => {
                        updateDashboardAfterPayment();
                        btn.style.display = 'none'; // Hide demo button
                    }, 1000);
                } else {
                    alert('Demo payment gagal: ' + result.message);
                    // Reset button
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat melakukan demo payment');

                // Reset button
                const btn = document.getElementById('demoPaymentBtn');
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        // Refresh dashboard data
        async function refreshDashboardData() {
            try {
                const response = await fetch('{{ route("user.dashboard.data") }}');
                const data = await response.json();

                // Update jika ada perubahan
                if (data.isPaid !== currentIsPaid) {
                    updateDashboardAfterPayment();
                    currentIsPaid = data.isPaid;
                }

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

        function updateDashboardAfterPayment() {
            // Update payment status card
            document.getElementById('paymentStatusText').innerHTML = '<span class="text-success">LUNAS</span>';

            document.getElementById('paymentStatusDetail').innerHTML = `
                <i class="bi bi-check-circle me-1 text-success"></i>
                <span class="fw-semibold text-success">Sudah Dibayar</span>
            `;

            document.getElementById('paymentStatusIcon').innerHTML = `
                <i class="bi bi-check-circle-fill text-success fs-3"></i>
            `;

            const paymentProgress = document.getElementById('paymentProgress');
            paymentProgress.style.width = '100%';
            paymentProgress.className = 'progress-bar bg-success';

            // Update welcome section alert
            const welcomeAlert = document.getElementById('welcomeAlert');
            if (welcomeAlert) {
                welcomeAlert.innerHTML = `
                    <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div>
                            <strong>Pembayaran Lunas!</strong> Anda dapat mengakses semua fitur kelengkapan data.
                            <br><small>Dibayar pada: ${new Date().toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            })}</small>
                        </div>
                    </div>
                `;
            }

            // Update data completion button
            document.getElementById('dataCompletionButton').innerHTML = `
                <a href="{{ route('pendaftar.form') }}" class="btn btn-outline-info btn-lg d-flex align-items-center justify-content-start pulse-animation">
                    <i class="bi bi-clipboard-data me-3 fs-4"></i>
                    <div class="text-start">
                        <div class="fw-semibold">Kelengkapan Data</div>
                        <small class="text-muted">Lengkapi data pendaftaran Anda</small>
                    </div>
                    <span class="badge bg-info ms-auto">${currentDataCompletion}%</span>
                </a>
            `;

            // Remove pulse after 3 seconds
            setTimeout(() => {
                const dataBtn = document.querySelector('#dataCompletionButton a');
                if (dataBtn) {
                    dataBtn.classList.remove('pulse-animation');
                }
            }, 3000);
        }

        function updateDataCompletion(newCompletion) {
            // Update counter
            const counterElement = document.getElementById('dataCompletionText');
            animateCounterUpdate(counterElement, newCompletion);

            // Update detail
            document.getElementById('dataCompletionDetail').innerHTML = `${newCompletion}% Lengkap`;

            // Update progress bar
            document.getElementById('dataProgress').style.width = newCompletion + '%';

            // Update chart percentage
            document.getElementById('progressPercentage').textContent = newCompletion + '%';

            // Re-initialize chart with new data
            initProgressChart();
        }

        function updateRegistrationStatus(newStatus) {
            let statusText, statusClass, statusDetail, statusIcon, progressWidth, progressClass;

            switch(newStatus) {
                case 'verified':
                    statusText = 'VERIFIED';
                    statusClass = 'text-success';
                    statusDetail = '<i class="bi bi-shield-check me-1 text-success"></i><span class="fw-semibold text-success">Terverifikasi</span>';
                    statusIcon = '<i class="bi bi-shield-check text-success fs-3"></i>';
                    progressWidth = '100%';
                    progressClass = 'bg-success';
                    break;
                case 'pending':
                    statusText = 'REVIEW';
                    statusClass = 'text-warning';
                    statusDetail = '<i class="bi bi-hourglass-split me-1 text-warning"></i><span class="fw-semibold text-warning">Dalam Review</span>';
                    statusIcon = '<i class="bi bi-hourglass-split text-warning fs-3"></i>';
                    progressWidth = '75%';
                    progressClass = 'bg-warning';
                    break;
                default:
                    statusText = 'DRAFT';
                    statusClass = 'text-secondary';
                    statusDetail = '<i class="bi bi-pencil me-1 text-secondary"></i><span class="fw-semibold text-secondary">Belum Submit</span>';
                    statusIcon = '<i class="bi bi-pencil text-secondary fs-3"></i>';
                    progressWidth = '25%';
                    progressClass = 'bg-secondary';
                    break;
            }

            document.getElementById('registrationStatusText').innerHTML = `<span class="${statusClass}">${statusText}</span>`;
            document.getElementById('registrationStatusDetail').innerHTML = statusDetail;
            document.getElementById('registrationStatusIcon').innerHTML = statusIcon;

            const regProgress = document.getElementById('registrationProgress');
            regProgress.style.width = progressWidth;
            regProgress.className = `progress-bar ${progressClass}`;
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
