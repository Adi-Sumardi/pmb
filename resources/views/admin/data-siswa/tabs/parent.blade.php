<!-- Parent Tab Content -->
<div class="row g-4">
    <div class="col-12">

        @if($parentDetail)
            <!-- Father Information -->
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #e3f2fd, #bbdefb); border-left: 5px solid #2196f3;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-person-fill me-2"></i>Informasi Ayah
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #f8fcff;">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Nama Lengkap</label>
                            <div class="fw-bold fs-5">{{ $parentDetail->nama_ayah ?? $student->nama_ayah ?? '-' }}</div>
                        </div>

                        @if($parentDetail->nik_ayah)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">NIK</label>
                            <div class="fw-bold">{{ $parentDetail->nik_ayah }}</div>
                        </div>
                        @endif

                        @if($parentDetail->tempat_lahir_ayah || $parentDetail->tanggal_lahir_ayah)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Tempat, Tanggal Lahir</label>
                            <div class="fw-bold">
                                {{ $parentDetail->tempat_lahir_ayah ?? '-' }}
                                @if($parentDetail->tanggal_lahir_ayah)
                                    , {{ \Carbon\Carbon::parse($parentDetail->tanggal_lahir_ayah)->format('d F Y') }}
                                    <small class="text-muted">({{ \Carbon\Carbon::parse($parentDetail->tanggal_lahir_ayah)->age }} tahun)</small>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->pendidikan_ayah)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Pendidikan</label>
                            <div class="fw-bold">
                                <span class="badge bg-primary bg-opacity-15 text-primary border border-primary px-3 py-2">
                                    {{ $parentDetail->pendidikan_ayah }}
                                </span>
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->pekerjaan_ayah)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Pekerjaan</label>
                            <div class="fw-bold">{{ $parentDetail->pekerjaan_ayah }}</div>
                        </div>
                        @endif

                        @if($parentDetail->penghasilan_ayah)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Penghasilan</label>
                            <div class="fw-bold">
                                <span class="badge bg-success bg-opacity-15 text-success border border-success px-3 py-2">
                                    Rp {{ number_format($parentDetail->penghasilan_ayah, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->telepon_ayah || $student->telp_ayah)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">No. Telepon</label>
                            <div class="fw-bold">
                                <a href="tel:{{ $parentDetail->telepon_ayah ?? $student->telp_ayah }}" class="text-decoration-none">
                                    <i class="bi bi-telephone me-1"></i>{{ $parentDetail->telepon_ayah ?? $student->telp_ayah }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->alamat_kantor_ayah)
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="color: #36474f;">Alamat Kantor</label>
                            <div class="fw-bold">{{ $parentDetail->alamat_kantor_ayah }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mother Information -->
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #f3e5f5, #e1bee7); border-left: 5px solid #9c27b0;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-person-dress me-2"></i>Informasi Ibu
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #fdfaff;">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Nama Lengkap</label>
                            <div class="fw-bold fs-5">{{ $parentDetail->nama_ibu ?? $student->nama_ibu ?? '-' }}</div>
                        </div>

                        @if($parentDetail->nik_ibu)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">NIK</label>
                            <div class="fw-bold">{{ $parentDetail->nik_ibu }}</div>
                        </div>
                        @endif

                        @if($parentDetail->tempat_lahir_ibu || $parentDetail->tanggal_lahir_ibu)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Tempat, Tanggal Lahir</label>
                            <div class="fw-bold">
                                {{ $parentDetail->tempat_lahir_ibu ?? '-' }}
                                @if($parentDetail->tanggal_lahir_ibu)
                                    , {{ \Carbon\Carbon::parse($parentDetail->tanggal_lahir_ibu)->format('d F Y') }}
                                    <small class="text-muted">({{ \Carbon\Carbon::parse($parentDetail->tanggal_lahir_ibu)->age }} tahun)</small>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->pendidikan_ibu)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Pendidikan</label>
                            <div class="fw-bold">
                                <span class="badge bg-purple bg-opacity-15 text-purple border border-purple px-3 py-2" style="color: #9c27b0 !important; border-color: #9c27b0 !important;">
                                    {{ $parentDetail->pendidikan_ibu }}
                                </span>
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->pekerjaan_ibu)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Pekerjaan</label>
                            <div class="fw-bold">{{ $parentDetail->pekerjaan_ibu }}</div>
                        </div>
                        @endif

                        @if($parentDetail->penghasilan_ibu)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Penghasilan</label>
                            <div class="fw-bold">
                                <span class="badge bg-success bg-opacity-15 text-success border border-success px-3 py-2">
                                    Rp {{ number_format($parentDetail->penghasilan_ibu, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->telepon_ibu || $student->telp_ibu)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">No. Telepon</label>
                            <div class="fw-bold">
                                <a href="tel:{{ $parentDetail->telepon_ibu ?? $student->telp_ibu }}" class="text-decoration-none">
                                    <i class="bi bi-telephone me-1"></i>{{ $parentDetail->telepon_ibu ?? $student->telp_ibu }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->alamat_kantor_ibu)
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="color: #36474f;">Alamat Kantor</label>
                            <div class="fw-bold">{{ $parentDetail->alamat_kantor_ibu }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Guardian Information -->
            @if($parentDetail->nama_wali)
            <div class="card shadow-sm rounded-4 mb-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #fff3e0, #ffcc02); border-left: 5px solid #ff9800;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-person-badge me-2"></i>Informasi Wali
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #fffef7;">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Nama Lengkap</label>
                            <div class="fw-bold fs-5">{{ $parentDetail->nama_wali }}</div>
                        </div>

                        @if($parentDetail->hubungan_wali)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Hubungan dengan Siswa</label>
                            <div class="fw-bold">
                                <span class="badge bg-warning bg-opacity-15 text-warning border border-warning px-3 py-2">
                                    {{ $parentDetail->hubungan_wali }}
                                </span>
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->nik_wali)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">NIK</label>
                            <div class="fw-bold">{{ $parentDetail->nik_wali }}</div>
                        </div>
                        @endif

                        @if($parentDetail->pekerjaan_wali)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Pekerjaan</label>
                            <div class="fw-bold">{{ $parentDetail->pekerjaan_wali }}</div>
                        </div>
                        @endif

                        @if($parentDetail->penghasilan_wali)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Penghasilan</label>
                            <div class="fw-bold">
                                <span class="badge bg-success bg-opacity-15 text-success border border-success px-3 py-2">
                                    Rp {{ number_format($parentDetail->penghasilan_wali, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->telepon_wali)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">No. Telepon</label>
                            <div class="fw-bold">
                                <a href="tel:{{ $parentDetail->telepon_wali }}" class="text-decoration-none">
                                    <i class="bi bi-telephone me-1"></i>{{ $parentDetail->telepon_wali }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->alamat_wali)
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="color: #36474f;">Alamat</label>
                            <div class="fw-bold">{{ $parentDetail->alamat_wali }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Family Information -->
            @if($parentDetail->jumlah_anak || $parentDetail->anak_ke || $parentDetail->status_dalam_keluarga)
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header py-3" style="background: linear-gradient(to right, #e8f5e9, #c8e6c9); border-left: 5px solid #4caf50;">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="bi bi-house-heart me-2"></i>Informasi Keluarga
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #f8fff8;">
                    <div class="row g-4">
                        @if($parentDetail->jumlah_anak)
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="color: #36474f;">Jumlah Anak</label>
                            <div class="fw-bold fs-5">
                                <span class="badge bg-info bg-opacity-15 text-info border border-info px-3 py-2">
                                    {{ $parentDetail->jumlah_anak }} anak
                                </span>
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->anak_ke)
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="color: #36474f;">Anak Ke</label>
                            <div class="fw-bold fs-5">
                                <span class="badge bg-primary bg-opacity-15 text-primary border border-primary px-3 py-2">
                                    Ke-{{ $parentDetail->anak_ke }}
                                </span>
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->status_dalam_keluarga)
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="color: #36474f;">Status dalam Keluarga</label>
                            <div class="fw-bold">
                                <span class="badge bg-success bg-opacity-15 text-success border border-success px-3 py-2">
                                    {{ $parentDetail->status_dalam_keluarga }}
                                </span>
                            </div>
                        </div>
                        @endif

                        @if($parentDetail->bahasa_dirumah)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="color: #36474f;">Bahasa di Rumah</label>
                            <div class="fw-bold">{{ $parentDetail->bahasa_dirumah }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        @else
            <!-- No Parent Detail -->
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-people fs-1 text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">Data Orang Tua Belum Lengkap</h5>
                    <p class="text-muted mb-4">
                        Informasi detail orang tua/wali belum tersedia.<br>
                        Data yang ada hanya informasi dasar dari pendaftaran.
                    </p>

                    <!-- Basic Parent Information from Registration -->
                    @if($student->nama_ayah || $student->nama_ibu)
                    <div class="card bg-light border-0 mx-auto mb-4" style="max-width: 600px;">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Informasi Dasar dari Pendaftaran:</h6>
                            <div class="row g-3 text-start">
                                @if($student->nama_ayah)
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">Nama Ayah</label>
                                    <div class="fw-bold">{{ $student->nama_ayah }}</div>
                                    @if($student->telp_ayah)
                                        <small class="text-muted">{{ $student->telp_ayah }}</small>
                                    @endif
                                </div>
                                @endif

                                @if($student->nama_ibu)
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">Nama Ibu</label>
                                    <div class="fw-bold">{{ $student->nama_ibu }}</div>
                                    @if($student->telp_ibu)
                                        <small class="text-muted">{{ $student->telp_ibu }}</small>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="bg-light rounded-3 p-4 mx-auto" style="max-width: 600px;">
                        <h6 class="fw-bold mb-3">Informasi yang Biasanya Dibutuhkan:</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person text-primary me-2"></i>
                                    <small>Data Lengkap Orang Tua</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-briefcase text-info me-2"></i>
                                    <small>Pekerjaan & Penghasilan</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-telephone text-success me-2"></i>
                                    <small>Kontak yang Dapat Dihubungi</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-house text-warning me-2"></i>
                                    <small>Alamat Rumah & Kantor</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-mortarboard text-purple me-2"></i>
                                    <small>Tingkat Pendidikan</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-badge text-danger me-2"></i>
                                    <small>Data Wali (jika ada)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-muted small">
                            <i class="bi bi-lightbulb me-1"></i>
                            Orang tua dapat melengkapi data melalui dashboard mereka atau saat mengisi formulir tambahan.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Emergency Contacts Summary -->
        <div class="card shadow-sm rounded-4 mt-4 border-0">
            <div class="card-header py-3" style="background: linear-gradient(to right, #ffebee, #ffcdd2); border-left: 5px solid #f44336;">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-telephone-plus me-2"></i>Kontak Darurat
                </h5>
            </div>
            <div class="card-body p-4" style="background-color: #fffafa;">
                <div class="row g-3">
                    @php
                        $emergencyContacts = [];

                        if ($parentDetail && $parentDetail->telepon_ayah) {
                            $emergencyContacts[] = ['name' => $parentDetail->nama_ayah ?? 'Ayah', 'phone' => $parentDetail->telepon_ayah, 'relation' => 'Ayah'];
                        } elseif ($student->telp_ayah) {
                            $emergencyContacts[] = ['name' => $student->nama_ayah ?? 'Ayah', 'phone' => $student->telp_ayah, 'relation' => 'Ayah'];
                        }

                        if ($parentDetail && $parentDetail->telepon_ibu) {
                            $emergencyContacts[] = ['name' => $parentDetail->nama_ibu ?? 'Ibu', 'phone' => $parentDetail->telepon_ibu, 'relation' => 'Ibu'];
                        } elseif ($student->telp_ibu) {
                            $emergencyContacts[] = ['name' => $student->nama_ibu ?? 'Ibu', 'phone' => $student->telp_ibu, 'relation' => 'Ibu'];
                        }

                        if ($parentDetail && $parentDetail->telepon_wali) {
                            $emergencyContacts[] = ['name' => $parentDetail->nama_wali, 'phone' => $parentDetail->telepon_wali, 'relation' => $parentDetail->hubungan_wali ?? 'Wali'];
                        }
                    @endphp

                    @if(count($emergencyContacts) > 0)
                        @foreach($emergencyContacts as $contact)
                        <div class="col-md-6">
                            <div class="bg-danger bg-opacity-10 rounded-3 p-3 border border-danger">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold text-danger">{{ $contact['name'] }}</div>
                                        <small class="text-muted">{{ $contact['relation'] }}</small>
                                    </div>
                                    <div>
                                        <a href="tel:{{ $contact['phone'] }}" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-telephone me-1"></i>{{ $contact['phone'] }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center py-4">
                            <i class="bi bi-telephone-x text-muted fs-1 mb-3"></i>
                            <p class="text-muted">Belum ada kontak darurat yang tersedia.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
