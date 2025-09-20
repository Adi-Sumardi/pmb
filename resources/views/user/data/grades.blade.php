<x-app-layout>
    <!-- Pastikan resources SweetAlert2 dimuat di halaman -->
    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    @endpush

    <!-- Subtle gradient background to the entire page -->
    <div class="container-fluid py-4" style="background: linear-gradient(to bottom right, #f8f9fa, #e9effd);">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                    <!-- Enhanced gradient header with vibrant colors -->
                    <div class="card-header border-0" style="background: linear-gradient(135deg, #4361ee, #3a0ca3);">
                        <div class="row align-items-center py-4">
                            <div class="col">
                                <h3 class="text-white fw-bold mb-0">
                                    <i class="bi bi-journal-text me-2"></i>
                                    Raport & Nilai
                                </h3>
                                <p class="text-white opacity-75 mb-0">Input nilai raport untuk melengkapi pendaftaran</p>
                            </div>
                            <div class="col-auto">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-white text-opacity-75">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('user.data.index') }}" class="text-white text-opacity-75">Kelengkapan Data</a></li>
                                        <li class="breadcrumb-item active text-white">Raport & Nilai</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Input Form -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-4 sticky-top">
                    <div class="card-header py-3" style="background: linear-gradient(to right, #fff8e1, #ffecb3); border-left: 5px solid #ffc107;">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-plus-circle me-2"></i>
                            Input Nilai Raport
                        </h5>
                    </div>
                    <div class="card-body p-4" style="background-color: #fffbf2;">
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm rounded-3" style="background-color: #ffebee; border-left: 4px solid #f44336;">
                                <i class="bi bi-exclamation-circle-fill me-2" style="color: #d32f2f;"></i>
                                <strong>Terdapat kesalahan pada pengisian form:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert border-0 shadow-sm rounded-3" style="background-color: #e8f5e9; border-left: 4px solid #43a047;">
                                <i class="bi bi-check-circle-fill me-2" style="color: #2e7d32;"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('user.data.grades.store') }}" method="POST" id="gradesForm" class="needs-validation">
                            @csrf

                            <!-- Semester & Year -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="semester" class="form-label fw-semibold" style="color: #36474f;">
                                        Semester <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('semester') is-invalid @enderror"
                                            id="semester" name="semester" required>
                                        <option value="">Pilih</option>
                                        <option value="Ganjil">Ganjil</option>
                                        <option value="Genap">Genap</option>
                                    </select>
                                    @error('semester')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="tahun_ajaran" class="form-label fw-semibold" style="color: #36474f;">
                                        Tahun Ajaran <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('tahun_ajaran') is-invalid @enderror"
                                           id="tahun_ajaran" name="tahun_ajaran"
                                           placeholder="2023/2024" required>
                                    @error('tahun_ajaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kelas dan Jenjang -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="kelas" class="form-label fw-semibold" style="color: #36474f;">
                                        Kelas <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('kelas') is-invalid @enderror"
                                           id="kelas" name="kelas" placeholder="Contoh: 9A" required>
                                    @error('kelas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="jenjang" class="form-label fw-semibold" style="color: #36474f;">
                                        Jenjang <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('jenjang') is-invalid @enderror"
                                            id="jenjang" name="jenjang" required>
                                        <option value="">Pilih</option>
                                        <option value="SD">SD</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMA">SMA</option>
                                        <option value="SMK">SMK</option>
                                    </select>
                                    @error('jenjang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Data Sekolah -->
                            <div class="card mb-4 border-0 shadow-sm rounded-3" style="background-color: #f8f9fa;">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Data Sekolah</h6>

                                    <div class="mb-3">
                                        <label for="nama_sekolah" class="form-label fw-semibold" style="color: #36474f;">
                                            Nama Sekolah <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('nama_sekolah') is-invalid @enderror"
                                               id="nama_sekolah" name="nama_sekolah" required>
                                        @error('nama_sekolah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="npsn" class="form-label fw-semibold" style="color: #36474f;">
                                            NPSN
                                        </label>
                                        <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('npsn') is-invalid @enderror"
                                               id="npsn" name="npsn">
                                        @error('npsn')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="alamat_sekolah" class="form-label fw-semibold" style="color: #36474f;">
                                            Alamat Sekolah
                                        </label>
                                        <textarea class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('alamat_sekolah') is-invalid @enderror"
                                                  id="alamat_sekolah" name="alamat_sekolah" rows="2"></textarea>
                                        @error('alamat_sekolah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Statistik Nilai -->
                            <div class="card mb-4 border-0 shadow-sm rounded-3" style="background-color: #f8f9fa;">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Statistik Kelas</h6>

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="ranking_kelas" class="form-label fw-semibold" style="color: #36474f;">
                                                Ranking
                                            </label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('ranking_kelas') is-invalid @enderror"
                                                   id="ranking_kelas" name="ranking_kelas" min="1">
                                            @error('ranking_kelas')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-8">
                                            <label for="jumlah_siswa_kelas" class="form-label fw-semibold" style="color: #36474f;">
                                                Jumlah Siswa Kelas
                                            </label>
                                            <input type="number" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('jumlah_siswa_kelas') is-invalid @enderror"
                                                   id="jumlah_siswa_kelas" name="jumlah_siswa_kelas" min="1">
                                            @error('jumlah_siswa_kelas')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Subjects -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-primary">
                                    Nilai Mata Pelajaran <span class="text-danger">*</span>
                                </label>

                                <div class="alert alert-info border-0 mb-3 rounded-3 shadow-sm" style="background-color: #e1f5fe; border-left: 4px solid #03a9f4;">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <i class="bi bi-info-circle fs-4"></i>
                                        </div>
                                        <div class="col">
                                            <h6 class="mb-1">Panduan Input Nilai</h6>
                                            <ul class="mb-0 small">
                                                <li>Input nilai sesuai raport resmi dari sekolah sebelumnya</li>
                                                <li>Nilai dalam rentang 0-100</li>
                                                <li>Minimal input {{ $academicSubjects->count() >= 8 ? '8' : ($academicSubjects->count() > 0 ? 'semua' : '0') }} mata pelajaran</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-warning border-0 mb-3 rounded-3 shadow-sm" id="min-subjects-alert" style="background-color: #fff3cd; border-left: 4px solid #ffc107;">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">Minimal 8 Mata Pelajaran</h6>
                                            <p class="mb-0 small">Mata pelajaran terisi: <span id="subject-counter" class="badge bg-warning">0</span>/8 minimum</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="subjects-container">
                                    @php
                                        // Pastikan academicSubjects tidak null
                                        $uniqueSubjects = $academicSubjects ? $academicSubjects->where('is_active', true)->unique('nama_mapel') : collect();
                                        $kategoris = $uniqueSubjects->groupBy('kategori');
                                    @endphp

                                    @foreach($kategoris as $kategori => $subjects)
                                        <div class="mb-3">
                                            <h6 class="text-primary fw-bold border-bottom pb-1 mb-2">
                                                <i class="bi bi-bookmark me-1"></i>{{ $kategori }}
                                            </h6>

                                            @foreach($subjects as $index => $subject)
                                            <div class="card border-0 shadow-sm mb-2 subject-card rounded-3">
                                                <div class="card-body p-3">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-8">
                                                            <label class="form-label mb-0 fw-semibold">{{ $subject->nama_mapel }}</label>
                                                            <input type="hidden" name="subjects[{{ $subject->id }}][academic_subject_id]" value="{{ $subject->id }}">
                                                            @if($subject->kkm)
                                                                <div class="form-text small text-muted">KKM: {{ $subject->kkm }}</div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="number"
                                                                   class="form-control form-control-lg rounded-3 border-0 shadow-sm nilai-input"
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
                            <div class="card bg-light border-0 shadow-sm mb-4 rounded-3" style="background: linear-gradient(to right, #e8f5e9, #c8e6c9);">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Rata-rata Nilai:</span>
                                        <span id="average-display" class="h5 text-primary mb-0">-</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning btn-lg w-100 shadow-sm">
                                <i class="bi bi-check-lg me-2"></i>Simpan Nilai
                            </button>
                        </form>

                        <!-- Guidelines -->
                        <div class="mt-4 p-3 rounded-3" style="background-color: #fff8e1; border-left: 4px solid #ffc107;">
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
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header py-3" style="background: linear-gradient(to right, #e3f2fd, #bbdefb); border-left: 5px solid #2196f3;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="bi bi-list-ul me-2"></i>
                                Raport yang Sudah Diinput
                            </h5>
                            <span class="badge bg-primary">{{ count($gradeReports) }} Raport</span>
                        </div>
                    </div>
                    <div class="card-body p-0" style="background-color: #f5f9ff;">
                        @if(count($gradeReports) > 0)
                            @foreach($gradeReports as $report)
                            <div class="border-bottom p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-1">
                                            Semester {{ $report->semester }} - {{ $report->tahun_ajaran }}
                                        </h6>
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-building me-1"></i>{{ $report->nama_sekolah ?? 'Tidak diketahui' }}
                                            <span class="ms-2">
                                                <i class="bi bi-journal me-1"></i>Kelas {{ $report->kelas ?? '-' }}
                                            </span>
                                        </p>
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
                                        <div class="badge bg-success fs-6 shadow-sm">
                                            Rata-rata: {{ number_format($rataRata, 1) }}
                                        </div>
                                        @if($report->ranking_kelas)
                                        <div class="badge bg-info mt-2 shadow-sm">
                                            Ranking: {{ $report->ranking_kelas }}/{{ $report->jumlah_siswa_kelas ?? '?' }}
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Subjects Table -->
                                <div class="table-responsive mt-2">
                                    <table class="table table-sm table-bordered rounded overflow-hidden shadow-sm">
                                        <thead style="background: linear-gradient(to right, #e3f2fd, #bbdefb);">
                                            <tr>
                                                <th>Mata Pelajaran</th>
                                                <th width="100" class="text-center">Nilai</th>
                                                <th width="80" class="text-center">Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($report->subjectGrades as $grade)
                                            <tr>
                                                <td>{{ $grade->academicSubject->nama_mapel }}</td>
                                                <td class="text-center fw-bold">{{ $grade->nilai }}</td>
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
                                                    <span class="badge {{ $gradeClass }} shadow-sm">{{ $gradeLabel }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot style="background-color: #f5f5f5;">
                                            <tr class="fw-bold">
                                                <td>Rata-rata</td>
                                                <td class="text-center">{{ number_format($rataRata, 1) }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $avgGradeLabel = '';
                                                        $avgGradeClass = '';
                                                        if($rataRata >= 90) { $avgGradeLabel = 'A'; $avgGradeClass = 'bg-success'; }
                                                        elseif($rataRata >= 80) { $avgGradeLabel = 'B'; $avgGradeClass = 'bg-info'; }
                                                        elseif($rataRata >= 70) { $avgGradeLabel = 'C'; $avgGradeClass = 'bg-warning'; }
                                                        elseif($rataRata >= 60) { $avgGradeLabel = 'D'; $avgGradeClass = 'bg-orange'; }
                                                        else { $avgGradeLabel = 'E'; $avgGradeClass = 'bg-danger'; }
                                                    @endphp
                                                    <span class="badge {{ $avgGradeClass }}">{{ $avgGradeLabel }}</span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Character Assessment -->
                                @if($report->characterAssessment)
                                <div class="mt-3 card border-0 shadow-sm rounded-3" style="background-color: #f8f9fa;">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-primary mb-2">Penilaian Karakter:</h6>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <small class="text-muted">Sikap Spiritual:</small>
                                                <div class="badge bg-info shadow-sm">{{ $report->characterAssessment->sikap_spiritual }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted">Sikap Sosial:</small>
                                                <div class="badge bg-info shadow-sm">{{ $report->characterAssessment->sikap_sosial }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Actions -->
                                <div class="mt-3">
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-3 shadow-sm"
                                            onclick="editGradeReport({{ $report->id }})">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-3 shadow-sm"
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
                <a href="{{ route('user.data.index') }}" class="btn btn-outline-secondary btn-lg px-4 shadow-sm" style="transition: all 0.3s">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Menu Utama
                </a>
            </div>
        </div>
    </div>

    <script>
        // Fallback jika SweetAlert tidak terdefinisi
        if (typeof Swal === 'undefined') {
            console.warn('SweetAlert2 tidak tersedia, loading secara inline');
            document.write('<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"><\/script>');
        }

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

            // Set warna berdasarkan rata-rata
            const averageDisplay = document.getElementById('average-display');
            if (average >= 90) {
                averageDisplay.className = 'h5 text-success mb-0 fw-bold';
            } else if (average >= 80) {
                averageDisplay.className = 'h5 text-primary mb-0 fw-bold';
            } else if (average >= 70) {
                averageDisplay.className = 'h5 text-warning mb-0 fw-bold';
            } else if (average >= 60) {
                averageDisplay.className = 'h5 text-orange mb-0 fw-bold';
            } else if (average > 0) {
                averageDisplay.className = 'h5 text-danger mb-0 fw-bold';
            } else {
                averageDisplay.className = 'h5 text-muted mb-0';
                averageDisplay.textContent = '-';
            }
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

        document.getElementById('gradesForm').addEventListener('submit', function(e) {
            // Hitung jumlah mata pelajaran yang diisi
            const nilaiInputs = document.querySelectorAll('.nilai-input');
            let filledSubjectsCount = 0;

            nilaiInputs.forEach(input => {
                if (input.value && parseFloat(input.value) > 0) {
                    filledSubjectsCount++;
                }
            });

            // Validasi minimal 8 mata pelajaran
            if (filledSubjectsCount < 8) {
                e.preventDefault(); // Hentikan pengiriman form

                // Tampilkan pesan error dengan SweetAlert
                Swal.fire({
                    title: 'Nilai Tidak Lengkap',
                    text: 'Minimal 8 mata pelajaran harus diisi nilainya',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });

                // Highlight mata pelajaran yang harus diisi
                highlightRequiredSubjects();
            }
        });

        // Fungsi untuk highlight mata pelajaran yang harus diisi
        function highlightRequiredSubjects() {
            const nilaiInputs = document.querySelectorAll('.nilai-input');
            let filledCount = 0;

            nilaiInputs.forEach(input => {
                const cardElement = input.closest('.subject-card');

                if (input.value && parseFloat(input.value) > 0) {
                    filledCount++;
                    cardElement.classList.add('filled-subject');
                    cardElement.classList.remove('required-subject');
                } else {
                    if (filledCount < 8) {
                        cardElement.classList.add('required-subject');
                        cardElement.classList.remove('filled-subject');
                    }
                }
            });
        }

        // Tambahkan event listener untuk input nilai yang memperbarui visual feedback
        document.querySelectorAll('.nilai-input').forEach(input => {
            input.addEventListener('input', function() {
                const cardElement = this.closest('.subject-card');

                if (this.value && parseFloat(this.value) > 0) {
                    cardElement.classList.add('filled-subject');
                    cardElement.classList.remove('required-subject');
                } else {
                    cardElement.classList.remove('filled-subject');
                }

                // Update counter dan visual indicator
                updateSubjectCounter();
            });
        });

        function updateSubjectCounter() {
            const nilaiInputs = document.querySelectorAll('.nilai-input');
            let filledCount = 0;

            nilaiInputs.forEach(input => {
                if (input.value && parseFloat(input.value) > 0) {
                    filledCount++;
                }
            });

            // Update counter display
            const counterElement = document.getElementById('subject-counter');
            if (counterElement) {
                counterElement.textContent = filledCount;

                // Update warna counter
                if (filledCount >= 8) {
                    counterElement.className = 'badge bg-success';
                    document.getElementById('min-subjects-alert').classList.add('d-none');
                } else {
                    counterElement.className = 'badge bg-warning';
                    document.getElementById('min-subjects-alert').classList.remove('d-none');
                }
            }
        }

        function editGradeReport(reportId) {
            // Add edit functionality here
            Swal.fire({
                title: 'Fitur Dalam Pengembangan',
                text: 'Fitur edit akan segera tersedia',
                icon: 'info'
            });
        }

        function deleteGradeReport(reportId) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda yakin ingin menghapus raport ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Menghapus Raport...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Kirim request hapus
                    fetch(`/user/data/grades/${reportId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message || 'Raport berhasil dihapus',
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Gagal menghapus raport');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Gagal!',
                            text: error.message || 'Terjadi kesalahan saat menghapus raport',
                            icon: 'error'
                        });
                    });
                }
            });
        }

        // Hover effects for elements
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to buttons
            document.querySelectorAll('.btn').forEach(button => {
                button.addEventListener('mouseover', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1)';
                });

                button.addEventListener('mouseout', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
                });
            });

            // Inisialisasi counter saat halaman dimuat
            updateSubjectCounter();

            // Check if CSRF token exists
            if (!document.querySelector('meta[name="csrf-token"]')) {
                console.warn('CSRF token meta tag tidak ditemukan, menambahkan secara otomatis');
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = '{{ csrf_token() }}';
                document.head.appendChild(meta);
            }
        });
    </script>

    <style>
        .bg-orange {
            background-color: #fd7e14 !important;
        }

        .text-orange {
            color: #fd7e14 !important;
        }

        .subject-card {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .filled-subject {
            border-left: 3px solid #28a745;
            background-color: rgba(40, 167, 69, 0.05);
        }

        .required-subject {
            border-left: 3px solid #ffc107;
            background-color: rgba(255, 193, 7, 0.05);
        }

        .required-subject input {
            border: 1px solid #ffc107 !important;
        }

        .nilai-input:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
        }
    </style>
</x-app-layout>
