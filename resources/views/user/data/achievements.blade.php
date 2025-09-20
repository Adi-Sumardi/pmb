<x-app-layout>
    <!-- Subtle gradient background to the entire page -->
    <div class="container-fluid py-4" style="background: linear-gradient(to bottom right, #f8f9fa, #e9effd);">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                    <!-- Enhanced gradient header with vibrant colors -->
                    <div class="card-header border-0" style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                        <div class="row align-items-center py-4">
                            <div class="col">
                                <h3 class="text-white fw-bold mb-0">
                                    <i class="bi bi-trophy me-2"></i>
                                    Prestasi & Pencapaian
                                </h3>
                                <p class="text-white opacity-75 mb-0">Catat prestasi dan pencapaian yang pernah diraih</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Input Form -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-4 sticky-top">
                    <div class="card-header py-3" style="background: linear-gradient(to right, #e3f2fd, #bbdefb); border-left: 5px solid #1976d2;">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-plus-circle me-2"></i>
                            Tambah Prestasi Baru
                        </h5>
                    </div>
                    <div class="card-body p-4" style="background-color: #f5fbff;">
                        <form action="{{ route('user.data.achievements.store') }}" method="POST" class="needs-validation">
                            @csrf

                            <div class="mb-3">
                                <label for="nama_prestasi" class="form-label fw-semibold" style="color: #36474f;">
                                    Nama Prestasi <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nama_prestasi') is-invalid @enderror"
                                       id="nama_prestasi" name="nama_prestasi"
                                       placeholder="Contoh: Juara 1 Olimpiade Matematika" required>
                                @error('nama_prestasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label for="jenis_prestasi" class="form-label fw-semibold" style="color: #36474f;">
                                        Jenis Prestasi <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('jenis_prestasi') is-invalid @enderror"
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
                                    <label for="tingkat" class="form-label fw-semibold" style="color: #36474f;">
                                        Tingkat <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('tingkat') is-invalid @enderror"
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
                                    <label for="tahun" class="form-label fw-semibold" style="color: #36474f;">
                                        Tahun <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('tahun') is-invalid @enderror"
                                           id="tahun" name="tahun" min="2000" max="{{ date('Y') }}"
                                           value="{{ date('Y') }}" required>
                                    @error('tahun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label for="penyelenggara" class="form-label fw-semibold" style="color: #36474f;">
                                        Penyelenggara <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('penyelenggara') is-invalid @enderror"
                                           id="penyelenggara" name="penyelenggara"
                                           placeholder="Contoh: Dinas Pendidikan" required>
                                    @error('penyelenggara')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="keterangan" class="form-label fw-semibold" style="color: #36474f;">Keterangan Tambahan</label>
                                <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('keterangan') is-invalid @enderror"
                                          id="keterangan" name="keterangan" rows="3"
                                          placeholder="Deskripsi prestasi, detail kompetisi, dll."></textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-plus-lg me-2"></i>Tambah Prestasi
                            </button>
                        </form>

                        <!-- Achievement Tips -->
                        <div class="mt-4 p-3 rounded-3" style="background-color: #e8f5e9; border-left: 4px solid #43a047;">
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
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header py-3" style="background: linear-gradient(to right, #fff8e1, #ffecb3); border-left: 5px solid #ffc107;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="bi bi-award me-2"></i>
                                Daftar Prestasi
                            </h5>
                            <span class="badge bg-primary">{{ count($achievements) }} Prestasi</span>
                        </div>
                    </div>
                    <div class="card-body p-0" style="background-color: #fffbf2;">
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
                                                <div class="icon-wrapper {{ $badgeClass }} text-white p-3 rounded-circle shadow-sm">
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
                                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-3 me-1"
                                                                onclick="editAchievement({{ $achievement->id }})">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-3"
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
                <div class="card border-0 shadow-lg rounded-4 mt-4">
                    <div class="card-header py-3" style="background: linear-gradient(to right, #f3e5f5, #e1bee7); border-left: 5px solid #9c27b0;">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-graph-up me-2"></i>Statistik Prestasi
                        </h5>
                    </div>
                    <div class="card-body p-4" style="background-color: #fcf8ff;">
                        @php
                            $jenisCount = $achievements->groupBy('jenis_prestasi')->map->count();
                            $tingkatCount = $achievements->groupBy('tingkat')->map->count();
                        @endphp

                        <div class="row g-3">
                            <div class="col-md-6">
                                <h6 class="small fw-semibold mb-2">Berdasarkan Jenis:</h6>
                                @foreach($jenisCount as $jenis => $count)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">{{ $jenis }}</span>
                                    <span class="badge bg-primary">{{ $count }}</span>
                                </div>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <h6 class="small fw-semibold mb-2">Berdasarkan Tingkat:</h6>
                                @foreach($tingkatCount as $tingkat => $count)
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">{{ $tingkat }}</span>
                                    <span class="badge bg-primary">{{ $count }}</span>
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
                <a href="{{ route('user.data.index') }}" class="btn btn-outline-secondary btn-lg px-4" style="transition: all 0.3s">
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

        // Hover effects for buttons and cards
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to buttons
            document.querySelectorAll('.btn').forEach(button => {
                button.addEventListener('mouseover', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1)';
                });

                button.addEventListener('mouseout', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
                });
            });
        });
    </script>

    <style>
        .achievement-item {
            transition: all 0.3s ease;
        }

        .achievement-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .achievement-item:hover .icon-wrapper {
            transform: scale(1.1);
        }

        .achievement-description {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            border-left: 4px solid #17a2b8;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</x-app-layout>
