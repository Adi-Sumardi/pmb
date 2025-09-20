<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Transaksi Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
            font-family: 'Arial', sans-serif;
            padding: 20px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
            body {
                padding: 0;
            }
            @page {
                size: landscape;
                margin: 1cm;
            }
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .print-only {
            display: none;
        }
        .table th {
            background-color: #f8f9fa !important;
            color: #4169e1;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
        }
        .badge-print {
            padding: 5px 10px;
            border-radius: 50px;
        }
        .bg-success-light {
            background-color: #d4edda;
            color: #28a745;
        }
        .bg-warning-light {
            background-color: #fff3cd;
            color: #ffc107;
        }
        .bg-danger-light {
            background-color: #f8d7da;
            color: #dc3545;
        }
        .stats-container {
            margin-bottom: 30px;
        }
        .stat-box {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            background-color: #f8f9fa;
        }
        .stat-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #718096;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4169e1;
        }
        .stat-value.success {
            color: #28a745;
        }
        .stat-value.warning {
            color: #ffc107;
        }
        .filters {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 10px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="no-print mb-4">
            <button class="btn btn-primary rounded-pill" onclick="window.print()">
                <i class="bi bi-printer me-1"></i>Print Dokumen Ini
            </button>
            <button class="btn btn-outline-secondary rounded-pill ms-2" onclick="window.close()">
                <i class="bi bi-x-circle me-1"></i>Tutup
            </button>
        </div>

        <div class="header">
            <h1 class="fw-bold">Laporan Transaksi Pembayaran PPDB</h1>
            <p class="text-muted">Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
        </div>

        <!-- Stats Section -->
        <div class="row stats-container">
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-title">Total Transaksi</div>
                    <div class="stat-value">{{ $stats['total_transactions'] }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-title">Pembayaran Lunas</div>
                    <div class="stat-value success">{{ $stats['paid_transactions'] }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-title">Menunggu Pembayaran</div>
                    <div class="stat-value warning">{{ $stats['pending_transactions'] }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-title">Total Revenue</div>
                    <div class="stat-value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Filter Info -->
        <div class="filters">
            <div class="row">
                <div class="col-md-12">
                    <strong>Filter yang digunakan:</strong>
                    <span class="ms-2 badge bg-light text-dark">Status: {{ request('status') ?: 'Semua' }}</span>
                    <span class="ms-2 badge bg-light text-dark">Jenjang: {{ request('jenjang') ? strtoupper(request('jenjang')) : 'Semua' }}</span>
                    <span class="ms-2 badge bg-light text-dark">Periode: {{ request('date_from') ? request('date_from') : 'Awal' }} s/d {{ request('date_to') ? request('date_to') : 'Akhir' }}</span>
                    @if(request('search'))
                    <span class="ms-2 badge bg-light text-dark">Pencarian: "{{ request('search') }}"</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 3%;">#</th>
                        <th style="width: 10%;">Transaction ID</th>
                        <th style="width: 15%;">Nama Siswa</th>
                        <th style="width: 10%;">No. Pendaftaran</th>
                        <th style="width: 7%;">Unit</th>
                        <th style="width: 10%;">Amount</th>
                        <th style="width: 15%;">Metode Transfer</th>
                        <th style="width: 8%;">Status</th>
                        <th style="width: 12%;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $index => $payment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><code>{{ $payment->external_id }}</code></td>
                        <td>
                            <div class="fw-bold">{{ $payment->pendaftar->nama_murid }}</div>
                            <small class="text-muted">{{ $payment->pendaftar->no_pendaftaran }}</small>
                        </td>
                        <td>{{ $payment->pendaftar->unit }}</td>
                        <td>{{ strtoupper($payment->pendaftar->jenjang) }}</td>
                        <td class="fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $paymentMethod = '';
                                $paymentChannel = '';

                                if (isset($payment->xendit_response['payment_method'])) {
                                    $paymentMethod = $payment->xendit_response['payment_method'];
                                    $paymentChannel = $payment->xendit_response['payment_channel'] ?? '';

                                    // Display friendly name
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
                                        $displayMethod = $paymentMethod;
                                    }
                                } else {
                                    $displayMethod = $payment->status == 'PENDING' ? 'Menunggu pembayaran' : 'N/A';
                                }
                            @endphp
                            {{ $displayMethod }}
                        </td>
                        <td>
                            @if($payment->status === 'PAID')
                                <span class="badge-print bg-success-light">Lunas</span>
                            @elseif($payment->status === 'PENDING')
                                <span class="badge-print bg-warning-light">Pending</span>
                            @elseif($payment->status === 'FAILED')
                                <span class="badge-print bg-danger-light">Gagal</span>
                            @else
                                <span class="badge-print bg-secondary-light">{{ $payment->status }}</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $payment->created_at->format('d/m/Y H:i') }}</div>
                            @if($payment->paid_at)
                                <div class="text-success small">
                                    <i class="bi bi-check-circle"></i>
                                    {{ $payment->paid_at->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="print-only mt-5 text-center">
            <p class="text-muted small">Dokumen ini dicetak secara otomatis dari sistem PPDB. © {{ now()->format('Y') }} Sistem Administrasi PPDB</p>
        </div>
    </div>
</body>
</html>
