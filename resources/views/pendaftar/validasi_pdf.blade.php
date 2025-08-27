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
            padding: 1cm;
            font-family: 'Times New Roman', serif;
            font-size: 11px;
            line-height: 1.2;
        }

        .registration-card {
            background: white;
            border: 2px solid #000;
            margin: 0;
            font-family: 'Times New Roman', serif;
            page-break-inside: avoid;
            width: 100%;
            min-height: calc(100vh - 2cm);
            position: relative;
        }

        /* Header Styles */
        .card-header-formal {
            padding: 10px 15px 8px;
            align-items: center;
            border-bottom: 2px solid #000;
        }

        .logo-formal {
            height: 50px;
            width: auto;
        }

        .institution-name {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            color: #000;
            text-transform: uppercase;
        }

        .school-name {
            font-size: 13px;
            font-weight: bold;
            margin: 2px 0;
            color: #000;
            text-transform: uppercase;
        }

        .address-text, .contact-text {
            font-size: 9px;
            margin: 1px 0;
            color: #333;
        }

        .card-title {
            font-size: 16px;
            font-weight: bold;
            margin: 8px 0 3px 0;
            color: #000;
            text-decoration: underline;
        }

        .academic-year {
            font-size: 12px;
            margin: 0;
            font-weight: bold;
            color: #000;
        }

        /* Content Styles */
        .card-content-formal {
            padding: 10px 15px;
        }

        .data-table {
            width: 100%;
            margin-bottom: 8px;
        }

        .data-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .label-col {
            width: 35%;
            font-weight: bold;
            color: #000;
            font-size: 11px;
        }

        .colon-col {
            width: 3%;
            text-align: center;
            font-weight: bold;
        }

        .value-col {
            width: 62%;
            color: #000;
            font-size: 11px;
        }

        .parent-section-formal {
            margin: 10px 0 8px 0;
            padding-top: 8px;
            border-top: 1px solid #ccc;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
            text-decoration: underline;
        }

        /* Account Info Styles */
        .account-section-formal {
            margin-top: 8px;
            padding: 8px;
            border: 1px solid #007bff;
            border-radius: 3px;
            background-color: #f8f9fa;
        }

        .account-title {
            color: #007bff;
            margin-bottom: 5px;
            font-size: 11px;
            font-weight: bold;
        }

        .account-table {
            width: 100%;
        }

        .account-table td {
            padding: 1px 0;
            vertical-align: top;
            font-size: 10px;
        }

        .account-label {
            width: 25%;
            font-weight: bold;
        }

        .password-value {
            font-family: monospace;
            background-color: #e9ecef;
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 9px;
        }

        .account-warning {
            margin-top: 5px;
            font-size: 9px;
            color: #6c757d;
        }

        .account-warning strong {
            color: #dc3545;
        }

        .registration-date {
            font-size: 10px;
            margin: 8px 0 0 0;
            color: #666;
            text-align: right;
        }

        /* Footer Styles */
        .card-footer-formal {
            padding: 8px 15px;
            border-top: 1px solid #000;
            background: #f9f9f9;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .notes-title {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #000;
        }

        .notes-list {
            margin: 0;
            padding-left: 15px;
            font-size: 9px;
            color: #333;
        }

        .notes-list li {
            margin-bottom: 1px;
        }

        .signature-section {
            text-align: center;
        }

        .signature-place, .signature-title, .signature-name {
            font-size: 10px;
            margin: 2px 0;
            color: #000;
        }

        .signature-title {
            font-weight: bold;
        }

        .signature-space {
            height: 40px;
            border-bottom: 1px solid #000;
            margin: 5px 10px;
        }

        .signature-name {
            font-weight: bold;
        }

        /* Print optimization */
        @media print {
            body {
                margin: 0;
                padding: 1cm;
            }

            .registration-card {
                box-shadow: none;
                border: 2px solid #000;
                min-height: calc(100vh - 2cm);
            }

            @page {
                margin: 1cm;
                size: A4;
            }
        }
    </style>
</head>
<body>
    <div class="registration-card">
        <!-- Header Section -->
        <div class="card-header-formal align-items-center">
            <div class="text-center">
                <h3 class="card-title">KARTU PENDAFTARAN CALON MURID BARU</h3>
                <p class="academic-year">TAHUN AJARAN {{ $tahunAjaran ?? '2026/2027' }}</p>
            </div>
        </div>

        <!-- Content Section -->
        <div class="card-content-formal">
            <div class="row">
                <!-- Left Column - Student Data -->
                <div class="col-8">
                    <table class="data-table">
                        <tr>
                            <td class="label-col">No. Pendaftaran</td>
                            <td class="colon-col">:</td>
                            <td class="value-col"><strong>{{ $peserta->no_pendaftaran }}</strong></td>
                        </tr>
                        <tr>
                            <td class="label-col">Nama Lengkap</td>
                            <td class="colon-col">:</td>
                            <td class="value-col"><strong>{{ strtoupper($peserta->nama) }}</strong></td>
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
                            <td class="label-col">Asal Sekolah</td>
                            <td class="colon-col">:</td>
                            <td class="value-col">{{ $peserta->asal_sekolah ?? '-' }}</td>
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
                        <h4 class="account-title">Informasi Akun Siswa</h4>
                        <table class="account-table">
                            <tr>
                                <td class="account-label">Email:</td>
                                <td>{{ $peserta->email }}</td>
                            </tr>
                            <tr>
                                <td class="account-label">Password:</td>
                                <td class="password-value">{{ $peserta->password }}</td>
                            </tr>
                        </table>
                        <div class="account-warning">
                            <strong>Penting:</strong> Simpan informasi akun ini dengan aman. Ganti password setelah login pertama.
                        </div>
                    </div>

                    <!-- Registration Date -->
                    <p class="registration-date">
                        Terdaftar: {{ \Carbon\Carbon::parse($peserta->created_at ?? now())->locale('id')->translatedFormat('d F Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="card-footer-formal">
            <div class="row">
                <div class="col-8">
                    <h6 class="notes-title">CATATAN PENTING:</h6>
                    <ul class="notes-list">
                        <li>Kartu ini wajib dibawa saat verifikasi dokumen</li>
                        <li>Harap datang tepat waktu sesuai jadwal yang ditentukan</li>
                        <li>Lengkapi berkas pendaftaran sebelum batas waktu</li>
                    </ul>
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
