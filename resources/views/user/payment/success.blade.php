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
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-3 fs-3"></i>
                    <div>
                        <h4 class="alert-heading fw-bold mb-1">Pembayaran Berhasil!</h4>
                        <p class="mb-0">{{ $paymentTypeDescription ?? 'Pembayaran PPDB' }} untuk <strong>{{ $pendaftar->nama_murid }}</strong> telah berhasil diverifikasi.</p>
                    </div>
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
                                        <h3 class="fw-bold text-primary mb-0">SIAKAD YAPI</h3>
                                        <div class="d-flex flex-wrap gap-1 mb-1">
                                            @if(isset($transactionTypes) && !empty($transactionTypes))
                                                @foreach($transactionTypes as $type)
                                                    <span class="badge bg-{{ $type['badge'] }} bg-opacity-75 text-{{ $type['badge'] }}">{{ $type['label'] }}</span>
                                                @endforeach
                                            @else
                                                <span class="badge bg-info bg-opacity-75 text-black">Pembayaran</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">Sistem Informasi Akademik</small>
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    <div><i class="bi bi-geo-alt me-1"></i>Jl. Sunan Giri No.1, Rawamangun, Kec. Pulogadung, Kota Jakarta Timur - DKI Jakarta</div>
                                    <div><i class="bi bi-telephone me-1"></i>+62 21 1234567</div>
                                    <div><i class="bi bi-envelope me-1"></i>info@yapi-alazhar.id</div>
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
                                    <i class="bi bi-person-circle me-1"></i>Informasi Pembayar
                                </h6>
                                <div class="bg-light rounded p-3">
                                    <div class="mb-2"><strong>Nama:</strong> {{ Auth::user()->name }}</div>
                                    <div class="mb-2"><strong>Email:</strong> {{ Auth::user()->email }}</div>
                                    @if($pendaftar->no_pendaftaran)
                                        <div class="mb-2"><strong>No. Pendaftaran:</strong> {{ $pendaftar->no_pendaftaran }}</div>
                                    @endif
                                    <div><strong>ID User:</strong> USR-{{ str_pad(Auth::user()->id, 6, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-person-badge me-1"></i>Informasi Siswa
                                </h6>
                                <div class="bg-light rounded p-3">
                                    <div class="mb-2"><strong>Nama Siswa:</strong> {{ $pendaftar->nama_murid }}</div>
                                    <div class="mb-2"><strong>Unit:</strong> {{ $pendaftar->unit }}</div>
                                    <div class="mb-2"><strong>Jenjang:</strong> {{ $jenjangName ?? strtoupper($pendaftar->jenjang) }}</div>
                                    @php
                                        $currentYear = date('Y');
                                        $nextYear = $currentYear + 1;
                                        // Determine academic year based on payment type
                                        $isRegistration = in_array('registration_fee', array_column($cartItems, 'bill_type'));
                                        $academicYear = $isRegistration ? "$currentYear/$nextYear" : "$currentYear/$nextYear";
                                    @endphp
                                    <div><strong>Tahun Ajaran:</strong> {{ $academicYear }}</div>
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
                                            <th scope="col" class="text-center">Jenis</th>
                                            <th scope="col" class="text-center">Qty</th>
                                            <th scope="col" class="text-end">Harga Satuan</th>
                                            <th scope="col" class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $subtotal = 0;
                                            $getBillTypeInfo = function($billType) {
                                                $info = [
                                                    'registration_fee' => ['label' => 'Formulir', 'badge' => 'info', 'description' => 'Biaya formulir pendaftaran'],
                                                    'spp' => ['label' => 'SPP', 'badge' => 'primary', 'description' => 'Sumbangan Pembinaan Pendidikan'],
                                                    'uang_pangkal' => ['label' => 'Uang Pangkal', 'badge' => 'success', 'description' => 'Uang pangkal masuk'],
                                                    'uniform' => ['label' => 'Seragam', 'badge' => 'warning', 'description' => 'Seragam sekolah'],
                                                    'books' => ['label' => 'Buku', 'badge' => 'secondary', 'description' => 'Buku pelajaran'],
                                                    'supplies' => ['label' => 'Alat Tulis', 'badge' => 'dark', 'description' => 'Alat tulis dan ATK'],
                                                    'activity' => ['label' => 'Kegiatan', 'badge' => 'danger', 'description' => 'Biaya kegiatan sekolah'],
                                                    'other' => ['label' => 'Lainnya', 'badge' => 'light', 'description' => 'Biaya lainnya']
                                                ];
                                                return $info[$billType] ?? $info['other'];
                                            };
                                        @endphp
                                        @foreach($cartItems as $index => $item)
                                            @php
                                                $itemTotal = ($item['amount'] ?? 0) * ($item['quantity'] ?? 1);
                                                $subtotal += $itemTotal;
                                                $billTypeInfo = $getBillTypeInfo($item['bill_type'] ?? 'other');
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="fw-semibold">{{ $item['name'] ?? 'Item Pembayaran' }}</div>
                                                    <small class="text-muted">{{ $item['description'] ?? $billTypeInfo['description'] }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-{{ $billTypeInfo['badge'] }}">{{ $billTypeInfo['label'] }}</span>
                                                </td>
                                                <td class="text-center">{{ $item['quantity'] ?? 1 }}</td>
                                                <td class="text-end">Rp {{ number_format($item['amount'] ?? 0, 0, ',', '.') }}</td>
                                                <td class="text-end fw-bold">Rp {{ number_format($itemTotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-light">
                                            <td colspan="5" class="text-end fw-bold">Subtotal:</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr class="table-light">
                                            <td colspan="5" class="text-end fw-bold">Admin Fee:</td>
                                            <td class="text-end fw-bold">Rp 0</td>
                                        </tr>
                                        <tr class="table-success">
                                            <td colspan="5" class="text-end fw-bold h5 mb-0">TOTAL PEMBAYARAN:</td>
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
                            @php
                                $hasRegistration = in_array('registration_fee', array_column($cartItems, 'bill_type'));
                                $hasSpp = in_array('spp', array_column($cartItems, 'bill_type'));
                                $hasUangPangkal = in_array('uang_pangkal', array_column($cartItems, 'bill_type'));
                                $hasOther = array_intersect(['uniform', 'books', 'supplies', 'activity', 'other'], array_column($cartItems, 'bill_type'));
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        <li class="mb-1">✅ {{ $paymentTypeDescription ?? 'Pembayaran' }} berhasil diverifikasi</li>

                                        @if($hasRegistration)
                                            <li class="mb-1">📝 Akses menu <strong>"Kelengkapan Data"</strong> di dashboard</li>
                                            <li class="mb-1">📋 Lengkapi semua formulir pendaftaran</li>
                                            <li class="mb-1">📤 Submit data untuk review admin</li>
                                        @elseif($hasSpp)
                                            <li class="mb-1">🎓 Pembayaran SPP masuk sistem akademik</li>
                                            <li class="mb-1">� Siswa dapat mengikuti kegiatan belajar</li>
                                            <li class="mb-1">📊 Cek status akademik di menu siswa</li>
                                        @elseif($hasUangPangkal)
                                            <li class="mb-1">🏫 Uang pangkal telah terdaftar di sistem</li>
                                            <li class="mb-1">📋 Proses administrasi akademik berlanjut</li>
                                            <li class="mb-1">🎒 Siswa siap memulai tahun ajaran baru</li>
                                        @elseif($hasOther)
                                            <li class="mb-1">� Pembayaran fasilitas/perlengkapan berhasil</li>
                                            <li class="mb-1">🏪 Koordinasi dengan bagian sarana prasarana</li>
                                            <li class="mb-1">📞 Hubungi admin untuk pengambilan/penyerahan</li>
                                        @else
                                            <li class="mb-1">✅ Pembayaran telah masuk sistem</li>
                                            <li class="mb-1">📋 Data pembayaran tersimpan dengan aman</li>
                                            <li class="mb-1">🎓 Proses akademik dapat dilanjutkan</li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        @if($hasRegistration)
                                            <li class="mb-1">⏰ Tunggu proses verifikasi (1-3 hari kerja)</li>
                                            <li class="mb-1">📧 Cek email untuk notifikasi status</li>
                                            <li class="mb-1">💬 Hubungi CS jika ada pertanyaan</li>
                                            <li class="mb-1">🎓 Siap mengikuti tahap selanjutnya</li>
                                        @elseif($hasSpp)
                                            <li class="mb-1">📧 Bukti pembayaran dikirim via email</li>
                                            <li class="mb-1">📱 Simpan invoice untuk arsip</li>
                                            <li class="mb-1">🗓️ Catat tanggal jatuh tempo berikutnya</li>
                                            <li class="mb-1">💬 Hubungi bendahara jika ada pertanyaan</li>
                                        @elseif($hasUangPangkal)
                                            <li class="mb-1">📜 Tanda terima akan diproses</li>
                                            <li class="mb-1">🎒 Persiapan tahun ajaran baru</li>
                                            <li class="mb-1">📚 Informasi orientasi siswa menyusul</li>
                                            <li class="mb-1">🏫 Selamat bergabung di keluarga YAPI</li>
                                        @else
                                            <li class="mb-1">📱 Simpan invoice untuk referensi</li>
                                            <li class="mb-1">💬 Hubungi CS untuk konfirmasi</li>
                                            <li class="mb-1">📚 Lanjutkan proses sesuai jenis pembayaran</li>
                                            <li class="mb-1">✅ Terima kasih atas pembayarannya</li>
                                        @endif
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
                                        <span><strong>Email:</strong> support@yapi-alazhar.id</span>
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
                                <strong>YAPI - Sistem Informasi Akademik</strong>
                            </div>
                            <div>Invoice dibuat secara otomatis pada {{ $payment->paid_at->format('d F Y, H:i:s') }} WIB</div>
                            <div class="mt-2">
                                <span class="badge bg-secondary text-white">Dokumen Digital</span>
                                <span class="badge bg-success">Terverifikasi</span>
                                @if($payment->xendit_response['demo_mode'] ?? false)
                                    <span class="badge bg-warning">Demo Mode</span>
                                @endif
                                @isset($transactionTypes)
                                    @if(count($transactionTypes) > 1)
                                        <span class="badge bg-info text-white">Multi Payment</span>
                                    @endif
                                @endisset
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

                            @php
                                $hasRegistration = in_array('registration_fee', array_column($cartItems, 'bill_type'));
                                $hasSpp = in_array('spp', array_column($cartItems, 'bill_type'));
                                $hasUangPangkal = in_array('uang_pangkal', array_column($cartItems, 'bill_type'));
                            @endphp

                            @if($hasRegistration && !$pendaftar->sudah_bayar_formulir)
                                <a href="{{ route('user.data.index') }}" class="btn btn-success btn-lg">
                                    <i class="bi bi-clipboard-check me-2"></i>Lengkapi Data Pendaftaran
                                </a>
                            @elseif($hasSpp)
                                <a href="{{ route('user.dashboard') }}#academic" class="btn btn-info btn-lg">
                                    <i class="bi bi-book me-2"></i>Lihat Status Akademik
                                </a>
                            @elseif($hasUangPangkal)
                                <a href="{{ route('user.dashboard') }}#admin" class="btn btn-warning btn-lg">
                                    <i class="bi bi-building me-2"></i>Proses Administrasi
                                </a>
                            @endif

                            <a href="{{ route('user.transactions.index') }}" class="btn btn-outline-primary btn-lg">
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
            const noPendaftaran = '{{ $pendaftar->no_pendaftaran ?? "N/A" }}';
            const invoiceNumber = '{{ $payment->external_id }}';
            const amount = '{{ $payment->formatted_amount }}';
            const date = '{{ $payment->paid_at->format("d F Y") }}';
            const time = '{{ $payment->paid_at->format("H:i") }}';
            const paymentDesc = '{{ $paymentTypeDescription ?? "Pembayaran Akademik" }}';

            // Determine primary payment context
            @php
                $hasRegistration = in_array('registration_fee', array_column($cartItems, 'bill_type'));
                $hasSpp = in_array('spp', array_column($cartItems, 'bill_type'));
                $hasUangPangkal = in_array('uang_pangkal', array_column($cartItems, 'bill_type'));
            @endphp

            // Build cart items list
            let cartItemsList = '';
            @foreach($cartItems as $item)
                cartItemsList += '• {{ $item["name"] ?? "Item Pembayaran" }}: Rp {{ number_format($item["amount"] ?? 0, 0, ",", ".") }}\n';
            @endforeach

            // Dynamic next steps based on payment type
            let nextSteps = '';
            @if($hasRegistration)
                nextSteps = `✅ ${paymentDesc} berhasil diverifikasi
✅ Akses menu "Kelengkapan Data" di dashboard
✅ Lengkapi formulir pendaftaran
✅ Submit untuk review admin
⏰ Tunggu verifikasi (1-3 hari kerja)`;
            @elseif($hasSpp)
                nextSteps = `✅ Pembayaran SPP masuk sistem akademik
✅ Siswa dapat mengikuti kegiatan belajar
✅ Bukti pembayaran dikirim via email
📅 Catat tanggal jatuh tempo berikutnya`;
            @elseif($hasUangPangkal)
                nextSteps = `✅ Uang pangkal terdaftar di sistem
✅ Proses administrasi akademik berlanjut
✅ Siswa siap memulai tahun ajaran baru
🏫 Selamat bergabung di keluarga YAPI`;
            @else
                nextSteps = `✅ ${paymentDesc} berhasil diverifikasi
✅ Pembayaran masuk sistem akademik
✅ Tanda terima dikirim via email
📱 Simpan invoice untuk referensi`;
            @endif

            return `🧾 *INVOICE PEMBAYARAN YAPI*

✅ *STATUS: LUNAS*

👤 *INFORMASI PEMBAYAR:*
• Nama: ${userName}
• Email: ${userEmail}
• Nama Siswa: ${studentName}
${noPendaftaran !== 'N/A' ? '• No. Pendaftaran: ' + noPendaftaran + '\n' : ''}• Unit: ${unit}
• Jenjang: ${jenjang}

📋 *RINCIAN PEMBAYARAN:*
${cartItemsList}• *Total: ${amount}*
• Status: LUNAS ✅
• Tanggal: ${date}
• Waktu: ${time} WIB

🔖 *DETAIL TRANSAKSI:*
• No. Invoice: ${invoiceNumber}
• Jenis: ${paymentDesc}
• Payment Gateway: {{ $payment->xendit_response['demo_mode'] ?? false ? 'Demo' : 'Xendit' }}

📝 *LANGKAH SELANJUTNYA:*
${nextSteps}

❓ *Butuh bantuan?*
📱 WhatsApp: +62 812-3456-7890
📧 Email: support@ppdb-yapi.com
🕐 Jam Kerja: 08:00 - 17:00 WIB

Terima kasih telah mempercayai YAPI! 🎓

_Invoice dibuat otomatis pada ${date} ${time} WIB_
_Sistem Informasi Akademik & Keuangan YAPI_`;
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
