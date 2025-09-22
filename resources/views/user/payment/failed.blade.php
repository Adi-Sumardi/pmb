{{-- filepath: /Users/yapi/project/ppdb-backend/resources/views/user/payment/failed.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ❌ {{ __('Pembayaran Gagal') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Print-friendly content -->
                    <div id="invoice-content">

                        <!-- Failed Alert -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <i class="bi bi-x-circle-fill me-3 fs-3"></i>
                                    <div>
                                        <h4 class="alert-heading fw-bold mb-1">Pembayaran Gagal!</h4>
                                        <p class="mb-0">
                                            @if($payment)
                                                Pembayaran dengan invoice <strong>{{ $payment->external_id }}</strong> tidak dapat diproses.
                                            @else
                                                Pembayaran tidak dapat diproses atau telah dibatalkan.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Header -->
                        <div class="row align-items-start mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-mortarboard-fill text-primary me-2" style="font-size: 2rem;"></i>
                                    <div>
                                        <h3 class="fw-bold text-primary mb-0">PPDB YAPI</h3>
                                        <div class="d-flex flex-wrap gap-1 mb-1">
                                            <span class="badge bg-danger bg-opacity-75 text-danger">GAGAL</span>
                                        </div>
                                        <small class="text-muted">Sistem Informasi Akademik & Keuangan</small>
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
                                    <h2 class="fw-bold text-danger mb-1">INVOICE</h2>
                                    <div class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 fs-6">
                                        <i class="bi bi-x-circle-fill me-1"></i>GAGAL
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    @if($payment)
                                        <div><strong>No. Invoice:</strong> {{ $payment->external_id }}</div>
                                        <div><strong>Tanggal:</strong> {{ $payment->created_at->format('d F Y') }}</div>
                                        <div><strong>Status:</strong> {{ strtoupper($payment->status) }}</div>
                                    @else
                                        <div><strong>Status:</strong> Tidak Ada Data Payment</div>
                                        <div><strong>Tanggal:</strong> {{ now()->format('d F Y') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
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
                                    <div><strong>Tahun Ajaran:</strong> {{ date('Y') }}/{{ date('Y') + 1 }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Failure Details -->
                        @if($payment)
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="bi bi-exclamation-triangle me-1"></i>Detail Pembayaran Gagal
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-danger">
                                        <tr>
                                            <th scope="col">Informasi</th>
                                            <th scope="col">Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold">No. Invoice</td>
                                            <td class="font-monospace">{{ $payment->external_id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Jumlah Pembayaran</td>
                                            <td class="fw-bold text-danger">{{ $payment->formatted_amount }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Status</td>
                                            <td>
                                                @php
                                                    $statusInfo = [
                                                        'FAILED' => ['label' => 'Gagal', 'badge' => 'danger', 'icon' => 'x-circle'],
                                                        'EXPIRED' => ['label' => 'Kadaluarsa', 'badge' => 'warning', 'icon' => 'clock'],
                                                        'CANCELLED' => ['label' => 'Dibatalkan', 'badge' => 'secondary', 'icon' => 'dash-circle']
                                                    ];
                                                    $status = $statusInfo[$payment->status] ?? ['label' => $payment->status, 'badge' => 'dark', 'icon' => 'question-circle'];
                                                @endphp
                                                <span class="badge bg-{{ $status['badge'] }}">
                                                    <i class="bi bi-{{ $status['icon'] }} me-1"></i>{{ $status['label'] }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Waktu Dibuat</td>
                                            <td>{{ $payment->created_at->format('d F Y, H:i:s') }} WIB</td>
                                        </tr>
                                        @if($payment->updated_at != $payment->created_at)
                                        <tr>
                                            <td class="fw-semibold">Waktu Update Status</td>
                                            <td>{{ $payment->updated_at->format('d F Y, H:i:s') }} WIB</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <!-- Error Information -->
                        <div class="alert alert-danger" role="alert">
                            <h6 class="fw-bold mb-2">
                                <i class="bi bi-exclamation-triangle me-1"></i>Kemungkinan Penyebab Pembayaran Gagal
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        <li class="mb-1">💳 Saldo rekening tidak mencukupi</li>
                                        <li class="mb-1">⏰ Sesi pembayaran telah berakhir/kadaluarsa</li>
                                        <li class="mb-1">❌ Pembayaran dibatalkan oleh user</li>
                                        <li class="mb-1">🚫 Metode pembayaran tidak didukung</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        <li class="mb-1">🔧 Gangguan teknis payment gateway</li>
                                        <li class="mb-1">🏦 Masalah koneksi dengan bank</li>
                                        <li class="mb-1">📱 Aplikasi mobile banking bermasalah</li>
                                        <li class="mb-1">⚠️ Transaksi ditolak oleh sistem keamanan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        </div>

                        <!-- Help Information -->
                        <div class="alert alert-info" role="alert">
                            <h6 class="fw-bold mb-2">
                                <i class="bi bi-question-circle me-1"></i>Langkah Selanjutnya
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        <li class="mb-1">🔄 Coba lakukan pembayaran ulang</li>
                                        <li class="mb-1">💳 Periksa saldo dan metode pembayaran</li>
                                        <li class="mb-1">🔄 Gunakan metode pembayaran lain</li>
                                        <li class="mb-1">📞 Hubungi bank penerbit kartu Anda</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        <li class="mb-1">⏰ Tunggu beberapa menit dan coba lagi</li>
                                        <li class="mb-1">📧 Cek email untuk notifikasi</li>
                                        <li class="mb-1">💬 Hubungi customer service kami</li>
                                        <li class="mb-1">📱 Gunakan aplikasi mobile banking</li>
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
                                <strong>YAPI - Sistem Informasi Akademik & Keuangan</strong>
                            </div>
                            <div>Laporan kegagalan dibuat pada {{ now()->format('d F Y, H:i:s') }} WIB</div>
                            <div class="mt-2">
                                <span class="badge bg-secondary">Dokumen Digital</span>
                                <span class="badge bg-danger">Pembayaran Gagal</span>
                                @if($payment && isset($payment->xendit_response['demo_mode']) && $payment->xendit_response['demo_mode'])
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
                            <a href="{{ route('user.payments.index') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-arrow-clockwise me-2"></i>Coba Bayar Lagi
                            </a>
                            <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-house-door me-2"></i>Kembali ke Dashboard
                            </a>
                            <a href="{{ route('user.transactions.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-receipt me-2"></i>Riwayat Pembayaran
                            </a>
                            <button onclick="sendToWhatsApp()" class="btn btn-success btn-lg">
                                <i class="bi bi-whatsapp me-1"></i>Hubungi Support
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function sendToWhatsApp() {
            const userName = '{{ Auth::user()->name }}';
            const userEmail = '{{ Auth::user()->email }}';
            const studentName = '{{ $pendaftar->nama_murid }}';
            const unit = '{{ $pendaftar->unit }}';
            const jenjang = '{{ strtoupper($pendaftar->jenjang) }}';
            const noPendaftaran = '{{ $pendaftar->no_pendaftaran ?? "N/A" }}';
            const currentDate = new Date().toLocaleDateString('id-ID');
            const currentTime = new Date().toLocaleTimeString('id-ID');

            @if($payment)
                const invoiceNumber = '{{ $payment->external_id }}';
                const amount = '{{ $payment->formatted_amount }}';
                const status = '{{ $payment->status }}';
                const paymentInfo = `🔖 *DETAIL PEMBAYARAN GAGAL:*
• No. Invoice: ${invoiceNumber}
• Jumlah: ${amount}
• Status: ${status}
• Waktu: {{ $payment->created_at->format("d F Y, H:i") }} WIB

`;
            @else
                const paymentInfo = '🔖 *DETAIL:* Tidak ada data pembayaran\n\n';
            @endif

            const message = `🚨 *BANTUAN PEMBAYARAN GAGAL*

👤 *INFORMASI USER:*
• Nama: ${userName}
• Email: ${userEmail}
• Nama Siswa: ${studentName}
${noPendaftaran !== 'N/A' ? '• No. Pendaftaran: ' + noPendaftaran + '\n' : ''}• Unit: ${unit}
• Jenjang: ${jenjang}

${paymentInfo}📋 *DESKRIPSI MASALAH:*
Pembayaran tidak dapat diproses. Mohon bantuan untuk menyelesaikan masalah ini.

⏰ *WAKTU LAPORAN:*
${currentDate} ${currentTime} WIB

Terima kasih atas bantuannya! 🙏

_Sistem Informasi Akademik & Keuangan YAPI_`;

            const phoneNumber = '6281234567890'; // Nomor WhatsApp admin
            const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        }
    </script>
</x-app-layout>
