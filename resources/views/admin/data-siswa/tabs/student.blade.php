<!-- Student Tab Content -->
<div class="row g-4">
    <div class="col-12">
        @if($studentDetail)
            <!-- Personal Information Section -->
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #e8f5e9, #c8e6c9); border-left: 5px solid #4caf50;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-person-badge me-2"></i>Informasi Pribadi
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #f8fff8;">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Nama Lengkap</label>
                            <div class="fw-bold fs-5 text-dark">{{ $studentDetail->nama_lengkap ?? $student->nama_murid }}</div>
                        </div>

                        @if($studentDetail->nama_panggilan)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Nama Panggilan</label>
                            <div class="fw-bold fs-5 text-dark">{{ $studentDetail->nama_panggilan }}</div>
                        </div>
                        @endif

                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="color: #36474f;">NISN</label>
                            <div class="fw-bold">{{ $studentDetail->nisn ?? $student->nisn ?? '-' }}</div>
                        </div>

                        @if($studentDetail->nik)
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="color: #36474f;">NIK</label>
                            <div class="fw-bold">{{ $studentDetail->nik }}</div>
                        </div>
                        @endif

                        @if($studentDetail->no_kk)
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="color: #36474f;">No. Kartu Keluarga</label>
                            <div class="fw-bold">{{ $studentDetail->no_kk }}</div>
                        </div>
                        @endif

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Jenis Kelamin</label>
                            <div class="fw-bold">
                                @if($studentDetail->jenis_kelamin)
                                    <span class="badge bg-{{ $studentDetail->jenis_kelamin === 'L' ? 'primary' : 'pink' }} bg-opacity-15 text-{{ $studentDetail->jenis_kelamin === 'L' ? 'primary' : 'pink' }} px-3 py-2" style="{{ $studentDetail->jenis_kelamin === 'P' ? 'color: #e91e63 !important;' : '' }}">
                                        <i class="bi bi-{{ $studentDetail->jenis_kelamin === 'L' ? 'person' : 'person-dress' }} me-1"></i>
                                        {{ $studentDetail->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Tanggal Lahir</label>
                            <div class="fw-bold">
                                {{ \Carbon\Carbon::parse($studentDetail->tanggal_lahir ?? $student->tanggal_lahir)->format('d F Y') }}
                                <small class="text-muted">({{ \Carbon\Carbon::parse($studentDetail->tanggal_lahir ?? $student->tanggal_lahir)->age }} tahun)</small>
                            </div>
                        </div>

                        @if($studentDetail->tempat_lahir)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Tempat Lahir</label>
                            <div class="fw-bold">{{ $studentDetail->tempat_lahir }}</div>
                        </div>
                        @endif

                        @if($studentDetail->agama)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Agama</label>
                            <div class="fw-bold">{{ $studentDetail->agama }}</div>
                        </div>
                        @endif

                        @if($studentDetail->kewarganegaraan)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Kewarganegaraan</label>
                            <div class="fw-bold">{{ $studentDetail->kewarganegaraan }}</div>
                        </div>
                        @endif

                        @if($studentDetail->anak_ke)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Anak Ke</label>
                            <div class="fw-bold">{{ $studentDetail->anak_ke }}</div>
                        </div>
                        @endif

                        @if($studentDetail->jumlah_saudara)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Jumlah Saudara</label>
                            <div class="fw-bold">{{ $studentDetail->jumlah_saudara }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            @if($studentDetail->alamat_lengkap || $studentDetail->rt || $studentDetail->rw || $studentDetail->kelurahan || $studentDetail->kecamatan)
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #e3f2fd, #bbdefb); border-left: 5px solid #2196f3;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-geo-alt me-2"></i>Alamat
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #f8fcff;">
                    <div class="row g-4">
                        @if($studentDetail->alamat_lengkap)
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="color: #36474f;">Alamat Lengkap</label>
                            <div class="fw-bold">{{ $studentDetail->alamat_lengkap }}</div>
                        </div>
                        @endif

                        @if($studentDetail->rt)
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="color: #36474f;">RT</label>
                            <div class="fw-bold">{{ $studentDetail->rt }}</div>
                        </div>
                        @endif

                        @if($studentDetail->rw)
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="color: #36474f;">RW</label>
                            <div class="fw-bold">{{ $studentDetail->rw }}</div>
                        </div>
                        @endif

                        @if($studentDetail->kelurahan)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Kelurahan</label>
                            <div class="fw-bold">{{ $studentDetail->kelurahan }}</div>
                        </div>
                        @endif

                        @if($studentDetail->kecamatan)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Kecamatan</label>
                            <div class="fw-bold">{{ $studentDetail->kecamatan }}</div>
                        </div>
                        @endif

                        @if($studentDetail->kabupaten_kota)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Kabupaten/Kota</label>
                            <div class="fw-bold">{{ $studentDetail->kabupaten_kota }}</div>
                        </div>
                        @endif

                        @if($studentDetail->provinsi)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Provinsi</label>
                            <div class="fw-bold">{{ $studentDetail->provinsi }}</div>
                        </div>
                        @endif

                        @if($studentDetail->kode_pos)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Kode Pos</label>
                            <div class="fw-bold">{{ $studentDetail->kode_pos }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Contact Information -->
            @if($studentDetail->no_telepon || $studentDetail->email || $studentDetail->kontak_darurat)
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #f3e5f5, #e1bee7); border-left: 5px solid #9c27b0;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-telephone me-2"></i>Kontak
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #fdfaff;">
                    <div class="row g-4">
                        @if($studentDetail->no_telepon)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">No. Telepon</label>
                            <div class="fw-bold">
                                <a href="tel:{{ $studentDetail->no_telepon }}" class="text-decoration-none">
                                    <i class="bi bi-telephone me-1"></i>{{ $studentDetail->no_telepon }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($studentDetail->email)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Email Siswa</label>
                            <div class="fw-bold">
                                <a href="mailto:{{ $studentDetail->email }}" class="text-decoration-none">
                                    <i class="bi bi-envelope me-1"></i>{{ $studentDetail->email }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($studentDetail->kontak_darurat)
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="color: #36474f;">Kontak Darurat</label>
                            <div class="fw-bold">
                                <a href="tel:{{ $studentDetail->kontak_darurat }}" class="text-decoration-none">
                                    <i class="bi bi-telephone-fill me-1"></i>{{ $studentDetail->kontak_darurat }}
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Additional Information -->
            @if($studentDetail->hobi || $studentDetail->cita_cita || $studentDetail->bahasa_sehari_hari)
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #fff3e0, #ffcc02); border-left: 5px solid #ff9800;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-star me-2"></i>Informasi Tambahan
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #fffef7;">
                    <div class="row g-4">
                        @if($studentDetail->hobi)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Hobi</label>
                            <div class="fw-bold">{{ $studentDetail->hobi }}</div>
                        </div>
                        @endif

                        @if($studentDetail->cita_cita)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Cita-cita</label>
                            <div class="fw-bold">{{ $studentDetail->cita_cita }}</div>
                        </div>
                        @endif

                        @if($studentDetail->bahasa_sehari_hari)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Bahasa Sehari-hari</label>
                            <div class="fw-bold">{{ $studentDetail->bahasa_sehari_hari }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        @else
            <!-- No Student Detail Available -->
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-info-circle fs-1 text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">Data Siswa Belum Lengkap</h5>
                    <p class="text-muted mb-4">
                        Siswa belum melengkapi data pribadi mereka. Data yang tersedia hanya informasi dasar dari pendaftaran.
                    </p>

                    <!-- Basic Registration Information -->
                    <div class="card bg-light border-0 mx-auto" style="max-width: 600px;">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Informasi Dasar dari Pendaftaran:</h6>
                            <div class="row g-3 text-start">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">Nama Murid</label>
                                    <div class="fw-bold">{{ $student->nama_murid }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">NISN</label>
                                    <div class="fw-bold">{{ $student->nisn ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">Tanggal Lahir</label>
                                    <div class="fw-bold">{{ \Carbon\Carbon::parse($student->tanggal_lahir)->format('d F Y') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">Unit & Jenjang</label>
                                    <div class="fw-bold">{{ $student->unit }} - {{ $student->jenjang }}</div>
                                </div>
                                @if($student->alamat)
                                <div class="col-12">
                                    <label class="form-label text-muted small">Alamat</label>
                                    <div class="fw-bold">{{ $student->alamat }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-muted small">
                            <i class="bi bi-lightbulb me-1"></i>
                            Siswa dapat melengkapi data pribadi mereka melalui dashboard siswa.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
