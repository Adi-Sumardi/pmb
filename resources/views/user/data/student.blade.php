<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-person me-2 text-primary"></i>
                Data Siswa
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.data') }}">Kelengkapan Data</a></li>
                    <li class="breadcrumb-item active">Data Siswa</li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-circle me-2"></i>
                            Informasi Pribadi Siswa
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('user.data.student.store') }}" method="POST">
                            @csrf

                            <!-- Personal Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-info-circle me-2"></i>Informasi Dasar
                                    </h6>
                                </div>

                                <div class="col-md-8">
                                    <label for="nama_lengkap" class="form-label fw-semibold">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                           id="nama_lengkap" name="nama_lengkap"
                                           value="{{ old('nama_lengkap', $studentDetail->nama_lengkap ?? '') }}" required>
                                    @error('nama_lengkap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="jenis_kelamin" class="form-label fw-semibold">
                                        Jenis Kelamin <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                            id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('jenis_kelamin', $studentDetail->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin', $studentDetail->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="tempat_lahir" class="form-label fw-semibold">
                                        Tempat Lahir <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror"
                                           id="tempat_lahir" name="tempat_lahir"
                                           value="{{ old('tempat_lahir', $studentDetail->tempat_lahir ?? '') }}" required>
                                    @error('tempat_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="tanggal_lahir" class="form-label fw-semibold">
                                        Tanggal Lahir <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                           id="tanggal_lahir" name="tanggal_lahir"
                                           value="{{ old('tanggal_lahir', $studentDetail->tanggal_lahir ?? '') }}" required>
                                    @error('tanggal_lahir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="agama" class="form-label fw-semibold">
                                        Agama <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('agama') is-invalid @enderror"
                                            id="agama" name="agama" required>
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam" {{ old('agama', $studentDetail->agama ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Kristen" {{ old('agama', $studentDetail->agama ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                        <option value="Katolik" {{ old('agama', $studentDetail->agama ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                        <option value="Hindu" {{ old('agama', $studentDetail->agama ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Buddha" {{ old('agama', $studentDetail->agama ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                        <option value="Konghucu" {{ old('agama', $studentDetail->agama ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    </select>
                                    @error('agama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="kewarganegaraan" class="form-label fw-semibold">
                                        Kewarganegaraan <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('kewarganegaraan') is-invalid @enderror"
                                           id="kewarganegaraan" name="kewarganegaraan"
                                           value="{{ old('kewarganegaraan', $studentDetail->kewarganegaraan ?? 'Indonesia') }}" required>
                                    @error('kewarganegaraan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-geo-alt me-2"></i>Alamat & Kontak
                                    </h6>
                                </div>

                                <div class="col-12">
                                    <label for="alamat_lengkap" class="form-label fw-semibold">
                                        Alamat Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('alamat_lengkap') is-invalid @enderror"
                                              id="alamat_lengkap" name="alamat_lengkap" rows="3" required>{{ old('alamat_lengkap', $studentDetail->alamat_lengkap ?? '') }}</textarea>
                                    @error('alamat_lengkap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="kode_pos" class="form-label fw-semibold">
                                        Kode Pos <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('kode_pos') is-invalid @enderror"
                                           id="kode_pos" name="kode_pos"
                                           value="{{ old('kode_pos', $studentDetail->kode_pos ?? '') }}" required>
                                    @error('kode_pos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="no_telepon" class="form-label fw-semibold">
                                        No. Telepon <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" class="form-control @error('no_telepon') is-invalid @enderror"
                                           id="no_telepon" name="no_telepon"
                                           value="{{ old('no_telepon', $studentDetail->no_telepon ?? '') }}" required>
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="email" class="form-label fw-semibold">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email"
                                           value="{{ old('email', $studentDetail->email ?? '') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-star me-2"></i>Informasi Tambahan
                                    </h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="anak_ke" class="form-label fw-semibold">Anak ke-</label>
                                    <input type="number" class="form-control @error('anak_ke') is-invalid @enderror"
                                           id="anak_ke" name="anak_ke" min="1"
                                           value="{{ old('anak_ke', $studentDetail->anak_ke ?? '') }}">
                                    @error('anak_ke')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="jumlah_saudara" class="form-label fw-semibold">Jumlah Saudara</label>
                                    <input type="number" class="form-control @error('jumlah_saudara') is-invalid @enderror"
                                           id="jumlah_saudara" name="jumlah_saudara" min="0"
                                           value="{{ old('jumlah_saudara', $studentDetail->jumlah_saudara ?? '') }}">
                                    @error('jumlah_saudara')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="hobi" class="form-label fw-semibold">Hobi</label>
                                    <input type="text" class="form-control @error('hobi') is-invalid @enderror"
                                           id="hobi" name="hobi"
                                           value="{{ old('hobi', $studentDetail->hobi ?? '') }}">
                                    @error('hobi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="cita_cita" class="form-label fw-semibold">Cita-cita</label>
                                    <input type="text" class="form-control @error('cita_cita') is-invalid @enderror"
                                           id="cita_cita" name="cita_cita"
                                           value="{{ old('cita_cita', $studentDetail->cita_cita ?? '') }}">
                                    @error('cita_cita')
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
