<!-- Achievements Tab Content -->
<div class="row g-4">
    <div class="col-12">

        @if($achievements && $achievements->count() > 0)
            <!-- Achievements List -->
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #fff3e0, #ffcc02); border-left: 5px solid #ff9800;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-trophy-fill me-2"></i>Prestasi & Penghargaan
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #fffef7;">
                    <div class="row g-4">
                        @foreach($achievements as $achievement)
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ff9800 !important;">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3 flex-shrink-0">
                                            @if($achievement->kategori === 'akademik')
                                                <i class="bi bi-mortarboard text-warning fs-4"></i>
                                            @elseif($achievement->kategori === 'olahraga')
                                                <i class="bi bi-trophy text-warning fs-4"></i>
                                            @elseif($achievement->kategori === 'seni')
                                                <i class="bi bi-palette text-warning fs-4"></i>
                                            @elseif($achievement->kategori === 'kepemimpinan')
                                                <i class="bi bi-person-badge text-warning fs-4"></i>
                                            @else
                                                <i class="bi bi-star text-warning fs-4"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-2">{{ $achievement->nama_prestasi }}</h6>

                                            @if($achievement->kategori)
                                                <span class="badge bg-warning bg-opacity-15 text-warning border border-warning mb-2">
                                                    {{ ucfirst($achievement->kategori) }}
                                                </span>
                                            @endif

                                            @if($achievement->tingkat)
                                                <span class="badge bg-info bg-opacity-15 text-info border border-info mb-2 ms-1">
                                                    {{ ucfirst($achievement->tingkat) }}
                                                </span>
                                            @endif

                                            @if($achievement->juara)
                                                <div class="mt-2">
                                                    <span class="badge bg-success bg-opacity-15 text-success border border-success">
                                                        <i class="bi bi-award me-1"></i>{{ $achievement->juara }}
                                                    </span>
                                                </div>
                                            @endif

                                            @if($achievement->penyelenggara)
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <i class="bi bi-building me-1"></i>{{ $achievement->penyelenggara }}
                                                    </small>
                                                </div>
                                            @endif

                                            @if($achievement->tahun)
                                                <div class="mt-1">
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar me-1"></i>{{ $achievement->tahun }}
                                                    </small>
                                                </div>
                                            @endif

                                            @if($achievement->deskripsi)
                                                <div class="mt-3">
                                                    <p class="text-muted small mb-0">{{ $achievement->deskripsi }}</p>
                                                </div>
                                            @endif

                                            @if($achievement->sertifikat_path)
                                                <div class="mt-3">
                                                    <a href="{{ Storage::url($achievement->sertifikat_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-file-earmark-pdf me-1"></i>Lihat Sertifikat
                                                    </a>
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

            <!-- Achievement Statistics -->
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #e8f5e9, #c8e6c9); border-left: 5px solid #4caf50;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-bar-chart me-2"></i>Statistik Prestasi
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #f8fff8;">
                    <div class="row g-3">
                        @php
                            $stats = [
                                'total' => $achievements->count(),
                                'akademik' => $achievements->where('kategori', 'akademik')->count(),
                                'olahraga' => $achievements->where('kategori', 'olahraga')->count(),
                                'seni' => $achievements->where('kategori', 'seni')->count(),
                                'kepemimpinan' => $achievements->where('kategori', 'kepemimpinan')->count(),
                                'nasional' => $achievements->where('tingkat', 'nasional')->count(),
                                'provinsi' => $achievements->where('tingkat', 'provinsi')->count(),
                                'kabupaten' => $achievements->where('tingkat', 'kabupaten')->count(),
                            ];
                        @endphp

                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                                    <i class="bi bi-trophy text-primary fs-4"></i>
                                </div>
                                <h4 class="fw-bold text-primary mb-1">{{ $stats['total'] }}</h4>
                                <small class="text-muted">Total Prestasi</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                                    <i class="bi bi-mortarboard text-success fs-4"></i>
                                </div>
                                <h4 class="fw-bold text-success mb-1">{{ $stats['akademik'] }}</h4>
                                <small class="text-muted">Akademik</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                                    <i class="bi bi-flag text-warning fs-4"></i>
                                </div>
                                <h4 class="fw-bold text-warning mb-1">{{ $stats['olahraga'] }}</h4>
                                <small class="text-muted">Olahraga</small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 mx-auto mb-2" style="width: 60px; height: 60px;">
                                    <i class="bi bi-palette text-info fs-4"></i>
                                </div>
                                <h4 class="fw-bold text-info mb-1">{{ $stats['seni'] }}</h4>
                                <small class="text-muted">Seni</small>
                            </div>
                        </div>
                    </div>

                    <!-- Achievement Levels -->
                    @if($stats['nasional'] > 0 || $stats['provinsi'] > 0 || $stats['kabupaten'] > 0)
                    <hr class="my-4">
                    <h6 class="fw-bold mb-3">Tingkat Prestasi</h6>
                    <div class="row g-3">
                        @if($stats['nasional'] > 0)
                        <div class="col-md-4">
                            <div class="bg-danger bg-opacity-10 rounded-3 p-3 text-center">
                                <i class="bi bi-globe text-danger fs-4 mb-2"></i>
                                <div class="fw-bold text-danger">{{ $stats['nasional'] }} Prestasi</div>
                                <small class="text-muted">Tingkat Nasional</small>
                            </div>
                        </div>
                        @endif

                        @if($stats['provinsi'] > 0)
                        <div class="col-md-4">
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3 text-center">
                                <i class="bi bi-map text-warning fs-4 mb-2"></i>
                                <div class="fw-bold text-warning">{{ $stats['provinsi'] }} Prestasi</div>
                                <small class="text-muted">Tingkat Provinsi</small>
                            </div>
                        </div>
                        @endif

                        @if($stats['kabupaten'] > 0)
                        <div class="col-md-4">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3 text-center">
                                <i class="bi bi-geo-alt text-info fs-4 mb-2"></i>
                                <div class="fw-bold text-info">{{ $stats['kabupaten'] }} Prestasi</div>
                                <small class="text-muted">Tingkat Kabupaten</small>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        @else
            <!-- No Achievements -->
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-trophy fs-1 text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">Belum Ada Prestasi</h5>
                    <p class="text-muted mb-4">
                        Siswa belum menambahkan data prestasi atau penghargaan.<br>
                        Data akan muncul setelah siswa melengkapi informasi prestasi mereka.
                    </p>

                    <div class="bg-light rounded-3 p-4 mx-auto" style="max-width: 500px;">
                        <h6 class="fw-bold mb-3">Jenis Prestasi yang Dapat Ditambahkan:</h6>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-mortarboard text-primary me-2"></i>
                                    <small>Akademik</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-trophy text-warning me-2"></i>
                                    <small>Olahraga</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-palette text-info me-2"></i>
                                    <small>Seni & Budaya</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-badge text-success me-2"></i>
                                    <small>Kepemimpinan</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-muted small">
                            <i class="bi bi-lightbulb me-1"></i>
                            Siswa dapat menambahkan prestasi melalui dashboard mereka.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
