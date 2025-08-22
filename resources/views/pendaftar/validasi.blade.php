<x-app-layout>
    <div class="py-12 d-flex justify-content-center">
        <div class="card shadow-sm p-4" style="width: 21cm; max-width: 100%; height: 14.8cm;">

            <!-- Kop Surat -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:60px; margin-right:10px;">
                    <div>
                        <h5 class="mb-0 fw-bold">Kartu Penerimaan Murid Baru {{ $pendaftar->unit }}</h5>
                        <small>Tahun Ajaran 2026/2027</small>
                    </div>
                </div>
                <!-- QR-Code -->
                <div>
                    <div id="qrcode-{{ $pendaftar->id }}"></div>
                </div>
            </div>

            <hr>
                <!-- Identitas Utama -->
                <div class="d-flex justify-content-evenly align-items-start mb-3 mt-3">
                    <div class="mr-5">
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <td style="width: 180px;"><strong>Nama Peserta</strong></td>
                                    <td>:</td>
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

                    <div>
                        <img src="{{ asset('storage/' . ($pendaftar->foto_murid_path ?? 'images/default-foto.jpg')) }}"
                            alt="Foto Peserta"
                            style="width:3cm; height:4cm; object-fit:cover; border:1px solid #333;">
                    </div>
                </div>

            <hr>

            <!-- Data Orang Tua -->
            <div class="row mt-2">
                <div class="col-md-6 mb-2">
                    <strong>Nama Ayah:</strong> {{ $pendaftar->nama_ayah }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Nama Ibu:</strong> {{ $pendaftar->nama_ibu }}
                </div>
            </div>

            <hr>

            <!-- Catatan Formal -->
            <div class="mt-3">
                <p class="small mb-0 text-center">
                    Kartu pendaftaran ini diterbitkan sebagai bukti sah Penerimaan Murid Baru Tahun Ajaran 2026/2027 pada {{ $pendaftar->unit }}.
                    Mohon dibawa saat mengikuti proses seleksi dan administrasi lebih lanjut.
                </p>
            </div>

            <!-- Tombol Cetak -->

        </div>
    </div>
    <div class="text-center">
        <button class="btn btn-secondary" onclick="window.print()">Cetak</button>

        <form action="{{ route('pendaftar.update', $pendaftar->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-primary">Validasi</button>
        </form>
    </div>

    <!-- Style Cetak -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .card, .card * {
                visibility: visible;
            }
            .card {
                position: absolute;
                left: 0;
                top: 0;
                width: 21cm;
                height: 14.8cm;
            }
        }
    </style>

    <script>
        new QRCode(document.getElementById("qrcode-{{ $pendaftar->id }}"), {
            text: "{{ $pendaftar->no_pendaftaran }}",
            width: 80,
            height: 80,
        });
    </script>
</x-app-layout>
