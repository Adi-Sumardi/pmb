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
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            transition: all 0.3s ease;
        }

        .data-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .data-left {
            flex: 1;
        }

        .data-right {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            min-width: 120px;
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

            .data-section {
                display: flex !important;
            }

            .data-left {
                flex: 1 !important;
            }

            .data-right {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                min-width: 120px !important;
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

    <!-- Identitas Utama dengan Layout Kiri-Kanan -->
    <div class="data-section mb-3">
        <div class="data-left">
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

        <div class="data-right">
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

    <!-- Informasi Akun Siswa -->
    <div style="margin-top: 20px; padding: 15px; border: 2px solid #007bff; border-radius: 5px; background-color: #f8f9fa;">
        <h4 style="color: #007bff; margin-bottom: 10px;">🔐 Informasi Akun Siswa</h4>
        <table style="width: 100%;">
            <tr>
                <td style="width: 25%; font-weight: bold;">Email:</td>
                <td>{{ $peserta->email }}</td>
            </tr>
            <tr>
                <td style="width: 25%; font-weight: bold;">Password:</td>
                <td style="font-family: monospace; background-color: #e9ecef; padding: 3px 6px; border-radius: 3px;">{{ $peserta->password }}</td>
            </tr>
        </table>
        <div style="margin-top: 10px; font-size: 12px; color: #6c757d;">
            <strong>⚠️ Penting:</strong> Simpan informasi akun ini dengan aman. Ganti password setelah login pertama.
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

<!-- QR Code Script menggunakan davidshimjs/qrcodejs -->
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
