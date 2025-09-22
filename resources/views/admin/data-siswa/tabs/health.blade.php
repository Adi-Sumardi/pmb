<!-- Health Tab Content -->
<div class="row g-4">
    <div class="col-12">

        @if($healthRecord)
            <!-- Health Information -->
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #ffebee, #ffcdd2); border-left: 5px solid #f44336;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-heart-pulse-fill me-2"></i>Informasi Kesehatan
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #fffafa;">
                    <div class="row g-4">
                        <!-- Physical Information -->
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-rulers me-2"></i>Data Fisik
                            </h6>
                            <div class="row g-3">
                                @if($healthRecord->tinggi_badan)
                                <div class="col-6">
                                    <label class="form-label fw-semibold" style="color: #36474f;">Tinggi Badan</label>
                                    <div class="fw-bold fs-5">
                                        <span class="badge bg-primary bg-opacity-15 text-primary border border-primary px-3 py-2">
                                            {{ $healthRecord->tinggi_badan }} cm
                                        </span>
                                    </div>
                                </div>
                                @endif

                                @if($healthRecord->berat_badan)
                                <div class="col-6">
                                    <label class="form-label fw-semibold" style="color: #36474f;">Berat Badan</label>
                                    <div class="fw-bold fs-5">
                                        <span class="badge bg-success bg-opacity-15 text-success border border-success px-3 py-2">
                                            {{ $healthRecord->berat_badan }} kg
                                        </span>
                                    </div>
                                </div>
                                @endif

                                @if($healthRecord->golongan_darah)
                                <div class="col-6">
                                    <label class="form-label fw-semibold" style="color: #36474f;">Golongan Darah</label>
                                    <div class="fw-bold fs-5">
                                        <span class="badge bg-danger bg-opacity-15 text-danger border border-danger px-3 py-2">
                                            {{ $healthRecord->golongan_darah }}
                                        </span>
                                    </div>
                                </div>
                                @endif

                                @if($healthRecord->riwayat_penyakit)
                                <div class="col-12">
                                    <label class="form-label fw-semibold" style="color: #36474f;">Riwayat Penyakit</label>
                                    <div class="bg-light rounded-3 p-3">
                                        <p class="mb-0">{{ $healthRecord->riwayat_penyakit }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Medical Information -->
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-capsule me-2"></i>Informasi Medis
                            </h6>
                            <div class="row g-3">
                                @if($healthRecord->alergi)
                                <div class="col-12">
                                    <label class="form-label fw-semibold" style="color: #36474f;">Alergi</label>
                                    <div class="bg-warning bg-opacity-10 rounded-3 p-3 border border-warning">
                                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                        <span class="fw-semibold">{{ $healthRecord->alergi }}</span>
                                    </div>
                                </div>
                                @endif

                                @if($healthRecord->obat_rutin)
                                <div class="col-12">
                                    <label class="form-label fw-semibold" style="color: #36474f;">Obat Rutin</label>
                                    <div class="bg-info bg-opacity-10 rounded-3 p-3 border border-info">
                                        <i class="bi bi-capsule text-info me-2"></i>
                                        <span class="fw-semibold">{{ $healthRecord->obat_rutin }}</span>
                                    </div>
                                </div>
                                @endif

                                @if($healthRecord->kondisi_khusus)
                                <div class="col-12">
                                    <label class="form-label fw-semibold" style="color: #36474f;">Kondisi Khusus</label>
                                    <div class="bg-secondary bg-opacity-10 rounded-3 p-3 border border-secondary">
                                        <i class="bi bi-info-circle text-secondary me-2"></i>
                                        <span class="fw-semibold">{{ $healthRecord->kondisi_khusus }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact for Health -->
            @if($healthRecord->kontak_darurat_kesehatan || $healthRecord->rumah_sakit_terdekat)
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #fff3e0, #ffcc02); border-left: 5px solid #ff9800;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-telephone-plus me-2"></i>Kontak Darurat Kesehatan
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #fffef7;">
                    <div class="row g-4">
                        @if($healthRecord->kontak_darurat_kesehatan)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Kontak Darurat</label>
                            <div class="fw-bold fs-5">
                                <a href="tel:{{ $healthRecord->kontak_darurat_kesehatan }}" class="text-decoration-none">
                                    <i class="bi bi-telephone-fill me-2 text-danger"></i>{{ $healthRecord->kontak_darurat_kesehatan }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($healthRecord->rumah_sakit_terdekat)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Rumah Sakit Terdekat</label>
                            <div class="fw-bold">
                                <i class="bi bi-hospital me-2 text-success"></i>{{ $healthRecord->rumah_sakit_terdekat }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Health Notes -->
            @if($healthRecord->catatan_kesehatan)
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #f3e5f5, #e1bee7); border-left: 5px solid #9c27b0;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-journal-medical me-2"></i>Catatan Kesehatan
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #fdfaff;">
                    <div class="bg-light rounded-3 p-4">
                        <p class="mb-0">{{ $healthRecord->catatan_kesehatan }}</p>
                    </div>
                </div>
            </div>
            @endif

        @else
            <!-- No Health Record -->
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-heart-pulse fs-1 text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">Data Kesehatan Belum Tersedia</h5>
                    <p class="text-muted mb-4">
                        Siswa belum melengkapi informasi kesehatan mereka.<br>
                        Data kesehatan penting untuk penanganan darurat dan perawatan di sekolah.
                    </p>

                    <div class="bg-light rounded-3 p-4 mx-auto" style="max-width: 600px;">
                        <h6 class="fw-bold mb-3">Informasi Kesehatan yang Diperlukan:</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-rulers text-primary me-2"></i>
                                    <small>Tinggi & Berat Badan</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-droplet text-danger me-2"></i>
                                    <small>Golongan Darah</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                    <small>Riwayat Alergi</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-capsule text-info me-2"></i>
                                    <small>Obat-obatan Rutin</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-journal-medical text-success me-2"></i>
                                    <small>Riwayat Penyakit</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-telephone text-purple me-2"></i>
                                    <small>Kontak Darurat</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="alert alert-info border-0 bg-primary bg-opacity-10">
                            <i class="bi bi-lightbulb me-2"></i>
                            <strong>Penting:</strong> Data kesehatan membantu sekolah memberikan perawatan yang tepat saat diperlukan.
                        </div>
                    </div>

                    <div class="mt-3">
                        <p class="text-muted small">
                            <i class="bi bi-shield-check me-1"></i>
                            Semua informasi kesehatan akan dijaga kerahasiaannya dan hanya digunakan untuk keperluan medis.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Health Tips -->
        <div class="card shadow-sm rounded-4 mt-4 border-0">
            <div class="card-header py-3" style="background: linear-gradient(to right, #e8f5e9, #c8e6c9); border-left: 5px solid #4caf50;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-lightbulb me-2"></i>Tips Kesehatan Siswa
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #f8fff8;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3 mt-1">
                                <i class="bi bi-cup-straw text-success"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Minum Air yang Cukup</h6>
                                <small class="text-muted">Pastikan siswa minum air putih minimal 8 gelas per hari</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3 mt-1">
                                <i class="bi bi-moon text-warning"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Tidur yang Cukup</h6>
                                <small class="text-muted">Siswa memerlukan tidur 8-10 jam setiap malam</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3 mt-1">
                                <i class="bi bi-heart text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Olahraga Teratur</h6>
                                <small class="text-muted">Aktivitas fisik minimal 30 menit setiap hari</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3 mt-1">
                                <i class="bi bi-apple text-info"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Makan Sehat</h6>
                                <small class="text-muted">Konsumsi buah, sayur, dan makanan bergizi seimbang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
