<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-person-gear me-2 text-primary"></i>
                    Edit User
                </h2>
                <p class="text-muted small mb-0">Ubah informasi pengguna: {{ $user->name }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row">
            <!-- User Info Card -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card border-0 shadow-sm" data-aos="fade-up">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-circle me-2"></i>
                            Informasi User
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <!-- Avatar -->
                        <div class="position-relative d-inline-block mb-3">
                            <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                 style="width: 100px; height: 100px; font-size: 2rem;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="position-absolute bottom-0 end-0">
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                    <i class="bi bi-{{ $user->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                </span>
                            </div>
                        </div>

                        <h5 class="fw-bold">{{ $user->name }}</h5>
                        <p class="text-muted mb-3">{{ $user->email }}</p>

                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <div class="fw-bold text-primary">Role</div>
                                    <span class="badge {{ $user->role === 'admin' ? 'bg-warning' : 'bg-info' }} rounded-pill">
                                        <i class="bi bi-{{ $user->role === 'admin' ? 'shield-check' : 'person' }} me-1"></i>
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="fw-bold text-primary">Status</div>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                    <i class="bi bi-{{ $user->is_active ? 'check-circle' : 'x-circle' }} me-1"></i>
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="row text-center text-muted small">
                            <div class="col-6">
                                <i class="bi bi-calendar-plus me-1"></i>
                                <div>Bergabung</div>
                                <div class="fw-semibold">{{ $user->created_at->format('d M Y') }}</div>
                            </div>
                            <div class="col-6">
                                <i class="bi bi-clock me-1"></i>
                                <div>Update Terakhir</div>
                                <div class="fw-semibold">{{ $user->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Log Card -->
                <div class="card border-0 shadow-sm mt-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-activity me-2 text-info"></i>
                            Aktivitas Terbaru
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex align-items-center py-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                        <i class="bi bi-person-plus text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">User dibuat</div>
                                    <div class="text-muted small">{{ $user->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="list-group-item d-flex align-items-center py-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-circle bg-warning bg-opacity-10 p-2">
                                        <i class="bi bi-pencil text-warning"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">Update terakhir</div>
                                    <div class="text-muted small">{{ $user->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="col-xl-8 col-lg-7">
                <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-header bg-white border-bottom">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#basicInfo" type="button">
                                    <i class="bi bi-person me-1"></i>Informasi Dasar
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#security" type="button">
                                    <i class="bi bi-shield-lock me-1"></i>Keamanan
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#permissions" type="button">
                                    <i class="bi bi-key me-1"></i>Permissions
                                </button>
                            </li>
                        </ul>
                    </div>

                    <form action="{{ route('admin.users.update', $user) }}" method="POST" id="editUserForm">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <!-- Alert Messages -->
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-circle me-2"></i>
                                    <strong>Terjadi kesalahan:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="tab-content">
                                <!-- Basic Information Tab -->
                                <div class="tab-pane fade show active" id="basicInfo">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label fw-semibold">
                                                <i class="bi bi-person me-1 text-primary"></i>Nama Lengkap
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   id="name" name="name" value="{{ old('name', $user->name) }}"
                                                   placeholder="Masukkan nama lengkap" required>
                                            <div class="invalid-feedback">
                                                @error('name') {{ $message }} @else Nama lengkap wajib diisi @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="email" class="form-label fw-semibold">
                                                <i class="bi bi-envelope me-1 text-primary"></i>Email
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{ old('email', $user->email) }}"
                                                   placeholder="user@example.com" required>
                                            <div class="invalid-feedback">
                                                @error('email') {{ $message }} @else Email valid wajib diisi @enderror
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
                                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                                    Admin
                                                </option>
                                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                                                    User
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @error('role') {{ $message }} @else Role wajib dipilih @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="is_active" class="form-label fw-semibold">
                                                <i class="bi bi-toggle-on me-1 text-primary"></i>Status
                                            </label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       id="is_active" name="is_active" value="1"
                                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    <span id="statusText">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                                </label>
                                            </div>
                                        </div>

                                        @if($user->id === auth()->id())
                                            <div class="col-12">
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                                    <strong>Perhatian:</strong> Anda sedang mengedit profil Anda sendiri.
                                                    Hati-hati saat mengubah role atau status.
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Security Tab -->
                                <div class="tab-pane fade" id="security">
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Info:</strong> Kosongkan field password jika tidak ingin mengubah password.
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="password" class="form-label fw-semibold">
                                                <i class="bi bi-lock me-1 text-primary"></i>Password Baru
                                            </label>
                                            <div class="input-group">
                                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                       id="password" name="password" placeholder="Masukkan password baru">
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                    <i class="bi bi-eye" id="passwordIcon"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback">
                                                @error('password') {{ $message }} @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="password_confirmation" class="form-label fw-semibold">
                                                <i class="bi bi-lock-fill me-1 text-primary"></i>Konfirmasi Password
                                            </label>
                                            <div class="input-group">
                                                <input type="password" class="form-control"
                                                       id="password_confirmation" name="password_confirmation"
                                                       placeholder="Ulangi password baru">
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                                    <i class="bi bi-eye" id="passwordConfirmationIcon"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="card bg-light border-0">
                                                <div class="card-body">
                                                    <h6 class="card-title">
                                                        <i class="bi bi-shield-check me-1 text-success"></i>
                                                        Riwayat Login Terakhir
                                                    </h6>
                                                    <div class="row text-center">
                                                        <div class="col-md-4">
                                                            <div class="text-muted small">Login Terakhir</div>
                                                            <div class="fw-semibold">
                                                                {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum pernah' }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-muted small">IP Address</div>
                                                            <div class="fw-semibold">{{ $user->last_login_ip ?? '-' }}</div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-muted small">User Agent</div>
                                                            <div class="fw-semibold small">{{ Str::limit($user->last_user_agent ?? '-', 20) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Permissions Tab -->
                                <div class="tab-pane fade" id="permissions">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <h6 class="fw-bold">
                                                <i class="bi bi-key me-1 text-warning"></i>
                                                Hak Akses Berdasarkan Role
                                            </h6>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card border-warning">
                                                <div class="card-header bg-warning bg-opacity-10">
                                                    <h6 class="card-title mb-0">
                                                        <i class="bi bi-shield-check me-1"></i>Admin
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled mb-0">
                                                        <li><i class="bi bi-check-circle text-success me-2"></i>Kelola semua user</li>
                                                        <li><i class="bi bi-check-circle text-success me-2"></i>Kelola pendaftar</li>
                                                        <li><i class="bi bi-check-circle text-success me-2"></i>Akses laporan</li>
                                                        <li><i class="bi bi-check-circle text-success me-2"></i>Pengaturan sistem</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card border-info">
                                                <div class="card-header bg-info bg-opacity-10">
                                                    <h6 class="card-title mb-0">
                                                        <i class="bi bi-person me-1"></i>User
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-unstyled mb-0">
                                                        <li><i class="bi bi-check-circle text-success me-2"></i>Lihat dashboard</li>
                                                        <li><i class="bi bi-check-circle text-success me-2"></i>Edit profil sendiri</li>
                                                        <li><i class="bi bi-x-circle text-danger me-2"></i>Kelola user lain</li>
                                                        <li><i class="bi bi-x-circle text-danger me-2"></i>Pengaturan sistem</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <i class="bi bi-lightbulb me-2"></i>
                                                <strong>Tips:</strong> Role menentukan hak akses user dalam sistem.
                                                Pilih dengan hati-hati sesuai tanggung jawab user.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="bi bi-clock me-1"></i>
                                    Update terakhir: {{ $user->updated_at->format('d M Y H:i') }}
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-warning" onclick="resetForm()">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-1"></i>Update User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .nav-tabs .nav-link:hover {
            transform: translateY(-2px);
            color: #495057;
        }

        .card {
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .list-group-item {
            transition: all 0.3s ease;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 600,
                easing: 'ease-in-out',
                once: true
            });

            // Initialize form validation
            initializeFormValidation();
            initializeStatusToggle();

            // Auto-hide alerts
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert.classList.contains('show')) {
                        alert.classList.remove('show');
                        setTimeout(() => alert.remove(), 300);
                    }
                });
            }, 5000);
        });

        function initializeFormValidation() {
            const form = document.getElementById('editUserForm');

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
                } else if (password.length > 0) {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
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
            if (confirm('Apakah Anda yakin ingin mereset form? Semua perubahan yang belum disimpan akan hilang.')) {
                location.reload();
            }
        }
    </script>
</x-app-layout>
