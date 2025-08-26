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
                                <div class="fs-4 fw-bold text-primary counter" data-target="{{ $users->total() }}">0</div>
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
                                <div class="fs-4 fw-bold text-success counter" data-target="{{ $users->where('is_active', true)->count() }}">0</div>
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
                                <div class="fs-4 fw-bold text-warning counter" data-target="{{ $users->where('role', 'admin')->count() }}">0</div>
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
                                <div class="fs-4 fw-bold text-info counter" data-target="{{ $users->where('created_at', '>=', now()->startOfMonth())->count() }}">0</div>
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
                        <div id="loadingOverlay" class="d-none">
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
                                                <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll()">
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

        <!-- Bulk Actions -->
        <div id="bulkActions" class="position-fixed bottom-0 start-50 translate-middle-x mb-4 d-none">
            <div class="card shadow-lg border-0">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted small">
                            <span id="selectedCount">0</span> item dipilih
                        </span>
                        <div class="vr"></div>
                        <button class="btn btn-outline-warning btn-sm" onclick="bulkAction('deactivate')">
                            <i class="bi bi-pause-circle me-1"></i>Nonaktifkan
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="bulkAction('activate')">
                            <i class="bi bi-play-circle me-1"></i>Aktifkan
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="bulkAction('delete')">
                            <i class="bi bi-trash me-1"></i>Hapus
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </button>
                    </div>
                </div>
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
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 1000;
        }

        .badge {
            font-size: 0.75em;
        }

        .table th {
            font-weight: 600;
        }

        .rounded-circle {
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

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

            // Trigger counter animation when in viewport
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

        // Search functionality
        function searchUsers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.user-row');

            showLoading();

            setTimeout(() => {
                rows.forEach(row => {
                    const name = row.querySelector('h6').textContent.toLowerCase();
                    const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                    if (name.includes(searchTerm) || email.includes(searchTerm)) {
                        row.style.display = '';
                        row.classList.add('fade-in');
                    } else {
                        row.style.display = 'none';
                    }
                });
                hideLoading();
            }, 500);
        }

        // Filter functionality
        function filterUsers(filter) {
            const rows = document.querySelectorAll('.user-row');

            showLoading();

            setTimeout(() => {
                rows.forEach(row => {
                    let show = true;

                    switch(filter) {
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
                    } else {
                        row.style.display = 'none';
                    }
                });
                hideLoading();
            }, 500);
        }

        // Select all functionality
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.user-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });

            updateBulkActions();
        }

        // Update bulk actions visibility
        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');

            if (checkedBoxes.length > 0) {
                bulkActions.classList.remove('d-none');
                selectedCount.textContent = checkedBoxes.length;
            } else {
                bulkActions.classList.add('d-none');
            }
        }

        // Clear selection
        function clearSelection() {
            const checkboxes = document.querySelectorAll('.user-checkbox, #selectAll');
            checkboxes.forEach(checkbox => checkbox.checked = false);
            updateBulkActions();
        }

        // Bulk actions
        function bulkAction(action) {
            const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            if (ids.length === 0) {
                alert('Pilih minimal satu user untuk melakukan aksi ini.');
                return;
            }

            let message = '';
            switch(action) {
                case 'delete':
                    message = `Apakah Anda yakin ingin menghapus ${ids.length} user yang dipilih?`;
                    break;
                case 'activate':
                    message = `Apakah Anda yakin ingin mengaktifkan ${ids.length} user yang dipilih?`;
                    break;
                case 'deactivate':
                    message = `Apakah Anda yakin ingin menonaktifkan ${ids.length} user yang dipilih?`;
                    break;
            }

            if (confirm(message)) {
                // Implement bulk action logic here
                console.log(`Bulk ${action} for IDs:`, ids);
                // You can make AJAX request here
            }
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

            // Simulate export process
            setTimeout(() => {
                alert(`Exporting data to ${format.toUpperCase()}...`);
                hideLoading();
            }, 1000);
        }

        // Refresh data
        function refreshData() {
            showLoading();

            setTimeout(() => {
                location.reload();
            }, 1000);
        }

        // Loading functions
        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('d-none');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('d-none');
        }

        // Listen for checkbox changes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('user-checkbox')) {
                updateBulkActions();
            }
        });

        // Add fade-in animation class
        const style = document.createElement('style');
        style.textContent = `
            .fade-in {
                animation: fadeIn 0.3s ease-in;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    </script>
</x-app-layout>
