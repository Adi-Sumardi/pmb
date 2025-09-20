<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-plus-circle me-2 text-info"></i>
                    Tambah SPP
                </h2>
                <p class="text-muted small mb-0">Buat tarif SPP baru</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.settings.spp.index') }}" class="btn btn-outline-secondary">
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
                            Form Tambah SPP
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.settings.spp.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Nama SPP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required placeholder="Contoh: SPP SD Internal">
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
                                        <label for="amount" class="form-label fw-semibold">Nominal SPP <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="amount" name="amount" required placeholder="0" min="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Academic Year and Period -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="academic_year" class="form-label fw-semibold">Tahun Ajaran</label>
                                        <input type="text" class="form-control" id="academic_year" name="academic_year" value="2025/2026" placeholder="2025/2026">
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

                                <!-- Additional Information -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Informasi Tambahan</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="include_facilities" name="include_facilities" value="1">
                                            <label class="form-check-label" for="include_facilities">
                                                Termasuk biaya fasilitas
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="include_books" name="include_books" value="1">
                                            <label class="form-check-label" for="include_books">
                                                Termasuk biaya buku
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="include_activities" name="include_activities" value="1">
                                            <label class="form-check-label" for="include_activities">
                                                Termasuk biaya kegiatan
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label fw-semibold">Catatan</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Catatan tambahan mengenai SPP ini"></textarea>
                                    </div>
                                </div>

                                <!-- Due Date Settings -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="due_date" class="form-label fw-semibold">Tanggal Jatuh Tempo</label>
                                        <input type="number" class="form-control" id="due_date" name="due_date" placeholder="10" min="1" max="31">
                                        <small class="text-muted">Tanggal dalam bulan (1-31)</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="late_fee" class="form-label fw-semibold">Denda Keterlambatan</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="late_fee" name="late_fee" placeholder="0" min="0">
                                        </div>
                                        <small class="text-muted">Denda per hari keterlambatan</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.settings.spp.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-info">
                                    <i class="bi bi-check-circle me-1"></i>Simpan SPP
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
            border-color: #0dcaf0;
            box-shadow: 0 0 0 0.2rem rgba(13, 202, 240, 0.25);
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
                nameInput.value = `SPP ${levelText} ${originText}`;
            }
        }

        schoolLevelSelect.addEventListener('change', generateName);
        schoolOriginSelect.addEventListener('change', generateName);
    });
    </script>
</x-app-layout>
