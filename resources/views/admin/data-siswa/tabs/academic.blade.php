<!-- Academic Tab Content -->
<div class="row g-4">
    <div class="col-12">

        <!-- Current Enrollment Info -->
        <div class="card shadow-sm rounded-4 mb-4 border-0">
            <div class="card-header py-3" style="background: linear-gradient(to right, #e8f5e9, #c8e6c9); border-left: 5px solid #4caf50;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-mortarboard me-2"></i>Status Akademik Saat Ini
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #f8fff8;">
                <div class="row g-4">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="color: #36474f;">Unit Pendidikan</label>
                        <div class="fw-bold fs-5">
                            <span class="badge bg-success bg-opacity-15 text-success border border-success px-3 py-2">
                                <i class="bi bi-building me-1"></i>{{ strtoupper($student->unit) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="color: #36474f;">Jenjang</label>
                        <div class="fw-bold fs-5">{{ $student->jenjang }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="color: #36474f;">Tahun Ajaran</label>
                        <div class="fw-bold fs-5">{{ $student->academic_year ?? '2026/2027' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="color: #36474f;">Status</label>
                        <div class="fw-bold fs-5">
                            @php
                                $statusConfig = [
                                    'active' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Aktif'],
                                    'inactive' => ['class' => 'warning', 'icon' => 'pause-circle', 'text' => 'Tidak Aktif'],
                                    'graduated' => ['class' => 'info', 'icon' => 'mortarboard', 'text' => 'Lulus'],
                                    'dropped_out' => ['class' => 'danger', 'icon' => 'x-circle', 'text' => 'Keluar'],
                                    'transferred' => ['class' => 'secondary', 'icon' => 'arrow-right-circle', 'text' => 'Pindah'],
                                ];
                                $currentStatus = $statusConfig[$student->student_status] ?? $statusConfig['inactive'];
                            @endphp
                            <span class="badge bg-{{ $currentStatus['class'] }} bg-opacity-15 text-{{ $currentStatus['class'] }} border border-{{ $currentStatus['class'] }} px-3 py-2">
                                <i class="bi bi-{{ $currentStatus['icon'] }} me-1"></i>{{ $currentStatus['text'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- School Origin Info -->
        @if($student->asal_sekolah || $student->nama_sekolah || $student->kelas)
        <div class="card shadow-sm rounded-4 mb-4 border-0">
            <div class="card-header py-3" style="background: linear-gradient(to right, #e3f2fd, #bbdefb); border-left: 5px solid #2196f3;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-bank me-2"></i>Asal Sekolah
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #f8fcff;">
                <div class="row g-4">
                    @if($student->nama_sekolah || $student->asal_sekolah)
                    <div class="col-md-8">
                        <label class="form-label fw-semibold" style="color: #36474f;">Nama Sekolah</label>
                        <div class="fw-bold fs-5">{{ $student->nama_sekolah ?? $student->asal_sekolah }}</div>
                    </div>
                    @endif

                    @if($student->kelas)
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="color: #36474f;">Kelas Terakhir</label>
                        <div class="fw-bold fs-5">
                            <span class="badge bg-primary bg-opacity-15 text-primary border border-primary px-3 py-2">
                                {{ $student->kelas }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Academic History -->
        @if($academicHistory && $academicHistory->count() > 0)
        <div class="card shadow-sm rounded-4 mb-4 border-0">
            <div class="card-header py-3" style="background: linear-gradient(to right, #f3e5f5, #e1bee7); border-left: 5px solid #9c27b0;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Pendidikan
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #fdfaff;">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Jenjang</th>
                                <th>Nama Sekolah</th>
                                <th>Tahun Masuk</th>
                                <th>Tahun Lulus</th>
                                <th>Nilai Akhir</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($academicHistory as $history)
                            <tr>
                                <td>
                                    <span class="badge bg-info bg-opacity-15 text-info border border-info">
                                        {{ $history->jenjang }}
                                    </span>
                                </td>
                                <td class="fw-semibold">{{ $history->nama_sekolah }}</td>
                                <td>{{ $history->tahun_masuk ?? '-' }}</td>
                                <td>{{ $history->tahun_lulus ?? '-' }}</td>
                                <td>
                                    @if($history->nilai_akhir)
                                        <span class="badge bg-success bg-opacity-15 text-success border border-success">
                                            {{ $history->nilai_akhir }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($history->status_kelulusan)
                                        <span class="badge bg-{{ $history->status_kelulusan === 'Lulus' ? 'success' : 'warning' }} bg-opacity-15 text-{{ $history->status_kelulusan === 'Lulus' ? 'success' : 'warning' }} border border-{{ $history->status_kelulusan === 'Lulus' ? 'success' : 'warning' }}">
                                            <i class="bi bi-{{ $history->status_kelulusan === 'Lulus' ? 'check-circle' : 'clock' }} me-1"></i>
                                            {{ $history->status_kelulusan }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Test Results / Grades Section -->
        @if($student->test_score || $student->test_notes)
        <div class="card shadow-sm rounded-4 mb-4 border-0">
            <div class="card-header py-3" style="background: linear-gradient(to right, #fff3e0, #ffcc02); border-left: 5px solid #ff9800;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-clipboard-data me-2"></i>Hasil Tes Masuk
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #fffef7;">
                <div class="row g-4">
                    @if($student->test_score)
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #36474f;">Nilai Tes</label>
                        <div class="fw-bold fs-3">
                            @php
                                $scoreColor = 'success';
                                if($student->test_score < 60) $scoreColor = 'danger';
                                elseif($student->test_score < 75) $scoreColor = 'warning';
                                elseif($student->test_score < 85) $scoreColor = 'info';
                            @endphp
                            <span class="badge bg-{{ $scoreColor }} bg-opacity-15 text-{{ $scoreColor }} border border-{{ $scoreColor }} px-4 py-3">
                                {{ $student->test_score }}
                            </span>
                        </div>
                        @if($student->test_scheduled_at)
                            <small class="text-muted">
                                <i class="bi bi-calendar-event me-1"></i>
                                Tes pada: {{ \Carbon\Carbon::parse($student->test_scheduled_at)->format('d F Y') }}
                            </small>
                        @endif
                    </div>
                    @endif

                    @if($student->test_notes)
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color: #36474f;">Catatan Tes</label>
                        <div class="bg-light rounded-3 p-3">
                            <p class="mb-0">{{ $student->test_notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Academic Timeline -->
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-header py-3" style="background: linear-gradient(to right, #ffebee, #ffcdd2); border-left: 5px solid #f44336;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-timeline me-2"></i>Timeline Akademik
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #fffafa;">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Pendaftaran</h6>
                            <p class="text-muted small mb-1">{{ $student->created_at->format('d F Y') }}</p>
                            <small class="text-muted">Mendaftar di {{ $student->unit }} - {{ $student->jenjang }}</small>
                        </div>
                    </div>

                    @if($student->data_verified_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Verifikasi Data</h6>
                            <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($student->data_verified_at)->format('d F Y') }}</p>
                            <small class="text-muted">Data pendaftaran telah diverifikasi</small>
                        </div>
                    </div>
                    @endif

                    @if($student->test_scheduled_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Tes Masuk</h6>
                            <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($student->test_scheduled_at)->format('d F Y') }}</p>
                            @if($student->test_score)
                                <small class="text-muted">Nilai: {{ $student->test_score }}</small>
                            @else
                                <small class="text-muted">Tes telah dijadwalkan</small>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($student->acceptance_decided_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Keputusan Penerimaan</h6>
                            <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($student->acceptance_decided_at)->format('d F Y') }}</p>
                            <small class="text-muted">Status: {{ $student->overall_status }}</small>
                        </div>
                    </div>
                    @endif

                    @if($student->student_activated_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-purple"></div>
                        <div class="timeline-content">
                            <h6 class="fw-bold mb-1">Aktivasi Siswa</h6>
                            <p class="text-muted small mb-1">{{ \Carbon\Carbon::parse($student->student_activated_at)->format('d F Y') }}</p>
                            <small class="text-muted">Siswa resmi terdaftar dan aktif</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- No Academic History Message -->
        @if(!$academicHistory || $academicHistory->count() === 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-journal-bookmark fs-1 text-muted"></i>
                    </div>
                    <h6 class="text-muted mb-3">Belum Ada Riwayat Pendidikan</h6>
                    <p class="text-muted">
                        Siswa belum menambahkan riwayat pendidikan sebelumnya.<br>
                        Data akan muncul setelah siswa melengkapi informasi akademik mereka.
                    </p>
                </div>
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

    .bg-purple {
        background-color: #9c27b0 !important;
    }
</style>
@endpush
