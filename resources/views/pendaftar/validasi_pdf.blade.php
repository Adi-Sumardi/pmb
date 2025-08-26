<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Penerimaan Murid Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .kartu {
            width: 21cm;
            max-width: 100%;
            min-height: 14.8cm;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
            margin: 0 auto;
            padding: 30px;
        }

        .foto-peserta {
            width: 3cm;
            height: 4cm;
            object-fit: cover;
            border: 1px solid #333;
        }

        .qr-box {
            border: 1px solid #ddd;
            padding: 8px;
            background: #fafafa;
            border-radius: 4px;
            display: inline-block;
        }

        .card {
            transition: all 0.3s ease;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white !important;
            }

            .btn {
                display: none !important;
            }

            .kartu {
                box-shadow: none !important;
                border: 1px solid #000 !important;
                margin: 0 !important;
                page-break-inside: avoid;
                border-radius: 0;
            }

            .col-md-8, .col-md-4, .col-md-6 {
                flex: none !important;
            }

            .col-md-8 {
                width: 66.666667% !important;
            }

            .col-md-4 {
                width: 33.333333% !important;
            }

            .col-md-6 {
                width: 50% !important;
            }
        }
    </style>
</head>
<body>

<div class="kartu card shadow-sm p-4">
    <!-- Kop Surat -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="height:60px; margin-right:15px;">
            <div>
                <h5 class="mb-0 fw-bold">Kartu Penerimaan Murid Baru {{ $peserta->unit ?? 'SDIT Yapinet' }}</h5>
                <small class="text-muted">Tahun Ajaran {{ $tahunAjaran }}</small>
            </div>
        </div>
    </div>

    <hr class="my-3">

    <!-- Identitas Utama -->
    <div class="row mb-3">
        <div class="col-md-8">
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <td style="width: 180px;"><strong>Nama Peserta</strong></td>
                        <td style="width: 10px;">:</td>
                        <td>{{ $peserta->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. Pendaftaran</strong></td>
                        <td>:</td>
                        <td>{{ $peserta->no_pendaftaran }}</td>
                    </tr>
                    <tr>
                        <td><strong>NISN</strong></td>
                        <td>:</td>
                        <td>{{ $peserta->nisn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Asal Sekolah</strong></td>
                        <td>:</td>
                        <td>{{ $peserta->asal_sekolah ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Lahir</strong></td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($peserta->tanggal_lahir)->locale('id')->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>:</td>
                        <td>{{ $peserta->alamat }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-4 d-flex flex-column align-items-center">
            <!-- Foto Peserta -->
            <div class="mb-3">
                @if($peserta->foto && $peserta->foto !== 'default.jpg')
                    <img src="{{ public_path('storage/uploads/foto_murid/' . $peserta->foto) }}"
                         alt="Foto Peserta"
                         class="foto-peserta border border-dark">
                @else
                    <div class="foto-peserta border border-dark d-flex align-items-center justify-content-center bg-light">
                        <small class="text-muted">Foto Belum<br>Tersedia</small>
                    </div>
                @endif
            </div>

            <!-- QR Code -->
            <div class="qr-box border border-secondary bg-light rounded">
                <div id="qrcode"></div>
            </div>
        </div>
    </div>

    <hr class="my-3">

    <!-- Data Orang Tua -->
    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Nama Ayah:</strong> {{ $peserta->nama_ayah }}
        </div>
        <div class="col-md-6">
            <strong>Nama Ibu:</strong> {{ $peserta->nama_ibu }}
        </div>
    </div>

    <hr class="my-3">

    <!-- Catatan Formal -->
    <div class="text-center">
        <p class="small mb-0 text-muted">
            Kartu pendaftaran ini diterbitkan sebagai bukti sah Penerimaan Murid Baru Tahun Ajaran {{ $tahunAjaran }} pada {{ $peserta->unit ?? 'SDIT Yapinet' }}.<br>
            Mohon dibawa saat mengikuti proses seleksi dan administrasi lebih lanjut.
        </p>
    </div>
</div>

<!-- QR Code Script untuk PDF -->
<script>
    // Untuk PDF generation, QR code akan di-generate di server
    document.addEventListener('DOMContentLoaded', function() {
        // Generate QR code menggunakan library yang kompatibel dengan PDF
        var qr = new QRious({
            element: document.getElementById('qrcode'),
            value: '{{ $peserta->no_pendaftaran }}',
            size: 80,
            foreground: '#000000',
            background: '#ffffff'
        });
    });
</script>

<!-- Fallback QR Code menggunakan library alternatif -->
<script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>

</body>
</html>
