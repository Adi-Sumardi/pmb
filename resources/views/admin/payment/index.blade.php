<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-credit-card-2-front me-2 text-primary"></i>
                Data Pembayaran
            </h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" onclick="refreshData()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                </button>
                <button class="btn btn-success btn-sm" onclick="exportData()">
                    <i class="bi bi-download me-1"></i>Export Excel
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Statistics Cards Row -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">
                                    Total Pendaftar
                                </div>
                                <div class="display-6 fw-bold text-primary mb-2 counter" data-target="{{ $pendaftars->count() }}">0</div>
                                <div class="d-flex align-items-center text-success small">
                                    <i class="bi bi-arrow-up me-1"></i>
                                    <span class="fw-semibold">+12%</span>
                                    <span class="ms-1 text-muted">bulan ini</span>
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

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">
                                    Sudah Bayar
                                </div>
                                <div class="display-6 fw-bold text-success mb-2 counter" data-target="{{ $pendaftars->where('sudah_bayar_formulir', true)->count() }}">0</div>
                                <div class="progress progress-sm mt-2">
                                    <div class="progress-bar bg-success" role="progressbar"
                                         style="width: {{ $pendaftars->count() > 0 ? ($pendaftars->where('sudah_bayar_formulir', true)->count() / $pendaftars->count()) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-check-circle-fill text-success fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">
                                    Belum Bayar
                                </div>
                                <div class="display-6 fw-bold text-warning mb-2 counter" data-target="{{ $pendaftars->where('sudah_bayar_formulir', false)->count() }}">0</div>
                                <div class="progress progress-sm mt-2">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                         style="width: {{ $pendaftars->count() > 0 ? ($pendaftars->where('sudah_bayar_formulir', false)->count() / $pendaftars->count()) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-clock text-warning fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 stat-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase tracking-wide mb-1">
                                    Total Revenue
                                </div>
                                @php
                                    $totalRevenue = \App\Models\StudentBill::where('bill_type', 'registration_fee')
                                        ->where('payment_status', 'paid')
                                        ->sum('total_amount');
                                    $totalRevenueInThousands = $totalRevenue / 1000;
                                @endphp
                                <div class="display-6 fw-bold text-info mb-2 counter" data-target="{{ $totalRevenueInThousands }}">0</div>
                                <div class="text-muted small">
                                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="bi bi-currency-dollar text-info fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="500">
            <div class="card-header bg-white border-bottom">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="card-title mb-0 fw-bold">
                            <i class="bi bi-table me-2 text-primary"></i>Data Pembayaran
                        </h6>
                    </div>
                    <div class="col-auto">
                        <!-- Search and Filter -->
                        <div class="d-flex gap-2">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" class="form-control" placeholder="Cari nama atau nomor..." id="searchInput">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <select class="form-select form-select-sm" id="unitFilter" style="width: 150px;">
                                <option value="">Semua Unit</option>
                                <option value="TK">TK</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <!-- Filter Tabs -->
                <ul class="nav nav-tabs nav-tabs-custom border-bottom-0" id="paymentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                            <i class="bi bi-list-ul me-1"></i>
                            Semua (<span id="countAll">{{ $pendaftars->count() }}</span>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="paid-tab" data-bs-toggle="tab" data-bs-target="#paid" type="button" role="tab">
                            <i class="bi bi-check-circle me-1 text-success"></i>
                            Sudah Bayar (<span id="countPaid">{{ $pendaftars->where('sudah_bayar_formulir', true)->count() }}</span>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="unpaid-tab" data-bs-toggle="tab" data-bs-target="#unpaid" type="button" role="tab">
                            <i class="bi bi-clock me-1 text-warning"></i>
                            Belum Bayar (<span id="countUnpaid">{{ $pendaftars->where('sudah_bayar_formulir', false)->count() }}</span>)
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="paymentTabsContent">
                    <!-- All Tab -->
                    <div class="tab-pane fade show active" id="all" role="tabpanel">
                        @include('payment.admin.table', ['pendaftars' => $pendaftars, 'tableId' => 'tableAll'])
                    </div>

                    <!-- Paid Tab -->
                    <div class="tab-pane fade" id="paid" role="tabpanel">
                        @include('payment.admin.table', ['pendaftars' => $pendaftars->where('sudah_bayar_formulir', true), 'tableId' => 'tablePaid'])
                    </div>

                    <!-- Unpaid Tab -->
                    <div class="tab-pane fade" id="unpaid" role="tabpanel">
                        @include('payment.admin.table', ['pendaftars' => $pendaftars->where('sudah_bayar_formulir', false), 'tableId' => 'tableUnpaid'])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pembayaran -->
    <div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-labelledby="paymentDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentDetailModalLabel">
                        <i class="bi bi-receipt me-2"></i>Detail Pembayaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="paymentDetailContent">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="printReceipt()">
                        <i class="bi bi-printer me-1"></i>Print
                    </button>
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

        .progress-sm {
            height: 0.375rem;
        }

        .nav-tabs-custom {
            background-color: #f8f9fa;
            padding: 0.5rem 1rem 0;
        }

        .nav-tabs-custom .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 0.75rem 1rem;
            margin-bottom: -1px;
            background-color: transparent;
            border-radius: 0.375rem 0.375rem 0 0;
            transition: all 0.2s;
        }

        .nav-tabs-custom .nav-link:hover {
            color: #495057;
            background-color: #e9ecef;
        }

        .nav-tabs-custom .nav-link.active {
            color: #0d6efd;
            background-color: white;
            border-bottom: 2px solid #0d6efd;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.025);
        }

        .tracking-wide {
            letter-spacing: 0.05em;
        }

        .counter {
            font-family: 'Inter', sans-serif;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .status-paid {
            background-color: #198754;
            box-shadow: 0 0 0 2px rgba(25, 135, 84, 0.2);
        }

        .status-unpaid {
            background-color: #ffc107;
            box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.2);
        }

        .avatar-sm {
            height: 2rem;
            width: 2rem;
        }

        .avatar-title {
            align-items: center;
            background-color: #556ee6;
            color: #fff;
            display: flex;
            font-weight: 500;
            height: 100%;
            justify-content: center;
            width: 100%;
        }

        .bg-soft-primary {
            background-color: rgba(85, 110, 230, 0.1) !important;
        }
    </style>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Add DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Add DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- Add SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });

            // Initialize DataTables for all tabs
            initializeDataTables();

            // Search functionality
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                filterTable(value);
            });

            // Unit filter
            $('#unitFilter').on('change', function() {
                var unit = $(this).val();
                filterByUnit(unit);
            });

            // Auto refresh every 30 seconds
            setInterval(function() {
                updateStatistics();
            }, 30000);

            // Tab change animation
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($(e.target).attr('data-bs-target')).addClass('animate__animated animate__fadeIn');
            });

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
        });

        function initializeDataTables() {
            $('.data-table').each(function() {
                $(this).DataTable({
                    responsive: true,
                    pageLength: 10,
                    order: [[0, 'desc']],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
                    },
                    columnDefs: [
                        { orderable: false, targets: -1 }
                    ],
                    drawCallback: function() {
                        // Re-initialize tooltips after table draw
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    }
                });
            });
        }

        function filterTable(value) {
            $('.data-table').each(function() {
                var table = $(this).DataTable();
                table.search(value).draw();
            });
        }

        function filterByUnit(unit) {
            $('.data-table').each(function() {
                var table = $(this).DataTable();
                table.column(3).search(unit).draw();
            });
        }

        function refreshData() {
            // Show loading
            Swal.fire({
                title: 'Memuat ulang data...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Reload page after 1 second
            setTimeout(function() {
                location.reload();
            }, 1000);
        }

        function exportData() {
            Swal.fire({
                title: 'Export Data',
                text: 'Pilih format export yang diinginkan',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                cancelButtonText: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Export to Excel
                    window.location.href = '/admin/payment/export/excel';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Export to PDF
                    window.location.href = '/admin/payment/export/pdf';
                }
            });
        }

        function viewPaymentDetail(pendaftarId) {
            // Show loading in modal
            $('#paymentDetailContent').html(`
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat detail pembayaran...</p>
                </div>
            `);

            // Show modal
            $('#paymentDetailModal').modal('show');

            // Load detail via AJAX
            $.get(`/admin/payment/detail/${pendaftarId}`)
                .done(function(data) {
                    $('#paymentDetailContent').html(data);
                })
                .fail(function() {
                    $('#paymentDetailContent').html(`
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Gagal memuat detail pembayaran.
                        </div>
                    `);
                });
        }

        function updateStatistics() {
            $.get('/admin/payment/statistics')
                .done(function(data) {
                    $('#countAll').text(data.total);
                    $('#countPaid').text(data.paid);
                    $('#countUnpaid').text(data.unpaid);
                });
        }

        function printReceipt() {
            window.print();
        }

        function markAsPaid(pendaftarId) {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Tandai pembayaran sebagai lunas?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tandai Lunas',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX request to mark as paid
                    $.post(`/admin/payment/mark-paid/${pendaftarId}`, {
                        _token: '{{ csrf_token() }}'
                    })
                    .done(function() {
                        Swal.fire('Berhasil!', 'Pembayaran telah ditandai lunas.', 'success');
                        setTimeout(() => location.reload(), 1500);
                    })
                    .fail(function() {
                        Swal.fire('Error!', 'Gagal memperbarui status pembayaran.', 'error');
                    });
                }
            });
        }

        // Initialize tooltips
        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        // Show success message if payment status updated
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
</x-app-layout>
