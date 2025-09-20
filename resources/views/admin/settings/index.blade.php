<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-gear-fill me-2 text-primary"></i>
                    Pengaturan Sistem
                </h2>
                <p class="text-muted small mb-0">Kelola pengaturan pembayaran dan konfigurasi sistem PPDB</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary" onclick="refreshData()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Settings Cards Grid -->
        <div class="row g-4">

            <!-- Discount Management -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 settings-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                <i class="bi bi-percent text-warning fs-4"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold">Manajemen Diskon</h5>
                                <p class="text-muted small mb-0">Atur diskon pembayaran</p>
                            </div>
                        </div>
                        <p class="text-muted mb-4">Kelola berbagai jenis diskon untuk pendaftaran siswa baru, termasuk diskon early bird, siswa berprestasi, dan lainnya.</p>
                        <a href="{{ route('admin.settings.discounts.index') }}" class="btn btn-warning btn-sm w-100">
                            <i class="bi bi-arrow-right me-1"></i>Kelola Diskon
                        </a>
                    </div>
                </div>
            </div>

            <!-- SPP Management -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 settings-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                                <i class="bi bi-calendar-month text-info fs-4"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold">Pengaturan SPP</h5>
                                <p class="text-muted small mb-0">Kelola biaya SPP bulanan</p>
                            </div>
                        </div>
                        <p class="text-muted mb-4">Atur biaya SPP untuk setiap jenjang dari Playgroup sampai SMA berdasarkan status asal sekolah siswa.</p>
                        <a href="{{ route('admin.settings.spp.index') }}" class="btn btn-info btn-sm w-100">
                            <i class="bi bi-arrow-right me-1"></i>Kelola SPP
                        </a>
                    </div>
                </div>
            </div>

            <!-- Uang Pangkal Management -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 settings-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                <i class="bi bi-cash-coin text-success fs-4"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold">Uang Pangkal</h5>
                                <p class="text-muted small mb-0">Kelola biaya uang pangkal</p>
                            </div>
                        </div>
                        <p class="text-muted mb-4">Atur biaya uang pangkal untuk setiap jenjang pendidikan berdasarkan status asal sekolah dan tahun ajaran.</p>
                        <a href="{{ route('admin.settings.uang-pangkal.index') }}" class="btn btn-success btn-sm w-100">
                            <i class="bi bi-arrow-right me-1"></i>Kelola Uang Pangkal
                        </a>
                    </div>
                </div>
            </div>

            <!-- Multi Payment Management -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 settings-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-purple bg-opacity-10 p-3 me-3">
                                <i class="bi bi-credit-card-2-front text-purple fs-4"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold">Multi Payment</h5>
                                <p class="text-muted small mb-0">Kelola pembayaran tambahan</p>
                            </div>
                        </div>
                        <p class="text-muted mb-4">Atur berbagai jenis pembayaran tambahan seperti uang buku, seragam, kegiatan ekstrakurikuler, dan lainnya.</p>
                        <a href="{{ route('admin.settings.multi-payments.index') }}" class="btn btn-purple btn-sm w-100">
                            <i class="bi bi-arrow-right me-1"></i>Kelola Multi Payment
                        </a>
                    </div>
                </div>
            </div>

            <!-- Installment Settings -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 settings-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="bi bi-calendar-range text-primary fs-4"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold">Pengaturan Cicilan</h5>
                                <p class="text-muted small mb-0">Kelola pembayaran cicilan</p>
                            </div>
                        </div>
                        <p class="text-muted mb-4">Atur sistem pembayaran cicilan untuk uang pangkal dengan berbagai pilihan tenor dan jumlah cicilan.</p>
                        <a href="{{ route('admin.settings.installments.index') }}" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-arrow-right me-1"></i>Kelola Cicilan
                        </a>
                    </div>
                </div>
            </div>

            <!-- SPP Bulk Payment -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 settings-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-secondary bg-opacity-10 p-3 me-3">
                                <i class="bi bi-stack text-secondary fs-4"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold">SPP Bulk Payment</h5>
                                <p class="text-muted small mb-0">Pembayaran SPP sekaligus</p>
                            </div>
                        </div>
                        <p class="text-muted mb-4">Atur sistem pembayaran SPP sekaligus untuk beberapa bulan (3, 6, atau 12 bulan) dengan diskon khusus.</p>
                        <a href="{{ route('admin.settings.spp-bulk.index') }}" class="btn btn-secondary btn-sm w-100">
                            <i class="bi bi-arrow-right me-1"></i>Kelola SPP Bulk
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .settings-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .settings-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        .btn-purple {
            background-color: #6f42c1;
            border-color: #6f42c1;
            color: white;
        }

        .btn-purple:hover {
            background-color: #5a359a;
            border-color: #5a359a;
            color: white;
        }

        .text-purple {
            color: #6f42c1 !important;
        }

        .bg-purple {
            background-color: #6f42c1 !important;
        }

        .card-header {
            border-bottom: 1px solid #e5e7eb !important;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .fs-2 {
            font-size: 2rem !important;
        }

        .settings-card .card-body {
            position: relative;
            overflow: hidden;
        }

        .settings-card .card-body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.5s;
            opacity: 0;
        }

        .settings-card:hover .card-body::before {
            animation: shimmer 1s ease-in-out;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) rotate(45deg); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateX(100%) rotate(45deg); opacity: 0; }
        }

        .rounded-circle {
            transition: all 0.3s ease;
        }

        .settings-card:hover .rounded-circle {
            transform: scale(1.1);
        }
    </style>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Main JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true
        });

        // Refresh data function
        window.refreshData = function() {
            location.reload();
        }
    });
    </script>
</x-app-layout>
