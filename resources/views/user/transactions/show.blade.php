{{-- filepath: /Users/yapi/project/ppdb-backend/resources/views/transactions/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="bi bi-receipt-cutoff me-2"></i>Detail Transaksi
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('user.transactions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                <button onclick="window.print()" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-printer me-1"></i>Print
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Transaction Header -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h3 class="fw-bold text-dark mb-2">
                                Invoice #{{ $payment->external_id }}
                            </h3>
                            <p class="text-muted mb-0">
                                Tanggal: {{ $payment->created_at->format('d F Y, H:i') }} WIB
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
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
                                    <i class="bi bi-x-circle me-1"></i>GAGAL
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- Payment Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Informasi Siswa</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted">Nama:</td>
                                    <td class="fw-semibold">{{ $payment->pendaftar->nama_murid }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. Pendaftaran:</td>
                                    <td><code>{{ $payment->pendaftar->no_pendaftaran }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jenjang:</td>
                                    <td><span class="badge bg-info">{{ $jenjangName }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Unit:</td>
                                    <td>{{ $payment->pendaftar->unit }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Informasi Pembayaran</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted">Amount:</td>
                                    <td class="fw-bold text-success fs-5">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Invoice ID:</td>
                                    <td><code>{{ $payment->invoice_id }}</code></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status:</td>
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
                                @if($payment->paid_at)
                                <tr>
                                    <td class="text-muted">Dibayar pada:</td>
                                    <td>{{ $payment->paid_at->format('d F Y, H:i') }} WIB</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Cart Items Detail (if available) -->
                    @if($payment->items)
                        @php
                            $items = json_decode($payment->items, true);
                        @endphp
                        @if($items && is_array($items) && count($items) > 0)
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-cart3 me-2"></i>Detail Tagihan
                                </h6>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <thead>
                                                    <tr class="border-bottom">
                                                        <th class="text-muted fw-normal">Item</th>
                                                        <th class="text-muted fw-normal text-end">Jumlah</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $subtotal = 0;
                                                    @endphp
                                                    @foreach($items as $item)
                                                        @php
                                                            $subtotal += $item['amount'] ?? 0;
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <div class="fw-semibold">{{ $item['name'] ?? 'Item' }}</div>
                                                                @if(isset($item['description']))
                                                                    <div class="text-muted small">{{ $item['description'] }}</div>
                                                                @endif
                                                            </td>
                                                            <td class="text-end fw-semibold">
                                                                Rp {{ number_format($item['amount'] ?? 0, 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    <!-- Calculation Summary -->
                                                    <tr class="border-top">
                                                        <td class="text-muted">Subtotal:</td>
                                                        <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                                    </tr>

                                                    @if($payment->promo_code)
                                                        <tr>
                                                            <td class="text-success">
                                                                <i class="bi bi-tag me-1"></i>Promo Code: {{ $payment->promo_code }}
                                                            </td>
                                                            <td class="text-end text-success">
                                                                @php
                                                                    $discount = $subtotal + 2500 - $payment->amount;
                                                                @endphp
                                                                @if($discount > 0)
                                                                    -Rp {{ number_format($discount, 0, ',', '.') }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @if($payment->discount_id)
                                                        <tr>
                                                            <td class="text-primary">
                                                                <i class="bi bi-percent me-1"></i>Discount Applied: {{ $payment->discount_id }}
                                                            </td>
                                                            <td class="text-end text-primary">
                                                                @php
                                                                    $discount = $subtotal + 2500 - $payment->amount;
                                                                @endphp
                                                                @if($discount > 0)
                                                                    -Rp {{ number_format($discount, 0, ',', '.') }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    <tr>
                                                        <td class="text-muted">Biaya Admin:</td>
                                                        <td class="text-end">Rp 2.500</td>
                                                    </tr>

                                                    <tr class="border-top">
                                                        <td class="fw-bold fs-5">Total:</td>
                                                        <td class="text-end fw-bold text-success fs-5">
                                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if($payment->status === 'PENDING' && $payment->invoice_url)
                        <!-- Payment Action -->
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">Pembayaran Belum Selesai</h6>
                                <p class="mb-2">Silakan lanjutkan pembayaran melalui link di bawah ini:</p>
                                <a href="{{ $payment->invoice_url }}" class="btn btn-warning" target="_blank">
                                    <i class="bi bi-credit-card me-2"></i>Lanjutkan Pembayaran
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($payment->status === 'PAID')
                        <!-- Success Message -->
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Pembayaran Berhasil!</h6>
                                <p class="mb-0">Terima kasih, pembayaran Anda telah berhasil diverifikasi.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
