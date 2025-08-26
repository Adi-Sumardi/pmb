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
                                    <div><strong>No. Invoice:</strong> INV-{{ date('Ymd') }}-{{ str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}</div>
                                    <div><strong>Tanggal:</strong> {{ now()->format('d F Y') }}</div>
                                    <div><strong>Waktu:</strong> {{ now()->format('H:i') }} WIB</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Customer Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-person-circle me-1"></i>Informasi Pendaftar
                                </h6>
                                <div class="bg-light rounded p-3">
                                    <div class="mb-2"><strong>Nama:</strong> {{ Auth::user()->name }}</div>
                                    <div class="mb-2"><strong>Email:</strong> {{ Auth::user()->email }}</div>
                                    <div class="mb-2"><strong>No. HP:</strong> {{ Auth::user()->phone ?? '-' }}</div>
                                    <div><strong>ID Pendaftar:</strong> USR-{{ str_pad(Auth::user()->id, 6, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-building me-1"></i>Informasi Sekolah
                                </h6>
                                <div class="bg-light rounded p-3">
                                    <div class="mb-2"><strong>Yayasan:</strong> YAPI (Yayasan Administrasi Pendidikan Indonesia)</div>
                                    <div class="mb-2"><strong>Jenjang:</strong> SMP & SMA</div>
                                    <div class="mb-2"><strong>Tahun Ajaran:</strong> {{ date('Y') }}/{{ date('Y') + 1 }}</div>
                                    <div><strong>Gelombang:</strong> I (Satu)</div>
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
                                                <div class="fw-semibold">Formulir Pendaftaran PPDB</div>
                                                <small class="text-muted">Biaya administrasi formulir pendaftaran peserta didik baru</small>
                                            </td>
                                            <td class="text-center">1</td>
                                            <td class="text-end">Rp 150.000</td>
                                            <td class="text-end fw-bold">Rp 150.000</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-light">
                                            <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                                            <td class="text-end fw-bold">Rp 150.000</td>
                                        </tr>
                                        <tr class="table-light">
                                            <td colspan="4" class="text-end fw-bold">Admin Fee:</td>
                                            <td class="text-end fw-bold">Rp 0</td>
                                        </tr>
                                        <tr class="table-success">
                                            <td colspan="4" class="text-end fw-bold h5 mb-0">TOTAL PEMBAYARAN:</td>
                                            <td class="text-end fw-bold h5 mb-0 text-success">Rp 150.000</td>
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
                                        <i class="bi bi-wallet2 text-primary me-2 fs-4"></i>
                                        <div>
                                            <div class="fw-semibold">Transfer Bank / E-Wallet</div>
                                            <small class="text-muted">Via Payment Gateway</small>
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
                                            <small class="text-success">Terverifikasi pada {{ now()->format('d/m/Y H:i') }}</small>
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
                                    <div class="font-monospace fw-bold">TXN-{{ strtoupper(uniqid()) }}</div>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <div class="text-muted small">Reference ID untuk customer service</div>
                                </div>
                            </div>
                        </div>

                        <!-- Important Notes -->
                        <div class="alert alert-info" role="alert">
                            <h6 class="fw-bold mb-2">
                                <i class="bi bi-info-circle me-1"></i>Informasi Penting
                            </h6>
                            <ul class="mb-0 small">
                                <li>Invoice ini adalah bukti pembayaran yang sah</li>
                                <li>Simpan invoice ini untuk keperluan administrasi</li>
                                <li>Dengan pembayaran ini, Anda dapat melanjutkan proses pendaftaran</li>
                                <li>Akses menu <strong>"Kelengkapan Data"</strong> untuk melengkapi formulir pendaftaran</li>
                                <li>Hubungi customer service jika ada pertanyaan: <strong>+62 812-3456-7890</strong></li>
                            </ul>
                        </div>

                        <!-- Footer -->
                        <div class="text-center text-muted small mt-4 pt-3 border-top">
                            <div class="mb-2">
                                <strong>PPDB YAPI - Sistem Informasi Penerimaan Peserta Didik Baru</strong>
                            </div>
                            <div>Invoice dibuat secara otomatis pada {{ now()->format('d F Y, H:i:s') }} WIB</div>
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
                            <a href="{{ route('payment.index') }}" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-clock-history me-2"></i>Riwayat Pembayaran
                            </a>
                            <button onclick="printInvoice()" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-printer me-2"></i>Print Invoice
                            </button>
                            <button onclick="sendToWhatsApp()" class="btn btn-success btn-lg">
                                <i class="bi bi-whatsapp me-2"></i>Kirim ke WhatsApp
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
            .btn, .header, .navigation {
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
            const invoiceNumber = 'INV-{{ date("Ymd") }}-{{ str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT) }}';
            const transactionId = 'TXN-{{ strtoupper(uniqid()) }}';
            const date = '{{ now()->format("d F Y") }}';
            const time = '{{ now()->format("H:i") }}';

            return `🧾 *INVOICE PEMBAYARAN PPDB YAPI*

✅ *STATUS: LUNAS*

👤 *INFORMASI PENDAFTAR:*
• Nama: ${userName}
• Email: ${userEmail}
• Tanggal: ${date}
• Waktu: ${time} WIB

📋 *RINCIAN PEMBAYARAN:*
• Item: Formulir Pendaftaran PPDB
• Jumlah: Rp 150.000
• Status: LUNAS

🔖 *DETAIL TRANSAKSI:*
• No. Invoice: ${invoiceNumber}
• Transaction ID: ${transactionId}
• Metode: Transfer Bank/E-Wallet

📝 *LANGKAH SELANJUTNYA:*
✓ Pembayaran berhasil diverifikasi
✓ Akses menu "Kelengkapan Data" di dashboard
✓ Lengkapi formulir pendaftaran
✓ Tunggu proses verifikasi admin

❓ *Butuh bantuan?*
Hubungi customer service kami

Terima kasih telah memilih PPDB YAPI! 🎓`;
        }

        // Auto-generate invoice on page load
        document.addEventListener('DOMContentLoaded', function() {
            // You can add auto-actions here if needed
            console.log('Invoice generated successfully');
        });
    </script>
</x-app-layout>
