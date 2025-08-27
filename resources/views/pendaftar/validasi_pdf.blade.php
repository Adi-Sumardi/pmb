<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Pendaftaran Calon Murid Baru</title>
    <style>
        body {
            background: white;
            margin: 0;
            padding: 30mm;
            font-family: 'Times New Roman', serif;
            font-size: 12px;
        }

        /* Reset and Base Styles */
        .registration-card {
            background: white;
            border: 2px solid #000;
            margin: 0;
            font-family: 'Times New Roman', serif;
            page-break-inside: avoid;
            width: 100%;
        }

        /* Header Styles */
        .card-header-formal {
            padding: 20px 30px 15px;
            border-bottom: 3px double #000;
        }

        .header-row {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .logo-section {
            width: 15%;
            text-align: center;
        }

        .logo-formal {
            height: 80px;
            width: auto;
        }

        .title-section {
            width: 70%;
            text-align: center;
            padding: 0 15px;
        }

        .qr-section {
            width: 15%;
            text-align: center;
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

        .qr-code-section {
            border: 1px solid #000;
            padding: 10px;
            background: #f9f9f9;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .qr-label {
            display: block;
            font-size: 10px;
            margin-top: 5px;
            font-weight: bold;
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

        /* Content Styles */
        .card-content-formal {
            padding: 20px 30px;
        }

        .content-row {
            display: flex;
            width: 100%;
        }

        .data-column {
            width: 75%;
            padding-right: 20px;
        }

        .photo-column {
            width: 25%;
            text-align: center;
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

        /* Account Info Styles */
        .account-section-formal {
            margin-top: 20px;
            padding: 15px;
            border: 2px solid #007bff;
            border-radius: 5px;
            background-color: #f8f9fa;
        }

        .account-title {
            color: #007bff;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .account-table {
            width: 100%;
        }

        .account-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .account-label {
            width: 25%;
            font-weight: bold;
        }

        .password-value {
            font-family: monospace;
            background-color: #e9ecef;
            padding: 3px 6px;
            border-radius: 3px;
        }

        .account-warning {
            margin-top: 10px;
            font-size: 12px;
            color: #6c757d;
        }

        .account-warning strong {
            color: #dc3545;
        }

        .registration-date {
            font-size: 12px;
            margin: 0;
            color: #666;
        }

        /* Photo Styles */
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

        /* Footer Styles */
        .card-footer-formal {
            padding: 20px 30px;
            border-top: 2px solid #000;
            background: #f9f9f9;
        }

        .footer-row {
            display: flex;
            width: 100%;
        }

        .notes-column {
            width: 70%;
            padding-right: 20px;
        }

        .signature-column {
            width: 30%;
            text-align: center;
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
                padding: 3cm;
            }

            .registration-card {
                box-shadow: none;
                border: 2px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="registration-card">
        <!-- Header Section -->
        <div class="card-header-formal">
            <div class="header-row">
                <div class="logo-section">
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo YAPI" class="logo-formal">
                </div>
                <div class="title-section">
                    <h4 class="institution-name">YAYASAN ASRAMA PELAJAR ISLAM</h4>
                    <h5 class="school-name">{{ $peserta->unit ?? 'YAPI SCHOOL' }}</h5>
                    <p class="address-text">Jl. Sunan Giri No. 1, Rawamangun, Jakarta Timur</p>
                    <p class="contact-text">Telp: (021) 7984-5555 | Email: info@yapi.sch.id | https://yapi.sch.id</p>
                </div>
                <div class="qr-section">
                    <div class="qr-code-section">
                        <div id="qrcode-{{ $peserta->no_pendaftaran }}"></div>
                        <small class="qr-label">QR Code</small>
                    </div>
                </div>
            </div>
            <hr class="divider-line">
            <div class="card-title-section">
                <h3 class="card-title">KARTU PENDAFTARAN CALON MURID BARU</h3>
                <p class="academic-year">TAHUN AJARAN {{ $tahunAjaran ?? '2026/2027' }}</p>
            </div>
        </div>

        <!-- Content Section -->
        <div class="card-content-formal">
            <div class="content-row">
                <!-- Left Column - Student Data -->
                <div class="data-column">
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
                        <h4 class="account-title">🔐 Informasi Akun Siswa</h4>
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
                            <strong>⚠️ Penting:</strong> Simpan informasi akun ini dengan aman. Ganti password setelah login pertama.
                        </div>
                    </div>

                    <!-- Registration Date -->
                    <div style="margin-top: 20px; text-align: right;">
                        <p class="registration-date">
                            Terdaftar: {{ isset($peserta->created_at) && $peserta->created_at ? \Carbon\Carbon::parse($peserta->created_at)->locale('id')->translatedFormat('d F Y') : now()->locale('id')->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>

                <!-- Right Column - Photo -->
                <div class="photo-column">
                    <div class="photo-section-formal">
                        <div class="photo-frame-formal">
                            @if($peserta->foto && $peserta->foto !== 'default.jpg')
                                <img src="{{ public_path('storage/uploads/foto_murid/' . $peserta->foto) }}"
                                     alt="Foto Calon Murid"
                                     class="student-photo-formal">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f9f9f9; font-size: 10px; text-align: center; color: #666;">
                                    Foto Belum<br>Tersedia
                                </div>
                            @endif
                        </div>
                        <p class="photo-label">Foto 3x4 cm</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="card-footer-formal">
            <div class="footer-row">
                <div class="notes-column">
                    <div class="notes-section">
                        <h6 class="notes-title">CATATAN PENTING:</h6>
                        <ul class="notes-list">
                            <li>Kartu ini wajib dibawa saat verifikasi dokumen</li>
                            <li>Harap datang tepat waktu sesuai jadwal yang ditentukan</li>
                            <li>Lengkapi berkas pendaftaran sebelum batas waktu</li>
                        </ul>
                    </div>
                </div>
                <div class="signature-column">
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

    <!-- QR Code Script -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new QRCode(document.getElementById("qrcode"), {
                text: "{{ $peserta->no_pendaftaran }}",
                width: 80,
                height: 80,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        });
    </script>
</body>
</html>
