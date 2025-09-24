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
        <!-- Welcome Section with Quick Stats -->
        <div class="row mb-4">
            <div class="col-md-12 mb-4 mb-md-0">
                <div class="card border-0 shadow-lg bg-gradient-primary text-white overflow-hidden position-relative rounded-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <h3 class="fw-bold mb-2 text-white">Selamat Datang, {{ Auth::user()->name }}!</h3>
                                <p class="mb-3 opacity-75">Kelola sistem PPDB dengan mudah dan efisien melalui dashboard yang terintegrasi</p>
                                <br>
                            </div>
                        </div>
                        <!-- Abstract Wave Background -->
                        <div class="wave-bg"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards Row -->
        <div class="row g-4 mb-4">
            <!-- Total Users -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card rounded-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">Total Users</div>
                                <div class="display-6 fw-bold text-primary mb-2 counter"
                                    data-target="{{ $stats['total_users'] }}">0</div>
                                <div class="d-flex align-items-center text-success small">
                                    <i class="bi bi-arrow-up me-1"></i>
                                    <span class="fw-semibold">+12%</span>
                                    <span class="ms-1 text-muted">bulan ini</span>
                                </div>
                            </div>
                            <div class="stat-icon bg-primary bg-opacity-10 rounded-4 p-3">
                                <i class="bi bi-people-fill text-primary fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pendaftar -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card rounded-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">Total Pendaftar</div>
                                <div class="display-6 fw-bold text-info mb-2 counter"
                                    data-target="{{ $stats['total_pendaftar'] }}">0</div>
                                <div class="d-flex align-items-center text-success small">
                                    <i class="bi bi-arrow-up me-1"></i>
                                    <span class="fw-semibold">+25%</span>
                                    <span class="ms-1 text-muted">minggu ini</span>
                                </div>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 rounded-4 p-3">
                                <i class="bi bi-person-plus text-info fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Students -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card rounded-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">Siswa Aktif</div>
                                <div class="display-6 fw-bold text-success mb-2 counter"
                                    data-target="{{ $studentStats['active_students'] }}">0</div>
                                @php
                                $activePercentage = $stats['total_pendaftar'] > 0 ? ($studentStats['active_students'] / $stats['total_pendaftar']) * 100 : 0;
                                @endphp
                                <div class="text-muted small">
                                    <span class="fw-semibold">{{ number_format($activePercentage, 1) }}%</span> dari total
                                </div>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 rounded-4 p-3">
                                <i class="bi bi-person-check-fill text-success fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $activePercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Revenue -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card rounded-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">Pemasukan Formulir</div>
                                <div class="display-6 fw-bold text-warning mb-2">
                                    Rp {{ number_format($revenueStreams['registration_revenue']['amount'], 0, ',', '.') }}
                                </div>
                                <div class="text-muted small">
                                    {{ $revenueStreams['registration_revenue']['description'] }}
                                </div>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10 rounded-4 p-3">
                                <i class="bi bi-cash-coin text-warning fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 90%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Revenue Row -->
        <div class="row g-4 mb-4">
            <!-- Bill Revenue -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card rounded-4" data-aos="fade-up" data-aos-delay="450">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">Pemasukan SPP</div>
                                <div class="display-6 fw-bold text-danger mb-2">
                                    Rp {{ number_format($revenueStreams['bill_revenue']['amount'], 0, ',', '.') }}
                                </div>
                                <div class="text-muted small">
                                    {{ $revenueStreams['bill_revenue']['description'] }}
                                </div>
                            </div>
                            <div class="stat-icon bg-danger bg-opacity-10 rounded-4 p-3">
                                <i class="bi bi-receipt text-danger fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Billing Statistics -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card rounded-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">Tagihan Lunas</div>
                                <div class="display-6 fw-bold text-success mb-2">
                                    {{ $billingStats['paid_bills'] }}
                                </div>
                                <div class="text-muted small">
                                    dari {{ $billingStats['total_bills'] }} total tagihan
                                </div>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 rounded-4 p-3">
                                <i class="bi bi-check-circle text-success fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            @php
                            $paidPercentage = $billingStats['total_bills'] > 0 ? ($billingStats['paid_bills'] / $billingStats['total_bills']) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $paidPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Uang Pangkal Revenue -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card rounded-4" data-aos="fade-up" data-aos-delay="550">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">Pemasukan Uang Pangkal</div>
                                <div class="display-6 fw-bold text-purple mb-2">
                                    Rp {{ number_format($revenueStreams['uang_pangkal_revenue']['amount'], 0, ',', '.') }}
                                </div>
                                <div class="text-muted small">
                                    {{ $revenueStreams['uang_pangkal_revenue']['description'] }}
                                </div>
                            </div>
                            <div class="stat-icon bg-purple bg-opacity-10 rounded-4 p-3">
                                <i class="bi bi-building text-purple fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-purple" role="progressbar" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Payments -->
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card rounded-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">Pembayaran Pending</div>
                                <div class="display-6 fw-bold text-secondary mb-2">
                                    {{ $billingStats['pending_payments'] }}
                                </div>
                                <div class="text-muted small">
                                    Menunggu konfirmasi
                                </div>
                            </div>
                            <div class="stat-icon bg-secondary bg-opacity-10 rounded-4 p-3">
                                <i class="bi bi-hourglass-split text-secondary fs-3"></i>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-secondary" role="progressbar" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Status Overview Cards -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-person-lines-fill me-2 text-primary"></i>Status Siswa Overview
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Active Students -->
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <div class="border rounded-3 p-3 text-center h-100 status-card active">
                                    <div class="icon-circle bg-success bg-opacity-10 text-success mx-auto mb-2">
                                        <i class="bi bi-person-check"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ $studentStats['active_students'] }}</h6>
                                    <small class="text-muted">Siswa Aktif</small>
                                </div>
                            </div>
                            <!-- Inactive Students -->
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <div class="border rounded-3 p-3 text-center h-100 status-card inactive">
                                    <div class="icon-circle bg-secondary bg-opacity-10 text-secondary mx-auto mb-2">
                                        <i class="bi bi-person-dash"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ $studentStats['inactive_students'] }}</h6>
                                    <small class="text-muted">Belum Aktif</small>
                                </div>
                            </div>
                            <!-- Graduated Students -->
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <div class="border rounded-3 p-3 text-center h-100 status-card graduated">
                                    <div class="icon-circle bg-primary bg-opacity-10 text-primary mx-auto mb-2">
                                        <i class="bi bi-mortarboard"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ $studentStats['graduated_students'] }}</h6>
                                    <small class="text-muted">Lulus</small>
                                </div>
                            </div>
                            <!-- Dropped Out -->
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <div class="border rounded-3 p-3 text-center h-100 status-card dropped">
                                    <div class="icon-circle bg-danger bg-opacity-10 text-danger mx-auto mb-2">
                                        <i class="bi bi-person-x"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ $studentStats['dropped_out_students'] }}</h6>
                                    <small class="text-muted">Keluar</small>
                                </div>
                            </div>
                            <!-- Transferred -->
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <div class="border rounded-3 p-3 text-center h-100 status-card transferred">
                                    <div class="icon-circle bg-info bg-opacity-10 text-info mx-auto mb-2">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ $studentStats['transferred_students'] }}</h6>
                                    <small class="text-muted">Pindah</small>
                                </div>
                            </div>
                            <!-- Quick Action -->
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <div class="border rounded-3 p-3 text-center h-100 d-flex flex-column justify-content-center">
                                    <a href="{{ route('admin.progres-pendaftaran.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-gear me-1"></i>Kelola Status
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Recent Pendaftar -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100 rounded-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="bi bi-person-lines-fill me-2 text-primary"></i>
                                Pendaftar Terbaru
                            </h5>
                            <a href="{{ route('admin.pendaftar.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
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
                                        <span class="badge {{ $pendaftar->status === 'pending' ? 'bg-warning' : 'bg-success' }} me-2 rounded-pill">
                                            <i class="bi bi-{{ $pendaftar->status === 'pending' ? 'clock' : 'check-circle' }} me-1"></i>
                                            {{ $pendaftar->status === 'pending' ? 'Pending' : 'Verified' }}
                                        </span>
                                        <a href="{{ route('admin.pendaftar.validasi', $pendaftar->id) }}"
                                            class="btn btn-sm btn-outline-primary rounded-circle">
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
                        <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="800">
                            <div class="card-header bg-white border-bottom-0 py-3">
                                <h6 class="card-title mb-0 fw-bold">
                                    <i class="bi bi-lightning-fill me-2 text-warning"></i>
                                    Aksi Cepat
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.users.create') }}"
                                        class="btn btn-outline-primary d-flex align-items-center justify-content-start action-btn rounded-pill p-2 ps-3">
                                        <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                                            <i class="bi bi-person-plus"></i>
                                        </div>
                                        <span>Tambah User Baru</span>
                                    </a>
                                    <a href="{{ route('admin.pendaftar.index') }}"
                                        class="btn btn-outline-success d-flex align-items-center justify-content-start action-btn rounded-pill p-2 ps-3">
                                        <div class="icon-circle bg-success bg-opacity-10 text-success me-3">
                                            <i class="bi bi-check-circle"></i>
                                        </div>
                                        <span>Verifikasi Pendaftar</span>
                                    </a>
                                    <a href="{{ route('admin.users.index') }}"
                                        class="btn btn-outline-info d-flex align-items-center justify-content-start action-btn rounded-pill p-2 ps-3">
                                        <div class="icon-circle bg-info bg-opacity-10 text-info me-3">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <span>Kelola Users</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Status Doughnut Chart -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="900">
                            <div class="card-header bg-white border-bottom-0 py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-pie-chart me-2 text-primary"></i>
                                        Status Verifikasi
                                    </h6>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown">
                                            Filter
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item active" href="#">Semua Unit</a></li>
                                            <li><a class="dropdown-item" href="#">TK</a></li>
                                            <li><a class="dropdown-item" href="#">SD</a></li>
                                            <li><a class="dropdown-item" href="#">SMP</a></li>
                                            <li><a class="dropdown-item" href="#">SMA</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <canvas id="verificationStatusChart" height="180"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="1000">
                            <div class="card-header bg-white border-bottom-0 py-3">
                                <h6 class="card-title mb-0 fw-bold">
                                    <i class="bi bi-gear-fill me-2 text-secondary"></i>
                                    Status Sistem
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted small">Database</span>
                                    <span class="badge bg-success rounded-pill px-3">
                                        <i class="bi bi-check-circle-fill me-1"></i>Online
                                    </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted small">Server</span>
                                    <span class="badge bg-success rounded-pill px-3">
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
                </div>
            </div>
        </div>

        <br>

        <!-- Unit Analysis Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-building me-2 text-primary"></i>Analisis Unit
                            </h5>
                            <div>
                                <select class="form-select form-select-sm border-0 bg-light rounded-pill px-3" id="unitTimeFilter">
                                    <option value="week">Minggu Ini</option>
                                    <option value="month" selected>Bulan Ini</option>
                                    <option value="year">Tahun Ini</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover unit-analysis-table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 py-3 ps-4">Unit</th>
                                        <th class="border-0 py-3 text-center">Total Pendaftar</th>
                                        <th class="border-0 py-3 text-center">Terverifikasi</th>
                                        <th class="border-0 py-3 text-center">Sudah Bayar</th>
                                        <th class="border-0 py-3 text-center">Siswa Aktif</th>
                                        <th class="border-0 py-3 text-center">Belum Aktif</th>
                                        <th class="border-0 py-3 text-center">Progress</th>
                                        <th class="border-0 py-3 text-end pe-4">Tren</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($unit_stats as $index => $stat)
                                    @php
                                        $colors = ['info', 'primary', 'success', 'warning', 'danger', 'secondary', 'dark'];
                                        $color = $colors[$index % count($colors)];
                                        $verificationRate = $stat->total > 0 ? ($stat->verified / $stat->total) * 100 : 0;
                                        $activeStudentRate = $stat->total > 0 ? (($stat->active_students ?? 0) / $stat->total) * 100 : 0;
                                        // Random trend value for now, could be replaced with actual trend data
                                        $trend = rand(-10, 25);
                                        $trendDirection = $trend >= 0 ? 'up' : 'down';
                                        $trendColor = $trend >= 0 ? 'success' : 'danger';
                                    @endphp
                                    <tr class="unit-row">
                                        <td class="py-3 ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-circle bg-{{ $color }} bg-opacity-10 text-{{ $color }} me-3">
                                                    <i class="bi bi-building"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-0">{{ $stat->unit }}</h6>
                                                    <span class="text-muted small">Unit Pendidikan</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-center fw-bold">{{ $stat->total }}</td>
                                        <td class="py-3 text-center text-success">{{ $stat->verified }}</td>
                                        <td class="py-3 text-center text-primary">{{ $stat->paid }}</td>
                                        <td class="py-3 text-center text-success">
                                            <span class="fw-semibold">{{ $stat->active_students ?? 0 }}</span>
                                            <small class="text-muted d-block">siswa</small>
                                        </td>
                                        <td class="py-3 text-center text-secondary">
                                            <span class="fw-semibold">{{ $stat->inactive_students ?? 0 }}</span>
                                            <small class="text-muted d-block">siswa</small>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $activeStudentRate }}%"></div>
                                                </div>
                                                <span class="ms-2 fw-semibold small">{{ number_format($activeStudentRate, 0) }}%</span>
                                            </div>
                                            <small class="text-muted">Tingkat Aktivasi</small>
                                        </td>
                                        <td class="py-3 text-end pe-4">
                                            <span class="badge bg-{{ $trendColor }} bg-opacity-10 text-{{ $trendColor }} rounded-pill">
                                                <i class="bi bi-graph-{{ $trendDirection }} me-1"></i>{{ $trend > 0 ? '+' : '' }}{{ $trend }}%
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="bi bi-exclamation-circle me-2"></i>Belum ada data pendaftaran
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & Statistics Row -->
        <div class="row g-4 mb-4">
            <!-- Registration Trend Chart -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100 rounded-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-graph-up me-2 text-primary"></i>Tren Pendaftaran
                            </h5>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary active period-selector" data-period="week">Minggu</button>
                                <button type="button" class="btn btn-sm btn-outline-primary period-selector" data-period="month">Bulan</button>
                                <button type="button" class="btn btn-sm btn-outline-primary period-selector" data-period="year">Tahun</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="registrationChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Distribution by Unit Pie Chart -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 rounded-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">
                                <i class="bi bi-pie-chart-fill me-2 text-primary"></i>Distribusi Unit
                            </h5>
                            <div>
                                <button class="btn btn-sm btn-outline-secondary rounded-pill refresh-chart">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <canvas id="unitDistributionChart" height="260"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --danger: #e63946;
            --light: #f8f9fa;
            --dark: #212529;
        }

        body {
            background-color: #f9fafb;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Enhanced gradient backgrounds */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        }

        /* Card styling */
        .card {
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
        }

        .rounded-4 {
            border-radius: 16px !important;
        }

        .stat-card {
            transition: all 0.3s ease;
            border-radius: 16px;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
        }

        .stat-icon {
            transition: all 0.3s ease;
            border-radius: 12px;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(-10deg);
        }

        /* Action buttons */
        .action-btn {
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .action-btn:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .action-btn:hover .icon-circle {
            transform: scale(1.1);
        }

        /* Table rows */
        .unit-row {
            transition: all 0.2s ease;
        }

        .unit-row:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        /* Wave background effect */
        .wave-bg {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.2' d='M0,160L48,170.7C96,181,192,203,288,202.7C384,203,480,181,576,165.3C672,149,768,139,864,149.3C960,160,1056,192,1152,197.3C1248,203,1344,181,1392,170.7L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") bottom center no-repeat;
            background-size: cover;
        }

        /* Decorative element */
        .dashboard-decorative-element {
            position: absolute;
            top: -20px;
            right: -20px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Progress bars */
        .progress {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        /* Animation counters */
        .counter {
            font-family: 'Inter', sans-serif;
            font-weight: 700;
        }

        /* Unit analysis table */
        .unit-analysis-table th {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #718096;
            font-weight: 600;
        }

        /* Badge styling */
        .badge {
            font-weight: 500;
            padding: 0.4em 0.8em;
        }

        .badge.rounded-pill {
            padding-left: 0.8em;
            padding-right: 0.8em;
        }

        /* Student Status Cards */
        .status-card {
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0 !important;
            cursor: pointer;
        }

        .status-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .status-card.active {
            border-color: #10b981 !important;
            background-color: rgba(16, 185, 129, 0.05);
        }

        .status-card.inactive {
            border-color: #6b7280 !important;
            background-color: rgba(107, 114, 128, 0.05);
        }

        .status-card.graduated {
            border-color: #3b82f6 !important;
            background-color: rgba(59, 130, 246, 0.05);
        }

        .status-card.dropped {
            border-color: #ef4444 !important;
            background-color: rgba(239, 68, 68, 0.05);
        }

        .status-card.transferred {
            border-color: #06b6d4 !important;
            background-color: rgba(6, 182, 212, 0.05);
        }

        .status-card.graduated {
            border-color: #3b82f6 !important;
            background-color: rgba(59, 130, 246, 0.05);
        }

        .status-card.dropped {
            border-color: #ef4444 !important;
            background-color: rgba(239, 68, 68, 0.05);
        }

        .status-card.transferred {
            border-color: #06b6d4 !important;
            background-color: rgba(6, 182, 212, 0.05);
        }

        .status-card .icon-circle {
            width: 40px;
            height: 40px;
        }
    </style>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize AOS animations
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

            // Counter animation with intersection observer
            const animateCounter = (element) => {
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
            };

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

            // Define dynamic data variables FIRST (move these up from later in the script)
            // Get weekly trend data from controller
            const weeklyLabels = {!! json_encode($weekly_trends->pluck('date')) !!};
            const weeklyData = {!! json_encode($weekly_trends->pluck('total')) !!};

            // Get verified weekly data if available, otherwise calculate an approximation
            @if(isset($weekly_verified) && count($weekly_verified) > 0)
                const weeklyVerifiedData = {!! json_encode($weekly_verified->pluck('total')) !!};
            @else
                const weeklyVerifiedData = weeklyData.map(val => Math.round(val * 0.8)); // 80% approximation
            @endif

            // Get monthly trend data
            const monthlyLabels = {!! json_encode($monthly_trends->pluck('date')) !!};
            const monthlyData = {!! json_encode($monthly_trends->pluck('total')) !!};

            @if(isset($monthly_verified) && count($monthly_verified) > 0)
                const monthlyVerifiedData = {!! json_encode($monthly_verified->pluck('total')) !!};
            @else
                const monthlyVerifiedData = monthlyData.map(val => Math.round(val * 0.8));
            @endif

            // Get yearly trend data (by month)
            const yearlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const yearlyData = Array(12).fill(0);
            const yearlyVerifiedData = Array(12).fill(0);

            @foreach($yearly_trends as $trend)
                yearlyData[{{ $trend->month - 1 }}] = {{ $trend->total }};
                @if(isset($trend->verified))
                    yearlyVerifiedData[{{ $trend->month - 1 }}] = {{ $trend->verified }};
                @else
                    yearlyVerifiedData[{{ $trend->month - 1 }}] = Math.round({{ $trend->total }} * 0.8);
                @endif
            @endforeach

            // Initialize Registration Trend Chart with DYNAMIC data
            const registrationCtx = document.getElementById('registrationChart').getContext('2d');
            const registrationChart = new Chart(registrationCtx, {
                type: 'line',
                data: {
                    labels: weeklyLabels, // Use dynamic data instead of hardcoded days
                    datasets: [
                        {
                            label: 'Pendaftar',
                            data: weeklyData, // Use dynamic data
                            borderColor: '#4361ee',
                            backgroundColor: 'rgba(67, 97, 238, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#4361ee'
                        },
                        {
                            label: 'Terverifikasi',
                            data: weeklyVerifiedData, // Use dynamic verified data
                            borderColor: '#4cc9f0',
                            backgroundColor: 'rgba(76, 201, 240, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#4cc9f0'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 10,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: '#fff',
                            titleColor: '#212529',
                            bodyColor: '#212529',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw + ' siswa';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false,
                                color: '#e2e8f0'
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuad'
                    }
                }
            });

            // Initialize Unit Distribution Pie Chart with dynamic data
            const unitDistCtx = document.getElementById('unitDistributionChart').getContext('2d');
            const unitDistributionLabels = {!! json_encode($unit_distribution->pluck('unit')) !!};
            const unitDistributionData = {!! json_encode($unit_distribution->pluck('total')) !!};

            const unitDistChart = new Chart(unitDistCtx, {
                type: 'doughnut',
                data: {
                    labels: unitDistributionLabels,
                    datasets: [{
                        data: unitDistributionData,
                        backgroundColor: [
                            'rgba(76, 201, 240, 0.8)',
                            'rgba(67, 97, 238, 0.8)',
                            'rgba(247, 37, 133, 0.8)',
                            'rgba(58, 12, 163, 0.8)',
                            'rgba(0, 180, 216, 0.8)',
                            'rgba(214, 40, 40, 0.8)',
                            'rgba(72, 149, 239, 0.8)',
                        ],
                        borderColor: [
                            'rgba(76, 201, 240, 1)',
                            'rgba(67, 97, 238, 1)',
                            'rgba(247, 37, 133, 1)',
                            'rgba(58, 12, 163, 1)',
                            'rgba(0, 180, 216, 1)',
                            'rgba(214, 40, 40, 1)',
                            'rgba(72, 149, 239, 1)',
                        ],
                        borderWidth: 1,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#212529',
                            bodyColor: '#212529',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    }
                }
            });

            // Initialize Verification Status Chart with dynamic data
            const verificationCtx = document.getElementById('verificationStatusChart').getContext('2d');
            const verificationChart = new Chart(verificationCtx, {
                type: 'pie',
                data: {
                    labels: ['Terverifikasi', 'Menunggu Verifikasi'],
                    datasets: [{
                        data: [{{ $stats['verified_pendaftar'] }}, {{ $stats['pending_pendaftar'] }}],
                        backgroundColor: [
                            'rgba(76, 201, 240, 0.8)',
                            'rgba(247, 37, 133, 0.8)'
                        ],
                        borderColor: [
                            'rgba(76, 201, 240, 1)',
                            'rgba(247, 37, 133, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#212529',
                            bodyColor: '#212529',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });

            // Initialize Summary Modal Charts
            const summaryWeeklyCtx = document.getElementById('summaryWeeklyChart').getContext('2d');
            const summaryWeeklyChart = new Chart(summaryWeeklyCtx, {
                type: 'bar',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    datasets: [{
                        label: 'Pendaftar',
                        data: [12, 19, 15, 25, 22, 30, 35],
                        backgroundColor: 'rgba(67, 97, 238, 0.7)',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    }
                }
            });

            const summaryDistCtx = document.getElementById('summaryDistributionChart').getContext('2d');
            const summaryDistChart = new Chart(summaryDistCtx, {
                type: 'doughnut',
                data: {
                    labels: ['TK', 'SD', 'SMP', 'SMA'],
                    datasets: [{
                        data: [28, 46, 32, 18],
                        backgroundColor: [
                            'rgba(76, 201, 240, 0.8)',
                            'rgba(67, 97, 238, 0.8)',
                            'rgba(247, 37, 133, 0.8)',
                            'rgba(58, 12, 163, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });

            // Refresh chart animation on button click
            document.querySelector('.refresh-chart').addEventListener('click', function() {
                this.classList.add('spin');
                setTimeout(() => {
                    this.classList.remove('spin');
                    unitDistChart.reset();
                    unitDistChart.update();
                }, 800);
            });

            // Add loading state to action buttons
            document.querySelectorAll('.action-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const iconEl = this.querySelector('.icon-circle i');
                    const originalIcon = iconEl.className;
                    iconEl.className = 'bi bi-arrow-clockwise spin';

                    setTimeout(() => {
                        iconEl.className = originalIcon;
                    }, 1000);
                });
            });

            // Handle period selectors for the registration chart
            document.querySelectorAll('.period-selector').forEach(button => {
                button.addEventListener('click', function() {
                    document.querySelectorAll('.period-selector').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    this.classList.add('active');

                    // Update chart based on selected period
                    const period = this.dataset.period;

                    if (period === 'week') {
                        registrationChart.data.labels = weeklyLabels;
                        registrationChart.data.datasets[0].data = weeklyData;
                        registrationChart.data.datasets[1].data = weeklyVerifiedData;
                    } else if (period === 'month') {
                        registrationChart.data.labels = monthlyLabels;
                        registrationChart.data.datasets[0].data = monthlyData;
                        registrationChart.data.datasets[1].data = monthlyVerifiedData;
                    } else if (period === 'year') {
                        registrationChart.data.labels = yearlyLabels;
                        registrationChart.data.datasets[0].data = yearlyData;
                        registrationChart.data.datasets[1].data = yearlyVerifiedData;
                    }

                    registrationChart.update();
                });
            });
        });

        // Add spin animation for refresh button
        document.styleSheets[0].insertRule(`
            .spin {
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `, document.styleSheets[0].cssRules.length);
    </script>
</x-app-layout>
