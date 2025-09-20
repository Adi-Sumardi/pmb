<x-app-layout>
    <!-- Add a subtle gradient background to the entire page -->
    <div class="container-fluid py-4" style="background: linear-gradient(to bottom right, #f8f9fa, #e9effd);">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Enhanced gradient header with more vibrant colors -->
                    <div class="card-header border-0" style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                        <div class="row align-items-center py-4">
                            <div class="col">
                                <h3 class="text-white fw-bold mb-0">Data Siswa</h3>
                                <p class="text-white opacity-75 mb-0">Lengkapi informasi personal siswa</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm rounded-3" style="background-color: #ffebee; border-left: 4px solid #f44336;">
                                <i class="bi bi-exclamation-circle-fill me-2" style="color: #d32f2f;"></i>
                                <strong>Terdapat kesalahan pada pengisian form:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert border-0 shadow-sm rounded-3" style="background-color: #e8f5e9; border-left: 4px solid #43a047;">
                                <i class="bi bi-check-circle-fill me-2" style="color: #2e7d32;"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('user.data.student.store', $pendaftar->id) }}" method="POST" class="needs-validation" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <!-- Hidden input for pendaftar_id -->
                            <input type="hidden" name="pendaftar_id" value="{{ $pendaftar->id }}">

                            <!-- Personal Information Section with colorful header -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #e8f5e9, #c8e6c9); border-left: 5px solid #4caf50;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-person-badge me-2"></i>Informasi Pribadi
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #f8fff8;">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="nama_lengkap" class="form-label fw-semibold" style="color: #36474f;">Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nama_lengkap') is-invalid @enderror"
                                                id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $studentDetail->nama_lengkap ?? $pendaftar->nama_murid ?? '') }}" required>
                                            @error('nama_lengkap')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="nama_panggilan" class="form-label fw-semibold" style="color: #36474f;">Nama Panggilan</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nama_panggilan') is-invalid @enderror"
                                                id="nama_panggilan" name="nama_panggilan" value="{{ old('nama_panggilan', $studentDetail->nama_panggilan ?? '') }}">
                                            @error('nama_panggilan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="nisn" class="form-label fw-semibold" style="color: #36474f;">NISN</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nisn') is-invalid @enderror"
                                                id="nisn" name="nisn" value="{{ old('nisn', $studentDetail->nisn ?? $pendaftar->nisn ?? '') }}">
                                            @error('nisn')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Nomor Induk Siswa Nasional (jika ada)</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="nik" class="form-label fw-semibold" style="color: #36474f;">NIK <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nik') is-invalid @enderror"
                                                id="nik" name="nik" value="{{ old('nik', $studentDetail->nik ?? '') }}" required>
                                            @error('nik')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Nomor Induk Kependudukan</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="no_kk" class="form-label fw-semibold" style="color: #36474f;">No. Kartu Keluarga <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('no_kk') is-invalid @enderror"
                                                id="no_kk" name="no_kk" value="{{ old('no_kk', $studentDetail->no_kk ?? '') }}" required>
                                            @error('no_kk')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Birth Information Section -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #e3f2fd, #bbdefb); border-left: 5px solid #2196f3;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-calendar-heart me-2"></i>Informasi Kelahiran
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #f5fcff;">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="tempat_lahir" class="form-label fw-semibold" style="color: #36474f;">Tempat Lahir <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('tempat_lahir') is-invalid @enderror"
                                                id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $studentDetail->tempat_lahir ?? '') }}" required>
                                            @error('tempat_lahir')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="tanggal_lahir" class="form-label fw-semibold" style="color: #36474f;">Tanggal Lahir <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('tanggal_lahir') is-invalid @enderror"
                                                id="tanggal_lahir" name="tanggal_lahir"
                                                value="{{ old('tanggal_lahir', ($studentDetail && $studentDetail->tanggal_lahir) ? $studentDetail->tanggal_lahir->format('Y-m-d') :
                                                    (($pendaftar && $pendaftar->tanggal_lahir) ? $pendaftar->tanggal_lahir->format('Y-m-d') : '')
                                                ) }}" required>
                                            @error('tanggal_lahir')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="jenis_kelamin" class="form-label fw-semibold" style="color: #36474f;">Jenis Kelamin <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('jenis_kelamin') is-invalid @enderror"
                                                id="jenis_kelamin" name="jenis_kelamin" required>
                                                <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki" {{ old('jenis_kelamin', $studentDetail->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                <option value="Perempuan" {{ old('jenis_kelamin', $studentDetail->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                            @error('jenis_kelamin')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="agama" class="form-label fw-semibold" style="color: #36474f;">Agama <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('agama') is-invalid @enderror"
                                                id="agama" name="agama" required>
                                                <option value="" selected disabled>Pilih Agama</option>
                                                <option value="Islam" {{ old('agama', $studentDetail->agama ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ old('agama', $studentDetail->agama ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                                <option value="Katolik" {{ old('agama', $studentDetail->agama ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                                <option value="Hindu" {{ old('agama', $studentDetail->agama ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Buddha" {{ old('agama', $studentDetail->agama ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                                <option value="Khonghucu" {{ old('agama', $studentDetail->agama ?? '') == 'Khonghucu' ? 'selected' : '' }}>Khonghucu</option>
                                                <option value="Lainnya" {{ old('agama', $studentDetail->agama ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            @error('agama')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="kewarganegaraan" class="form-label fw-semibold" style="color: #36474f;">Kewarganegaraan</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('kewarganegaraan') is-invalid @enderror"
                                                id="kewarganegaraan" name="kewarganegaraan" value="{{ old('kewarganegaraan', $studentDetail->kewarganegaraan ?? 'Indonesia') }}">
                                            @error('kewarganegaraan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <label for="bahasa_sehari_hari" class="form-label fw-semibold" style="color: #36474f;">Bahasa Sehari-hari</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('bahasa_sehari_hari') is-invalid @enderror"
                                                id="bahasa_sehari_hari" name="bahasa_sehari_hari" value="{{ old('bahasa_sehari_hari', $studentDetail->bahasa_sehari_hari ?? 'Bahasa Indonesia') }}">
                                            @error('bahasa_sehari_hari')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Physical Information Section -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #fff8e1, #ffecb3); border-left: 5px solid #ffc107;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-rulers me-2"></i>Informasi Fisik
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #fffcf5;">
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <label for="tinggi_badan" class="form-label fw-semibold" style="color: #36474f;">Tinggi Badan (cm) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('tinggi_badan') is-invalid @enderror"
                                                id="tinggi_badan" name="tinggi_badan" value="{{ old('tinggi_badan', $studentDetail->tinggi_badan ?? '') }}" min="50" max="250" required>
                                            @error('tinggi_badan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="berat_badan" class="form-label fw-semibold" style="color: #36474f;">Berat Badan (kg) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('berat_badan') is-invalid @enderror"
                                                id="berat_badan" name="berat_badan" value="{{ old('berat_badan', $studentDetail->berat_badan ?? '') }}" min="1" max="200" required>
                                            @error('berat_badan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="golongan_darah" class="form-label fw-semibold" style="color: #36474f;">Golongan Darah</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('golongan_darah') is-invalid @enderror"
                                                id="golongan_darah" name="golongan_darah">
                                                <option value="" selected disabled>Pilih Golongan Darah</option>
                                                <option value="A" {{ old('golongan_darah', $studentDetail->golongan_darah ?? '') == 'A' ? 'selected' : '' }}>A</option>
                                                <option value="B" {{ old('golongan_darah', $studentDetail->golongan_darah ?? '') == 'B' ? 'selected' : '' }}>B</option>
                                                <option value="AB" {{ old('golongan_darah', $studentDetail->golongan_darah ?? '') == 'AB' ? 'selected' : '' }}>AB</option>
                                                <option value="O" {{ old('golongan_darah', $studentDetail->golongan_darah ?? '') == 'O' ? 'selected' : '' }}>O</option>
                                                <option value="Tidak Tahu" {{ old('golongan_darah', $studentDetail->golongan_darah ?? '') == 'Tidak Tahu' ? 'selected' : '' }}>Tidak Tahu</option>
                                            </select>
                                            @error('golongan_darah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Section -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #f3e5f5, #e1bee7); border-left: 5px solid #9c27b0;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-geo-alt me-2"></i>Alamat Tempat Tinggal
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #fcf8ff;">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <label for="alamat_lengkap" class="form-label fw-semibold" style="color: #36474f;">Alamat Lengkap <span class="text-danger">*</span></label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('alamat_lengkap') is-invalid @enderror"
                                                id="alamat_lengkap" name="alamat_lengkap" rows="3" required>{{ old('alamat_lengkap', $studentDetail->alamat_lengkap ?? $pendaftar->alamat ?? '') }}</textarea>
                                            @error('alamat_lengkap')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="rt" class="form-label fw-semibold" style="color: #36474f;">RT</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('rt') is-invalid @enderror"
                                                id="rt" name="rt" value="{{ old('rt', $studentDetail->rt ?? '') }}">
                                            @error('rt')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="rw" class="form-label fw-semibold" style="color: #36474f;">RW</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('rw') is-invalid @enderror"
                                                id="rw" name="rw" value="{{ old('rw', $studentDetail->rw ?? '') }}">
                                            @error('rw')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="kelurahan" class="form-label fw-semibold" style="color: #36474f;">Kelurahan/Desa <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('kelurahan') is-invalid @enderror"
                                                id="kelurahan" name="kelurahan" value="{{ old('kelurahan', $studentDetail->kelurahan ?? '') }}" required>
                                            @error('kelurahan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="kecamatan" class="form-label fw-semibold" style="color: #36474f;">Kecamatan <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('kecamatan') is-invalid @enderror"
                                                id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $studentDetail->kecamatan ?? '') }}" required>
                                            @error('kecamatan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="kota_kabupaten" class="form-label fw-semibold" style="color: #36474f;">Kota/Kabupaten <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('kota_kabupaten') is-invalid @enderror"
                                                id="kota_kabupaten" name="kota_kabupaten" value="{{ old('kota_kabupaten', $studentDetail->kota_kabupaten ?? '') }}" required>
                                            @error('kota_kabupaten')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="provinsi" class="form-label fw-semibold" style="color: #36474f;">Provinsi <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('provinsi') is-invalid @enderror"
                                                id="provinsi" name="provinsi" value="{{ old('provinsi', $studentDetail->provinsi ?? '') }}" required>
                                            @error('provinsi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="kode_pos" class="form-label fw-semibold" style="color: #36474f;">Kode Pos</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('kode_pos') is-invalid @enderror"
                                                id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $studentDetail->kode_pos ?? '') }}">
                                            @error('kode_pos')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="jarak_ke_sekolah" class="form-label fw-semibold" style="color: #36474f;">Jarak ke Sekolah (km)</label>
                                            <input type="number" step="0.1" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('jarak_ke_sekolah') is-invalid @enderror"
                                                id="jarak_ke_sekolah" name="jarak_ke_sekolah" value="{{ old('jarak_ke_sekolah', $studentDetail->jarak_ke_sekolah ?? '') }}">
                                            @error('jarak_ke_sekolah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="transportasi" class="form-label fw-semibold" style="color: #36474f;">Transportasi ke Sekolah</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('transportasi') is-invalid @enderror"
                                                id="transportasi" name="transportasi" value="{{ old('transportasi', $studentDetail->transportasi ?? '') }}" placeholder="Contoh: Jalan Kaki, Sepeda, Motor">
                                            @error('transportasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Family Information -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #e0f2f1, #b2dfdb); border-left: 5px solid #009688;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-people me-2"></i>Informasi Keluarga
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #f5fffd;">
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <label for="tinggal_dengan" class="form-label fw-semibold" style="color: #36474f;">Tinggal Dengan <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('tinggal_dengan') is-invalid @enderror"
                                                id="tinggal_dengan" name="tinggal_dengan" required>
                                                <option value="" selected disabled>Pilih...</option>
                                                <option value="Orang Tua" {{ old('tinggal_dengan', $studentDetail->tinggal_dengan ?? '') == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                                                <option value="Wali" {{ old('tinggal_dengan', $studentDetail->tinggal_dengan ?? '') == 'Wali' ? 'selected' : '' }}>Wali</option>
                                                <option value="Kos" {{ old('tinggal_dengan', $studentDetail->tinggal_dengan ?? '') == 'Kos' ? 'selected' : '' }}>Kos</option>
                                                <option value="Asrama" {{ old('tinggal_dengan', $studentDetail->tinggal_dengan ?? '') == 'Asrama' ? 'selected' : '' }}>Asrama</option>
                                                <option value="Panti Asuhan" {{ old('tinggal_dengan', $studentDetail->tinggal_dengan ?? '') == 'Panti Asuhan' ? 'selected' : '' }}>Panti Asuhan</option>
                                                <option value="Lainnya" {{ old('tinggal_dengan', $studentDetail->tinggal_dengan ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            @error('tinggal_dengan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="anak_ke" class="form-label fw-semibold" style="color: #36474f;">Anak ke <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('anak_ke') is-invalid @enderror"
                                                id="anak_ke" name="anak_ke" min="1" max="20" value="{{ old('anak_ke', $studentDetail->anak_ke ?? '1') }}" required>
                                            @error('anak_ke')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="jumlah_saudara_kandung" class="form-label fw-semibold" style="color: #36474f;">Jumlah Saudara Kandung</label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('jumlah_saudara_kandung') is-invalid @enderror"
                                                id="jumlah_saudara_kandung" name="jumlah_saudara_kandung" min="0" max="20" value="{{ old('jumlah_saudara_kandung', $studentDetail->jumlah_saudara_kandung ?? '0') }}">
                                            @error('jumlah_saudara_kandung')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #e8eaf6, #c5cae9); border-left: 5px solid #3f51b5;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-info-circle me-2"></i>Informasi Tambahan
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #f8f9ff;">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="hobi" class="form-label fw-semibold" style="color: #36474f;">Hobi</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('hobi') is-invalid @enderror"
                                                id="hobi" name="hobi" value="{{ old('hobi', $studentDetail->hobi ?? '') }}" placeholder="Contoh: Membaca, Menggambar, Olahraga">
                                            @error('hobi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="cita_cita" class="form-label fw-semibold" style="color: #36474f;">Cita-cita</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('cita_cita') is-invalid @enderror"
                                                id="cita_cita" name="cita_cita" value="{{ old('cita_cita', $studentDetail->cita_cita ?? '') }}">
                                            @error('cita_cita')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="kepribadian" class="form-label fw-semibold" style="color: #36474f;">Kepribadian</label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('kepribadian') is-invalid @enderror"
                                                id="kepribadian" name="kepribadian" rows="2" placeholder="Contoh: Aktif, Pendiam, Percaya diri, dll">{{ old('kepribadian', $studentDetail->kepribadian ?? '') }}</textarea>
                                            @error('kepribadian')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="kesulitan_belajar" class="form-label fw-semibold" style="color: #36474f;">Kesulitan Belajar (jika ada)</label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('kesulitan_belajar') is-invalid @enderror"
                                                id="kesulitan_belajar" name="kesulitan_belajar" rows="2" placeholder="Contoh: Kesulitan membaca, Kesulitan berhitung, dll">{{ old('kesulitan_belajar', $studentDetail->kesulitan_belajar ?? '') }}</textarea>
                                            @error('kesulitan_belajar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions - Enhanced buttons with hover effect -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('user.data.index') }}" class="btn btn-outline-secondary btn-lg px-4" style="transition: all 0.3s">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-5" style="transition: all 0.3s">
                                    <i class="bi bi-check-lg me-2"></i>Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form validation script -->
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })

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
        })()
    </script>
</x-app-layout>
