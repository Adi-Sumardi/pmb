<!-- Overview Tab Content -->
<div class="row g-4">
    <!-- Quick Statistics -->
    <div class="col-12">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #e3f2fd, #bbdefb);">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-person-check text-primary fs-4"></i>
                        </div>
                        <h6 class="fw-bold text-primary">Status Siswa</h6>
                        <h4 class="fw-bold mb-0">{{ ucfirst($currentStatus['text']) }}</h4>
                        @if($student->student_activated_at)
                            <small class="text-muted">Sejak {{ \Carbon\Carbon::parse($student->student_activated_at)->format('M Y') }}</small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f3e5f5, #e1bee7);">
                    <div class="card-body text-center">
                        <div class="bg-purple bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-calendar-event text-purple fs-4" style="color: #9c27b0 !important;"></i>
                        </div>
                        <h6 class="fw-bold" style="color: #9c27b0;">Umur</h6>
                        <h4 class="fw-bold mb-0">{{ \Carbon\Carbon::parse($student->tanggal_lahir)->age }} tahun</h4>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($student->tanggal_lahir)->format('d M Y') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #e8f5e8, #c8e6c9);">
                    <div class="card-body text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-wallet2 text-success fs-4"></i>
                        </div>
                        <h6 class="fw-bold text-success">Total Bayar</h6>
                        <h4 class="fw-bold mb-0">Rp {{ number_format($totalPaid ?? 0, 0, ',', '.') }}</h4>
                        @if($pendingPayments > 0)
                            <small class="text-warning">{{ $pendingPayments }} pending</small>
                        @else
                            <small class="text-muted">Semua lunas</small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fff3e0, #ffcc02);">
                    <div class="card-body text-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-building text-warning fs-4"></i>
                        </div>
                        <h6 class="fw-bold text-warning">Unit</h6>
                        <h4 class="fw-bold mb-0">{{ strtoupper($student->unit) }}</h4>
                        <small class="text-muted">{{ $student->jenjang }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Information Cards -->
    <div class="col-lg-8">
        <!-- Registration Information -->
        <div class="card shadow-sm rounded-4 mb-4">
            <div class="card-header py-3" style="background: linear-gradient(to right, #e3f2fd, #bbdefb); border-left: 5px solid #2196f3;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-card-checklist me-2"></i>Informasi Pendaftaran
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #fafafa;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-semibold">No. Pendaftaran</label>
                        <div class="fw-bold fs-5 text-primary">{{ $student->no_pendaftaran }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-semibold">Tahun Ajaran</label>
                        <div class="fw-bold fs-5">{{ $student->academic_year ?? '2026/2027' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-semibold">Status Keseluruhan</label>
                        <div>
                            <span class="badge bg-success bg-opacity-15 text-success border border-success px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>{{ $student->overall_status }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-semibold">Tanggal Daftar</label>
                        <div class="fw-bold">{{ $student->created_at->format('d F Y') }}</div>
                        <small class="text-muted">{{ $student->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        @if($student->nama_ayah || $student->nama_ibu || ($student->user && $student->user->email))
        <div class="card shadow-sm rounded-4 mb-4">
            <div class="card-header py-3" style="background: linear-gradient(to right, #f3e5f5, #e1bee7); border-left: 5px solid #9c27b0;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-person-lines-fill me-2"></i>Kontak & Keluarga
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #fafafa;">
                <div class="row g-3">
                    @if($student->user && $student->user->email)
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold">Email</label>
                            <div class="fw-bold">{{ $student->user->email }}</div>
                            @if($student->user->email_verified_at)
                                <small class="text-success"><i class="bi bi-check-circle me-1"></i>Terverifikasi</small>
                            @else
                                <small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Belum terverifikasi</small>
                            @endif
                        </div>
                    @endif

                    @if($student->nama_ayah)
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold">Ayah</label>
                            <div class="fw-bold">{{ $student->nama_ayah }}</div>
                            @if($student->telp_ayah)
                                <small class="text-muted">{{ $student->telp_ayah }}</small>
                            @endif
                        </div>
                    @endif

                    @if($student->nama_ibu)
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-semibold">Ibu</label>
                            <div class="fw-bold">{{ $student->nama_ibu }}</div>
                            @if($student->telp_ibu)
                                <small class="text-muted">{{ $student->telp_ibu }}</small>
                            @endif
                        </div>
                    @endif

                    @if($student->alamat)
                        <div class="col-12">
                            <label class="form-label text-muted small fw-semibold">Alamat</label>
                            <div class="fw-bold">{{ $student->alamat }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- School Information -->
        @if($student->asal_sekolah || $student->nama_sekolah || $student->kelas)
        <div class="card shadow-sm rounded-4 mb-4">
            <div class="card-header py-3" style="background: linear-gradient(to right, #e8f5e8, #c8e6c9); border-left: 5px solid #4caf50;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-mortarboard me-2"></i>Informasi Sekolah Asal
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #fafafa;">
                <div class="row g-3">
                    @if($student->nama_sekolah || $student->asal_sekolah)
                        <div class="col-md-8">
                            <label class="form-label text-muted small fw-semibold">Nama Sekolah</label>
                            <div class="fw-bold">{{ $student->nama_sekolah ?? $student->asal_sekolah }}</div>
                        </div>
                    @endif

                    @if($student->kelas)
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-semibold">Kelas Terakhir</label>
                            <div class="fw-bold">{{ $student->kelas }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Status Timeline -->
        <div class="card shadow-sm rounded-4 mb-4">
            <div class="card-header py-3" style="background: linear-gradient(to right, #fff3e0, #ffcc02); border-left: 5px solid #ff9800;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-clock-history me-2"></i>Timeline Status
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Pendaftaran</h6>
                            <p class="text-muted small mb-1">{{ $student->created_at->format('d F Y') }}</p>
                            <small class="text-muted">{{ $student->created_at->diffForHumans() }}</small>
                        </div>
                    </div>

                    @if($student->data_verified_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Data Terverifikasi</h6>
                            <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($student->data_verified_at)->format('d F Y') }}</p>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($student->data_verified_at)->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endif

                    @if($student->acceptance_decided_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Diterima</h6>
                            <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($student->acceptance_decided_at)->format('d F Y') }}</p>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($student->acceptance_decided_at)->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endif

                    @if($student->student_activated_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Status Aktif</h6>
                            <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($student->student_activated_at)->format('d F Y') }}</p>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($student->student_activated_at)->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow-sm rounded-4 mb-4">
            <div class="card-header py-3" style="background: linear-gradient(to right, #fce4ec, #f8bbd9); border-left: 5px solid #e91e63;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-lightning-charge me-2"></i>Aksi Cepat
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="document.getElementById('student-tab').click()">
                        <i class="bi bi-person-circle me-2"></i>Lihat Data Siswa
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="document.getElementById('academic-tab').click()">
                        <i class="bi bi-mortarboard me-2"></i>Riwayat Akademik
                    </button>
                    <button class="btn btn-outline-success btn-sm" onclick="document.getElementById('documents-tab').click()">
                        <i class="bi bi-file-earmark-text me-2"></i>Lihat Dokumen
                    </button>
                    <button class="btn btn-outline-warning btn-sm" onclick="updateStatus({{ $student->id }})">
                        <i class="bi bi-diagram-3 me-2"></i>Update Status
                    </button>
                </div>
            </div>
        </div>

        <!-- Status Notes -->
        @if($student->student_status_notes)
        <div class="card shadow-sm rounded-4">
            <div class="card-header py-3" style="background: linear-gradient(to right, #ffebee, #ffcdd2); border-left: 5px solid #f44336;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-sticky me-2"></i>Catatan Status
                </h5>
            </div>
            <div class="card-body p-4">
                <p class="mb-0">{{ $student->student_status_notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0.625rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-marker {
        position: absolute;
        left: -0.75rem;
        top: 0.25rem;
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }

    .timeline-content {
        margin-left: 1rem;
    }

    .timeline-item:last-child::before {
        display: none;
    }
</style>
@endpush
