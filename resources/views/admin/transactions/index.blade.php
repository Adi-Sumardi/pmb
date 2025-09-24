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
                <div class="dropdown">
                    <button class="btn btn-success btn-sm rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-toggle="tooltip" title="Export data transaksi">
                        <i class="bi bi-file-earmark-arrow-down me-1"></i>Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li>
                            <a href="{{ route('admin.transactions.export', request()->query()) }}" class="dropdown-item">
                                <i class="bi bi-file-excel me-2 text-success"></i>Export Excel
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.transactions.export.pdf', request()->query()) }}" class="dropdown-item" target="_blank">
                                <i class="bi bi-file-pdf me-2 text-danger"></i>Export PDF
                            </a>
                        </li>
                    </ul>
                </div>
                <button onclick="refreshData()" class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="tooltip" title="Refresh data terbaru">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Statistics Cards with Animation -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 card-hover rounded-4" data-aos="fade-up" data-aos-delay="100">
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
                <div class="card border-0 shadow-sm h-100 card-hover rounded-4" data-aos="fade-up" data-aos-delay="200">
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
                <div class="card border-0 shadow-sm h-100 card-hover rounded-4" data-aos="fade-up" data-aos-delay="300">
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
                <div class="card border-0 shadow-sm h-100 card-hover rounded-4" data-aos="fade-up" data-aos-delay="400">
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
                                    @if(request()->hasAny(['date_from', 'date_to']))
                                        Revenue periode filter
                                    @else
                                        Revenue terkumpul
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filter Section -->
        <div class="card border-0 shadow-sm mb-4 rounded-4" data-aos="fade-up" data-aos-delay="500">
            <div class="card-header bg-white border-bottom py-3 rounded-top-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-funnel me-2 text-primary"></i>Filter Transaksi
                    </h5>
                    @if(request()->hasAny(['status', 'payment_type', 'jenjang', 'date_from', 'date_to', 'search']))
                        <div class="d-flex gap-1">
                            @if(request('status'))
                                <span class="badge bg-primary">Status: {{ request('status') }}</span>
                            @endif
                            @if(request('payment_type'))
                                <span class="badge bg-warning">Jenis: {{ ucwords(str_replace('_', ' ', request('payment_type'))) }}</span>
                            @endif
                            @if(request('jenjang'))
                                <span class="badge bg-info">Jenjang: {{ strtoupper(request('jenjang')) }}</span>
                            @endif
                            @if(request('date_from') || request('date_to'))
                                <span class="badge bg-success">
                                    Periode: {{ request('date_from') ?: 'Awal' }} - {{ request('date_to') ?: 'Sekarang' }}
                                </span>
                            @endif
                            @if(request('search'))
                                <span class="badge bg-warning">Pencarian: {{ Str::limit(request('search'), 20) }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.transactions.index') }}" class="row g-3" id="filterForm">
                    <div class="col-lg-2 col-md-4">
                        <label for="status" class="form-label fw-semibold">Status Pembayaran</label>
                        <select name="status" id="status" class="form-select rounded-pill">
                            <option value="">🔍 Semua Status</option>
                            <option value="PAID" {{ request('status') === 'PAID' ? 'selected' : '' }}>✅ Lunas</option>
                            <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>⏳ Menunggu</option>
                            <option value="FAILED" {{ request('status') === 'FAILED' ? 'selected' : '' }}>❌ Gagal</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="payment_type" class="form-label fw-semibold">Jenis Pembayaran</label>
                        <select name="payment_type" id="payment_type" class="form-select rounded-pill">
                            <option value="">💰 Semua Jenis</option>
                            <option value="formulir" {{ request('payment_type') === 'formulir' ? 'selected' : '' }}>📄 Formulir Pendaftaran</option>
                            <option value="spp" {{ request('payment_type') === 'spp' ? 'selected' : '' }}>🏫 SPP</option>
                            <option value="uang_pangkal" {{ request('payment_type') === 'uang_pangkal' ? 'selected' : '' }}>💎 Uang Pangkal</option>
                            <option value="uniform" {{ request('payment_type') === 'uniform' ? 'selected' : '' }}>👕 Seragam</option>
                            <option value="books" {{ request('payment_type') === 'books' ? 'selected' : '' }}>📚 Buku</option>
                            <option value="activity" {{ request('payment_type') === 'activity' ? 'selected' : '' }}>🎯 Kegiatan</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="jenjang" class="form-label fw-semibold">Jenjang Pendidikan</label>
                        <select name="jenjang" id="jenjang" class="form-select rounded-pill">
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
                        <input type="date" name="date_from" id="date_from" class="form-control rounded-pill" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="date_to" class="form-label fw-semibold">Sampai Tanggal</label>
                        <input type="date" name="date_to" id="date_to" class="form-control rounded-pill" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="search" class="form-label fw-semibold">Pencarian</label>
                        <input type="text" name="search" id="search" class="form-control rounded-pill"
                               placeholder="Nama atau No. Pendaftaran" value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label fw-semibold">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill flex-fill" id="filterButton" data-bs-toggle="tooltip" title="Terapkan filter">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary rounded-pill" data-bs-toggle="tooltip" title="Reset semua filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Transactions Table -->
        <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="600">
            <div class="card-header bg-white border-bottom py-3 rounded-top-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-table me-2 text-primary"></i>Daftar Transaksi
                    </h5>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill">
                            {{ $payments->total() }} transaksi ditemukan
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.transactions.print', request()->query()) }}" target="_blank">
                                        <i class="bi bi-printer me-2 text-primary"></i>Print Tabel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.transactions.export', request()->query()) }}">
                                        <i class="bi bi-file-excel me-2 text-success"></i>Export Excel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.transactions.export.pdf', request()->query()) }}" target="_blank">
                                        <i class="bi bi-file-pdf me-2 text-danger"></i>Export PDF
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="refreshData()">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Refresh Data
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="transactionsTable">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th class="border-0 px-4 py-3 text-uppercase" style="width: 5%;">
                                        <i class="bi bi-hash"></i>
                                    </th>
                                    <th class="border-0 px-4 py-3 text-uppercase" style="width: 13%;">
                                        <i class="bi bi-credit-card me-1"></i>Transaction ID
                                    </th>
                                    <th class="border-0 px-4 py-3 text-uppercase" style="width: 20%;">
                                        <i class="bi bi-person me-1"></i>Data Siswa
                                    </th>
                                    <th class="border-0 px-4 py-3 text-uppercase" style="width: 10%;">
                                        <i class="bi bi-bookmark me-1"></i>Jenjang
                                    </th>
                                    <th class="border-0 px-4 py-3 text-uppercase" style="width: 12%;">
                                        <i class="bi bi-currency-dollar me-1"></i>Amount
                                    </th>
                                    <th class="border-0 px-4 py-3 text-uppercase" style="width: 12%;">
                                        <i class="bi bi-wallet2 me-1"></i>Metode Transfer
                                    </th>
                                    <th class="border-0 px-4 py-3 text-uppercase" style="width: 8%;">
                                        <i class="bi bi-flag me-1"></i>Status
                                    </th>
                                    <th class="border-0 px-4 py-3 text-uppercase" style="width: 12%;">
                                        <i class="bi bi-calendar me-1"></i>Tanggal
                                    </th>
                                    <th class="border-0 px-4 py-3 text-center text-uppercase" style="width: 8%;">
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
                                                <div class="avatar-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                                                     style="width: 45px; height: 45px; border-radius: 50%;">
                                                    {{ substr($payment->pendaftar->nama_murid, 0, 1) }}
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $payment->pendaftar->nama_murid }}</h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-card-text me-1"></i>
                                                    {{ $payment->pendaftar->no_pendaftaran }}
                                                </small>
                                                <br>
                                                <span class="badge bg-info bg-opacity-10 text-info small">
                                                    {{ $payment->pendaftar->unit }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4">
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                            {{ strtoupper($payment->pendaftar->jenjang) }}
                                        </span>
                                    </td>
                                    <td class="px-4">
                                        <div class="fw-bold text-success fs-5">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </div>
                                        <small class="text-muted">
                                            @if(isset($payment->studentBill))
                                                {{-- BillPayment - get type from StudentBill --}}
                                                @switch($payment->studentBill->bill_type)
                                                    @case('spp')
                                                        📚 SPP
                                                        @break
                                                    @case('uang_pangkal')
                                                        🏦 Uang Pangkal
                                                        @break
                                                    @case('seragam')
                                                        👕 Seragam
                                                        @break
                                                    @case('buku')
                                                        📖 Buku
                                                        @break
                                                    @case('kegiatan')
                                                        🎯 Kegiatan
                                                        @break
                                                    @default
                                                        💰 {{ ucwords(str_replace('_', ' ', $payment->studentBill->bill_type)) }}
                                                @endswitch
                                            @else
                                                {{-- Payment - formulir pendaftaran --}}
                                                📝 Uang Formulir
                                            @endif
                                        </small>
                                    </td>
                                    <!-- Payment method column -->
                                    <td class="px-4">
                                        @php
                                            $paymentMethod = '';
                                            $paymentChannel = '';
                                            $badgeClass = 'bg-light text-secondary';
                                            $icon = 'credit-card';

                                            // Extract payment method from xendit_response
                                            if (isset($payment->xendit_response['payment_method'])) {
                                                $paymentMethod = $payment->xendit_response['payment_method'];
                                                $paymentChannel = $payment->xendit_response['payment_channel'] ?? '';

                                                // Set icon and badge based on payment method
                                                if ($paymentMethod == 'EWALLET') {
                                                    $icon = 'wallet2';
                                                    $badgeClass = 'bg-info bg-opacity-10 text-info';
                                                } elseif ($paymentMethod == 'BANK_TRANSFER' || $paymentMethod == 'VIRTUAL_ACCOUNT') {
                                                    $icon = 'bank';
                                                    $badgeClass = 'bg-primary bg-opacity-10 text-primary';
                                                } elseif ($paymentMethod == 'QR_CODE' || $paymentMethod == 'QRIS') {
                                                    $icon = 'qr-code';
                                                    $badgeClass = 'bg-warning bg-opacity-10 text-warning';
                                                } elseif ($paymentMethod == 'CREDIT_CARD') {
                                                    $icon = 'credit-card';
                                                    $badgeClass = 'bg-dark bg-opacity-10 text-dark';
                                                } elseif ($paymentMethod == 'RETAIL_OUTLET') {
                                                    $icon = 'shop';
                                                    $badgeClass = 'bg-success bg-opacity-10 text-success';
                                                }
                                            }

                                            // Display friendly name
                                            $displayMethod = '';
                                            if ($paymentMethod == 'EWALLET') {
                                                $displayMethod = $paymentChannel ?: 'E-Wallet';
                                            } elseif ($paymentMethod == 'BANK_TRANSFER' || $paymentMethod == 'VIRTUAL_ACCOUNT') {
                                                $displayMethod = ($paymentChannel ?: 'VA') . ' Virtual Account';
                                            } elseif ($paymentMethod == 'QR_CODE' || $paymentMethod == 'QRIS') {
                                                $displayMethod = 'QRIS';
                                            } elseif ($paymentMethod == 'CREDIT_CARD') {
                                                $displayMethod = 'Kartu Kredit/Debit';
                                            } elseif ($paymentMethod == 'RETAIL_OUTLET') {
                                                $displayMethod = $paymentChannel ?: 'Retail Store';
                                            } else {
                                                if ($payment->status == 'PENDING') {
                                                    $displayMethod = 'Menunggu pembayaran';
                                                } else {
                                                    $displayMethod = 'N/A';
                                                }
                                            }
                                        @endphp

                                        <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fw-semibold">
                                            <i class="bi bi-{{ $icon }} me-1"></i>{{ $displayMethod }}
                                        </span>

                                        @if($payment->status == 'PAID' && !empty($paymentChannel))
                                            <div class="small text-muted mt-1">{{ $paymentChannel }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4">
                                        @if($payment->status === 'PAID')
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                <i class="bi bi-check-circle-fill me-1"></i>Lunas
                                            </span>
                                        @elseif($payment->status === 'PENDING')
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                                <i class="bi bi-clock-fill me-1"></i>Pending
                                            </span>
                                        @elseif($payment->status === 'FAILED')
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                                <i class="bi bi-x-circle-fill me-1"></i>Gagal
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                                {{ $payment->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4">
                                        <div class="fw-semibold">{{ $payment->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $payment->created_at->format('H:i') }} WIB</small>
                                        @if($payment->paid_at)
                                            <div class="small text-success mt-1">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Lunas: {{ $payment->paid_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 text-center">
                                        <div class="btn-group" role="group">
                                            <!-- Detail Button -->
                                            <a href="{{ route('admin.transactions.show', $payment->id) }}"
                                               class="btn btn-outline-info btn-sm rounded-pill"
                                               data-bs-toggle="tooltip"
                                               data-bs-placement="top"
                                               title="Lihat detail lengkap transaksi">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- Confirm Payment Button (hanya untuk status PENDING) -->
                                            @if($payment->status === 'PENDING')
                                                <button onclick="confirmPayment({{ $payment->id }}, '{{ $payment->pendaftar->nama_murid }}')"
                                                        class="btn btn-outline-success btn-sm rounded-pill"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Konfirmasi pembayaran secara manual">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            @endif

                                            <!-- Invoice Button -->
                                            @if($payment->invoice_url)
                                                <a href="{{ $payment->invoice_url }}"
                                                   class="btn btn-outline-primary btn-sm rounded-pill"
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
                    <div class="card-footer bg-white border-top p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i>
                                Menampilkan <span class="fw-bold text-primary">{{ $payments->firstItem() ?? 0 }}</span> -
                                <span class="fw-bold text-primary">{{ $payments->lastItem() ?? 0 }}</span>
                                dari <span class="fw-bold">{{ $payments->total() }}</span> transaksi
                                <span class="ms-2 text-muted">|</span>
                                <span class="ms-2 fw-bold text-info">Halaman {{ $payments->currentPage() }} dari {{ $payments->lastPage() }}</span>
                            </div>
                            <div>
                                @if($payments->hasPages())
                                    {{ $payments->appends(request()->query())->links() }}
                                @else
                                    <span class="text-muted small">
                                        <i class="bi bi-file-earmark-text me-1"></i>
                                        Semua data ditampilkan
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Enhanced Empty State -->
                    <div class="text-center py-5 my-4">
                        <div class="mb-4">
                            <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-light p-4" style="width: 120px; height: 120px;">
                                <i class="bi bi-receipt-cutoff text-primary" style="font-size: 3.5rem;"></i>
                            </div>
                        </div>
                        <h4 class="text-muted fw-bold mb-3">Tidak Ada Transaksi Ditemukan</h4>
                        <p class="text-muted mb-4 col-md-6 mx-auto">
                            Belum ada transaksi yang sesuai dengan filter yang dipilih.<br>
                            Coba ubah filter atau hapus beberapa kriteria pencarian.
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
                            </a>
                            <button onclick="refreshData()" class="btn btn-primary rounded-pill px-4">
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
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-gradient-primary text-white border-0 rounded-top-4">
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
                    <div class="alert alert-light border rounded-3">
                        <div class="fw-bold" id="studentName">-</div>
                        <small class="text-muted">Pastikan pembayaran sudah benar-benar diterima</small>
                    </div>
                    <div class="alert alert-warning border-warning rounded-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Perhatian:</strong> Aksi ini akan mengubah status pembayaran menjadi <strong>LUNAS</strong>
                        dan akan mengirim notifikasi konfirmasi kepada orang tua. Aksi ini tidak dapat dibatalkan.
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-success rounded-pill" id="confirmPaymentBtn">
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
        :root {
            --bs-primary-rgb: 65, 105, 225;
            --bs-success-rgb: 40, 167, 69;
            --bs-warning-rgb: 255, 193, 7;
            --bs-info-rgb: 23, 162, 184;
            --bs-danger-rgb: 220, 53, 69;
        }

        /* New elegant styling */
        body {
            background-color: #f9fafb;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #4169e1 0%, #3f51b5 100%) !important;
        }

        /* Enhanced cards */
        .card {
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        }

        /* Rounded corners */
        .rounded-4 {
            border-radius: 1rem !important;
        }

        .rounded-top-4 {
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
        }

        /* Table improvements */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            font-size: 0.8rem;
            color: #718096;
            letter-spacing: 0.5px;
            border-top: none;
        }

        .table-row {
            transition: transform 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            border-left: 3px solid transparent;
        }

        .table-row:hover {
            background-color: #f8fafd !important;
            transform: translateX(5px);
            border-left: 3px solid rgba(var(--bs-primary-rgb), 0.5);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* Enhance badges */
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
            padding: 0.4rem 0.65rem;
        }

        /* Better code formatting */
        code {
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            font-size: 0.85rem;
            padding: 0.3rem 0.5rem;
            border: 1px solid #edf2f7;
            color: #4a5568;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        }

        /* Button enhancements */
        .btn {
            font-weight: 500;
            letter-spacing: 0.3px;
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-group .btn {
            margin: 0 0.15rem;
        }

        /* Animated elements */
        .counter {
            font-family: 'Inter', sans-serif;
        }

        /* Avatar styling */
        .avatar-circle {
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        /* Form controls */
        .form-control, .form-select {
            padding: 0.6rem 1rem;
            border-color: #e2e8f0;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
            border-color: rgba(var(--bs-primary-rgb), 0.5);
        }

        /* Dropdown styling */
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }

        .dropdown-item {
            padding: 0.6rem 1.5rem;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.1);
        }

        /* Toast styling */
        .toast {
            background: white;
            border: none;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
            border-radius: 0.75rem;
        }

        /* Pagination styling */
        .pagination {
            gap: 0.25rem;
        }

        .page-item .page-link {
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4169e1;
            border: none;
        }

        .page-item.active .page-link {
            background-color: #4169e1;
            color: white;
        }

        /* Responsive tweaks */
        @media (max-width: 768px) {
            .btn-group {
                flex-direction: row;
            }

            .btn-group .btn {
                padding: 0.375rem;
                font-size: 0.75rem;
                margin: 0.1rem;
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
                once: true,
                offset: 100
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
            window.location.href = "{{ route('admin.transactions.export', request()->query()) }}";
        }

        function printTable() {
            window.open("{{ route('admin.transactions.print', request()->query()) }}", "_blank");
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

        // Auto-submit form when date filter changes for better UX with validation
        function validateAndSubmitDateFilter() {
            const dateFrom = document.getElementById('date_from').value;
            const dateTo = document.getElementById('date_to').value;

            if (dateFrom && dateTo) {
                if (dateFrom > dateTo) {
                    alert('Tanggal "Dari" tidak boleh lebih besar dari tanggal "Sampai"!');
                    return false;
                }
                showFilterLoadingState();
                document.getElementById('filterForm').submit();
            }
        }

        document.getElementById('date_from').addEventListener('change', function() {
            if (this.value && document.getElementById('date_to').value) {
                validateAndSubmitDateFilter();
            }
        });

        document.getElementById('date_to').addEventListener('change', function() {
            if (this.value && document.getElementById('date_from').value) {
                validateAndSubmitDateFilter();
            }
        });

        // Show loading state when form is submitted
        function showFilterLoadingState() {
            const filterButton = document.getElementById('filterButton');
            filterButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memfilter...';
            filterButton.disabled = true;
        }

        // Auto-submit when status, payment_type, or jenjang changes
        document.getElementById('status').addEventListener('change', function() {
            showFilterLoadingState();
            document.getElementById('filterForm').submit();
        });

        document.getElementById('payment_type').addEventListener('change', function() {
            showFilterLoadingState();
            document.getElementById('filterForm').submit();
        });

        document.getElementById('jenjang').addEventListener('change', function() {
            showFilterLoadingState();
            document.getElementById('filterForm').submit();
        });

        // Add loading state to form submit
        document.getElementById('filterForm').addEventListener('submit', function() {
            showFilterLoadingState();
        });
    </script>
</x-app-layout>
