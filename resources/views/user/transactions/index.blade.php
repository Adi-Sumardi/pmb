{{-- filepath: /Users/yapi/project/ppdb-backend/resources/views/transactions/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="bi bi-receipt-cutoff me-2"></i>Riwayat Transaksi Pembayaran
            </h2>
            @if($payments->count() > 0)
            <a href="{{ route('user.payments.index') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Pembayaran Baru
            </a>
            @endif
        </div>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm rounded-3 border-0">
                    <div class="card-body p-4">
                        @if($payments->count() > 0)
                            <!-- Statistics Cards -->
                            <div class="row g-4 mb-5">
                                <div class="col-md-4">
                                    <div class="card bg-primary bg-gradient text-white shadow-sm rounded-4 border-0 h-100">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="card-title text-uppercase fw-light mb-2">Total Transaksi</h6>
                                                    <h3 class="mb-0 display-6 fw-bold">{{ $payments->total() }}</h3>
                                                </div>
                                                <div class="align-self-center">
                                                    <i class="bi bi-receipt-cutoff opacity-75" style="font-size: 3rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card bg-success bg-gradient text-white shadow-sm rounded-4 border-0 h-100">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="card-title text-uppercase fw-light mb-2">Pembayaran Lunas</h6>
                                                    <h3 class="mb-0 display-6 fw-bold">{{ $payments->where('status', 'PAID')->count() }}</h3>
                                                </div>
                                                <div class="align-self-center">
                                                    <i class="bi bi-check-circle opacity-75" style="font-size: 3rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card bg-warning bg-gradient text-white shadow-sm rounded-4 border-0 h-100">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="card-title text-uppercase fw-light mb-2">Menunggu Pembayaran</h6>
                                                    <h3 class="mb-0 display-6 fw-bold">{{ $payments->where('status', 'PENDING')->count() }}</h3>
                                                </div>
                                                <div class="align-self-center">
                                                    <i class="bi bi-clock-history opacity-75" style="font-size: 3rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Transactions Table -->
                            <div class="card shadow-sm border-0 rounded-3 mb-4">
                                <div class="card-header bg-light py-3">
                                    <h5 class="mb-0 fw-bold">
                                        <i class="bi bi-list-ul me-2"></i>Daftar Transaksi
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th class="px-4 py-3">External ID</th>
                                                    <th class="py-3">Nama Murid</th>
                                                    <th class="py-3">Unit</th>
                                                    <th class="py-3">Amount</th>
                                                    <th class="py-3">Jenis Transaksi</th>
                                                    <th class="py-3">Metode Pembayaran</th>
                                                    <th class="py-3">Status</th>
                                                    <th class="py-3">Tanggal</th>
                                                    <th class="py-3 text-end pe-4">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="border-0">
                                                @foreach($payments as $payment)
                                                <tr class="border-bottom">
                                                    <td class="px-4 py-3">
                                                        <code class="text-primary bg-light px-2 py-1 rounded-2">{{ $payment->external_id }}</code>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                <span class="fw-bold">{{ substr($payment->pendaftar->nama_murid, 0, 1) }}</span>
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">{{ $payment->pendaftar->nama_murid }}</div>
                                                                <small class="text-muted">{{ $payment->pendaftar->no_pendaftaran }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-semibold">{{ strtoupper($payment->pendaftar->unit) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                                    </td>
                                                    <!-- Transaction type column -->
                                                    <td>
                                                        @if(isset($payment->primary_type))
                                                            <span class="badge bg-{{ $payment->primary_type['color'] }} bg-opacity-10 text-{{ $payment->primary_type['color'] }} px-3 py-2 rounded-pill fw-semibold">
                                                                <i class="bi bi-{{ $payment->primary_type['icon'] }} me-1"></i>{{ $payment->primary_type['label'] }}
                                                            </span>
                                                            @if(count($payment->transaction_types) > 1)
                                                                <small class="d-block text-muted mt-1">+{{ count($payment->transaction_types) - 1 }} lainnya</small>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill fw-semibold">
                                                                <i class="bi bi-credit-card me-1"></i>Pembayaran
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <!-- Payment method column -->
                                                    <td>
                                                        @php
                                                            $paymentMethod = '';
                                                            $paymentChannel = '';
                                                            $badgeClass = 'bg-light text-secondary';
                                                            $icon = 'credit-card';

                                                            // Extract payment method from xendit_response
                                                            if (isset($payment->xendit_response['payment_method'])) {
                                                                $paymentMethod = $payment->xendit_response['payment_method'];
                                                                $paymentChannel = $payment->xendit_response['payment_channel'] ?? '';

                                                                // Set icon and badge based on payment method
                                                                if ($paymentMethod == 'EWALLET') {
                                                                    $icon = 'wallet2';
                                                                    $badgeClass = 'bg-info bg-opacity-10 text-info';
                                                                } elseif ($paymentMethod == 'BANK_TRANSFER' || $paymentMethod == 'VIRTUAL_ACCOUNT') {
                                                                    $icon = 'bank';
                                                                    $badgeClass = 'bg-primary bg-opacity-10 text-primary';
                                                                } elseif ($paymentMethod == 'QR_CODE' || $paymentMethod == 'QRIS') {
                                                                    $icon = 'qr-code';
                                                                    $badgeClass = 'bg-warning bg-opacity-10 text-warning';
                                                                } elseif ($paymentMethod == 'CREDIT_CARD') {
                                                                    $icon = 'credit-card';
                                                                    $badgeClass = 'bg-dark bg-opacity-10 text-dark';
                                                                } elseif ($paymentMethod == 'RETAIL_OUTLET') {
                                                                    $icon = 'shop';
                                                                    $badgeClass = 'bg-success bg-opacity-10 text-success';
                                                                }
                                                            }

                                                            // Display friendly name
                                                            $displayMethod = '';
                                                            if ($paymentMethod == 'EWALLET') {
                                                                $displayMethod = $paymentChannel ?: 'E-Wallet';
                                                            } elseif ($paymentMethod == 'BANK_TRANSFER' || $paymentMethod == 'VIRTUAL_ACCOUNT') {
                                                                $displayMethod = ($paymentChannel ?: 'VA') . ' Virtual Account';
                                                            } elseif ($paymentMethod == 'QR_CODE' || $paymentMethod == 'QRIS') {
                                                                $displayMethod = 'QRIS';
                                                            } elseif ($paymentMethod == 'CREDIT_CARD') {
                                                                $displayMethod = 'Kartu Kredit/Debit';
                                                            } elseif ($paymentMethod == 'RETAIL_OUTLET') {
                                                                $displayMethod = $paymentChannel ?: 'Retail Store';
                                                            } else {
                                                                if ($payment->status == 'PENDING') {
                                                                    $displayMethod = 'Menunggu pembayaran';
                                                                } else {
                                                                    $displayMethod = 'N/A';
                                                                }
                                                            }
                                                        @endphp

                                                        <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fw-semibold">
                                                            <i class="bi bi-{{ $icon }} me-1"></i>{{ $displayMethod }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($payment->status === 'PAID')
                                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold">
                                                                <i class="bi bi-check-circle-fill me-1"></i>Lunas
                                                            </span>
                                                        @elseif($payment->status === 'PENDING')
                                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-semibold">
                                                                <i class="bi bi-clock-fill me-1"></i>Menunggu
                                                            </span>
                                                        @elseif($payment->status === 'FAILED')
                                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-semibold">
                                                                <i class="bi bi-x-circle-fill me-1"></i>Gagal
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill fw-semibold">
                                                                {{ $payment->status }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-semibold">{{ $payment->created_at->format('d/m/Y') }}</span>
                                                            <small class="text-muted">{{ $payment->created_at->format('H:i') }} WIB</small>
                                                        </div>
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('user.transactions.show', $payment->id) }}"
                                                               class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2">
                                                                <i class="bi bi-eye me-1"></i> Detail
                                                            </a>
                                                            @if($payment->status === 'PENDING' && $payment->invoice_url)
                                                                <a href="{{ $payment->invoice_url }}"
                                                                   class="btn btn-sm btn-success rounded-pill px-3 me-2" target="_blank">
                                                                    <i class="bi bi-credit-card me-1"></i> Bayar
                                                                </a>
                                                            @endif
                                                            @if($payment->status === 'PAID')
                                                                <button onclick="printInvoice('{{ $payment->id }}')"
                                                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                                    <i class="bi bi-printer me-1"></i> Print
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer bg-white p-3">
                                    <!-- Pagination -->
                                    <div class="d-flex justify-content-center">
                                        {{ $payments->links() }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-5 my-5">
                                <div class="mb-4">
                                    <div class="d-inline-flex justify-content-center align-items-center rounded-circle bg-light p-4" style="width: 120px; height: 120px;">
                                        <i class="bi bi-receipt text-primary" style="font-size: 3.5rem;"></i>
                                    </div>
                                </div>
                                <h3 class="fw-bold mb-3">Belum Ada Transaksi</h3>
                                <p class="text-muted mb-4 col-md-6 mx-auto">
                                    Anda belum melakukan transaksi pembayaran. Mulai lakukan pembayaran untuk melanjutkan proses pendaftaran.
                                </p>
                                <a href="{{ route('user.payments.index') }}" class="btn btn-primary rounded-pill px-4 py-2">
                                    <i class="bi bi-credit-card me-2"></i>Lakukan Pembayaran
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Enhanced styling */
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }

        code {
            font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, monospace;
            font-size: 0.875rem;
        }

        /* Card hover effects */
        .card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        }

        /* Button hover improvements */
        .btn {
            transition: all 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        /* Typography improvements */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
        }

        /* Table row separation */
        .table tbody tr {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Pagination styling */
        .pagination {
            gap: 5px;
        }

        .page-item .page-link {
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <script>
        function printInvoice(paymentId) {
            // Redirect to detailed invoice page for printing
            window.open(`/transactions/${paymentId}?print=1`, '_blank');
        }
    </script>
</x-app-layout>
