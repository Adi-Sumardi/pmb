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
                                <h3 class="text-white fw-bold mb-0">Riwayat Akademik</h3>
                                <p class="text-white opacity-75 mb-0">Informasi sekolah sebelumnya dan riwayat pendidikan</p>
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

                        <form action="{{ route('user.data.academic.store') }}" method="POST" class="needs-validation">
                            @csrf

                            <!-- Informasi Sekolah -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #e3f2fd, #bbdefb); border-left: 5px solid #1976d2;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-building me-2"></i>Informasi Sekolah
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #f5fbff;">
                                    <div class="row g-4">
                                        <div class="col-md-8">
                                            <label for="nama_sekolah_sebelumnya" class="form-label fw-semibold" style="color: #36474f;">Nama Sekolah <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nama_sekolah_sebelumnya') is-invalid @enderror"
                                                id="nama_sekolah_sebelumnya" name="nama_sekolah_sebelumnya" value="{{ old('nama_sekolah_sebelumnya', $academicHistory->nama_sekolah_sebelumnya ?? '') }}" required>
                                            @error('nama_sekolah_sebelumnya')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="npsn_sekolah_sebelumnya" class="form-label fw-semibold" style="color: #36474f;">NPSN Sekolah</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('npsn_sekolah_sebelumnya') is-invalid @enderror"
                                                id="npsn_sekolah_sebelumnya" name="npsn_sekolah_sebelumnya" value="{{ old('npsn_sekolah_sebelumnya', $academicHistory->npsn_sekolah_sebelumnya ?? '') }}">
                                            @error('npsn_sekolah_sebelumnya')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Nomor Pokok Sekolah Nasional</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="jenjang_sebelumnya" class="form-label fw-semibold" style="color: #36474f;">Jenjang Sekolah <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('jenjang_sebelumnya') is-invalid @enderror"
                                                id="jenjang_sebelumnya" name="jenjang_sebelumnya" required>
                                                <option value="" selected disabled>Pilih Jenjang</option>
                                                <option value="Sanggar Bermain" {{ old('jenjang_sebelumnya', $academicHistory->jenjang_sebelumnya ?? '') == 'Sanggar Bermain' ? 'selected' : '' }}>Sanggar Bermain</option>
                                                <option value="Kelompok Bermain" {{ old('jenjang_sebelumnya', $academicHistory->jenjang_sebelumnya ?? '') == 'Kelompok Bermain' ? 'selected' : '' }}>Kelompok Bermain</option>
                                                <option value="TKA" {{ old('jenjang_sebelumnya', $academicHistory->jenjang_sebelumnya ?? '') == 'TKA' ? 'selected' : '' }}>TKA</option>
                                                <option value="TKB" {{ old('jenjang_sebelumnya', $academicHistory->jenjang_sebelumnya ?? '') == 'TKB' ? 'selected' : '' }}>TKB</option>
                                                <option value="SD" {{ old('jenjang_sebelumnya', $academicHistory->jenjang_sebelumnya ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                                <option value="SMP" {{ old('jenjang_sebelumnya', $academicHistory->jenjang_sebelumnya ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                                <option value="SMA" {{ old('jenjang_sebelumnya', $academicHistory->jenjang_sebelumnya ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                                <option value="SMK" {{ old('jenjang_sebelumnya', $academicHistory->jenjang_sebelumnya ?? '') == 'SMK' ? 'selected' : '' }}>SMK</option>
                                            </select>
                                            @error('jenjang_sebelumnya')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="kelas_terakhir" class="form-label fw-semibold" style="color: #36474f;">Kelas Terakhir</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('kelas_terakhir') is-invalid @enderror"
                                                id="kelas_terakhir" name="kelas_terakhir" value="{{ old('kelas_terakhir', $academicHistory->kelas_terakhir ?? '') }}">
                                            @error('kelas_terakhir')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="tahun_lulus" class="form-label fw-semibold" style="color: #36474f;">Tahun Lulus <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('tahun_lulus') is-invalid @enderror"
                                                id="tahun_lulus" name="tahun_lulus" min="2000" max="{{ date('Y') + 1 }}"
                                                value="{{ old('tahun_lulus', $academicHistory->tahun_lulus ?? '') }}" required>
                                            @error('tahun_lulus')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label for="alamat_sekolah_sebelumnya" class="form-label fw-semibold" style="color: #36474f;">Alamat Sekolah <span class="text-danger">*</span></label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('alamat_sekolah_sebelumnya') is-invalid @enderror"
                                                id="alamat_sekolah_sebelumnya" name="alamat_sekolah_sebelumnya" rows="3" required>{{ old('alamat_sekolah_sebelumnya', $academicHistory->alamat_sekolah_sebelumnya ?? '') }}</textarea>
                                            @error('alamat_sekolah_sebelumnya')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Ijazah -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #fff8e1, #ffecb3); border-left: 5px solid #ffc107;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-award me-2"></i>Informasi Ijazah
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #fffbf2;">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="no_ijazah" class="form-label fw-semibold" style="color: #36474f;">Nomor Ijazah</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('no_ijazah') is-invalid @enderror"
                                                id="no_ijazah" name="no_ijazah" value="{{ old('no_ijazah', $academicHistory->no_ijazah ?? '') }}">
                                            @error('no_ijazah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="no_skhun" class="form-label fw-semibold" style="color: #36474f;">Nomor SKHUN</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('no_skhun') is-invalid @enderror"
                                                id="no_skhun" name="no_skhun" value="{{ old('no_skhun', $academicHistory->no_skhun ?? '') }}">
                                            @error('no_skhun')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nilai Akademik -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #e8f5e9, #c8e6c9); border-left: 5px solid #4caf50;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-journal-text me-2"></i>Nilai Akademik
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #f5fff8;">
                                    <div class="row g-4">
                                        <div class="col-md-12">
                                            <label for="rata_rata_nilai" class="form-label fw-semibold" style="color: #36474f;">Rata-rata Nilai</label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('rata_rata_nilai') is-invalid @enderror"
                                                id="rata_rata_nilai" name="rata_rata_nilai" step="0.01" min="0" max="100"
                                                value="{{ old('rata_rata_nilai', $academicHistory->rata_rata_nilai ?? '') }}">
                                            @error('rata_rata_nilai')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Contoh: 85.50</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="nilai_bahasa_indonesia" class="form-label fw-semibold" style="color: #36474f;">Nilai Bahasa Indonesia</label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nilai_bahasa_indonesia') is-invalid @enderror"
                                                id="nilai_bahasa_indonesia" name="nilai_bahasa_indonesia" step="0.01" min="0" max="100"
                                                value="{{ old('nilai_bahasa_indonesia', $academicHistory->nilai_bahasa_indonesia ?? '') }}">
                                            @error('nilai_bahasa_indonesia')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="nilai_matematika" class="form-label fw-semibold" style="color: #36474f;">Nilai Matematika</label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nilai_matematika') is-invalid @enderror"
                                                id="nilai_matematika" name="nilai_matematika" step="0.01" min="0" max="100"
                                                value="{{ old('nilai_matematika', $academicHistory->nilai_matematika ?? '') }}">
                                            @error('nilai_matematika')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="nilai_ipa" class="form-label fw-semibold" style="color: #36474f;">Nilai IPA</label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nilai_ipa') is-invalid @enderror"
                                                id="nilai_ipa" name="nilai_ipa" step="0.01" min="0" max="100"
                                                value="{{ old('nilai_ipa', $academicHistory->nilai_ipa ?? '') }}">
                                            @error('nilai_ipa')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="nilai_ips" class="form-label fw-semibold" style="color: #36474f;">Nilai IPS</label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nilai_ips') is-invalid @enderror"
                                                id="nilai_ips" name="nilai_ips" step="0.01" min="0" max="100"
                                                value="{{ old('nilai_ips', $academicHistory->nilai_ips ?? '') }}">
                                            @error('nilai_ips')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="nilai_bahasa_inggris" class="form-label fw-semibold" style="color: #36474f;">Nilai Bahasa Inggris</label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nilai_bahasa_inggris') is-invalid @enderror"
                                                id="nilai_bahasa_inggris" name="nilai_bahasa_inggris" step="0.01" min="0" max="100"
                                                value="{{ old('nilai_bahasa_inggris', $academicHistory->nilai_bahasa_inggris ?? '') }}">
                                            @error('nilai_bahasa_inggris')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="ranking_kelas" class="form-label fw-semibold" style="color: #36474f;">Ranking di Kelas</label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('ranking_kelas') is-invalid @enderror"
                                                id="ranking_kelas" name="ranking_kelas" min="1"
                                                value="{{ old('ranking_kelas', $academicHistory->ranking_kelas ?? '') }}">
                                            @error('ranking_kelas')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="jumlah_siswa_sekelas" class="form-label fw-semibold" style="color: #36474f;">Jumlah Siswa di Kelas</label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('jumlah_siswa_sekelas') is-invalid @enderror"
                                                id="jumlah_siswa_sekelas" name="jumlah_siswa_sekelas" min="1"
                                                value="{{ old('jumlah_siswa_sekelas', $academicHistory->jumlah_siswa_sekelas ?? '') }}">
                                            @error('jumlah_siswa_sekelas')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Prestasi & Organisasi -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #f3e5f5, #e1bee7); border-left: 5px solid #9c27b0;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-trophy me-2"></i>Prestasi & Organisasi
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #fcf8ff;">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="prestasi_akademik" class="form-label fw-semibold" style="color: #36474f;">Prestasi Akademik</label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('prestasi_akademik') is-invalid @enderror"
                                                id="prestasi_akademik" name="prestasi_akademik" rows="3">{{ old('prestasi_akademik', $academicHistory->prestasi_akademik ?? '') }}</textarea>
                                            @error('prestasi_akademik')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Contoh: Juara 1 Olimpiade Matematika</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="prestasi_non_akademik" class="form-label fw-semibold" style="color: #36474f;">Prestasi Non-Akademik</label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('prestasi_non_akademik') is-invalid @enderror"
                                                id="prestasi_non_akademik" name="prestasi_non_akademik" rows="3">{{ old('prestasi_non_akademik', $academicHistory->prestasi_non_akademik ?? '') }}</textarea>
                                            @error('prestasi_non_akademik')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Contoh: Juara 2 Lomba Futsal Tingkat Kota</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="organisasi_yang_diikuti" class="form-label fw-semibold" style="color: #36474f;">Organisasi yang Diikuti</label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('organisasi_yang_diikuti') is-invalid @enderror"
                                                id="organisasi_yang_diikuti" name="organisasi_yang_diikuti" rows="3">{{ old('organisasi_yang_diikuti', $academicHistory->organisasi_yang_diikuti ?? '') }}</textarea>
                                            @error('organisasi_yang_diikuti')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Contoh: OSIS, Pramuka</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="jabatan_organisasi" class="form-label fw-semibold" style="color: #36474f;">Jabatan di Organisasi</label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('jabatan_organisasi') is-invalid @enderror"
                                                id="jabatan_organisasi" name="jabatan_organisasi" rows="3">{{ old('jabatan_organisasi', $academicHistory->jabatan_organisasi ?? '') }}</textarea>
                                            @error('jabatan_organisasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Contoh: Ketua OSIS, Sekretaris Pramuka</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('user.data') }}" class="btn btn-outline-secondary btn-lg px-4" style="transition: all 0.3s">
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

            // Validation for graduation year
            document.getElementById('tahun_lulus').addEventListener('change', function() {
                const tahunLulus = parseInt(this.value);
                const currentYear = new Date().getFullYear();

                if (tahunLulus > currentYear + 1) {
                    alert('Tahun lulus tidak boleh lebih dari tahun depan');
                    this.value = currentYear;
                }
            });
        })()
    </script>
</x-app-layout>
