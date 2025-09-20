<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>
                    Tambah Pengaturan Cicilan
                </h2>
                <p class="text-muted small mb-0">Buat pengaturan cicilan baru</p>
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
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" data-aos="fade-up">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-form me-2 text-primary"></i>
                            Form Tambah Pengaturan Cicilan
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.settings.installments.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Nama Pengaturan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required placeholder="Contoh: Cicilan SD 4x">
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

                                <!-- Installment Configuration -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="installment_count" class="form-label fw-semibold">Jumlah Cicilan <span class="text-danger">*</span></label>
                                        <select class="form-select" id="installment_count" name="installment_count" required>
                                            <option value="">Pilih Jumlah</option>
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
                                        <label for="first_payment_percentage" class="form-label fw-semibold">Persentase Pembayaran Pertama (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="first_payment_percentage" name="first_payment_percentage" required placeholder="50" min="10" max="90">
                                        <small class="text-muted">Minimal 10%, maksimal 90%</small>
                                    </div>
                                </div>

                                <!-- Payment Schedule -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="payment_interval" class="form-label fw-semibold">Interval Pembayaran</label>
                                        <select class="form-select" id="payment_interval" name="payment_interval">
                                            <option value="monthly">Bulanan</option>
                                            <option value="bi_monthly">2 Bulan</option>
                                            <option value="quarterly">3 Bulan</option>
                                            <option value="semi_annually">6 Bulan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="late_fee_percentage" class="form-label fw-semibold">Denda Keterlambatan (%)</label>
                                        <input type="number" class="form-control" id="late_fee_percentage" name="late_fee_percentage" placeholder="0" min="0" max="10" step="0.1">
                                        <small class="text-muted">Persentase dari sisa tagihan per bulan</small>
                                    </div>
                                </div>

                                <!-- Additional Settings -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="grace_period_days" class="form-label fw-semibold">Masa Tenggang (Hari)</label>
                                        <input type="number" class="form-control" id="grace_period_days" name="grace_period_days" value="7" min="0" max="30">
                                        <small class="text-muted">Hari toleransi sebelum dikenakan denda</small>
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

                                <!-- Payment Schedule Preview -->
                                <div class="col-12">
                                    <div class="card bg-light border-0 mb-3" id="previewCard" style="display: none;">
                                        <div class="card-header bg-transparent border-0 py-2">
                                            <h6 class="mb-0 fw-semibold">Preview Jadwal Pembayaran</h6>
                                        </div>
                                        <div class="card-body pt-2">
                                            <div id="paymentSchedule"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rules and Conditions -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Syarat dan Ketentuan</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto_reminder" name="auto_reminder" value="1" checked>
                                            <label class="form-check-label" for="auto_reminder">
                                                Kirim pengingat otomatis 3 hari sebelum jatuh tempo
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="allow_early_payment" name="allow_early_payment" value="1" checked>
                                            <label class="form-check-label" for="allow_early_payment">
                                                Boleh bayar lebih awal
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="penalty_accumulative" name="penalty_accumulative" value="1">
                                            <label class="form-check-label" for="penalty_accumulative">
                                                Denda akumulatif (bertambah setiap bulan)
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label fw-semibold">Catatan</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Catatan tambahan mengenai pengaturan cicilan ini"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.settings.installments.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Simpan Pengaturan
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
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }

        .card {
            border-radius: 12px;
        }

        .payment-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
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
        const installmentCountSelect = document.getElementById('installment_count');
        const nameInput = document.getElementById('name');

        function generateName() {
            const level = schoolLevelSelect.value;
            const count = installmentCountSelect.value;

            if (level && count) {
                const levelText = level.toUpperCase();
                nameInput.value = `Cicilan ${levelText} ${count}x`;
            }
        }

        schoolLevelSelect.addEventListener('change', generateName);
        installmentCountSelect.addEventListener('change', generateName);

        // Payment schedule preview
        const firstPaymentInput = document.getElementById('first_payment_percentage');
        const previewCard = document.getElementById('previewCard');
        const paymentSchedule = document.getElementById('paymentSchedule');

        function updatePreview() {
            const count = parseInt(installmentCountSelect.value);
            const firstPercent = parseFloat(firstPaymentInput.value);

            if (count && firstPercent) {
                previewCard.style.display = 'block';

                const remainingPercent = 100 - firstPercent;
                const remainingPerPayment = remainingPercent / (count - 1);

                let html = '';
                html += `<div class="payment-item">
                    <strong>Pembayaran 1:</strong> ${firstPercent}% (Saat pendaftaran)
                </div>`;

                for (let i = 2; i <= count; i++) {
                    html += `<div class="payment-item">
                        <strong>Pembayaran ${i}:</strong> ${remainingPerPayment.toFixed(1)}%
                    </div>`;
                }

                paymentSchedule.innerHTML = html;
            } else {
                previewCard.style.display = 'none';
            }
        }

        installmentCountSelect.addEventListener('change', updatePreview);
        firstPaymentInput.addEventListener('input', updatePreview);
    });
    </script>
</x-app-layout>
