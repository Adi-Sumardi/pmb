{{-- filepath: /Users/yapi/project/ppdb-backend/resources/views/payment/success.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-receipt me-2 text-success"></i>
                Invoice Pembayaran
            </h2>
            <div class="d-flex gap-2">
                <button onclick="printInvoice()" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-printer me-1"></i>Print
                </button>
                <button onclick="sendToWhatsApp()" class="btn btn-success btn-sm">
                    <i class="bi bi-whatsapp me-1"></i>Kirim WhatsApp
                </button>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @php
                    // Use payment->pendaftar if $pendaftar is not defined
                    $pendaftar = $pendaftar ?? $payment->pendaftar;
                @endphp

                <!-- Success Alert -->
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Pembayaran Berhasil!</h5>
                            <p class="mb-0">Terima kasih, pembayaran formulir pendaftaran PPDB telah berhasil diverifikasi.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>

                <!-- Invoice Card -->
                <div class="card border-0 shadow-lg" id="invoice-content">
                    <div class="card-body p-5">
                        <!-- Header Invoice -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-mortarboard-fill text-primary me-2" style="font-size: 2rem;"></i>
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
                                    <h2 class="fw-bold text-success mb-1">INVOICE</h2>
                                    <div class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 fs-6">
                                        <i class="bi bi-check-circle-fill me-1"></i>LUNAS
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    <div><strong>No. Invoice:</strong> {{ $payment->external_id }}</div>
                                    <div><strong>Tanggal:</strong> {{ $payment->paid_at->format('d F Y') }}</div>
                                    <div><strong>Waktu:</strong> {{ $payment->paid_at->format('H:i') }} WIB</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Customer & Student Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-person-circle me-1"></i>Informasi Pendaftar
                                </h6>
                                <div class="bg-light rounded p-3">
                                    <div class="mb-2"><strong>Nama:</strong> {{ Auth::user()->name }}</div>
                                    <div class="mb-2"><strong>Email:</strong> {{ Auth::user()->email }}</div>
                                    <div class="mb-2"><strong>No. Pendaftaran:</strong> {{ $pendaftar->no_pendaftaran }}</div>
                                    <div><strong>ID User:</strong> USR-{{ str_pad(Auth::user()->id, 6, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-person-badge me-1"></i>Informasi Calon Siswa
                                </h6>
                                <div class="bg-light rounded p-3">
                                    <div class="mb-2"><strong>Nama Siswa:</strong> {{ $pendaftar->nama_murid }}</div>
                                    <div class="mb-2"><strong>Unit:</strong> {{ $pendaftar->unit }}</div>
                                    <div class="mb-2"><strong>Jenjang:</strong> {{ $jenjangName ?? strtoupper($pendaftar->jenjang) }}</div>
                                    <div><strong>Tahun Ajaran:</strong> {{ date('Y') }}/{{ date('Y') + 1 }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="bi bi-clipboard-check me-1"></i>Rincian Pembayaran
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
                                                <div class="fw-semibold">Formulir Pendaftaran PPDB {{ strtoupper($pendaftar->jenjang) }}</div>
                                                <small class="text-muted">{{ $pendaftar->unit }} - Biaya administrasi formulir pendaftaran</small>
                                            </td>
                                            <td class="text-center">1</td>
                                            <td class="text-end">{{ $payment->formatted_amount }}</td>
                                            <td class="text-end fw-bold">{{ $payment->formatted_amount }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-light">
                                            <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                                            <td class="text-end fw-bold">{{ $payment->formatted_amount }}</td>
                                        </tr>
                                        <tr class="table-light">
                                            <td colspan="4" class="text-end fw-bold">Admin Fee:</td>
                                            <td class="text-end fw-bold">Rp 0</td>
                                        </tr>
                                        <tr class="table-success">
                                            <td colspan="4" class="text-end fw-bold h5 mb-0">TOTAL PEMBAYARAN:</td>
                                            <td class="text-end fw-bold h5 mb-0 text-success">{{ $payment->formatted_amount }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-credit-card me-1"></i>Metode Pembayaran
                                </h6>
                                <div class="bg-light rounded p-3">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $paymentMethod = $payment->xendit_response['payment_method'] ?? 'payment_gateway';
                                            $icon = match(strtolower($paymentMethod)) {
                                                'credit_card' => 'bi-credit-card',
                                                'bank_transfer' => 'bi-bank',
                                                'ewallet' => 'bi-wallet2',
                                                'qr_code', 'qris' => 'bi-qr-code',
                                                default => 'bi-wallet2'
                                            };
                                            $methodName = match(strtolower($paymentMethod)) {
                                                'credit_card' => 'Kartu Kredit',
                                                'bank_transfer' => 'Transfer Bank',
                                                'ewallet' => 'E-Wallet',
                                                'qr_code', 'qris' => 'QRIS',
                                                default => ucfirst(str_replace('_', ' ', $paymentMethod))
                                            };
                                        @endphp
                                        <i class="{{ $icon }} text-primary me-2 fs-4"></i>
                                        <div>
                                            <div class="fw-semibold">{{ $methodName }}</div>
                                            <small class="text-muted">Via Payment Gateway {{ $payment->xendit_response['demo_mode'] ?? false ? '(Demo)' : 'Xendit' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-calendar-check me-1"></i>Status Pembayaran
                                </h6>
                                <div class="bg-success bg-opacity-10 rounded p-3 border border-success">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success me-2 fs-4"></i>
                                        <div>
                                            <div class="fw-semibold text-success">PEMBAYARAN BERHASIL</div>
                                            <small class="text-success">Terverifikasi pada {{ $payment->paid_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transaction ID -->
                        <div class="bg-primary bg-opacity-10 border border-primary rounded p-3 mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="fw-bold text-primary mb-1">
                                        <i class="bi bi-hash me-1"></i>ID Transaksi
                                    </h6>
                                    <div class="font-monospace fw-bold">{{ $payment->external_id }}</div>
                                    @if($payment->invoice_id)
                                        <small class="text-muted">{{ $payment->xendit_response['demo_mode'] ?? false ? 'Demo' : 'Xendit' }} Invoice ID: {{ $payment->invoice_id }}</small>
                                    @endif
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <div class="text-muted small">Reference ID untuk customer service</div>
                                </div>
                            </div>
                        </div>

                        <!-- Next Steps -->
                        <div class="alert alert-info" role="alert">
                            <h6 class="fw-bold mb-2">
                                <i class="bi bi-list-check me-1"></i>Langkah Selanjutnya
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        <li class="mb-1">✅ Pembayaran formulir berhasil diverifikasi</li>
                                        <li class="mb-1">📝 Akses menu <strong>"Kelengkapan Data"</strong> di dashboard</li>
                                        <li class="mb-1">📋 Lengkapi semua formulir pendaftaran</li>
                                        <li class="mb-1">📤 Submit data untuk review admin</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        <li class="mb-1">⏰ Tunggu proses verifikasi (1-3 hari kerja)</li>
                                        <li class="mb-1">📧 Cek email untuk notifikasi status</li>
                                        <li class="mb-1">💬 Hubungi CS jika ada pertanyaan</li>
                                        <li class="mb-1">🎓 Siap mengikuti tahap selanjutnya</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="alert alert-warning" role="alert">
                            <h6 class="fw-bold mb-2">
                                <i class="bi bi-headset me-1"></i>Butuh Bantuan?
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-whatsapp text-success me-2"></i>
                                        <span><strong>WhatsApp:</strong> +62 812-3456-7890</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-envelope text-primary me-2"></i>
                                        <span><strong>Email:</strong> support@ppdb-yapi.com</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-telephone text-info me-2"></i>
                                        <span><strong>Telepon:</strong> (021) 1234-5678</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock text-warning me-2"></i>
                                        <span><strong>Jam Kerja:</strong> 08:00 - 17:00 WIB</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="text-center text-muted small mt-4 pt-3 border-top">
                            <div class="mb-2">
                                <strong>PPDB YAPI - Sistem Informasi Penerimaan Peserta Didik Baru</strong>
                            </div>
                            <div>Invoice dibuat secara otomatis pada {{ $payment->paid_at->format('d F Y, H:i:s') }} WIB</div>
                            <div class="mt-2">
                                <span class="badge bg-secondary">Dokumen Digital</span>
                                <span class="badge bg-success">Terverifikasi</span>
                                @if($payment->xendit_response['demo_mode'] ?? false)
                                    <span class="badge bg-warning">Demo Mode</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-house-door me-2"></i>Kembali ke Dashboard
                            </a>
                            @if(!$pendaftar->sudah_bayar_formulir)
                                <a href="{{ route('user.data') }}" class="btn btn-success btn-lg">
                                    <i class="bi bi-clipboard-check me-2"></i>Lengkapi Data
                                </a>
                            @endif
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-receipt me-2"></i>Riwayat Pembayaran
                            </a>
                            <button onclick="printInvoice()" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-printer me-2"></i>Print Invoice
                            </button>
                            <button onclick="sendToWhatsApp()" class="btn btn-success btn-lg">
                                <i class="bi bi-whatsapp me-1"></i>Kirim ke WhatsApp
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for Print -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #invoice-content, #invoice-content * {
                visibility: visible;
            }
            #invoice-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100% !important;
            }
            .btn, .header, .navigation, .alert {
                display: none !important;
            }
        }

        .font-monospace {
            font-family: 'Courier New', monospace;
        }

        .table th {
            background-color: var(--bs-primary) !important;
            color: white !important;
        }

        .invoice-number {
            font-size: 1.5rem;
            letter-spacing: 2px;
        }

        .alert {
            border-left: 4px solid;
        }

        .alert-info {
            border-left-color: var(--bs-info);
        }

        .alert-warning {
            border-left-color: var(--bs-warning);
        }
    </style>

    <!-- JavaScript -->
    <script>
        function printInvoice() {
            window.print();
        }

        function sendToWhatsApp() {
            const invoiceText = generateWhatsAppMessage();
            const phoneNumber = '6281234567890'; // Nomor WhatsApp admin
            const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(invoiceText)}`;
            window.open(whatsappUrl, '_blank');
        }

        function generateWhatsAppMessage() {
            const userName = '{{ Auth::user()->name }}';
            const userEmail = '{{ Auth::user()->email }}';
            const studentName = '{{ $pendaftar->nama_murid }}';
            const unit = '{{ $pendaftar->unit }}';
            const jenjang = '{{ strtoupper($pendaftar->jenjang) }}';
            const noPendaftaran = '{{ $pendaftar->no_pendaftaran }}';
            const invoiceNumber = '{{ $payment->external_id }}';
            const amount = '{{ $payment->formatted_amount }}';
            const date = '{{ $payment->paid_at->format("d F Y") }}';
            const time = '{{ $payment->paid_at->format("H:i") }}';

            return `🧾 *INVOICE PEMBAYARAN PPDB YAPI*

✅ *STATUS: LUNAS*

👤 *INFORMASI PENDAFTAR:*
• Nama Wali: ${userName}
• Email: ${userEmail}
• Nama Siswa: ${studentName}
• No. Pendaftaran: ${noPendaftaran}
• Unit: ${unit}
• Jenjang: ${jenjang}

📋 *RINCIAN PEMBAYARAN:*
• Item: Formulir Pendaftaran PPDB ${jenjang}
• Jumlah: ${amount}
• Status: LUNAS ✅
• Tanggal: ${date}
• Waktu: ${time} WIB

🔖 *DETAIL TRANSAKSI:*
• No. Invoice: ${invoiceNumber}
• Payment Gateway: {{ $payment->xendit_response['demo_mode'] ?? false ? 'Demo' : 'Xendit' }}

📝 *LANGKAH SELANJUTNYA:*
✅ Pembayaran berhasil diverifikasi
✅ Akses menu "Kelengkapan Data" di dashboard
✅ Lengkapi formulir pendaftaran
✅ Submit untuk review admin
⏰ Tunggu verifikasi (1-3 hari kerja)

❓ *Butuh bantuan?*
📱 WhatsApp: +62 812-3456-7890
📧 Email: support@ppdb-yapi.com
🕐 Jam Kerja: 08:00 - 17:00 WIB

Terima kasih telah memilih PPDB YAPI! 🎓

_Invoice ini dibuat otomatis pada ${date} ${time} WIB_`;
        }

        // Show success animation
        document.addEventListener('DOMContentLoaded', function() {
            // Add success animation or any auto-actions
            setTimeout(() => {
                const alert = document.querySelector('.alert-success');
                if (alert) {
                    alert.classList.add('animate__animated', 'animate__pulse');
                }
            }, 500);
        });
    </script>
</x-app-layout>
