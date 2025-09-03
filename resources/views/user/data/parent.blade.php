<x-app-layout>
    <!-- Add a subtle gradient background to the entire page -->
    <div class="container-fluid py-4" style="background: linear-gradient(to bottom right, #f8f9fa, #e9effd);">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Enhanced gradient header with vibrant colors -->
                    <div class="card-header border-0" style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                        <div class="row align-items-center py-4">
                            <div class="col">
                                <h3 class="text-white fw-bold mb-0">Data Orang Tua/Wali</h3>
                                <p class="text-white opacity-75 mb-0">Lengkapi informasi orang tua atau wali siswa</p>
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

                        <form action="{{ route('user.data.parent.store', $pendaftar->id) }}" method="POST" class="needs-validation">
                            @csrf
                            <input type="hidden" name="pendaftar_id" value="{{ $pendaftar->id }}">

                            <!-- Fathers Data Section -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #e3f2fd, #bbdefb); border-left: 5px solid #1976d2;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-person me-2"></i>Data Ayah
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #f5fbff;">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="nama_ayah" class="form-label fw-semibold" style="color: #36474f;">Nama Lengkap Ayah <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nama_ayah') is-invalid @enderror"
                                                id="nama_ayah" name="nama_ayah" value="{{ old('nama_ayah', $parentDetail->nama_ayah ?? '') }}" required>
                                            @error('nama_ayah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="nik_ayah" class="form-label fw-semibold" style="color: #36474f;">NIK Ayah</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nik_ayah') is-invalid @enderror"
                                                id="nik_ayah" name="nik_ayah" value="{{ old('nik_ayah', $parentDetail->nik_ayah ?? '') }}">
                                            @error('nik_ayah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Nomor Induk Kependudukan Ayah</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="tempat_lahir_ayah" class="form-label fw-semibold" style="color: #36474f;">Tempat Lahir Ayah</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('tempat_lahir_ayah') is-invalid @enderror"
                                                id="tempat_lahir_ayah" name="tempat_lahir_ayah" value="{{ old('tempat_lahir_ayah', $parentDetail->tempat_lahir_ayah ?? '') }}">
                                            @error('tempat_lahir_ayah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="tanggal_lahir_ayah" class="form-label fw-semibold" style="color: #36474f;">Tanggal Lahir Ayah</label>
                                            <input type="date" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('tanggal_lahir_ayah') is-invalid @enderror"
                                                id="tanggal_lahir_ayah" name="tanggal_lahir_ayah"
                                                value="{{ old('tanggal_lahir_ayah', ($parentDetail && $parentDetail->tanggal_lahir_ayah) ? $parentDetail->tanggal_lahir_ayah->format('Y-m-d') : '') }}">
                                            @error('tanggal_lahir_ayah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="agama_ayah" class="form-label fw-semibold" style="color: #36474f;">Agama Ayah</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('agama_ayah') is-invalid @enderror"
                                                id="agama_ayah" name="agama_ayah">
                                                <option value="" selected disabled>Pilih Agama</option>
                                                <option value="Islam" {{ old('agama_ayah', $parentDetail->agama_ayah ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ old('agama_ayah', $parentDetail->agama_ayah ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                                <option value="Katolik" {{ old('agama_ayah', $parentDetail->agama_ayah ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                                <option value="Hindu" {{ old('agama_ayah', $parentDetail->agama_ayah ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Buddha" {{ old('agama_ayah', $parentDetail->agama_ayah ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                                <option value="Khonghucu" {{ old('agama_ayah', $parentDetail->agama_ayah ?? '') == 'Khonghucu' ? 'selected' : '' }}>Khonghucu</option>
                                                <option value="Lainnya" {{ old('agama_ayah', $parentDetail->agama_ayah ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            @error('agama_ayah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="pendidikan_ayah" class="form-label fw-semibold" style="color: #36474f;">Pendidikan Terakhir Ayah</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('pendidikan_ayah') is-invalid @enderror"
                                                id="pendidikan_ayah" name="pendidikan_ayah">
                                                <option value="" selected disabled>Pilih Pendidikan</option>
                                                <option value="Tidak Sekolah" {{ old('pendidikan_ayah', $parentDetail->pendidikan_ayah ?? '') == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                                <option value="SD" {{ old('pendidikan_ayah', $parentDetail->pendidikan_ayah ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                                <option value="SMP" {{ old('pendidikan_ayah', $parentDetail->pendidikan_ayah ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                                <option value="SMA" {{ old('pendidikan_ayah', $parentDetail->pendidikan_ayah ?? '') == 'SMA' ? 'selected' : '' }}>SMA/SMK</option>
                                                <option value="D1" {{ old('pendidikan_ayah', $parentDetail->pendidikan_ayah ?? '') == 'D1' ? 'selected' : '' }}>D1</option>
                                                <option value="D2" {{ old('pendidikan_ayah', $parentDetail->pendidikan_ayah ?? '') == 'D2' ? 'selected' : '' }}>D2</option>
                                                <option value="D3" {{ old('pendidikan_ayah', $parentDetail->pendidikan_ayah ?? '') == 'D3' ? 'selected' : '' }}>D3</option>
                                                <option value="S1" {{ old('pendidikan_ayah', $parentDetail->pendidikan_ayah ?? '') == 'S1' ? 'selected' : '' }}>S1</option>
                                                <option value="S2" {{ old('pendidikan_ayah', $parentDetail->pendidikan_ayah ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                                                <option value="S3" {{ old('pendidikan_ayah', $parentDetail->pendidikan_ayah ?? '') == 'S3' ? 'selected' : '' }}>S3</option>
                                            </select>
                                            @error('pendidikan_ayah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="pekerjaan_ayah" class="form-label fw-semibold" style="color: #36474f;">Pekerjaan Ayah <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('pekerjaan_ayah') is-invalid @enderror"
                                                id="pekerjaan_ayah" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $parentDetail->pekerjaan_ayah ?? '') }}" required>
                                            @error('pekerjaan_ayah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="jabatan_ayah" class="form-label fw-semibold" style="color: #36474f;">Jabatan</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('jabatan_ayah') is-invalid @enderror"
                                                id="jabatan_ayah" name="jabatan_ayah" value="{{ old('jabatan_ayah', $parentDetail->jabatan_ayah ?? '') }}">
                                            @error('jabatan_ayah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <label for="instansi_ayah" class="form-label fw-semibold" style="color: #36474f;">Instansi/Perusahaan</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('instansi_ayah') is-invalid @enderror"
                                                id="instansi_ayah" name="instansi_ayah" value="{{ old('instansi_ayah', $parentDetail->instansi_ayah ?? '') }}">
                                            @error('instansi_ayah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <label for="alamat_kantor_ayah" class="form-label fw-semibold" style="color: #36474f;">Alamat Kantor</label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('alamat_kantor_ayah') is-invalid @enderror"
                                                id="alamat_kantor_ayah" name="alamat_kantor_ayah" rows="2">{{ old('alamat_kantor_ayah', $parentDetail->alamat_kantor_ayah ?? '') }}</textarea>
                                            @error('alamat_kantor_ayah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="penghasilan_ayah" class="form-label fw-semibold" style="color: #36474f;">Penghasilan Bulanan</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('penghasilan_ayah') is-invalid @enderror"
                                                id="penghasilan_ayah" name="penghasilan_ayah" type="text">
                                                <option value="" selected disabled>Pilih Rentang</option>
                                                <option value="Kurang dari 5 Juta" {{ old('penghasilan_ayah', $parentDetail->penghasilan_ayah ?? '') == 'Kurang dari 5 Juta' ? 'selected' : '' }}>Kurang dari 5 Juta</option>
                                                <option value="5-10 Juta" {{ old('penghasilan_ayah', $parentDetail->penghasilan_ayah ?? '') == '5-10 Juta' ? 'selected' : '' }}>5-10 Juta</option>
                                                <option value="10-30 Juta" {{ old('penghasilan_ayah', $parentDetail->penghasilan_ayah ?? '') == '10-30 Juta' ? 'selected' : '' }}>10-30 Juta</option>
                                                <option value="30-50 Juta" {{ old('penghasilan_ayah', $parentDetail->penghasilan_ayah ?? '') == '30-50 Juta' ? 'selected' : '' }}>30-50 Juta</option>
                                                <option value="50-100 Juta" {{ old('penghasilan_ayah', $parentDetail->penghasilan_ayah ?? '') == '50-100 Juta' ? 'selected' : '' }}>50-100 Juta</option>
                                                <option value="Lebih dari 100 Juta" {{ old('penghasilan_ayah', $parentDetail->penghasilan_ayah ?? '') == 'Lebih dari 100 Juta' ? 'selected' : '' }}>Lebih dari 100 Juta</option>
                                            </select>
                                            @error('penghasilan_ayah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="no_hp_ayah" class="form-label fw-semibold" style="color: #36474f;">No. HP Ayah <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('no_hp_ayah') is-invalid @enderror"
                                                id="no_hp_ayah" name="no_hp_ayah" value="{{ old('no_hp_ayah', $parentDetail->no_hp_ayah ?? '') }}" required>
                                            @error('no_hp_ayah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="email_ayah" class="form-label fw-semibold" style="color: #36474f;">Email Ayah</label>
                                            <input type="email" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('email_ayah') is-invalid @enderror"
                                                id="email_ayah" name="email_ayah" value="{{ old('email_ayah', $parentDetail->email_ayah ?? '') }}">
                                            @error('email_ayah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mothers Data Section -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #fff8e1, #ffecb3); border-left: 5px solid #ffc107;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-person me-2"></i>Data Ibu
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #fffbf2;">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="nama_ibu" class="form-label fw-semibold" style="color: #36474f;">Nama Lengkap Ibu <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nama_ibu') is-invalid @enderror"
                                                id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu', $parentDetail->nama_ibu ?? '') }}" required>
                                            @error('nama_ibu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="nik_ibu" class="form-label fw-semibold" style="color: #36474f;">NIK Ibu</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nik_ibu') is-invalid @enderror"
                                                id="nik_ibu" name="nik_ibu" value="{{ old('nik_ibu', $parentDetail->nik_ibu ?? '') }}">
                                            @error('nik_ibu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Nomor Induk Kependudukan Ibu</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="tempat_lahir_ibu" class="form-label fw-semibold" style="color: #36474f;">Tempat Lahir Ibu</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('tempat_lahir_ibu') is-invalid @enderror"
                                                id="tempat_lahir_ibu" name="tempat_lahir_ibu" value="{{ old('tempat_lahir_ibu', $parentDetail->tempat_lahir_ibu ?? '') }}">
                                            @error('tempat_lahir_ibu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="tanggal_lahir_ibu" class="form-label fw-semibold" style="color: #36474f;">Tanggal Lahir Ibu</label>
                                            <input type="date" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('tanggal_lahir_ibu') is-invalid @enderror"
                                                id="tanggal_lahir_ibu" name="tanggal_lahir_ibu"
                                                value="{{ old('tanggal_lahir_ibu', ($parentDetail && $parentDetail->tanggal_lahir_ibu) ? $parentDetail->tanggal_lahir_ibu->format('Y-m-d') : '') }}">
                                            @error('tanggal_lahir_ibu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="agama_ibu" class="form-label fw-semibold" style="color: #36474f;">Agama Ibu</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('agama_ibu') is-invalid @enderror"
                                                id="agama_ibu" name="agama_ibu">
                                                <option value="" selected disabled>Pilih Agama</option>
                                                <option value="Islam" {{ old('agama_ibu', $parentDetail->agama_ibu ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                                <option value="Kristen" {{ old('agama_ibu', $parentDetail->agama_ibu ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                                <option value="Katolik" {{ old('agama_ibu', $parentDetail->agama_ibu ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                                <option value="Hindu" {{ old('agama_ibu', $parentDetail->agama_ibu ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                                <option value="Buddha" {{ old('agama_ibu', $parentDetail->agama_ibu ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                                <option value="Khonghucu" {{ old('agama_ibu', $parentDetail->agama_ibu ?? '') == 'Khonghucu' ? 'selected' : '' }}>Khonghucu</option>
                                                <option value="Lainnya" {{ old('agama_ibu', $parentDetail->agama_ibu ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            @error('agama_ibu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="pendidikan_ibu" class="form-label fw-semibold" style="color: #36474f;">Pendidikan Terakhir Ibu</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('pendidikan_ibu') is-invalid @enderror"
                                                id="pendidikan_ibu" name="pendidikan_ibu">
                                                <option value="" selected disabled>Pilih Pendidikan</option>
                                                <option value="Tidak Sekolah" {{ old('pendidikan_ibu', $parentDetail->pendidikan_ibu ?? '') == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                                <option value="SD" {{ old('pendidikan_ibu', $parentDetail->pendidikan_ibu ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                                <option value="SMP" {{ old('pendidikan_ibu', $parentDetail->pendidikan_ibu ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                                <option value="SMA" {{ old('pendidikan_ibu', $parentDetail->pendidikan_ibu ?? '') == 'SMA' ? 'selected' : '' }}>SMA/SMK</option>
                                                <option value="D1" {{ old('pendidikan_ibu', $parentDetail->pendidikan_ibu ?? '') == 'D1' ? 'selected' : '' }}>D1</option>
                                                <option value="D2" {{ old('pendidikan_ibu', $parentDetail->pendidikan_ibu ?? '') == 'D2' ? 'selected' : '' }}>D2</option>
                                                <option value="D3" {{ old('pendidikan_ibu', $parentDetail->pendidikan_ibu ?? '') == 'D3' ? 'selected' : '' }}>D3</option>
                                                <option value="S1" {{ old('pendidikan_ibu', $parentDetail->pendidikan_ibu ?? '') == 'S1' ? 'selected' : '' }}>S1</option>
                                                <option value="S2" {{ old('pendidikan_ibu', $parentDetail->pendidikan_ibu ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                                                <option value="S3" {{ old('pendidikan_ibu', $parentDetail->pendidikan_ibu ?? '') == 'S3' ? 'selected' : '' }}>S3</option>
                                            </select>
                                            @error('pendidikan_ibu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="pekerjaan_ibu" class="form-label fw-semibold" style="color: #36474f;">Pekerjaan Ibu <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('pekerjaan_ibu') is-invalid @enderror"
                                                id="pekerjaan_ibu" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $parentDetail->pekerjaan_ibu ?? '') }}" required>
                                            @error('pekerjaan_ibu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="jabatan_ibu" class="form-label fw-semibold" style="color: #36474f;">Jabatan</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('jabatan_ibu') is-invalid @enderror"
                                                id="jabatan_ibu" name="jabatan_ibu" value="{{ old('jabatan_ibu', $parentDetail->jabatan_ibu ?? '') }}">
                                            @error('jabatan_ibu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <label for="instansi_ibu" class="form-label fw-semibold" style="color: #36474f;">Instansi/Perusahaan</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('instansi_ibu') is-invalid @enderror"
                                                id="instansi_ibu" name="instansi_ibu" value="{{ old('instansi_ibu', $parentDetail->instansi_ibu ?? '') }}">
                                            @error('instansi_ibu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <label for="alamat_kantor_ibu" class="form-label fw-semibold" style="color: #36474f;">Alamat Kantor</label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('alamat_kantor_ibu') is-invalid @enderror"
                                                id="alamat_kantor_ibu" name="alamat_kantor_ibu" rows="2">{{ old('alamat_kantor_ibu', $parentDetail->alamat_kantor_ibu ?? '') }}</textarea>
                                            @error('alamat_kantor_ibu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="penghasilan_ibu" class="form-label fw-semibold" style="color: #36474f;">Penghasilan Bulanan</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('penghasilan_ibu') is-invalid @enderror"
                                                id="penghasilan_ibu" name="penghasilan_ibu" type="text">
                                                <option value="" selected disabled>Pilih Rentang</option>
                                                <option value="Kurang dari 5 Juta" {{ old('penghasilan_ibu', $parentDetail->penghasilan_ibu ?? '') == 'Kurang dari 5 Juta' ? 'selected' : '' }}>Kurang dari 5 Juta</option>
                                                <option value="5-10 Juta" {{ old('penghasilan_ibu', $parentDetail->penghasilan_ibu ?? '') == '5-10 Juta' ? 'selected' : '' }}>5-10 Juta</option>
                                                <option value="10-30 Juta" {{ old('penghasilan_ibu', $parentDetail->penghasilan_ibu ?? '') == '10-30 Juta' ? 'selected' : '' }}>10-30 Juta</option>
                                                <option value="30-50 Juta" {{ old('penghasilan_ibu', $parentDetail->penghasilan_ibu ?? '') == '30-50 Juta' ? 'selected' : '' }}>30-50 Juta</option>
                                                <option value="50-100 Juta" {{ old('penghasilan_ibu', $parentDetail->penghasilan_ibu ?? '') == '50-100 Juta' ? 'selected' : '' }}>50-100 Juta</option>
                                                <option value="Lebih dari 100 Juta" {{ old('penghasilan_ibu', $parentDetail->penghasilan_ibu ?? '') == 'Lebih dari 100 Juta' ? 'selected' : '' }}>Lebih dari 100 Juta</option>
                                            </select>
                                            @error('penghasilan_ibu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="no_hp_ibu" class="form-label fw-semibold" style="color: #36474f;">No. HP Ibu <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('no_hp_ibu') is-invalid @enderror"
                                                id="no_hp_ibu" name="no_hp_ibu" value="{{ old('no_hp_ibu', $parentDetail->no_hp_ibu ?? '') }}" required>
                                            @error('no_hp_ibu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="email_ibu" class="form-label fw-semibold" style="color: #36474f;">Email Ibu</label>
                                            <input type="email" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('email_ibu') is-invalid @enderror"
                                                id="email_ibu" name="email_ibu" value="{{ old('email_ibu', $parentDetail->email_ibu ?? '') }}">
                                            @error('email_ibu')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Guardians Data Section (Wali) -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #e8f5e9, #c8e6c9); border-left: 5px solid #4caf50;">
                                    <h5 class="card-title mb-0 fw-bold d-flex align-items-center">
                                        <i class="bi bi-person me-2"></i>Data Wali (Opsional)
                                        <span class="badge bg-light text-dark ms-2" style="font-size: 0.7rem;">Diisi jika siswa diasuh selain orangtua kandung</span>
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #f5fff8;">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="nama_wali" class="form-label fw-semibold" style="color: #36474f;">Nama Lengkap Wali</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nama_wali') is-invalid @enderror"
                                                id="nama_wali" name="nama_wali" value="{{ old('nama_wali', $parentDetail->nama_wali ?? '') }}">
                                            @error('nama_wali')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="hubungan_dengan_siswa" class="form-label fw-semibold" style="color: #36474f;">Hubungan dengan Siswa</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('hubungan_dengan_siswa') is-invalid @enderror"
                                                id="hubungan_dengan_siswa" name="hubungan_dengan_siswa" value="{{ old('hubungan_dengan_siswa', $parentDetail->hubungan_dengan_siswa ?? '') }}"
                                                placeholder="Contoh: Kakek, Nenek, Paman, Bibi">
                                            @error('hubungan_dengan_siswa')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="nik_wali" class="form-label fw-semibold" style="color: #36474f;">NIK Wali</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nik_wali') is-invalid @enderror"
                                                id="nik_wali" name="nik_wali" value="{{ old('nik_wali', $parentDetail->nik_wali ?? '') }}">
                                            @error('nik_wali')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="pendidikan_wali" class="form-label fw-semibold" style="color: #36474f;">Pendidikan Terakhir Wali</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('pendidikan_wali') is-invalid @enderror"
                                                id="pendidikan_wali" name="pendidikan_wali">
                                                <option value="" selected disabled>Pilih Pendidikan</option>
                                                <option value="Tidak Sekolah" {{ old('pendidikan_wali', $parentDetail->pendidikan_wali ?? '') == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                                <option value="SD" {{ old('pendidikan_wali', $parentDetail->pendidikan_wali ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                                <option value="SMP" {{ old('pendidikan_wali', $parentDetail->pendidikan_wali ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                                <option value="SMA" {{ old('pendidikan_wali', $parentDetail->pendidikan_wali ?? '') == 'SMA' ? 'selected' : '' }}>SMA/SMK</option>
                                                <option value="D1" {{ old('pendidikan_wali', $parentDetail->pendidikan_wali ?? '') == 'D1' ? 'selected' : '' }}>D1</option>
                                                <option value="D2" {{ old('pendidikan_wali', $parentDetail->pendidikan_wali ?? '') == 'D2' ? 'selected' : '' }}>D2</option>
                                                <option value="D3" {{ old('pendidikan_wali', $parentDetail->pendidikan_wali ?? '') == 'D3' ? 'selected' : '' }}>D3</option>
                                                <option value="S1" {{ old('pendidikan_wali', $parentDetail->pendidikan_wali ?? '') == 'S1' ? 'selected' : '' }}>S1</option>
                                                <option value="S2" {{ old('pendidikan_wali', $parentDetail->pendidikan_wali ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                                                <option value="S3" {{ old('pendidikan_wali', $parentDetail->pendidikan_wali ?? '') == 'S3' ? 'selected' : '' }}>S3</option>
                                            </select>
                                            @error('pendidikan_wali')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="pekerjaan_wali" class="form-label fw-semibold" style="color: #36474f;">Pekerjaan Wali</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('pekerjaan_wali') is-invalid @enderror"
                                                id="pekerjaan_wali" name="pekerjaan_wali" value="{{ old('pekerjaan_wali', $parentDetail->pekerjaan_wali ?? '') }}">
                                            @error('pekerjaan_wali')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="penghasilan_wali" class="form-label fw-semibold" style="color: #36474f;">Penghasilan Bulanan</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('penghasilan_wali') is-invalid @enderror"
                                                id="penghasilan_wali" name="penghasilan_wali" type="text">
                                                <option value="" selected disabled>Pilih Rentang</option>
                                                <option value="Kurang dari 5 Juta" {{ old('penghasilan_wali', $parentDetail->penghasilan_wali ?? '') == 'Kurang dari 5 Juta' ? 'selected' : '' }}>Kurang dari 5 Juta</option>
                                                <option value="5-10 Juta" {{ old('penghasilan_wali', $parentDetail->penghasilan_wali ?? '') == '5-10 Juta' ? 'selected' : '' }}>5-10 Juta</option>
                                                <option value="10-30 Juta" {{ old('penghasilan_wali', $parentDetail->penghasilan_wali ?? '') == '10-30 Juta' ? 'selected' : '' }}>10-30 Juta</option>
                                                <option value="30-50 Juta" {{ old('penghasilan_wali', $parentDetail->penghasilan_wali ?? '') == '30-50 Juta' ? 'selected' : '' }}>30-50 Juta</option>
                                                <option value="50-100 Juta" {{ old('penghasilan_wali', $parentDetail->penghasilan_wali ?? '') == '50-100 Juta' ? 'selected' : '' }}>50-100 Juta</option>
                                                <option value="Lebih dari 100 Juta" {{ old('penghasilan_wali', $parentDetail->penghasilan_wali ?? '') == 'Lebih dari 100 Juta' ? 'selected' : '' }}>Lebih dari 100 Juta</option>
                                            </select>
                                            @error('penghasilan_wali')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="no_hp_wali" class="form-label fw-semibold" style="color: #36474f;">No. HP Wali</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('no_hp_wali') is-invalid @enderror"
                                                id="no_hp_wali" name="no_hp_wali" value="{{ old('no_hp_wali', $parentDetail->no_hp_wali ?? '') }}">
                                            @error('no_hp_wali')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="email_wali" class="form-label fw-semibold" style="color: #36474f;">Email Wali</label>
                                            <input type="email" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('email_wali') is-invalid @enderror"
                                                id="email_wali" name="email_wali" value="{{ old('email_wali', $parentDetail->email_wali ?? '') }}">
                                            @error('email_wali')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <label for="alamat_wali" class="form-label fw-semibold" style="color: #36474f;">Alamat Wali</label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('alamat_wali') is-invalid @enderror"
                                                id="alamat_wali" name="alamat_wali" rows="3">{{ old('alamat_wali', $parentDetail->alamat_wali ?? '') }}</textarea>
                                            @error('alamat_wali')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('user.data.student', $pendaftar->id) }}" class="btn btn-outline-secondary btn-lg px-4" style="transition: all 0.3s">
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
