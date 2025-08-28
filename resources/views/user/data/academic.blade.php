<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-mortarboard me-2 text-primary"></i>
                Riwayat Akademik
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.data') }}">Kelengkapan Data</a></li>
                    <li class="breadcrumb-item active">Riwayat Akademik</li>
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
                            <i class="bi bi-building me-2"></i>
                            Informasi Sekolah Sebelumnya
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('user.data.academic.store') }}" method="POST">
                            @csrf

                            <!-- School Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-bank me-2"></i>Informasi Sekolah
                                    </h6>
                                </div>

                                <div class="col-md-8">
                                    <label for="nama_sekolah" class="form-label fw-semibold">
                                        Nama Sekolah <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('nama_sekolah') is-invalid @enderror"
                                           id="nama_sekolah" name="nama_sekolah"
                                           value="{{ old('nama_sekolah', $academicHistory->nama_sekolah ?? '') }}" required>
                                    @error('nama_sekolah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="jenjang_sekolah" class="form-label fw-semibold">
                                        Jenjang <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('jenjang_sekolah') is-invalid @enderror"
                                            id="jenjang_sekolah" name="jenjang_sekolah" required>
                                        <option value="">Pilih Jenjang</option>
                                        <option value="SD" {{ old('jenjang_sekolah', $academicHistory->jenjang_sekolah ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="MI" {{ old('jenjang_sekolah', $academicHistory->jenjang_sekolah ?? '') == 'MI' ? 'selected' : '' }}>MI</option>
                                        <option value="SMP" {{ old('jenjang_sekolah', $academicHistory->jenjang_sekolah ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="MTs" {{ old('jenjang_sekolah', $academicHistory->jenjang_sekolah ?? '') == 'MTs' ? 'selected' : '' }}>MTs</option>
                                        <option value="SMA" {{ old('jenjang_sekolah', $academicHistory->jenjang_sekolah ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                        <option value="MA" {{ old('jenjang_sekolah', $academicHistory->jenjang_sekolah ?? '') == 'MA' ? 'selected' : '' }}>MA</option>
                                        <option value="SMK" {{ old('jenjang_sekolah', $academicHistory->jenjang_sekolah ?? '') == 'SMK' ? 'selected' : '' }}>SMK</option>
                                    </select>
                                    @error('jenjang_sekolah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="alamat_sekolah" class="form-label fw-semibold">
                                        Alamat Sekolah <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('alamat_sekolah') is-invalid @enderror"
                                              id="alamat_sekolah" name="alamat_sekolah" rows="3" required>{{ old('alamat_sekolah', $academicHistory->alamat_sekolah ?? '') }}</textarea>
                                    @error('alamat_sekolah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Academic Period -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-calendar-range me-2"></i>Periode Akademik
                                    </h6>
                                </div>

                                <div class="col-md-4">
                                    <label for="tahun_masuk" class="form-label fw-semibold">
                                        Tahun Masuk <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control @error('tahun_masuk') is-invalid @enderror"
                                           id="tahun_masuk" name="tahun_masuk" min="2000" max="{{ date('Y') }}"
                                           value="{{ old('tahun_masuk', $academicHistory->tahun_masuk ?? '') }}" required>
                                    @error('tahun_masuk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="tahun_lulus" class="form-label fw-semibold">
                                        Tahun Lulus <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control @error('tahun_lulus') is-invalid @enderror"
                                           id="tahun_lulus" name="tahun_lulus" min="2000" max="{{ date('Y') + 1 }}"
                                           value="{{ old('tahun_lulus', $academicHistory->tahun_lulus ?? '') }}" required>
                                    @error('tahun_lulus')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="rata_rata_nilai" class="form-label fw-semibold">Rata-rata Nilai</label>
                                    <input type="number" class="form-control @error('rata_rata_nilai') is-invalid @enderror"
                                           id="rata_rata_nilai" name="rata_rata_nilai" step="0.01" min="0" max="100"
                                           value="{{ old('rata_rata_nilai', $academicHistory->rata_rata_nilai ?? '') }}">
                                    @error('rata_rata_nilai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Contoh: 85.50</div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-info-circle me-2"></i>Informasi Tambahan
                                    </h6>
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="bi bi-lightbulb me-2"></i>
                                        <strong>Tips:</strong> Pastikan semua data yang diisi sesuai dengan dokumen resmi dari sekolah sebelumnya.
                                        Data ini akan diverifikasi oleh admin.
                                    </div>
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

    <script>
        // Validation for graduation year
        document.getElementById('tahun_masuk').addEventListener('change', function() {
            const tahunMasuk = parseInt(this.value);
            const tahunLulusInput = document.getElementById('tahun_lulus');

            if (tahunMasuk) {
                tahunLulusInput.min = tahunMasuk;
                if (parseInt(tahunLulusInput.value) < tahunMasuk) {
                    tahunLulusInput.value = tahunMasuk;
                }
            }
        });

        document.getElementById('tahun_lulus').addEventListener('change', function() {
            const tahunLulus = parseInt(this.value);
            const tahunMasukInput = document.getElementById('tahun_masuk');

            if (tahunLulus && parseInt(tahunMasukInput.value) > tahunLulus) {
                alert('Tahun lulus tidak boleh lebih kecil dari tahun masuk');
                this.value = '';
            }
        });
    </script>
</x-app-layout>
