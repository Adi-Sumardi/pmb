<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-plus-circle me-2 text-warning"></i>
                    Tambah SPP Bulk Payment
                </h2>
                <p class="text-muted small mb-0">Buat pengaturan pembayaran SPP bulk baru</p>
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
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" data-aos="fade-up">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-form me-2 text-primary"></i>
                            Form Tambah SPP Bulk Payment
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.settings.spp-bulk.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Nama Pengaturan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required placeholder="Contoh: SPP SD 12 Bulan">
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

                                <!-- Period and Discount -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="months_count" class="form-label fw-semibold">Periode Pembayaran <span class="text-danger">*</span></label>
                                        <select class="form-select" id="months_count" name="months_count" required>
                                            <option value="">Pilih Periode</option>
                                            <option value="3">3 Bulan (Triwulan)</option>
                                            <option value="6">6 Bulan (Semester)</option>
                                            <option value="12">12 Bulan (Tahunan)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="discount_percentage" class="form-label fw-semibold">Persentase Diskon (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" required placeholder="0" min="0" max="50" step="0.1">
                                        <small class="text-muted">Maksimal 50%</small>
                                    </div>
                                </div>

                                <!-- Academic Year and Application Period -->
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

                                <!-- Application Period -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label fw-semibold">Tanggal Mulai Berlaku</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label fw-semibold">Tanggal Berakhir</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date">
                                    </div>
                                </div>

                                <!-- Calculation Preview -->
                                <div class="col-12">
                                    <div class="card bg-light border-0 mb-3" id="calculationPreview" style="display: none;">
                                        <div class="card-header bg-transparent border-0 py-2">
                                            <h6 class="mb-0 fw-semibold">Preview Perhitungan</h6>
                                        </div>
                                        <div class="card-body pt-2">
                                            <div id="calculationDetails"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Rules -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Aturan Pembayaran</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="allow_partial_refund" name="allow_partial_refund" value="1">
                                            <label class="form-check-label" for="allow_partial_refund">
                                                Boleh refund jika siswa pindah/keluar
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="auto_apply_discount" name="auto_apply_discount" value="1" checked>
                                            <label class="form-check-label" for="auto_apply_discount">
                                                Otomatis terapkan diskon saat pembayaran
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="show_savings_info" name="show_savings_info" value="1" checked>
                                            <label class="form-check-label" for="show_savings_info">
                                                Tampilkan informasi penghematan di invoice
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Settings -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="min_payment_amount" class="form-label fw-semibold">Minimal Nominal SPP</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="min_payment_amount" name="min_payment_amount" placeholder="0" min="0">
                                        </div>
                                        <small class="text-muted">Minimal nominal untuk bisa menggunakan bulk payment</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_usage_per_student" class="form-label fw-semibold">Maksimal Penggunaan per Siswa</label>
                                        <input type="number" class="form-control" id="max_usage_per_student" name="max_usage_per_student" value="1" min="1" max="10">
                                        <small class="text-muted">Berapa kali siswa bisa menggunakan dalam 1 tahun</small>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="terms_conditions" class="form-label fw-semibold">Syarat dan Ketentuan</label>
                                        <textarea class="form-control" id="terms_conditions" name="terms_conditions" rows="3" placeholder="Syarat dan ketentuan untuk pembayaran bulk SPP"></textarea>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label fw-semibold">Catatan</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Catatan tambahan mengenai pengaturan ini"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.settings.spp-bulk.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-warning">
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
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }

        .card {
            border-radius: 12px;
        }

        .calculation-item {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 8px;
        }

        .savings-highlight {
            background: #22c55e;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-weight: 600;
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
        const monthsCountSelect = document.getElementById('months_count');
        const nameInput = document.getElementById('name');

        function generateName() {
            const level = schoolLevelSelect.value;
            const months = monthsCountSelect.value;

            if (level && months) {
                const levelText = level.toUpperCase();
                nameInput.value = `SPP ${levelText} ${months} Bulan`;
            }
        }

        schoolLevelSelect.addEventListener('change', generateName);
        monthsCountSelect.addEventListener('change', generateName);

        // Calculation preview
        const discountInput = document.getElementById('discount_percentage');
        const calculationPreview = document.getElementById('calculationPreview');
        const calculationDetails = document.getElementById('calculationDetails');

        function updateCalculation() {
            const months = parseInt(monthsCountSelect.value);
            const discount = parseFloat(discountInput.value);

            if (months && discount) {
                calculationPreview.style.display = 'block';

                // Sample SPP amount (this would come from actual SPP settings)
                const monthlySpp = 400000; // Rp 400,000
                const totalWithoutDiscount = monthlySpp * months;
                const discountAmount = totalWithoutDiscount * (discount / 100);
                const totalWithDiscount = totalWithoutDiscount - discountAmount;
                const annualSavings = discountAmount * (12 / months); // Annualized savings

                let html = `
                    <div class="calculation-item">
                        <strong>SPP Bulanan:</strong> Rp ${monthlySpp.toLocaleString('id-ID')}
                    </div>
                    <div class="calculation-item">
                        <strong>Total ${months} Bulan (tanpa diskon):</strong> Rp ${totalWithoutDiscount.toLocaleString('id-ID')}
                    </div>
                    <div class="calculation-item">
                        <strong>Diskon ${discount}%:</strong> -Rp ${discountAmount.toLocaleString('id-ID')}
                    </div>
                    <div class="calculation-item">
                        <strong>Total Setelah Diskon:</strong> Rp ${totalWithDiscount.toLocaleString('id-ID')}
                    </div>
                    <div class="savings-highlight">
                        💰 Penghematan per ${months} bulan: Rp ${discountAmount.toLocaleString('id-ID')}
                        <br>📈 Estimasi penghematan per tahun: Rp ${annualSavings.toLocaleString('id-ID')}
                    </div>
                `;

                calculationDetails.innerHTML = html;
            } else {
                calculationPreview.style.display = 'none';
            }
        }

        monthsCountSelect.addEventListener('change', updateCalculation);
        discountInput.addEventListener('input', updateCalculation);
    });
    </script>
</x-app-layout>
