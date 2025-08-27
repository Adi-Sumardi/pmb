<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-people-fill me-2 text-primary"></i>
                    User Management
                </h2>
                <p class="text-muted small mb-0">Kelola pengguna sistem PPDB</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary" onclick="refreshData()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                </button>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-1"></i>Tambah User
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                                    <i class="bi bi-people text-primary fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small">Total Users</div>
                                <div class="fs-4 fw-bold text-primary counter" data-target="{{ $users->total() }}">0
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                                    <i class="bi bi-person-check text-success fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small">Active Users</div>
                                <div class="fs-4 fw-bold text-success counter"
                                    data-target="{{ $users->where('is_active', true)->count() }}">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                                    <i class="bi bi-shield-check text-warning fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small">Admins</div>
                                <div class="fs-4 fw-bold text-warning counter"
                                    data-target="{{ $users->where('role', 'admin')->count() }}">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                                    <i class="bi bi-clock text-info fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="text-muted small">This Month</div>
                                <div class="fs-4 fw-bold text-info counter"
                                    data-target="{{ $users->where('created_at', '>=', now()->startOfMonth())->count() }}">0
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="card-title mb-0 fw-bold">
                                    <i class="bi bi-table me-2 text-primary"></i>
                                    Daftar Pengguna
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end gap-2">
                                    <!-- Search -->
                                    <div class="input-group" style="width: 250px;">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0" id="searchInput"
                                            placeholder="Cari user..." onkeyup="searchUsers()">
                                    </div>

                                    <!-- Filter -->
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-funnel me-1"></i>Filter
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="filterUsers('all')">
                                                <i class="bi bi-people me-2"></i>Semua User
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="filterUsers('admin')">
                                                <i class="bi bi-shield-check me-2"></i>Admin
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="filterUsers('user')">
                                                <i class="bi bi-person me-2"></i>User
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="#" onclick="filterUsers('active')">
                                                <i class="bi bi-check-circle me-2 text-success"></i>Aktif
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="filterUsers('inactive')">
                                                <i class="bi bi-x-circle me-2 text-danger"></i>Nonaktif
                                            </a></li>
                                        </ul>
                                    </div>

                                    <!-- Export -->
                                    <div class="dropdown">
                                        <button class="btn btn-outline-success dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="bi bi-download me-1"></i>Export
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="exportData('excel')">
                                                <i class="bi bi-file-earmark-excel me-2"></i>Excel
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="exportData('pdf')">
                                                <i class="bi bi-file-earmark-pdf me-2"></i>PDF
                                            </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <!-- Alert Messages -->
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3 mb-0" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-3 mb-0" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <!-- Loading Overlay -->
                        <div id="loadingOverlay" class="d-none position-relative">
                            <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                                <div class="spinner-border text-primary me-3" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span class="text-muted">Memuat data...</span>
                            </div>
                        </div>

                        <!-- Users Table -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="usersTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 px-3 py-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll"
                                                    onchange="toggleSelectAll()">
                                            </div>
                                        </th>
                                        <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">
                                            <i class="bi bi-person me-1"></i>User
                                        </th>
                                        <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">
                                            <i class="bi bi-envelope me-1"></i>Email
                                        </th>
                                        <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">
                                            <i class="bi bi-shield me-1"></i>Role
                                        </th>
                                        <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">
                                            <i class="bi bi-activity me-1"></i>Status
                                        </th>
                                        <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">
                                            <i class="bi bi-calendar me-1"></i>Bergabung
                                        </th>
                                        <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase text-center">
                                            <i class="bi bi-gear me-1"></i>Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $index => $user)
                                    <tr class="user-row" data-aos="fade-up" data-aos-delay="{{ 600 + ($index * 50) }}"
                                        data-role="{{ $user->role }}" data-status="{{ $user->is_active ? 'active' : 'inactive' }}">
                                        <td class="px-3">
                                            <div class="form-check">
                                                <input class="form-check-input user-checkbox" type="checkbox" value="{{ $user->id }}">
                                            </div>
                                        </td>
                                        <td class="px-3">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="rounded-circle bg-gradient-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                                        style="width: 40px; height: 40px;">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">{{ $user->name }}</h6>
                                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-envelope me-2 text-muted"></i>
                                                <span>{{ $user->email }}</span>
                                            </div>
                                        </td>
                                        <td class="px-3">
                                            @if($user->role === 'admin')
                                            <span class="badge bg-warning bg-gradient rounded-pill">
                                                <i class="bi bi-shield-check me-1"></i>Admin
                                            </span>
                                            @else
                                            <span class="badge bg-info bg-gradient rounded-pill">
                                                <i class="bi bi-person me-1"></i>User
                                            </span>
                                            @endif
                                        </td>
                                        <td class="px-3">
                                            @if($user->is_active)
                                            <span class="badge bg-success bg-gradient rounded-pill">
                                                <i class="bi bi-check-circle me-1"></i>Aktif
                                            </span>
                                            @else
                                            <span class="badge bg-danger bg-gradient rounded-pill">
                                                <i class="bi bi-x-circle me-1"></i>Nonaktif
                                            </span>
                                            @endif
                                        </td>
                                        <td class="px-3">
                                            <div class="text-muted small">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ $user->created_at->format('d M Y') }}
                                            </div>
                                            <div class="text-muted" style="font-size: 11px;">
                                                {{ $user->created_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-3 text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.users.edit', $user) }}"
                                                    class="btn btn-outline-warning btn-sm"
                                                    data-bs-toggle="tooltip" title="Edit User">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @if($user->id !== auth()->id())
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm"
                                                    data-bs-toggle="tooltip" title="Hapus User"
                                                    onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox display-4 mb-3"></i>
                                                <h6>Tidak ada data user</h6>
                                                <p class="small">Belum ada user yang terdaftar dalam sistem.</p>
                                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                                    <i class="bi bi-person-plus me-1"></i>Tambah User Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($users->hasPages())
                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }}
                                    dari {{ $users->total() }} hasil
                                </div>
                                {{ $users->links() }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Bulk Actions -->
        <div id="bulkActions" class="position-fixed bottom-0 start-50 translate-middle-x mb-4 d-none"
            style="z-index: 1050;">
            <div class="card shadow-lg border-0" style="border-radius: 1rem;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted small fw-semibold">
                            <i class="bi bi-check-circle-fill text-primary me-1"></i>
                            <span id="selectedCount">0</span> item dipilih
                        </span>
                        <div class="vr"></div>
                        <button class="btn btn-outline-success btn-sm" onclick="bulkAction('activate')"
                            data-bs-toggle="tooltip" title="Aktifkan user yang dipilih">
                            <i class="bi bi-play-circle me-1"></i>Aktifkan
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="bulkAction('deactivate')"
                            data-bs-toggle="tooltip" title="Nonaktifkan user yang dipilih">
                            <i class="bi bi-pause-circle me-1"></i>Nonaktifkan
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="bulkAction('delete')"
                            data-bs-toggle="tooltip" title="Hapus user yang dipilih">
                            <i class="bi bi-trash me-1"></i>Hapus
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="clearSelection()"
                            data-bs-toggle="tooltip" title="Batalkan pilihan">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Bulk Action Confirmation Modal -->
        <div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0" id="modalHeader">
                        <h5 class="modal-title fw-bold" id="bulkActionModalLabel">
                            <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Aksi
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                            <div class="rounded-circle d-inline-flex p-3 mb-3" id="modalIcon">
                                <i class="fs-2" id="modalIconClass"></i>
                            </div>
                            <h6 id="modalMessage">Apakah Anda yakin?</h6>
                            <p class="text-muted small" id="modalDescription"></p>
                        </div>
                        <div class="alert alert-warning border-warning" id="warningAlert">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Perhatian:</strong> <span id="warningText">Aksi ini tidak dapat dibatalkan!</span>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </button>
                        <button type="button" class="btn" id="confirmActionBtn">
                            <i class="bi bi-check-circle me-1"></i>Ya, Lanjutkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Toast Container - POSISI DAN STYLING DIPERBAIKI -->
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
            <!-- Success Toast -->
            <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <span id="successMessage">Operasi berhasil!</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>

            <!-- Error Toast -->
            <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                        <span id="errorMessage">Terjadi kesalahan!</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>

            <!-- Warning Toast -->
            <div id="warningToast" class="toast align-items-center text-dark bg-warning border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <span id="warningMessage">Peringatan!</span>
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>

            <!-- Info Toast -->
            <div id="infoToast" class="toast align-items-center text-white bg-info border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        <span id="infoMessage">Informasi!</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                            Konfirmasi Penghapusan
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus user <strong id="userName"></strong>?</p>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <small>Tindakan ini tidak dapat dibatalkan!</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form id="deleteForm" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i>Hapus
                            </button>
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

            .card {
                transition: all 0.3s ease;
            }

            .card:hover {
                transform: translateY(-2px);
            }

            .user-row {
                transition: all 0.3s ease;
            }

            .user-row:hover {
                background-color: #f8f9fa !important;
                transform: scale(1.01);
            }

            .btn-group .btn {
                transition: all 0.3s ease;
            }

            .btn-group .btn:hover {
                transform: scale(1.1);
            }

            .counter {
                font-family: 'Inter', sans-serif;
            }

            #loadingOverlay {
                background: rgba(255, 255, 255, 0.9);
                border-radius: 0.5rem;
                margin: 1rem;
            }

            .badge {
                font-size: 0.75em;
            }

            .table th {
                font-weight: 600;
            }

            .rounded-circle {
                border: 2px solid #fff;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            /* Enhanced Toast Styling */
            .toast {
                border-radius: 0.75rem;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                margin-bottom: 0.5rem;
                min-width: 300px;
            }

            .toast-body {
                font-weight: 500;
                padding: 1rem;
            }

            /* Animation for toast */
            .toast.show {
                animation: slideInRight 0.5s ease-out;
            }

            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }

                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            /* Bulk actions animation */
            #bulkActions {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                transform: translateY(100%);
                opacity: 0;
            }

            #bulkActions.show {
                transform: translateY(0);
                opacity: 1;
            }

            /* Loading overlay animation */
            #loadingOverlay {
                transition: opacity 0.3s ease;
            }
        </style>

        <!-- JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

        <script>
            // Global variables
            let currentBulkAction = null;
            let selectedUserIds = [];

            document.addEventListener('DOMContentLoaded', function () {
                // Initialize AOS
                AOS.init({
                    duration: 600,
                    easing: 'ease-in-out',
                    once: true
                });

                // Initialize tooltips
                initializeTooltips();

                // Initialize counter animations
                initializeCounterAnimations();

                // Auto-hide session alerts
                autoHideAlerts();

                // Listen for checkbox changes
                document.addEventListener('change', function (e) {
                    if (e.target.classList.contains('user-checkbox')) {
                        updateBulkActions();
                    }
                });
            });

            // Initialize tooltips
            function initializeTooltips() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Initialize counter animations
            function initializeCounterAnimations() {
                const counters = document.querySelectorAll('.counter');
                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animateCounter(entry.target);
                            observer.unobserve(entry.target);
                        }
                    });
                });

                counters.forEach(counter => {
                    observer.observe(counter);
                });
            }

            // Counter animation
            function animateCounter(element) {
                const target = parseInt(element.getAttribute('data-target'));
                const duration = 1500;
                const step = target / (duration / 16);
                let current = 0;

                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    element.textContent = Math.floor(current);
                }, 16);
            }

            // Auto-hide alerts
            function autoHideAlerts() {
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.alert-dismissible');
                    alerts.forEach(alert => {
                        if (alert.classList.contains('show')) {
                            alert.classList.remove('show');
                            setTimeout(() => alert.remove(), 300);
                        }
                    });
                }, 5000);
            }

            // ENHANCED TOAST SYSTEM
            function showToast(type, message, duration = 4000) {
                const toastId = type + 'Toast';
                const messageId = type + 'Message';
                const toastElement = document.getElementById(toastId);
                const messageElement = document.getElementById(messageId);

                if (!toastElement || !messageElement) {
                    console.error('Toast element not found:', toastId);
                    return;
                }

                // Set message
                messageElement.textContent = message;

                // Create and show toast
                const toast = new bootstrap.Toast(toastElement, {
                    delay: duration,
                    autohide: true
                });

                toast.show();

                // Add custom animation
                toastElement.addEventListener('shown.bs.toast', function () {
                    this.classList.add('show');
                });

                return toast;
            }

            // Shorthand functions for different toast types
            function showSuccessToast(message, duration = 4000) {
                return showToast('success', message, duration);
            }

            function showErrorToast(message, duration = 6000) {
                return showToast('error', message, duration);
            }

            function showWarningToast(message, duration = 5000) {
                return showToast('warning', message, duration);
            }

            function showInfoToast(message, duration = 4000) {
                return showToast('info', message, duration);
            }

            // Search functionality
            function searchUsers() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const rows = document.querySelectorAll('.user-row');

                if (searchTerm === '') {
                    rows.forEach(row => {
                        row.style.display = '';
                        row.classList.add('fade-in');
                    });
                    return;
                }

                showLoading();

                setTimeout(() => {
                    let foundCount = 0;
                    rows.forEach(row => {
                        const name = row.querySelector('h6').textContent.toLowerCase();
                        const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                        if (name.includes(searchTerm) || email.includes(searchTerm)) {
                            row.style.display = '';
                            row.classList.add('fade-in');
                            foundCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    hideLoading();

                    if (foundCount === 0) {
                        showWarningToast(`Tidak ditemukan user dengan kata kunci "${searchTerm}"`);
                    } else {
                        showInfoToast(`Ditemukan ${foundCount} user`);
                    }
                }, 300);
            }

            // Filter functionality
            function filterUsers(filter) {
                const rows = document.querySelectorAll('.user-row');
                showLoading();

                setTimeout(() => {
                    let visibleCount = 0;
                    rows.forEach(row => {
                        let show = true;

                        switch (filter) {
                            case 'admin':
                                show = row.dataset.role === 'admin';
                                break;
                            case 'user':
                                show = row.dataset.role === 'user';
                                break;
                            case 'active':
                                show = row.dataset.status === 'active';
                                break;
                            case 'inactive':
                                show = row.dataset.status === 'inactive';
                                break;
                            case 'all':
                            default:
                                show = true;
                        }

                        if (show) {
                            row.style.display = '';
                            row.classList.add('fade-in');
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    hideLoading();

                    // Show filter result
                    const filterNames = {
                        'all': 'Semua User',
                        'admin': 'Admin',
                        'user': 'User Biasa',
                        'active': 'User Aktif',
                        'inactive': 'User Nonaktif'
                    };

                    showInfoToast(`Filter "${filterNames[filter]}" diterapkan. Menampilkan ${visibleCount} user.`);
                }, 300);
            }

            // Enhanced select all functionality
            function toggleSelectAll() {
                const selectAll = document.getElementById('selectAll');
                const checkboxes = document.querySelectorAll('.user-checkbox');
                const visibleCheckboxes = Array.from(checkboxes).filter(cb =>
                    cb.closest('tr').style.display !== 'none'
                );

                visibleCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });

                updateBulkActions();

                if (selectAll.checked) {
                    showInfoToast(`${visibleCheckboxes.length} user dipilih`);
                }
            }

            // Enhanced update bulk actions function
            function updateBulkActions() {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                const bulkActions = document.getElementById('bulkActions');
                const selectedCount = document.getElementById('selectedCount');

                if (checkedBoxes.length > 0) {
                    bulkActions.classList.remove('d-none');
                    bulkActions.classList.add('show');
                    selectedCount.textContent = checkedBoxes.length;
                } else {
                    clearSelection();
                }
            }

            // Enhanced clear selection
            function clearSelection() {
                const checkboxes = document.querySelectorAll('.user-checkbox, #selectAll');
                checkboxes.forEach(checkbox => checkbox.checked = false);

                const bulkActions = document.getElementById('bulkActions');
                bulkActions.classList.remove('show');
                setTimeout(() => {
                    bulkActions.classList.add('d-none');
                }, 300);
            }

            // Enhanced bulk action function
            function bulkAction(action) {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                selectedUserIds = Array.from(checkedBoxes).map(cb => cb.value);

                if (selectedUserIds.length === 0) {
                    showWarningToast('Pilih minimal satu user untuk melakukan aksi ini.');
                    return;
                }

                currentBulkAction = action;
                showBulkActionModal(action, selectedUserIds.length);
            }

            // Show bulk action confirmation modal
            function showBulkActionModal(action, count) {
                const modal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
                const modalHeader = document.getElementById('modalHeader');
                const modalIcon = document.getElementById('modalIcon');
                const modalIconClass = document.getElementById('modalIconClass');
                const modalMessage = document.getElementById('modalMessage');
                const modalDescription = document.getElementById('modalDescription');
                const warningText = document.getElementById('warningText');
                const confirmBtn = document.getElementById('confirmActionBtn');

                // Configure modal based on action
                switch (action) {
                    case 'activate':
                        modalHeader.className = 'modal-header border-0 bg-success bg-opacity-10';
                        modalIcon.className = 'rounded-circle d-inline-flex p-3 mb-3 bg-success bg-opacity-10';
                        modalIconClass.className = 'bi bi-play-circle text-success fs-2';
                        modalMessage.textContent = `Aktifkan ${count} User?`;
                        modalDescription.textContent = `${count} user yang dipilih akan diaktifkan dan dapat mengakses sistem.`;
                        warningText.textContent = 'User yang diaktifkan akan dapat login ke sistem.';
                        confirmBtn.className = 'btn btn-success';
                        confirmBtn.innerHTML = '<i class="bi bi-play-circle me-1"></i>Ya, Aktifkan';
                        break;

                    case 'deactivate':
                        modalHeader.className = 'modal-header border-0 bg-warning bg-opacity-10';
                        modalIcon.className = 'rounded-circle d-inline-flex p-3 mb-3 bg-warning bg-opacity-10';
                        modalIconClass.className = 'bi bi-pause-circle text-warning fs-2';
                        modalMessage.textContent = `Nonaktifkan ${count} User?`;
                        modalDescription.textContent = `${count} user yang dipilih akan dinonaktifkan dan tidak dapat mengakses sistem.`;
                        warningText.textContent = 'User yang dinonaktifkan akan logout otomatis dari sistem.';
                        confirmBtn.className = 'btn btn-warning';
                        confirmBtn.innerHTML = '<i class="bi bi-pause-circle me-1"></i>Ya, Nonaktifkan';
                        break;

                    case 'delete':
                        modalHeader.className = 'modal-header border-0 bg-danger bg-opacity-10';
                        modalIcon.className = 'rounded-circle d-inline-flex p-3 mb-3 bg-danger bg-opacity-10';
                        modalIconClass.className = 'bi bi-trash text-danger fs-2';
                        modalMessage.textContent = `Hapus ${count} User?`;
                        modalDescription.textContent = `${count} user yang dipilih akan dihapus secara permanen dari sistem.`;
                        warningText.textContent = 'Aksi ini tidak dapat dibatalkan! Semua data user akan hilang.';
                        confirmBtn.className = 'btn btn-danger';
                        confirmBtn.innerHTML = '<i class="bi bi-trash me-1"></i>Ya, Hapus';
                        break;
                }

                modal.show();
            }

            // Execute bulk action with enhanced error handling
            document.getElementById('confirmActionBtn').addEventListener('click', function () {
                if (!currentBulkAction || selectedUserIds.length === 0) return;

                const button = this;
                const originalContent = button.innerHTML;

                // Show loading state
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                button.disabled = true;

                // Prepare data
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('action', currentBulkAction);
                selectedUserIds.forEach(id => {
                    formData.append('user_ids[]', id);
                });

                // Send AJAX request
                fetch('{{ route("admin.users.bulk-action") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Hide modal
                    bootstrap.Modal.getInstance(document.getElementById('bulkActionModal')).hide();

                    if (data.success) {
                        showSuccessToast(data.message);

                        // Update UI based on action
                        updateUIAfterBulkAction(data.action, selectedUserIds);

                        // Clear selection
                        clearSelection();

                        // Update statistics
                        setTimeout(() => {
                            updateStatistics();
                        }, 500);
                    } else {
                        showErrorToast(data.message || 'Terjadi kesalahan saat memproses permintaan.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    bootstrap.Modal.getInstance(document.getElementById('bulkActionModal')).hide();
                    showErrorToast('Terjadi kesalahan jaringan. Silakan coba lagi.');
                })
                .finally(() => {
                    // Reset button
                    button.innerHTML = originalContent;
                    button.disabled = false;
                });
            });

            // Update UI after bulk action
            function updateUIAfterBulkAction(action, userIds) {
                userIds.forEach(userId => {
                    const row = document.querySelector(`input[value="${userId}"]`)?.closest('tr');

                    if (!row) return;

                    if (action === 'delete') {
                        // Remove row with animation
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(-100%)';
                        setTimeout(() => row.remove(), 300);
                    } else {
                        // Update status badge
                        const statusBadge = row.querySelector('td:nth-child(5) .badge');
                        if (statusBadge) {
                            if (action === 'activate') {
                                statusBadge.className = 'badge bg-success bg-gradient rounded-pill';
                                statusBadge.innerHTML = '<i class="bi bi-check-circle me-1"></i>Aktif';
                                row.dataset.status = 'active';
                            } else if (action === 'deactivate') {
                                statusBadge.className = 'badge bg-danger bg-gradient rounded-pill';
                                statusBadge.innerHTML = '<i class="bi bi-x-circle me-1"></i>Nonaktif';
                                row.dataset.status = 'inactive';
                            }
                        }
                    }
                });
            }

            // Update statistics after actions
            function updateStatistics() {
                const totalUsers = document.querySelectorAll('.user-row').length;
                const activeUsers = document.querySelectorAll('.user-row[data-status="active"]').length;

                // Update counter animations
                const counters = document.querySelectorAll('.counter');
                counters.forEach((counter, index) => {
                    let newTarget;
                    switch (index) {
                        case 0: // Total users
                            newTarget = totalUsers;
                            break;
                        case 1: // Active users
                            newTarget = activeUsers;
                            break;
                        default:
                            return;
                    }
                    counter.setAttribute('data-target', newTarget);
                    animateCounter(counter);
                });
            }

            // Delete confirmation
            function confirmDelete(userId, userName) {
                document.getElementById('userName').textContent = userName;
                document.getElementById('deleteForm').action = `/admin/users/${userId}`;
                new bootstrap.Modal(document.getElementById('deleteModal')).show();
            }

            // Export functionality
            function exportData(format) {
                showLoading();
                showInfoToast(`Memproses export ${format.toUpperCase()}...`);

                // Simulate export process
                setTimeout(() => {
                    hideLoading();
                    showSuccessToast(`Export ${format.toUpperCase()} berhasil! File akan segera didownload.`);
                }, 2000);
            }

            // Refresh data
            function refreshData() {
                showLoading();
                showInfoToast('Memuat ulang data...');

                setTimeout(() => {
                    location.reload();
                }, 1500);
            }

            // Loading functions
            function showLoading() {
                document.getElementById('loadingOverlay').classList.remove('d-none');
            }

            function hideLoading() {
                document.getElementById('loadingOverlay').classList.add('d-none');
            }

            // Prevent accidental page refresh during bulk operations
            window.addEventListener('beforeunload', function (e) {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                if (checkedBoxes.length > 0) {
                    e.preventDefault();
                    e.returnValue = 'Ada user yang dipilih. Yakin ingin meninggalkan halaman?';
                }
            });
        </script>
    </div>
</x-app-layout>
