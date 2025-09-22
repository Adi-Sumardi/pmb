<!-- Review Tab Content -->
<div class="row g-4">
    <div class="col-12">

        <!-- Review Summary Card -->
        <div class="card shadow-sm rounded-4 mb-4 border-0">
            <div class="card-header py-3" style="background: linear-gradient(to right, #e8f5e9, #c8e6c9); border-left: 5px solid #4caf50;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-clipboard-check me-2"></i>Status Review
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #f8fff8;">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #36474f;">Status Saat Ini</label>
                        <div class="fw-bold fs-5">
                            @if($student->status == 'pending')
                                <span class="badge bg-warning bg-opacity-15 text-warning border border-warning px-3 py-2">
                                    <i class="bi bi-clock me-1"></i>Menunggu Review
                                </span>
                            @elseif($student->status == 'approved')
                                <span class="badge bg-success bg-opacity-15 text-success border border-success px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i>Disetujui
                                </span>
                            @elseif($student->status == 'rejected')
                                <span class="badge bg-danger bg-opacity-15 text-danger border border-danger px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i>Ditolak
                                </span>
                            @elseif($student->status == 'revision')
                                <span class="badge bg-info bg-opacity-15 text-info border border-info px-3 py-2">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Perlu Revisi
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-15 text-secondary border border-secondary px-3 py-2">
                                    <i class="bi bi-question-circle me-1"></i>{{ ucfirst($student->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #36474f;">Tanggal Pendaftaran</label>
                        <div class="fw-bold">
                            {{ $student->created_at->format('d F Y, H:i') }}
                            <small class="text-muted">({{ $student->created_at->diffForHumans() }})</small>
                        </div>
                    </div>

                    @if($student->updated_at != $student->created_at)
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #36474f;">Terakhir Diupdate</label>
                        <div class="fw-bold">
                            {{ $student->updated_at->format('d F Y, H:i') }}
                            <small class="text-muted">({{ $student->updated_at->diffForHumans() }})</small>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #36474f;">No. Pendaftaran</label>
                        <div class="fw-bold">
                            <span class="badge bg-primary bg-opacity-15 text-primary border border-primary px-3 py-2">
                                {{ $student->nomor_pendaftaran ?? 'PPD-' . str_pad($student->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Actions -->
        <div class="card shadow-sm rounded-4 mb-4 border-0">
            <div class="card-header py-3" style="background: linear-gradient(to right, #e3f2fd, #bbdefb); border-left: 5px solid #2196f3;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-gear me-2"></i>Tindakan Review
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #f8fcff;">
                <div class="row g-3">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="bi bi-check-circle me-2"></i>Setujui
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle me-2"></i>Tolak
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#revisionModal">
                            <i class="bi bi-arrow-clockwise me-2"></i>Minta Revisi
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#pendingModal">
                            <i class="bi bi-clock me-2"></i>Pending
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Checklist -->
        <div class="card shadow-sm rounded-4 mb-4 border-0">
            <div class="card-header py-3" style="background: linear-gradient(to right, #fff3e0, #ffcc02); border-left: 5px solid #ff9800;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-list-check me-2"></i>Checklist Dokumen
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #fffef7;">
                @php
                    $requiredDocs = [
                        'Kartu Keluarga' => 'kartu_keluarga',
                        'Akta Kelahiran' => 'akta_kelahiran',
                        'Ijazah' => 'ijazah',
                        'SKHUN' => 'skhun',
                        'Rapor' => 'rapor',
                        'Foto' => 'foto',
                        'KTP Orang Tua' => 'ktp_orangtua',
                        'Surat Keterangan Sehat' => 'surat_sehat',
                        'Surat Keterangan Berkelakuan Baik' => 'surat_berkelakuan_baik'
                    ];

                    $submittedDocs = $documents->pluck('jenis_dokumen')->toArray();
                @endphp

                <div class="row g-3">
                    @foreach($requiredDocs as $docName => $docType)
                        @php
                            $isSubmitted = in_array($docType, $submittedDocs) || in_array($docName, $submittedDocs);
                        @endphp
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between p-3 border rounded-3 {{ $isSubmitted ? 'bg-success bg-opacity-10 border-success' : 'bg-danger bg-opacity-10 border-danger' }}">
                                <div class="d-flex align-items-center">
                                    @if($isSubmitted)
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger me-2"></i>
                                    @endif
                                    <span class="fw-semibold">{{ $docName }}</span>
                                </div>
                                @if($isSubmitted)
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-danger">Belum</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @php
                    $totalRequired = count($requiredDocs);
                    $totalSubmitted = collect($requiredDocs)->filter(function($docType, $docName) use ($submittedDocs) {
                        return in_array($docType, $submittedDocs) || in_array($docName, $submittedDocs);
                    })->count();
                    $completionPercentage = $totalRequired > 0 ? round(($totalSubmitted / $totalRequired) * 100) : 0;
                @endphp

                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Kelengkapan Dokumen</span>
                        <span class="fw-bold">{{ $totalSubmitted }}/{{ $totalRequired }} ({{ $completionPercentage }}%)</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-{{ $completionPercentage >= 80 ? 'success' : ($completionPercentage >= 50 ? 'warning' : 'danger') }}"
                             role="progressbar"
                             style="width: {{ $completionPercentage }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        @if($payments->isNotEmpty())
        <div class="card shadow-sm rounded-4 mb-4 border-0">
            <div class="card-header py-3" style="background: linear-gradient(to right, #f3e5f5, #e1bee7); border-left: 5px solid #9c27b0;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-credit-card me-2"></i>Status Pembayaran
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #fdfaff;">
                <div class="row g-3">
                    @foreach($payments as $payment)
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $payment->jenis_pembayaran ?? 'Pembayaran' }}</h6>
                                        <p class="text-muted small mb-2">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    @if($payment->status == 'confirmed')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif($payment->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </div>

                                <div class="fw-bold text-primary fs-5">
                                    Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                </div>

                                @if($payment->metode_pembayaran)
                                <div class="text-muted small mt-2">
                                    <i class="bi bi-credit-card me-1"></i>{{ $payment->metode_pembayaran }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Review Timeline -->
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-header py-3" style="background: linear-gradient(to right, #ffebee, #ffcdd2); border-left: 5px solid #f44336;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-clock-history me-2"></i>Timeline Review
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #fffafa;">
                <div class="timeline">
                    <!-- Registration -->
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Pendaftaran Dibuat</h6>
                            <p class="text-muted mb-1">{{ $student->created_at->format('d F Y, H:i') }}</p>
                            <small class="text-muted">Calon siswa melakukan pendaftaran online</small>
                        </div>
                    </div>

                    <!-- Status Updates -->
                    @if($student->updated_at != $student->created_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Status Diperbarui</h6>
                            <p class="text-muted mb-1">{{ $student->updated_at->format('d F Y, H:i') }}</p>
                            <small class="text-muted">Status berubah menjadi:
                                <span class="fw-semibold">{{ ucfirst($student->status) }}</span>
                            </small>
                        </div>
                    </div>
                    @endif

                    <!-- Document Submissions -->
                    @foreach($documents->take(3) as $doc)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Dokumen Diunggah</h6>
                            <p class="text-muted mb-1">{{ $doc->created_at->format('d F Y, H:i') }}</p>
                            <small class="text-muted">{{ $doc->jenis_dokumen }} - {{ $doc->nama_file }}</small>
                        </div>
                    </div>
                    @endforeach

                    <!-- Payments -->
                    @foreach($payments->take(2) as $payment)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Pembayaran</h6>
                            <p class="text-muted mb-1">{{ $payment->created_at->format('d F Y, H:i') }}</p>
                            <small class="text-muted">
                                {{ $payment->jenis_pembayaran ?? 'Pembayaran' }} -
                                Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                ({{ ucfirst($payment->status) }})
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS for Timeline -->
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -38px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #dee2e6;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-item:last-child::before {
    display: none;
}
</style>
