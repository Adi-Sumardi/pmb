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
                    <div class="col-auto">
                        <select class="form-select" id="academicYearFilter" style="width: auto;">
                            <option value="">Semua Tahun Ajaran</option>
                            <option value="2026/2027" {{ ($academicYear ?? '2026/2027') == '2026/2027' ? 'selected' : '' }}>2026/2027</option>
                            <option value="2025/2026" {{ ($academicYear ?? '') == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
                            <option value="2024/2025" {{ ($academicYear ?? '') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                        </select>
                    </div>
                </div>

                <!-- Bulk Actions Bar -->
                <div id="bulkActionsBar" class="d-none bg-light border-top border-bottom p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            <i class="bi bi-check-square me-1"></i>
                            <span id="selectedCountText">0</span> siswa dipilih
                        </div>
                        <div class="d-flex gap-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="bulkOverallStatusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-diagram-3 me-1"></i>Update Status Keseluruhan
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="bulkOverallStatusDropdown">
                                    <li><h6 class="dropdown-header">Pilih Status</h6></li>
                                    <li><a class="dropdown-item bulk-overall-status-option" href="#" data-status="Draft"><i class="bi bi-file-earmark me-2 text-secondary"></i>Draft</a></li>
                                    <li><a class="dropdown-item bulk-overall-status-option" href="#" data-status="Diverifikasi"><i class="bi bi-check-circle-fill me-2 text-success"></i>Diverifikasi</a></li>
                                    <li><a class="dropdown-item bulk-overall-status-option" href="#" data-status="Sudah Bayar"><i class="bi bi-credit-card-check me-2 text-info"></i>Sudah Bayar</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item bulk-overall-status-option" href="#" data-status="Observasi"><i class="bi bi-eyeglasses me-2 text-primary"></i>Observasi</a></li>
                                    <li><a class="dropdown-item bulk-overall-status-option" href="#" data-status="Tes Tulis"><i class="bi bi-pencil-square me-2 text-primary"></i>Tes Tulis</a></li>
                                    <li><a class="dropdown-item bulk-overall-status-option" href="#" data-status="Praktek Shalat & BTQ"><i class="bi bi-book me-2 text-primary"></i>Praktek Shalat & BTQ</a></li>
                                    <li><a class="dropdown-item bulk-overall-status-option" href="#" data-status="Wawancara"><i class="bi bi-chat-dots me-2 text-primary"></i>Wawancara</a></li>
                                    <li><a class="dropdown-item bulk-overall-status-option" href="#" data-status="Psikotest"><i class="bi bi-brain me-2 text-primary"></i>Psikotest</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item bulk-overall-status-option" href="#" data-status="Lulus"><i class="bi bi-trophy me-2 text-success"></i>Lulus</a></li>
                                    <li><a class="dropdown-item bulk-overall-status-option" href="#" data-status="Tidak Lulus"><i class="bi bi-x-circle me-2 text-danger"></i>Tidak Lulus</a></li>
                                </ul>
                            </div>
                            <button class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </button>
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
                                <th class="border-0 fw-semibold" style="width: 50px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                        <label class="form-check-label visually-hidden" for="selectAll">Select All</label>
                                    </div>
                                </th>
                                <th class="border-0 fw-semibold">No. Pendaftaran</th>
                                <th class="border-0 fw-semibold">Nama Siswa</th>
                                <th class="border-0 fw-semibold">Unit</th>
                                <th class="border-0 fw-semibold">Status Verifikasi</th>
                                <th class="border-0 fw-semibold">Status Keseluruhan</th>
                                <th class="border-0 fw-semibold">Status Siswa</th>
                                <th class="border-0 fw-semibold">Pembayaran</th>
                                <th class="border-0 fw-semibold">Tanggal Daftar</th>
                                <th class="border-0 fw-semibold">Aksi</th>
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

        /* Enhanced Alert Styles */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem 1.25rem;
        }

        .alert-success {
            background: linear-gradient(135deg, #d1e7dd 0%, #a3cfbb 100%);
            color: #0f5132;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f1aeb5 100%);
            color: #721c24;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffda6a 100%);
            color: #664d03;
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #9eeaf9 100%);
            color: #055160;
        }

        .alert .bi {
            filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));
        }

        /* Loading button animation */
        .spinner-border-sm {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Modal enhancements */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .modal-header {
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 1.5rem;
        }
    </style>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Main JavaScript -->
    <script>
    // Laravel routes for JavaScript
    window.adminRoutes = {
        baseUrl: "{{ url('admin/progres-pendaftaran') }}"
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true
        });

        // Debug modal elements
        console.log('DOM Loaded - Checking modal elements:');
        console.log('Student status modal:', document.getElementById('studentStatusModal') ? 'FOUND' : 'NOT FOUND');
        console.log('Status form:', document.getElementById('statusForm') ? 'FOUND' : 'NOT FOUND');
        console.log('CSRF token:', document.querySelector('meta[name="csrf-token"]') ? 'FOUND' : 'NOT FOUND');
        console.log('Admin base URL:', window.adminRoutes.baseUrl);

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Search functionality with debouncing
        const searchInput = document.getElementById('searchInput');
        const academicYearFilter = document.getElementById('academicYearFilter');
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

        if (academicYearFilter) {
            academicYearFilter.addEventListener('change', function() {
                const academicYear = this.value;
                performAcademicYearFilter(academicYear);
            });
        }

        function performAcademicYearFilter(academicYear) {
            const currentUrl = new URL(window.location.href);
            
            if (academicYear) {
                currentUrl.searchParams.set('academic_year', academicYear);
            } else {
                currentUrl.searchParams.delete('academic_year');
            }

            // Add loading state
            const tableBody = document.getElementById('studentsTableBody');
            if (tableBody) {
                tableBody.innerHTML = '<tr><td colspan="10" class="text-center py-4"><div class="spinner-border spinner-border-sm me-2" role="status"></div>Memfilter data...</td></tr>';
            }

            // Navigate to new URL to trigger server-side filtering
            window.location.href = currentUrl.toString();
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
                tableBody.innerHTML = '<tr><td colspan="10" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';
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
                    tableBody.innerHTML = '<tr><td colspan="10" class="text-center py-4 text-danger">Terjadi kesalahan saat mencari data</td></tr>';
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
                tableBody.innerHTML = '<tr><td colspan="10" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';
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
                    tableBody.innerHTML = '<tr><td colspan="10" class="text-center py-4 text-danger">Terjadi kesalahan saat memfilter data</td></tr>';
                }
            });
        }

        // Refresh data function
        window.refreshData = function() {
            location.reload();
        }

        // Student status management
        window.openStatusModal = function(studentId) {
            console.log('Opening status modal for student ID:', studentId);

            const statusModalUrl = `${window.adminRoutes.baseUrl}/${studentId}/status-modal`;

            fetch(statusModalUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    console.log('Status modal response:', response.status, response.statusText);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Status modal data received:', data);
                    if (data.success) {
                        // Populate modal
                        document.getElementById('statusStudentId').value = data.data.id;
                        document.getElementById('statusStudentName').textContent = data.data.nama_murid;
                        document.getElementById('statusStudentNumber').textContent = data.data.no_pendaftaran;
                        document.getElementById('statusStudentUnit').textContent = data.data.unit;
                        document.getElementById('studentStatus').value = data.data.student_status;
                        document.getElementById('studentStatusNotes').value = data.data.student_status_notes || '';

                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('studentStatusModal'));
                        modal.show();

                        // Show info alert
                        showAlert('info', `Modal berhasil dimuat untuk siswa: ${data.data.nama_murid}`);
                    } else {
                        console.error('Modal data error:', data.message);
                        showAlert('danger', data.message || 'Gagal memuat data siswa');
                    }
                })
                .catch(error => {
                    console.error('Error loading status modal:', error);

                    let errorMessage = 'Terjadi kesalahan saat memuat data siswa';
                    if (error.message.includes('HTTP error! status: 404')) {
                        errorMessage = 'Data siswa tidak ditemukan';
                    } else if (error.message.includes('HTTP error! status: 403')) {
                        errorMessage = 'Anda tidak memiliki izin untuk mengakses data ini';
                    } else if (error.message.includes('HTTP error! status: 500')) {
                        errorMessage = 'Terjadi kesalahan server';
                    }

                    showAlert('danger', `${errorMessage}. Detail: ${error.message}`);
                });
        };

        // Handle status form submission
        const statusForm = document.getElementById('statusForm');
        if (statusForm) {
            statusForm.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Status form submitted');

                const formData = new FormData(this);
                const studentId = formData.get('student_id');
                const studentName = document.getElementById('statusStudentName').textContent;
                const newStatus = document.getElementById('studentStatus').selectedOptions[0].text;

                console.log('Updating status for student ID:', studentId);

                // Confirmation with SweetAlert
                Swal.fire({
                    title: 'Konfirmasi Perubahan Status',
                    html: `Apakah Anda yakin ingin mengubah status siswa <strong>${studentName}</strong> menjadi <strong>${newStatus}</strong>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Perbarui',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        performStatusUpdate(formData, studentId, this);
                    }
                });
            });
        } else {
            console.error('Status form not found!');
        }

        // Function to perform the actual status update
        function performStatusUpdate(formData, studentId, form) {
            // Show loading state
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memperbarui...';
            submitButton.disabled = true;

                // Show loading alert
                showAlert('info', 'Sedang memperbarui status siswa, mohon tunggu...');

                const requestData = {
                    student_status: formData.get('student_status'),
                    student_status_notes: formData.get('student_status_notes')
                };
                console.log('Request data:', requestData);

                const statusUpdateUrl = `${window.adminRoutes.baseUrl}/${studentId}/status`;

                fetch(statusUpdateUrl, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => {
                    console.log('Status update response:', response.status, response.statusText);
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Response error text:', text);
                            throw new Error(`HTTP error! status: ${response.status} - ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Status update data received:', data);

                    // Reset button state
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;

                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('studentStatusModal'));
                        modal.hide();

                        // Show detailed success message with SweetAlert
                        const studentName = document.getElementById('statusStudentName').textContent;
                        const newStatus = document.getElementById('studentStatus').selectedOptions[0].text;

                        Swal.fire({
                            title: 'Berhasil!',
                            html: `Status siswa <strong>${studentName}</strong> berhasil diperbarui menjadi <strong>${newStatus}</strong>`,
                            icon: 'success',
                            confirmButtonColor: '#198754',
                            confirmButtonText: 'OK'
                        });

                        // Also show regular alert
                        showAlert('success', `Status siswa ${studentName} berhasil diperbarui menjadi: ${newStatus}`);

                        // Refresh table after a short delay
                        setTimeout(() => {
                            refreshData();
                        }, 1500);
                    } else {
                        console.error('Update failed:', data.message);

                        // Show error with SweetAlert
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan tidak diketahui',
                            icon: 'error',
                            confirmButtonColor: '#dc3545',
                            confirmButtonText: 'OK'
                        });

                        showAlert('danger', `Gagal memperbarui status: ${data.message || 'Terjadi kesalahan tidak diketahui'}`);
                    }
                })
                .catch(error => {
                    // Reset button state
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;

                    console.error('Error updating status:', error);

                    // More detailed error message
                    let errorMessage = 'Terjadi kesalahan saat memperbarui status siswa';
                    if (error.message.includes('HTTP error! status: 422')) {
                        errorMessage = 'Data yang dimasukkan tidak valid. Silakan periksa kembali.';
                    } else if (error.message.includes('HTTP error! status: 403')) {
                        errorMessage = 'Anda tidak memiliki izin untuk melakukan aksi ini.';
                    } else if (error.message.includes('HTTP error! status: 404')) {
                        errorMessage = 'Data siswa tidak ditemukan.';
                    } else if (error.message.includes('HTTP error! status: 500')) {
                        errorMessage = 'Terjadi kesalahan server. Silakan coba lagi nanti.';
                    }

                    // Show error with SweetAlert
                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK'
                    });

                    showAlert('danger', `${errorMessage} Detail: ${error.message}`);
                });
        }

        function showAlert(type, message) {
            // Remove any existing alerts first
            const existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(alert => {
                if (!alert.hasAttribute('data-permanent')) {
                    alert.remove();
                }
            });

            // Alert configuration
            const alertConfig = {
                'success': {
                    icon: 'check-circle-fill',
                    title: 'Berhasil!',
                    class: 'alert-success',
                    duration: 4000
                },
                'danger': {
                    icon: 'exclamation-triangle-fill',
                    title: 'Error!',
                    class: 'alert-danger',
                    duration: 6000
                },
                'warning': {
                    icon: 'exclamation-triangle-fill',
                    title: 'Peringatan!',
                    class: 'alert-warning',
                    duration: 5000
                },
                'info': {
                    icon: 'info-circle-fill',
                    title: 'Info',
                    class: 'alert-info',
                    duration: 3000
                }
            };

            const config = alertConfig[type] || alertConfig['info'];

            const alertHtml = `
                <div class="alert ${config.class} alert-dismissible fade show shadow-sm border-0" role="alert"
                     data-aos="fade-down" data-aos-duration="500" style="margin-bottom: 1rem;">
                    <div class="d-flex align-items-start">
                        <div class="me-2 mt-1">
                            <i class="bi bi-${config.icon} fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold mb-1">${config.title}</div>
                            <div class="small">${message}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            `;

            const container = document.querySelector('.container-fluid');
            if (container) {
                container.insertAdjacentHTML('afterbegin', alertHtml);

                // Initialize AOS for the new alert
                if (typeof AOS !== 'undefined') {
                    AOS.refresh();
                }

                // Auto dismiss after specified duration
                setTimeout(() => {
                    const alert = container.querySelector('.alert');
                    if (alert && !alert.matches(':hover')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        if (bsAlert) {
                            bsAlert.close();
                        }
                    }
                }, config.duration);
            }
        }

        // === BULK ACTIONS FUNCTIONALITY ===

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Variables for bulk actions
        const selectAllCheckbox = document.getElementById('selectAll');
        const bulkActionsBar = document.getElementById('bulkActionsBar');
        const selectedCountText = document.getElementById('selectedCountText');

        // Toggle bulk actions bar visibility
        function toggleBulkActions() {
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            const hasChecked = checkedBoxes.length > 0;

            if (bulkActionsBar) {
                bulkActionsBar.classList.toggle('d-none', !hasChecked);
                if (selectedCountText) {
                    selectedCountText.textContent = checkedBoxes.length;
                }
            }
        }

        // Clear selection function
        window.clearSelection = function() {
            const checkboxes = document.querySelectorAll('.row-checkbox, #selectAll');
            checkboxes.forEach(checkbox => checkbox.checked = false);
            toggleBulkActions();
        }

        // Select all functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const rowCheckboxes = document.querySelectorAll('.row-checkbox');
                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                toggleBulkActions();
            });
        }

        // Individual checkbox change handling
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('row-checkbox')) {
                toggleBulkActions();

                // Update select all checkbox state
                const rowCheckboxes = document.querySelectorAll('.row-checkbox');
                const checkedRowBoxes = document.querySelectorAll('.row-checkbox:checked');

                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = rowCheckboxes.length > 0 && checkedRowBoxes.length === rowCheckboxes.length;
                    selectAllCheckbox.indeterminate = checkedRowBoxes.length > 0 && checkedRowBoxes.length < rowCheckboxes.length;
                }
            }
        });

        // Bulk overall status update functionality
        document.addEventListener('click', function(e) {
            if (e.target.closest('.bulk-overall-status-option')) {
                e.preventDefault();

                const statusOption = e.target.closest('.bulk-overall-status-option');
                const newStatus = statusOption.dataset.status;

                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Silakan pilih siswa yang akan diubah status keseluruhannya.',
                        confirmButtonColor: '#ffc107'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Perubahan Status',
                    text: `Yakin ingin mengubah status keseluruhan ${selectedIds.length} siswa terpilih menjadi "${newStatus}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Ubah Status!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performBulkOverallStatusUpdate(selectedIds, newStatus);
                    }
                });
            }
        });

        // Function to perform bulk overall status update
        function performBulkOverallStatusUpdate(selectedIds, newStatus) {
            Swal.fire({
                title: 'Memperbarui Status...',
                text: `Sedang mengubah status keseluruhan ${selectedIds.length} siswa menjadi "${newStatus}"`,
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Use AJAX for bulk update
            fetch(`${window.adminRoutes.baseUrl}/bulk-update-overall-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    ids: selectedIds,
                    overall_status: newStatus
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP error! status: ${response.status} - ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    title: 'Berhasil!',
                    text: `Status keseluruhan ${selectedIds.length} siswa berhasil diubah menjadi "${newStatus}".`,
                    icon: 'success',
                    confirmButtonColor: '#198754'
                }).then(() => {
                    // Clear selection and refresh page
                    clearSelection();
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                });
            })
            .catch(error => {
                console.error('Error updating bulk overall status:', error);

                let errorMessage = 'Terjadi kesalahan saat memperbarui status keseluruhan';
                if (error.message.includes('422')) {
                    errorMessage = 'Data yang dikirim tidak valid. Pastikan data yang dipilih masih tersedia.';
                } else if (error.message.includes('403')) {
                    errorMessage = 'Anda tidak memiliki izin untuk melakukan aksi ini.';
                } else if (error.message.includes('500')) {
                    errorMessage = 'Terjadi kesalahan server. Silakan coba lagi nanti.';
                }

                Swal.fire({
                    title: 'Gagal!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            });
        }

        // Re-initialize bulk actions after AJAX content updates
        function reinitializeBulkActions() {
            // Re-attach event listeners for new content
            toggleBulkActions();
        }

        // Call reinitializeBulkActions after table updates
        const originalPerformSearch = performSearch;
        performSearch = function(query) {
            return originalPerformSearch(query).then(() => {
                reinitializeBulkActions();
            });
        };
    });
    </script>

    <!-- Student Status Management Modal -->
    <div class="modal fade" id="studentStatusModal" tabindex="-1" aria-labelledby="studentStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentStatusModalLabel">
                        <i class="bi bi-person-gear me-2"></i>Kelola Status Siswa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="statusForm">
                    <div class="modal-body">
                        <input type="hidden" id="statusStudentId" name="student_id">

                        <!-- Student Info -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="bi bi-person text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1" id="statusStudentName">-</h6>
                                                <small class="text-muted">
                                                    <span id="statusStudentNumber">-</span> • <span id="statusStudentUnit">-</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Selection -->
                        <div class="mb-3">
                            <label for="studentStatus" class="form-label fw-semibold">Status Siswa</label>
                            <select class="form-select" id="studentStatus" name="student_status" required>
                                <option value="inactive">Belum Aktif</option>
                                <option value="active">Aktif</option>
                                <option value="graduated">Lulus</option>
                                <option value="dropped_out">Keluar</option>
                                <option value="transferred">Pindah</option>
                            </select>
                            <div class="form-text">Pilih status terkini siswa</div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label for="studentStatusNotes" class="form-label fw-semibold">Catatan</label>
                            <textarea class="form-control" id="studentStatusNotes" name="student_status_notes"
                                      rows="3" placeholder="Tambahkan catatan atau keterangan (opsional)"></textarea>
                            <div class="form-text">Catatan akan membantu tracking perubahan status</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Perbarui Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
