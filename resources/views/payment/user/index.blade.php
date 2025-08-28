{{-- filepath: /Users/yapi/project/ppdb-backend/resources/views/payment/user/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-receipt me-2 text-primary"></i>
                Tagihan Pembayaran
            </h2>
            <div class="d-flex align-items-center text-muted">
                <i class="bi bi-calendar3 me-2"></i>
                <span>{{ now()->format('d F Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Invoice Header -->
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-body p-5">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-mortarboard-fill text-primary me-3" style="font-size: 2.5rem;"></i>
                                    <div>
                                        <h3 class="fw-bold text-primary mb-0">PPDB YAPI</h3>
                                        <small class="text-muted">Penerimaan Peserta Didik Baru</small>
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    <div><i class="bi bi-geo-alt me-1"></i>Jl. Pendidikan No. 123, Jakarta</div>
                                    <div><i class="bi bi-telephone me-1"></i>+62 21 1234567</div>
                                    <div><i class="bi bi-envelope me-1"></i>info@ppdb-yapi.com</div>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="mb-3">
                                    <h2 class="fw-bold text-dark mb-1">TAGIHAN</h2>
                                    @if($pendaftar->sudah_bayar_formulir)
                                        <div class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 fs-6">
                                            <i class="bi bi-check-circle-fill me-1"></i>LUNAS
                                        </div>
                                    @else
                                        <div class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 fs-6">
                                            <i class="bi bi-clock me-1"></i>BELUM DIBAYAR
                                        </div>
                                    @endif
                                </div>
                                <div class="text-muted small">
                                    <div><strong>No. Tagihan:</strong> INV-{{ date('Ymd') }}-{{ str_pad($pendaftar->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    <div><strong>Tanggal:</strong> {{ now()->format('d F Y') }}</div>
                                    <div><strong>Jatuh Tempo:</strong> {{ now()->addDays(7)->format('d F Y') }}</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Customer Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-3">
                                    <i class="bi bi-person-circle me-2"></i>Tagihan Untuk:
                                </h6>
                                <div class="bg-light rounded p-3">
                                    <div class="mb-2"><strong>Nama:</strong> {{ $pendaftar->nama_murid }}</div>
                                    <div class="mb-2"><strong>No. Pendaftaran:</strong>
                                        <span class="badge bg-primary">{{ $pendaftar->no_pendaftaran }}</span>
                                    </div>
                                    <div class="mb-2"><strong>Email:</strong> {{ Auth::user()->email }}</div>
                                    <div class="mb-2"><strong>Unit Tujuan:</strong> {{ $pendaftar->unit }}</div>
                                    <div><strong>Jenjang:</strong>
                                        <span class="badge bg-info">
                                            @switch($pendaftar->jenjang)
                                                @case('sanggar') Sanggar Bermain @break
                                                @case('kelompok') Kelompok Bermain @break
                                                @case('tka') TK A @break
                                                @case('tkb') TK B @break
                                                @case('sd') SD @break
                                                @case('smp') SMP @break
                                                @case('sma') SMA @break
                                                @default {{ strtoupper($pendaftar->jenjang) }}
                                            @endswitch
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-3">
                                    <i class="bi bi-info-circle me-2"></i>Status Pendaftaran:
                                </h6>
                                <div class="bg-light rounded p-3">
                                    <div class="mb-2">
                                        <strong>Status Verifikasi:</strong>
                                        @if($pendaftar->status === 'diverifikasi')
                                            <span class="badge bg-success ms-1">Diverifikasi</span>
                                        @else
                                            <span class="badge bg-warning ms-1">Pending</span>
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <strong>Tahun Ajaran:</strong> {{ date('Y') }}/{{ date('Y') + 1 }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Gelombang:</strong> I (Satu)
                                    </div>
                                    <div><strong>Total Biaya:</strong>
                                        <span class="fw-bold text-primary">Rp {{ number_format($pendaftar->payment_amount ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="bi bi-clipboard-data me-2"></i>Rincian Tagihan:
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-primary">
                                        <tr>
                                            <th scope="col" class="text-center">#</th>
                                            <th scope="col">Deskripsi</th>
                                            <th scope="col" class="text-center">Qty</th>
                                            <th scope="col" class="text-end">Harga Satuan</th>
                                            <th scope="col" class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>
                                                <div class="fw-semibold">Biaya Pendaftaran PPDB
                                                    @switch($pendaftar->jenjang)
                                                        @case('sanggar') Sanggar Bermain @break
                                                        @case('kelompok') Kelompok Bermain @break
                                                        @case('tka') TK A @break
                                                        @case('tkb') TK B @break
                                                        @case('sd') SD @break
                                                        @case('smp') SMP @break
                                                        @case('sma') SMA @break
                                                        @default {{ strtoupper($pendaftar->jenjang) }}
                                                    @endswitch
                                                </div>
                                                <small class="text-muted">
                                                    Biaya pendaftaran peserta didik baru jenjang
                                                    @switch($pendaftar->jenjang)
                                                        @case('sanggar') Sanggar Bermain @break
                                                        @case('kelompok') Kelompok Bermain @break
                                                        @case('tka') TK A @break
                                                        @case('tkb') TK B @break
                                                        @case('sd') SD @break
                                                        @case('smp') SMP @break
                                                        @case('sma') SMA @break
                                                        @default {{ strtoupper($pendaftar->jenjang) }}
                                                    @endswitch
                                                    tahun ajaran {{ date('Y') }}/{{ date('Y') + 1 }}
                                                </small>
                                            </td>
                                            <td class="text-center">1</td>
                                            <td class="text-end">Rp {{ number_format($pendaftar->payment_amount ?? 0, 0, ',', '.') }}</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($pendaftar->payment_amount ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-light">
                                            <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($pendaftar->payment_amount ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr class="table-light">
                                            <td colspan="4" class="text-end fw-bold">Admin Fee:</td>
                                            <td class="text-end fw-bold">Rp 0</td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td colspan="4" class="text-end fw-bold h5 mb-0">TOTAL TAGIHAN:</td>
                                            <td class="text-end fw-bold h5 mb-0 text-primary">Rp {{ number_format($pendaftar->payment_amount ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Payment Amount Summary -->
                        <div class="alert alert-info mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-1">
                                        <i class="bi bi-info-circle me-1"></i>Rincian Biaya per Jenjang
                                    </h6>
                                    <div class="small">
                                        <div class="row">
                                            <div class="col-6">
                                                • Sanggar & Kelompok Bermain: Rp 325000<br>
                                                • TK A & TK B: Rp 355000<br>
                                                • SD: Rp 425000
                                            </div>
                                            <div class="col-6">
                                                • SMP: Rp 455000<br>
                                                • SMA: Rp 525000
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <div class="text-muted small">Biaya untuk jenjang:</div>
                                    <div class="fw-bold text-primary fs-5">
                                        @switch($pendaftar->jenjang)
                                            @case('sanggar') Sanggar Bermain @break
                                            @case('kelompok') Kelompok Bermain @break
                                            @case('tka') TK A @break
                                            @case('tkb') TK B @break
                                            @case('sd') SD @break
                                            @case('smp') SMP @break
                                            @case('sma') SMA @break
                                            @default {{ strtoupper($pendaftar->jenjang) }}
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($pendaftar->sudah_bayar_formulir)
                            <!-- Payment Success Section -->
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Pembayaran Berhasil!</h6>
                                    <div>Pembayaran sebesar <strong>Rp {{ number_format($pendaftar->payment_amount ?? 0, 0, ',', '.') }}</strong> sudah lunas.</div>
                                    @if($pendaftar->latestPayment && $pendaftar->latestPayment->paid_at)
                                        <small class="text-success">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            Dibayar pada: {{ $pendaftar->latestPayment->paid_at->format('d F Y, H:i') }} WIB
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <!-- Next Steps -->
                            <div class="bg-info bg-opacity-10 border border-info rounded p-4">
                                <h6 class="fw-bold text-info mb-2">
                                    <i class="bi bi-lightbulb me-1"></i>Langkah Selanjutnya:
                                </h6>
                                <ul class="mb-0 small text-info">
                                    <li>✅ Pembayaran sebesar Rp {{ number_format($pendaftar->payment_amount ?? 0, 0, ',', '.') }} sudah lunas</li>
                                    <li>📝 Lengkapi data pendaftaran di menu "Kelengkapan Data"</li>
                                    <li>⏳ Tunggu proses verifikasi dari admin (1-3 hari kerja)</li>
                                    <li>📞 Hubungi customer service jika ada pertanyaan</li>
                                </ul>
                            </div>
                        @else
                            <!-- Payment Required Section -->
                            <div class="alert alert-warning d-flex align-items-start" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Pembayaran Diperlukan</h6>
                                    <div>Silakan lakukan pembayaran sebesar <strong>Rp {{ number_format($pendaftar->payment_amount ?? 0, 0, ',', '.') }}</strong> untuk melanjutkan proses pendaftaran.</div>
                                    <small class="text-warning">
                                        <i class="bi bi-clock me-1"></i>
                                        Jatuh tempo: {{ now()->addDays(7)->format('d F Y') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Payment Action -->
                            <div class="bg-primary bg-opacity-10 border border-primary rounded p-4 text-center">
                                <h5 class="fw-bold text-primary mb-3">
                                    <i class="bi bi-credit-card me-2"></i>Total yang Harus Dibayar
                                </h5>
                                <div class="display-4 fw-bold text-primary mb-3">Rp {{ number_format($pendaftar->payment_amount ?? 0, 0, ',', '.') }}</div>
                                <div class="mb-3 text-muted">
                                    Biaya pendaftaran untuk jenjang
                                    <strong>
                                        @switch($pendaftar->jenjang)
                                            @case('sanggar') Sanggar Bermain @break
                                            @case('kelompok') Kelompok Bermain @break
                                            @case('tka') TK A @break
                                            @case('tkb') TK B @break
                                            @case('sd') SD @break
                                            @case('smp') SMP @break
                                            @case('sma') SMA @break
                                            @default {{ strtoupper($pendaftar->jenjang) }}
                                        @endswitch
                                    </strong>
                                </div>
                                <form action="{{ route('payment.create-invoice') }}" method="POST" class="d-inline" id="paymentForm">
                                    @csrf
                                    <input type="hidden" name="pendaftar_id" value="{{ $pendaftar->id }}">
                                    <input type="hidden" name="amount" value="{{ $pendaftar->payment_amount }}">
                                    <button type="submit" class="btn btn-primary btn-lg px-5" id="paymentBtn">
                                        <i class="bi bi-credit-card me-2"></i>
                                        <span class="btn-text">Bayar Sekarang</span>
                                        <span class="btn-loading d-none">
                                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                            Memproses...
                                        </span>
                                    </button>
                                </form>
                                <div class="mt-3 text-muted small">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Pembayaran aman dengan berbagai metode pembayaran
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment History -->
                @if($pendaftar->payments && $pendaftar->payments->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pembayaran
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Tanggal</th>
                                        <th scope="col" class="px-4 py-3">No. Transaksi</th>
                                        <th scope="col" class="px-4 py-3">Jumlah</th>
                                        <th scope="col" class="px-4 py-3">Status</th>
                                        <th scope="col" class="px-4 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendaftar->payments as $payment)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="fw-semibold">{{ $payment->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $payment->created_at->format('H:i') }} WIB</small>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="font-monospace small">{{ $payment->invoice_id ?? 'TXN-' . $payment->id }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="fw-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($payment->status === 'PAID')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle-fill me-1"></i>Lunas
                                                </span>
                                            @elseif($payment->status === 'EXPIRED')
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle-fill me-1"></i>Expired
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock me-1"></i>Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($payment->status === 'PENDING' && $payment->invoice_url)
                                                <a href="{{ $payment->invoice_url }}" target="_blank"
                                                   class="btn btn-primary btn-sm">
                                                    <i class="bi bi-credit-card me-1"></i>Bayar
                                                </a>
                                            @elseif($payment->status === 'PAID')
                                                <button class="btn btn-success btn-sm" disabled>
                                                    <i class="bi bi-check-circle me-1"></i>Lunas
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Info Section -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-info-circle me-2 text-info"></i>Informasi Pembayaran
                                </h6>
                                <ul class="list-unstyled small mb-0">
                                    <li class="mb-2">• Pembayaran dapat dilakukan via transfer bank, e-wallet, atau kartu kredit</li>
                                    <li class="mb-2">• Pembayaran akan otomatis terverifikasi</li>
                                    <li class="mb-2">• Setelah pembayaran lunas, Anda dapat mengakses menu kelengkapan data</li>
                                    <li class="mb-2">• Biaya berbeda-beda sesuai jenjang pendidikan</li>
                                    <li>• Simpan bukti pembayaran untuk keperluan administrasi</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-headset me-2 text-success"></i>Butuh Bantuan?
                                </h6>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-whatsapp text-success me-2"></i>
                                    <a href="https://wa.me/6281234567890" target="_blank" class="text-decoration-none">
                                        +62 812-3456-7890
                                    </a>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-envelope text-primary me-2"></i>
                                    <a href="mailto:support@ppdb-yapi.com" class="text-decoration-none">
                                        support@ppdb-yapi.com
                                    </a>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clock text-warning me-2"></i>
                                    <span class="small">Senin - Jumat, 08:00 - 17:00 WIB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary btn-lg me-2">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                    </a>
                    @if($pendaftar->sudah_bayar_formulir)
                        <a href="{{ route('user.data') }}" class="btn btn-success btn-lg">
                            <i class="bi bi-clipboard-data me-2"></i>Kelengkapan Data
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .font-monospace {
            font-family: 'Courier New', monospace;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5em 0.75em;
        }

        .display-4 {
            font-size: 2.5rem;
        }

        @media print {
            .btn, .card-header, .alert {
                display: none !important;
            }
        }
    </style>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentForm');
    const paymentBtn = document.getElementById('paymentBtn');

    if (paymentForm && paymentBtn) {
        paymentForm.addEventListener('submit', function(e) {
            const btnText = paymentBtn.querySelector('.btn-text');
            const btnLoading = paymentBtn.querySelector('.btn-loading');

            if (btnText && btnLoading) {
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');
            }

            paymentBtn.disabled = true;

            // Log for debugging
            console.log('Payment form submitted');
        });
    }
});
</script>
