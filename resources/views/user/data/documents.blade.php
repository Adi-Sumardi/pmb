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
                                    <i class="bi bi-file-earmark-arrow-up me-2"></i>
                                    Dokumen Pendukung
                                </h3>
                                <p class="text-white opacity-75 mb-0">Upload dokumen-dokumen penting untuk melengkapi pendaftaran</p>
                            </div>
                            <div class="col-auto">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-white text-opacity-75">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('user.data') }}" class="text-white text-opacity-75">Kelengkapan Data</a></li>
                                        <li class="breadcrumb-item active text-white">Dokumen</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Upload Form -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg rounded-4 sticky-top">
                    <div class="card-header py-3" style="background: linear-gradient(to right, #ffebee, #ffcdd2); border-left: 5px solid #f44336;">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-cloud-upload me-2"></i>
                            Upload Dokumen Baru
                        </h5>
                    </div>
                    <div class="card-body p-4" style="background-color: #fff5f5;">
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

                        <form action="{{ route('user.data.documents.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation">
                            @csrf

                            <div class="mb-3">
                                <label for="document_type" class="form-label fw-semibold" style="color: #36474f;">
                                    Jenis Dokumen <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg rounded-3 border-0 shadow-sm @error('document_type') is-invalid @enderror"
                                        id="document_type" name="document_type" required>
                                    <option value="">Pilih Jenis Dokumen</option>
                                    <option value="Kartu Keluarga">Kartu Keluarga (KK)</option>
                                    <option value="Akta Kelahiran">Akta Kelahiran</option>
                                    <option value="Ijazah">Ijazah</option>
                                    <option value="SKHUN">SKHUN</option>
                                    <option value="Raport">Raport</option>
                                    <option value="Pas Foto">Pas Foto</option>
                                    <option value="KTP Ayah">KTP Ayah</option>
                                    <option value="KTP Ibu">KTP Ibu</option>
                                    <option value="KTP Wali">KTP Wali</option>
                                    <option value="Surat Keterangan Sehat">Surat Keterangan Sehat</option>
                                    <option value="Surat Vaksin">Surat Vaksin</option>
                                    <option value="Surat Keterangan Kelakuan Baik">Surat Keterangan Kelakuan Baik</option>
                                    <option value="Sertifikat Prestasi">Sertifikat Prestasi</option>
                                    <option value="Surat Tidak Mampu">Surat Keterangan Tidak Mampu</option>
                                    <option value="Surat Yatim Piatu">Surat Keterangan Yatim/Piatu</option>
                                    <option value="Lainnya">Dokumen Lainnya</option>
                                </select>
                                @error('document_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="document_name" class="form-label fw-semibold" style="color: #36474f;">
                                    Nama Dokumen <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('document_name') is-invalid @enderror"
                                       id="document_name" name="document_name"
                                       placeholder="Contoh: Kartu Keluarga Budi Santoso" required>
                                @error('document_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="file" class="form-label fw-semibold" style="color: #36474f;">
                                    File Dokumen <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control form-control-lg rounded-3 border-0 shadow-sm @error('file') is-invalid @enderror"
                                       id="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Format: PDF, JPG, JPEG, PNG. Maksimal 2MB.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-danger btn-lg w-100">
                                <i class="bi bi-upload me-2"></i>Upload Dokumen
                            </button>
                        </form>

                        <!-- Requirements Info -->
                        <div class="mt-4 p-3 rounded-3" style="background-color: #fff8e1; border-left: 4px solid #ffc107;">
                            <h6 class="fw-bold text-primary">Dokumen Wajib:</h6>
                            <ul class="small text-muted mb-0">
                                <li>Kartu Keluarga (KK)</li>
                                <li>Akta Kelahiran</li>
                                <li>Ijazah/SKHUN</li>
                                <li>Pas Foto</li>
                                <li>KTP Orang Tua</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents List -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-header py-3" style="background: linear-gradient(to right, #fff8e1, #ffecb3); border-left: 5px solid #ffc107;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="bi bi-files me-2"></i>
                                Dokumen yang Sudah Diupload
                            </h5>
                            <span class="badge bg-primary">{{ count($documents) }} Dokumen</span>
                        </div>
                    </div>
                    <div class="card-body p-0" style="background-color: #fffbf2;">
                        @if(count($documents) > 0)
                            <div class="list-group list-group-flush">
                                @foreach($documents as $document)
                                <div class="list-group-item border-0 p-4 document-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="document-icon text-center">
                                                @if(str_contains($document->mime_type ?? '', 'pdf'))
                                                    <div class="icon-wrapper bg-danger bg-opacity-10 text-danger p-3 rounded-circle">
                                                        <i class="bi bi-file-earmark-pdf fs-2"></i>
                                                    </div>
                                                @elseif(str_contains($document->mime_type ?? '', 'image'))
                                                    <div class="icon-wrapper bg-success bg-opacity-10 text-success p-3 rounded-circle">
                                                        <i class="bi bi-file-earmark-image fs-2"></i>
                                                    </div>
                                                @else
                                                    <div class="icon-wrapper bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                                                        <i class="bi bi-file-earmark-text fs-2"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="mb-1 fw-bold">{{ $document->document_name ?? 'Dokumen' }}</h6>
                                            <p class="text-muted small mb-1">
                                                <span class="badge bg-secondary">{{ $document->document_type ?? 'Dokumen' }}</span>
                                            </p>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-calendar me-1"></i>{{ isset($document->created_at) ? $document->created_at->format('d M Y, H:i') : 'Tidak diketahui' }}
                                                <span class="ms-2">
                                                    <i class="bi bi-hdd me-1"></i>{{ isset($document->file_size) ? number_format($document->file_size / 1024, 2) : '0' }} KB
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ isset($document->file_path) ? Storage::url($document->file_path) : '#' }}"
                                                   target="_blank" class="btn btn-outline-primary btn-sm rounded-3 me-1">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ isset($document->file_path) ? Storage::url($document->file_path) : '#' }}"
                                                   download class="btn btn-outline-success btn-sm rounded-3 me-1">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm rounded-3"
                                                        onclick="deleteDocument({{ $document->id ?? 0 }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-file-earmark-x text-muted" style="font-size: 4rem;"></i>
                                <h5 class="text-muted mt-3">Belum Ada Dokumen</h5>
                                <p class="text-muted">Upload dokumen pertama Anda menggunakan form di sebelah kiri.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Progress Card -->
                @if(count($documents) > 0)
                <div class="card border-0 shadow-lg rounded-4 mt-4">
                    <div class="card-header py-3" style="background: linear-gradient(to right, #e8f5e9, #c8e6c9); border-left: 5px solid #4caf50;">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-check-circle me-2"></i>Progress Upload Dokumen
                        </h5>
                    </div>
                    <div class="card-body p-4" style="background-color: #f5fff8;">
                        @php
                            $requiredDocs = ['Kartu Keluarga', 'Akta Kelahiran', 'Ijazah', 'Pas Foto', 'KTP Ayah', 'KTP Ibu'];
                            $uploadedTypes = $documents->pluck('document_type')->toArray();
                            $completedCount = 0;
                            foreach($requiredDocs as $reqDoc) {
                                if(in_array($reqDoc, $uploadedTypes)) $completedCount++;
                            }
                            $progressPercent = ($completedCount / count($requiredDocs)) * 100;
                        @endphp

                        <div class="progress mb-3 rounded-pill" style="height: 12px;">
                            <div class="progress-bar bg-success rounded-pill progress-bar-striped progress-bar-animated" style="width: {{ $progressPercent }}%"></div>
                        </div>

                        <div class="row g-3 mt-2">
                            @foreach($requiredDocs as $reqDoc)
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center p-2 rounded-3 {{ in_array($reqDoc, $uploadedTypes) ? 'bg-success bg-opacity-10' : 'bg-light' }}">
                                    <span class="fw-medium {{ in_array($reqDoc, $uploadedTypes) ? 'text-success' : 'text-muted' }}">{{ $reqDoc }}</span>
                                    @if(in_array($reqDoc, $uploadedTypes))
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-circle text-muted"></i>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            <div class="badge bg-{{ $progressPercent == 100 ? 'success' : 'warning' }} p-2">
                                <i class="bi {{ $progressPercent == 100 ? 'bi-check-circle' : 'bi-exclamation-triangle' }} me-1"></i>
                                {{ $completedCount }}/{{ count($requiredDocs) }} dokumen wajib telah diupload
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('user.data') }}" class="btn btn-outline-secondary btn-lg px-4" style="transition: all 0.3s">
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

        function deleteDocument(id) {
            console.log('Menghapus dokumen dengan ID:', id);

            // Periksa apakah SweetAlert tersedia
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert tidak tersedia, menggunakan konfirmasi standar');
                if (confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
                    processDelete(id);
                }
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Anda yakin ingin menghapus dokumen ini?",
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
                        title: 'Menghapus Dokumen...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Panggil fungsi untuk proses delete
                    processDelete(id);
                }
            });
        }

        function processDelete(id) {
            // Dapatkan CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            // Log untuk debugging
            console.log('CSRF Token tersedia:', csrfToken ? 'Ya' : 'Tidak');
            console.log('URL delete:', `/user/data/documents/${id}`);

            // Kirim request hapus
            fetch(`/user/data/documents/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message || 'Dokumen berhasil dihapus',
                            icon: 'success'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        alert('Berhasil: ' + (data.message || 'Dokumen berhasil dihapus'));
                        location.reload();
                    }
                } else {
                    throw new Error(data.message || 'Gagal menghapus dokumen');
                }
            })
            .catch(error => {
                console.error('Detailed Error:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Gagal!',
                        text: error.message || 'Terjadi kesalahan saat menghapus dokumen',
                        icon: 'error'
                    });
                } else {
                    alert('Gagal: ' + (error.message || 'Terjadi kesalahan saat menghapus dokumen'));
                }
            });
        }

        // Auto fill document name based on type
        document.getElementById('document_type').addEventListener('change', function() {
            const docName = document.getElementById('document_name');
            if (this.value && !docName.value) {
                docName.value = this.value;
            }
        });

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

            // Add hover effects to document items
            document.querySelectorAll('.document-item').forEach(item => {
                item.addEventListener('mouseover', function() {
                    this.style.backgroundColor = '#f8f9fa';
                    this.style.transform = 'translateX(5px)';
                });

                item.addEventListener('mouseout', function() {
                    this.style.backgroundColor = '';
                    this.style.transform = 'translateX(0)';
                });
            });

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
        .document-item {
            transition: all 0.3s ease;
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .document-item:hover .icon-wrapper {
            transform: scale(1.1);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</x-app-layout>
