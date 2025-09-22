<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-person-fill me-2 text-primary"></i>
                    Detail Siswa - {{ $student->nama_murid }}
                </h2>
                <p class="text-muted small mb-0">
                    <span class="badge bg-primary bg-opacity-10 text-primary me-2">{{ $student->no_pendaftaran }}</span>
                    {{ $student->unit }} - {{ $student->jenjang }} - {{ $student->academic_year ?? '2026/2027' }}
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.data-siswa.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                <button class="btn btn-primary" onclick="updateStatus({{ $student->id }})">
                    <i class="bi bi-diagram-3 me-1"></i>Update Status
                </button>
            </div>
        </div>
    </x-slot>

    <!-- Add a subtle gradient background to the entire page -->
    <div class="container-fluid py-4" style="background: linear-gradient(to bottom right, #f8f9fa, #e9effd);">

        <!-- Student Quick Info Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body" style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                        <div class="row align-items-center text-white">
                            <div class="col-md-2 text-center">
                                <div class="bg-white bg-opacity-20 rounded-circle p-4 mx-auto" style="width: 80px; height: 80px;">
                                    <i class="bi bi-person-fill fs-1"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3 class="fw-bold mb-1">{{ $student->nama_murid }}</h3>
                                <p class="mb-2 opacity-75">
                                    <i class="bi bi-card-text me-1"></i>{{ $student->no_pendaftaran }}
                                    @if($student->nisn)
                                        <span class="ms-3"><i class="bi bi-person-badge me-1"></i>{{ $student->nisn }}</span>
                                    @endif
                                </p>
                                <div class="d-flex gap-3">
                                    <span><i class="bi bi-calendar me-1"></i>{{ \Carbon\Carbon::parse($student->tanggal_lahir)->format('d M Y') }} ({{ \Carbon\Carbon::parse($student->tanggal_lahir)->age }} tahun)</span>
                                    <span><i class="bi bi-building me-1"></i>{{ $student->unit }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                @php
                                    $statusConfig = [
                                        'active' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Aktif'],
                                        'inactive' => ['class' => 'warning', 'icon' => 'pause-circle', 'text' => 'Tidak Aktif'],
                                        'graduated' => ['class' => 'info', 'icon' => 'mortarboard', 'text' => 'Lulus'],
                                        'dropped_out' => ['class' => 'danger', 'icon' => 'x-circle', 'text' => 'Keluar'],
                                        'transferred' => ['class' => 'secondary', 'icon' => 'arrow-right-circle', 'text' => 'Pindah'],
                                    ];
                                    $currentStatus = $statusConfig[$student->student_status] ?? $statusConfig['inactive'];
                                @endphp

                                <div class="bg-blue-500 bg-opacity-20 rounded-3 p-3 mb-3">
                                    <div class="text-white-50 small">Status Siswa</div>
                                    <div class="fw-bold fs-5">
                                        <i class="bi bi-{{ $currentStatus['icon'] }} me-2"></i>
                                        {{ $currentStatus['text'] }}
                                    </div>
                                </div>

                                @if($student->user)
                                    <div class="small opacity-75">
                                        <i class="bi bi-envelope me-1"></i>{{ $student->user->email }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                    <!-- Tab Navigation -->
                    <div class="card-header bg-white border-0 p-0">
                        <ul class="nav nav-tabs nav-fill border-0" id="studentDetailTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-0 fw-semibold" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                                    <i class="bi bi-speedometer2 me-2"></i>Overview
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-0 fw-semibold" id="student-tab" data-bs-toggle="tab" data-bs-target="#student" type="button" role="tab">
                                    <i class="bi bi-person-circle me-2"></i>Data Siswa
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-0 fw-semibold" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic" type="button" role="tab">
                                    <i class="bi bi-mortarboard me-2"></i>Akademik
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-0 fw-semibold" id="achievements-tab" data-bs-toggle="tab" data-bs-target="#achievements" type="button" role="tab">
                                    <i class="bi bi-trophy me-2"></i>Prestasi
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-0 fw-semibold" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                                    <i class="bi bi-file-earmark-text me-2"></i>Dokumen
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-0 fw-semibold" id="health-tab" data-bs-toggle="tab" data-bs-target="#health" type="button" role="tab">
                                    <i class="bi bi-heart-pulse me-2"></i>Kesehatan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-0 fw-semibold" id="parent-tab" data-bs-toggle="tab" data-bs-target="#parent" type="button" role="tab">
                                    <i class="bi bi-people me-2"></i>Orang Tua
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-0 fw-semibold" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" type="button" role="tab">
                                    <i class="bi bi-chat-text me-2"></i>Review
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Tab Content -->
                    <div class="card-body p-4">
                        <div class="tab-content" id="studentDetailTabsContent">

                            <!-- Overview Tab -->
                            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                                @include('admin.data-siswa.tabs.overview')
                            </div>

                            <!-- Student Tab -->
                            <div class="tab-pane fade" id="student" role="tabpanel" aria-labelledby="student-tab">
                                @include('admin.data-siswa.tabs.student')
                            </div>

                            <!-- Academic Tab -->
                            <div class="tab-pane fade" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                                @include('admin.data-siswa.tabs.academic')
                            </div>

                            <!-- Achievements Tab -->
                            <div class="tab-pane fade" id="achievements" role="tabpanel" aria-labelledby="achievements-tab">
                                @include('admin.data-siswa.tabs.achievements')
                            </div>

                            <!-- Documents Tab -->
                            <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                @include('admin.data-siswa.tabs.documents')
                            </div>

                            <!-- Health Tab -->
                            <div class="tab-pane fade" id="health" role="tabpanel" aria-labelledby="health-tab">
                                @include('admin.data-siswa.tabs.health')
                            </div>

                            <!-- Parent Tab -->
                            <div class="tab-pane fade" id="parent" role="tabpanel" aria-labelledby="parent-tab">
                                @include('admin.data-siswa.tabs.parent')
                            </div>

                            <!-- Review Tab -->
                            <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                                @include('admin.data-siswa.tabs.review')
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Update Status Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="statusForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="student_status" class="form-label">Status Siswa</label>
                            <select class="form-select" id="student_status" name="student_status" required>
                                <option value="active" {{ $student->student_status === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ $student->student_status === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="graduated" {{ $student->student_status === 'graduated' ? 'selected' : '' }}>Lulus</option>
                                <option value="dropped_out" {{ $student->student_status === 'dropped_out' ? 'selected' : '' }}>Keluar</option>
                                <option value="transferred" {{ $student->student_status === 'transferred' ? 'selected' : '' }}>Pindah</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="student_status_notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="student_status_notes" name="student_status_notes" rows="3" placeholder="Tambahkan catatan mengenai perubahan status...">{{ $student->student_status_notes }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentStudentId = {{ $student->id }};

        function updateStatus(studentId) {
            currentStudentId = studentId;
            $('#statusModal').modal('show');
        }

        $('#statusForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(`/admin/data-siswa/${currentStudentId}/update-status`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const alertHtml = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Sukses!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);

                    // Close modal and reload page
                    $('#statusModal').modal('hide');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    // Show error message
                    const alertHtml = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <strong>Error!</strong> ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                    document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const alertHtml = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>Error!</strong> Terjadi kesalahan saat memperbarui status.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
            });
        });
    </script>
    @endpush
</x-app-layout>
