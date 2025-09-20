<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-pencil-square me-2 text-warning"></i>
                    Edit Diskon
                </h2>
                <p class="text-muted small mb-0">Ubah informasi diskon</p>
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
                            Form Edit Diskon
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Alert Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div>
                                        <strong>Error!</strong> Ada masalah dengan data yang Anda masukkan:
                                        <ul class="mb-0 mt-2">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div><strong>Error!</strong> {{ session('error') }}</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ isset($discount) && is_object($discount) ? route('admin.settings.discounts.update', $discount->id) : '#' }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Nama Diskon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required value="{{ old('name', (isset($discount) && is_object($discount)) ? $discount->name : '') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code" class="form-label fw-semibold">Kode Diskon</label>
                                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code', (isset($discount) && is_object($discount)) ? $discount->code : '') }}">
                                    </div>
                                </div>

                                <!-- Discount Type and Value -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="type" class="form-label fw-semibold">Jenis Diskon <span class="text-danger">*</span></label>
                                        <select class="form-select" id="type" name="type" required>
                                            <option value="percentage" {{ old('type', (isset($discount) && is_object($discount)) ? $discount->type : '') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                            <option value="fixed" {{ old('type', (isset($discount) && is_object($discount)) ? $discount->type : '') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="value" class="form-label fw-semibold">Nilai Diskon <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="valuePrefix">{{ (isset($discount) && is_object($discount) && $discount->type == 'percentage') ? '%' : 'Rp' }}</span>
                                            <input type="number" class="form-control" id="value" name="value" required value="{{ old('value', (isset($discount) && is_object($discount)) ? $discount->value : '') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Target and Application -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="target" class="form-label fw-semibold">Target Pembayaran <span class="text-danger">*</span></label>
                                        <select class="form-select" id="target" name="target" required>
                                            <option value="uang_pangkal" {{ old('target', (isset($discount) && is_object($discount)) ? $discount->target : '') == 'uang_pangkal' ? 'selected' : '' }}>Uang Pangkal</option>
                                            <option value="spp" {{ old('target', (isset($discount) && is_object($discount)) ? $discount->target : '') == 'spp' ? 'selected' : '' }}>SPP</option>
                                            <option value="multi_payment" {{ old('target', (isset($discount) && is_object($discount)) ? $discount->target : '') == 'multi_payment' ? 'selected' : '' }}>Multi Payment</option>
                                            <option value="all" {{ old('target', (isset($discount) && is_object($discount)) ? $discount->target : '') == 'all' ? 'selected' : '' }}>Semua Pembayaran</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="school_level" class="form-label fw-semibold">Jenjang Sekolah</label>
                                        <select class="form-select" id="school_level" name="school_level">
                                            <option value="">Semua Jenjang</option>
                                            <option value="playgroup" {{ old('school_level', (isset($discount) && is_object($discount)) ? $discount->school_level : '') == 'playgroup' ? 'selected' : '' }}>Playgroup</option>
                                            <option value="tk" {{ old('school_level', (isset($discount) && is_object($discount)) ? $discount->school_level : '') == 'tk' ? 'selected' : '' }}>TK</option>
                                            <option value="sd" {{ old('school_level', (isset($discount) && is_object($discount)) ? $discount->school_level : '') == 'sd' ? 'selected' : '' }}>SD</option>
                                            <option value="smp" {{ old('school_level', (isset($discount) && is_object($discount)) ? $discount->school_level : '') == 'smp' ? 'selected' : '' }}>SMP</option>
                                            <option value="sma" {{ old('school_level', (isset($discount) && is_object($discount)) ? $discount->school_level : '') == 'sma' ? 'selected' : '' }}>SMA</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Period -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label fw-semibold">Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', (isset($discount) && is_object($discount) && $discount->start_date) ? $discount->start_date->format('Y-m-d') : '') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label fw-semibold">Tanggal Berakhir</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', (isset($discount) && is_object($discount) && $discount->end_date) ? $discount->end_date->format('Y-m-d') : '') }}">
                                    </div>
                                </div>

                                <!-- Additional Settings -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="max_usage" class="form-label fw-semibold">Maksimal Penggunaan</label>
                                        <input type="number" class="form-control" id="max_usage" name="max_usage" value="{{ old('max_usage', (isset($discount) && is_object($discount)) ? $discount->max_usage : '') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="active" {{ old('status', (isset($discount) && is_object($discount)) ? $discount->status : '') == 'active' ? 'selected' : '' }}>Aktif</option>
                                            <option value="inactive" {{ old('status', (isset($discount) && is_object($discount)) ? $discount->status : '') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', (isset($discount) && is_object($discount)) ? $discount->description : '') }}</textarea>
                                    </div>
                                </div>

                                <!-- Conditions -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Syarat dan Ketentuan</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="for_new_students" name="conditions[]" value="new_students" checked>
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
                                            <input class="form-check-input" type="checkbox" id="early_registration" name="conditions[]" value="early_registration" checked>
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
                                    <i class="bi bi-check-circle me-1"></i>Update Diskon
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

        typeSelect.addEventListener('change', function() {
            if (this.value === 'percentage') {
                valuePrefix.textContent = '%';
            } else if (this.value === 'fixed') {
                valuePrefix.textContent = 'Rp';
            }
        });
    });
    </script>
</x-app-layout>
