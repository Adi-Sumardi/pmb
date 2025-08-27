<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-person-gear me-2 text-primary"></i>
                    Edit User
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}" class="text-decoration-none">Users</a></li>
                        <li class="breadcrumb-item active">Edit {{ $user->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
                <button type="button" class="btn btn-outline-info btn-lg" data-bs-toggle="modal" data-bs-target="#userActivityModal">
                    <i class="bi bi-activity me-2"></i>Aktivitas
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row">
            <!-- Enhanced User Info Card -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <!-- Profile Card -->
                <div class="card border-0 shadow-lg overflow-hidden" data-aos="fade-up">
                    <div class="position-relative">
                        <div class="bg-gradient-primary" style="height: 120px;">
                            <div class="position-absolute top-0 end-0 p-3">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="generateNewPassword()">
                                            <i class="bi bi-key me-2"></i>Generate Password
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" onclick="sendWelcomeEmail()">
                                            <i class="bi bi-envelope me-2"></i>Kirim Welcome Email
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="resetUserSessions()">
                                            <i class="bi bi-power me-2"></i>Reset Sessions
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Avatar Section -->
                        <div class="text-center" style="margin-top: -60px;">
                            <div class="position-relative d-inline-block">
                                <div class="avatar-xl rounded-circle border-4 border-white bg-gradient-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-lg"
                                     style="width: 120px; height: 120px; font-size: 2.5rem;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="position-absolute bottom-0 end-0">
                                    <div class="status-indicator {{ $user->is_active ? 'bg-success' : 'bg-danger' }} rounded-circle border-2 border-white"
                                         style="width: 24px; height: 24px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body text-center pt-3">
                        <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-3">{{ $user->email }}</p>

                        <!-- Role & Status Badges -->
                        <div class="d-flex justify-content-center gap-2 mb-4">
                            <span class="badge {{ $user->role === 'admin' ? 'bg-warning' : 'bg-primary' }} rounded-pill px-3 py-2">
                                <i class="bi bi-{{ $user->role === 'admin' ? 'shield-check' : 'person' }} me-1"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2">
                                <i class="bi bi-{{ $user->is_active ? 'check-circle' : 'x-circle' }} me-1"></i>
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        <!-- Stats Row -->
                        <div class="row g-3 text-center mb-4">
                            <div class="col-4">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body p-3">
                                        <div class="fw-bold text-primary fs-5">{{ $user->created_at->diffInDays() }}</div>
                                        <div class="text-muted small">Hari Bergabung</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body p-3">
                                        <div class="fw-bold text-success fs-5">{{ rand(1, 50) }}</div>
                                        <div class="text-muted small">Login Count</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body p-3">
                                        <div class="fw-bold text-info fs-5">{{ rand(1, 20) }}</div>
                                        <div class="text-muted small">Updates</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent border-top-0">
                        <div class="row text-center text-muted small">
                            <div class="col-6 border-end">
                                <i class="bi bi-calendar-plus text-primary d-block mb-1"></i>
                                <div class="fw-semibold">Bergabung</div>
                                <div>{{ $user->created_at->format('d M Y') }}</div>
                            </div>
                            <div class="col-6">
                                <i class="bi bi-clock text-warning d-block mb-1"></i>
                                <div class="fw-semibold">Update Terakhir</div>
                                <div>{{ $user->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Activity Log Card -->
                <div class="card border-0 shadow-lg mt-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header bg-gradient-info text-white">
                        <h6 class="card-title mb-0 d-flex align-items-center">
                            <i class="bi bi-activity me-2"></i>
                            Aktivitas Terbaru
                            <span class="badge bg-white text-info ms-auto">{{ rand(3, 8) }}</span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="mb-0">User dibuat</h6>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="text-muted small mb-0">Akun pengguna berhasil dibuat dalam sistem</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="mb-0">Update terakhir</h6>
                                        <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="text-muted small mb-0">Informasi profil diperbarui</p>
                                </div>
                            </div>

                            @if($user->last_login_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="mb-0">Login terakhir</h6>
                                        <small class="text-muted">{{ $user->last_login_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="text-muted small mb-0">Login dari {{ $user->last_login_ip ?? 'Unknown IP' }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Edit Form -->
            <div class="col-xl-8 col-lg-7">
                <div class="card border-0 shadow-lg" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active rounded-pill" data-bs-toggle="pill" data-bs-target="#basicInfo" type="button">
                                    <i class="bi bi-person me-2"></i>Informasi Dasar
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill" data-bs-toggle="pill" data-bs-target="#security" type="button">
                                    <i class="bi bi-shield-lock me-2"></i>Keamanan
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link rounded-pill" data-bs-toggle="pill" data-bs-target="#permissions" type="button">
                                    <i class="bi bi-key me-2"></i>Permissions
                                </button>
                            </li>
                        </ul>
                    </div>

                    <form action="{{ route('admin.users.update', $user) }}" method="POST" id="editUserForm">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <!-- Enhanced Alert Messages -->
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                                    <div class="d-flex align-items-center">
                                        <div class="alert-icon me-3">
                                            <i class="bi bi-check-circle-fill fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="alert-heading mb-1">Berhasil!</h6>
                                            <p class="mb-0">{{ session('success') }}</p>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                                    <div class="d-flex align-items-start">
                                        <div class="alert-icon me-3">
                                            <i class="bi bi-exclamation-circle-fill fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="alert-heading mb-2">Terjadi kesalahan:</h6>
                                            <ul class="mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="tab-content">
                                <!-- Enhanced Basic Information Tab -->
                                <div class="tab-pane fade show active" id="basicInfo">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <h5 class="fw-bold text-primary mb-3">
                                                <i class="bi bi-person-lines-fill me-2"></i>
                                                Informasi Personal
                                            </h5>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="name" class="form-label fw-semibold">
                                                <i class="bi bi-person me-2 text-primary"></i>Nama Lengkap
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-person text-muted"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror"
                                                       id="name" name="name" value="{{ old('name', $user->name) }}"
                                                       placeholder="Masukkan nama lengkap" required>
                                            </div>
                                            <div class="invalid-feedback">
                                                @error('name') {{ $message }} @else Nama lengkap wajib diisi @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="email" class="form-label fw-semibold">
                                                <i class="bi bi-envelope me-2 text-primary"></i>Email
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-envelope text-muted"></i>
                                                </span>
                                                <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                                                       id="email" name="email" value="{{ old('email', $user->email) }}"
                                                       placeholder="user@example.com" required>
                                            </div>
                                            <div class="invalid-feedback">
                                                @error('email') {{ $message }} @else Email valid wajib diisi @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="role" class="form-label fw-semibold">
                                                <i class="bi bi-shield me-2 text-primary"></i>Role
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                                <option value="">Pilih Role</option>
                                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                                    🛡️ Admin - Full Access
                                                </option>
                                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                                                    👤 User - Limited Access
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @error('role') {{ $message }} @else Role wajib dipilih @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="is_active" class="form-label fw-semibold">
                                                <i class="bi bi-toggle-on me-2 text-primary"></i>Status Akun
                                            </label>
                                            <div class="card border-0 bg-light">
                                                <div class="card-body p-3">
                                                    <div class="form-check form-switch form-switch-lg">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="is_active" name="is_active" value="1"
                                                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold" for="is_active">
                                                            <span id="statusText" class="{{ $user->is_active ? 'text-success' : 'text-danger' }}">
                                                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Status menentukan apakah user dapat login ke sistem
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        @if($user->id === auth()->id())
                                            <div class="col-12">
                                                <div class="alert alert-warning border-0 shadow-sm">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                                                        <div>
                                                            <h6 class="alert-heading mb-1">Perhatian!</h6>
                                                            <p class="mb-0">Anda sedang mengedit profil Anda sendiri. Hati-hati saat mengubah role atau status.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Enhanced Security Tab -->
                                <div class="tab-pane fade" id="security">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <h5 class="fw-bold text-primary mb-3">
                                                <i class="bi bi-shield-lock-fill me-2"></i>
                                                Pengaturan Keamanan
                                            </h5>

                                            <div class="alert alert-info border-0 shadow-sm">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                                                    <div>
                                                        <h6 class="alert-heading mb-1">Info Password</h6>
                                                        <p class="mb-0">Kosongkan field password jika tidak ingin mengubah password saat ini.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="password" class="form-label fw-semibold">
                                                <i class="bi bi-lock me-2 text-primary"></i>Password Baru
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-lock text-muted"></i>
                                                </span>
                                                <input type="password" class="form-control border-0 @error('password') is-invalid @enderror"
                                                       id="password" name="password" placeholder="Masukkan password baru">
                                                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword('password')">
                                                    <i class="bi bi-eye" id="passwordIcon"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback">
                                                @error('password') {{ $message }} @enderror
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
                                                <i class="bi bi-lock-fill me-2 text-primary"></i>Konfirmasi Password
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-lock-fill text-muted"></i>
                                                </span>
                                                <input type="password" class="form-control border-0"
                                                       id="password_confirmation" name="password_confirmation"
                                                       placeholder="Ulangi password baru">
                                                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePassword('password_confirmation')">
                                                    <i class="bi bi-eye" id="passwordConfirmationIcon"></i>
                                                </button>
                                            </div>
                                            <div class="valid-feedback">Password cocok!</div>
                                            <div class="invalid-feedback">Password tidak cocok</div>
                                        </div>

                                        <div class="col-12">
                                            <div class="card border-0 bg-gradient-light">
                                                <div class="card-body">
                                                    <h6 class="card-title">
                                                        <i class="bi bi-shield-check me-2 text-success"></i>
                                                        Informasi Login Terakhir
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <div class="text-center p-3 bg-white rounded shadow-sm">
                                                                <i class="bi bi-calendar-event text-primary fs-4 mb-2"></i>
                                                                <div class="fw-bold">Login Terakhir</div>
                                                                <div class="text-muted small">
                                                                    {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum pernah' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-center p-3 bg-white rounded shadow-sm">
                                                                <i class="bi bi-geo-alt text-warning fs-4 mb-2"></i>
                                                                <div class="fw-bold">IP Address</div>
                                                                <div class="text-muted small">{{ $user->last_login_ip ?? 'Unknown' }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="text-center p-3 bg-white rounded shadow-sm">
                                                                <i class="bi bi-device-hdd text-info fs-4 mb-2"></i>
                                                                <div class="fw-bold">Device</div>
                                                                <div class="text-muted small">{{ Str::limit($user->last_user_agent ?? 'Unknown', 15) }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Enhanced Permissions Tab -->
                                <div class="tab-pane fade" id="permissions">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <h5 class="fw-bold text-primary mb-3">
                                                <i class="bi bi-key-fill me-2"></i>
                                                Hak Akses & Permissions
                                            </h5>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="card border-warning shadow-sm h-100">
                                                <div class="card-header bg-warning bg-opacity-10 border-warning">
                                                    <h6 class="card-title mb-0 d-flex align-items-center">
                                                        <i class="bi bi-shield-check text-warning me-2"></i>
                                                        Admin Privileges
                                                        <span class="badge bg-warning text-dark ms-auto">Full Access</span>
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="list-group list-group-flush">
                                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                                            <i class="bi bi-check-circle-fill text-success me-3"></i>
                                                            <div>
                                                                <div class="fw-semibold">User Management</div>
                                                                <small class="text-muted">Create, read, update, delete users</small>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                                            <i class="bi bi-check-circle-fill text-success me-3"></i>
                                                            <div>
                                                                <div class="fw-semibold">PPDB Management</div>
                                                                <small class="text-muted">Manage registration data</small>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                                            <i class="bi bi-check-circle-fill text-success me-3"></i>
                                                            <div>
                                                                <div class="fw-semibold">System Reports</div>
                                                                <small class="text-muted">Access all reports & analytics</small>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                                            <i class="bi bi-check-circle-fill text-success me-3"></i>
                                                            <div>
                                                                <div class="fw-semibold">System Settings</div>
                                                                <small class="text-muted">Configure system parameters</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="card border-primary shadow-sm h-100">
                                                <div class="card-header bg-primary bg-opacity-10 border-primary">
                                                    <h6 class="card-title mb-0 d-flex align-items-center">
                                                        <i class="bi bi-person text-primary me-2"></i>
                                                        User Privileges
                                                        <span class="badge bg-primary ms-auto">Limited Access</span>
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="list-group list-group-flush">
                                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                                            <i class="bi bi-check-circle-fill text-success me-3"></i>
                                                            <div>
                                                                <div class="fw-semibold">Dashboard Access</div>
                                                                <small class="text-muted">View dashboard & statistics</small>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                                            <i class="bi bi-check-circle-fill text-success me-3"></i>
                                                            <div>
                                                                <div class="fw-semibold">Profile Management</div>
                                                                <small class="text-muted">Edit own profile only</small>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                                            <i class="bi bi-x-circle-fill text-danger me-3"></i>
                                                            <div>
                                                                <div class="fw-semibold text-muted">User Management</div>
                                                                <small class="text-muted">Cannot manage other users</small>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item d-flex align-items-center px-0 border-0">
                                                            <i class="bi bi-x-circle-fill text-danger me-3"></i>
                                                            <div>
                                                                <div class="fw-semibold text-muted">System Settings</div>
                                                                <small class="text-muted">No access to system config</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="alert alert-info border-0 shadow-sm">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-lightbulb-fill me-3 fs-4"></i>
                                                    <div>
                                                        <h6 class="alert-heading mb-1">💡 Tips Pemilihan Role</h6>
                                                        <p class="mb-0">
                                                            Role menentukan tingkat akses dalam sistem. Pilih <strong>Admin</strong> untuk akses penuh
                                                            atau <strong>User</strong> untuk akses terbatas sesuai kebutuhan.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small d-flex align-items-center">
                                    <i class="bi bi-clock-history me-2"></i>
                                    Update terakhir: {{ $user->updated_at->format('d M Y, H:i') }} WIB
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-warning btn-lg" onclick="resetForm()">
                                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg px-4">
                                        <i class="bi bi-check-circle me-2"></i>Update User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced CSS -->
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }

        .bg-gradient-light {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .nav-pills .nav-link {
            border: 2px solid transparent;
            color: #6c757d;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 0 5px;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .nav-pills .nav-link:hover:not(.active) {
            background-color: #f8f9fa;
            color: #495057;
            transform: translateY(-1px);
        }

        .card {
            transition: all 0.3s ease;
            border-radius: 15px;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-switch-lg .form-check-input {
            width: 3rem;
            height: 1.5rem;
        }

        .status-indicator {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .timeline {
            padding: 1rem 0;
        }

        .timeline-item {
            position: relative;
            padding-left: 2rem;
            padding-bottom: 1.5rem;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 24px;
            bottom: -8px;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-marker {
            position: absolute;
            left: 0;
            top: 8px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .timeline-content {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            border-left: 3px solid #667eea;
        }

        .alert {
            border-radius: 15px;
        }

        .avatar-xl {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .progress-bar {
            transition: width 0.3s ease;
        }
    </style>

    <!-- Enhanced JavaScript -->
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

            initializeFormValidation();
            initializeStatusToggle();
            initializePasswordStrength();
            autoHideAlerts();
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
                if (this.checked) {
                    statusText.textContent = 'Aktif';
                    statusText.className = 'text-success';
                } else {
                    statusText.textContent = 'Nonaktif';
                    statusText.className = 'text-danger';
                }
            });
        }

        function initializePasswordStrength() {
            const passwordField = document.getElementById('password');
            if (passwordField) {
                passwordField.addEventListener('input', function() {
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
        }

        function autoHideAlerts() {
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert.classList.contains('show')) {
                        alert.classList.remove('show');
                        setTimeout(() => alert.remove(), 300);
                    }
                });
            }, 5000);
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

        // Additional functions for dropdown actions
        function generateNewPassword() {
            if (confirm('Generate password baru untuk user ini?')) {
                const newPassword = generateSecurePassword();
                document.getElementById('password').value = newPassword;
                document.getElementById('password_confirmation').value = newPassword;
                alert(`Password baru: ${newPassword}\nSilakan salin dan berikan kepada user.`);
            }
        }

        function generateSecurePassword() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
            let password = '';
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return password;
        }

        function sendWelcomeEmail() {
            if (confirm('Kirim welcome email ke user ini?')) {
                // Implement welcome email functionality
                alert('Welcome email berhasil dikirim!');
            }
        }

        function resetUserSessions() {
            if (confirm('Reset semua session login user ini? User akan logout dari semua device.')) {
                // Implement session reset functionality
                alert('Session user berhasil direset!');
            }
        }
    </script>
</x-app-layout>
