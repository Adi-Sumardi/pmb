<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-credit-card me-2 text-primary"></i>
                    Pengaturan Cicilan
                </h2>
                <p class="text-muted small mb-0">Kelola pengaturan cicilan uang pangkal</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.settings.installments.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Pengaturan
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" data-aos="fade-down" data-aos-duration="500">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" data-aos="fade-down" data-aos-duration="500">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">Total Pengaturan</h6>
                                <h4 class="fw-bold mb-0" id="totalInstallmentsCount">{{ $totalInstallments }}</h4>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-credit-card text-primary fs-5"></i>
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
                                <h6 class="text-muted fw-normal mb-1">Aktif</h6>
                                <h4 class="fw-bold mb-0 text-success" id="activeInstallmentsCount">{{ $activeInstallments }}</h4>
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
                                <h6 class="text-muted fw-normal mb-1">Nonaktif</h6>
                                <h4 class="fw-bold mb-0 text-warning" id="inactiveInstallmentsCount">{{ $inactiveInstallments }}</h4>
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
                                <h6 class="text-muted fw-normal mb-1">Rata-rata Cicilan</h6>
                                <h4 class="fw-bold mb-0 text-primary" id="avgInstallmentCountDisplay">{{ round($avgInstallmentCount) }}x</h4>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-calculator text-primary fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Installment Settings Table -->
        <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="500">
            <div class="card-header bg-white border-bottom py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-list-ul me-2 text-primary"></i>
                            Daftar Pengaturan Cicilan
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
                                   placeholder="Cari pengaturan..."
                                   value="{{ $search }}"
                                   autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0" id="tableContainer">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold">#</th>
                                <th class="border-0 fw-semibold">Nama Pengaturan</th>
                                <th class="border-0 fw-semibold">Jenjang</th>
                                <th class="border-0 fw-semibold">Jumlah Cicilan</th>
                                <th class="border-0 fw-semibold">Pembayaran Pertama</th>
                                <th class="border-0 fw-semibold">Status</th>
                                <th class="border-0 fw-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="installmentTableBody">
                            @include('admin.settings.installments.partials.table')
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top" id="paginationContainer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        @if($installments->count() > 0)
                            Menampilkan {{ $installments->firstItem() }} sampai {{ $installments->lastItem() }}
                            dari {{ $installments->total() }} data
                        @else
                            Tidak ada data untuk ditampilkan
                        @endif
                    </div>
                    <div id="installmentPagination">
                        {{ $installments->appends(['search' => $search])->links() }}
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

        // Delete functionality with SweetAlert
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Pengaturan cicilan "${name}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Send delete request
                        fetch(`/admin/settings/installments/${id}`, {
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
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Terjadi kesalahan');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus data',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                    }
                });
            });
        });

        // Live Search Functionality
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchValue = this.value;

                searchTimeout = setTimeout(() => {
                    performSearch(searchValue);
                }, 300);
            });
        }

        function performSearch(query) {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('search', query);

            if (!query) {
                currentUrl.searchParams.delete('search');
            }

            // Add loading state
            const tableBody = document.getElementById('installmentTableBody');
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
                const paginationContainer = document.getElementById('installmentPagination');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }

                // Update statistics
                if (data.statistics) {
                    const totalElement = document.getElementById('totalInstallmentsCount');
                    const activeElement = document.getElementById('activeInstallmentsCount');
                    const inactiveElement = document.getElementById('inactiveInstallmentsCount');
                    const avgElement = document.getElementById('avgInstallmentCountDisplay');

                    if (totalElement) totalElement.textContent = data.statistics.totalInstallments;
                    if (activeElement) activeElement.textContent = data.statistics.activeInstallments;
                    if (inactiveElement) inactiveElement.textContent = data.statistics.inactiveInstallments;
                    if (avgElement) avgElement.textContent = Math.round(data.statistics.avgInstallmentCount) + 'x';
                }

                // Re-attach event listeners for new content
                attachDeleteListeners();

                // Re-initialize tooltips for new content
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

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
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: `Pengaturan cicilan "${name}" akan dihapus permanen!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Menghapus...',
                                text: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Send delete request
                            fetch(`/admin/settings/installments/${id}`, {
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
                                        text: data.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    throw new Error(data.message || 'Terjadi kesalahan');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat menghapus data',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            });
                        }
                    });
                });
            });
        }

        // Initial attachment of delete listeners
        attachDeleteListeners();
    });
    </script>
</x-app-layout>
