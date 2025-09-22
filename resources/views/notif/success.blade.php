<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Berhasil Disimpan - YAPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f3f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .success-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .success-icon {
            width: 120px;
            margin-bottom: 20px;
        }
        .btn-custom {
            border-radius: 30px;
            padding: 10px 25px;
        }
    </style>
</head>
<body>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card text-center p-4 shadow-lg" style="max-width: 500px;">
        {{-- Gambar ilustrasi sukses --}}
        <img src="https://cdn-icons-png.flaticon.com/512/845/845646.png"
             alt="Success"
             class="mx-auto mb-3"
             style="width: 100px; height: 100px;">

        <h2 class="text-success fw-bold">Data Berhasil Disimpan!</h2>
        <p class="text-muted mb-3">Terima kasih sudah mengisi form. Data Anda telah tersimpan dengan aman dan akan segera diverifikasi.</p>

        {{-- Informasi Verifikasi --}}
        <div class="alert alert-info border-0 shadow-sm text-start" style="background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%); border-radius: 15px;">
            <div class="d-flex align-items-start mb-2">
                <i class="bi bi-clock-history fs-5 text-primary me-2"></i>
                <strong class="text-primary">Jadwal Verifikasi Data</strong>
            </div>
            <p class="mb-2 small text-start">
                <strong>Waktu Verifikasi:</strong> Setiap hari pukul <span class="badge bg-primary">07:00 - 21:00 WIB</span>
            </p>
            <p class="mb-2 small text-start">
                <i class="bi bi-info-circle me-1"></i>
                Tim verifikasi akan memeriksa kelengkapan dan kevalidan data yang telah Anda submit.
            </p>
            <p class="mb-0 small text-muted text-start">
                <i class="bi bi-telephone me-1"></i>
                Untuk pertanyaan mengenai status verifikasi, hubungi:
                <strong>+62 21 1234567</strong>
            </p>
        </div>

        {{-- Informasi Selanjutnya --}}
        <div class="alert alert-warning border-0 shadow-sm mt-3 text-start" style="background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border-radius: 15px;">
            <div class="d-flex align-items-start mb-2">
                <i class="bi bi-exclamation-triangle fs-5 text-warning me-2"></i>
                <strong class="text-warning">Informasi Penting</strong>
            </div>
            <ul class="mb-0 small text-dark text-start">
                <li>Pastikan nomor telepon yang didaftarkan aktif untuk notifikasi</li>
                <li>Siapkan dokumen asli untuk proses verifikasi lanjutan</li>
                <li>Status verifikasi akan diinformasikan melalui kontak yang terdaftar</li>
            </ul>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-custom">
                <i class="bi bi-arrow-left me-1"></i>Isi Form Lagi
            </a>
            <a href="{{ url('/') }}" class="btn btn-primary btn-custom">
                <i class="bi bi-house me-1"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

</body>
</html>
