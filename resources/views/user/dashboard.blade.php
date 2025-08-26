<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-house-door me-2 text-primary"></i>
                Dashboard Pendaftar
            </h2>
            <div class="d-flex align-items-center text-muted">
                <i class="bi bi-calendar3 me-2"></i>
                <span id="currentDate"></span>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
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
                                    @if(!$isPaid)
                                        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                            <div>
                                                <strong>Pembayaran Diperlukan!</strong> Silakan lakukan pembayaran formulir terlebih dahulu untuk mengakses kelengkapan data.
                                            </div>
                                        </div>
                                    @endif
                                    <div class="d-flex flex-wrap gap-3">
                                        <a href="{{ route('profile.edit') }}" class="btn btn-light btn-lg px-4 shadow-sm">
                                            <i class="bi bi-person-gear me-2"></i>Edit Profile
                                        </a>
                                        <a href="{{ route('payment.index') }}" class="btn btn-outline-light btn-lg px-4">
                                            <i class="bi bi-credit-card me-2"></i>Pembayaran
                                        </a>
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
                                @if($isPaid)
                                    <div class="display-6 fw-bold text-success mb-2">LUNAS</div>
                                    <div class="d-flex align-items-center text-success small">
                                        <i class="bi bi-check-circle me-1"></i>
                                        <span class="fw-semibold">Sudah Dibayar</span>
                                    </div>
                                @else
                                    <div class="display-6 fw-bold text-warning mb-2">PENDING</div>
                                    <div class="d-flex align-items-center text-warning small">
                                        <i class="bi bi-clock me-1"></i>
                                        <span class="fw-semibold">Belum Dibayar</span>
                                    </div>
                                @endif
                            </div>
                            <div class="stat-icon {{ $isPaid ? 'bg-success' : 'bg-warning' }} bg-opacity-10 rounded-3 p-3">
                                <i class="bi {{ $isPaid ? 'bi-check-circle-fill text-success' : 'bi-clock text-warning' }} fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar {{ $isPaid ? 'bg-success' : 'bg-warning' }}" role="progressbar" style="width: {{ $isPaid ? '100' : '50' }}%"></div>
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
                                <div class="display-6 fw-bold text-info mb-2 counter" data-target="{{ $dataCompletion }}">0</div>
                                <div class="d-flex align-items-center text-info small">
                                    <i class="bi bi-graph-up me-1"></i>
                                    <span class="fw-semibold">{{ $dataCompletion }}% Lengkap</span>
                                </div>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-clipboard-data text-info fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $dataCompletion }}%"></div>
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
                                @if($registrationStatus == 'verified')
                                    <div class="display-6 fw-bold text-success mb-2">VERIFIED</div>
                                    <div class="d-flex align-items-center text-success small">
                                        <i class="bi bi-shield-check me-1"></i>
                                        <span class="fw-semibold">Terverifikasi</span>
                                    </div>
                                @elseif($registrationStatus == 'pending')
                                    <div class="display-6 fw-bold text-warning mb-2">REVIEW</div>
                                    <div class="d-flex align-items-center text-warning small">
                                        <i class="bi bi-hourglass-split me-1"></i>
                                        <span class="fw-semibold">Dalam Review</span>
                                    </div>
                                @else
                                    <div class="display-6 fw-bold text-secondary mb-2">DRAFT</div>
                                    <div class="d-flex align-items-center text-secondary small">
                                        <i class="bi bi-pencil me-1"></i>
                                        <span class="fw-semibold">Belum Submit</span>
                                    </div>
                                @endif
                            </div>
                            <div class="stat-icon
                                @if($registrationStatus == 'verified') bg-success
                                @elseif($registrationStatus == 'pending') bg-warning
                                @else bg-secondary @endif
                                bg-opacity-10 rounded-3 p-3">
                                <i class="bi
                                    @if($registrationStatus == 'verified') bi-shield-check text-success
                                    @elseif($registrationStatus == 'pending') bi-hourglass-split text-warning
                                    @else bi-pencil text-secondary @endif
                                    fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar
                                @if($registrationStatus == 'verified') bg-success
                                @elseif($registrationStatus == 'pending') bg-warning
                                @else bg-secondary @endif"
                                role="progressbar"
                                style="width:
                                    @if($registrationStatus == 'verified') 100%
                                    @elseif($registrationStatus == 'pending') 75%
                                    @else 25% @endif"></div>
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

                            <!-- Data Completion -->
                            @if($isPaid)
                                <a href="{{ route('pendaftar.form') }}" class="btn btn-outline-info btn-lg d-flex align-items-center justify-content-start">
                                    <i class="bi bi-clipboard-data me-3 fs-4"></i>
                                    <div class="text-start">
                                        <div class="fw-semibold">Kelengkapan Data</div>
                                        <small class="text-muted">Lengkapi data pendaftaran Anda</small>
                                    </div>
                                    <span class="badge bg-info ms-auto">{{ $dataCompletion }}%</span>
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
                        <div class="d-flex flex-column gap-3">
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
                            <strong>Biaya Formulir:</strong> Rp 150.000
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
    </style>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
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
            const percentage = {{ $dataCompletion }};

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
    </script>
</x-app-layout>
