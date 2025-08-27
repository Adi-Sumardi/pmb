<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-people-fill me-2 text-primary"></i>
                    Data Pendaftar PPDB
                </h2>
                <p class="text-muted small mb-0">Kelola data pendaftaran murid baru tahun ajaran 2026/2027</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary" onclick="refreshData()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                </button>
                <button class="btn btn-success" id="exportBtn">
                    <i class="bi bi-file-excel me-1"></i>Export Excel
                </button>
                <button class="btn btn-info" id="printBtn">
                    <i class="bi bi-printer me-1"></i>Print
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Alert Messages -->
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="alertSuccess">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Sukses!</strong> Data Calon Murid Sudah Diverifikasi
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session()->has('deleted'))
            <div class="alert alert-info alert-dismissible fade show" role="alert" id="alertDeleted">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Sukses!</strong> {{ session('deleted') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertError">
                <i class="bi bi-exclamation-circle me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                    <i class="bi bi-people text-info fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small">Total Pendaftar</div>
                                <div class="fs-4 fw-bold text-info counter" id="totalCount" data-target="{{ $dt_pendaftars->count() }}">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                    <i class="bi bi-clock text-warning fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small">Pending</div>
                                <div class="fs-4 fw-bold text-warning counter" id="pendingCount" data-target="{{ $dt_pendaftars->where('status', 'pending')->count() }}">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                    <i class="bi bi-check-circle text-success fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small">Diverifikasi</div>
                                <div class="fs-4 fw-bold text-success counter" id="verifiedCount" data-target="{{ $dt_pendaftars->where('status', 'diverifikasi')->count() }}">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                    <i class="bi bi-percentage text-primary fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small">Tingkat Verifikasi</div>
                                <div class="fs-4 fw-bold text-primary counter" data-target="{{ $dt_pendaftars->count() > 0 ? round(($dt_pendaftars->where('status', 'diverifikasi')->count() / $dt_pendaftars->count()) * 100) : 0 }}">0</div>
                                <div class="text-muted" style="font-size: 0.75rem;">%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Search Section -->
        <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="500">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-search me-2 text-primary"></i>
                    Pencarian Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-primary text-white border-primary">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text"
                                   class="form-control border-primary"
                                   id="quickSearch"
                                   placeholder="Ketik nama calon murid atau nomor pendaftaran..."
                                   autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="clearQuickSearch">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="form-text mt-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Hasil pencarian akan muncul secara real-time saat Anda mengetik
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted fw-semibold">Hasil ditemukan:</small>
                            <span class="badge bg-primary fs-6" id="searchResultCount">{{ $dt_pendaftars->count() }}</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-gradient bg-primary" role="progressbar" style="width: 100%" id="searchProgress"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Data Table -->
        <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="600">
            <div class="card-header bg-white border-bottom py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-table me-2 text-primary"></i>
                            Data Pendaftar
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end gap-2">
                            <!-- Filter Controls -->
                            <div class="d-flex gap-2">
                                <select class="form-select form-select-sm" id="statusFilter" style="width: auto;">
                                    <option value="">Semua Status</option>
                                    <option value="pending">🟡 Pending</option>
                                    <option value="diverifikasi">🟢 Diverifikasi</option>
                                </select>

                                <select class="form-select form-select-sm" id="unitFilter" style="width: auto;">
                                    <option value="">Semua Unit</option>
                                    @foreach($dt_pendaftars->pluck('unit')->unique()->sort() as $unit)
                                        <option value="{{ $unit }}">{{ $unit }}</option>
                                    @endforeach
                                </select>

                                <select class="form-select form-select-sm" id="ageFilter" style="width: auto;">
                                    <option value="">Semua Umur</option>
                                    <option value="4-5">4-5 Tahun</option>
                                    <option value="6-7">6-7 Tahun</option>
                                    <option value="8-10">8-10 Tahun</option>
                                    <option value="11-13">11-13 Tahun</option>
                                    <option value="14-16">14-16 Tahun</option>
                                </select>

                                <select class="form-select form-select-sm" id="entriesPerPage" style="width: auto;">
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>

                                <button class="btn btn-outline-secondary btn-sm" id="resetFilter">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- Loading Overlay -->
                <div id="loadingOverlay" class="d-none position-relative">
                    <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                        <div class="spinner-border text-primary me-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="text-muted">Memuat data...</span>
                    </div>
                </div>

                <!-- Bulk Actions Bar -->
                <div id="bulkActionsBar" class="d-none bg-light border-bottom p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            <i class="bi bi-check-square me-1"></i>
                            <span id="selectedCountText">0</span> item dipilih
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-success btn-sm" id="bulkVerify">
                                <i class="bi bi-check-all me-1"></i>Verifikasi Terpilih
                            </button>
                            <button class="btn btn-outline-danger btn-sm" id="bulkDelete">
                                <i class="bi bi-trash me-1"></i>Hapus Terpilih
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="pendaftarTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-3 py-3" style="width: 50px;">
                                    <div class="form-check">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </div>
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase" style="width: 60px;">
                                    <i class="bi bi-hash me-1"></i>No
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase" style="min-width: 280px;">
                                    <i class="bi bi-person me-1"></i>Calon Murid
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase" style="width: 150px;">
                                    <i class="bi bi-card-text me-1"></i>No Pendaftaran
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase" style="width: 120px;">
                                    <i class="bi bi-building me-1"></i>Unit
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase" style="width: 100px;">
                                    <i class="bi bi-calendar me-1"></i>Umur
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase" style="width: 120px;">
                                    <i class="bi bi-file-earmark me-1"></i>Dokumen
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase" style="width: 120px;">
                                    <i class="bi bi-check-circle me-1"></i>Status
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase text-center" style="width: 150px;">
                                    <i class="bi bi-gear me-1"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dt_pendaftars as $index => $item)
                                <tr class="table-row"
                                    data-status="{{ $item->status }}"
                                    data-unit="{{ $item->unit }}"
                                    data-age="{{ \Carbon\Carbon::parse($item->tanggal_lahir)->diff(\Carbon\Carbon::create(2026,7,1))->y }}"
                                    data-nama="{{ strtolower($item->nama_murid) }}"
                                    data-no-pendaftaran="{{ strtolower($item->no_pendaftaran) }}"
                                    data-nisn="{{ $item->nisn ?? '' }}"
                                    data-tanggal-lahir="{{ $item->tanggal_lahir }}"
                                    data-alamat="{{ $item->alamat ?? '' }}"
                                    data-search="{{ strtolower($item->nama_murid . ' ' . $item->nisn . ' ' . $item->no_pendaftaran . ' ' . $item->unit) }}"
                                    data-index="{{ $index + 1 }}">
                                    <td class="px-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input row-checkbox" value="{{ $item->id }}">
                                        </div>
                                    </td>
                                    <td class="px-3 fw-semibold text-muted">{{ $index + 1 }}</td>
                                    <td class="px-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                @if($item->foto_murid_path)
                                                    <img src="{{ asset('storage/' . $item->foto_murid_path) }}"
                                                         alt="Foto {{ $item->nama_murid }}"
                                                         class="rounded-circle border-2 border-primary"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-gradient-primary d-flex align-items-center justify-content-center text-white"
                                                         style="width: 50px; height: 50px;">
                                                        <i class="bi bi-person fs-5"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold nama-murid">{{ $item->nama_murid }}</h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-card-text me-1"></i>
                                                    {{ $item->nisn ?? 'NISN: Belum diisi' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3">
                                        <code class="bg-light px-2 py-1 rounded no-pendaftaran fw-bold">{{ $item->no_pendaftaran }}</code>
                                    </td>
                                    <td class="px-3">
                                        <span class="badge bg-info bg-gradient text-white px-2 py-1 fs-6">
                                            {{ $item->unit }}
                                        </span>
                                    </td>
                                    <td class="px-3">
                                        @php
                                            $age = \Carbon\Carbon::parse($item->tanggal_lahir)->diff(\Carbon\Carbon::create(2026,7,1));
                                        @endphp
                                        <div class="text-center">
                                            <div class="fw-bold fs-5">{{ $age->y }}</div>
                                            <small class="text-muted">tahun {{ $age->m }} bln</small>
                                        </div>
                                    </td>
                                    <td class="px-3">
                                        <div class="d-flex flex-wrap gap-1">
                                            @if($item->akta_kelahiran_path)
                                                <a href="{{ asset('storage/' . $item->akta_kelahiran_path) }}"
                                                   target="_blank"
                                                   class="btn btn-outline-info btn-sm"
                                                   data-bs-toggle="tooltip"
                                                   title="Akta Kelahiran">
                                                    <i class="bi bi-file-earmark-text"></i>
                                                </a>
                                            @endif
                                            @if($item->kartu_keluarga_path)
                                                <a href="{{ asset('storage/' . $item->kartu_keluarga_path) }}"
                                                   target="_blank"
                                                   class="btn btn-outline-secondary btn-sm"
                                                   data-bs-toggle="tooltip"
                                                   title="Kartu Keluarga">
                                                    <i class="bi bi-file-earmark"></i>
                                                </a>
                                            @endif
                                            @if($item->bukti_pendaftaran_path)
                                                <a href="{{ asset($item->bukti_pendaftaran_path) }}"
                                                   target="_blank"
                                                   class="btn btn-outline-success btn-sm"
                                                   data-bs-toggle="tooltip"
                                                   title="Bukti Pendaftaran">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3">
                                        @if($item->status == 'pending')
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                                <i class="bi bi-clock-fill me-1"></i>Pending
                                            </span>
                                        @elseif($item->status == 'diverifikasi')
                                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                                <i class="bi bi-check-circle-fill me-1"></i>Verified
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('pendaftar.validasi', $item->id) }}"
                                               class="btn btn-outline-info btn-sm"
                                               data-bs-toggle="tooltip"
                                               title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($item->status == 'pending')
                                                <button type="button"
                                                        class="btn btn-outline-success btn-sm verify-btn"
                                                        data-id="{{ $item->id }}"
                                                        data-name="{{ $item->nama_murid }}"
                                                        data-bs-toggle="tooltip"
                                                        title="Verifikasi">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="noDataRow">
                                    <td colspan="9" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox display-4 text-secondary mb-3"></i>
                                            <h5 class="mt-3">Belum ada data pendaftar</h5>
                                            <p>Data pendaftar akan muncul di sini setelah ada yang mendaftar</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-white border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i>
                            Menampilkan <span class="fw-bold text-primary" id="showingCount">{{ $dt_pendaftars->count() }}</span>
                            dari <span class="fw-bold" id="totalRecords">{{ $dt_pendaftars->count() }}</span> data
                        </div>
                        <nav aria-label="Pagination Navigation">
                            <ul class="pagination pagination-sm justify-content-center mb-0" id="pagination">
                                <!-- Pagination will be generated by JavaScript -->
                            </ul>
                        </nav>
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

        .table-row {
            transition: all 0.3s ease;
        }

        .table-row:hover {
            background-color: #f8f9fa !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .table-row.highlight {
            background-color: #fff3cd !important;
            border-left: 4px solid #ffc107;
        }

        .search-highlight {
            background-color: #ffeb3b;
            padding: 2px 4px;
            border-radius: 4px;
            font-weight: bold;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-group .btn {
            border-radius: 0.375rem !important;
            margin-right: 2px;
            transition: all 0.3s ease;
        }

        .btn-group .btn:hover {
            transform: scale(1.1);
        }

        #quickSearch {
            border: 2px solid #dee2e6;
            transition: all 0.3s ease;
        }

        #quickSearch:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .counter {
            font-family: 'Inter', sans-serif;
        }

        #loadingOverlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            z-index: 1000;
        }

        .badge {
            font-size: 0.75em;
        }

        .table th {
            font-weight: 600;
            background-color: #f8f9fa !important;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <!-- Hidden meta tags for JavaScript -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="update-route" content="{{ route('pendaftar.update', ':id') }}">

    <!-- Main JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Initializing...');

            // Initialize AOS
            AOS.init({
                duration: 600,
                easing: 'ease-in-out',
                once: true
            });

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Counter animation
            function animateCounter(element) {
                const target = parseInt(element.getAttribute('data-target'));
                const duration = 1500;
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

            // Trigger counter animation
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

            // Get CSRF token and route template
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const updateRouteTemplate = document.querySelector('meta[name="update-route"]').getAttribute('content');

            // Variables
            const quickSearch = document.getElementById('quickSearch');
            const clearQuickSearch = document.getElementById('clearQuickSearch');
            const statusFilter = document.getElementById('statusFilter');
            const unitFilter = document.getElementById('unitFilter');
            const ageFilter = document.getElementById('ageFilter');
            const resetBtn = document.getElementById('resetFilter');
            const entriesPerPage = document.getElementById('entriesPerPage');
            const selectAllCheckbox = document.getElementById('selectAll');
            const tableRows = document.querySelectorAll('.table-row');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const searchResultCount = document.getElementById('searchResultCount');
            const searchProgress = document.getElementById('searchProgress');
            const exportBtn = document.getElementById('exportBtn');
            const printBtn = document.getElementById('printBtn');
            const bulkActionsBar = document.getElementById('bulkActionsBar');
            const bulkVerify = document.getElementById('bulkVerify');

            let currentPage = 1;
            let filteredRows = Array.from(tableRows);
            let searchTimeout;

            // Auto close alerts
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert.classList.contains('show')) {
                        alert.classList.remove('show');
                        setTimeout(() => alert.remove(), 300);
                    }
                });
            }, 5000);

            // Show loading spinner
            function showLoading(show) {
                if (loadingOverlay) {
                    loadingOverlay.classList.toggle('d-none', !show);
                }
            }

            // Highlight search text
            function highlightSearchText(text, searchTerm) {
                if (!searchTerm) return text;
                const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                return text.replace(regex, '<span class="search-highlight">$1</span>');
            }

            // Remove highlights
            function removeHighlights() {
                document.querySelectorAll('.search-highlight').forEach(el => {
                    el.outerHTML = el.innerHTML;
                });
                document.querySelectorAll('.table-row').forEach(row => {
                    row.classList.remove('highlight');
                });
            }

            // Quick search function
            function performQuickSearch() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    showLoading(true);

                    const searchTerm = quickSearch ? quickSearch.value.toLowerCase().trim() : '';
                    let visibleCount = 0;
                    let pendingCount = 0;
                    let verifiedCount = 0;
                    filteredRows = [];

                    removeHighlights();

                    tableRows.forEach(row => {
                        const namaMurid = row.dataset.nama || '';
                        const noPendaftaran = row.dataset.noPendaftaran || '';
                        const status = row.dataset.status;
                        const unit = row.dataset.unit;
                        const age = parseInt(row.dataset.age);

                        let show = true;

                        // Quick search
                        if (searchTerm) {
                            if (!namaMurid.includes(searchTerm) && !noPendaftaran.includes(searchTerm)) {
                                show = false;
                            } else {
                                row.classList.add('highlight');

                                const namaMuridEl = row.querySelector('.nama-murid');
                                if (namaMuridEl && namaMurid.includes(searchTerm)) {
                                    namaMuridEl.innerHTML = highlightSearchText(namaMuridEl.textContent, searchTerm);
                                }

                                const noPendaftaranEl = row.querySelector('.no-pendaftaran');
                                if (noPendaftaranEl && noPendaftaran.includes(searchTerm)) {
                                    noPendaftaranEl.innerHTML = highlightSearchText(noPendaftaranEl.textContent, searchTerm);
                                }
                            }
                        }

                        // Apply other filters
                        const statusValue = statusFilter ? statusFilter.value : '';
                        const unitValue = unitFilter ? unitFilter.value : '';
                        const ageValue = ageFilter ? ageFilter.value : '';

                        if (statusValue && status !== statusValue) show = false;
                        if (unitValue && unit !== unitValue) show = false;

                        if (ageValue) {
                            const [minAge, maxAge] = ageValue.split('-').map(Number);
                            if (age < minAge || age > maxAge) show = false;
                        }

                        if (show) {
                            filteredRows.push(row);
                            visibleCount++;
                            if (status === 'pending') pendingCount++;
                            if (status === 'diverifikasi') verifiedCount++;
                        }
                    });

                    // Update counters
                    if (searchResultCount) {
                        searchResultCount.textContent = visibleCount;
                    }

                    // Update progress bar
                    if (searchProgress) {
                        const totalRows = tableRows.length;
                        const percentage = totalRows > 0 ? (visibleCount / totalRows) * 100 : 0;
                        searchProgress.style.width = percentage + '%';
                    }

                    currentPage = 1;
                    updateTableDisplay();
                    updatePagination();
                    showLoading(false);
                }, 300);
            }

            // Update table display
            function updateTableDisplay() {
                const perPage = entriesPerPage ? parseInt(entriesPerPage.value) : 25;
                const startIndex = (currentPage - 1) * perPage;
                const endIndex = startIndex + perPage;

                // Hide all rows first
                tableRows.forEach(row => {
                    row.style.display = 'none';
                });

                // Show filtered rows for current page
                filteredRows.slice(startIndex, endIndex).forEach(row => {
                    row.style.display = '';
                });

                // Handle no data row
                const noDataRow = document.getElementById('noDataRow');
                if (noDataRow) {
                    noDataRow.style.display = filteredRows.length === 0 ? '' : 'none';
                }

                // Update showing count
                const showing = Math.min(endIndex, filteredRows.length);
                const start = filteredRows.length > 0 ? startIndex + 1 : 0;
                const showingCountEl = document.getElementById('showingCount');
                if (showingCountEl) {
                    showingCountEl.textContent = filteredRows.length > 0 ? `${start}-${showing}` : '0';
                }
            }

            // Generate pagination
            function updatePagination() {
                const perPage = entriesPerPage ? parseInt(entriesPerPage.value) : 25;
                const totalPages = Math.ceil(filteredRows.length / perPage);
                const pagination = document.getElementById('pagination');

                if (!pagination) return;
                pagination.innerHTML = '';
                if (totalPages <= 1) return;

                // Previous button
                const prevLi = document.createElement('li');
                prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                prevLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage - 1}">
                    <i class="bi bi-chevron-left"></i></a>`;
                pagination.appendChild(prevLi);

                // Page numbers
                const startPage = Math.max(1, currentPage - 2);
                const endPage = Math.min(totalPages, currentPage + 2);

                for (let i = startPage; i <= endPage; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
                    pagination.appendChild(li);
                }

                // Next button
                const nextLi = document.createElement('li');
                nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
                nextLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage + 1}">
                    <i class="bi bi-chevron-right"></i></a>`;
                pagination.appendChild(nextLi);
            }

            // Toggle bulk actions
            function toggleBulkActions() {
                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                const hasChecked = checkedBoxes.length > 0;

                if (bulkActionsBar) {
                    bulkActionsBar.classList.toggle('d-none', !hasChecked);
                    const selectedCountText = document.getElementById('selectedCountText');
                    if (selectedCountText) selectedCountText.textContent = checkedBoxes.length;
                }
            }

            // Clear selection
            window.clearSelection = function() {
                const checkboxes = document.querySelectorAll('.row-checkbox, #selectAll');
                checkboxes.forEach(checkbox => checkbox.checked = false);
                toggleBulkActions();
            }

            // Export to Excel
            function exportToExcel() {
                if (typeof XLSX === 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Library XLSX tidak ditemukan. Silakan refresh halaman.',
                        confirmButtonColor: '#dc3545'
                    });
                    return;
                }

                const visibleData = [];
                visibleData.push([
                    'No', 'Nama Calon Murid', 'NISN', 'No Pendaftaran',
                    'Unit Sekolah', 'Tanggal Lahir', 'Umur (Juli 2026)', 'Alamat', 'Status'
                ]);

                filteredRows.forEach((row, index) => {
                    const nama = (row.dataset.nama || '').replace(/^\w/, c => c.toUpperCase());
                    const nisn = row.dataset.nisn || 'Belum diisi';
                    const noPendaftaran = (row.dataset.noPendaftaran || '').toUpperCase();
                    const unit = row.dataset.unit || '';
                    const tanggalLahir = row.dataset.tanggalLahir || '';
                    const umur = row.dataset.age || '';
                    const alamat = row.dataset.alamat || '';
                    const status = row.dataset.status || '';

                    visibleData.push([
                        index + 1, nama, nisn, noPendaftaran, unit,
                        tanggalLahir, `${umur} tahun`, alamat,
                        status === 'pending' ? 'Pending' : 'Diverifikasi'
                    ]);
                });

                try {
                    const wb = XLSX.utils.book_new();
                    const ws = XLSX.utils.aoa_to_sheet(visibleData);

                    ws['!cols'] = [
                        { wch: 5 }, { wch: 25 }, { wch: 15 }, { wch: 15 },
                        { wch: 15 }, { wch: 12 }, { wch: 12 }, { wch: 30 }, { wch: 12 }
                    ];

                    XLSX.utils.book_append_sheet(wb, ws, 'Data Pendaftar');

                    const now = new Date();
                    const timestamp = now.toISOString().slice(0, 10);
                    const filename = `Data_Pendaftar_PPDB_${timestamp}.xlsx`;

                    XLSX.writeFile(wb, filename);

                    Swal.fire({
                        icon: 'success',
                        title: 'Export Berhasil!',
                        text: `File ${filename} berhasil didownload.`,
                        confirmButtonColor: '#28a745'
                    });
                } catch (error) {
                    console.error('Export error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Export Gagal!',
                        text: 'Terjadi kesalahan saat export data.',
                        confirmButtonColor: '#dc3545'
                    });
                }
            }

            // Print function
            function printTable() {
                const printWindow = window.open('', '_blank');
                let printContent = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Data Pendaftar PPDB 2026/2027</title>
                        <style>
                            body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
                            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                            th { background-color: #f2f2f2; font-weight: bold; }
                            .header { text-align: center; margin-bottom: 20px; }
                        </style>
                    </head>
                    <body>
                        <div class="header">
                            <h2>Data Pendaftar Murid Baru</h2>
                            <h3>Tahun Ajaran 2026/2027</h3>
                            <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}</p>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th><th>Nama Calon Murid</th><th>NISN</th>
                                    <th>No Pendaftaran</th><th>Unit Sekolah</th><th>Umur</th><th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                filteredRows.forEach((row, index) => {
                    const nama = (row.dataset.nama || '').replace(/^\w/, c => c.toUpperCase());
                    const nisn = row.dataset.nisn || 'Belum diisi';
                    const noPendaftaran = (row.dataset.noPendaftaran || '').toUpperCase();
                    const unit = row.dataset.unit || '';
                    const umur = row.dataset.age || '';
                    const status = row.dataset.status || '';

                    printContent += `
                        <tr>
                            <td>${index + 1}</td><td>${nama}</td><td>${nisn}</td>
                            <td>${noPendaftaran}</td><td>${unit}</td><td>${umur} tahun</td>
                            <td>${status === 'pending' ? 'Pending' : 'Diverifikasi'}</td>
                        </tr>
                    `;
                });

                printContent += `
                            </tbody>
                        </table>
                    </body>
                    </html>
                `;

                printWindow.document.write(printContent);
                printWindow.document.close();
                printWindow.print();
            }

            // Single verification
            function handleSingleVerification(id, name) {
                Swal.fire({
                    title: 'Konfirmasi Verifikasi',
                    text: `Yakin ingin memverifikasi pendaftar ${name}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Verifikasi!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang memverifikasi pendaftar',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = updateRouteTemplate.replace(':id', id);

                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;

                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'PATCH';

                        form.appendChild(csrfInput);
                        form.appendChild(methodField);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            // Refresh data
            window.refreshData = function() {
                showLoading(true);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }

            // Event listeners
            if (quickSearch) {
                quickSearch.addEventListener('input', performQuickSearch);
            }

            if (statusFilter) {
                statusFilter.addEventListener('change', performQuickSearch);
            }

            if (unitFilter) {
                unitFilter.addEventListener('change', performQuickSearch);
            }

            if (ageFilter) {
                ageFilter.addEventListener('change', performQuickSearch);
            }

            if (entriesPerPage) {
                entriesPerPage.addEventListener('change', () => {
                    currentPage = 1;
                    updateTableDisplay();
                    updatePagination();
                });
            }

            if (clearQuickSearch) {
                clearQuickSearch.addEventListener('click', () => {
                    if (quickSearch) {
                        quickSearch.value = '';
                        removeHighlights();
                        performQuickSearch();
                        quickSearch.focus();
                    }
                });
            }

            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    if (quickSearch) quickSearch.value = '';
                    if (statusFilter) statusFilter.value = '';
                    if (unitFilter) unitFilter.value = '';
                    if (ageFilter) ageFilter.value = '';
                    currentPage = 1;
                    removeHighlights();
                    performQuickSearch();
                });
            }

            if (exportBtn) {
                exportBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    exportToExcel();
                });
            }

            if (printBtn) {
                printBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    printTable();
                });
            }

            // Pagination click handler
            document.addEventListener('click', function(e) {
                if (e.target.closest('.page-link') && e.target.closest('.page-link').dataset.page) {
                    e.preventDefault();
                    const page = parseInt(e.target.closest('.page-link').dataset.page);
                    if (page !== currentPage && page > 0) {
                        currentPage = page;
                        updateTableDisplay();
                        updatePagination();
                    }
                }
            });

            // Verify button click handler
            document.addEventListener('click', function(e) {
                if (e.target.closest('.verify-btn')) {
                    e.preventDefault();
                    const btn = e.target.closest('.verify-btn');
                    const id = btn.dataset.id;
                    const name = btn.dataset.name;
                    handleSingleVerification(id, name);
                }
            });

            // Select all functionality
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    filteredRows.forEach(row => {
                        if (row.style.display !== 'none') {
                            const checkbox = row.querySelector('.row-checkbox');
                            if (checkbox) {
                                checkbox.checked = this.checked;
                            }
                        }
                    });
                    toggleBulkActions();
                });
            }

            // Individual checkbox change
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('row-checkbox')) {
                    toggleBulkActions();

                    const visibleCheckboxes = filteredRows
                        .filter(row => row.style.display !== 'none')
                        .map(row => row.querySelector('.row-checkbox'))
                        .filter(checkbox => checkbox !== null);

                    const checkedVisibleBoxes = visibleCheckboxes.filter(checkbox => checkbox.checked);

                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = visibleCheckboxes.length > 0 && checkedVisibleBoxes.length === visibleCheckboxes.length;
                        selectAllCheckbox.indeterminate = checkedVisibleBoxes.length > 0 && checkedVisibleBoxes.length < visibleCheckboxes.length;
                    }
                }
            });

            if (bulkVerify) {
            bulkVerify.addEventListener('click', function(e) {
                e.preventDefault();

                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Silakan pilih data yang akan diverifikasi.',
                        confirmButtonColor: '#ffc107'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Verifikasi Massal',
                    text: `Yakin ingin memverifikasi ${selectedIds.length} pendaftar terpilih?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Verifikasi Semua!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performBulkVerification(selectedIds);
                    }
                });
            });
        }

        // Bulk delete
        const bulkDelete = document.getElementById('bulkDelete');
        if (bulkDelete) {
            bulkDelete.addEventListener('click', function(e) {
                e.preventDefault();

                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Silakan pilih data yang akan dihapus.',
                        confirmButtonColor: '#ffc107'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Hapus Massal',
                    text: `Yakin ingin menghapus ${selectedIds.length} pendaftar terpilih? Data yang dihapus tidak dapat dikembalikan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus Semua!',
                    cancelButtonText: 'Batal',
                    inputLabel: 'Ketik "HAPUS" untuk konfirmasi',
                    input: 'text',
                    inputPlaceholder: 'Ketik HAPUS',
                    inputValidator: (value) => {
                        if (value !== 'HAPUS') {
                            return 'Ketik "HAPUS" untuk melanjutkan!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        performBulkDelete(selectedIds);
                    }
                });
            });
        }

        // Function to perform bulk verification
        function performBulkVerification(selectedIds) {
            Swal.fire({
                title: 'Memproses Verifikasi...',
                text: 'Sedang memverifikasi data terpilih',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create form for bulk verification
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("pendaftar.bulk-verify") }}'; // Buat route ini di Laravel
            form.style.display = 'none';

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            // Add selected IDs
            selectedIds.forEach(id => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'ids[]';
                idInput.value = id;
                form.appendChild(idInput);
            });

            document.body.appendChild(form);
            form.submit();
        }

        // Function to perform bulk delete
        function performBulkDelete(selectedIds) {
            Swal.fire({
                title: 'Menghapus Data...',
                text: 'Sedang menghapus data terpilih',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create form for bulk delete
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("pendaftar.bulk-delete") }}'; // Buat route ini di Laravel
            form.style.display = 'none';

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            // Add method DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Add selected IDs
            selectedIds.forEach(id => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'ids[]';
                idInput.value = id;
                form.appendChild(idInput);
            });

            document.body.appendChild(form);
            form.submit();
        }

            // Initialize
            console.log('Initializing table with', tableRows.length, 'rows');
            performQuickSearch();

            if (quickSearch) {
                quickSearch.focus();
            }

            console.log('Initialization complete');
        });
    </script>
</x-app-layout>
