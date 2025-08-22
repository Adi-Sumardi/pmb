<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berhasil Disimpan</title>
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
        <p class="text-muted">Terima kasih sudah mengisi form. Data Anda telah tersimpan dengan aman dan akan segera di verifikasi.</p>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-custom">Isi Form Lagi</a>
        </div>
    </div>
</div>

</body>
</html>
