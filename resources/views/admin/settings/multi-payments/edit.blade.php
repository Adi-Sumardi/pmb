<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-pencil-square me-2 text-purple"></i>
                    Edit Multi Payment Item
                </h2>
                <p class="text-muted small mb-0">Ubah informasi item pembayaran</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.settings.multi-payments.index') }}" class="btn btn-outline-secondary">
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
                            Form Edit Multi Payment Item
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.settings.multi-payments.update', $multiPayment) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Nama Item <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required value="{{ old('name', $multiPayment->name) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="books" {{ old('category', $multiPayment->category) == 'books' ? 'selected' : '' }}>Buku</option>
                                            <option value="uniforms" {{ old('category', $multiPayment->category) == 'uniforms' ? 'selected' : '' }}>Seragam</option>
                                            <option value="supplies" {{ old('category', $multiPayment->category) == 'supplies' ? 'selected' : '' }}>Alat Tulis</option>
                                            <option value="equipment" {{ old('category', $multiPayment->category) == 'equipment' ? 'selected' : '' }}>Peralatan</option>
                                            <option value="others" {{ old('category', $multiPayment->category) == 'others' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- School Level and Amount -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="school_level" class="form-label fw-semibold">Jenjang Sekolah <span class="text-danger">*</span></label>
                                        <select class="form-select" id="school_level" name="school_level" required>
                                            <option value="">Pilih Jenjang</option>
                                            <option value="playgroup" {{ old('school_level', $multiPayment->school_level) == 'playgroup' ? 'selected' : '' }}>Playgroup</option>
                                            <option value="tk" {{ old('school_level', $multiPayment->school_level) == 'tk' ? 'selected' : '' }}>TK</option>
                                            <option value="sd" {{ old('school_level', $multiPayment->school_level) == 'sd' ? 'selected' : '' }}>SD</option>
                                            <option value="smp" {{ old('school_level', $multiPayment->school_level) == 'smp' ? 'selected' : '' }}>SMP</option>
                                            <option value="sma" {{ old('school_level', $multiPayment->school_level) == 'sma' ? 'selected' : '' }}>SMA</option>
                                            <option value="all" {{ old('school_level', $multiPayment->school_level) == 'all' ? 'selected' : '' }}>Semua Jenjang</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="amount" class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="amount" name="amount" required value="{{ old('amount', $multiPayment->amount) }}" min="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Requirements and Status -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="is_mandatory" class="form-label fw-semibold">Jenis Pembayaran <span class="text-danger">*</span></label>
                                        <select class="form-select" id="is_mandatory" name="is_mandatory" required>
                                            <option value="1" {{ old('is_mandatory', $multiPayment->is_mandatory) == 1 ? 'selected' : '' }}>Wajib</option>
                                            <option value="0" {{ old('is_mandatory', $multiPayment->is_mandatory) == 0 ? 'selected' : '' }}>Opsional</option>
                                        </select>
                                        <small class="text-muted">Item wajib akan otomatis masuk ke tagihan</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="active" {{ old('status', $multiPayment->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                            <option value="inactive" {{ old('status', $multiPayment->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Specific Level (for books) -->
                                <div class="col-md-6" id="specificLevelDiv">
                                    <div class="mb-3">
                                        <label for="specific_level" class="form-label fw-semibold">Kelas/Tingkat Spesifik</label>
                                        <select class="form-select" id="specific_level" name="specific_level">
                                            <option value="">Semua Kelas</option>
                                            <option value="1" selected>Kelas 1</option>
                                            <option value="2">Kelas 2</option>
                                            <option value="3">Kelas 3</option>
                                            <option value="4">Kelas 4</option>
                                            <option value="5">Kelas 5</option>
                                            <option value="6">Kelas 6</option>
                                        </select>
                                        <small class="text-muted">Khusus untuk buku pelajaran</small>
                                    </div>
                                </div>

                                <!-- Size Options (for uniforms) -->
                                <div class="col-md-6" id="sizeOptionsDiv" style="display: none;">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tersedia Ukuran</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="size_s" name="available_sizes[]" value="S">
                                            <label class="form-check-label" for="size_s">S (Small)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="size_m" name="available_sizes[]" value="M" checked>
                                            <label class="form-check-label" for="size_m">M (Medium)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="size_l" name="available_sizes[]" value="L" checked>
                                            <label class="form-check-label" for="size_l">L (Large)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="size_xl" name="available_sizes[]" value="XL">
                                            <label class="form-check-label" for="size_xl">XL (Extra Large)</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Supplier Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="supplier" class="form-label fw-semibold">Supplier/Vendor</label>
                                        <input type="text" class="form-control" id="supplier" name="supplier" value="Toko Buku Pendidikan">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stock_quantity" class="form-label fw-semibold">Stok Tersedia</label>
                                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="100" min="0">
                                        <small class="text-muted">Kosongkan jika tidak dibatasi</small>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $multiPayment->description) }}</textarea>
                                    </div>
                                </div>

                                <!-- Additional Options -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Opsi Tambahan</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="allow_partial_payment" name="allow_partial_payment" value="1">
                                            <label class="form-check-label" for="allow_partial_payment">
                                                Boleh dibayar sebagian
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="show_in_invoice" name="show_in_invoice" value="1" checked>
                                            <label class="form-check-label" for="show_in_invoice">
                                                Tampilkan di invoice
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto_add_new_students" name="auto_add_new_students" value="1" checked>
                                            <label class="form-check-label" for="auto_add_new_students">
                                                Otomatis ditambahkan untuk siswa baru
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.settings.multi-payments.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-purple">
                                    <i class="bi bi-check-circle me-1"></i>Update Item
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
        .text-purple { color: #6f42c1 !important; }
        .bg-purple { background-color: #6f42c1 !important; }
        .btn-purple {
            background-color: #6f42c1;
            border-color: #6f42c1;
            color: white;
        }
        .btn-purple:hover {
            background-color: #5a2d91;
            border-color: #5a2d91;
            color: white;
        }

        .form-label {
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
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

        // Category-specific field management
        const categorySelect = document.getElementById('category');
        const specificLevelDiv = document.getElementById('specificLevelDiv');
        const sizeOptionsDiv = document.getElementById('sizeOptionsDiv');

        function toggleCategoryFields() {
            // Hide all specific fields first
            specificLevelDiv.style.display = 'none';
            sizeOptionsDiv.style.display = 'none';

            // Show relevant fields based on category
            if (categorySelect.value === 'books') {
                specificLevelDiv.style.display = 'block';
            } else if (categorySelect.value === 'uniforms') {
                sizeOptionsDiv.style.display = 'block';
            }
        }

        // Initialize on page load
        toggleCategoryFields();

        categorySelect.addEventListener('change', toggleCategoryFields);

        // School level change for specific level options
        const schoolLevelSelect = document.getElementById('school_level');
        const specificLevelSelect = document.getElementById('specific_level');

        schoolLevelSelect.addEventListener('change', function() {
            // Clear and update specific level options based on school level
            specificLevelSelect.innerHTML = '<option value="">Semua Kelas</option>';

            if (this.value === 'sd') {
                for (let i = 1; i <= 6; i++) {
                    const selected = i === 1 ? 'selected' : '';
                    specificLevelSelect.innerHTML += `<option value="${i}" ${selected}>Kelas ${i}</option>`;
                }
            } else if (this.value === 'smp' || this.value === 'sma') {
                for (let i = 1; i <= 3; i++) {
                    specificLevelSelect.innerHTML += `<option value="${i}">Kelas ${i}</option>`;
                }
            } else if (this.value === 'tk') {
                specificLevelSelect.innerHTML += '<option value="A">TK A</option>';
                specificLevelSelect.innerHTML += '<option value="B">TK B</option>';
            }
        });
    });
    </script>
</x-app-layout>
