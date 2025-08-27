<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-person-plus-fill me-2 text-primary"></i>
                    Tambah User Baru
                </h2>
                <p class="text-muted small mb-0">Buat akun pengguna baru untuk sistem PPDB</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#helpModal">
                    <i class="bi bi-question-circle me-1"></i>Bantuan
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12">
                <!-- Enhanced Progress Steps -->
                <div class="card border-0 shadow-lg mb-4" data-aos="fade-up">
                    <div class="card-body py-4">
                        <div class="progress-steps d-flex justify-content-between position-relative">
                            <!-- Progress Line -->
                            <div class="progress-line position-absolute" style="top: 25px; left: 50px; right: 50px; height: 4px; background: #e9ecef; z-index: 1;">
                                <div class="progress-fill" id="progressFill" style="height: 100%; background: linear-gradient(90deg, #667eea, #764ba2); width: 0%; transition: width 0.5s ease;"></div>
                            </div>

                            <!-- Step 1 -->
                            <div class="step-item text-center position-relative" style="z-index: 2;">
                                <div class="step-circle active" id="stepCircle1">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <div class="step-title mt-2">
                                    <h6 class="mb-0 fw-bold">Informasi Dasar</h6>
                                    <small class="text-muted">Data pribadi user</small>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="step-item text-center position-relative" style="z-index: 2;">
                                <div class="step-circle" id="stepCircle2">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <div class="step-title mt-2">
                                    <h6 class="mb-0 fw-bold">Keamanan</h6>
                                    <small class="text-muted">Password & akses</small>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="step-item text-center position-relative" style="z-index: 2;">
                                <div class="step-circle" id="stepCircle3">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="step-title mt-2">
                                    <h6 class="mb-0 fw-bold">Konfirmasi</h6>
                                    <small class="text-muted">Review & simpan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Form Card -->
                <div class="card border-0 shadow-lg overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                    <!-- Dynamic Header -->
                    <div class="card-header position-relative overflow-hidden" id="formHeader">
                        <div class="header-bg position-absolute w-100 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); opacity: 0.1;"></div>
                        <div class="d-flex align-items-center position-relative">
                            <div class="header-icon me-3">
                                <div class="icon-circle" id="headerIcon">
                                    <i class="bi bi-person-plus text-primary fs-3"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="card-title mb-0 fw-bold" id="formTitle">Informasi Dasar Pengguna</h5>
                                <p class="card-subtitle text-muted mb-0" id="formSubtitle">Masukkan data dasar pengguna</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.users.store') }}" method="POST" id="userForm" enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="card-body p-4">
                            <!-- Step 1: Enhanced Basic Information -->
                            <div class="form-step active" id="formStep1">
                                <div class="row g-4">
                                    <!-- Avatar Upload Section -->
                                    <div class="col-12">
                                        <div class="avatar-section text-center mb-4">
                                            <div class="avatar-upload-container position-relative d-inline-block">
                                                <div class="avatar-preview-large position-relative">
                                                    <div class="avatar-placeholder" id="avatarPlaceholder">
                                                        <i class="bi bi-person display-3 text-muted"></i>
                                                    </div>
                                                    <img id="avatarPreview" class="avatar-image d-none">
                                                    <div class="avatar-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                                        <div class="overlay-content text-white">
                                                            <i class="bi bi-camera fs-4 mb-2"></i>
                                                            <div class="small">Klik untuk upload</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="file" id="avatarUpload" name="avatar" accept="image/*" class="d-none">
                                                <div class="avatar-edit-btn position-absolute">
                                                    <button type="button" class="btn btn-primary btn-sm rounded-circle shadow" onclick="document.getElementById('avatarUpload').click()">
                                                        <i class="bi bi-camera"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <h6 class="mb-1">Foto Profil</h6>
                                                <small class="text-muted">JPG, PNG maksimal 2MB (opsional)</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Fields with Enhanced Design -->
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                                   id="name" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required>
                                            <label for="name">
                                                <i class="bi bi-person me-2 text-primary"></i>Nama Lengkap <span class="text-danger">*</span>
                                            </label>
                                            <div class="invalid-feedback">
                                                @error('name') {{ $message }} @else Nama lengkap wajib diisi (min. 2 karakter) @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                                            <label for="email">
                                                <i class="bi bi-envelope me-2 text-primary"></i>Email <span class="text-danger">*</span>
                                            </label>
                                            <div class="invalid-feedback">
                                                @error('email') {{ $message }} @else Email valid wajib diisi @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select form-select-lg @error('role') is-invalid @enderror" id="role" name="role" required>
                                                <option value="">Pilih Role</option>
                                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                                            </select>
                                            <label for="role">
                                                <i class="bi bi-shield me-2 text-primary"></i>Role <span class="text-danger">*</span>
                                            </label>
                                            <div class="invalid-feedback">
                                                @error('role') {{ $message }} @else Role wajib dipilih @enderror
                                            </div>
                                        </div>
                                        <div class="role-description" id="roleDescription">
                                            <div class="alert alert-info border-0 py-2">
                                                <i class="bi bi-info-circle me-2"></i>
                                                <small>Pilih role untuk menentukan hak akses pengguna</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="status-toggle-card">
                                            <div class="card border-2" style="border-color: #e9ecef !important;">
                                                <div class="card-body py-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-1">
                                                                <i class="bi bi-toggle-on me-2 text-primary"></i>Status Akun
                                                            </h6>
                                                            <small class="text-muted">Aktifkan akun setelah dibuat</small>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input form-check-input-lg" type="checkbox"
                                                                   id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold" for="is_active" id="statusLabel">
                                                                Aktif
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Enhanced Security -->
                            <div class="form-step" id="formStep2">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="security-info-card mb-4">
                                            <div class="card bg-gradient-info text-white border-0">
                                                <div class="card-body py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="security-icon me-3">
                                                            <i class="bi bi-shield-check fs-2"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">Keamanan Password</h6>
                                                            <small class="opacity-75">Password yang kuat melindungi akun dari ancaman keamanan</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="password-input-group">
                                            <div class="form-floating mb-2">
                                                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                                       id="password" name="password" placeholder="Password" required>
                                                <label for="password">
                                                    <i class="bi bi-lock me-2 text-primary"></i>Password <span class="text-danger">*</span>
                                                </label>
                                                <button class="btn btn-outline-secondary position-absolute end-0 top-50 translate-middle-y me-3 border-0"
                                                        type="button" onclick="togglePassword('password')" style="z-index: 10;">
                                                    <i class="bi bi-eye" id="passwordIcon"></i>
                                                </button>
                                                <div class="invalid-feedback">
                                                    @error('password') {{ $message }} @else Password minimal 8 karakter @enderror
                                                </div>
                                            </div>

                                            <!-- Enhanced Password Strength -->
                                            <div class="password-strength-container">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <small class="text-muted">Kekuatan Password</small>
                                                    <small class="fw-bold" id="passwordStrengthText">-</small>
                                                </div>
                                                <div class="progress mb-2" style="height: 6px;">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                         id="passwordStrength" style="width: 0%"></div>
                                                </div>
                                                <div class="password-requirements">
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <small class="requirement" id="req-length">
                                                                <i class="bi bi-circle me-1"></i>Min. 8 karakter
                                                            </small>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="requirement" id="req-case">
                                                                <i class="bi bi-circle me-1"></i>Huruf besar & kecil
                                                            </small>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="requirement" id="req-number">
                                                                <i class="bi bi-circle me-1"></i>Mengandung angka
                                                            </small>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="requirement" id="req-special">
                                                                <i class="bi bi-circle me-1"></i>Karakter khusus
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control form-control-lg"
                                                   id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required>
                                            <label for="password_confirmation">
                                                <i class="bi bi-lock-fill me-2 text-primary"></i>Konfirmasi Password <span class="text-danger">*</span>
                                            </label>
                                            <button class="btn btn-outline-secondary position-absolute end-0 top-50 translate-middle-y me-3 border-0"
                                                    type="button" onclick="togglePassword('password_confirmation')" style="z-index: 10;">
                                                <i class="bi bi-eye" id="passwordConfirmationIcon"></i>
                                            </button>
                                            <div class="invalid-feedback" id="passwordMatchError">Password tidak cocok</div>
                                            <div class="valid-feedback" id="passwordMatchSuccess">
                                                <i class="bi bi-check-circle me-1"></i>Password cocok
                                            </div>
                                        </div>

                                        <!-- Password Match Indicator -->
                                        <div class="password-match-indicator d-none" id="matchIndicator">
                                            <div class="alert alert-success border-0 py-2 mb-0">
                                                <i class="bi bi-check-circle me-2"></i>
                                                <small>Password cocok!</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Enhanced Confirmation -->
                            <div class="form-step" id="formStep3">
                                <div class="text-center mb-4">
                                    <div class="confirmation-icon mb-3">
                                        <div class="icon-circle-large bg-success bg-opacity-10">
                                            <i class="bi bi-check-circle-fill text-success display-3"></i>
                                        </div>
                                    </div>
                                    <h4 class="text-success mb-2">Konfirmasi Data User</h4>
                                    <p class="text-muted">Periksa kembali data yang akan disimpan</p>
                                </div>

                                <!-- Enhanced Data Summary -->
                                <div class="data-summary">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-4">
                                            <div class="row g-4">
                                                <!-- Avatar Preview -->
                                                <div class="col-12 text-center">
                                                    <div class="avatar-confirmation mb-3">
                                                        <div class="avatar-preview-confirm" id="avatarConfirm">
                                                            <i class="bi bi-person-circle display-4 text-muted"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Data Fields -->
                                                <div class="col-md-6">
                                                    <div class="data-item">
                                                        <label class="form-label text-muted small fw-bold text-uppercase">Nama Lengkap</label>
                                                        <div class="data-value fw-bold fs-5" id="confirmName">-</div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="data-item">
                                                        <label class="form-label text-muted small fw-bold text-uppercase">Email</label>
                                                        <div class="data-value fw-bold fs-5" id="confirmEmail">-</div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="data-item">
                                                        <label class="form-label text-muted small fw-bold text-uppercase">Role</label>
                                                        <div class="data-value" id="confirmRole">-</div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="data-item">
                                                        <label class="form-label text-muted small fw-bold text-uppercase">Status</label>
                                                        <div class="data-value" id="confirmStatus">-</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-warning border-warning mt-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle me-3 fs-5"></i>
                                        <div>
                                            <strong>Perhatian:</strong> Data yang sudah disimpan akan langsung aktif di sistem dan user dapat login menggunakan kredensial yang telah dibuat.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Footer -->
                        <div class="card-footer bg-white border-top-0 p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-outline-secondary btn-lg" id="prevBtn" onclick="previousStep()" disabled>
                                    <i class="bi bi-arrow-left me-2"></i>Sebelumnya
                                </button>

                                <div class="step-indicator">
                                    <span class="current-step fw-bold" id="currentStepText">1</span>
                                    <span class="text-muted"> dari </span>
                                    <span class="total-steps text-muted">3</span>
                                </div>

                                <div class="d-flex gap-3">
                                    <button type="button" class="btn btn-outline-warning btn-lg" onclick="resetForm()">
                                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg px-4" id="nextBtn" onclick="nextStep()">
                                        Selanjutnya<i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg px-4 d-none" id="submitBtn">
                                        <i class="bi bi-check-circle me-2"></i>Simpan User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient-info text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-question-circle me-2"></i>
                        Panduan Pembuatan User
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="help-content">
                        <!-- Step-by-step guide -->
                        <div class="mb-4">
                            <h6 class="text-primary">
                                <i class="bi bi-1-circle me-2"></i>Langkah 1: Informasi Dasar
                            </h6>
                            <ul class="list-unstyled ms-4">
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i><strong>Nama Lengkap:</strong> Masukkan nama lengkap pengguna (minimal 2 karakter)</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i><strong>Email:</strong> Email unik yang akan digunakan untuk login</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i><strong>Role:</strong> Admin (akses penuh) atau User (akses terbatas)</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i><strong>Status:</strong> Tentukan apakah user dapat login setelah dibuat</li>
                            </ul>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-primary">
                                <i class="bi bi-2-circle me-2"></i>Langkah 2: Keamanan
                            </h6>
                            <ul class="list-unstyled ms-4">
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Password minimal 8 karakter</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Kombinasi huruf besar dan kecil</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Mengandung angka</li>
                                <li class="mb-2"><i class="bi bi-check text-success me-2"></i>Mengandung karakter khusus (!@#$%^&*)</li>
                            </ul>
                        </div>

                        <div class="alert alert-info border-0">
                            <i class="bi bi-lightbulb me-2"></i>
                            <strong>Tips:</strong> Gunakan password yang mudah diingat pengguna namun tetap aman. Sarankan pengguna untuk mengubah password setelah login pertama.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <span id="successMessage">Operasi berhasil!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>

        <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <span id="errorMessage">Terjadi kesalahan!</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Enhanced CSS -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --info-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --shadow-soft: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-hover: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }

        /* Enhanced Progress Steps */
        .progress-steps {
            padding: 0 50px;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content:center;
            font-size: 1.2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 3px solid #e9ecef;
            margin: 0 auto;
        }

        .step-circle.active {
            background: var(--primary-gradient);
            color: white;
            border-color: #667eea;
            transform: scale(1.1);
            box-shadow: var(--shadow-soft);
        }

        .step-circle.completed {
            background: var(--success-gradient);
            color: white;
            border-color: #11998e;
        }

        .step-title h6 {
            transition: color 0.3s ease;
        }

        .step-item:has(.step-circle.active) .step-title h6,
        .step-item:has(.step-circle.completed) .step-title h6 {
            color: #667eea;
        }

        /* Enhanced Form Steps */
        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhanced Avatar Upload */
        .avatar-preview-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #e9ecef;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .avatar-preview-large:hover {
            border-color: #667eea;
            transform: scale(1.05);
        }

        .avatar-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-overlay {
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .avatar-preview-large:hover .avatar-overlay {
            opacity: 1;
        }

        .avatar-edit-btn {
            bottom: -5px;
            right: -5px;
        }

        /* Enhanced Form Controls */
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label,
        .form-floating > .form-select ~ label {
            color: #667eea;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
        }

        .form-control-lg,
        .form-select-lg {
            padding: 1rem 0.75rem;
            font-size: 1.1rem;
        }

        /* Status Toggle Card */
        .status-toggle-card .card:hover {
            border-color: #667eea !important;
            transform: translateY(-2px);
            box-shadow: var(--shadow-soft);
        }

        .form-check-input-lg {
            width: 2.5rem;
            height: 1.25rem;
        }

        /* Role Description */
        .role-description .alert {
            transition: all 0.3s ease;
        }

        /* Password Strength */
        .password-strength-container {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 0.5rem;
        }

        .requirement {
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .requirement.met {
            color: #198754;
        }

        .requirement.met i {
            color: #198754;
        }

        .requirement.met i::before {
            content: "\f26a"; /* check-circle-fill */
        }

        /* Password Match */
        .password-match-indicator {
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhanced Header */
        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .icon-circle-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        /* Enhanced Cards */
        .card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        /* Security Info Card */
        .bg-gradient-info {
            background: var(--info-gradient);
        }

        .security-icon {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Data Summary */
        .data-item {
            padding: 1rem;
            background: white;
            border-radius: 0.5rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .data-item:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }

        .data-value {
            color: #495057;
            margin-top: 0.5rem;
        }

        /* Avatar Confirmation */
        .avatar-preview-confirm {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #e9ecef;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            overflow: hidden;
        }

        .avatar-confirmation img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Enhanced Buttons */
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            border-radius: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-soft);
        }

        /* Enhanced Toast */
        .toast {
            border-radius: 0.75rem;
            box-shadow: var(--shadow-soft);
            margin-bottom: 0.5rem;
            min-width: 300px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .progress-steps {
                padding: 0 20px;
            }

            .step-title h6 {
                font-size: 0.9rem;
            }

            .step-title small {
                font-size: 0.75rem;
            }

            .card-footer .d-flex {
                flex-direction: column;
                gap: 1rem;
            }

            .card-footer .d-flex > div:last-child {
                order: -1;
            }
        }

        /* Loading Animation */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }
    </style>

    <!-- Enhanced JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <script>
        let currentStep = 1;
        const totalSteps = 3;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 600,
                easing: 'ease-in-out',
                once: true
            });

            // Initialize all components
            initializeFormValidation();
            initializePasswordStrength();
            initializeRoleDescription();
            initializeStatusToggle();
            initializeAvatarUpload();
            updateStepDisplay();
        });

        // Enhanced step navigation
        function nextStep() {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStep();
                    if (currentStep === totalSteps) {
                        updateConfirmationData();
                    }
                }
            } else {
                showToast('error', 'Mohon lengkapi semua field yang wajib diisi');
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                currentStep--;
                updateStep();
            }
        }

        function updateStep() {
            // Update progress fill
            const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
            document.getElementById('progressFill').style.width = progressPercentage + '%';

            // Update step circles
            for (let i = 1; i <= totalSteps; i++) {
                const circle = document.getElementById(`stepCircle${i}`);
                const formStep = document.getElementById(`formStep${i}`);

                if (i === currentStep) {
                    circle.classList.add('active');
                    circle.classList.remove('completed');
                    formStep.classList.add('active');
                } else if (i < currentStep) {
                    circle.classList.remove('active');
                    circle.classList.add('completed');
                    formStep.classList.remove('active');
                } else {
                    circle.classList.remove('active', 'completed');
                    formStep.classList.remove('active');
                }
            }

            updateStepDisplay();
            updateFormHeader();
            updateButtons();
        }

        function updateStepDisplay() {
            document.getElementById('currentStepText').textContent = currentStep;
        }

        function updateFormHeader() {
            const titles = [
                'Informasi Dasar Pengguna',
                'Pengaturan Keamanan',
                'Konfirmasi Data'
            ];

            const subtitles = [
                'Masukkan data dasar pengguna',
                'Atur password dan keamanan akun',
                'Review dan simpan data pengguna'
            ];

            const icons = [
                'bi-person-plus',
                'bi-shield-lock',
                'bi-check-circle'
            ];

            document.getElementById('formTitle').textContent = titles[currentStep - 1];
            document.getElementById('formSubtitle').textContent = subtitles[currentStep - 1];

            const headerIcon = document.querySelector('#headerIcon i');
            headerIcon.className = `${icons[currentStep - 1]} text-primary fs-3`;
        }

        function updateButtons() {
            document.getElementById('prevBtn').disabled = currentStep === 1;

            if (currentStep === totalSteps) {
                document.getElementById('nextBtn').classList.add('d-none');
                document.getElementById('submitBtn').classList.remove('d-none');
            } else {
                document.getElementById('nextBtn').classList.remove('d-none');
                document.getElementById('submitBtn').classList.add('d-none');
            }
        }

        // Enhanced validation
        function validateCurrentStep() {
            const currentFormStep = document.getElementById(`formStep${currentStep}`);
            const requiredFields = currentFormStep.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!validateField(field)) {
                    isValid = false;
                }
            });

            // Step-specific validation
            if (currentStep === 2) {
                isValid = validatePasswordStep() && isValid;
            }

            return isValid;
        }

        function validateField(field) {
            const value = field.value.trim();

            if (!value) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                return false;
            }

            // Email validation
            if (field.type === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');
                    return false;
                }
            }

            // Name validation
            if (field.name === 'name' && value.length < 2) {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
                return false;
            }

            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            return true;
        }

        function validatePasswordStep() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;

            if (password.length < 8) {
                document.getElementById('password').classList.add('is-invalid');
                return false;
            }

            if (password !== confirmation) {
                document.getElementById('password_confirmation').classList.add('is-invalid');
                return false;
            }

            return true;
        }

        // Enhanced password strength
        function initializePasswordStrength() {
            const passwordField = document.getElementById('password');

            passwordField.addEventListener('input', function() {
                const password = this.value;
                updatePasswordStrength(password);
                updatePasswordRequirements(password);
            });
        }

        function updatePasswordStrength(password) {
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('passwordStrengthText');

            let strength = 0;
            let strengthLabel = '';
            let strengthColor = '';

            // Check requirements
            if (password.length >= 8) strength += 25;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
            if (/\d/.test(password)) strength += 25;
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 25;

            // Set label and color
            if (strength <= 25) {
                strengthLabel = 'Lemah';
                strengthColor = 'bg-danger';
            } else if (strength <= 50) {
                strengthLabel = 'Sedang';
                strengthColor = 'bg-warning';
            } else if (strength <= 75) {
                strengthLabel = 'Kuat';
                strengthColor = 'bg-info';
            } else {
                strengthLabel = 'Sangat Kuat';
                strengthColor = 'bg-success';
            }

            strengthBar.style.width = strength + '%';
            strengthBar.className = `progress-bar progress-bar-striped progress-bar-animated ${strengthColor}`;
            strengthText.textContent = strengthLabel;
            strengthText.className = `fw-bold ${strengthColor.replace('bg-', 'text-')}`;
        }

        function updatePasswordRequirements(password) {
            const requirements = [
                { id: 'req-length', test: password.length >= 8 },
                { id: 'req-case', test: /[a-z]/.test(password) && /[A-Z]/.test(password) },
                { id: 'req-number', test: /\d/.test(password) },
                { id: 'req-special', test: /[!@#$%^&*(),.?":{}|<>]/.test(password) }
            ];

            requirements.forEach(req => {
                const element = document.getElementById(req.id);
                if (req.test) {
                    element.classList.add('met');
                    element.style.color = '#198754';
                } else {
                    element.classList.remove('met');
                    element.style.color = '#6c757d';
                }
            });
        }

        // Enhanced form validation
        function initializeFormValidation() {
            const form = document.getElementById('userForm');

            // Real-time validation
            form.addEventListener('input', function(e) {
                const field = e.target;

                if (field.hasAttribute('required') || field.type === 'email') {
                    validateField(field);
                }
            });

            // Password confirmation
            document.getElementById('password_confirmation').addEventListener('input', function() {
                const password = document.getElementById('password').value;
                const confirmation = this.value;

                if (password === confirmation && password.length > 0) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    document.getElementById('matchIndicator').classList.remove('d-none');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                    document.getElementById('matchIndicator').classList.add('d-none');
                }
            });
        }

        // Enhanced role description
        function initializeRoleDescription() {
            document.getElementById('role').addEventListener('change', function() {
                const roleDescription = document.getElementById('roleDescription');
                const alertDiv = roleDescription.querySelector('.alert');

                if (this.value === 'admin') {
                    alertDiv.className = 'alert alert-warning border-0 py-2';
                    alertDiv.innerHTML = '<i class="bi bi-shield-check me-2"></i><small><strong>Admin:</strong> Memiliki akses penuh ke semua fitur sistem termasuk manajemen user</small>';
                } else if (this.value === 'user') {
                    alertDiv.className = 'alert alert-info border-0 py-2';
                    alertDiv.innerHTML = '<i class="bi bi-person me-2"></i><small><strong>User:</strong> Memiliki akses terbatas sesuai dengan role pengguna biasa</small>';
                } else {
                    alertDiv.className = 'alert alert-info border-0 py-2';
                    alertDiv.innerHTML = '<i class="bi bi-info-circle me-2"></i><small>Pilih role untuk menentukan hak akses pengguna</small>';
                }
            });
        }

        // Enhanced status toggle
        function initializeStatusToggle() {
            document.getElementById('is_active').addEventListener('change', function() {
                const statusLabel = document.getElementById('statusLabel');
                if (this.checked) {
                    statusLabel.textContent = 'Aktif';
                    statusLabel.className = 'form-check-label fw-bold text-success';
                } else {
                    statusLabel.textContent = 'Nonaktif';
                    statusLabel.className = 'form-check-label fw-bold text-danger';
                }
            });
        }

        // Enhanced avatar upload
        function initializeAvatarUpload() {
            const avatarUpload = document.getElementById('avatarUpload');
            const avatarPreview = document.getElementById('avatarPreview');
            const avatarPlaceholder = document.getElementById('avatarPlaceholder');

            // Click handler for avatar container
            document.querySelector('.avatar-preview-large').addEventListener('click', function() {
                avatarUpload.click();
            });

            avatarUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        showToast('error', 'Ukuran file maksimal 2MB');
                        this.value = '';
                        return;
                    }

                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        showToast('error', 'File harus berupa gambar');
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                        avatarPreview.classList.remove('d-none');
                        avatarPlaceholder.classList.add('d-none');

                        showToast('success', 'Foto profil berhasil diupload');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Update confirmation data
        function updateConfirmationData() {
            // Basic info
            document.getElementById('confirmName').textContent = document.getElementById('name').value;
            document.getElementById('confirmEmail').textContent = document.getElementById('email').value;

            // Role badge
            const role = document.getElementById('role').value;
            const roleText = role === 'admin' ? 'Admin' : 'User';
            const roleClass = role === 'admin' ? 'bg-warning' : 'bg-info';
            document.getElementById('confirmRole').innerHTML = `<span class="badge ${roleClass} fs-6">${roleText}</span>`;

            // Status badge
            const isActive = document.getElementById('is_active').checked;
            const statusText = isActive ? 'Aktif' : 'Nonaktif';
            const statusClass = isActive ? 'bg-success' : 'bg-danger';
            document.getElementById('confirmStatus').innerHTML = `<span class="badge ${statusClass} fs-6">${statusText}</span>`;

            // Avatar confirmation
            const avatarPreview = document.getElementById('avatarPreview');
            const avatarConfirm = document.getElementById('avatarConfirm');

            if (!avatarPreview.classList.contains('d-none')) {
                avatarConfirm.innerHTML = `<img src="${avatarPreview.src}" alt="Avatar" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">`;
            }
        }

        // Utility functions
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + 'Icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }

        function resetForm() {
            if (confirm('Apakah Anda yakin ingin mereset form? Semua data yang sudah diisi akan hilang.')) {
                // Reset form
                document.getElementById('userForm').reset();

                // Reset step
                currentStep = 1;
                updateStep();

                // Reset avatar
                document.getElementById('avatarPreview').classList.add('d-none');
                document.getElementById('avatarPlaceholder').classList.remove('d-none');
                document.getElementById('avatarConfirm').innerHTML = '<i class="bi bi-person-circle display-4 text-muted"></i>';

                // Reset validation classes
                document.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
                    el.classList.remove('is-valid', 'is-invalid');
                });

                // Reset password strength
                document.getElementById('passwordStrength').style.width = '0%';
                document.getElementById('passwordStrengthText').textContent = '-';

                // Reset requirements
                document.querySelectorAll('.requirement').forEach(req => {
                    req.classList.remove('met');
                    req.style.color = '#6c757d';
                });

                showToast('success', 'Form berhasil direset');
            }
        }

        // Toast function
        function showToast(type, message) {
            const toastId = type + 'Toast';
            const messageId = type + 'Message';
            const toastElement = document.getElementById(toastId);
            const messageElement = document.getElementById(messageId);

            if (toastElement && messageElement) {
                messageElement.textContent = message;
                const toast = new bootstrap.Toast(toastElement);
                toast.show();
            }
        }

        // Form submission enhancement
        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Show loading
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
            submitBtn.disabled = true;

            // Simulate processing
            setTimeout(() => {
                showToast('success', 'User berhasil dibuat! Mengarahkan ke halaman daftar user...');

                setTimeout(() => {
                    this.submit();
                }, 1500);
            }, 1000);
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (currentStep < totalSteps) {
                    nextStep();
                } else {
                    document.getElementById('submitBtn').click();
                }
            }
        });
    </script>
</x-app-layout>
