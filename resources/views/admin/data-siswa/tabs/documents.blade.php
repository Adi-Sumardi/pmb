<!-- Documents Tab Content -->
<div class="row g-4">
    <div class="col-12">

        @if($documents && $documents->count() > 0)
            <!-- Documents List -->
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #e3f2fd, #bbdefb); border-left: 5px solid #2196f3;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-file-earmark-text-fill me-2"></i>Dokumen Siswa
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #f8fcff;">
                    <div class="row g-4">
                        @foreach($documents as $document)
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #2196f3 !important;">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 flex-shrink-0">
                                            @php
                                                $ext = pathinfo($document->nama_file ?? '', PATHINFO_EXTENSION);
                                                $iconClass = match(strtolower($ext)) {
                                                    'pdf' => 'bi-file-earmark-pdf text-danger',
                                                    'doc', 'docx' => 'bi-file-earmark-word text-primary',
                                                    'jpg', 'jpeg', 'png', 'gif' => 'bi-file-earmark-image text-success',
                                                    'xls', 'xlsx' => 'bi-file-earmark-excel text-success',
                                                    'zip', 'rar' => 'bi-file-earmark-zip text-warning',
                                                    default => 'bi-file-earmark text-secondary'
                                                };
                                            @endphp
                                            <i class="bi {{ $iconClass }} fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-2">{{ $document->nama_dokumen }}</h6>

                                            @if($document->jenis_dokumen)
                                                <span class="badge bg-primary bg-opacity-15 text-primary border border-primary mb-2">
                                                    {{ ucfirst(str_replace('_', ' ', $document->jenis_dokumen)) }}
                                                </span>
                                            @endif

                                            @if($document->nama_file)
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <i class="bi bi-paperclip me-1"></i>{{ $document->nama_file }}
                                                    </small>
                                                </div>
                                            @endif

                                            @if($document->ukuran_file)
                                                <div class="mt-1">
                                                    <small class="text-muted">
                                                        <i class="bi bi-hdd me-1"></i>{{ formatBytes($document->ukuran_file) }}
                                                    </small>
                                                </div>
                                            @endif

                                            @if($document->tanggal_upload)
                                                <div class="mt-1">
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($document->tanggal_upload)->format('d F Y') }}
                                                    </small>
                                                </div>
                                            @endif

                                            @if($document->deskripsi)
                                                <div class="mt-3">
                                                    <p class="text-muted small mb-0">{{ $document->deskripsi }}</p>
                                                </div>
                                            @endif

                                            @if($document->file_path)
                                                <div class="mt-3">
                                                    <div class="btn-group" role="group" aria-label="Document actions">
                                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                            <i class="bi bi-eye me-1"></i>Lihat
                                                        </a>
                                                        <a href="{{ Storage::url($document->file_path) }}" download="{{ $document->nama_file }}" class="btn btn-outline-success btn-sm">
                                                            <i class="bi bi-download me-1"></i>Download
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($document->status_verifikasi)
                                                <div class="mt-3">
                                                    @if($document->status_verifikasi === 'terverifikasi')
                                                        <span class="badge bg-success bg-opacity-15 text-success border border-success">
                                                            <i class="bi bi-check-circle me-1"></i>Terverifikasi
                                                        </span>
                                                    @elseif($document->status_verifikasi === 'ditolak')
                                                        <span class="badge bg-danger bg-opacity-15 text-danger border border-danger">
                                                            <i class="bi bi-x-circle me-1"></i>Ditolak
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning bg-opacity-15 text-warning border border-warning">
                                                            <i class="bi bi-clock me-1"></i>Menunggu Verifikasi
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Document Statistics -->
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #f3e5f5, #e1bee7); border-left: 5px solid #9c27b0;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-pie-chart me-2"></i>Statistik Dokumen
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #fdfaff;">
                    <div class="row g-3">
                        @php
                            $stats = [
                                'total' => $documents->count(),
                                'terverifikasi' => $documents->where('status_verifikasi', 'terverifikasi')->count(),
                                'menunggu' => $documents->where('status_verifikasi', 'menunggu')->count(),
                                'ditolak' => $documents->where('status_verifikasi', 'ditolak')->count(),
                                'total_size' => $documents->sum('ukuran_file'),
                            ];

                            $docTypes = $documents->groupBy('jenis_dokumen')->map(function ($group) {
                                return $group->count();
                            });
                        @endphp

                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                                    <i class="bi bi-files text-primary fs-4"></i>
                                </div>
                                <h4 class="fw-bold text-primary mb-1">{{ $stats['total'] }}</h4>
                                <small class="text-muted">Total Dokumen</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                                    <i class="bi bi-check-circle text-success fs-4"></i>
                                </div>
                                <h4 class="fw-bold text-success mb-1">{{ $stats['terverifikasi'] }}</h4>
                                <small class="text-muted">Terverifikasi</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                                    <i class="bi bi-clock text-warning fs-4"></i>
                                </div>
                                <h4 class="fw-bold text-warning mb-1">{{ $stats['menunggu'] }}</h4>
                                <small class="text-muted">Menunggu</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                                    <i class="bi bi-hdd text-info fs-4"></i>
                                </div>
                                <h4 class="fw-bold text-info mb-1">{{ formatBytes($stats['total_size']) }}</h4>
                                <small class="text-muted">Total Ukuran</small>
                            </div>
                        </div>
                    </div>

                    <!-- Document Types -->
                    @if($docTypes->count() > 0)
                    <hr class="my-4">
                    <h6 class="fw-bold mb-3">Jenis Dokumen</h6>
                    <div class="row g-3">
                        @foreach($docTypes as $type => $count)
                        <div class="col-md-4">
                            <div class="bg-light rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $type)) }}</div>
                                        <small class="text-muted">{{ $count }} dokumen</small>
                                    </div>
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                        <i class="bi bi-file-earmark text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        @else
            <!-- No Documents -->
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-file-earmark fs-1 text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">Belum Ada Dokumen</h5>
                    <p class="text-muted mb-4">
                        Siswa belum mengunggah dokumen pendukung.<br>
                        Dokumen akan muncul setelah siswa mengunggah file-file yang diperlukan.
                    </p>

                    <div class="bg-light rounded-3 p-4 mx-auto" style="max-width: 600px;">
                        <h6 class="fw-bold mb-3">Jenis Dokumen yang Biasanya Diperlukan:</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-person text-primary me-2"></i>
                                    <small>Foto Siswa</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-text text-info me-2"></i>
                                    <small>Akta Kelahiran</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-text text-success me-2"></i>
                                    <small>Kartu Keluarga</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-medical text-warning me-2"></i>
                                    <small>Surat Keterangan Sehat</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-mortarboard text-purple me-2"></i>
                                    <small>Ijazah/Raport</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-award text-danger me-2"></i>
                                    <small>Sertifikat Prestasi</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-muted small">
                            <i class="bi bi-lightbulb me-1"></i>
                            Siswa dapat mengunggah dokumen melalui dashboard mereka.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Registration Documents (from Pendaftar) -->
        <div class="card shadow-sm rounded-4 mt-4 border-0">
            <div class="card-header py-3" style="background: linear-gradient(to right, #e8f5e9, #c8e6c9); border-left: 5px solid #4caf50;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-folder-check me-2"></i>Dokumen Pendaftaran
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #f8fff8;">
                <div class="row g-3">
                    <!-- Foto Murid -->
                    @if($student->foto_murid_path)
                    <div class="col-md-4">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3 text-center">
                                <i class="bi bi-person-circle text-primary fs-1 mb-2"></i>
                                <h6 class="fw-semibold mb-2">Foto Siswa</h6>
                                <small class="text-muted d-block mb-2">{{ formatBytes($student->foto_murid_size) }}</small>
                                <a href="{{ Storage::url($student->foto_murid_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Akta Kelahiran -->
                    @if($student->akta_kelahiran_path)
                    <div class="col-md-4">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3 text-center">
                                <i class="bi bi-file-earmark-text text-info fs-1 mb-2"></i>
                                <h6 class="fw-semibold mb-2">Akta Kelahiran</h6>
                                <small class="text-muted d-block mb-2">{{ formatBytes($student->akta_kelahiran_size) }}</small>
                                <a href="{{ Storage::url($student->akta_kelahiran_path) }}" target="_blank" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-eye me-1"></i>Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Kartu Keluarga -->
                    @if($student->kartu_keluarga_path)
                    <div class="col-md-4">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3 text-center">
                                <i class="bi bi-file-earmark-text text-success fs-1 mb-2"></i>
                                <h6 class="fw-semibold mb-2">Kartu Keluarga</h6>
                                <small class="text-muted d-block mb-2">{{ formatBytes($student->kartu_keluarga_size) }}</small>
                                <a href="{{ Storage::url($student->kartu_keluarga_path) }}" target="_blank" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-eye me-1"></i>Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Bukti Pendaftaran -->
                    @if($student->bukti_pendaftaran_path)
                    <div class="col-md-4">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3 text-center">
                                <i class="bi bi-receipt text-warning fs-1 mb-2"></i>
                                <h6 class="fw-semibold mb-2">Bukti Pendaftaran</h6>
                                <small class="text-muted d-block mb-2">{{ formatBytes($student->bukti_pendaftaran_size) }}</small>
                                <a href="{{ Storage::url($student->bukti_pendaftaran_path) }}" target="_blank" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-eye me-1"></i>Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                @if(!$student->foto_murid_path && !$student->akta_kelahiran_path && !$student->kartu_keluarga_path && !$student->bukti_pendaftaran_path)
                <div class="text-center py-4">
                    <i class="bi bi-folder-x text-muted fs-1 mb-3"></i>
                    <p class="text-muted">Tidak ada dokumen pendaftaran yang tersedia.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@php
function formatBytes($size, $precision = 2) {
    if (!$size) return '0 B';
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}
@endphp
