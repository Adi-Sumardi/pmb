{{-- filepath: /Users/yapi/project/ppdb-backend/resources/views/transactions/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="bi bi-receipt me-2"></i>{{ __('Riwayat Transaksi Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($payments->count() > 0)
                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="card-title">Total Transaksi</h6>
                                                <h4 class="mb-0">{{ $payments->total() }}</h4>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="bi bi-receipt fs-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="card-title">Pembayaran Lunas</h6>
                                                <h4 class="mb-0">{{ $payments->where('status', 'PAID')->count() }}</h4>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="bi bi-check-circle fs-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="card-title">Menunggu Pembayaran</h6>
                                                <h4 class="mb-0">{{ $payments->where('status', 'PENDING')->count() }}</h4>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="bi bi-clock fs-1"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transactions Table -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-list-ul me-2"></i>Daftar Transaksi
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>External ID</th>
                                                <th>Nama Murid</th>
                                                <th>Unit</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($payments as $payment)
                                            <tr>
                                                <td>
                                                    <code class="text-primary">{{ $payment->external_id }}</code>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold">{{ $payment->pendaftar->nama_murid }}</div>
                                                    <small class="text-muted">{{ $payment->pendaftar->no_pendaftaran }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ strtoupper($payment->pendaftar->unit) }}</span>
                                                </td>
                                                <td class="fw-bold text-success">
                                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    @if($payment->status === 'PAID')
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle me-1"></i>Lunas
                                                        </span>
                                                    @elseif($payment->status === 'PENDING')
                                                        <span class="badge bg-warning">
                                                            <i class="bi bi-clock me-1"></i>Menunggu
                                                        </span>
                                                    @elseif($payment->status === 'FAILED')
                                                        <span class="badge bg-danger">
                                                            <i class="bi bi-x-circle me-1"></i>Gagal
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $payment->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>{{ $payment->created_at->format('d/m/Y') }}</div>
                                                    <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('transactions.show', $payment->id) }}"
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye"></i> Detail
                                                        </a>
                                                        @if($payment->status === 'PENDING' && $payment->invoice_url)
                                                            <a href="{{ $payment->invoice_url }}"
                                                               class="btn btn-sm btn-success" target="_blank">
                                                                <i class="bi bi-credit-card"></i> Bayar
                                                            </a>
                                                        @endif
                                                        @if($payment->status === 'PAID')
                                                            <button onclick="printInvoice('{{ $payment->id }}')"
                                                                    class="btn btn-sm btn-outline-secondary">
                                                                <i class="bi bi-printer"></i> Print
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $payments->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mt-3">Belum Ada Transaksi</h4>
                            <p class="text-muted">Anda belum melakukan transaksi pembayaran.</p>
                            <a href="{{ route('payment.index') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-credit-card me-2"></i>Lakukan Pembayaran
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.75rem;
        }

        code {
            background-color: #f8f9fa;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }
    </style>

    <script>
        function printInvoice(paymentId) {
            // Redirect to detailed invoice page for printing
            window.open(`/transactions/${paymentId}?print=1`, '_blank');
        }
    </script>
</x-app-layout>
