<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-graph-up-arrow me-2 text-primary"></i>
                    Progres Pendaftaran
                </h2>
                <p class="text-muted small mb-0">Monitor dan kelola progres pendaftaran siswa per unit</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="refreshData()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh Data
                </button>
                <a href="{{ route('admin.pendaftar.index') }}" class="btn btn-primary">
                    <i class="bi bi-people me-1"></i>Kelola Pendaftar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Success & Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" data-aos="fade-down" data-aos-duration="500">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">Total Pendaftar</h6>
                                <h4 class="fw-bold mb-0" id="totalPendaftarCount">{{ $totalPendaftar }}</h4>
                                <small class="text-muted">
                                    @if($unit !== 'all')
                                        di {{ strtoupper($unit) }}
                                    @else
                                        Semua Unit
                                    @endif
                                </small>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-people text-primary fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">Disetujui</h6>
                                <h4 class="fw-bold mb-0 text-success" id="totalApprovedCount">{{ $totalApproved }}</h4>
                                <small class="text-muted">Status Verifikasi</small>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-check-circle text-success fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">Menunggu</h6>
                                <h4 class="fw-bold mb-0 text-warning" id="totalPendingCount">{{ $totalPending }}</h4>
                                <small class="text-muted">Perlu Verifikasi</small>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-clock text-warning fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">Sudah Bayar</h6>
                                <h4 class="fw-bold mb-0 text-info" id="totalPaidCount">{{ $totalPaid }}</h4>
                                <small class="text-muted">Formulir Lunas</small>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-cash-coin text-info fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unit Statistics Chart -->
        <div class="row mb-4">
            <div class="col-12" data-aos="fade-up" data-aos-delay="500">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-bar-chart me-2 text-primary"></i>
                            Distribusi Pendaftar per Unit
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($unitStats as $unit)
                            <div class="col-lg-3 col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="me-3">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle">
                                            <i class="bi bi-building text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $unit->school_unit ?? 'Tidak Diketahui' }}</div>
                                        <div class="text-primary fw-bold">{{ $unit->count }} siswa</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unit Tabs & Students Data Table -->
        <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="600">
            <div class="card-header bg-white border-bottom py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-list-ul me-2 text-primary"></i>
                            Daftar Siswa Pendaftar
                        </h5>
                    </div>
                    <div class="col-auto">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text"
                                   id="searchInput"
                                   class="form-control border-start-0"
                                   placeholder="Cari nama siswa, no pendaftaran..."
                                   value="{{ $search ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- Unit Filter Tabs -->
                <div class="mt-3">
                    <ul class="nav nav-pills nav-fill bg-light rounded p-1" id="unitTabs">
                        <li class="nav-item">
                            <a class="nav-link {{ $unit === 'all' ? 'active' : '' }}"
                               href="#"
                               data-unit="all"
                               onclick="filterByUnit('all', event)">
                                <i class="bi bi-grid-3x3-gap me-1"></i>
                                Semua Unit
                            </a>
                        </li>
                        @foreach($availableUnits as $availableUnit)
                        <li class="nav-item">
                            <a class="nav-link {{ $unit === $availableUnit ? 'active' : '' }}"
                               href="#"
                               data-unit="{{ $availableUnit }}"
                               onclick="filterByUnit('{{ $availableUnit }}', event)">
                                <i class="bi bi-building me-1"></i>
                                {{ strtoupper($availableUnit) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold">No. Pendaftaran</th>
                                <th class="border-0 fw-semibold">Nama Siswa</th>
                                <th class="border-0 fw-semibold">Unit</th>
                                <th class="border-0 fw-semibold">Jenjang</th>
                                <th class="border-0 fw-semibold">Status Verifikasi</th>
                                <th class="border-0 fw-semibold">Status Keseluruhan</th>
                                <th class="border-0 fw-semibold">Pembayaran</th>
                                <th class="border-0 fw-semibold">Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            @include('admin.progres-pendaftaran.partials.table')
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        @if($studentsData->count() > 0)
                            Menampilkan {{ $studentsData->firstItem() }} sampai {{ $studentsData->lastItem() }}
                            dari {{ $studentsData->total() }} siswa
                        @else
                            Tidak ada data untuk ditampilkan
                        @endif
                    </div>
                    <div id="studentsPagination">
                        {{ $studentsData->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }

        .table th {
            font-weight: 600;
            color: #374151;
        }

        .card {
            border-radius: 12px;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375em 0.75em;
        }

        .progress {
            height: 8px;
        }
    </style>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Search functionality with debouncing
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchValue = this.value;

                searchTimeout = setTimeout(() => {
                    performSearch(searchValue);
                }, 300); // 300ms debounce
            });
        }

        function performSearch(query) {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('search', query);

            if (!query) {
                currentUrl.searchParams.delete('search');
            }

            // Add loading state
            const tableBody = document.getElementById('studentsTableBody');
            if (tableBody) {
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';
            }

            fetch(currentUrl.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update table content
                if (tableBody) {
                    tableBody.innerHTML = data.html;
                }

                // Update pagination
                const paginationContainer = document.getElementById('studentsPagination');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }

                // Update statistics
                if (data.statistics) {
                    updateStatistics(data.statistics);
                }

                // Re-initialize tooltips for new content
                const newTooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                newTooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Update URL without page reload
                history.pushState(null, '', currentUrl.toString());
            })
            .catch(error => {
                console.error('Search error:', error);
                if (tableBody) {
                    tableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-danger">Terjadi kesalahan saat mencari data</td></tr>';
                }
            });
        }

        function updateStatistics(statistics) {
            const totalElement = document.getElementById('totalPendaftarCount');
            const approvedElement = document.getElementById('totalApprovedCount');
            const pendingElement = document.getElementById('totalPendingCount');
            const paidElement = document.getElementById('totalPaidCount');

            if (totalElement) totalElement.textContent = statistics.totalPendaftar;
            if (approvedElement) approvedElement.textContent = statistics.totalApproved;
            if (pendingElement) pendingElement.textContent = statistics.totalPending;
            if (paidElement) paidElement.textContent = statistics.totalPaid;
        }

        // Unit filter function
        window.filterByUnit = function(unit, event) {
            event.preventDefault();

            // Update active tab
            document.querySelectorAll('#unitTabs .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            event.target.classList.add('active');

            // Create new URL with unit filter
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('unit', unit);

            // Add loading state
            const tableBody = document.getElementById('studentsTableBody');
            if (tableBody) {
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';
            }

            // Fetch filtered data
            fetch(currentUrl.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update table content
                if (tableBody) {
                    tableBody.innerHTML = data.html;
                }

                // Update pagination
                const paginationContainer = document.getElementById('studentsPagination');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }

                // Update statistics
                if (data.statistics) {
                    updateStatistics(data.statistics);
                }

                // Re-initialize tooltips for new content
                const newTooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                newTooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Update URL without page reload
                history.pushState(null, '', currentUrl.toString());
            })
            .catch(error => {
                console.error('Filter error:', error);
                if (tableBody) {
                    tableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-danger">Terjadi kesalahan saat memfilter data</td></tr>';
                }
            });
        }

        // Refresh data function
        window.refreshData = function() {
            location.reload();
        }
    });
    </script>
</x-app-layout>
