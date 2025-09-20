<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.settings.index') }}" class="text-decoration-none">
                                <i class="bi bi-gear-fill me-1"></i>Settings
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.settings.spp-bulk.index') }}" class="text-decoration-none">
                                SPP Bulk
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Edit Paket SPP</li>
                    </ol>
                </nav>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-pencil-square me-2 text-purple"></i>
                    Edit Paket SPP Bulk
                </h2>
                <p class="text-muted small mb-0">Ubah pengaturan paket pembayaran SPP bulk</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.settings.spp-bulk.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm" data-aos="fade-up">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-form me-2 text-primary"></i>
                            Form Edit Paket SPP Bulk
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.settings.spp-bulk.update', $sppBulkSetting) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Nama Paket <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required value="{{ old('name', $sppBulkSetting->name) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="school_level" class="form-label fw-semibold">Jenjang Sekolah <span class="text-danger">*</span></label>
                                        <select class="form-select" id="school_level" name="school_level" required>
                                            <option value="">Pilih Jenjang</option>
                                            <option value="tk" {{ old('school_level', $sppBulkSetting->school_level) == 'tk' ? 'selected' : '' }}>TK</option>
                                            <option value="sd" {{ old('school_level', $sppBulkSetting->school_level) == 'sd' ? 'selected' : '' }}>SD</option>
                                            <option value="smp" {{ old('school_level', $sppBulkSetting->school_level) == 'smp' ? 'selected' : '' }}>SMP</option>
                                            <option value="sma" {{ old('school_level', $sppBulkSetting->school_level) == 'sma' ? 'selected' : '' }}>SMA</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Academic Period -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="academic_year" class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="academic_year" name="academic_year" required value="{{ old('academic_year', $sppBulkSetting->academic_year) }}" placeholder="2024/2025">
                                    </div>
                                </div>

                                <!-- Package Details -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="months_count" class="form-label fw-semibold">Jumlah Bulan <span class="text-danger">*</span></label>
                                        <select class="form-select" id="months_count" name="months_count" required>
                                            <option value="">Pilih Jumlah Bulan</option>
                                            <option value="3" {{ old('months_count', $sppBulkSetting->months_count) == 3 ? 'selected' : '' }}>3 Bulan</option>
                                            <option value="6" {{ old('months_count', $sppBulkSetting->months_count) == 6 ? 'selected' : '' }}>6 Bulan</option>
                                            <option value="12" {{ old('months_count', $sppBulkSetting->months_count) == 12 ? 'selected' : '' }}>12 Bulan (1 Tahun)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_percentage" class="form-label fw-semibold">Persentase Diskon <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" required
                                                   value="{{ old('discount_percentage', $sppBulkSetting->discount_percentage) }}"
                                                   min="0" max="50" step="0.01">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Discount and Final Price -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_type" class="form-label fw-semibold">Jenis Diskon</label>
                                        <select class="form-select" id="discount_type" name="discount_type">
                                            <option value="">Tanpa Diskon</option>
                                            <option value="percentage" selected>Persentase (%)</option>
                                            <option value="fixed_amount">Nominal Tetap (Rp)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3" id="discountValueDiv">
                                        <label for="discount_value" class="form-label fw-semibold">Nilai Diskon</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="discount_value" name="discount_value" value="10" min="0">
                                            <span class="input-group-text" id="discountUnit">%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Calculated totals -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="normal_total" class="form-label fw-semibold">Total Normal</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="normal_total" name="normal_total" readonly value="3000000">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="final_price" class="form-label fw-semibold">Harga Final <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="final_price" name="final_price" required value="2700000" min="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Bonus and Benefits -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Bonus & Keuntungan</label>
                                        <div id="bonusContainer">
                                            <div class="bonus-item d-flex mb-2">
                                                <input type="text" class="form-control me-2" name="bonuses[]" value="Gratis 1 bulan SPP" placeholder="Masukkan bonus">
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-bonus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                            <div class="bonus-item d-flex mb-2">
                                                <input type="text" class="form-control me-2" name="bonuses[]" value="Diskon 10% untuk paket selanjutnya" placeholder="Masukkan bonus">
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-bonus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="addBonus">
                                            <i class="bi bi-plus me-1"></i>Tambah Bonus
                                        </button>
                                    </div>
                                </div>

                                <!-- Validity Period -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label fw-semibold">Berlaku Dari</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date"
                                               value="{{ old('start_date', $sppBulkSetting->start_date ? $sppBulkSetting->start_date->format('Y-m-d') : '') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label fw-semibold">Berlaku Sampai</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date"
                                               value="{{ old('end_date', $sppBulkSetting->end_date ? $sppBulkSetting->end_date->format('Y-m-d') : '') }}">
                                    </div>
                                </div>

                                <!-- Min/Max Payment Amount -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="min_payment_amount" class="form-label fw-semibold">Minimum Pembayaran</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="min_payment_amount" name="min_payment_amount"
                                                   value="{{ old('min_payment_amount', $sppBulkSetting->min_payment_amount) }}" min="0">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_payment_amount" class="form-label fw-semibold">Maksimum Pembayaran</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="max_payment_amount" name="max_payment_amount"
                                                   value="{{ old('max_payment_amount', $sppBulkSetting->max_payment_amount) }}" min="0">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="active" {{ old('status', $sppBulkSetting->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                            <option value="inactive" {{ old('status', $sppBulkSetting->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-semibold">Deskripsi Paket</label>
                                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $sppBulkSetting->description) }}</textarea>
                                    </div>
                                </div>

                                <!-- Additional Options -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Opsi Tambahan</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="allow_partial_refund" name="allow_partial_refund" value="1"
                                                   {{ old('allow_partial_refund', $sppBulkSetting->allow_partial_refund) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="allow_partial_refund">
                                                Izinkan refund sebagian
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto_apply_discount" name="auto_apply_discount" value="1"
                                                   {{ old('auto_apply_discount', $sppBulkSetting->auto_apply_discount) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="auto_apply_discount">
                                                Otomatis terapkan diskon
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="show_savings_info" name="show_savings_info" value="1"
                                                   {{ old('show_savings_info', $sppBulkSetting->show_savings_info) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_savings_info">
                                                Tampilkan info penghematan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="show_savings" name="show_savings" value="1" checked>
                                            <label class="form-check-label" for="show_savings">
                                                Tampilkan nominal penghematan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="allow_partial_months" name="allow_partial_months" value="1">
                                            <label class="form-check-label" for="allow_partial_months">
                                                Boleh dimulai dari bulan manapun
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="send_reminder" name="send_reminder" value="1" checked>
                                            <label class="form-check-label" for="send_reminder">
                                                Kirim notifikasi sebelum paket berakhir
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Package Summary -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card bg-light border-0" id="packageSummary">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0"><i class="bi bi-receipt me-2"></i>Ringkasan Paket</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center">
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-success">SPP per Bulan</div>
                                                    <div class="h5" id="summaryPerMonth">Rp 500.000</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-success">Total Normal</div>
                                                    <div class="h5" id="summaryNormal">Rp 3.000.000</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-success">Diskon</div>
                                                    <div class="h5" id="summaryDiscount">Rp 300.000</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-success">Harga Final</div>
                                                    <div class="h5" id="summaryFinal">Rp 2.700.000</div>
                                                </div>
                                            </div>
                                            <div class="text-center mt-3">
                                                <span class="badge bg-success fs-6" id="savingsAmount">Hemat Rp 300.000!</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.settings.spp-bulk.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-purple">
                                    <i class="bi bi-check-circle me-1"></i>Update Paket
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

        .bonus-item {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        #packageSummary {
            transition: all 0.3s ease;
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

        // Elements
        const sppPerMonthInput = document.getElementById('spp_per_month');
        const monthsIncludedSelect = document.getElementById('months_included');
        const discountTypeSelect = document.getElementById('discount_type');
        const discountValueInput = document.getElementById('discount_value');
        const discountUnit = document.getElementById('discountUnit');
        const discountValueDiv = document.getElementById('discountValueDiv');
        const normalTotalInput = document.getElementById('normal_total');
        const finalPriceInput = document.getElementById('final_price');

        // Summary elements
        const summaryPerMonth = document.getElementById('summaryPerMonth');
        const summaryNormal = document.getElementById('summaryNormal');
        const summaryDiscount = document.getElementById('summaryDiscount');
        const summaryFinal = document.getElementById('summaryFinal');
        const savingsAmount = document.getElementById('savingsAmount');

        // Format currency function
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        // Calculate totals function
        function calculateTotals() {
            const sppPerMonth = parseFloat(sppPerMonthInput.value) || 0;
            const monthsIncluded = parseInt(monthsIncludedSelect.value) || 0;
            const discountType = discountTypeSelect.value;
            const discountValue = parseFloat(discountValueInput.value) || 0;

            const normalTotal = sppPerMonth * monthsIncluded;
            let discountAmount = 0;

            if (discountType === 'percentage') {
                discountAmount = (normalTotal * discountValue) / 100;
            } else if (discountType === 'fixed_amount') {
                discountAmount = discountValue;
            }

            const finalPrice = normalTotal - discountAmount;

            // Update input fields
            normalTotalInput.value = normalTotal;
            finalPriceInput.value = finalPrice;

            // Update summary
            summaryPerMonth.textContent = formatCurrency(sppPerMonth);
            summaryNormal.textContent = formatCurrency(normalTotal);
            summaryDiscount.textContent = formatCurrency(discountAmount);
            summaryFinal.textContent = formatCurrency(finalPrice);
            savingsAmount.textContent = `Hemat ${formatCurrency(discountAmount)}!`;
        }

        // Event listeners for calculation
        sppPerMonthInput.addEventListener('input', calculateTotals);
        monthsIncludedSelect.addEventListener('change', calculateTotals);
        discountTypeSelect.addEventListener('change', function() {
            if (this.value === '') {
                discountValueDiv.style.display = 'none';
            } else {
                discountValueDiv.style.display = 'block';
                if (this.value === 'percentage') {
                    discountUnit.textContent = '%';
                    discountValueInput.max = 100;
                } else {
                    discountUnit.textContent = 'Rp';
                    discountValueInput.removeAttribute('max');
                }
            }
            calculateTotals();
        });
        discountValueInput.addEventListener('input', calculateTotals);

        // Initialize calculation
        calculateTotals();

        // Bonus management
        const bonusContainer = document.getElementById('bonusContainer');
        const addBonusBtn = document.getElementById('addBonus');

        addBonusBtn.addEventListener('click', function() {
            const bonusItem = document.createElement('div');
            bonusItem.className = 'bonus-item d-flex mb-2';
            bonusItem.innerHTML = `
                <input type="text" class="form-control me-2" name="bonuses[]" placeholder="Masukkan bonus">
                <button type="button" class="btn btn-outline-danger btn-sm remove-bonus">
                    <i class="bi bi-trash"></i>
                </button>
            `;
            bonusContainer.appendChild(bonusItem);
        });

        // Remove bonus
        bonusContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-bonus')) {
                e.target.closest('.bonus-item').remove();
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const sppPerMonth = parseFloat(sppPerMonthInput.value);
            const monthsIncluded = parseInt(monthsIncludedSelect.value);
            const finalPrice = parseFloat(finalPriceInput.value);

            if (sppPerMonth <= 0) {
                e.preventDefault();
                alert('SPP per bulan harus lebih dari 0');
                sppPerMonthInput.focus();
                return;
            }

            if (monthsIncluded <= 0) {
                e.preventDefault();
                alert('Jumlah bulan harus dipilih');
                monthsIncludedSelect.focus();
                return;
            }

            if (finalPrice <= 0) {
                e.preventDefault();
                alert('Harga final harus lebih dari 0');
                finalPriceInput.focus();
                return;
            }
        });
    });
    </script>
</x-app-layout>
