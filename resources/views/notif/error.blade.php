<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terjadi Kesalahan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f85032, #e73827);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            border: none;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            background: #fff;
            color: #333;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.2);
        }
        .card img {
            width: 150px;
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #e73827;
            color: #fff;
            border-radius: 10px;
            padding: 10px 20px;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background-color: #c71c00;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center p-4">
                    <img src="https://cdn-icons-png.flaticon.com/512/564/564619.png"
                        alt="Error Icon"
                        class="mx-auto d-block"
                        style="width: 100px; height: 100px;">

                    <h2 class="mt-3">Oops! Terjadi Kesalahan</h2>
                    <p>
                        Mohon maaf, data Anda tidak dapat diproses saat ini.<br>
                        Silakan coba kembali atau hubungi admin.
                    </p>

                    <a href="{{ url()->previous() }}" class="btn btn-custom mt-3">Kembali</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
