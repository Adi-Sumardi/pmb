<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-person-fill me-2 text-primary"></i>
                    Detail Siswa
                </h2>
                <p class="text-muted small mb-0">Informasi lengkap siswa {{ $student->nama_murid }}</p>
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

    <div class="container-fluid py-4">
        <div class="row g-4">
            <!-- Student Information Card -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-circle me-2"></i>
                            Informasi Siswa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Nama Lengkap</label>
                                <div class="fw-bold">{{ $student->nama_murid }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">NISN</label>
                                <div class="fw-bold">{{ $student->nisn ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">No. Pendaftaran</label>
                                <div class="fw-bold">{{ $student->no_pendaftaran }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Tahun Ajaran</label>
                                <div class="fw-bold">{{ $student->academic_year ?? '2026/2027' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Unit Sekolah</label>
                                <div class="fw-bold">{{ $student->unit }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Jenjang</label>
                                <div class="fw-bold">{{ strtoupper($student->jenjang) }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Tanggal Lahir</label>
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($student->tanggal_lahir)->locale('id')->translatedFormat('d F Y') }}</div>
                                <small class="text-muted">({{ \Carbon\Carbon::parse($student->tanggal_lahir)->age }} tahun)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Alamat</label>
                                <div class="fw-bold">{{ $student->alamat }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parent Information Card -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-people me-2"></i>
                            Informasi Orang Tua
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Nama Ayah</label>
                                <div class="fw-bold">{{ $student->nama_ayah }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Telepon Ayah</label>
                                <div class="fw-bold">{{ $student->telp_ayah }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Nama Ibu</label>
                                <div class="fw-bold">{{ $student->nama_ibu }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Telepon Ibu</label>
                                <div class="fw-bold">{{ $student->telp_ibu }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Information Card -->
                @if($student->user)
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-circle me-2"></i>
                            Informasi Akun
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Email</label>
                                <div class="fw-bold">{{ $student->user->email }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Status Akun</label>
                                <div class="fw-bold">
                                    @if($student->user->email_verified_at)
                                        <span class="badge bg-success">Terverifikasi</span>
                                    @else
                                        <span class="badge bg-warning">Belum Terverifikasi</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Status & Actions Card -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-diagram-3 me-2"></i>
                            Status Siswa
                        </h5>
                    </div>
                    <div class="card-body">
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

                        <div class="text-center mb-3">
                            <div class="bg-{{ $currentStatus['class'] }} bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: 80px; height: 80px;">
                                <i class="bi bi-{{ $currentStatus['icon'] }} text-{{ $currentStatus['class'] }} fs-2"></i>
                            </div>
                            <h4 class="text-{{ $currentStatus['class'] }}">{{ $currentStatus['text'] }}</h4>
                        </div>

                        @if($student->student_activated_at)
                        <div class="mb-3">
                            <label class="form-label text-muted small">Tanggal Aktif</label>
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($student->student_activated_at)->locale('id')->translatedFormat('d F Y H:i') }}</div>
                        </div>
                        @endif

                        @if($student->student_status_notes)
                        <div class="mb-3">
                            <label class="form-label text-muted small">Catatan Status</label>
                            <div class="fw-bold">{{ $student->student_status_notes }}</div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label text-muted small">Terakhir Update</label>
                            <div class="fw-bold">{{ $student->updated_at->locale('id')->translatedFormat('d F Y H:i') }}</div>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary" onclick="updateStatus({{ $student->id }})">
                                <i class="bi bi-diagram-3 me-2"></i>
                                Update Status
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Overall Registration Status -->
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-check me-2"></i>
                            Status Pendaftaran
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Status Verifikasi</label>
                            <div class="fw-bold">
                                @if($student->status === 'diverifikasi')
                                    <span class="badge bg-success">Diverifikasi</span>
                                @else
                                    <span class="badge bg-warning">{{ ucfirst($student->status) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small">Status Keseluruhan</label>
                            <div class="fw-bold">
                                @if($student->overall_status === 'Lulus')
                                    <span class="badge bg-success">{{ $student->overall_status }}</span>
                                @else
                                    <span class="badge bg-info">{{ $student->overall_status }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small">Tanggal Daftar</label>
                            <div class="fw-bold">{{ $student->created_at->locale('id')->translatedFormat('d F Y H:i') }}</div>
                        </div>
                    </div>
                </div>
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
                        <input type="hidden" id="studentId" name="student_id" value="{{ $student->id }}">

                        <div class="mb-3">
                            <label for="studentStatus" class="form-label">Status Siswa</label>
                            <select class="form-select" id="studentStatus" name="student_status" required>
                                <option value="">Pilih Status</option>
                                <option value="active" {{ $student->student_status === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ $student->student_status === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="graduated" {{ $student->student_status === 'graduated' ? 'selected' : '' }}>Lulus</option>
                                <option value="dropped_out" {{ $student->student_status === 'dropped_out' ? 'selected' : '' }}>Keluar</option>
                                <option value="transferred" {{ $student->student_status === 'transferred' ? 'selected' : '' }}>Pindah</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="statusNotes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="statusNotes" name="student_status_notes" rows="3" placeholder="Tambahkan catatan terkait perubahan status...">{{ $student->student_status_notes }}</textarea>
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
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        const statusForm = document.getElementById('statusForm');

        // Individual status update
        window.updateStatus = function(studentId) {
            statusModal.show();
        };

        // Form submission
        if (statusForm) {
            statusForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const studentId = formData.get('student_id');

                const data = {
                    student_status: formData.get('student_status'),
                    student_status_notes: formData.get('student_status_notes'),
                    _token: '{{ csrf_token() }}'
                };

                fetch(`/admin/data-siswa/${studentId}/update-status`, {
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
                        // Show success message and reload
                        showAlert('success', data.message);
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
    });
    </script>
    @endpush
</x-app-layout>
