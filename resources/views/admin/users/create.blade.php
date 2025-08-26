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
            <div class="col-xl-8 col-lg-10">
                <!-- Progress Steps -->
                <div class="card border-0 shadow-sm mb-4" data-aos="fade-up">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="step active" id="step1">
                                    <div class="step-icon">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                    <div class="step-title">Informasi Dasar</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="step" id="step2">
                                    <div class="step-icon">
                                        <i class="bi bi-shield-lock"></i>
                                    </div>
                                    <div class="step-title">Keamanan</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="step" id="step3">
                                    <div class="step-icon">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div class="step-title">Konfirmasi</div>
                                </div>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 4px;">
                            <div class="progress-bar bg-primary" id="progressBar" style="width: 33%"></div>
                        </div>
                    </div>
                </div>

                <!-- Main Form -->
                <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-plus me-2"></i>
                            <span id="formTitle">Informasi Dasar Pengguna</span>
                        </h5>
                    </div>

                    <form action="{{ route('admin.users.store') }}" method="POST" id="userForm" novalidate>
                        @csrf
                        <div class="card-body">
                            <!-- Step 1: Basic Information -->
                            <div class="form-step active" id="formStep1">
                                <div class="row g-4">
                                    <div class="col-12 text-center mb-4">
                                        <div class="avatar-upload position-relative d-inline-block">
                                            <div class="avatar-preview rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                 style="width: 120px; height: 120px; border: 3px dashed #ddd;">
                                                <i class="bi bi-person-plus display-4 text-muted" id="avatarIcon"></i>
                                                <img id="avatarPreview" class="rounded-circle d-none" style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                            <div class="avatar-edit position-absolute bottom-0 end-0">
                                                <input type="file" id="avatarUpload" accept="image/*" class="d-none">
                                                <button type="button" class="btn btn-primary btn-sm rounded-circle" onclick="document.getElementById('avatarUpload').click()">
                                                    <i class="bi bi-camera"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">Klik untuk upload foto profil (opsional)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">
                                            <i class="bi bi-person me-1 text-primary"></i>Nama Lengkap
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name') }}"
                                               placeholder="Masukkan nama lengkap" required>
                                        <div class="invalid-feedback">
                                            @error('name') {{ $message }} @else Nama lengkap wajib diisi @enderror
                                        </div>
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>Minimal 2 karakter
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold">
                                            <i class="bi bi-envelope me-1 text-primary"></i>Email
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email') }}"
                                               placeholder="user@example.com" required>
                                        <div class="invalid-feedback">
                                            @error('email') {{ $message }} @else Email valid wajib diisi @enderror
                                        </div>
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>Akan digunakan untuk login
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="role" class="form-label fw-semibold">
                                            <i class="bi bi-shield me-1 text-primary"></i>Role
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('role') is-invalid @enderror"
                                                id="role" name="role" required>
                                            <option value="">Pilih Role</option>
                                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                                <i class="bi bi-shield-check"></i> Admin
                                            </option>
                                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>
                                                <i class="bi bi-person"></i> User
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @error('role') {{ $message }} @else Role wajib dipilih @enderror
                                        </div>
                                        <div class="form-text" id="roleDescription">
                                            <i class="bi bi-info-circle me-1"></i>Pilih role untuk menentukan hak akses
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="is_active" class="form-label fw-semibold">
                                            <i class="bi bi-toggle-on me-1 text-primary"></i>Status
                                        </label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   id="is_active" name="is_active" value="1"
                                                   {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                <span id="statusText">Aktif</span>
                                            </label>
                                        </div>
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>User aktif dapat login ke sistem
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Security -->
                            <div class="form-step" id="formStep2">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="bi bi-shield-lock me-2"></i>
                                            <strong>Keamanan Password:</strong>
                                            Password harus minimal 8 karakter dan mengandung kombinasi huruf, angka, dan simbol.
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password" class="form-label fw-semibold">
                                            <i class="bi bi-lock me-1 text-primary"></i>Password
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                   id="password" name="password" placeholder="Masukkan password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                <i class="bi bi-eye" id="passwordIcon"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">
                                            @error('password') {{ $message }} @else Password minimal 8 karakter @enderror
                                        </div>

                                        <!-- Password Strength Indicator -->
                                        <div class="mt-2">
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar" id="passwordStrength" style="width: 0%"></div>
                                            </div>
                                            <small class="text-muted" id="passwordStrengthText">Kekuatan password</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label fw-semibold">
                                            <i class="bi bi-lock-fill me-1 text-primary"></i>Konfirmasi Password
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control"
                                                   id="password_confirmation" name="password_confirmation"
                                                   placeholder="Ulangi password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                                <i class="bi bi-eye" id="passwordConfirmationIcon"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback" id="passwordMatchError">
                                            Password tidak cocok
                                        </div>
                                        <div class="valid-feedback" id="passwordMatchSuccess">
                                            Password cocok
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="card bg-light border-0">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <i class="bi bi-lightbulb me-1 text-warning"></i>
                                                    Tips Password Kuat
                                                </h6>
                                                <ul class="list-unstyled mb-0 small">
                                                    <li><i class="bi bi-check-circle text-success me-2"></i>Minimal 8 karakter</li>
                                                    <li><i class="bi bi-check-circle text-success me-2"></i>Kombinasi huruf besar dan kecil</li>
                                                    <li><i class="bi bi-check-circle text-success me-2"></i>Mengandung angka</li>
                                                    <li><i class="bi bi-check-circle text-success me-2"></i>Mengandung simbol (!@#$%^&*)</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Confirmation -->
                            <div class="form-step" id="formStep3">
                                <div class="text-center mb-4">
                                    <i class="bi bi-check-circle-fill text-success display-1 mb-3"></i>
                                    <h4 class="text-success">Konfirmasi Data User</h4>
                                    <p class="text-muted">Periksa kembali data yang akan disimpan</p>
                                </div>

                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Nama Lengkap</label>
                                                <div class="fw-bold" id="confirmName">-</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Email</label>
                                                <div class="fw-bold" id="confirmEmail">-</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Role</label>
                                                <div class="fw-bold" id="confirmRole">-</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Status</label>
                                                <div class="fw-bold" id="confirmStatus">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-warning mt-4">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Perhatian:</strong> Data yang sudah disimpan akan langsung aktif di sistem.
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary" id="prevBtn" onclick="previousStep()" disabled>
                                    <i class="bi bi-arrow-left me-1"></i>Sebelumnya
                                </button>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-warning" onclick="resetForm()">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                    </button>
                                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextStep()">
                                        Selanjutnya<i class="bi bi-arrow-right ms-1"></i>
                                    </button>
                                    <button type="submit" class="btn btn-success d-none" id="submitBtn">
                                        <i class="bi bi-check-circle me-1"></i>Simpan User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-question-circle text-info me-2"></i>
                        Bantuan Pembuatan User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="accordion" id="helpAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#help1">
                                    <i class="bi bi-person me-2"></i>Informasi Dasar
                                </button>
                            </h2>
                            <div id="help1" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <ul>
                                        <li><strong>Nama Lengkap:</strong> Masukkan nama lengkap pengguna</li>
                                        <li><strong>Email:</strong> Email unik yang akan digunakan untuk login</li>
                                        <li><strong>Role:</strong> Admin memiliki akses penuh, User memiliki akses terbatas</li>
                                        <li><strong>Status:</strong> Aktif/nonaktif menentukan apakah user bisa login</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#help2">
                                    <i class="bi bi-shield-lock me-2"></i>Keamanan Password
                                </button>
                            </h2>
                            <div id="help2" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <p>Password yang kuat harus memenuhi kriteria:</p>
                                    <ul>
                                        <li>Minimal 8 karakter</li>
                                        <li>Kombinasi huruf besar dan kecil</li>
                                        <li>Mengandung angka</li>
                                        <li>Mengandung simbol khusus</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .step {
            opacity: 0.5;
            transition: all 0.3s ease;
        }

        .step.active {
            opacity: 1;
        }

        .step.completed {
            opacity: 1;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content-center;
            margin: 0 auto 10px;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .step.active .step-icon,
        .step.completed .step-icon {
            background: #007bff;
            color: white;
        }

        .step-title {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .avatar-upload {
            cursor: pointer;
        }

        .avatar-upload:hover .avatar-preview {
            opacity: 0.8;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .progress-bar {
            transition: width 0.3s ease;
        }
    </style>

    <!-- JavaScript -->
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

            // Form validation and interactions
            initializeFormValidation();
            initializePasswordStrength();
            initializeRoleDescription();
            initializeStatusToggle();
            initializeAvatarUpload();
        });

        function nextStep() {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStep();
                    if (currentStep === totalSteps) {
                        updateConfirmationData();
                    }
                }
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                currentStep--;
                updateStep();
            }
        }

        function updateStep() {
            // Update steps visual
            for (let i = 1; i <= totalSteps; i++) {
                const stepElement = document.getElementById(`step${i}`);
                const formStep = document.getElementById(`formStep${i}`);

                if (i === currentStep) {
                    stepElement.classList.add('active');
                    stepElement.classList.remove('completed');
                    formStep.classList.add('active');
                } else if (i < currentStep) {
                    stepElement.classList.remove('active');
                    stepElement.classList.add('completed');
                    formStep.classList.remove('active');
                } else {
                    stepElement.classList.remove('active', 'completed');
                    formStep.classList.remove('active');
                }
            }

            // Update progress bar
            const progressPercentage = (currentStep / totalSteps) * 100;
            document.getElementById('progressBar').style.width = progressPercentage + '%';

            // Update buttons
            document.getElementById('prevBtn').disabled = currentStep === 1;

            if (currentStep === totalSteps) {
                document.getElementById('nextBtn').classList.add('d-none');
                document.getElementById('submitBtn').classList.remove('d-none');
            } else {
                document.getElementById('nextBtn').classList.remove('d-none');
                document.getElementById('submitBtn').classList.add('d-none');
            }

            // Update form title
            const titles = [
                'Informasi Dasar Pengguna',
                'Pengaturan Keamanan',
                'Konfirmasi Data'
            ];
            document.getElementById('formTitle').textContent = titles[currentStep - 1];
        }

        function validateCurrentStep() {
            const currentFormStep = document.getElementById(`formStep${currentStep}`);
            const requiredFields = currentFormStep.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });

            // Additional validation for step 2 (password)
            if (currentStep === 2) {
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;

                if (password !== passwordConfirmation) {
                    document.getElementById('password_confirmation').classList.add('is-invalid');
                    isValid = false;
                } else {
                    document.getElementById('password_confirmation').classList.remove('is-invalid');
                    document.getElementById('password_confirmation').classList.add('is-valid');
                }
            }

            return isValid;
        }

        function updateConfirmationData() {
            document.getElementById('confirmName').textContent = document.getElementById('name').value;
            document.getElementById('confirmEmail').textContent = document.getElementById('email').value;

            const role = document.getElementById('role').value;
            document.getElementById('confirmRole').innerHTML = role === 'admin'
                ? '<span class="badge bg-warning">Admin</span>'
                : '<span class="badge bg-info">User</span>';

            const isActive = document.getElementById('is_active').checked;
            document.getElementById('confirmStatus').innerHTML = isActive
                ? '<span class="badge bg-success">Aktif</span>'
                : '<span class="badge bg-danger">Nonaktif</span>';
        }

        function initializeFormValidation() {
            const form = document.getElementById('userForm');

            // Real-time validation
            form.addEventListener('input', function(e) {
                const field = e.target;

                if (field.hasAttribute('required')) {
                    if (field.value.trim()) {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    } else {
                        field.classList.remove('is-valid');
                        field.classList.add('is-invalid');
                    }
                }

                // Email validation
                if (field.type === 'email') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (emailRegex.test(field.value)) {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    } else {
                        field.classList.remove('is-valid');
                        field.classList.add('is-invalid');
                    }
                }
            });

            // Password confirmation validation
            document.getElementById('password_confirmation').addEventListener('input', function() {
                const password = document.getElementById('password').value;
                const confirmation = this.value;

                if (password === confirmation && password.length > 0) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    document.getElementById('passwordMatchSuccess').style.display = 'block';
                    document.getElementById('passwordMatchError').style.display = 'none';
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                    document.getElementById('passwordMatchSuccess').style.display = 'none';
                    document.getElementById('passwordMatchError').style.display = 'block';
                }
            });
        }

        function initializePasswordStrength() {
            document.getElementById('password').addEventListener('input', function() {
                const password = this.value;
                const strengthBar = document.getElementById('passwordStrength');
                const strengthText = document.getElementById('passwordStrengthText');

                let strength = 0;
                let strengthLabel = '';
                let strengthColor = '';

                if (password.length >= 8) strength += 25;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
                if (/\d/.test(password)) strength += 25;
                if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 25;

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
                strengthBar.className = `progress-bar ${strengthColor}`;
                strengthText.textContent = `Kekuatan password: ${strengthLabel}`;
            });
        }

        function initializeRoleDescription() {
            document.getElementById('role').addEventListener('change', function() {
                const roleDescription = document.getElementById('roleDescription');

                if (this.value === 'admin') {
                    roleDescription.innerHTML = '<i class="bi bi-info-circle me-1"></i>Admin memiliki akses penuh ke semua fitur sistem';
                } else if (this.value === 'user') {
                    roleDescription.innerHTML = '<i class="bi bi-info-circle me-1"></i>User memiliki akses terbatas sesuai dengan role pengguna';
                } else {
                    roleDescription.innerHTML = '<i class="bi bi-info-circle me-1"></i>Pilih role untuk menentukan hak akses';
                }
            });
        }

        function initializeStatusToggle() {
            document.getElementById('is_active').addEventListener('change', function() {
                const statusText = document.getElementById('statusText');
                statusText.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                statusText.className = this.checked ? 'text-success' : 'text-danger';
            });
        }

        function initializeAvatarUpload() {
            document.getElementById('avatarUpload').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('avatarPreview').src = e.target.result;
                        document.getElementById('avatarPreview').classList.remove('d-none');
                        document.getElementById('avatarIcon').classList.add('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

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
                document.getElementById('userForm').reset();
                currentStep = 1;
                updateStep();

                // Reset avatar
                document.getElementById('avatarPreview').classList.add('d-none');
                document.getElementById('avatarIcon').classList.remove('d-none');

                // Reset validation classes
                document.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
                    el.classList.remove('is-valid', 'is-invalid');
                });
            }
        }
    </script>
</x-app-layout>
