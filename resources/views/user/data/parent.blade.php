<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-people me-2 text-primary"></i>
                Data Orang Tua
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.data') }}">Kelengkapan Data</a></li>
                    <li class="breadcrumb-item active">Data Orang Tua</li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-people-fill me-2"></i>
                            Informasi Orang Tua / Wali
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('user.data.parent.store') }}" method="POST">
                            @csrf

                            <!-- Father Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-person-fill me-2"></i>Data Ayah
                                    </h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="nama_ayah" class="form-label fw-semibold">
                                        Nama Lengkap Ayah <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('nama_ayah') is-invalid @enderror"
                                           id="nama_ayah" name="nama_ayah"
                                           value="{{ old('nama_ayah', $parentDetail->nama_ayah ?? '') }}" required>
                                    @error('nama_ayah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="pekerjaan_ayah" class="form-label fw-semibold">
                                        Pekerjaan Ayah <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('pekerjaan_ayah') is-invalid @enderror"
                                           id="pekerjaan_ayah" name="pekerjaan_ayah"
                                           value="{{ old('pekerjaan_ayah', $parentDetail->pekerjaan_ayah ?? '') }}" required>
                                    @error('pekerjaan_ayah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="tempat_lahir_ayah" class="form-label fw-semibold">Tempat Lahir Ayah</label>
                                    <input type="text" class="form-control @error('tempat_lahir_ayah') is-invalid @enderror"
                                           id="tempat_lahir_ayah" name="tempat_lahir_ayah"
                                           value="{{ old('tempat_lahir_ayah', $parentDetail->tempat_lahir_ayah ?? '') }}">
                                    @error('tempat_lahir_ayah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="tanggal_lahir_ayah" class="form-label fw-semibold">Tanggal Lahir Ayah</label>
                                    <input type="date" class="form-control @error('tanggal_lahir_ayah') is-invalid @enderror"
                                           id="tanggal_lahir_ayah" name="tanggal_lahir_ayah"
                                           value="{{ old('tanggal_lahir_ayah', $parentDetail->tanggal_lahir_ayah ?? '') }}">
                                    @error('tanggal_lahir_ayah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="pendidikan_ayah" class="form-label fw-semibold">Pendidikan Terakhir Ayah</label>
                                    <select class="form-select @error('pendidikan_ayah') is-invalid @enderror"
                                            id="pendidikan_ayah" name="pendidikan_ayah">
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SD" {{ old('pendidikan_ayah', $parentDetail->pendidikan_ayah ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('pendidikan_ayah', $parentDetail->pendidikan_ayah ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA" {{ old('pendidikan_ayah', $parentDetail->pendidikan_ayah ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
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
                                    <label for="penghasilan_ayah" class="form-label fw-semibold">Penghasilan Ayah (Rp)</label>
                                    <input type="number" class="form-control @error('penghasilan_ayah') is-invalid @enderror"
                                           id="penghasilan_ayah" name="penghasilan_ayah" min="0"
                                           value="{{ old('penghasilan_ayah', $parentDetail->penghasilan_ayah ?? '') }}">
                                    @error('penghasilan_ayah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Mother Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-person-heart me-2"></i>Data Ibu
                                    </h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="nama_ibu" class="form-label fw-semibold">
                                        Nama Lengkap Ibu <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('nama_ibu') is-invalid @enderror"
                                           id="nama_ibu" name="nama_ibu"
                                           value="{{ old('nama_ibu', $parentDetail->nama_ibu ?? '') }}" required>
                                    @error('nama_ibu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="pekerjaan_ibu" class="form-label fw-semibold">
                                        Pekerjaan Ibu <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('pekerjaan_ibu') is-invalid @enderror"
                                           id="pekerjaan_ibu" name="pekerjaan_ibu"
                                           value="{{ old('pekerjaan_ibu', $parentDetail->pekerjaan_ibu ?? '') }}" required>
                                    @error('pekerjaan_ibu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="tempat_lahir_ibu" class="form-label fw-semibold">Tempat Lahir Ibu</label>
                                    <input type="text" class="form-control @error('tempat_lahir_ibu') is-invalid @enderror"
                                           id="tempat_lahir_ibu" name="tempat_lahir_ibu"
                                           value="{{ old('tempat_lahir_ibu', $parentDetail->tempat_lahir_ibu ?? '') }}">
                                    @error('tempat_lahir_ibu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="tanggal_lahir_ibu" class="form-label fw-semibold">Tanggal Lahir Ibu</label>
                                    <input type="date" class="form-control @error('tanggal_lahir_ibu') is-invalid @enderror"
                                           id="tanggal_lahir_ibu" name="tanggal_lahir_ibu"
                                           value="{{ old('tanggal_lahir_ibu', $parentDetail->tanggal_lahir_ibu ?? '') }}">
                                    @error('tanggal_lahir_ibu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="pendidikan_ibu" class="form-label fw-semibold">Pendidikan Terakhir Ibu</label>
                                    <select class="form-select @error('pendidikan_ibu') is-invalid @enderror"
                                            id="pendidikan_ibu" name="pendidikan_ibu">
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SD" {{ old('pendidikan_ibu', $parentDetail->pendidikan_ibu ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('pendidikan_ibu', $parentDetail->pendidikan_ibu ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA" {{ old('pendidikan_ibu', $parentDetail->pendidikan_ibu ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
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
                                    <label for="penghasilan_ibu" class="form-label fw-semibold">Penghasilan Ibu (Rp)</label>
                                    <input type="number" class="form-control @error('penghasilan_ibu') is-invalid @enderror"
                                           id="penghasilan_ibu" name="penghasilan_ibu" min="0"
                                           value="{{ old('penghasilan_ibu', $parentDetail->penghasilan_ibu ?? '') }}">
                                    @error('penghasilan_ibu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Guardian Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-person-plus me-2"></i>Data Wali (Jika Ada)
                                    </h6>
                                </div>

                                <div class="col-md-12">
                                    <label for="nama_wali" class="form-label fw-semibold">Nama Lengkap Wali</label>
                                    <input type="text" class="form-control @error('nama_wali') is-invalid @enderror"
                                           id="nama_wali" name="nama_wali"
                                           value="{{ old('nama_wali', $parentDetail->nama_wali ?? '') }}">
                                    @error('nama_wali')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Isi jika siswa memiliki wali selain ayah dan ibu</div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-geo-alt me-2"></i>Alamat & Kontak Orang Tua
                                    </h6>
                                </div>

                                <div class="col-12">
                                    <label for="alamat_orangtua" class="form-label fw-semibold">
                                        Alamat Lengkap Orang Tua <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('alamat_orangtua') is-invalid @enderror"
                                              id="alamat_orangtua" name="alamat_orangtua" rows="3" required>{{ old('alamat_orangtua', $parentDetail->alamat_orangtua ?? '') }}</textarea>
                                    @error('alamat_orangtua')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="no_telepon_orangtua" class="form-label fw-semibold">
                                        No. Telepon Orang Tua <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" class="form-control @error('no_telepon_orangtua') is-invalid @enderror"
                                           id="no_telepon_orangtua" name="no_telepon_orangtua"
                                           value="{{ old('no_telepon_orangtua', $parentDetail->no_telepon_orangtua ?? '') }}" required>
                                    @error('no_telepon_orangtua')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between pt-3 border-top">
                                <a href="{{ route('user.data') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
