<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-pencil-square me-2 text-purple"></i>
                    Edit Rencana Cicilan
                </h2>
                <p class="text-muted small mb-0">Ubah pengaturan cicilan pembayaran</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.settings.installments.index') }}" class="btn btn-outline-secondary">
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
                            Form Edit Rencana Cicilan
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.settings.installments.update', $installment) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Nama Paket Cicilan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required value="{{ old('name', $installment->name) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="payment_type" class="form-label fw-semibold">Jenis Pembayaran <span class="text-danger">*</span></label>
                                        <select class="form-select" id="payment_type" name="payment_type" required>
                                            <option value="">Pilih Jenis Pembayaran</option>
                                            <option value="spp" selected>SPP Bulanan</option>
                                            <option value="uang_pangkal">Uang Pangkal</option>
                                            <option value="multi_payment">Multi Payment</option>
                                            <option value="other">Lainnya</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- School Level and Period -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="school_level" class="form-label fw-semibold">Jenjang Sekolah <span class="text-danger">*</span></label>
                                        <select class="form-select" id="school_level" name="school_level" required>
                                            <option value="">Pilih Jenjang</option>
                                            <option value="playgroup" {{ old('school_level', $installment->school_level) == 'playgroup' ? 'selected' : '' }}>Playgroup</option>
                                            <option value="tk" {{ old('school_level', $installment->school_level) == 'tk' ? 'selected' : '' }}>TK</option>
                                            <option value="sd" {{ old('school_level', $installment->school_level) == 'sd' ? 'selected' : '' }}>SD</option>
                                            <option value="smp" {{ old('school_level', $installment->school_level) == 'smp' ? 'selected' : '' }}>SMP</option>
                                            <option value="sma" {{ old('school_level', $installment->school_level) == 'sma' ? 'selected' : '' }}>SMA</option>
                                            <option value="all" {{ old('school_level', $installment->school_level) == 'all' ? 'selected' : '' }}>Semua Jenjang</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="academic_year" class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="academic_year" name="academic_year" required value="2024/2025" placeholder="2024/2025">
                                    </div>
                                </div>

                                <!-- Total Amount and Installment Details -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_payment_percentage" class="form-label fw-semibold">Persentase Pembayaran Pertama <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="first_payment_percentage" name="first_payment_percentage" required value="{{ old('first_payment_percentage', $installment->first_payment_percentage) }}" min="10" max="90" step="0.01">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="installment_count" class="form-label fw-semibold">Jumlah Cicilan <span class="text-danger">*</span></label>
                                        <select class="form-select" id="installment_count" name="installment_count" required>
                                            <option value="">Pilih Jumlah Cicilan</option>
                                            <option value="2" {{ old('installment_count', $installment->installment_count) == 2 ? 'selected' : '' }}>2 Kali Cicilan</option>
                                            <option value="3" {{ old('installment_count', $installment->installment_count) == 3 ? 'selected' : '' }}>3 Kali Cicilan</option>
                                            <option value="4" {{ old('installment_count', $installment->installment_count) == 4 ? 'selected' : '' }}>4 Kali Cicilan</option>
                                            <option value="5" {{ old('installment_count', $installment->installment_count) == 5 ? 'selected' : '' }}>5 Kali Cicilan</option>
                                            <option value="6" {{ old('installment_count', $installment->installment_count) == 6 ? 'selected' : '' }}>6 Kali Cicilan</option>
                                            <option value="8" {{ old('installment_count', $installment->installment_count) == 8 ? 'selected' : '' }}>8 Kali Cicilan</option>
                                            <option value="10" {{ old('installment_count', $installment->installment_count) == 10 ? 'selected' : '' }}>10 Kali Cicilan</option>
                                            <option value="12" {{ old('installment_count', $installment->installment_count) == 12 ? 'selected' : '' }}>12 Kali Cicilan</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Interest and fees -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="interest_rate" class="form-label fw-semibold">Bunga per Bulan (%)</label>
                                        <input type="number" class="form-control" id="interest_rate" name="interest_rate" value="0" min="0" max="100" step="0.1">
                                        <small class="text-muted">Kosongkan atau 0 jika tanpa bunga</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="admin_fee" class="form-label fw-semibold">Biaya Admin</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="admin_fee" name="admin_fee" value="25000" min="0">
                                        </div>
                                        <small class="text-muted">Biaya admin per cicilan</small>
                                    </div>
                                </div>

                                <!-- Due dates -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_due_date" class="form-label fw-semibold">Tanggal Jatuh Tempo Pertama <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="first_due_date" name="first_due_date" required value="2024-08-15">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="installment_interval" class="form-label fw-semibold">Interval Cicilan <span class="text-danger">*</span></label>
                                        <select class="form-select" id="installment_interval" name="installment_interval" required>
                                            <option value="monthly" selected>Bulanan</option>
                                            <option value="biweekly">Dua Minggu</option>
                                            <option value="weekly">Mingguan</option>
                                            <option value="custom">Custom</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Late payment penalty -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="late_payment_penalty" class="form-label fw-semibold">Denda Keterlambatan</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="late_payment_penalty" name="late_payment_penalty" value="50000" min="0">
                                        </div>
                                        <small class="text-muted">Denda per hari keterlambatan</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="grace_period" class="form-label fw-semibold">Masa Tenggang (Hari)</label>
                                        <input type="number" class="form-control" id="grace_period" name="grace_period" value="7" min="0" max="30">
                                        <small class="text-muted">Hari toleransi sebelum denda dikenakan</small>
                                    </div>
                                </div>

                                <!-- Status and eligibility -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="active" {{ old('status', $installment->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                            <option value="inactive" {{ old('status', $installment->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_participants" class="form-label fw-semibold">Maksimal Peserta</label>
                                        <input type="number" class="form-control" id="max_participants" name="max_participants" value="100" min="0">
                                        <small class="text-muted">Kosongkan jika tidak dibatasi</small>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $installment->description) }}</textarea>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="terms_conditions" class="form-label fw-semibold">Syarat & Ketentuan</label>
                                        <textarea class="form-control" id="terms_conditions" name="terms_conditions" rows="4">1. Pembayaran cicilan tidak dapat dibatalkan setelah disetujui
2. Keterlambatan pembayaran akan dikenakan denda sesuai ketentuan
3. Siswa yang menunggak lebih dari 2 bulan dapat dikenakan sanksi akademik
4. Pelunasan lebih awal dapat dilakukan tanpa penalti</textarea>
                                    </div>
                                </div>

                                <!-- Additional Options -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Opsi Tambahan</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="allow_early_payment" name="allow_early_payment" value="1" checked>
                                            <label class="form-check-label" for="allow_early_payment">
                                                Boleh pelunasan lebih awal
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto_reminder" name="auto_reminder" value="1" checked>
                                            <label class="form-check-label" for="auto_reminder">
                                                Kirim pengingat otomatis 3 hari sebelum jatuh tempo
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="require_approval" name="require_approval" value="1">
                                            <label class="form-check-label" for="require_approval">
                                                Memerlukan persetujuan admin
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="show_calculation" name="show_calculation" value="1" checked>
                                            <label class="form-check-label" for="show_calculation">
                                                Tampilkan rincian perhitungan ke siswa/orangtua
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Calculation Preview -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card bg-light border-0" id="calculationPreview">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0"><i class="bi bi-calculator me-2"></i>Preview Perhitungan Cicilan</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center">
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-primary">Total Nominal</div>
                                                    <div class="h5" id="previewTotal">Rp 3.000.000</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-primary">Per Cicilan</div>
                                                    <div class="h5" id="previewPerInstallment">Rp 500.000</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-primary">Biaya Admin Total</div>
                                                    <div class="h5" id="previewAdminFee">Rp 150.000</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-primary">Total Dibayar</div>
                                                    <div class="h5" id="previewGrandTotal">Rp 3.150.000</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.settings.installments.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-purple">
                                    <i class="bi bi-check-circle me-1"></i>Update Cicilan
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

        #calculationPreview {
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

        // Calculation elements
        const totalAmountInput = document.getElementById('total_amount');
        const installmentCountSelect = document.getElementById('installment_count');
        const adminFeeInput = document.getElementById('admin_fee');
        const interestRateInput = document.getElementById('interest_rate');

        // Preview elements
        const previewTotal = document.getElementById('previewTotal');
        const previewPerInstallment = document.getElementById('previewPerInstallment');
        const previewAdminFee = document.getElementById('previewAdminFee');
        const previewGrandTotal = document.getElementById('previewGrandTotal');

        // Format currency function
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        // Calculate installment function
        function calculateInstallment() {
            const totalAmount = parseFloat(totalAmountInput.value) || 0;
            const installmentCount = parseInt(installmentCountSelect.value) || 1;
            const adminFee = parseFloat(adminFeeInput.value) || 0;
            const interestRate = parseFloat(interestRateInput.value) || 0;

            // Basic calculations
            const baseInstallment = totalAmount / installmentCount;
            const interestAmount = (totalAmount * interestRate / 100) / installmentCount;
            const perInstallment = baseInstallment + interestAmount;
            const totalAdminFee = adminFee * installmentCount;
            const grandTotal = totalAmount + totalAdminFee + (interestAmount * installmentCount);

            // Update preview
            previewTotal.textContent = formatCurrency(totalAmount);
            previewPerInstallment.textContent = formatCurrency(perInstallment + adminFee);
            previewAdminFee.textContent = formatCurrency(totalAdminFee);
            previewGrandTotal.textContent = formatCurrency(grandTotal);
        }

        // Add event listeners for calculation
        totalAmountInput.addEventListener('input', calculateInstallment);
        installmentCountSelect.addEventListener('change', calculateInstallment);
        adminFeeInput.addEventListener('input', calculateInstallment);
        interestRateInput.addEventListener('input', calculateInstallment);

        // Initialize calculation on page load
        calculateInstallment();

        // Payment type change handler
        const paymentTypeSelect = document.getElementById('payment_type');
        paymentTypeSelect.addEventListener('change', function() {
            // Update default values based on payment type
            if (this.value === 'spp') {
                totalAmountInput.value = 3000000;
                installmentCountSelect.value = 6;
            } else if (this.value === 'uang_pangkal') {
                totalAmountInput.value = 5000000;
                installmentCountSelect.value = 4;
            } else if (this.value === 'multi_payment') {
                totalAmountInput.value = 2000000;
                installmentCountSelect.value = 3;
            }
            calculateInstallment();
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const totalAmount = parseFloat(totalAmountInput.value);
            const installmentCount = parseInt(installmentCountSelect.value);

            if (totalAmount <= 0) {
                e.preventDefault();
                alert('Total nominal harus lebih dari 0');
                totalAmountInput.focus();
                return;
            }

            if (installmentCount <= 0) {
                e.preventDefault();
                alert('Jumlah cicilan harus dipilih');
                installmentCountSelect.focus();
                return;
            }
        });
    });
    </script>
</x-app-layout>
