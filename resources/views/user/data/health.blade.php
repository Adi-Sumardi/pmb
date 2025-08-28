<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-heart-pulse me-2 text-primary"></i>
                Data Kesehatan
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.data') }}">Kelengkapan Data</a></li>
                    <li class="breadcrumb-item active">Data Kesehatan</li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clipboard2-pulse me-2"></i>
                            Informasi Kesehatan Siswa
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info border-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Catatan:</strong> Data kesehatan ini bersifat opsional namun sangat membantu pihak sekolah
                            dalam memberikan perhatian khusus jika diperlukan.
                        </div>

                        <form action="{{ route('user.data.health.store') }}" method="POST">
                            @csrf

                            <!-- Basic Health Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-person-badge me-2"></i>Data Fisik Dasar
                                    </h6>
                                </div>

                                <div class="col-md-4">
                                    <label for="tinggi_badan" class="form-label fw-semibold">
                                        Tinggi Badan (cm) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control @error('tinggi_badan') is-invalid @enderror"
                                           id="tinggi_badan" name="tinggi_badan" min="50" max="250"
                                           value="{{ old('tinggi_badan', $healthRecord->tinggi_badan ?? '') }}" required>
                                    @error('tinggi_badan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="berat_badan" class="form-label fw-semibold">
                                        Berat Badan (kg) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control @error('berat_badan') is-invalid @enderror"
                                           id="berat_badan" name="berat_badan" min="10" max="200"
                                           value="{{ old('berat_badan', $healthRecord->berat_badan ?? '') }}" required>
                                    @error('berat_badan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="golongan_darah" class="form-label fw-semibold">
                                        Golongan Darah <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('golongan_darah') is-invalid @enderror"
                                            id="golongan_darah" name="golongan_darah" required>
                                        <option value="">Pilih Golongan Darah</option>
                                        <option value="A" {{ old('golongan_darah', $healthRecord->golongan_darah ?? '') == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ old('golongan_darah', $healthRecord->golongan_darah ?? '') == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="AB" {{ old('golongan_darah', $healthRecord->golongan_darah ?? '') == 'AB' ? 'selected' : '' }}>AB</option>
                                        <option value="O" {{ old('golongan_darah', $healthRecord->golongan_darah ?? '') == 'O' ? 'selected' : '' }}>O</option>
                                        <option value="Tidak Diketahui" {{ old('golongan_darah', $healthRecord->golongan_darah ?? '') == 'Tidak Diketahui' ? 'selected' : '' }}>Tidak Diketahui</option>
                                    </select>
                                    @error('golongan_darah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- BMI Display -->
                                <div class="col-12">
                                    <div class="card bg-light border-0">
                                        <div class="card-body p-3">
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <h6 class="mb-1">Indeks Massa Tubuh (BMI)</h6>
                                                    <div id="bmi-result" class="h4 text-primary mb-0">-</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div id="bmi-category" class="badge bg-secondary">Belum dihitung</div>
                                                    <div id="bmi-description" class="small text-muted mt-1"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Medical History -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-clipboard2-check me-2"></i>Riwayat Kesehatan
                                    </h6>
                                </div>

                                <div class="col-12">
                                    <label for="riwayat_penyakit" class="form-label fw-semibold">Riwayat Penyakit</label>
                                    <textarea class="form-control @error('riwayat_penyakit') is-invalid @enderror"
                                              id="riwayat_penyakit" name="riwayat_penyakit" rows="3"
                                              placeholder="Sebutkan riwayat penyakit yang pernah dialami (jika ada)">{{ old('riwayat_penyakit', $healthRecord->riwayat_penyakit ?? '') }}</textarea>
                                    @error('riwayat_penyakit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Contoh: Asma, Diabetes, Jantung, dll. Kosongkan jika tidak ada.</div>
                                </div>

                                <div class="col-12">
                                    <label for="alergi" class="form-label fw-semibold">Alergi</label>
                                    <textarea class="form-control @error('alergi') is-invalid @enderror"
                                              id="alergi" name="alergi" rows="3"
                                              placeholder="Sebutkan alergi yang dimiliki (jika ada)">{{ old('alergi', $healthRecord->alergi ?? '') }}</textarea>
                                    @error('alergi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Contoh: Alergi makanan, obat, debu, dll. Kosongkan jika tidak ada.</div>
                                </div>

                                <div class="col-12">
                                    <label for="obat_yang_dikonsumsi" class="form-label fw-semibold">Obat yang Dikonsumsi Rutin</label>
                                    <textarea class="form-control @error('obat_yang_dikonsumsi') is-invalid @enderror"
                                              id="obat_yang_dikonsumsi" name="obat_yang_dikonsumsi" rows="3"
                                              placeholder="Sebutkan obat yang dikonsumsi secara rutin (jika ada)">{{ old('obat_yang_dikonsumsi', $healthRecord->obat_yang_dikonsumsi ?? '') }}</textarea>
                                    @error('obat_yang_dikonsumsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Sebutkan nama obat dan dosis jika ada. Kosongkan jika tidak ada.</div>
                                </div>
                            </div>

                            <!-- Health Guidelines -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">
                                        <i class="bi bi-shield-check me-2"></i>Panduan Kesehatan
                                    </h6>
                                </div>

                                <div class="col-12">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="card border-success">
                                                <div class="card-body p-3">
                                                    <h6 class="text-success mb-2">
                                                        <i class="bi bi-check-circle me-2"></i>Tips Kesehatan
                                                    </h6>
                                                    <ul class="small mb-0 ps-3">
                                                        <li>Jaga pola makan sehat dan seimbang</li>
                                                        <li>Olahraga teratur minimal 30 menit/hari</li>
                                                        <li>Istirahat cukup 7-8 jam per hari</li>
                                                        <li>Minum air putih minimal 8 gelas/hari</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-info">
                                                <div class="card-body p-3">
                                                    <h6 class="text-info mb-2">
                                                        <i class="bi bi-info-circle me-2"></i>Informasi Penting
                                                    </h6>
                                                    <ul class="small mb-0 ps-3">
                                                        <li>Data ini akan dijaga kerahasiaannya</li>
                                                        <li>Hanya digunakan untuk keperluan medis darurat</li>
                                                        <li>Dapat diupdate sewaktu-waktu jika ada perubahan</li>
                                                        <li>Hubungi UKS jika ada keluhan kesehatan</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between pt-3 border-top">
                                <a href="{{ route('user.data') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-info">
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
        // BMI Calculator
        function calculateBMI() {
            const height = parseFloat(document.getElementById('tinggi_badan').value);
            const weight = parseFloat(document.getElementById('berat_badan').value);

            if (height && weight) {
                const heightInMeters = height / 100;
                const bmi = weight / (heightInMeters * heightInMeters);
                const bmiRounded = Math.round(bmi * 10) / 10;

                document.getElementById('bmi-result').textContent = bmiRounded;

                let category, description, badgeClass;

                if (bmi < 18.5) {
                    category = 'Kurus';
                    description = 'Berat badan kurang';
                    badgeClass = 'bg-warning';
                } else if (bmi < 25) {
                    category = 'Normal';
                    description = 'Berat badan ideal';
                    badgeClass = 'bg-success';
                } else if (bmi < 30) {
                    category = 'Gemuk';
                    description = 'Berat badan berlebih';
                    badgeClass = 'bg-warning';
                } else {
                    category = 'Obesitas';
                    description = 'Berat badan sangat berlebih';
                    badgeClass = 'bg-danger';
                }

                const categoryElement = document.getElementById('bmi-category');
                categoryElement.textContent = category;
                categoryElement.className = `badge ${badgeClass}`;

                document.getElementById('bmi-description').textContent = description;
            } else {
                document.getElementById('bmi-result').textContent = '-';
                document.getElementById('bmi-category').textContent = 'Belum dihitung';
                document.getElementById('bmi-category').className = 'badge bg-secondary';
                document.getElementById('bmi-description').textContent = '';
            }
        }

        // Add event listeners
        document.getElementById('tinggi_badan').addEventListener('input', calculateBMI);
        document.getElementById('berat_badan').addEventListener('input', calculateBMI);

        // Calculate BMI on page load if values exist
        document.addEventListener('DOMContentLoaded', calculateBMI);
    </script>
</x-app-layout>
