<!-- filepath: /Users/yapi/project/ppdb-backend/resources/views/user/data/review.blade.php -->
<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Style untuk print dan PDF */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                font-size: 12pt;
                color: #000;
                background-color: #fff;
            }
            .container-fluid {
                width: 100%;
                padding: 0;
                margin: 0;
            }
            .page-break {
                page-break-before: always;
            }
            .table-bordered {
                border: 1px solid #000;
            }
            .table-bordered th, .table-bordered td {
                border: 1px solid #000 !important;
                padding: 5px;
            }

            .photo-box {
                height: 4cm !important;
                width: 3cm !important;
                border: 1px solid black !important;
                overflow: hidden !important;
            }

            .photo-box img {
                width: 100% !important;
                height: 100% !important;
            }
        }

        .photo-box {
            border: 1px solid #dee2e6;
            height: 4cm;
            width: 3cm;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin: 0 auto;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Pastikan foto menutupi seluruh area tanpa distorsi */
            object-position: center top; /* Fokus ke bagian atas foto (wajah) */
        }

        /* Style umum */
        .section-title {
            background-color: #f8f9fa;
            padding: 8px 15px;
            margin-bottom: 15px;
            border-left: 4px solid #2563eb;
            font-weight: bold;
        }
        .table-data th {
            width: 35%;
            font-weight: 600;
            background-color: #f8f9fa;
        }
        .formal-document {
            background-color: white;
            padding: 30px;
            border: 1px solid #dee2e6;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .document-header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .photo-box {
            border: 1px solid #dee2e6;
            height: 4cm;
            width: 3cm;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .photo-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        .signature-area {
            margin-top: 50px;
        }
        .signature-box {
            border-bottom: 1px solid #000;
            height: 70px;
            width: 150px;
            margin: 0 auto 10px;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Fungsi untuk mengunduh dokumen sebagai PDF
        function downloadPDF() {
            // Konfigurasi PDF
            const pdfOptions = {
                margin: [10, 10, 10, 10],
                filename: 'formulir-pendaftaran-{{ $pendaftar->no_pendaftaran ?? $pendaftar->id }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // Elemen yang akan dikonversi
            const element = document.getElementById('formal-document');

            // Sembunyikan tombol saat mengunduh
            const actionButtons = document.getElementById('action-buttons');
            actionButtons.classList.add('d-none');

            // Proses pengunduhan
            html2pdf().set(pdfOptions).from(element).save().then(() => {
                // Tampilkan kembali tombol setelah selesai
                actionButtons.classList.remove('d-none');
            });
        }
    </script>
    @endpush

    <div class="container-fluid py-4 bg-light">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Tombol Aksi - Tidak akan dicetak/diunduh -->
                <div id="action-buttons" class="card mb-4 no-print">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold text-primary">Review Data Pendaftaran</h4>
                        <div>
                            <button onclick="window.print()" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-print me-1"></i> Cetak
                            </button>
                            <button onclick="downloadPDF()" class="btn btn-primary">
                                <i class="fas fa-file-pdf me-1"></i> Unduh PDF
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Formal yang akan dicetak/diunduh -->
                <div id="formal-document" class="formal-document">
                    <!-- Header Dokumen -->
                    <div class="document-header row align-items-center">
                        <div class="col-2 text-center">
                            <img src="{{ asset('images/logo-yapi.png') }}" alt="Logo Sekolah" style="max-height: 80px;">
                        </div>
                        <div class="col-8 text-center">
                            <h4 class="mb-0 fw-bold">FORMULIR PENDAFTARAN SISWA BARU</h4>
                            <h5 class="mb-1">TAHUN AJARAN {{ date('Y') }}/{{ date('Y')+1 }}</h5>
                            <p class="mb-0 fw-bold">{{ config('app.name', 'SEKOLAH INDONESIA MAJU') }}</p>
                            <p class="small mb-0">Jl. Pendidikan No. 123, Kecamatan Ilmu, Kota Pengetahuan</p>
                        </div>
                        <div class="col-2 text-end">
                            <div class="photo-box mx-auto">
                                @if($pendaftar->foto_murid_path)
                                    <!-- Gunakan foto langsung dari tabel pendaftars -->
                                    <img src="{{ asset('storage/' . $pendaftar->foto_murid_path) }}" alt="Foto {{ $studentDetail->nama_lengkap ?? 'Siswa' }}">
                                @elseif(isset($documents) && $documents->where('jenis', 'foto')->first())
                                    <!-- Fallback ke dokumen jika foto di pendaftar tidak ada -->
                                    <img src="{{ asset('storage/' . $documents->where('jenis', 'foto')->first()->file_path) }}" alt="Foto Siswa">
                                @else
                                    <!-- Tampilkan placeholder jika tidak ada foto -->
                                    <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                                        <span class="text-muted small text-center">
                                            <i class="fas fa-user mb-2 d-block" style="font-size: 24px;"></i>
                                            Foto 3x4
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pendaftaran -->
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td width="200"><strong>No. Pendaftaran</strong></td>
                                        <td>: {{ $pendaftar->no_pendaftaran ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Pendaftaran</strong></td>
                                        <td>: {{ $pendaftar->created_at->format('d F Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td width="200"><strong>Jenjang</strong></td>
                                        <td>: {{ $pendaftar->jenjang ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Unit</strong></td>
                                        <td>: {{ $pendaftar->unit ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pribadi Siswa -->
                    <div class="mb-4">
                        <h5 class="section-title">A. INFORMASI PRIBADI SISWA</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-data">
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td>{{ $studentDetail->nama_lengkap ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>NIK</th>
                                    <td>{{ $studentDetail->nik ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor Kartu Keluarga</th>
                                    <td>{{ $studentDetail->no_kk ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tempat, Tanggal Lahir</th>
                                    <td>
                                        {{ $studentDetail->tempat_lahir ?? '-' }},
                                        {{ $studentDetail->tanggal_lahir ? date('d F Y', strtotime($studentDetail->tanggal_lahir)) : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>{{ $studentDetail->jenis_kelamin ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Agama</th>
                                    <td>{{ $studentDetail->agama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat Lengkap</th>
                                    <td>
                                        {{ $studentDetail->alamat_lengkap ?? '-' }}<br>
                                        Kelurahan {{ $studentDetail->kelurahan ?? '-' }},
                                        Kecamatan {{ $studentDetail->kecamatan ?? '-' }}<br>
                                        {{ $studentDetail->kota_kabupaten ?? '-' }},
                                        {{ $studentDetail->provinsi ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $studentDetail->email ?? $pendaftar->user->email ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Data Orang Tua -->
                    <div class="mb-4">
                        <h5 class="section-title">B. INFORMASI ORANG TUA/WALI</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-2">Data Ayah</h6>
                                <table class="table table-bordered table-data">
                                    <tr>
                                        <th>Nama Lengkap</th>
                                        <td>{{ $parentDetail->nama_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pendidikan</th>
                                        <td>{{ $parentDetail->pendidikan_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pekerjaan</th>
                                        <td>{{ $parentDetail->pekerjaan_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Penghasilan</th>
                                        <td>
                                            {{ $parentDetail->penghasilan_ayah ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Nomor Telepon</th>
                                        <td>{{ $parentDetail->no_hp_ayah ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-2">Data Ibu</h6>
                                <table class="table table-bordered table-data">
                                    <tr>
                                        <th>Nama Lengkap</th>
                                        <td>{{ $parentDetail->nama_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pendidikan</th>
                                        <td>{{ $parentDetail->pendidikan_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pekerjaan</th>
                                        <td>{{ $parentDetail->pekerjaan_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Penghasilan</th>
                                        <td>
                                            {{ $parentDetail->penghasilan_ibu ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Nomor Telepon</th>
                                        <td>{{ $parentDetail->no_hp_ibu ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($parentDetail && ($parentDetail->nama_wali || $parentDetail->pekerjaan_wali))
                        <div class="mt-3">
                            <h6 class="fw-bold mb-2">Data Wali (Jika Ada)</h6>
                            <table class="table table-bordered table-data">
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td>{{ $parentDetail->nama_wali ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Hubungan dengan Siswa</th>
                                    <td>{{ $parentDetail->hubungan_wali ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Pekerjaan</th>
                                    <td>{{ $parentDetail->pekerjaan_wali ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor Telepon</th>
                                    <td>{{ $parentDetail->nomor_telepon_wali ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        @endif
                    </div>

                    <!-- Riwayat Akademik -->
                    <div class="mb-4">
                        <h5 class="section-title">C. RIWAYAT PENDIDIKAN</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-data">
                                <tr>
                                    <th>Asal Sekolah</th>
                                    <td>{{ $academicHistory->nama_sekolah_sebelumnya ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>NPSN Sekolah</th>
                                    <td>{{ $academicHistory->npsn_sekolah_sebelumnya ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat Sekolah</th>
                                    <td>{{ $academicHistory->alamat_sekolah_sebelumnya ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tahun Lulus</th>
                                    <td>{{ $academicHistory->tahun_lulus ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>No. Ijazah</th>
                                    <td>{{ $academicHistory->no_ijazah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Nilai Rata-rata Raport</th>
                                    <td>{{ $academicHistory->rata_rata_nilai ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Prestasi Akademik</th>
                                    <td>{{ $academicHistory->prestasi_akademik ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Prestasi -->
                    <div class="mb-4">
                        <h5 class="section-title">D. PRESTASI</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="table-light">
                                        <th width="50">No</th>
                                        <th>Nama Prestasi</th>
                                        <th>Tingkat</th>
                                        <th>Tahun</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($achievements) && $achievements->count() > 0)
                                        @foreach($achievements as $index => $achievement)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $achievement->nama_prestasi }}</td>
                                            <td>{{ $achievement->tingkat }}</td>
                                            <td>{{ $achievement->tahun }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada prestasi yang diinput</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Catatan Kesehatan -->
                    @if($healthRecord)
                    <div class="mb-4">
                        <h5 class="section-title">E. CATATAN KESEHATAN</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-data">
                                <tr>
                                    <th>Golongan Darah</th>
                                    <td>{{ $healthRecord->golongan_darah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tinggi Badan</th>
                                    <td>{{ $healthRecord->tinggi_badan ? $healthRecord->tinggi_badan . ' cm' : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Berat Badan</th>
                                    <td>{{ $healthRecord->berat_badan ? $healthRecord->berat_badan . ' kg' : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Riwayat Penyakit</th>
                                    <td>{{ $healthRecord->riwayat_penyakit ?? 'Tidak Ada' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Area Tanda Tangan -->
                    <div class="signature-area">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <p>Mengetahui,<br>Orang Tua/Wali</p>
                                <div class="signature-box"></div>
                                <p>{{ $parentDetail->nama_ayah ?? $parentDetail->nama_ibu ?? $parentDetail->nama_wali ?? '...........................' }}</p>
                            </div>
                            <div class="col-md-4 text-center">
                            </div>
                            <div class="col-md-4 text-center">
                                <p>{{ date('d F Y') }}<br>Panitian PPDB 2026/2027</p>
                                <div class="signature-box"></div>
                                <p>Panitia</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Dokumen -->
                    <div class="mt-5 pt-3 border-top">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="small mb-0">Nomor Pendaftaran: {{ $pendaftar->no_pendaftaran ?? $pendaftar->id }}</p>
                                <p class="small mb-0">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="small mb-0">{{ config('app.name', 'PPDB Online') }}</p>
                                <p class="small mb-0">Halaman 1 dari 1</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi Bawah - Tidak akan dicetak/diunduh -->
                <div class="card mt-4 no-print">
                    <div class="card-body d-flex justify-content-between">
                        <a href="{{ route('user.data.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>

                        @if($pendaftar->status != 'submitted')
                        <form action="{{ route('user.data.submit') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane me-1"></i> Simpan Data Pendaftaran
                            </button>
                        </form>
                        @else
                        <button disabled class="btn btn-success">
                            <i class="fas fa-check me-1"></i>Data Pendaftaran Tersimpan
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
