<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-journal-text me-2 text-primary"></i>
                Raport & Nilai
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.data') }}">Kelengkapan Data</a></li>
                    <li class="breadcrumb-item active">Raport & Nilai</li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row">
            <!-- Input Form -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm sticky-top">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-plus-circle me-2"></i>
                            Input Nilai Raport
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('user.data.grades.store') }}" method="POST" id="gradesForm">
                            @csrf

                            <!-- Semester & Year -->
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <label for="semester" class="form-label fw-semibold">
                                        Semester <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('semester') is-invalid @enderror"
                                            id="semester" name="semester" required>
                                        <option value="">Pilih Semester</option>
                                        @for($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}">Semester {{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('semester')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <label for="tahun_ajaran" class="form-label fw-semibold">
                                        Tahun Ajaran <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('tahun_ajaran') is-invalid @enderror"
                                           id="tahun_ajaran" name="tahun_ajaran"
                                           placeholder="2023/2024" required>
                                    @error('tahun_ajaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Subjects -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Nilai Mata Pelajaran <span class="text-danger">*</span>
                                </label>

                                @php
                                    // Pastikan academicSubjects tidak null
                                    $uniqueSubjects = $academicSubjects ? $academicSubjects->where('is_active', true)->unique('nama_mapel') : collect();
                                    $kategoris = $uniqueSubjects->groupBy('kategori');
                                @endphp

                                <!-- Tambahkan sebelum subjects container -->
                                <div class="alert alert-info border-0 mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <i class="bi bi-info-circle fs-4"></i>
                                        </div>
                                        <div class="col">
                                            <h6 class="mb-1">Panduan Input Nilai</h6>
                                            <ul class="mb-0 small">
                                                <li>Input nilai sesuai raport resmi dari sekolah sebelumnya</li>
                                                <li>Nilai dalam rentang 0-100</li>
                                                <li>Minimal input {{ $uniqueSubjects->count() >= 8 ? '8' : ($uniqueSubjects->count() > 0 ? 'semua' : '0') }} mata pelajaran</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="subjects-container">
                                    @foreach($kategoris as $kategori => $subjects)
                                        <div class="mb-3">
                                            <h6 class="text-primary fw-bold border-bottom pb-1 mb-2">
                                                <i class="bi bi-bookmark me-1"></i>{{ $kategori }}
                                            </h6>

                                            @foreach($subjects as $index => $subject)
                                            <div class="card border mb-2 subject-card">
                                                <div class="card-body p-3">
                                                    <div class="row align-items-center">
                                                        <div class="col-8">
                                                            <label class="form-label mb-0 fw-semibold">{{ $subject->nama_mapel }}</label>
                                                            <input type="hidden" name="subjects[{{ $subject->id }}][academic_subject_id]" value="{{ $subject->id }}">
                                                            @if($subject->kkm)
                                                                <div class="form-text small text-muted">KKM: {{ $subject->kkm }}</div>
                                                            @endif
                                                        </div>
                                                        <div class="col-4">
                                                            <input type="number"
                                                                   class="form-control nilai-input"
                                                                   name="subjects[{{ $subject->id }}][nilai]"
                                                                   min="0" max="100" step="0.1"
                                                                   placeholder="0-100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Average Display -->
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Rata-rata Nilai:</span>
                                        <span id="average-display" class="h5 text-primary mb-0">-</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bi bi-check-lg me-2"></i>Simpan Nilai
                            </button>
                        </form>

                        <!-- Guidelines -->
                        <div class="mt-4">
                            <h6 class="fw-bold text-primary">Panduan Penilaian:</h6>
                            <ul class="small text-muted mb-0">
                                <li>Nilai 90-100: Sangat Baik (A)</li>
                                <li>Nilai 80-89: Baik (B)</li>
                                <li>Nilai 70-79: Cukup (C)</li>
                                <li>Nilai 60-69: Kurang (D)</li>
                                <li>Nilai 0-59: Sangat Kurang (E)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grade Reports List -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-list-ul me-2"></i>
                                Raport yang Sudah Diinput
                            </h5>
                            <span class="badge bg-primary">{{ count($gradeReports) }} Raport</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if(count($gradeReports) > 0)
                            @foreach($gradeReports as $report)
                            <div class="border-bottom p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-1">
                                            Semester {{ $report->semester }} - {{ $report->tahun_ajaran }}
                                        </h6>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar me-1"></i>{{ $report->created_at->format('d M Y') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        @php
                                            $totalNilai = $report->subjectGrades->sum('nilai');
                                            $jumlahMapel = $report->subjectGrades->count();
                                            $rataRata = $jumlahMapel > 0 ? $totalNilai / $jumlahMapel : 0;
                                        @endphp
                                        <div class="badge bg-success fs-6">
                                            Rata-rata: {{ number_format($rataRata, 1) }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Subjects Table -->
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mata Pelajaran</th>
                                                <th width="100">Nilai</th>
                                                <th width="80">Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($report->subjectGrades as $grade)
                                            <tr>
                                                <td>{{ $grade->academicSubject->nama_mapel }}</td>
                                                <td class="text-center">{{ $grade->nilai }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $gradeLabel = '';
                                                        $gradeClass = '';
                                                        if($grade->nilai >= 90) { $gradeLabel = 'A'; $gradeClass = 'bg-success'; }
                                                        elseif($grade->nilai >= 80) { $gradeLabel = 'B'; $gradeClass = 'bg-info'; }
                                                        elseif($grade->nilai >= 70) { $gradeLabel = 'C'; $gradeClass = 'bg-warning'; }
                                                        elseif($grade->nilai >= 60) { $gradeLabel = 'D'; $gradeClass = 'bg-orange'; }
                                                        else { $gradeLabel = 'E'; $gradeClass = 'bg-danger'; }
                                                    @endphp
                                                    <span class="badge {{ $gradeClass }}">{{ $gradeLabel }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Character Assessment -->
                                @if($report->characterAssessment)
                                <div class="mt-3">
                                    <h6 class="fw-bold text-primary mb-2">Penilaian Karakter:</h6>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <small class="text-muted">Sikap Spiritual:</small>
                                            <div class="badge bg-info">{{ $report->characterAssessment->sikap_spiritual }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">Sikap Sosial:</small>
                                            <div class="badge bg-info">{{ $report->characterAssessment->sikap_sosial }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Actions -->
                                <div class="mt-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="editGradeReport({{ $report->id }})">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="deleteGradeReport({{ $report->id }})">
                                        <i class="bi bi-trash me-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-journal-x text-muted" style="font-size: 4rem;"></i>
                                <h5 class="text-muted mt-3">Belum Ada Raport</h5>
                                <p class="text-muted">Input nilai raport pertama Anda menggunakan form di sebelah kiri.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('user.data') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Menu Utama
                </a>
            </div>
        </div>
    </div>

    <script>
        // Calculate average
        function calculateAverage() {
            const nilaiInputs = document.querySelectorAll('.nilai-input');
            let total = 0;
            let count = 0;

            nilaiInputs.forEach(input => {
                const value = parseFloat(input.value);
                if (!isNaN(value) && value > 0) {
                    total += value;
                    count++;
                }
            });

            const average = count > 0 ? total / count : 0;
            document.getElementById('average-display').textContent = average.toFixed(1);
        }

        // Add event listeners to all nilai inputs
        document.querySelectorAll('.nilai-input').forEach(input => {
            input.addEventListener('input', calculateAverage);
        });

        // Auto-generate tahun ajaran based on current year
        document.getElementById('semester').addEventListener('change', function() {
            const tahunAjaranInput = document.getElementById('tahun_ajaran');
            if (!tahunAjaranInput.value) {
                const currentYear = new Date().getFullYear();
                const nextYear = currentYear + 1;
                tahunAjaranInput.value = `${currentYear}/${nextYear}`;
            }
        });

        function editGradeReport(reportId) {
            // Add edit functionality here
            alert('Fitur edit akan segera tersedia');
        }

        function deleteGradeReport(reportId) {
            if (confirm('Apakah Anda yakin ingin menghapus raport ini?')) {
                // Add delete functionality here
                fetch(`/user/data/grades/${reportId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Gagal menghapus raport');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
            }
        }
    </script>

    <style>
        .bg-orange {
            background-color: #fd7e14 !important;
        }

        .subject-card:hover {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .nilai-input:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }
    </style>
</x-app-layout>
