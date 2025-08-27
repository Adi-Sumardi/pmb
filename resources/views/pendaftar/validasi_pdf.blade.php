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
            font-size: 12px;
            line-height: 1.2;
        }

        .registration-card {
            background: white;
            border: 2px solid #000;
            margin: 0;
            font-family: 'Times New Roman', serif;
            page-break-inside: avoid;
            width: 100%;
        }

        /* Header Styles - Sesuai dengan validasi.blade.php */
        .card-header-formal {
            padding: 20px 30px 15px;
            border-bottom: 3px double #000;
        }

        .logo-official {
            text-align: center;
        }

        .logo-formal {
            height: 80px;
            width: auto;
        }

        .institution-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            color: #000;
            text-transform: uppercase;
        }

        .school-name {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
            color: #000;
            text-transform: uppercase;
        }

        .address-text, .contact-text {
            font-size: 12px;
            margin: 2px 0;
            color: #333;
        }

        .divider-line {
            border: 1px solid #000;
            margin: 15px 0 10px 0;
        }

        .card-title-section {
            text-align: center;
            margin: 10px 0;
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

        /* Content Styles - Sesuai dengan validasi.blade.php */
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

        /* Photo Styles - Sesuai dengan validasi.blade.php */
        .photo-section-formal {
            margin-top: 20px;
        }

        .photo-frame-formal {
            border: 2px solid #000;
            width: 120px;
            height: 160px;
            margin: 0 auto 10px;
            overflow: hidden;
            background: #f9f9f9;
        }

        .student-photo-formal {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-label {
            font-size: 12px;
            margin: 0;
            font-weight: bold;
            color: #000;
        }

        /* Footer Styles - Sesuai dengan validasi.blade.php */
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

        /* Print optimization */
        @media print {
            body {
                margin: 0;
                padding: 0.5cm;
            }

            .registration-card {
                box-shadow: none;
                border: 2px solid #000;
                transform: scale(0.9);
                transform-origin: top left;
            }

            @page {
                margin: 0.5cm;
                size: A4;
            }
        }

        /* Remove Bootstrap margins for better fit */
        .row {
            margin: 0;
        }

        .col-2, .col-4, .col-8, .col-10 {
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="registration-card">
        <!-- Header Section - Sama persis dengan validasi.blade.php -->
        <div class="card-header-formal">
            <div class="row align-items-center">
                <div class="col-2">
                    <div class="logo-official">
                        <img src="{{ public_path('images/logo.png') }}" alt="Logo YAPI" class="logo-formal">
                    </div>
                </div>
                <div class="col-10 text-center">
                    <h4 class="institution-name">YAYASAN ASRAMA PELAJAR ISLAM</h4>
                    <h5 class="school-name">{{ $peserta->unit ?? 'YAPI SCHOOL' }}</h5>
                    <p class="address-text">Jl. Sunan Giri No. 1, Rawamangun, Jakarta Timur</p>
                    <p class="contact-text">Telp: (021) 7984-5555 | Email: info@yapi.sch.id | https://yapi.sch.id</p>
                </div>
            </div>
            <hr class="divider-line">
            <div class="card-title-section">
                <h3 class="card-title">KARTU PENDAFTARAN CALON MURID BARU</h3>
                <p class="academic-year">TAHUN AJARAN {{ $tahunAjaran ?? '2026/2027' }}</p>
            </div>
        </div>

        <!-- Content Section - Sama persis dengan validasi.blade.php -->
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

                <!-- Right Column - Photo -->
                <div class="col-4 text-center">
                    <div class="photo-section-formal">
                        <div class="photo-frame-formal">
                            @if(isset($peserta->foto_murid_path) && $peserta->foto_murid_path)
                                <img src="{{ public_path('storage/' . $peserta->foto_murid_path) }}"
                                     alt="Foto Calon Murid"
                                     class="student-photo-formal">
                            @elseif(isset($peserta->foto) && $peserta->foto && $peserta->foto !== 'default.jpg')
                                <img src="{{ public_path('storage/uploads/foto_murid/' . $peserta->foto) }}"
                                     alt="Foto Calon Murid"
                                     class="student-photo-formal">
                            @else
                                <img src="{{ public_path('images/default-foto.jpg') }}"
                                     alt="Foto Calon Murid"
                                     class="student-photo-formal">
                            @endif
                        </div>
                        <p class="photo-label">Foto 3x4 cm</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section - Sama persis dengan validasi.blade.php -->
        <div class="card-footer-formal">
            <div class="row">
                <div class="col-8">
                    <div class="notes-section">
                        <h6 class="notes-title">CATATAN PENTING:</h6>
                        <ul class="notes-list">
                            <li>Kartu ini wajib dibawa saat verifikasi dokumen</li>
                            <li>Harap datang tepat waktu sesuai jadwal yang ditentukan</li>
                            <li>Lengkapi berkas pendaftaran sebelum batas waktu</li>
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
