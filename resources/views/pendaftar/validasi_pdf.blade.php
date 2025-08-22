<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Pendaftaran</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Bukti Pendaftaran</h2>
        <p>No Pendaftaran: {{ $pendaftar->id }}</p>
    </div>

    <table class="table">
        <tr>
            <th>Nama Lengkap</th>
            <td>{{ $pendaftar->nama }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $pendaftar->email }}</td>
        </tr>
        <tr>
            <th>No HP</th>
            <td>{{ $pendaftar->no_hp }}</td>
        </tr>
        <tr>
            <th>Tanggal Daftar</th>
            <td>{{ $pendaftar->created_at->format('d-m-Y') }}</td>
        </tr>
    </table>
</body>
</html>
