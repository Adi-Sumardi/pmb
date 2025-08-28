<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-trophy me-2 text-primary"></i>
                Prestasi & Pencapaian
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.data') }}">Kelengkapan Data</a></li>
                    <li class="breadcrumb-item active">Prestasi</li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row">
            <!-- Input Form -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm sticky-top">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-plus-circle me-2"></i>
                            Tambah Prestasi Baru
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('user.data.achievements.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="nama_prestasi" class="form-label fw-semibold">
                                    Nama Prestasi <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('nama_prestasi') is-invalid @enderror"
                                       id="nama_prestasi" name="nama_prestasi"
                                       placeholder="Contoh: Juara 1 Olimpiade Matematika" required>
                                @error('nama_prestasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label for="jenis_prestasi" class="form-label fw-semibold">
                                        Jenis Prestasi <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('jenis_prestasi') is-invalid @enderror"
                                            id="jenis_prestasi" name="jenis_prestasi" required>
                                        <option value="">Pilih Jenis</option>
                                        <option value="Akademik">Akademik</option>
                                        <option value="Olahraga">Olahraga</option>
                                        <option value="Seni">Seni</option>
                                        <option value="Teknologi">Teknologi</option>
                                        <option value="Bahasa">Bahasa</option>
                                        <option value="Karya Ilmiah">Karya Ilmiah</option>
                                        <option value="Kepemimpinan">Kepemimpinan</option>
                                        <option value="Sosial">Sosial</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    @error('jenis_prestasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label for="tingkat" class="form-label fw-semibold">
                                        Tingkat <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('tingkat') is-invalid @enderror"
                                            id="tingkat" name="tingkat" required>
                                        <option value="">Pilih Tingkat</option>
                                        <option value="Sekolah">Sekolah</option>
                                        <option value="Kecamatan">Kecamatan</option>
                                        <option value="Kabupaten">Kabupaten</option>
                                        <option value="Provinsi">Provinsi</option>
                                        <option value="Nasional">Nasional</option>
                                        <option value="Internasional">Internasional</option>
                                    </select>
                                    @error('tingkat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label for="tahun" class="form-label fw-semibold">
                                        Tahun <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control @error('tahun') is-invalid @enderror"
                                           id="tahun" name="tahun" min="2000" max="{{ date('Y') }}"
                                           value="{{ date('Y') }}" required>
                                    @error('tahun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label for="penyelenggara" class="form-label fw-semibold">
                                        Penyelenggara <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('penyelenggara') is-invalid @enderror"
                                           id="penyelenggara" name="penyelenggara"
                                           placeholder="Contoh: Dinas Pendidikan" required>
                                    @error('penyelenggara')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="keterangan" class="form-label fw-semibold">Keterangan Tambahan</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                          id="keterangan" name="keterangan" rows="3"
                                          placeholder="Deskripsi prestasi, detail kompetisi, dll."></textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-info w-100">
                                <i class="bi bi-plus-lg me-2"></i>Tambah Prestasi
                            </button>
                        </form>

                        <!-- Achievement Tips -->
                        <div class="mt-4">
                            <h6 class="fw-bold text-primary">Tips Menulis Prestasi:</h6>
                            <ul class="small text-muted mb-0">
                                <li>Tulis nama prestasi dengan jelas</li>
                                <li>Sertakan peringkat/posisi yang diraih</li>
                                <li>Cantumkan tahun yang tepat</li>
                                <li>Sebutkan penyelenggara resmi</li>
                                <li>Tambahkan detail di keterangan jika perlu</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievements List -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-award me-2"></i>
                                Daftar Prestasi
                            </h5>
                            <span class="badge bg-primary">{{ count($achievements) }} Prestasi</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if(count($achievements) > 0)
                            <div class="row g-0">
                                @foreach($achievements as $achievement)
                                <div class="col-12">
                                    <div class="border-bottom p-4 achievement-item">
                                        <div class="d-flex align-items-start">
                                            <div class="achievement-icon me-3">
                                                @php
                                                    $iconClass = 'bi-trophy';
                                                    $badgeClass = 'bg-warning';

                                                    switch($achievement->tingkat) {
                                                        case 'Internasional':
                                                            $iconClass = 'bi-globe';
                                                            $badgeClass = 'bg-danger';
                                                            break;
                                                        case 'Nasional':
                                                            $iconClass = 'bi-flag';
                                                            $badgeClass = 'bg-success';
                                                            break;
                                                        case 'Provinsi':
                                                            $iconClass = 'bi-geo-alt';
                                                            $badgeClass = 'bg-primary';
                                                            break;
                                                        case 'Kabupaten':
                                                            $iconClass = 'bi-building';
                                                            $badgeClass = 'bg-info';
                                                            break;
                                                        default:
                                                            $iconClass = 'bi-trophy';
                                                            $badgeClass = 'bg-warning';
                                                    }
                                                @endphp
                                                <div class="icon-wrapper {{ $badgeClass }} text-white p-3 rounded-circle">
                                                    <i class="{{ $iconClass }} fs-4"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="fw-bold mb-1">{{ $achievement->nama_prestasi }}</h6>
                                                    <div class="text-end">
                                                        <span class="badge {{ $badgeClass }}">{{ $achievement->tingkat }}</span>
                                                    </div>
                                                </div>

                                                <div class="row g-2 mb-2">
                                                    <div class="col-md-6">
                                                        <small class="text-muted">
                                                            <i class="bi bi-tag me-1"></i>{{ $achievement->jenis_prestasi }}
                                                        </small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted">
                                                            <i class="bi bi-calendar me-1"></i>{{ $achievement->tahun }}
                                                        </small>
                                                    </div>
                                                </div>

                                                <p class="text-muted small mb-2">
                                                    <i class="bi bi-building me-1"></i>
                                                    <strong>Penyelenggara:</strong> {{ $achievement->penyelenggara }}
                                                </p>

                                                @if($achievement->keterangan)
                                                <div class="achievement-description">
                                                    <p class="small text-dark mb-2">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        {{ $achievement->keterangan }}
                                                    </p>
                                                </div>
                                                @endif

                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock me-1"></i>
                                                        Ditambahkan {{ $achievement->created_at->format('d M Y') }}
                                                    </small>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                                onclick="editAchievement({{ $achievement->id }})">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                                onclick="deleteAchievement({{ $achievement->id }})">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-trophy text-muted" style="font-size: 4rem;"></i>
                                <h5 class="text-muted mt-3">Belum Ada Prestasi</h5>
                                <p class="text-muted">Tambahkan prestasi pertama Anda menggunakan form di sebelah kiri.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Achievement Statistics -->
                @if(count($achievements) > 0)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Statistik Prestasi</h6>
                        @php
                            $jenisCount = $achievements->groupBy('jenis_prestasi')->map->count();
                            $tingkatCount = $achievements->groupBy('tingkat')->map->count();
                        @endphp

                        <div class="row g-3">
                            <div class="col-md-6">
                                <h6 class="small text-muted mb-2">Berdasarkan Jenis:</h6>
                                @foreach($jenisCount as $jenis => $count)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">{{ $jenis }}</span>
                                    <span class="badge bg-secondary">{{ $count }}</span>
                                </div>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <h6 class="small text-muted mb-2">Berdasarkan Tingkat:</h6>
                                @foreach($tingkatCount as $tingkat => $count)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">{{ $tingkat }}</span>
                                    <span class="badge bg-secondary">{{ $count }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('user.data') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Menu Utama
                </a>
            </div>
        </div>
    </div>

    <script>
        function editAchievement(achievementId) {
            // Add edit functionality here
            alert('Fitur edit akan segera tersedia');
        }

        function deleteAchievement(achievementId) {
            if (confirm('Apakah Anda yakin ingin menghapus prestasi ini?')) {
                fetch(`/user/data/achievements/${achievementId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Gagal menghapus prestasi');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
            }
        }

        // Auto-suggest based on jenis prestasi
        document.getElementById('jenis_prestasi').addEventListener('change', function() {
            const penyelenggaraInput = document.getElementById('penyelenggara');
            if (!penyelenggaraInput.value) {
                switch(this.value) {
                    case 'Akademik':
                        penyelenggaraInput.value = 'Dinas Pendidikan';
                        break;
                    case 'Olahraga':
                        penyelenggaraInput.value = 'KONI';
                        break;
                    case 'Seni':
                        penyelenggaraInput.value = 'Dinas Kebudayaan';
                        break;
                    case 'Teknologi':
                        penyelenggaraInput.value = 'Kementerian Pendidikan';
                        break;
                }
            }
        });
    </script>

    <style>
        .achievement-item {
            transition: all 0.3s ease;
        }

        .achievement-item:hover {
            background-color: #f8f9fa;
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .achievement-description {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #17a2b8;
        }
    </style>
</x-app-layout>
