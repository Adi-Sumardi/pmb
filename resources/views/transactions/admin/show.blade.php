{{-- filepath: /Users/yapi/project/ppdb-backend/resources/views/admin/transactions/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="bi bi-receipt-cutoff me-2"></i>Detail Transaksi
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                <button onclick="window.print()" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-printer me-1"></i>Print
                </button>
                @if($payment->status === 'PENDING')
                    <button onclick="confirmPayment()" class="btn btn-success btn-sm">
                        <i class="bi bi-check2 me-1"></i>Konfirmasi Manual
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="row g-4">
                <!-- Transaction Info -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-receipt me-2"></i>Informasi Transaksi
                                </h5>
                                @if($payment->status === 'PAID')
                                    <span class="badge bg-success fs-6 px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>LUNAS
                                    </span>
                                @elseif($payment->status === 'PENDING')
                                    <span class="badge bg-warning fs-6 px-3 py-2">
                                        <i class="bi bi-clock me-1"></i>MENUNGGU
                                    </span>
                                @else
                                    <span class="badge bg-danger fs-6 px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i>{{ $payment->status }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-muted fw-semibold">External ID:</td>
                                            <td><code>{{ $payment->external_id }}</code></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Invoice ID:</td>
                                            <td><code>{{ $payment->invoice_id }}</code></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Amount:</td>
                                            <td class="fw-bold text-success fs-5">
                                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Status:</td>
                                            <td>
                                                @if($payment->status === 'PAID')
                                                    <span class="badge bg-success">LUNAS</span>
                                                @elseif($payment->status === 'PENDING')
                                                    <span class="badge bg-warning">MENUNGGU</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $payment->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-muted fw-semibold">Dibuat:</td>
                                            <td>{{ $payment->created_at->format('d F Y, H:i') }} WIB</td>
                                        </tr>
                                        @if($payment->paid_at)
                                        <tr>
                                            <td class="text-muted fw-semibold">Dibayar:</td>
                                            <td class="text-success">{{ $payment->paid_at->format('d F Y, H:i') }} WIB</td>
                                        </tr>
                                        @endif
                                        @if($payment->invoice_url)
                                        <tr>
                                            <td class="text-muted fw-semibold">Invoice URL:</td>
                                            <td>
                                                <a href="{{ $payment->invoice_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i>Lihat Invoice
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="text-muted fw-semibold">Duration:</td>
                                            <td>
                                                @if($payment->paid_at)
                                                    <span class="text-success">
                                                        {{ $payment->created_at->diffForHumans($payment->paid_at, true) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">
                                                        {{ $payment->created_at->diffForHumans() }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Student Information -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-person me-2"></i>Informasi Siswa
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-muted fw-semibold">Nama Murid:</td>
                                            <td class="fw-bold">{{ $payment->pendaftar->nama_murid }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">No. Pendaftaran:</td>
                                            <td><code>{{ $payment->pendaftar->no_pendaftaran }}</code></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Jenjang:</td>
                                            <td>
                                                <span class="badge bg-info text-dark fs-6">{{ $jenjangName }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Unit:</td>
                                            <td>{{ $payment->pendaftar->unit }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-muted fw-semibold">Status Pembayaran:</td>
                                            <td>
                                                @if($payment->pendaftar->sudah_bayar_formulir)
                                                    <span class="badge bg-success">Sudah Bayar</span>
                                                @else
                                                    <span class="badge bg-warning">Belum Bayar</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Tanggal Daftar:</td>
                                            <td>{{ $payment->pendaftar->created_at->format('d F Y, H:i') }} WIB</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">User Email:</td>
                                            <td>{{ $payment->pendaftar->user->email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Alamat:</td>
                                            <td>{{ $payment->pendaftar->alamat ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions & Status -->
                <div class="col-lg-4">
                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-lightning me-2"></i>Quick Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($payment->status === 'PENDING')
                                    <button onclick="confirmPayment()" class="btn btn-success">
                                        <i class="bi bi-check2 me-2"></i>Konfirmasi Manual
                                    </button>
                                @endif

                                @if($payment->invoice_url)
                                    <a href="{{ $payment->invoice_url }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="bi bi-box-arrow-up-right me-2"></i>Lihat Invoice Xendit
                                    </a>
                                @endif

                                <a href="{{ route('admin.pendaftar.index') }}?search={{ $payment->pendaftar->no_pendaftaran }}"
                                   class="btn btn-outline-info">
                                    <i class="bi bi-person-lines-fill me-2"></i>Lihat Data Pendaftar
                                </a>

                                <button onclick="window.print()" class="btn btn-outline-secondary">
                                    <i class="bi bi-printer me-2"></i>Print Detail
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Timeline -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-clock-history me-2"></i>Timeline Pembayaran
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Invoice Dibuat</h6>
                                        <p class="text-muted mb-1">{{ $payment->created_at->format('d F Y, H:i') }}</p>
                                        <small class="text-muted">External ID: {{ $payment->external_id }}</small>
                                    </div>
                                </div>

                                @if($payment->paid_at)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Pembayaran Berhasil</h6>
                                        <p class="text-muted mb-1">{{ $payment->paid_at->format('d F Y, H:i') }}</p>
                                        <small class="text-success">Status: PAID</small>
                                    </div>
                                </div>
                                @else
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Menunggu Pembayaran</h6>
                                        <p class="text-muted mb-1">{{ $payment->created_at->diffForHumans() }}</p>
                                        <small class="text-warning">Status: {{ $payment->status }}</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    @if($payment->xendit_response)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-code-square me-2"></i>Xendit Response
                            </h6>
                        </div>
                        <div class="card-body">
                            <pre class="bg-light p-3 rounded small" style="max-height: 300px; overflow-y: auto;">{{ json_encode($payment->xendit_response, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmPaymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pembayaran Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle me-2"></i>Detail Pembayaran</h6>
                        <ul class="mb-0">
                            <li>Nama: <strong>{{ $payment->pendaftar->nama_murid }}</strong></li>
                            <li>Amount: <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></li>
                            <li>External ID: <strong>{{ $payment->external_id }}</strong></li>
                        </ul>
                    </div>
                    <p>Apakah Anda yakin ingin mengkonfirmasi pembayaran ini secara manual?</p>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Aksi ini akan:
                        <ul class="mb-0 mt-2">
                            <li>Mengubah status pembayaran menjadi LUNAS</li>
                            <li>Mengubah status pendaftar menjadi "Sudah Bayar"</li>
                            <li>Tidak dapat dibatalkan</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="confirmPaymentBtn">
                        <i class="bi bi-check2 me-1"></i>Ya, Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: -19px;
            top: 20px;
            height: calc(100% + 20px);
            width: 2px;
            background-color: #dee2e6;
        }

        .timeline-marker {
            position: absolute;
            left: -25px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px currentColor;
        }

        .timeline-content h6 {
            margin-bottom: 4px;
            font-size: 0.9rem;
        }

        .timeline-content p {
            margin-bottom: 2px;
            font-size: 0.85rem;
        }

        @media print {
            .btn, .card-header .badge, .modal {
                display: none !important;
            }
        }
    </style>

    <script>
        function confirmPayment() {
            const modal = new bootstrap.Modal(document.getElementById('confirmPaymentModal'));
            modal.show();
        }

        document.getElementById('confirmPaymentBtn').addEventListener('click', function() {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.transactions.confirm", $payment->id) }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            document.body.appendChild(form);
            form.submit();
        });
    </script>
</x-app-layout>
