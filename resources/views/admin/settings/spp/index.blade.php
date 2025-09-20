<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-0">
                    <i class="bi bi-calendar-month me-2 text-info"></i>
                    Manajemen SPP
                </h2>
                <p class="text-muted small mb-0">Kelola tarif SPP berdasarkan asal sekolah</p>
            </div>
            <div class="d-flex gap-2">
                <button onclick="refreshData()" class="btn btn-outline-secondary" title="Refresh Data">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <a href="{{ route('admin.settings.spp.create') }}" class="btn btn-info">
                    <i class="bi bi-plus-circle me-1"></i>Tambah SPP
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Alert Messages -->
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Sukses!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-down">
                <i class="bi bi-exclamation-circle me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
                        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">Total SPP</h6>
                                <h4 class="fw-bold mb-0" id="totalSppCount">{{ $totalSpp }}</h4>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-calendar-month text-info fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">SPP Aktif</h6>
                                <h4 class="fw-bold mb-0 text-success">{{ $activeSpp }}</h4>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-check-circle text-success fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">SPP Nonaktif</h6>
                                <h4 class="fw-bold mb-0 text-warning">{{ $inactiveSpp }}</h4>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-pause-circle text-warning fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">Rata-rata SPP</h6>
                                <h4 class="fw-bold mb-0 text-info">
                                    Rp {{ $avgAmount ? number_format($avgAmount, 0, ',', '.') : '0' }}
                                </h4>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-calculator text-info fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SPP Data Table -->
        <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="500">
            <div class="card-header bg-white border-bottom py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-list-ul me-2 text-primary"></i>
                            Daftar SPP
                        </h5>
                    </div>
                    <div class="col-auto">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Cari SPP..." id="searchInput" value="{{ request('search') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold">#</th>
                                <th class="border-0 fw-semibold">Nama SPP</th>
                                <th class="border-0 fw-semibold">Jenjang</th>
                                <th class="border-0 fw-semibold">Asal Sekolah</th>
                                <th class="border-0 fw-semibold">Nominal</th>
                                <th class="border-0 fw-semibold">Status</th>
                                <th class="border-0 fw-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sppTableBody">
                            @include('admin.settings.spp.partials.spp-table', ['sppSettings' => $sppSettings])
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        @if($sppSettings->count() > 0)
                            Menampilkan {{ $sppSettings->firstItem() }}-{{ $sppSettings->lastItem() }} dari {{ $sppSettings->total() }} data
                        @else
                            Tidak ada data
                        @endif
                    </small>
                    @if($sppSettings->hasPages())
                        {{ $sppSettings->links() }}
                    @endif
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
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-group .btn {
            border-radius: 0.375rem;
            margin-right: 2px;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375em 0.75em;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }
    </style>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Main JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true
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
            const tableBody = document.getElementById('sppTableBody');
            if (tableBody) {
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';
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
                const paginationContainer = document.getElementById('sppPagination');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }

                // Update statistics
                if (data.statistics) {
                    const totalElement = document.getElementById('totalSppCount');
                    const activeElement = document.getElementById('activeSppCount');
                    const inactiveElement = document.getElementById('inactiveSppCount');
                    const avgElement = document.getElementById('avgAmountDisplay');

                    if (totalElement) totalElement.textContent = data.statistics.totalSpp;
                    if (activeElement) activeElement.textContent = data.statistics.activeSpp;
                    if (inactiveElement) inactiveElement.textContent = data.statistics.inactiveSpp;
                    if (avgElement) {
                        avgElement.innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.statistics.avgAmount || 0);
                    }
                }

                // Re-attach event listeners for new content
                attachDeleteListeners();

                // Update URL without page reload
                history.pushState(null, '', currentUrl.toString());
            })
            .catch(error => {
                console.error('Search error:', error);
                if (tableBody) {
                    tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-danger">Terjadi kesalahan saat mencari data</td></tr>';
                }
            });
        }

        function attachDeleteListeners() {
            // Re-attach delete button event listeners
            document.querySelectorAll('[onclick^="deleteSpp"]').forEach(button => {
                const onclickAttr = button.getAttribute('onclick');
                const sppId = onclickAttr.match(/deleteSpp\((\d+)\)/)[1];

                // Remove old onclick and add new event listener
                button.removeAttribute('onclick');
                button.addEventListener('click', () => deleteSpp(sppId));
            });
        }

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

        // Refresh data function
        window.refreshData = function() {
            location.reload();
        }

        // Delete SPP function with enhanced UX
        window.deleteSpp = function(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Yakin ingin menghapus data SPP ini? Data yang sudah dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'animated fadeInDown faster'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading with progress
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Sedang memproses permintaan',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'animated fadeIn faster'
                        },
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Perform AJAX delete
                    fetch(`/admin/settings/spp/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message || 'Data SPP berhasil dihapus.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'animated fadeInUp faster'
                                }
                            }).then(() => {
                                // Smooth page reload
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: data.message || 'Terjadi kesalahan saat menghapus data SPP.',
                                icon: 'error',
                                customClass: {
                                    popup: 'animated shakeX faster'
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan pada server. Silakan coba lagi.',
                            icon: 'error',
                            customClass: {
                                popup: 'animated shakeX faster'
                            }
                        });
                    });
                }
            });
        }

        // Add tooltip for action buttons
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        if (typeof bootstrap !== 'undefined') {
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });
    </script>
</x-app-layout>
