<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-plus-circle me-2 text-success"></i>
                    Tambah Uang Pangkal
                </h2>
                <p class="text-muted small mb-0">Buat tarif uang pangkal baru</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.settings.uang-pangkal.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" data-aos="fade-up">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-form me-2 text-primary"></i>
                            Form Tambah Uang Pangkal
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.settings.uang-pangkal.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Nama Uang Pangkal <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required placeholder="Contoh: Uang Pangkal SD Internal">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="school_level" class="form-label fw-semibold">Jenjang Sekolah <span class="text-danger">*</span></label>
                                        <select class="form-select" id="school_level" name="school_level" required>
                                            <option value="">Pilih Jenjang</option>
                                            <option value="playgroup">Playgroup</option>
                                            <option value="tk">TK</option>
                                            <option value="sd">SD</option>
                                            <option value="smp">SMP</option>
                                            <option value="sma">SMA</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- School Origin and Amount -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="school_origin" class="form-label fw-semibold">Asal Sekolah <span class="text-danger">*</span></label>
                                        <select class="form-select" id="school_origin" name="school_origin" required>
                                            <option value="">Pilih Asal Sekolah</option>
                                            <option value="internal">Internal YAPI</option>
                                            <option value="external">Eksternal</option>
                                        </select>
                                        <small class="text-muted">Status asal sekolah siswa</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label fw-semibold">Nominal Uang Pangkal <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="amount" name="amount" required placeholder="0" min="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Academic Year and Installment Options -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="academic_year" class="form-label fw-semibold">Tahun Ajaran</label>
                                        <input type="text" class="form-control" id="academic_year" name="academic_year" value="2025/2026" placeholder="2025/2026">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="allow_installments" class="form-label fw-semibold">Sistem Pembayaran</label>
                                        <select class="form-select" id="allow_installments" name="allow_installments">
                                            <option value="0">Lunas Langsung</option>
                                            <option value="1">Boleh Cicilan</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Installment Settings (shown when cicilan is selected) -->
                                <div class="col-12" id="installmentSettings" style="display: none;">
                                    <div class="card bg-light border-0 mb-3">
                                        <div class="card-header bg-transparent border-0 py-2">
                                            <h6 class="mb-0 fw-semibold">Pengaturan Cicilan</h6>
                                        </div>
                                        <div class="card-body pt-2">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="max_installments" class="form-label fw-semibold">Maksimal Cicilan</label>
                                                        <select class="form-select" id="max_installments" name="max_installments">
                                                            <option value="2">2 Kali</option>
                                                            <option value="3">3 Kali</option>
                                                            <option value="4">4 Kali</option>
                                                            <option value="6">6 Kali</option>
                                                            <option value="12">12 Kali</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="first_installment_percentage" class="form-label fw-semibold">Persentase Cicilan Pertama (%)</label>
                                                        <input type="number" class="form-control" id="first_installment_percentage" name="first_installment_percentage" value="50" min="10" max="90">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Include Components -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Komponen yang Termasuk</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="include_registration" name="include_registration" value="1" checked>
                                            <label class="form-check-label" for="include_registration">
                                                Biaya pendaftaran
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="include_uniform" name="include_uniform" value="1">
                                            <label class="form-check-label" for="include_uniform">
                                                Seragam sekolah
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="include_books" name="include_books" value="1">
                                            <label class="form-check-label" for="include_books">
                                                Buku pelajaran
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="include_facilities" name="include_facilities" value="1" checked>
                                            <label class="form-check-label" for="include_facilities">
                                                Biaya fasilitas
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label fw-semibold">Catatan</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Catatan tambahan mengenai uang pangkal ini"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.settings.uang-pangkal.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>Simpan Uang Pangkal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .form-label {
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }

        .card {
            border-radius: 12px;
        }
    </style>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Main JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true
        });

        // Auto-generate name based on selections
        const schoolLevelSelect = document.getElementById('school_level');
        const schoolOriginSelect = document.getElementById('school_origin');
        const nameInput = document.getElementById('name');

        function generateName() {
            const level = schoolLevelSelect.value;
            const origin = schoolOriginSelect.value;

            if (level && origin) {
                const levelText = level.toUpperCase();
                const originText = origin === 'internal' ? 'Internal' : 'Eksternal';
                nameInput.value = `Uang Pangkal ${levelText} ${originText}`;
            }
        }

        schoolLevelSelect.addEventListener('change', generateName);
        schoolOriginSelect.addEventListener('change', generateName);

        // Toggle installment settings
        const allowInstallmentsSelect = document.getElementById('allow_installments');
        const installmentSettings = document.getElementById('installmentSettings');

        allowInstallmentsSelect.addEventListener('change', function() {
            if (this.value === '1') {
                installmentSettings.style.display = 'block';
            } else {
                installmentSettings.style.display = 'none';
            }
        });
    });
    </script>
</x-app-layout>
