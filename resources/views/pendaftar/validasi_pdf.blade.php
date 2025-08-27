<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Pendaftaran Calon Murid Baru</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: white;
            margin: 0;
            padding: 0.5cm;
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.2;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Reset and Base Styles */
        .registration-card {
            background: white;
            border: 2px solid #000;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: 0;
            font-family: 'Times New Roman', serif;
            page-break-inside: avoid;
            width: 100%;
            transform: scale(0.9);
            transform-origin: top left;
        }

        /* Title Section */
        .card-title-section {
            text-align: center;
            margin: 20px 0;
            padding: 20px 30px;
            border-bottom: 2px solid #000;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
            color: #000;
            text-decoration: underline;
        }

        .academic-year {
            font-size: 14px;
            margin: 5px 0 0 0;
            font-weight: bold;
            color: #000;
        }

        /* Content Styles */
        .card-content-formal {
            padding: 20px 30px;
        }

        .data-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .data-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .label-col {
            width: 35%;
            font-weight: bold;
            color: #000;
            font-size: 14px;
        }

        .colon-col {
            width: 5%;
            text-align: center;
            font-weight: bold;
        }

        .value-col {
            width: 60%;
            color: #000;
            font-size: 14px;
        }

        .parent-section-formal {
            margin: 25px 0 20px 0;
            padding-top: 15px;
            border-top: 1px solid #ccc;
        }

        .account-section-formal {
            margin: 25px 0 20px 0;
            padding-top: 15px;
            border-top: 1px solid #ccc;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
            text-decoration: underline;
        }

        .status-section-formal {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
        }

        .status-text {
            font-size: 14px;
            margin: 0;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-valid {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-invalid {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .registration-date {
            font-size: 12px;
            margin: 0;
            color: #666;
        }

        /* Footer Styles */
        .card-footer-formal {
            padding: 20px 30px;
            border-top: 2px solid #000;
            background: #f9f9f9;
        }

        .notes-section {
            margin-bottom: 10px;
        }

        .notes-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #000;
        }

        .notes-list {
            margin: 0;
            padding-left: 20px;
            font-size: 12px;
            color: #333;
        }

        .notes-list li {
            margin-bottom: 3px;
        }

        .signature-section {
            text-align: center;
        }

        .signature-place, .signature-title, .signature-name {
            font-size: 12px;
            margin: 5px 0;
            color: #000;
        }

        .signature-title {
            font-weight: bold;
        }

        .signature-space {
            height: 60px;
            border-bottom: 1px solid #000;
            margin: 10px 20px;
        }

        .signature-name {
            font-weight: bold;
        }

        /* Bootstrap Grid Fix untuk PDF */
        .row {
            margin: 0 !important;
            display: flex;
            flex-wrap: wrap;
        }

        .col-6 {
            flex: 0 0 auto;
            width: 50%;
            padding: 0;
        }

        .col-8 {
            flex: 0 0 auto;
            width: 66.66666667%;
            padding: 0;
        }

        .col-4 {
            flex: 0 0 auto;
            width: 33.33333333%;
            padding: 0;
        }

        .text-center {
            text-align: center !important;
        }

        .text-end {
            text-align: right !important;
        }

        /* Print optimization untuk 1 halaman */
        @page {
            size: A4;
            margin: 1cm;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                transform: scale(0.85);
                transform-origin: top left;
            }

            .registration-card {
                box-shadow: none;
                transform: scale(1);
                margin: 0;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="registration-card">
        <!-- Title Section Only -->
        <div class="card-title-section">
            <h3 class="card-title">KARTU PENDAFTARAN CALON MURID BARU</h3>
            <p class="academic-year">TAHUN AJARAN 2026/2027</p>
        </div>

        <!-- Content Section -->
        <div class="card-content-formal">
            <!-- Student Data -->
            <table class="data-table">
                <tr>
                    <td class="label-col">No. Pendaftaran</td>
                    <td class="colon-col">:</td>
                    <td class="value-col"><strong>{{ $peserta->no_pendaftaran }}</strong></td>
                </tr>
                <tr>
                    <td class="label-col">Nama Lengkap</td>
                    <td class="colon-col">:</td>
                    <td class="value-col"><strong>{{ strtoupper($peserta->nama_murid ?? $peserta->nama) }}</strong></td>
                </tr>
                <tr>
                    <td class="label-col">NISN</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">{{ $peserta->nisn ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Tanggal Lahir</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">{{ \Carbon\Carbon::parse($peserta->tanggal_lahir)->locale('id')->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="label-col">Jenjang</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">{{ strtoupper($peserta->jenjang ?? '-') }}</td>
                </tr>
                <tr>
                    <td class="label-col">Unit Sekolah</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">{{ $peserta->unit ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Asal Sekolah</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">{{ $peserta->nama_sekolah ?? $peserta->asal_sekolah ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Alamat</td>
                    <td class="colon-col">:</td>
                    <td class="value-col">{{ $peserta->alamat }}</td>
                </tr>
            </table>

            <!-- Parent Information -->
            <div class="parent-section-formal">
                <h6 class="section-title">DATA ORANG TUA/WALI</h6>
                <table class="data-table">
                    <tr>
                        <td class="label-col">Nama Ayah</td>
                        <td class="colon-col">:</td>
                        <td class="value-col">{{ $peserta->nama_ayah }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">No. Telepon Ayah</td>
                        <td class="colon-col">:</td>
                        <td class="value-col">{{ $peserta->telp_ayah ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Nama Ibu</td>
                        <td class="colon-col">:</td>
                        <td class="value-col">{{ $peserta->nama_ibu }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">No. Telepon Ibu</td>
                        <td class="colon-col">:</td>
                        <td class="value-col">{{ $peserta->telp_ibu ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Account Information -->
            <div class="account-section-formal">
                <h6 class="section-title">INFORMASI AKUN</h6>
                <table class="data-table">
                    <tr>
                        <td class="label-col">Email</td>
                        <td class="colon-col">:</td>
                        <td class="value-col">{{ $peserta->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Password</td>
                        <td class="colon-col">:</td>
                        <td class="value-col">{{ $peserta->raw_password ?? 'Tidak tersedia' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Status Akun</td>
                        <td class="colon-col">:</td>
                        <td class="value-col">{{ $peserta->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Status and Date -->
            <div class="status-section-formal">
                <div class="row">
                    <div class="col-6">
                        <p class="status-text">
                            <strong>Status: </strong>
                            <span class="status-badge status-{{ $peserta->status ?? 'pending' }}">
                                {{ strtoupper($peserta->status ?? 'PENDING') }}
                            </span>
                        </p>
                    </div>
                    <div class="col-6 text-end">
                        <p class="registration-date">
                            Terdaftar: {{ \Carbon\Carbon::parse($peserta->created_at ?? now())->locale('id')->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="card-footer-formal">
            <div class="row">
                <div class="col-8">
                    <div class="notes-section">
                        <h6 class="notes-title">CATATAN PENTING:</h6>
                        <ul class="notes-list">
                            <li>Kartu ini wajib dibawa saat verifikasi dokumen</li>
                            <li>Harap datang tepat waktu sesuai jadwal yang ditentukan</li>
                            <li>Lengkapi berkas pendaftaran sebelum batas waktu</li>
                            <li>Simpan informasi akun untuk login ke sistem</li>
                        </ul>
                    </div>
                </div>
                <div class="col-4 text-center">
                    <div class="signature-section">
                        <p class="signature-place">Jakarta, {{ now()->locale('id')->translatedFormat('d F Y') }}</p>
                        <p class="signature-title">Panitia PMB</p>
                        <div class="signature-space"></div>
                        <p class="signature-name">Admin PMB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
