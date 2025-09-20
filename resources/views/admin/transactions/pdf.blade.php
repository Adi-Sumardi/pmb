<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
            color: #4169e1;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 5px 8px;
            text-align: left;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 5px 0;
            color: #4169e1;
        }
        .header p {
            margin: 5px 0;
            color: #718096;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .stat-box {
            border: 1px solid #e2e8f0;
            padding: 8px;
            width: 23%;
            text-align: center;
            border-radius: 5px;
        }
        .stat-title {
            font-size: 8pt;
            text-transform: uppercase;
            color: #718096;
        }
        .stat-value {
            font-size: 12pt;
            font-weight: bold;
            color: #4169e1;
        }
        .footer {
            margin-top: 30px;
            font-size: 8pt;
            text-align: center;
            color: #718096;
        }
        .filter-info {
            font-size: 8pt;
            color: #718096;
            margin-bottom: 10px;
        }
        .paid {
            color: #28a745;
            font-weight: bold;
        }
        .pending {
            color: #ffc107;
            font-weight: bold;
        }
        .failed {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Transaksi Pembayaran</h1>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Stats Section -->
    <div class="stats">
        <div class="stat-box">
            <div class="stat-title">Total Transaksi</div>
            <div class="stat-value">{{ $stats['total_transactions'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Pembayaran Lunas</div>
            <div class="stat-value" style="color: #28a745;">{{ $stats['paid_transactions'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Menunggu Pembayaran</div>
            <div class="stat-value" style="color: #ffc107;">{{ $stats['pending_transactions'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Total Revenue</div>
            <div class="stat-value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Filter Info -->
    <div class="filter-info">
        <strong>Filter:</strong>
        Status: {{ request('status') ?: 'Semua' }} |
        Jenjang: {{ request('jenjang') ? strtoupper(request('jenjang')) : 'Semua' }} |
        Periode: {{ request('date_from') ? request('date_from') : 'Awal' }} s/d {{ request('date_to') ? request('date_to') : 'Akhir' }} |
        Pencarian: "{{ request('search') ?: 'Tidak ada' }}"
    </div>

    <!-- Transactions Table -->
    <table>
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
                <td>{{ $payment->external_id }}</td>
                <td>{{ $payment->pendaftar->nama_murid }}</td>
                <td>{{ $payment->pendaftar->no_pendaftaran }}</td>
                <td>{{ strtoupper($payment->pendaftar->jenjang) }}</td>
                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
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
                <td class="{{ strtolower($payment->status) }}">
                    @if($payment->status === 'PAID')
                        Lunas
                    @elseif($payment->status === 'PENDING')
                        Pending
                    @elseif($payment->status === 'FAILED')
                        Gagal
                    @else
                        {{ $payment->status }}
                    @endif
                </td>
                <td>
                    {{ $payment->created_at->format('d/m/Y H:i') }}
                    @if($payment->paid_at)
                    <br><span class="paid">Dibayar: {{ $payment->paid_at->format('d/m/Y H:i') }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari sistem PPDB. © {{ now()->format('Y') }} Sistem Administrasi PPDB</p>
    </div>
</body>
</html>
