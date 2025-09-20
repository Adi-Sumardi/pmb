<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">
                    <i class="bi bi-bag-check me-2 text-purple"></i>
                    Multi Payment Management
                </h2>
                <p class="text-muted small mb-0">Kelola pembayaran buku, seragam, dan item lainnya</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.settings.multi-payments.create') }}" class="btn btn-purple">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Item
                </a>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Success & Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" data-aos="fade-down" data-aos-duration="500">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div>
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" data-aos="fade-down" data-aos-duration="500">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" data-aos="fade-down" data-aos-duration="500">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-normal mb-1">Total Item</h6>
                                <h4 class="fw-bold mb-0">{{ $totalMultiPayments }}</h4>
                            </div>
                            <div class="bg-purple bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-bag-check text-purple fs-5"></i>
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
                                <h6 class="text-muted fw-normal mb-1">Item Wajib</h6>
                                <h4 class="fw-bold mb-0 text-danger">{{ $mandatoryMultiPayments }}</h4>
                            </div>
                            <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-exclamation-circle text-danger fs-5"></i>
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
                                <h6 class="text-muted fw-normal mb-1">Item Opsional</h6>
                                <h4 class="fw-bold mb-0 text-info">{{ $optionalMultiPayments }}</h4>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-check-circle text-info fs-5"></i>
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
                                <h6 class="text-muted fw-normal mb-1">Total Nilai</h6>
                                <h4 class="fw-bold mb-0 text-purple">
                                    @if($totalMultiPayments > 0)
                                        {{ number_format($avgAmount / 1000000, 1) }}M
                                    @else
                                        0
                                    @endif
                                </h4>
                            </div>
                            <div class="bg-purple bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-calculator text-purple fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="500">
            <div class="card-header bg-white border-bottom py-3">
                <ul class="nav nav-tabs card-header-tabs" id="categoryTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $category === 'all' ? 'active' : '' }}"
                           href="{{ route('admin.settings.multi-payments.index', ['category' => 'all']) }}">
                            <i class="bi bi-grid me-1"></i>Semua ({{ $totalMultiPayments }})
                        </a>
                    </li>
                    @php
                        $categoryIcons = [
                            'buku' => 'book',
                            'seragam' => 'person-badge',
                            'perlengkapan' => 'bag',
                            'kegiatan' => 'calendar-event',
                            'teknologi' => 'laptop',
                        ];
                    @endphp
                    @foreach($categories as $cat => $count)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $category === $cat ? 'active' : '' }}"
                           href="{{ route('admin.settings.multi-payments.index', ['category' => $cat]) }}">
                            <i class="bi bi-{{ $categoryIcons[$cat] ?? 'bag-check' }} me-1"></i>{{ ucfirst($cat) }} ({{ $count }})
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold">#</th>
                                <th class="border-0 fw-semibold">Item</th>
                                <th class="border-0 fw-semibold">Kategori</th>
                                <th class="border-0 fw-semibold">Jenjang</th>
                                <th class="border-0 fw-semibold">Harga</th>
                                <th class="border-0 fw-semibold">Status</th>
                                <th class="border-0 fw-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($multiPayments as $index => $multiPayment)
                            <tr>
                                <td class="align-middle">{{ $multiPayments->firstItem() + $index }}</td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $categoryConfig = [
                                                'buku' => ['icon' => 'book', 'color' => 'primary'],
                                                'seragam' => ['icon' => 'person-badge', 'color' => 'success'],
                                                'perlengkapan' => ['icon' => 'bag', 'color' => 'warning'],
                                                'kegiatan' => ['icon' => 'calendar-event', 'color' => 'info'],
                                                'teknologi' => ['icon' => 'laptop', 'color' => 'purple'],
                                                    ];
                                                    $config = $categoryConfig[$multiPayment->category] ?? ['icon' => 'bag-check', 'color' => 'secondary'];
                                                @endphp
                                                <div class="bg-{{ $config['color'] }} bg-opacity-10 p-2 rounded me-3">
                                                    <i class="bi bi-{{ $config['icon'] }} text-{{ $config['color'] }}"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $multiPayment->name }}</div>
                                                    @if($multiPayment->description)
                                                        <small class="text-muted">{{ Str::limit($multiPayment->description, 40) }}</small>
                                                    @endif
                                                    @if($multiPayment->is_mandatory)
                                                        <span class="badge bg-danger ms-2" style="font-size: 0.65rem;">Wajib</span>
                                                    @else
                                                        <span class="badge bg-info ms-2" style="font-size: 0.65rem;">Opsional</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            @php
                                                $categoryBadges = [
                                                    'buku' => ['label' => 'Buku', 'color' => 'primary'],
                                                    'seragam' => ['label' => 'Seragam', 'color' => 'success'],
                                                    'perlengkapan' => ['label' => 'Perlengkapan', 'color' => 'warning'],
                                                    'kegiatan' => ['label' => 'Kegiatan', 'color' => 'info'],
                                                    'teknologi' => ['label' => 'Teknologi', 'color' => 'purple'],
                                                ];
                                                $badge = $categoryBadges[$multiPayment->category] ?? ['label' => ucfirst($multiPayment->category), 'color' => 'secondary'];
                                            @endphp
                                            <span class="badge bg-{{ $badge['color'] }}">{{ $badge['label'] }}</span>
                                        </td>
                                        <td class="align-middle">
                                            @php
                                                $levelColors = [
                                                    'tk' => 'success',
                                                    'sd' => 'info',
                                                    'smp' => 'primary',
                                                    'sma' => 'warning'
                                                ];
                                                $color = $levelColors[strtolower($multiPayment->school_level)] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">{{ strtoupper($multiPayment->school_level) }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <div class="fw-semibold">Rp {{ number_format($multiPayment->amount, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="align-middle">
                                            @if($multiPayment->status == 'active')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.settings.multi-payments.edit', $multiPayment) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   data-bs-toggle="tooltip"
                                                   title="Edit Item">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-btn"
                                                        data-id="{{ $multiPayment->id }}"
                                                        data-name="{{ $multiPayment->name }}"
                                                        data-bs-toggle="tooltip"
                                                        title="Hapus Item">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada data multi payment</h5>
                                                <p class="text-muted">Klik tombol "Tambah Item" untuk menambahkan data pertama</p>
                                                <a href="{{ route('admin.settings.multi-payments.create') }}" class="btn btn-purple">
                                                    <i class="bi bi-plus-circle me-1"></i>Tambah Item
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
                    <div class="text-muted">
                        @if($multiPayments->count() > 0)
                            Menampilkan {{ $multiPayments->firstItem() }} sampai {{ $multiPayments->lastItem() }}
                            dari {{ $multiPayments->total() }} data
                        @else
                            Tidak ada data untuk ditampilkan
                        @endif
                    </div>
                    <div>
                        {{ $multiPayments->appends(['category' => $category])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .text-purple { color: #6f42c1 !important; }
        .bg-purple { background-color: #6f42c1 !important; }
        .btn-purple {
            background-color: #6f42c1;
            border-color: #6f42c1;
            color: white;
        }
        .btn-purple:hover {
            background-color: #5a2d91;
            border-color: #5a2d91;
            color: white;
        }

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
            border-bottom: 2px solid #6f42c1;
            color: #6f42c1;
            font-weight: 600;
        }
    </style>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Main JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 600,
            easing: 'ease-in-out',
            once: true
        });

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Delete functionality with SweetAlert
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Item "${name}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Send delete request
                        fetch(`/admin/settings/multi-payments/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Terjadi kesalahan');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus data',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        });
                    }
                });
            });
        });
    });
    </script>
</x-app-layout>
