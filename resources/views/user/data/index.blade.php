<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-clipboard-data me-2 text-primary"></i>
                Kelengkapan Data Pendaftaran
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Kelengkapan Data</li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Progress Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="fw-bold mb-2">{{ $pendaftar->nama_lengkap ?? 'Data Pendaftar' }}</h4>
                                <p class="mb-0 opacity-90">Nomor Pendaftaran: {{ $pendaftar->nomor_pendaftaran }}</p>
                            </div>
                            <div class="text-end">
                                <div class="h5 mb-1">Progress Kelengkapan</div>
                                <div class="progress bg-white bg-opacity-20" style="height: 8px;">
                                    @php
                                        $completedCount = array_sum($completionStatus);
                                        $totalCount = count($completionStatus);
                                        $percentage = $totalCount > 0 ? ($completedCount / $totalCount) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                                </div>
                                <small class="text-white-50">{{ $completedCount }}/{{ $totalCount }} Selesai</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Sections -->
        <div class="row g-4">
            <!-- Student Details -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 data-card {{ $completionStatus['student'] ? 'completed' : 'incomplete' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper me-3">
                                @if($completionStatus['student'])
                                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                @else
                                    <i class="bi bi-person text-primary fs-3"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold">Data Siswa</h5>
                                <p class="text-muted small mb-0">Informasi pribadi siswa</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $completionStatus['student'] ? 'bg-success' : 'bg-warning' }}"
                                     style="width: {{ $completionStatus['student'] ? '100' : '20' }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge {{ $completionStatus['student'] ? 'bg-success' : 'bg-warning' }}">
                                {{ $completionStatus['student'] ? 'Lengkap' : 'Belum Lengkap' }}
                            </span>
                        </div>

                        <a href="{{ route('user.data.student') }}"
                           class="btn {{ $completionStatus['student'] ? 'btn-outline-success' : 'btn-primary' }} w-100">
                            <i class="bi bi-{{ $completionStatus['student'] ? 'eye' : 'plus' }} me-2"></i>
                            {{ $completionStatus['student'] ? 'Lihat / Edit' : 'Isi Data' }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Parent Details -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 data-card {{ $completionStatus['parent'] ? 'completed' : 'incomplete' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper me-3">
                                @if($completionStatus['parent'])
                                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                @else
                                    <i class="bi bi-people text-primary fs-3"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold">Data Orang Tua</h5>
                                <p class="text-muted small mb-0">Informasi ayah, ibu, wali</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $completionStatus['parent'] ? 'bg-success' : 'bg-warning' }}"
                                     style="width: {{ $completionStatus['parent'] ? '100' : '20' }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge {{ $completionStatus['parent'] ? 'bg-success' : 'bg-warning' }}">
                                {{ $completionStatus['parent'] ? 'Lengkap' : 'Belum Lengkap' }}
                            </span>
                        </div>

                        <a href="{{ route('user.data.parent') }}"
                           class="btn {{ $completionStatus['parent'] ? 'btn-outline-success' : 'btn-primary' }} w-100">
                            <i class="bi bi-{{ $completionStatus['parent'] ? 'eye' : 'plus' }} me-2"></i>
                            {{ $completionStatus['parent'] ? 'Lihat / Edit' : 'Isi Data' }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Academic History -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 data-card {{ $completionStatus['academic'] ? 'completed' : 'incomplete' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper me-3">
                                @if($completionStatus['academic'])
                                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                @else
                                    <i class="bi bi-mortarboard text-primary fs-3"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold">Riwayat Akademik</h5>
                                <p class="text-muted small mb-0">Sekolah sebelumnya</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $completionStatus['academic'] ? 'bg-success' : 'bg-warning' }}"
                                     style="width: {{ $completionStatus['academic'] ? '100' : '20' }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge {{ $completionStatus['academic'] ? 'bg-success' : 'bg-warning' }}">
                                {{ $completionStatus['academic'] ? 'Lengkap' : 'Belum Lengkap' }}
                            </span>
                        </div>

                        <a href="{{ route('user.data.academic') }}"
                           class="btn {{ $completionStatus['academic'] ? 'btn-outline-success' : 'btn-primary' }} w-100">
                            <i class="bi bi-{{ $completionStatus['academic'] ? 'eye' : 'plus' }} me-2"></i>
                            {{ $completionStatus['academic'] ? 'Lihat / Edit' : 'Isi Data' }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Health Records -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 data-card {{ $completionStatus['health'] ? 'completed' : 'incomplete' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper me-3">
                                @if($completionStatus['health'])
                                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                @else
                                    <i class="bi bi-heart-pulse text-info fs-3"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold">Data Kesehatan</h5>
                                <p class="text-muted small mb-0">Riwayat kesehatan siswa</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $completionStatus['health'] ? 'bg-success' : 'bg-info' }}"
                                     style="width: {{ $completionStatus['health'] ? '100' : '20' }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge {{ $completionStatus['health'] ? 'bg-success' : 'bg-info' }}">
                                {{ $completionStatus['health'] ? 'Lengkap' : 'Opsional' }}
                            </span>
                        </div>

                        <a href="{{ route('user.data.health') }}"
                           class="btn {{ $completionStatus['health'] ? 'btn-outline-success' : 'btn-info' }} w-100">
                            <i class="bi bi-{{ $completionStatus['health'] ? 'eye' : 'plus' }} me-2"></i>
                            {{ $completionStatus['health'] ? 'Lihat / Edit' : 'Isi Data' }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 data-card {{ $completionStatus['documents'] ? 'completed' : 'incomplete' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper me-3">
                                @if($completionStatus['documents'])
                                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                @else
                                    <i class="bi bi-file-earmark-arrow-up text-danger fs-3"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold">Dokumen</h5>
                                <p class="text-muted small mb-0">Upload dokumen pendukung</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $completionStatus['documents'] ? 'bg-success' : 'bg-danger' }}"
                                     style="width: {{ $completionStatus['documents'] ? '100' : '20' }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge {{ $completionStatus['documents'] ? 'bg-success' : 'bg-danger' }}">
                                {{ $completionStatus['documents'] ? 'Lengkap' : 'Wajib' }}
                            </span>
                        </div>

                        <a href="{{ route('user.data.documents') }}"
                           class="btn {{ $completionStatus['documents'] ? 'btn-outline-success' : 'btn-danger' }} w-100">
                            <i class="bi bi-{{ $completionStatus['documents'] ? 'eye' : 'upload' }} me-2"></i>
                            {{ $completionStatus['documents'] ? 'Lihat / Upload' : 'Upload Dokumen' }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Grades -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 data-card {{ $completionStatus['grades'] ? 'completed' : 'incomplete' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper me-3">
                                @if($completionStatus['grades'])
                                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                @else
                                    <i class="bi bi-journal-text text-warning fs-3"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold">Raport & Nilai</h5>
                                <p class="text-muted small mb-0">Input nilai raport</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $completionStatus['grades'] ? 'bg-success' : 'bg-warning' }}"
                                     style="width: {{ $completionStatus['grades'] ? '100' : '20' }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge {{ $completionStatus['grades'] ? 'bg-success' : 'bg-warning' }}">
                                {{ $completionStatus['grades'] ? 'Lengkap' : 'Wajib' }}
                            </span>
                        </div>

                        <a href="{{ route('user.data.grades') }}"
                           class="btn {{ $completionStatus['grades'] ? 'btn-outline-success' : 'btn-warning' }} w-100">
                            <i class="bi bi-{{ $completionStatus['grades'] ? 'eye' : 'plus' }} me-2"></i>
                            {{ $completionStatus['grades'] ? 'Lihat / Edit' : 'Input Nilai' }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Achievements -->
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 data-card {{ $completionStatus['achievements'] ? 'completed' : 'incomplete' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-wrapper me-3">
                                @if($completionStatus['achievements'])
                                    <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                @else
                                    <i class="bi bi-trophy text-info fs-3"></i>
                                @endif
                            </div>
                            <div>
                                <h5 class="card-title mb-1 fw-bold">Prestasi</h5>
                                <p class="text-muted small mb-0">Pencapaian & prestasi</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $completionStatus['achievements'] ? 'bg-success' : 'bg-info' }}"
                                     style="width: {{ $completionStatus['achievements'] ? '100' : '20' }}%"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge {{ $completionStatus['achievements'] ? 'bg-success' : 'bg-info' }}">
                                {{ $completionStatus['achievements'] ? 'Lengkap' : 'Opsional' }}
                            </span>
                        </div>

                        <a href="{{ route('user.data.achievements') }}"
                           class="btn {{ $completionStatus['achievements'] ? 'btn-outline-success' : 'btn-info' }} w-100">
                            <i class="bi bi-{{ $completionStatus['achievements'] ? 'eye' : 'plus' }} me-2"></i>
                            {{ $completionStatus['achievements'] ? 'Lihat / Tambah' : 'Tambah Prestasi' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <h5 class="fw-bold mb-3">Langkah Selanjutnya</h5>

                        @php
                            $isAllComplete = array_sum($completionStatus) >= 5; // Minimum 5 sections
                        @endphp

                        @if($isAllComplete)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <strong>Selamat!</strong> Data Anda sudah lengkap dan siap untuk direview.
                            </div>
                            <a href="{{ route('user.data.review') }}" class="btn btn-success btn-lg me-3">
                                <i class="bi bi-eye me-2"></i>Review Data
                            </a>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Lengkapi minimal 5 section untuk dapat melanjutkan ke tahap review.
                            </div>
                        @endif

                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .data-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .data-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }

        .data-card.completed {
            border-left: 4px solid #198754 !important;
        }

        .data-card.incomplete {
            border-left: 4px solid #ffc107 !important;
        }

        .icon-wrapper {
            transition: all 0.3s ease;
        }

        .data-card:hover .icon-wrapper {
            transform: scale(1.1);
        }
    </style>
</x-app-layout>
