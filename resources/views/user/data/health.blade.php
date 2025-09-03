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
                    <li class="breadcrumb-item"><a href="{{ route('user.data') }}">Data Pendaftaran</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Data Kesehatan</li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="container-fluid py-4" style="background: linear-gradient(to bottom right, #f8f9fa, #e9effd);">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Enhanced gradient header with more vibrant colors -->
                    <div class="card-header border-0" style="background: linear-gradient(135deg, #4cc9f0, #4361ee);">
                        <div class="row align-items-center py-4">
                            <div class="col">
                                <h3 class="text-white fw-bold mb-0">Data Kesehatan</h3>
                                <p class="text-white opacity-75 mb-0">Lengkapi informasi kesehatan siswa</p>
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

                        <form action="{{ route('user.data.health.store', $pendaftar->id) }}" method="POST" class="needs-validation">
                            @csrf
                            @method('POST')
                            <!-- Hidden input for pendaftar_id -->
                            <input type="hidden" name="pendaftar_id" value="{{ $pendaftar->id }}">

                            <!-- BMI Calculator Section -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #e3f2fd, #bbdefb); border-left: 5px solid #2196f3;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-calculator me-2"></i>Kalkulator BMI
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #f5fcff;">
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <label for="tinggi_badan" class="form-label fw-semibold" style="color: #36474f;">Tinggi Badan (cm) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm"
                                                id="tinggi_badan" value="{{ $studentDetail->tinggi_badan ?? '' }}" min="50" max="250" readonly>
                                            <div class="form-text text-muted">Data dari Data Siswa</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="berat_badan" class="form-label fw-semibold" style="color: #36474f;">Berat Badan (kg) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm"
                                                id="berat_badan" value="{{ $studentDetail->berat_badan ?? '' }}" min="1" max="200" readonly>
                                            <div class="form-text text-muted">Data dari Data Siswa</div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="bmi-result" class="form-label fw-semibold" style="color: #36474f;">BMI (Body Mass Index)</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg rounded-start border-0 shadow-sm bg-light" id="bmi-result" readonly>
                                                <span class="input-group-text rounded-end border-0 shadow-sm" style="background-color: #e3f2fd;">kg/m²</span>
                                            </div>
                                            <div id="bmi-description" class="form-text mt-2 fw-medium"></div>
                                        </div>

                                        <div class="col-12">
                                            <div class="progress mt-2" style="height: 10px;">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Kurus</div>
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Normal</div>
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Gemuk</div>
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 40%;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">Obesitas</div>
                                            </div>
                                            <div class="d-flex justify-content-between text-muted small mt-1">
                                                <span>&lt;18.5</span>
                                                <span>18.5-24.9</span>
                                                <span>25-29.9</span>
                                                <span>≥30</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Medical History Section -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #fff8e1, #ffecb3); border-left: 5px solid #ffc107;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-journal-medical me-2"></i>Riwayat Kesehatan
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #fffcf5;">
                                    <div class="row g-4">
                                        <div class="col-md-12">
                                            <label for="riwayat_penyakit" class="form-label fw-semibold" style="color: #36474f;">Riwayat Penyakit</label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('riwayat_penyakit') is-invalid @enderror"
                                                id="riwayat_penyakit" name="riwayat_penyakit" rows="2" placeholder="Contoh: Asma, Tifus, dll">{{ old('riwayat_penyakit', $healthRecord->riwayat_penyakit ?? '') }}</textarea>
                                            <div class="form-text">Tuliskan riwayat penyakit yang pernah diderita (jika ada)</div>
                                            @error('riwayat_penyakit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <label for="alergi" class="form-label fw-semibold" style="color: #36474f;">Alergi</label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('alergi') is-invalid @enderror"
                                                id="alergi" name="alergi" rows="2" placeholder="Contoh: Alergi makanan, Alergi obat, dll">{{ old('alergi', $healthRecord->alergi ?? '') }}</textarea>
                                            <div class="form-text">Tuliskan alergi yang dimiliki (jika ada)</div>
                                            @error('alergi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <label for="obat_yang_dikonsumsi" class="form-label fw-semibold" style="color: #36474f;">Obat yang Dikonsumsi</label>
                                            <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('obat_yang_dikonsumsi') is-invalid @enderror"
                                                id="obat_yang_dikonsumsi" name="obat_yang_dikonsumsi" rows="2" placeholder="Obat yang rutin diminum (jika ada)">{{ old('obat_yang_dikonsumsi', $healthRecord->obat_yang_dikonsumsi ?? '') }}</textarea>
                                            @error('obat_yang_dikonsumsi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="cacat_jasmani" class="form-label fw-semibold" style="color: #36474f;">Cacat Jasmani</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('cacat_jasmani') is-invalid @enderror"
                                                id="cacat_jasmani" name="cacat_jasmani">
                                                <option value="Tidak Ada" {{ old('cacat_jasmani', $healthRecord->cacat_jasmani ?? 'Tidak Ada') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                                <option value="Ringan" {{ old('cacat_jasmani', $healthRecord->cacat_jasmani ?? '') == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                                                <option value="Sedang" {{ old('cacat_jasmani', $healthRecord->cacat_jasmani ?? '') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                                <option value="Berat" {{ old('cacat_jasmani', $healthRecord->cacat_jasmani ?? '') == 'Berat' ? 'selected' : '' }}>Berat</option>
                                            </select>
                                            @error('cacat_jasmani')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="keterangan_cacat" class="form-label fw-semibold" style="color: #36474f;">Keterangan Cacat</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('keterangan_cacat') is-invalid @enderror"
                                                id="keterangan_cacat" name="keterangan_cacat" value="{{ old('keterangan_cacat', $healthRecord->keterangan_cacat ?? '') }}"
                                                {{ old('cacat_jasmani', $healthRecord->cacat_jasmani ?? 'Tidak Ada') == 'Tidak Ada' ? 'disabled' : '' }}>
                                            @error('keterangan_cacat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Immunization Section -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #e8f5e9, #c8e6c9); border-left: 5px solid #4caf50;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-shield-check me-2"></i>Riwayat Imunisasi
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #f8fff8;">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <p class="mb-3">Centang imunisasi yang telah diberikan:</p>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1" id="bcg" name="bcg"
                                                            {{ old('bcg', $healthRecord->bcg ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-medium" for="bcg">
                                                            BCG (Vaksin Tuberkulosis)
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1" id="polio" name="polio"
                                                            {{ old('polio', $healthRecord->polio ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-medium" for="polio">
                                                            Polio
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1" id="dpt" name="dpt"
                                                            {{ old('dpt', $healthRecord->dpt ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-medium" for="dpt">
                                                            DPT (Difteri, Pertusis, Tetanus)
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1" id="campak" name="campak"
                                                            {{ old('campak', $healthRecord->campak ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-medium" for="campak">
                                                            Campak
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1" id="hepatitis_b" name="hepatitis_b"
                                                            {{ old('hepatitis_b', $healthRecord->hepatitis_b ?? false) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-medium" for="hepatitis_b">
                                                            Hepatitis B
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Special Needs & Conditions Section -->
                            <div class="card shadow-sm rounded-4 mb-4 border-0">
                                <div class="card-header py-3" style="background: linear-gradient(to right, #f3e5f5, #e1bee7); border-left: 5px solid #9c27b0;">
                                    <h5 class="card-title mb-0 fw-bold">
                                        <i class="bi bi-eye me-2"></i>Kondisi Khusus & Indera
                                    </h5>
                                </div>
                                <div class="card-body p-4" style="background-color: #fcf8ff;">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="kebutuhan_khusus" class="form-label fw-semibold" style="color: #36474f;">Kebutuhan Khusus</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('kebutuhan_khusus') is-invalid @enderror"
                                                id="kebutuhan_khusus" name="kebutuhan_khusus">
                                                <option value="Tidak Ada" {{ old('kebutuhan_khusus', $healthRecord->kebutuhan_khusus ?? 'Tidak Ada') == 'Tidak Ada' ? 'selected' : '' }}>Tidak Ada</option>
                                                <option value="Tuna Netra" {{ old('kebutuhan_khusus', $healthRecord->kebutuhan_khusus ?? '') == 'Tuna Netra' ? 'selected' : '' }}>Tuna Netra</option>
                                                <option value="Tuna Rungu" {{ old('kebutuhan_khusus', $healthRecord->kebutuhan_khusus ?? '') == 'Tuna Rungu' ? 'selected' : '' }}>Tuna Rungu</option>
                                                <option value="Tuna Grahita" {{ old('kebutuhan_khusus', $healthRecord->kebutuhan_khusus ?? '') == 'Tuna Grahita' ? 'selected' : '' }}>Tuna Grahita</option>
                                                <option value="Tuna Daksa" {{ old('kebutuhan_khusus', $healthRecord->kebutuhan_khusus ?? '') == 'Tuna Daksa' ? 'selected' : '' }}>Tuna Daksa</option>
                                                <option value="Autis" {{ old('kebutuhan_khusus', $healthRecord->kebutuhan_khusus ?? '') == 'Autis' ? 'selected' : '' }}>Autis</option>
                                                <option value="ADHD" {{ old('kebutuhan_khusus', $healthRecord->kebutuhan_khusus ?? '') == 'ADHD' ? 'selected' : '' }}>ADHD</option>
                                                <option value="Disleksia" {{ old('kebutuhan_khusus', $healthRecord->kebutuhan_khusus ?? '') == 'Disleksia' ? 'selected' : '' }}>Disleksia</option>
                                                <option value="Lainnya" {{ old('kebutuhan_khusus', $healthRecord->kebutuhan_khusus ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            @error('kebutuhan_khusus')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="keterangan_kebutuhan_khusus" class="form-label fw-semibold" style="color: #36474f;">Keterangan Kebutuhan Khusus</label>
                                            <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('keterangan_kebutuhan_khusus') is-invalid @enderror"
                                                id="keterangan_kebutuhan_khusus" name="keterangan_kebutuhan_khusus" value="{{ old('keterangan_kebutuhan_khusus', $healthRecord->keterangan_kebutuhan_khusus ?? '') }}"
                                                {{ old('kebutuhan_khusus', $healthRecord->kebutuhan_khusus ?? 'Tidak Ada') == 'Tidak Ada' ? 'disabled' : '' }}>
                                            @error('keterangan_kebutuhan_khusus')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="kondisi_mata" class="form-label fw-semibold" style="color: #36474f;">Kondisi Mata</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('kondisi_mata') is-invalid @enderror"
                                                id="kondisi_mata" name="kondisi_mata">
                                                <option value="Normal" {{ old('kondisi_mata', $healthRecord->kondisi_mata ?? 'Normal') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                                <option value="Rabun Jauh" {{ old('kondisi_mata', $healthRecord->kondisi_mata ?? '') == 'Rabun Jauh' ? 'selected' : '' }}>Rabun Jauh (Miopia)</option>
                                                <option value="Rabun Dekat" {{ old('kondisi_mata', $healthRecord->kondisi_mata ?? '') == 'Rabun Dekat' ? 'selected' : '' }}>Rabun Dekat (Hipermetropia)</option>
                                                <option value="Buta Warna" {{ old('kondisi_mata', $healthRecord->kondisi_mata ?? '') == 'Buta Warna' ? 'selected' : '' }}>Buta Warna</option>
                                                <option value="Silinder" {{ old('kondisi_mata', $healthRecord->kondisi_mata ?? '') == 'Silinder' ? 'selected' : '' }}>Silinder (Astigmatisme)</option>
                                                <option value="Lainnya" {{ old('kondisi_mata', $healthRecord->kondisi_mata ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            @error('kondisi_mata')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="kondisi_telinga" class="form-label fw-semibold" style="color: #36474f;">Kondisi Telinga</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('kondisi_telinga') is-invalid @enderror"
                                                id="kondisi_telinga" name="kondisi_telinga">
                                                <option value="Normal" {{ old('kondisi_telinga', $healthRecord->kondisi_telinga ?? 'Normal') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                                <option value="Gangguan Pendengaran Ringan" {{ old('kondisi_telinga', $healthRecord->kondisi_telinga ?? '') == 'Gangguan Pendengaran Ringan' ? 'selected' : '' }}>Gangguan Pendengaran Ringan</option>
                                                <option value="Gangguan Pendengaran Sedang" {{ old('kondisi_telinga', $healthRecord->kondisi_telinga ?? '') == 'Gangguan Pendengaran Sedang' ? 'selected' : '' }}>Gangguan Pendengaran Sedang</option>
                                                <option value="Gangguan Pendengaran Berat" {{ old('kondisi_telinga', $healthRecord->kondisi_telinga ?? '') == 'Gangguan Pendengaran Berat' ? 'selected' : '' }}>Gangguan Pendengaran Berat</option>
                                            </select>
                                            @error('kondisi_telinga')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label for="kondisi_gigi" class="form-label fw-semibold" style="color: #36474f;">Kondisi Gigi</label>
                                            <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('kondisi_gigi') is-invalid @enderror"
                                                id="kondisi_gigi" name="kondisi_gigi">
                                                <option value="Baik" {{ old('kondisi_gigi', $healthRecord->kondisi_gigi ?? 'Baik') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                                <option value="Cukup" {{ old('kondisi_gigi', $healthRecord->kondisi_gigi ?? '') == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                                <option value="Kurang" {{ old('kondisi_gigi', $healthRecord->kondisi_gigi ?? '') == 'Kurang' ? 'selected' : '' }}>Kurang</option>
                                            </select>
                                            @error('kondisi_gigi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions - Enhanced buttons with hover effect -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('user.data.academic', $pendaftar->id) }}" class="btn btn-outline-secondary btn-lg px-4" style="transition: all 0.3s">
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

    <script>
        // BMI Calculator
        function calculateBMI() {
            const height = parseFloat(document.getElementById('tinggi_badan').value);
            const weight = parseFloat(document.getElementById('berat_badan').value);
            const bmiResult = document.getElementById('bmi-result');
            const bmiDescription = document.getElementById('bmi-description');

            if (height && weight) {
                // Convert height from cm to m and calculate BMI
                const heightInMeters = height / 100;
                const bmi = weight / (heightInMeters * heightInMeters);

                // Display BMI with 1 decimal place
                bmiResult.value = bmi.toFixed(1);

                // Set description and styling based on BMI value
                let description, textClass;

                if (bmi < 18.5) {
                    description = "Berat badan kurang (Underweight)";
                    textClass = "text-info";
                } else if (bmi >= 18.5 && bmi <= 24.9) {
                    description = "Berat badan ideal (Normal)";
                    textClass = "text-success";
                } else if (bmi >= 25 && bmi <= 29.9) {
                    description = "Berat badan berlebih (Overweight)";
                    textClass = "text-warning";
                } else {
                    description = "Obesitas";
                    textClass = "text-danger";
                }

                // Update description with appropriate styling
                bmiDescription.textContent = description;
                bmiDescription.className = `form-text mt-2 fw-medium ${textClass}`;
            } else {
                bmiResult.value = "";
                bmiDescription.textContent = "Masukkan tinggi dan berat badan untuk menghitung BMI";
                bmiDescription.className = "form-text mt-2";
            }
        }

        // Add event listeners
        document.getElementById('tinggi_badan').addEventListener('input', calculateBMI);
        document.getElementById('berat_badan').addEventListener('input', calculateBMI);

        // Handle disabled fields based on selections
        document.getElementById('cacat_jasmani').addEventListener('change', function() {
            const keteranganField = document.getElementById('keterangan_cacat');
            keteranganField.disabled = this.value === 'Tidak Ada';
            if (this.value === 'Tidak Ada') {
                keteranganField.value = '';
            }
        });

        document.getElementById('kebutuhan_khusus').addEventListener('change', function() {
            const keteranganField = document.getElementById('keterangan_kebutuhan_khusus');
            keteranganField.disabled = this.value === 'Tidak Ada';
            if (this.value === 'Tidak Ada') {
                keteranganField.value = '';
            }
        });

        // Calculate BMI on page load if values exist
        document.addEventListener('DOMContentLoaded', calculateBMI);

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
    </script>
</x-app-layout>
