<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-people-fill me-2 text-primary"></i>
                    Data Siswa
                </h2>
                <p class="text-muted small mb-0">Kelola data siswa yang telah diterima tahun ajaran {{ $academicYear ?? '2026/2027' }}</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary" onclick="refreshData()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                </button>
                <button class="btn btn-success" id="exportBtn">
                    <i class="bi bi-file-excel me-1"></i>Export Excel
                </button>
                <button class="btn btn-info" id="printBtn">
                    <i class="bi bi-printer me-1"></i>Print
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Alert Messages -->
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="alertSuccess">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Sukses!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alertError">
                <i class="bi bi-exclamation-circle me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 mx-auto" style="width: 60px; height: 60px;">
                                <i class="bi bi-people text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="text-muted small">Total Siswa</div>
                        <div class="fs-4 fw-bold text-primary counter" id="totalCount" data-target="{{ $totalStudents }}">{{ $totalStudents }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 mx-auto" style="width: 60px; height: 60px;">
                                <i class="bi bi-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="text-muted small">Aktif</div>
                        <div class="fs-4 fw-bold text-success counter" id="activeCount" data-target="{{ $activeStudents }}">{{ $activeStudents }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3 mx-auto" style="width: 60px; height: 60px;">
                                <i class="bi bi-pause-circle text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="text-muted small">Tidak Aktif</div>
                        <div class="fs-4 fw-bold text-warning counter" id="inactiveCount" data-target="{{ $inactiveStudents }}">{{ $inactiveStudents }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3 mx-auto" style="width: 60px; height: 60px;">
                                <i class="bi bi-mortarboard text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="text-muted small">Lulus</div>
                        <div class="fs-4 fw-bold text-info counter" id="graduatedCount" data-target="{{ $graduatedStudents }}">{{ $graduatedStudents }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <div class="rounded-circle bg-danger bg-opacity-10 p-3 mx-auto" style="width: 60px; height: 60px;">
                                <i class="bi bi-x-circle text-danger fs-4"></i>
                            </div>
                        </div>
                        <div class="text-muted small">Keluar</div>
                        <div class="fs-4 fw-bold text-danger counter" id="droppedCount" data-target="{{ $droppedOutStudents }}">{{ $droppedOutStudents }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="600">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <div class="rounded-circle bg-secondary bg-opacity-10 p-3 mx-auto" style="width: 60px; height: 60px;">
                                <i class="bi bi-arrow-right-circle text-secondary fs-4"></i>
                            </div>
                        </div>
                        <div class="text-muted small">Pindah</div>
                        <div class="fs-4 fw-bold text-secondary counter" id="transferredCount" data-target="{{ $transferredStudents }}">{{ $transferredStudents }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-search me-2 text-primary"></i>
                            Pencarian & Filter
                        </h5>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text"
                                   id="searchInput"
                                   class="form-control border-start-0"
                                   placeholder="Cari nama siswa, NISN, no pendaftaran..."
                                   value="{{ $search ?? '' }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <select class="form-select" id="academicYearFilter">
                            <option value="all">Semua Tahun Ajaran</option>
                            <option value="2026/2027" {{ ($academicYear ?? '2026/2027') == '2026/2027' ? 'selected' : '' }}>2026/2027</option>
                            <option value="2025/2026" {{ ($academicYear ?? '') == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
                            <option value="2024/2025" {{ ($academicYear ?? '') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select class="form-select" id="unitFilter">
                            <option value="all">Semua Unit</option>
                            @foreach($availableUnits as $availableUnit)
                                <option value="{{ $availableUnit }}" {{ $unit == $availableUnit ? 'selected' : '' }}>
                                    {{ $availableUnit }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select class="form-select" id="studentStatusFilter">
                            <option value="all">Semua Status</option>
                            <option value="active" {{ $studentStatus == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ $studentStatus == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            <option value="graduated" {{ $studentStatus == 'graduated' ? 'selected' : '' }}>Lulus</option>
                            <option value="dropped_out" {{ $studentStatus == 'dropped_out' ? 'selected' : '' }}>Keluar</option>
                            <option value="transferred" {{ $studentStatus == 'transferred' ? 'selected' : '' }}>Pindah</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                            <i class="bi bi-x-circle me-1"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Bar -->
        <div id="bulkActionsBar" class="d-none bg-light border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    <i class="bi bi-check-square me-1"></i>
                    <span id="selectedCountText">0</span> siswa dipilih
                </div>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="bulkStatusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-diagram-3 me-1"></i>Update Status
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="bulkStatusDropdown">
                            <li><h6 class="dropdown-header">Pilih Status</h6></li>
                            <li><a class="dropdown-item bulk-status-option" href="#" data-status="active"><i class="bi bi-check-circle me-2 text-success"></i>Aktif</a></li>
                            <li><a class="dropdown-item bulk-status-option" href="#" data-status="inactive"><i class="bi bi-pause-circle me-2 text-warning"></i>Tidak Aktif</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item bulk-status-option" href="#" data-status="graduated"><i class="bi bi-mortarboard me-2 text-info"></i>Lulus</a></li>
                            <li><a class="dropdown-item bulk-status-option" href="#" data-status="dropped_out"><i class="bi bi-x-circle me-2 text-danger"></i>Keluar</a></li>
                            <li><a class="dropdown-item bulk-status-option" href="#" data-status="transferred"><i class="bi bi-arrow-right-circle me-2 text-secondary"></i>Pindah</a></li>
                        </ul>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm" onclick="clearSelection()">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-3 py-3" style="width: 50px;">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                        <label class="form-check-label visually-hidden" for="selectAll">Select All</label>
                                    </div>
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">
                                    <i class="bi bi-person me-1"></i>Nama Siswa
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">
                                    <i class="bi bi-credit-card-2-front me-1"></i>NISN
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">
                                    <i class="bi bi-card-text me-1"></i>No Pendaftaran
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">
                                    <i class="bi bi-building me-1"></i>Unit
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">
                                    <i class="bi bi-calendar me-1"></i>Tgl Lahir
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase">
                                    <i class="bi bi-diagram-3 me-1"></i>Status
                                </th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold text-uppercase text-center">
                                    <i class="bi bi-gear me-1"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            @include('admin.data-siswa.partials.table')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Menampilkan {{ $studentsData->firstItem() ?? 0 }} - {{ $studentsData->lastItem() ?? 0 }} dari {{ $studentsData->total() }} siswa
            </div>
            <div>
                {{ $studentsData->links() }}
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="statusForm">
                    <div class="modal-body">
                        <input type="hidden" id="studentId" name="student_id">
                        <input type="hidden" id="bulkMode" name="bulk_mode" value="false">

                        <div class="mb-3">
                            <label for="studentStatus" class="form-label">Status Siswa</label>
                            <select class="form-select" id="studentStatus" name="student_status" required>
                                <option value="">Pilih Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                                <option value="graduated">Lulus</option>
                                <option value="dropped_out">Keluar</option>
                                <option value="transferred">Pindah</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="statusNotes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="statusNotes" name="student_status_notes" rows="3" placeholder="Tambahkan catatan terkait perubahan status..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize elements
        const searchInput = document.getElementById('searchInput');
        const academicYearFilter = document.getElementById('academicYearFilter');
        const unitFilter = document.getElementById('unitFilter');
        const studentStatusFilter = document.getElementById('studentStatusFilter');
        const selectAllCheckbox = document.getElementById('selectAll');
        const bulkActionsBar = document.getElementById('bulkActionsBar');
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        const statusForm = document.getElementById('statusForm');

        let searchTimeout;

        // Search functionality
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    applyFilters();
                }, 300);
            });
        }

        // Filter events
        if (academicYearFilter) {
            academicYearFilter.addEventListener('change', applyFilters);
        }
        if (unitFilter) {
            unitFilter.addEventListener('change', applyFilters);
        }
        if (studentStatusFilter) {
            studentStatusFilter.addEventListener('change', applyFilters);
        }

        // Apply filters function
        function applyFilters() {
            const currentUrl = new URL(window.location.href);

            // Update URL parameters
            const search = searchInput?.value || '';
            const academicYear = academicYearFilter?.value || '';
            const unit = unitFilter?.value || '';
            const studentStatus = studentStatusFilter?.value || '';

            if (search) currentUrl.searchParams.set('search', search);
            else currentUrl.searchParams.delete('search');

            if (academicYear && academicYear !== 'all') currentUrl.searchParams.set('academic_year', academicYear);
            else currentUrl.searchParams.delete('academic_year');

            if (unit && unit !== 'all') currentUrl.searchParams.set('unit', unit);
            else currentUrl.searchParams.delete('unit');

            if (studentStatus && studentStatus !== 'all') currentUrl.searchParams.set('student_status', studentStatus);
            else currentUrl.searchParams.delete('student_status');

            // Navigate to filtered URL
            window.location.href = currentUrl.toString();
        }

        // Clear filters
        window.clearFilters = function() {
            if (searchInput) searchInput.value = '';
            if (academicYearFilter) academicYearFilter.value = '2026/2027';
            if (unitFilter) unitFilter.value = 'all';
            if (studentStatusFilter) studentStatusFilter.value = 'all';
            applyFilters();
        };

        // Refresh data
        window.refreshData = function() {
            window.location.reload();
        };

        // Checkbox selection management
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.row-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionsVisibility();
            });
        }

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('row-checkbox')) {
                updateBulkActionsVisibility();
                updateSelectAllState();
            }
        });

        function updateBulkActionsVisibility() {
            const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
            const selectedCount = selectedCheckboxes.length;

            if (selectedCount > 0) {
                bulkActionsBar.classList.remove('d-none');
                document.getElementById('selectedCountText').textContent = selectedCount;
            } else {
                bulkActionsBar.classList.add('d-none');
            }
        }

        function updateSelectAllState() {
            const allCheckboxes = document.querySelectorAll('.row-checkbox');
            const checkedCheckboxes = document.querySelectorAll('.row-checkbox:checked');

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allCheckboxes.length > 0 && checkedCheckboxes.length === allCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < allCheckboxes.length;
            }
        }

        // Clear selection
        window.clearSelection = function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            bulkActionsBar.classList.add('d-none');
        };

        // Individual status update
        window.updateStatus = function(studentId) {
            document.getElementById('studentId').value = studentId;
            document.getElementById('bulkMode').value = 'false';
            document.getElementById('studentStatus').value = '';
            document.getElementById('statusNotes').value = '';
            statusModal.show();
        };

        // Bulk status update
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('bulk-status-option')) {
                e.preventDefault();
                const status = e.target.dataset.status;
                const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);

                if (selectedIds.length === 0) {
                    alert('Pilih siswa terlebih dahulu');
                    return;
                }

                document.getElementById('bulkMode').value = 'true';
                document.getElementById('studentStatus').value = status;
                document.getElementById('statusNotes').value = '';
                statusModal.show();
            }
        });

        // Form submission
        if (statusForm) {
            statusForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const isBulk = formData.get('bulk_mode') === 'true';

                let url, data;
                if (isBulk) {
                    const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
                    url = '{{ route("admin.data-siswa.bulk-update-status") }}';
                    data = {
                        student_ids: selectedIds,
                        student_status: formData.get('student_status'),
                        student_status_notes: formData.get('student_status_notes'),
                        _token: '{{ csrf_token() }}'
                    };
                } else {
                    const studentId = formData.get('student_id');
                    url = `/admin/data-siswa/${studentId}/update-status`;
                    data = {
                        student_status: formData.get('student_status'),
                        student_status_notes: formData.get('student_status_notes'),
                        _token: '{{ csrf_token() }}'
                    };
                }

                fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        statusModal.hide();
                        // Show success message
                        showAlert('success', data.message);
                        // Reload page to refresh data
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        showAlert('error', data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Terjadi kesalahan saat mengupdate status');
                });
            });
        }

        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const iconClass = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle';

            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="${iconClass} me-2"></i>
                    <strong>${type === 'success' ? 'Sukses!' : 'Error!'}</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            const container = document.querySelector('.container-fluid');
            container.insertAdjacentHTML('afterbegin', alertHtml);

            // Auto dismiss after 5 seconds
            setTimeout(() => {
                const alert = container.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }

        // Auto close alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.classList.contains('show')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    });
    </script>
    @endpush
</x-app-layout>
