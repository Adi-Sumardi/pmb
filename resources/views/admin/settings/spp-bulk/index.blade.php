<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-calendar-range me-2 text-warning"></i>
                    SPP Bulk Payment Settings
                </h2>
                <p class="text-muted small mb-0">Kelola pembayaran SPP dalam jumlah bulan (3, 6, 12 bulan)</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.settings.spp-bulk.create') }}" class="btn btn-warning">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Pengaturan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">Total Pengaturan</h6>
                                <h4 class="fw-bold mb-0">{{ $sppBulkSettings->total() }}</h4>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-calendar-range text-warning fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">Aktif</h6>
                                <h4 class="fw-bold mb-0 text-success">{{ $sppBulkSettings->where('status', 'active')->count() }}</h4>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-check-circle text-success fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">Diskon Terbesar</h6>
                                <h4 class="fw-bold mb-0 text-warning">{{ $sppBulkSettings->max('discount_percentage') }}%</h4>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-percent text-warning fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">Rata-rata Periode</h6>
                                <h4 class="fw-bold mb-0 text-warning">6 Bulan</h4>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-calendar-month text-warning fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Period Tabs -->
        <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="500">
            <div class="card-header bg-white border-bottom py-3">
                <ul class="nav nav-tabs card-header-tabs" id="periodTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ !request('period') ? 'active' : '' }}"
                           href="{{ route('admin.settings.spp-bulk.index') }}">
                            <i class="bi bi-grid me-1"></i>Semua
                            ({{ \App\Models\SppBulkSetting::count() }})
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request('period') == '3' ? 'active' : '' }}"
                           href="{{ route('admin.settings.spp-bulk.index', ['period' => '3']) }}">
                            <i class="bi bi-calendar3 me-1"></i>3 Bulan
                            ({{ \App\Models\SppBulkSetting::where('months_count', 3)->count() }})
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request('period') == '6' ? 'active' : '' }}"
                           href="{{ route('admin.settings.spp-bulk.index', ['period' => '6']) }}">
                            <i class="bi bi-calendar-week me-1"></i>6 Bulan
                            ({{ \App\Models\SppBulkSetting::where('months_count', 6)->count() }})
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ request('period') == '12' ? 'active' : '' }}"
                           href="{{ route('admin.settings.spp-bulk.index', ['period' => '12']) }}">
                            <i class="bi bi-calendar-year me-1"></i>12 Bulan
                            ({{ \App\Models\SppBulkSetting::where('months_count', 12)->count() }})
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="sppTable">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold">#</th>
                                <th class="border-0 fw-semibold">Nama Pengaturan</th>
                                <th class="border-0 fw-semibold">Jenjang</th>
                                <th class="border-0 fw-semibold">Periode</th>
                                <th class="border-0 fw-semibold">Diskon</th>
                                <th class="border-0 fw-semibold">Penghematan/Tahun</th>
                                <th class="border-0 fw-semibold">Status</th>
                                <th class="border-0 fw-semibold">Aksi</th>
                            </tr>
                        </thead>
                                <tbody>
                                    @forelse ($sppBulkSettings as $index => $setting)
                                    <tr>
                                        <td class="align-middle">{{ $sppBulkSettings->firstItem() + $index }}</td>
                                        <td class="align-middle">
                                            <div>
                                                <div class="fw-semibold">{{ $setting->name }}</div>
                                                @if($setting->description)
                                                    <small class="text-muted">{{ Str::limit($setting->description, 40) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            @php
                                                $levelColors = [
                                                    'tk' => 'success',
                                                    'sd' => 'primary',
                                                    'smp' => 'info',
                                                    'sma' => 'warning'
                                                ];
                                                $color = $levelColors[strtolower($setting->school_level)] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">{{ strtoupper($setting->school_level) }}</span>
                                        </td>
                                        <td class="align-middle">
                                            @php
                                                $periodColors = [
                                                    3 => 'warning',
                                                    6 => 'info',
                                                    12 => 'success'
                                                ];
                                                $periodColor = $periodColors[$setting->months_count] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $periodColor }}">{{ $setting->months_count }} Bulan</span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="fw-semibold text-success">{{ $setting->discount_percentage }}%</div>
                                        </td>
                                        <td class="align-middle">
                                            @php
                                                $avgSavings = ($setting->min_payment_amount + $setting->max_payment_amount) / 2;
                                                $savings = $avgSavings * ($setting->discount_percentage / 100);
                                            @endphp
                                            <div class="fw-semibold">Rp {{ number_format($savings, 0, ',', '.') }}</div>
                                            <small class="text-muted">dari Rp {{ number_format($avgSavings, 0, ',', '.') }}</small>
                                        </td>
                                        <td class="align-middle">
                                            @if($setting->status == 'active')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.settings.spp-bulk.edit', $setting) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   data-bs-toggle="tooltip"
                                                   title="Edit Pengaturan">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-btn"
                                                        data-id="{{ $setting->id }}"
                                                        data-name="{{ $setting->name }}"
                                                        data-bs-toggle="tooltip"
                                                        title="Hapus Pengaturan">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada pengaturan SPP Bulk</h5>
                                                <p class="text-muted">Klik tombol "Tambah Pengaturan" untuk menambahkan data pertama</p>
                                                <a href="{{ route('admin.settings.spp-bulk.create') }}" class="btn btn-warning">
                                                    <i class="bi bi-plus-circle me-1"></i>Tambah Pengaturan
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $sppBulkSettings->firstItem() ?? 0 }} - {{ $sppBulkSettings->lastItem() ?? 0 }}
                        dari {{ $sppBulkSettings->total() }} data
                    </div>
                    <div>
                        {{ $sppBulkSettings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }

        .table th {
            font-weight: 600;
            color: #374151;
        }

        .card {
            border-radius: 12px;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375em 0.75em;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            padding: 0.75rem 1rem;
        }

        .nav-tabs .nav-link.active {
            background-color: transparent;
            border-bottom: 2px solid #ffc107;
            color: #ffc107;
            font-weight: 600;
        }
    </style>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Main JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true
        });

        // Delete confirmation with SweetAlert
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;

                Swal.fire({
                    title: 'Hapus Pengaturan SPP Bulk?',
                    text: `Pengaturan "${name}" akan dihapus permanen. Aksi ini tidak dapat dibatalkan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return fetch(`{{ url('admin/settings/spp-bulk') }}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Pengaturan SPP Bulk berhasil dihapus.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                });
            });
        });

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    </script>
</x-app-layout>
