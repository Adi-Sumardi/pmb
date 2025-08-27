{{-- filepath: /Users/yapi/project/ppdb-backend/resources/views/transactions/admin/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-credit-card-2-front me-2 text-primary"></i>
                    Kelola Transaksi Pembayaran
                </h2>
                <p class="text-muted small mb-0">Monitoring dan pengelolaan transaksi pembayaran formulir PPDB</p>
            </div>
            <div class="d-flex gap-2">
                <button onclick="exportTransactions()" class="btn btn-success" data-bs-toggle="tooltip" title="Export data transaksi ke Excel">
                    <i class="bi bi-file-excel-fill me-1"></i>Export Excel
                </button>
                <button onclick="refreshData()" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Refresh data terbaru">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Statistics Cards with Animation -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                    <i class="bi bi-receipt text-primary fs-3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small fw-semibold text-uppercase mb-1">Total Transaksi</div>
                                <div class="display-6 fw-bold text-primary counter" data-target="{{ $stats['total_transactions'] }}">0</div>
                                <div class="text-muted small">
                                    <i class="bi bi-graph-up-arrow text-success me-1"></i>
                                    Semua transaksi
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small fw-semibold text-uppercase mb-1">Pembayaran Lunas</div>
                                <div class="display-6 fw-bold text-success counter" data-target="{{ $stats['paid_transactions'] }}">0</div>
                                <div class="text-muted small">
                                    <span class="badge bg-success bg-opacity-20 text-success">
                                        {{ $stats['total_transactions'] > 0 ? round(($stats['paid_transactions'] / $stats['total_transactions']) * 100, 1) : 0 }}%
                                    </span> dari total
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                    <i class="bi bi-clock-fill text-warning fs-3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small fw-semibold text-uppercase mb-1">Menunggu Pembayaran</div>
                                <div class="display-6 fw-bold text-warning counter" data-target="{{ $stats['pending_transactions'] }}">0</div>
                                <div class="text-muted small">
                                    <span class="badge bg-warning bg-opacity-20 text-warning">
                                        {{ $stats['total_transactions'] > 0 ? round(($stats['pending_transactions'] / $stats['total_transactions']) * 100, 1) : 0 }}%
                                    </span> pending
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                    <i class="bi bi-currency-dollar text-info fs-3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small fw-semibold text-uppercase mb-1">Total Revenue</div>
                                <div class="fs-4 fw-bold text-info">
                                    Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                                </div>
                                <div class="text-muted small">
                                    <i class="bi bi-trending-up text-success me-1"></i>
                                    Revenue terkumpul
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filter Section -->
        <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="500">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-funnel me-2 text-primary"></i>Filter Transaksi
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.transactions.index') }}" class="row g-3">
                    <div class="col-lg-2 col-md-4">
                        <label for="status" class="form-label fw-semibold">Status Pembayaran</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">🔍 Semua Status</option>
                            <option value="PAID" {{ request('status') === 'PAID' ? 'selected' : '' }}>✅ Lunas</option>
                            <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>⏳ Menunggu</option>
                            <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>❌ Gagal</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="jenjang" class="form-label fw-semibold">Jenjang Pendidikan</label>
                        <select name="jenjang" id="jenjang" class="form-select">
                            <option value="">🎓 Semua Jenjang</option>
                            <option value="sanggar" {{ request('jenjang') === 'sanggar' ? 'selected' : '' }}>🧸 Sanggar</option>
                            <option value="kelompok" {{ request('jenjang') === 'kelompok' ? 'selected' : '' }}>👶 Kelompok</option>
                            <option value="tka" {{ request('jenjang') === 'tka' ? 'selected' : '' }}>🎪 TK A</option>
                            <option value="tkb" {{ request('jenjang') === 'tkb' ? 'selected' : '' }}>🎨 TK B</option>
                            <option value="sd" {{ request('jenjang') === 'sd' ? 'selected' : '' }}>📚 SD</option>
                            <option value="smp" {{ request('jenjang') === 'smp' ? 'selected' : '' }}>📖 SMP</option>
                            <option value="sma" {{ request('jenjang') === 'sma' ? 'selected' : '' }}>🎯 SMA</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="date_from" class="form-label fw-semibold">Dari Tanggal</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="date_to" class="form-label fw-semibold">Sampai Tanggal</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="search" class="form-label fw-semibold">Pencarian</label>
                        <input type="text" name="search" id="search" class="form-control"
                               placeholder="Nama atau No. Pendaftaran" value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label fw-semibold">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill" data-bs-toggle="tooltip" title="Terapkan filter">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Reset semua filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Transactions Table -->
        <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="600">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-table me-2 text-primary"></i>Daftar Transaksi
                    </h5>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-primary fs-6 px-3 py-2">
                            {{ $payments->total() }} transaksi ditemukan
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="printTable()">
                                    <i class="bi bi-printer me-2"></i>Print Tabel
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="exportTransactions()">
                                    <i class="bi bi-file-excel me-2"></i>Export Excel
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="refreshData()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Refresh Data
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="transactionsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th class="border-0 px-4 py-3" style="width: 5%;">
                                        <i class="bi bi-hash"></i>
                                    </th>
                                    <th class="border-0 px-4 py-3" style="width: 15%;">
                                        <i class="bi bi-credit-card me-1"></i>Transaction ID
                                    </th>
                                    <th class="border-0 px-4 py-3" style="width: 25%;">
                                        <i class="bi bi-person me-1"></i>Data Siswa
                                    </th>
                                    <th class="border-0 px-4 py-3" style="width: 10%;">
                                        <i class="bi bi-bookmark me-1"></i>Jenjang
                                    </th>
                                    <th class="border-0 px-4 py-3" style="width: 12%;">
                                        <i class="bi bi-currency-dollar me-1"></i>Amount
                                    </th>
                                    <th class="border-0 px-4 py-3" style="width: 10%;">
                                        <i class="bi bi-flag me-1"></i>Status
                                    </th>
                                    <th class="border-0 px-4 py-3" style="width: 13%;">
                                        <i class="bi bi-calendar me-1"></i>Tanggal
                                    </th>
                                    <th class="border-0 px-4 py-3 text-center" style="width: 10%;">
                                        <i class="bi bi-gear me-1"></i>Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $index => $payment)
                                <tr class="table-row" data-aos="fade-up" data-aos-delay="{{ 100 + ($index * 50) }}">
                                    <td class="px-4 fw-semibold text-muted">
                                        {{ $payments->firstItem() + $index }}
                                    </td>
                                    <td class="px-4">
                                        <div class="d-flex flex-column">
                                            <code class="bg-light px-2 py-1 rounded text-primary fw-bold small">
                                                {{ $payment->external_id }}
                                            </code>
                                            <small class="text-muted mt-1">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $payment->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-circle bg-gradient-primary d-flex align-items-center justify-content-center text-white"
                                                     style="width: 45px; height: 45px;">
                                                    <i class="bi bi-person fs-5"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $payment->pendaftar->nama_murid }}</h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-card-text me-1"></i>
                                                    {{ $payment->pendaftar->no_pendaftaran }}
                                                </small>
                                                <br>
                                                <span class="badge bg-info bg-gradient text-white small">
                                                    {{ $payment->pendaftar->unit }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4">
                                        <span class="badge bg-primary bg-gradient px-3 py-2 rounded-pill">
                                            {{ strtoupper($payment->pendaftar->jenjang) }}
                                        </span>
                                    </td>
                                    <td class="px-4">
                                        <div class="fw-bold text-success fs-5">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </div>
                                        <small class="text-muted">Uang Formulir</small>
                                    </td>
                                    <td class="px-4">
                                        @if($payment->status === 'PAID')
                                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                                <i class="bi bi-check-circle-fill me-1"></i>Lunas
                                            </span>
                                        @elseif($payment->status === 'PENDING')
                                            <span class="badge bg-warning px-3 py-2 rounded-pill">
                                                <i class="bi bi-clock-fill me-1"></i>Pending
                                            </span>
                                        @elseif($payment->status === 'FAILED')
                                            <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                <i class="bi bi-x-circle-fill me-1"></i>Gagal
                                            </span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                                {{ $payment->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4">
                                        <div class="fw-semibold">{{ $payment->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $payment->created_at->format('H:i') }} WIB</small>
                                        @if($payment->paid_at)
                                            <br>
                                            <small class="text-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Lunas: {{ $payment->paid_at->format('d/m/Y H:i') }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="px-4 text-center">
                                        <div class="btn-group" role="group">
                                            <!-- Detail Button -->
                                            <a href="{{ route('admin.transactions.show', $payment->id) }}"
                                               class="btn btn-outline-info btn-sm"
                                               data-bs-toggle="tooltip"
                                               data-bs-placement="top"
                                               title="Lihat detail lengkap transaksi">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- Confirm Payment Button (hanya untuk status PENDING) -->
                                            @if($payment->status === 'PENDING')
                                                <button onclick="confirmPayment({{ $payment->id }}, '{{ $payment->pendaftar->nama_murid }}')"
                                                        class="btn btn-outline-success btn-sm"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Konfirmasi pembayaran secara manual">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            @endif

                                            <!-- Invoice Button -->
                                            @if($payment->invoice_url)
                                                <a href="{{ $payment->invoice_url }}"
                                                   class="btn btn-outline-primary btn-sm"
                                                   target="_blank"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-placement="top"
                                                   title="Buka invoice di tab baru">
                                                    <i class="bi bi-receipt"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i>
                                Menampilkan <span class="fw-bold text-primary">{{ $payments->firstItem() }}</span> -
                                <span class="fw-bold text-primary">{{ $payments->lastItem() }}</span>
                                dari <span class="fw-bold">{{ $payments->total() }}</span> transaksi
                            </div>
                            <div>
                                {{ $payments->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Enhanced Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-receipt text-muted opacity-50" style="font-size: 5rem;"></i>
                        </div>
                        <h4 class="text-muted fw-bold mb-3">Tidak Ada Transaksi Ditemukan</h4>
                        <p class="text-muted mb-4">
                            Belum ada transaksi yang sesuai dengan filter yang dipilih.<br>
                            Coba ubah filter atau hapus beberapa kriteria pencarian.
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
                            </a>
                            <button onclick="refreshData()" class="btn btn-primary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh Data
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Enhanced Confirmation Modal -->
    <div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient-primary text-white border-0">
                    <h5 class="modal-title fw-bold" id="confirmPaymentModalLabel">
                        <i class="bi bi-check-circle me-2"></i>Konfirmasi Pembayaran Manual
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 d-inline-flex">
                            <i class="bi bi-exclamation-triangle text-warning fs-2"></i>
                        </div>
                    </div>
                    <h6 class="text-center mb-3">Konfirmasi Pembayaran untuk:</h6>
                    <div class="alert alert-light border">
                        <div class="fw-bold" id="studentName">-</div>
                        <small class="text-muted">Pastikan pembayaran sudah benar-benar diterima</small>
                    </div>
                    <div class="alert alert-warning border-warning">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Perhatian:</strong> Aksi ini akan mengubah status pembayaran menjadi <strong>LUNAS</strong>
                        dan akan mengirim notifikasi konfirmasi kepada orang tua. Aksi ini tidak dapat dibatalkan.
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-success" id="confirmPaymentBtn">
                        <i class="bi bi-check-circle me-1"></i>Ya, Konfirmasi Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="successToast" class="toast" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong class="me-auto">Berhasil</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Pembayaran berhasil dikonfirmasi!
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        .table-row {
            transition: all 0.3s ease;
        }

        .table-row:hover {
            background-color: #f8f9fa !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .table th {
            font-weight: 600;
            font-size: 0.875rem;
            vertical-align: middle;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle;
            border-color: #f1f3f4;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
        }

        code {
            background-color: #f8f9fa;
            padding: 0.4rem 0.6rem;
            border-radius: 0.375rem;
            font-size: 0.8rem;
            border: 1px solid #e9ecef;
        }

        .btn-group .btn {
            border-radius: 0.375rem !important;
            margin-right: 3px;
            transition: all 0.3s ease;
        }

        .btn-group .btn:hover {
            transform: scale(1.05);
        }

        .counter {
            font-family: 'Inter', sans-serif;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .modal-content {
            border-radius: 1rem;
        }

        .toast {
            border-radius: 0.75rem;
        }

        @media (max-width: 768px) {
            .btn-group {
                flex-direction: column;
            }

            .btn-group .btn {
                margin-right: 0;
                margin-bottom: 2px;
            }
        }
    </style>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Enhanced JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });

        let selectedPaymentId = null;
        let selectedStudentName = null;

        function confirmPayment(paymentId, studentName) {
            selectedPaymentId = paymentId;
            selectedStudentName = studentName;

            document.getElementById('studentName').textContent = studentName;

            const modal = new bootstrap.Modal(document.getElementById('confirmPaymentModal'));
            modal.show();
        }

        document.getElementById('confirmPaymentBtn').addEventListener('click', function() {
            if (selectedPaymentId) {
                // Show loading state
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                this.disabled = true;

                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/transactions/${selectedPaymentId}/confirm`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                document.body.appendChild(form);
                form.submit();
            }
        });

        function refreshData() {
            // Show loading state
            const refreshBtn = document.querySelector('[onclick="refreshData()"]');
            const originalContent = refreshBtn.innerHTML;
            refreshBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Loading...';
            refreshBtn.disabled = true;

            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }

        function exportTransactions() {
            // Show loading state
            const exportBtn = document.querySelector('[onclick="exportTransactions()"]');
            const originalContent = exportBtn.innerHTML;
            exportBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Exporting...';
            exportBtn.disabled = true;

            // Simulate export process
            setTimeout(() => {
                alert('Export Excel akan segera tersedia!');
                exportBtn.innerHTML = originalContent;
                exportBtn.disabled = false;
            }, 2000);
        }

        function printTable() {
            window.print();
        }

        function showToast(message) {
            const toastEl = document.getElementById('successToast');
            const toastBody = toastEl.querySelector('.toast-body');
            toastBody.textContent = message;

            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }

        // Auto-close alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                if (alert.classList.contains('show')) {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 300);
                }
            });
        }, 5000);
    </script>
</x-app-layout>
