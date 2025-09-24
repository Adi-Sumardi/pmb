{{-- filepath: /Users/yapi/project/ppdb-backend/resources/views/transactions/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="bi bi-receipt-cutoff me-2"></i>Detail Transaksi
            </h2>
            <div class="d-flex gap-2 no-print">
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

                    <!-- Transaction Types Breakdown -->
                    @if(isset($payment->transaction_types) && count($payment->transaction_types) > 0)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-tags me-2"></i>Jenis Transaksi
                            </h6>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($payment->transaction_types as $type)
                                            <span class="badge bg-{{ $type['color'] }} bg-opacity-10 text-{{ $type['color'] }} px-3 py-2 rounded-pill fw-semibold">
                                                <i class="bi bi-{{ $type['icon'] }} me-1"></i>{{ $type['label'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Transaction Items Detail -->
                    @if(isset($payment->metadata['cart_items']) && count($payment->metadata['cart_items']) > 0)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-list-ul me-2"></i>Detail Pembayaran per Item
                            </h6>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <thead>
                                                <tr class="border-bottom">
                                                    <th class="text-muted fw-normal">Item</th>
                                                    <th class="text-muted fw-normal">Jenis</th>
                                                    <th class="text-muted fw-normal text-end">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $subtotal = 0;
                                                @endphp
                                                @foreach($payment->metadata['cart_items'] as $item)
                                                    @php
                                                        $subtotal += $item['amount'] ?? 0;
                                                        $bill = \App\Models\StudentBill::find($item['bill_id'] ?? 0);
                                                        $billTypeInfo = null;
                                                        if ($bill) {
                                                            // Simple mapping without reflection
                                                            $typeMap = [
                                                                'registration_fee' => ['label' => 'Formulir Pendaftaran', 'color' => 'primary', 'icon' => 'file-earmark-text'],
                                                                'uang_pangkal' => ['label' => 'Uang Pangkal', 'color' => 'success', 'icon' => 'piggy-bank'],
                                                                'spp' => ['label' => $bill->month ? 'SPP ' . date('F', mktime(0, 0, 0, $bill->month, 1)) : 'SPP', 'color' => 'info', 'icon' => 'calendar-month'],
                                                                'uniform' => ['label' => 'Seragam', 'color' => 'warning', 'icon' => 'person-square'],
                                                                'books' => ['label' => 'Buku', 'color' => 'secondary', 'icon' => 'book'],
                                                                'supplies' => ['label' => 'Alat Tulis', 'color' => 'dark', 'icon' => 'pencil'],
                                                                'activity' => ['label' => 'Kegiatan', 'color' => 'danger', 'icon' => 'activity'],
                                                                'other' => ['label' => 'Lainnya', 'color' => 'secondary', 'icon' => 'three-dots']
                                                            ];
                                                            $billTypeInfo = $typeMap[$bill->bill_type] ?? $typeMap['other'];
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="fw-semibold">{{ $item['name'] ?? 'Item' }}</div>
                                                            @if($bill && $bill->description)
                                                                <div class="text-muted small">{{ $bill->description }}</div>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($billTypeInfo)
                                                                <span class="badge bg-{{ $billTypeInfo['color'] }} bg-opacity-10 text-{{ $billTypeInfo['color'] }} px-2 py-1 rounded-pill small">
                                                                    <i class="bi bi-{{ $billTypeInfo['icon'] }} me-1"></i>{{ $billTypeInfo['label'] }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill small">
                                                                    <i class="bi bi-credit-card me-1"></i>Pembayaran
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end fw-semibold">
                                                            Rp {{ number_format($item['amount'] ?? 0, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr class="border-top">
                                                    <td colspan="2" class="text-end text-muted">Subtotal:</td>
                                                    <td class="text-end fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                                @if(isset($payment->metadata['applied_discount']))
                                                    <tr>
                                                        <td colspan="2" class="text-end text-muted">
                                                            Diskon ({{ $payment->metadata['applied_discount']['code'] ?? '' }}):
                                                        </td>
                                                        <td class="text-end text-success">
                                                            -Rp {{ number_format($payment->metadata['applied_discount']['discount_amount'] ?? 0, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if(isset($payment->metadata['transaction_fee']))
                                                    <tr>
                                                        <td colspan="2" class="text-end text-muted">Biaya Transaksi:</td>
                                                        <td class="text-end">Rp {{ number_format($payment->metadata['transaction_fee'] ?? 0, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endif
                                                <tr class="border-top">
                                                    <td colspan="2" class="text-end fw-bold">Total:</td>
                                                    <td class="text-end fw-bold text-success fs-5">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

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

    {{-- Print Styling --}}
    <style>
        @media print {
            /* Hide elements that shouldn't appear in print */
            .no-print,
            header,
            nav,
            .btn,
            .d-flex.gap-2,
            x-slot[name="header"] {
                display: none !important;
            }

            /* Optimize layout for print */
            body {
                font-size: 12px !important;
                line-height: 1.4 !important;
                color: #000 !important;
                background: white !important;
            }

            .container,
            .max-w-4xl {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 20px !important;
            }

            .bg-white,
            .dark\\:bg-gray-800 {
                background: white !important;
                box-shadow: none !important;
            }

            /* Ensure badges and status are visible */
            .badge {
                color: #000 !important;
                background: #f8f9fa !important;
                border: 1px solid #000 !important;
            }

            .badge.bg-success {
                background: #d4edda !important;
                border-color: #28a745 !important;
            }

            .badge.bg-warning {
                background: #fff3cd !important;
                border-color: #ffc107 !important;
            }

            .badge.bg-danger {
                background: #f8d7da !important;
                border-color: #dc3545 !important;
            }

            /* Table styling for print */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            table th,
            table td {
                border: 1px solid #000 !important;
                padding: 8px !important;
            }

            /* Hide interactive elements */
            .alert {
                page-break-inside: avoid;
            }

            /* Page breaks */
            .page-break {
                page-break-before: always;
            }

            /* Margin adjustments */
            @page {
                margin: 1in;
                size: A4;
            }
        }
    </style>

    @if(isset($isPrintMode) && $isPrintMode)
        {{-- Auto-trigger print dialog when in print mode --}}
        <script>
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            });
        </script>
    @endif
</x-app-layout>
