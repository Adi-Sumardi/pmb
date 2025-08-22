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
        }
        .kartu {
            width: 21cm;
            height: 14.8cm;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
            margin: 30px auto;
            padding: 30px;
        }
        .foto-peserta {
            width: 3cm;
            height: 4cm;
            object-fit: cover;
            border: 1px solid #333;
        }
        .kop h5 {
            font-weight: bold;
            margin-bottom: 0;
        }
        .kop small {
            color: #666;
        }
        .catatan {
            font-size: 0.85rem;
            color: #555;
            text-align: center;
        }
        .qr-box {
            border: 1px solid #ddd;
            padding: 4px;
            background: #fafafa;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="kartu">
    <!-- Kop Surat -->
    <div class="d-flex justify-content-between align-items-center kop mb-3">
        <div class="d-flex align-items-center">
            <img src="images/logo.png" alt="Logo" style="height:60px; margin-right:10px;">
            <div>
                <h5>Kartu Penerimaan Murid Baru SDIT Yapinet</h5>
                <small>Tahun Ajaran 2026/2027</small>
            </div>
        </div>
        <!-- QR Code -->
        <div class="qr-box">
            <div id="qrcode"></div>
        </div>
    </div>

    <hr>

    <!-- Identitas Utama -->
    <div class="row mt-3 mb-3">
        <div class="col-md-8">
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <td style="width: 180px;"><strong>Nama Peserta</strong></td>
                        <td>:</td>
                        <td>Adi Nugraha</td>
                    </tr>
                    <tr>
                        <td><strong>No. Pendaftaran</strong></td>
                        <td>:</td>
                        <td>PMB01260123</td>
                    </tr>
                    <tr>
                        <td><strong>NISN</strong></td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td><strong>Asal Sekolah</strong></td>
                        <td>:</td>
                        <td>TK Negeri Pembina</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Lahir</strong></td>
                        <td>:</td>
                        <td>12 Januari 2019</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>:</td>
                        <td>Jl. Merdeka No. 45, Jakarta</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-4 text-center">
            <img src="storage/foto_murid/contoh.jpg" alt="Foto Peserta" class="foto-peserta">
        </div>
    </div>

    <hr>

    <!-- Data Orang Tua -->
    <div class="row mt-2">
        <div class="col-md-6 mb-2">
            <strong>Nama Ayah:</strong> Budi Santoso
        </div>
        <div class="col-md-6 mb-2">
            <strong>Nama Ibu:</strong> Siti Aminah
        </div>
    </div>

    <hr>

    <!-- Catatan Formal -->
    <div class="mt-3 catatan">
        <p>
            Kartu pendaftaran ini diterbitkan sebagai bukti sah Penerimaan Murid Baru Tahun Ajaran 2026/2027.
            Mohon dibawa saat mengikuti proses seleksi dan administrasi lebih lanjut.
        </p>
    </div>

    <!-- Tombol Cetak -->
    <div class="text-center mt-3">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Kartu
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
<script>
    new QRCode(document.getElementById("qrcode"), {
        text: "PMB01260123",
        width: 80,
        height: 80,
    });
</script>

</body>
</html>
