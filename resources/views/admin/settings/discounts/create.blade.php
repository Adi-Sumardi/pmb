<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-plus-circle me-2 text-warning"></i>
                    Tambah Diskon Baru
                </h2>
                <p class="text-muted small mb-0">Buat diskon baru untuk pendaftaran siswa</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.settings.discounts.index') }}" class="btn btn-outline-secondary">
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
                            Form Tambah Diskon
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.settings.discounts.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Nama Diskon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required placeholder="Contoh: Early Bird Discount">
                                        <div class="form-text">Nama yang mudah diingat untuk diskon ini</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code" class="form-label fw-semibold">Kode Diskon</label>
                                        <input type="text" class="form-control" id="code" name="code" placeholder="EARLY2026">
                                        <div class="form-text">Kode unik untuk diskon (opsional)</div>
                                    </div>
                                </div>

                                <!-- Discount Type and Value -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="type" class="form-label fw-semibold">Jenis Diskon <span class="text-danger">*</span></label>
                                        <select class="form-select" id="type" name="type" required>
                                            <option value="">Pilih jenis diskon</option>
                                            <option value="percentage">Persentase (%)</option>
                                            <option value="fixed">Nominal Tetap (Rp)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="value" class="form-label fw-semibold">Nilai Diskon <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="valuePrefix">%</span>
                                            <input type="number" class="form-control" id="value" name="value" required min="0" step="0.01">
                                        </div>
                                        <div class="form-text" id="valueHelp">Masukkan nilai diskon</div>
                                    </div>
                                </div>

                                <!-- Target and Application -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="target" class="form-label fw-semibold">Target Pembayaran <span class="text-danger">*</span></label>
                                        <select class="form-select" id="target" name="target" required>
                                            <option value="">Pilih target</option>
                                            <option value="uang_pangkal">Uang Pangkal</option>
                                            <option value="spp">SPP</option>
                                            <option value="multi_payment">Multi Payment</option>
                                            <option value="all">Semua Pembayaran</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="school_level" class="form-label fw-semibold">Jenjang Sekolah</label>
                                        <select class="form-select" id="school_level" name="school_level">
                                            <option value="">Semua Jenjang</option>
                                            <option value="playgroup">Playgroup</option>
                                            <option value="tk">TK</option>
                                            <option value="sd">SD</option>
                                            <option value="smp">SMP</option>
                                            <option value="sma">SMA</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Period -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label fw-semibold">Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label fw-semibold">Tanggal Berakhir</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date">
                                    </div>
                                </div>

                                <!-- Additional Settings -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_usage" class="form-label fw-semibold">Maksimal Penggunaan</label>
                                        <input type="number" class="form-control" id="max_usage" name="max_usage" min="0" placeholder="0 = tidak terbatas">
                                        <div class="form-text">Kosongkan atau isi 0 untuk tidak terbatas</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Deskripsi lengkap tentang diskon ini..."></textarea>
                                    </div>
                                </div>

                                <!-- Conditions -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Syarat dan Ketentuan</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="for_new_students" name="conditions[]" value="new_students">
                                            <label class="form-check-label" for="for_new_students">
                                                Hanya untuk siswa baru
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="first_payment" name="conditions[]" value="first_payment">
                                            <label class="form-check-label" for="first_payment">
                                                Hanya untuk pembayaran pertama
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="early_registration" name="conditions[]" value="early_registration">
                                            <label class="form-check-label" for="early_registration">
                                                Pendaftaran di periode tertentu
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.settings.discounts.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-circle me-1"></i>Simpan Diskon
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
            border-color: #fbbf24;
            box-shadow: 0 0 0 0.2rem rgba(251, 191, 36, 0.25);
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }

        .card {
            border-radius: 12px;
        }

        .input-group-text {
            background-color: #f3f4f6;
            border-color: #d1d5db;
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

        // Handle discount type change
        const typeSelect = document.getElementById('type');
        const valuePrefix = document.getElementById('valuePrefix');
        const valueHelp = document.getElementById('valueHelp');

        typeSelect.addEventListener('change', function() {
            if (this.value === 'percentage') {
                valuePrefix.textContent = '%';
                valueHelp.textContent = 'Masukkan persentase diskon (contoh: 20 untuk 20%)';
            } else if (this.value === 'fixed') {
                valuePrefix.textContent = 'Rp';
                valueHelp.textContent = 'Masukkan nominal diskon (contoh: 500000 untuk Rp 500.000)';
            } else {
                valuePrefix.textContent = '';
                valueHelp.textContent = 'Masukkan nilai diskon';
            }
        });

        // Validate end date is after start date
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        startDate.addEventListener('change', function() {
            endDate.min = this.value;
        });

        // Format currency input for fixed type
        const valueInput = document.getElementById('value');
        valueInput.addEventListener('input', function() {
            if (typeSelect.value === 'fixed') {
                // Remove non-numeric characters
                let value = this.value.replace(/[^\d]/g, '');
                this.value = value;
            }
        });
    });
    </script>
</x-app-layout>
