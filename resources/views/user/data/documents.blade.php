<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-file-earmark-arrow-up me-2 text-primary"></i>
                Dokumen Pendukung
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.data') }}">Kelengkapan Data</a></li>
                    <li class="breadcrumb-item active">Dokumen</li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row">
            <!-- Upload Form -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm sticky-top">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-cloud-upload me-2"></i>
                            Upload Dokumen Baru
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('user.data.documents.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="document_type" class="form-label fw-semibold">
                                    Jenis Dokumen <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('document_type') is-invalid @enderror"
                                        id="document_type" name="document_type" required>
                                    <option value="">Pilih Jenis Dokumen</option>
                                    <option value="Kartu Keluarga">Kartu Keluarga (KK)</option>
                                    <option value="Akta Kelahiran">Akta Kelahiran</option>
                                    <option value="Ijazah">Ijazah</option>
                                    <option value="SKHUN">SKHUN</option>
                                    <option value="Raport">Raport</option>
                                    <option value="Pas Foto">Pas Foto</option>
                                    <option value="KTP Orang Tua">KTP Orang Tua</option>
                                    <option value="Surat Keterangan Sehat">Surat Keterangan Sehat</option>
                                    <option value="Surat Keterangan Kelakuan Baik">Surat Keterangan Kelakuan Baik</option>
                                    <option value="Sertifikat Prestasi">Sertifikat Prestasi</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                @error('document_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="document_name" class="form-label fw-semibold">
                                    Nama Dokumen <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('document_name') is-invalid @enderror"
                                       id="document_name" name="document_name"
                                       placeholder="Contoh: Kartu Keluarga Budi Santoso" required>
                                @error('document_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="file" class="form-label fw-semibold">
                                    File Dokumen <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control @error('file') is-invalid @enderror"
                                       id="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Format: PDF, JPG, JPEG, PNG. Maksimal 2MB.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-upload me-2"></i>Upload Dokumen
                            </button>
                        </form>

                        <!-- Requirements Info -->
                        <div class="mt-4">
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
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-files me-2"></i>
                                Dokumen yang Sudah Diupload
                            </h5>
                            <span class="badge bg-primary">{{ count($documents) }} Dokumen</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if(count($documents) > 0)
                            <div class="list-group list-group-flush">
                                @foreach($documents as $document)
                                <div class="list-group-item border-0 p-4">
                                    <div class="row align-items-center">
                                        <div class="col-2">
                                            <div class="file-icon text-center">
                                                @if(str_contains($document->mime_type, 'pdf'))
                                                    <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 2rem;"></i>
                                                @else
                                                    <i class="bi bi-file-earmark-image text-success" style="font-size: 2rem;"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-7">
                                            <h6 class="mb-1 fw-bold">{{ $document->document_name }}</h6>
                                            <p class="text-muted small mb-1">
                                                <span class="badge bg-secondary">{{ $document->document_type }}</span>
                                            </p>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-calendar me-1"></i>{{ $document->created_at->format('d M Y, H:i') }}
                                                <span class="ms-2">
                                                    <i class="bi bi-hdd me-1"></i>{{ number_format($document->file_size / 1024, 2) }} KB
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-3 text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ Storage::url($document->file_path) }}"
                                                   target="_blank" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ Storage::url($document->file_path) }}"
                                                   download class="btn btn-outline-success btn-sm">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                        onclick="deleteDocument({{ $document->id }})">
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
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Progress Upload Dokumen</h6>
                        @php
                            $requiredDocs = ['Kartu Keluarga', 'Akta Kelahiran', 'Ijazah', 'Pas Foto', 'KTP Orang Tua'];
                            $uploadedTypes = $documents->pluck('document_type')->toArray();
                            $completedCount = 0;
                            foreach($requiredDocs as $reqDoc) {
                                if(in_array($reqDoc, $uploadedTypes)) $completedCount++;
                            }
                            $progressPercent = ($completedCount / count($requiredDocs)) * 100;
                        @endphp

                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: {{ $progressPercent }}%"></div>
                        </div>

                        <div class="row g-2">
                            @foreach($requiredDocs as $reqDoc)
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small">{{ $reqDoc }}</span>
                                    @if(in_array($reqDoc, $uploadedTypes))
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    @else
                                        <i class="bi bi-circle text-muted"></i>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-3">
                            <small class="text-muted">{{ $completedCount }}/{{ count($requiredDocs) }} dokumen wajib telah diupload</small>
                        </div>
                    </div>
                </div>
                @endif
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
        function deleteDocument(documentId) {
            if (confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
                // Add delete functionality here
                fetch(`/user/data/documents/${documentId}`, {
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
                        alert('Gagal menghapus dokumen');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
            }
        }

        // Auto fill document name based on type
        document.getElementById('document_type').addEventListener('change', function() {
            const docName = document.getElementById('document_name');
            if (this.value && !docName.value) {
                docName.value = this.value;
            }
        });
    </script>
</x-app-layout>
