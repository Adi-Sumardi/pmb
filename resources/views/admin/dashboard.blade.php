<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-speedometer2 me-2 text-primary"></i>
                Admin Dashboard
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
                <div class="card border-0 shadow-lg bg-gradient-primary text-white overflow-hidden position-relative">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="fw-bold mb-2 text-white">Selamat Datang, {{ Auth::user()->name }}!</h3>
                                <p class="mb-0 opacity-75">Kelola sistem PMB dengan mudah dan efisien melalui dashboard yang terintegrasi
                                </p>
                            </div>
                            <div class="col-md-4 text-end d-none d-md-block">
                                 <i class="bi bi-grid-3x3-gap display-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Users -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">Total Users</div>
                                <div class="display-6 fw-bold text-primary mb-2 counter"
                                    data-target="{{ $stats['total_users'] }}">0</div>
                                <div class="d-flex align-items-center text-success small">
                                    <i class="bi bi-arrow-up me-1"></i>
                                    <span class="fw-semibold">+12%</span>
                                    <span class="ms-1 text-muted">bulan lalu</span>
                                </div>
                            </div>
                            <div class="stat-icon bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-people-fill text-primary fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Admins -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">Total Admins</div>
                                <div class="display-6 fw-bold text-success mb-2 counter"
                                    data-target="{{ $stats['total_admins'] }}">0</div>
                                <div class="d-flex align-items-center text-secondary small">
                                    <i class="bi bi-dash me-1"></i>
                                    <span class="fw-semibold">Stabil</span>
                                    <span class="ms-1 text-muted">sistem aman</span>
                                </div>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-shield-check text-success fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pendaftar -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">Total Pendaftar
                                </div>
                                <div class="display-6 fw-bold text-info mb-2 counter"
                                    data-target="{{ $stats['total_pendaftar'] }}">0</div>
                                <div class="d-flex align-items-center text-success small">
                                    <i class="bi bi-arrow-up me-1"></i>
                                    <span class="fw-semibold">+25%</span>
                                    <span class="ms-1 text-muted">minggu ini</span>
                                </div>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-person-plus text-info fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Verification -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">Menunggu Verifikasi
                                </div>
                                <div class="display-6 fw-bold text-warning mb-2 counter"
                                    data-target="{{ $stats['pending_pendaftar'] }}">0</div>
                                @if($stats['pending_pendaftar'] > 0)
                                <a href="{{ route('admin.pendaftar.index') }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-arrow-right me-1"></i>Proses Sekarang
                                </a>
                                @else
                                <div class="text-success small">
                                    <i class="bi bi-check-circle me-1"></i>Semua Terverifikasi
                                </div>
                                @endif
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-clock text-warning fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verified -->
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">Terverifikasi</div>
                                <div class="display-6 fw-bold text-success mb-2 counter"
                                    data-target="{{ $stats['verified_pendaftar'] }}">0</div>
                                @php
                                $percentage = $stats['total_pendaftar'] > 0 ? ($stats['verified_pendaftar'] / $stats['total_pendaftar']) * 100 : 0;
                                @endphp
                                <div class="text-muted small">
                                    <span class="fw-semibold">{{ number_format($percentage, 1) }}%</span> dari total
                                </div>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-check-circle-fill text-success fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Recent Pendaftar -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="600">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="bi bi-person-lines-fill me-2 text-primary"></i>
                                Pendaftar Terbaru
                            </h5>
                            <a href="{{ route('admin.pendaftar.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-arrow-right me-1"></i>Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($recent_pendaftar->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recent_pendaftar as $index => $pendaftar)
                            <div class="list-group-item border-0 py-3 pendaftar-item"
                                data-aos="fade-up" data-aos-delay="{{ 700 + ($index * 100) }}">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        @if($pendaftar->foto_murid_path)
                                        <img src="{{ asset('storage/' . $pendaftar->foto_murid_path) }}"
                                        alt="Foto {{ $pendaftar->nama_murid }}"
                                        class="rounded-circle border" width="48" height="48"
                                        style="object-fit: cover;">
                                        @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 48px; height: 48px;">
                                            {{ substr($pendaftar->nama_murid, 0, 1) }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold">{{ $pendaftar->nama_murid }}</h6>
                                        <div class="text-muted small">
                                            <i class="bi bi-building me-1"></i>{{ $pendaftar->unit }}
                                            <span class="mx-2">•</span>
                                            <i class="bi bi-hash me-1"></i>{{ $pendaftar->no_pendaftaran }}
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge {{ $pendaftar->status === 'pending' ? 'bg-warning' : 'bg-success' }} me-2">
                                            <i class="bi bi-{{ $pendaftar->status === 'pending' ? 'clock' : 'check-circle' }} me-1"></i>
                                            {{ $pendaftar->status === 'pending' ? 'Pending' : 'Verified' }}
                                        </span>
                                        <a href="{{ route('admin.pendaftar.validasi', $pendaftar->id) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                            <h6 class="text-muted">Belum ada pendaftar terbaru</h6>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="row g-4">
                    <!-- Quick Actions -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="800">
                            <div class="card-header bg-white border-bottom-0 py-3">
                                <h6 class="card-title mb-0 fw-bold">
                                    <i class="bi bi-lightning-fill me-2 text-warning"></i>
                                    Aksi Cepat
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.users.create') }}"
                                        class="btn btn-outline-primary d-flex align-items-center justify-content-start action-btn">
                                        <i class="bi bi-person-plus me-3"></i>
                                        <span>Tambah User Baru</span>
                                    </a>
                                    <a href="{{ route('admin.pendaftar.index') }}"
                                        class="btn btn-outline-success d-flex align-items-center justify-content-start action-btn">
                                        <i class="bi bi-check-circle me-3"></i>
                                        <span>Verifikasi Pendaftar</span>
                                    </a>
                                    <a href="{{ route('admin.users.index') }}"
                                        class="btn btn-outline-info d-flex align-items-center justify-content-start action-btn">
                                        <i class="bi bi-people me-3"></i>
                                        <span>Kelola Users</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="900">
                            <div class="card-header bg-white border-bottom-0 py-3">
                                <h6 class="card-title mb-0 fw-bold">
                                    <i class="bi bi-gear-fill me-2 text-secondary"></i>
                                    Status Sistem
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted small">Database</span>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>Online
                                    </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted small">Server</span>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>Aktif
                                    </span>
                                </div>
                                <hr class="my-3">
                                <div class="text-muted small">
                                    <i class="bi bi-clock me-1"></i>
                                    Last Update: <span id="lastUpdate"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Users -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="1000">
                            <div class="card-header bg-white border-bottom-0 py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-people me-2 text-info"></i>
                                        Users Terbaru
                                    </h6>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info btn-sm">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                @if($recent_users->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recent_users as $user)
                                    <div class="list-group-item border-0 py-2">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center fw-bold"
                                                    style="width: 36px; height: 36px; font-size: 14px;">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 small fw-semibold">{{ Str::limit($user->name, 15) }}</h6>
                                                <div class="text-muted" style="font-size: 11px;">{{ Str::limit($user->email, 20) }}</div>
                                            </div>
                                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }} small">
                                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-3">
                                    <i class="bi bi-inbox display-6 text-muted mb-2"></i>
                                    <p class="text-muted small">Belum ada user terbaru</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card {
            transition: all 0.3s ease;
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

        .action-btn {
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateX(5px);
        }

        .pendaftar-item {
            transition: all 0.3s ease;
        }

        .pendaftar-item:hover {
            background-color: #f8f9fa;
        }

        .tracking-wide {
            letter-spacing: 0.05em;
        }

        .counter {
            font-family: 'Inter', sans-serif;
        }
    </style>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });

            // Update current date
            function updateDateTime() {
                const now = new Date();
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);

                const timeOptions = {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };
                document.getElementById('lastUpdate').textContent = now.toLocaleTimeString('id-ID', timeOptions);
            }

            updateDateTime();
            setInterval(updateDateTime, 1000);

            // Counter animation
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

            // Trigger counter animation when in viewport
            const counters = document.querySelectorAll('.counter');
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            });

            counters.forEach(counter => {
                observer.observe(counter);
            });

            // Add loading state to action buttons
            document.querySelectorAll('.action-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    this.classList.add('disabled');
                    const originalText = this.innerHTML;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';

                    setTimeout(() => {
                        this.classList.remove('disabled');
                        this.innerHTML = originalText;
                    }, 2000);
                });
            });
        });
    </script>
</x-app-layout>
