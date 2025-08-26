<x-app-layout>
    <div class="py-12 d-flex justify-content-center">
        <div class="card shadow-sm p-4" style="width: 21cm; max-width: 100%; min-height: 14.8cm;">

            <!-- Kop Surat -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:60px; margin-right:15px;">
                    <div>
                        <h5 class="mb-0 fw-bold">Kartu Penerimaan Murid Baru {{ $pendaftar->unit }}</h5>
                        <small class="text-muted">Tahun Ajaran 2026/2027</small>
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
                                <td>{{ $pendaftar->nama_murid }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. Pendaftaran</strong></td>
                                <td>:</td>
                                <td>{{ $pendaftar->no_pendaftaran }}</td>
                            </tr>
                            <tr>
                                <td><strong>NISN</strong></td>
                                <td>:</td>
                                <td>{{ $pendaftar->nisn ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Asal Sekolah</strong></td>
                                <td>:</td>
                                <td>{{ $pendaftar->asal_sekolah ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Lahir</strong></td>
                                <td>:</td>
                                <td>{{ \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->locale('id')->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>:</td>
                                <td>{{ $pendaftar->alamat }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-4 d-flex flex-column align-items-center">
                    <!-- Foto Peserta -->
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . ($pendaftar->foto_murid_path ?? 'images/default-foto.jpg')) }}"
                            alt="Foto Peserta"
                            class="border border-dark"
                            style="width:3cm; height:4cm; object-fit:cover;">
                    </div>

                    <!-- QR Code -->
                    <div class="border border-secondary p-2 bg-light rounded">
                        <div id="qrcode-{{ $pendaftar->id }}"></div>
                    </div>
                </div>
            </div>

            <hr class="my-3">

            <!-- Data Orang Tua -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Nama Ayah:</strong> {{ $pendaftar->nama_ayah }}
                </div>
                <div class="col-md-6">
                    <strong>Nama Ibu:</strong> {{ $pendaftar->nama_ibu }}
                </div>
            </div>

            <hr class="my-3">

            <!-- Catatan Formal -->
            <div class="text-center">
                <p class="small mb-0 text-muted">
                    Kartu pendaftaran ini diterbitkan sebagai bukti sah Penerimaan Murid Baru Tahun Ajaran 2026/2027 pada {{ $pendaftar->unit }}.<br>
                    Mohon dibawa saat mengikuti proses seleksi dan administrasi lebih lanjut.
                </p>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="text-center mt-3 mb-4">
        <button class="btn btn-secondary me-2" onclick="window.print()">
            <i class="fas fa-print me-1"></i>Cetak Kartu
        </button>

        <form action="{{ route('pendaftar.update', $pendaftar->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check me-1"></i>Validasi
            </button>
        </form>
    </div>

    <!-- Style Cetak -->
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white !important;
            }

            .py-12, .mt-3, .mb-4 {
                margin: 0 !important;
                padding: 0 !important;
            }

            .btn, .text-center:last-child {
                display: none !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #000 !important;
                margin: 0 !important;
                page-break-inside: avoid;
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

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new QRCode(document.getElementById("qrcode-{{ $pendaftar->id }}"), {
                text: "{{ $pendaftar->no_pendaftaran }}",
                width: 80,
                height: 80,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        });
    </script>
</x-app-layout>
