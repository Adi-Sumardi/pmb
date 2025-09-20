<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-percent me-2 text-warning"></i>
                    Manajemen Diskon
                </h2>
                <p class="text-muted small mb-0">Kelola berbagai jenis diskon untuk pendaftaran siswa baru</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.settings.discounts.create') }}" class="btn btn-warning">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Diskon
                </a>
                <button class="btn btn-outline-secondary" onclick="refreshData()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Alert Messages -->
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Sukses!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                    <i class="bi bi-percent text-warning fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small">Total Diskon</div>
                                <div class="fs-4 fw-bold text-warning" id="totalDiscountsCount">{{ $totalDiscounts ?? 0 }}</div>
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
                                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                    <i class="bi bi-check-circle text-success fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small">Aktif</div>
                                <div class="fs-4 fw-bold text-success" id="activeDiscountsCount">{{ $activeDiscounts ?? 0 }}</div>
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
                                <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                                    <i class="bi bi-x-circle text-danger fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small">Tidak Aktif</div>
                                <div class="fs-4 fw-bold text-danger" id="inactiveDiscountsCount">{{ $inactiveDiscounts ?? 0 }}</div>
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
                                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                    <i class="bi bi-graph-up text-info fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small">Total Nilai Diskon</div>
                                <div class="fs-4 fw-bold text-info" id="totalDiscountValue">
                                    Rp {{ number_format($totalDiscountValue ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Data Table -->
        <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="500">
            <div class="card-header bg-white border-bottom py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-table me-2 text-primary"></i>
                            Daftar Diskon
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end gap-2">
                            <div class="input-group" style="max-width: 300px;">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Cari diskon..." id="searchInput" value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">No</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">Nama Diskon</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">Jenis</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">Nilai</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">Target</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">Periode</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">Status</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="discountTableBody">
                            @include('admin.settings.discounts.partials.discount-table', ['discounts' => $discounts])
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-white border-top" id="discountPagination">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            @if($discounts->count() > 0)
                                Menampilkan {{ $discounts->firstItem() }}-{{ $discounts->lastItem() }} dari {{ $discounts->total() }} data
                            @else
                                Tidak ada data
                            @endif
                        </div>
                        @if($discounts->hasPages())
                            {{ $discounts->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .card {
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
            const tableBody = document.getElementById('discountTableBody');
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
                const paginationContainer = document.getElementById('discountPagination');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }

                // Update statistics
                if (data.statistics) {
                    const totalElement = document.getElementById('totalDiscountsCount');
                    const activeElement = document.getElementById('activeDiscountsCount');
                    const inactiveElement = document.getElementById('inactiveDiscountsCount');
                    const totalValueElement = document.getElementById('totalDiscountValue');

                    if (totalElement) totalElement.textContent = data.statistics.totalDiscounts;
                    if (activeElement) activeElement.textContent = data.statistics.activeDiscounts;
                    if (inactiveElement) inactiveElement.textContent = data.statistics.inactiveDiscounts;
                    if (totalValueElement) {
                        totalValueElement.innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.statistics.totalDiscountValue);
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
                    tableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-danger">Terjadi kesalahan saat mencari data</td></tr>';
                }
            });
        }

        function attachDeleteListeners() {
            // Re-attach delete button event listeners
            document.querySelectorAll('[onclick^="deleteDiscount"]').forEach(button => {
                const onclickAttr = button.getAttribute('onclick');
                const discountId = onclickAttr.match(/deleteDiscount\((\d+)\)/)[1];

                // Remove old onclick and add new event listener
                button.removeAttribute('onclick');
                button.addEventListener('click', () => deleteDiscount(discountId));
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

        // Delete discount function
        window.deleteDiscount = function(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Yakin ingin menghapus diskon ini? Data yang sudah dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Sedang memproses permintaan',
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Perform AJAX delete
                    fetch(`/admin/settings/discounts/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message || 'Diskon berhasil dihapus.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload the page
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: data.message || 'Terjadi kesalahan saat menghapus diskon.',
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan pada server.',
                            icon: 'error'
                        });
                    });
                }
            });
        }
    });
    </script>
</x-app-layout>
