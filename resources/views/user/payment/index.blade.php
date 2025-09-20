{{-- filepath: /Users/yapi/project/ppdb-backend/resources/views/payment/user/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-dark mb-0">
                <i class="bi bi-credit-card me-2 text-primary"></i>
                Dashboard Pembayaran
            </h2>
            <div class="d-flex align-items-center text-muted">
                <i class="bi bi-calendar3 me-2"></i>
                <span>{{ now()->format('d F Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-receipt text-primary fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="card-title text-muted mb-1">Total Tagihan</h6>
                                <h3 class="mb-0 text-primary fw-bold">Rp {{ number_format(1250000, 0, ',', '.') }}</h3>
                                <small class="text-muted">Semua tagihan aktif</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-list-ol text-warning fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="card-title text-muted mb-1">Jumlah Tagihan</h6>
                                <h3 class="mb-0 text-warning fw-bold">4</h3>
                                <small class="text-muted">Item belum dibayar</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-cart text-success fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="card-title text-muted mb-1">Keranjang</h6>
                                <h3 class="mb-0 text-success fw-bold"><span id="cart-count">0</span></h3>
                                <small class="text-muted">Item dipilih</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Bills List -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-header bg-white border-0 pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="bi bi-receipt-cutoff me-2 text-primary"></i>
                                Daftar Tagihan
                            </h5>
                            <div class="text-muted small">
                                <span id="selected-info">0 dari 4 item dipilih</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <!-- Bill Item 1 -->
                        <div class="bill-item border rounded-3 p-3 mb-3" data-bill-id="1" data-amount="425000">
                            <div class="row align-items-center">
                                <div class="col-1">
                                    <div class="form-check">
                                        <input class="form-check-input bill-checkbox" type="checkbox" value="1" id="bill1">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <h6 class="fw-semibold mb-1">Biaya Pendaftaran {{ strtoupper($pendaftar->jenjang) }}</h6>
                                    <p class="text-muted small mb-1">Biaya formulir pendaftaran peserta didik baru</p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning text-dark me-2">
                                            <i class="bi bi-clock me-1"></i>Belum Dibayar
                                        </span>
                                        <small class="text-muted">Jatuh tempo: {{ now()->addDays(7)->format('d M Y') }}</small>
                                    </div>
                                </div>
                                <div class="col-3 text-end">
                                    <h5 class="fw-bold text-primary mb-1">Rp {{ number_format(425000, 0, ',', '.') }}</h5>
                                    <button class="btn btn-outline-primary btn-sm view-detail" data-bill-id="1">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Bill Item 2 -->
                        <div class="bill-item border rounded-3 p-3 mb-3" data-bill-id="2" data-amount="350000">
                            <div class="row align-items-center">
                                <div class="col-1">
                                    <div class="form-check">
                                        <input class="form-check-input bill-checkbox" type="checkbox" value="2" id="bill2">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <h6 class="fw-semibold mb-1">SPP Bulan Pertama</h6>
                                    <p class="text-muted small mb-1">Sumbangan Pembinaan Pendidikan bulan pertama</p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning text-dark me-2">
                                            <i class="bi bi-clock me-1"></i>Belum Dibayar
                                        </span>
                                        <small class="text-muted">Jatuh tempo: {{ now()->addDays(14)->format('d M Y') }}</small>
                                    </div>
                                </div>
                                <div class="col-3 text-end">
                                    <h5 class="fw-bold text-primary mb-1">Rp {{ number_format(350000, 0, ',', '.') }}</h5>
                                    <button class="btn btn-outline-primary btn-sm view-detail" data-bill-id="2">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Bill Item 3 -->
                        <div class="bill-item border rounded-3 p-3 mb-3" data-bill-id="3" data-amount="225000">
                            <div class="row align-items-center">
                                <div class="col-1">
                                    <div class="form-check">
                                        <input class="form-check-input bill-checkbox" type="checkbox" value="3" id="bill3">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <h6 class="fw-semibold mb-1">Uang Pangkal</h6>
                                    <p class="text-muted small mb-1">Biaya pengembangan sarana dan prasarana</p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning text-dark me-2">
                                            <i class="bi bi-clock me-1"></i>Belum Dibayar
                                        </span>
                                        <small class="text-muted">Jatuh tempo: {{ now()->addDays(21)->format('d M Y') }}</small>
                                    </div>
                                </div>
                                <div class="col-3 text-end">
                                    <h5 class="fw-bold text-primary mb-1">Rp {{ number_format(225000, 0, ',', '.') }}</h5>
                                    <button class="btn btn-outline-primary btn-sm view-detail" data-bill-id="3">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Bill Item 4 -->
                        <div class="bill-item border rounded-3 p-3 mb-3" data-bill-id="4" data-amount="250000">
                            <div class="row align-items-center">
                                <div class="col-1">
                                    <div class="form-check">
                                        <input class="form-check-input bill-checkbox" type="checkbox" value="4" id="bill4">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <h6 class="fw-semibold mb-1">Biaya Seragam & Buku</h6>
                                    <p class="text-muted small mb-1">Paket seragam sekolah dan buku pembelajaran</p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning text-dark me-2">
                                            <i class="bi bi-clock me-1"></i>Belum Dibayar
                                        </span>
                                        <small class="text-muted">Jatuh tempo: {{ now()->addDays(30)->format('d M Y') }}</small>
                                    </div>
                                </div>
                                <div class="col-3 text-end">
                                    <h5 class="fw-bold text-primary mb-1">Rp {{ number_format(250000, 0, ',', '.') }}</h5>
                                    <button class="btn btn-outline-primary btn-sm view-detail" data-bill-id="4">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Select All -->
                        <div class="border-top pt-3 mt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                    <label class="form-check-label fw-semibold" for="selectAll">
                                        Pilih Semua Tagihan
                                    </label>
                                </div>
                                <div class="text-muted small">
                                    Total jika semua dipilih: <span class="fw-bold text-primary">Rp {{ number_format(1250000, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shopping Cart -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 2rem;" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-header bg-primary text-white">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-cart3 me-2"></i>
                            Keranjang Pembayaran
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Student Info -->
                        <div class="bg-light rounded p-3 mb-3">
                            <h6 class="fw-semibold mb-2">
                                <i class="bi bi-person-circle me-1"></i>Data Siswa
                            </h6>
                            <div class="small">
                                <div class="mb-1"><strong>Nama:</strong> {{ $pendaftar->nama_murid }}</div>
                                <div class="mb-1"><strong>No. Daftar:</strong>
                                    <span class="badge bg-primary">{{ $pendaftar->no_pendaftaran }}</span>
                                </div>
                                <div><strong>Jenjang:</strong> {{ strtoupper($pendaftar->jenjang) }}</div>
                            </div>
                        </div>

                        <!-- Cart Items -->
                        <div id="cart-items">
                            <div class="text-center text-muted py-4" id="empty-cart">
                                <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                                <p class="mb-0">Keranjang kosong</p>
                                <small>Pilih tagihan yang ingin dibayar</small>
                            </div>
                            <!-- Cart items will be inserted here dynamically -->
                        </div>

                        <!-- Cart Summary -->
                        <div id="cart-summary" class="d-none">
                            <hr>

                            <!-- Promo Code Section -->
                            <div class="mb-3">
                                <label for="promoCode" class="form-label small fw-semibold">
                                    <i class="bi bi-ticket-perforated me-1 text-primary"></i>Kode Promo
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="promoCode" placeholder="Masukkan kode promo" maxlength="20">
                                    <button class="btn btn-outline-primary" type="button" id="applyPromo">
                                        <i class="bi bi-check2"></i> Gunakan
                                    </button>
                                </div>
                                <div id="promoMessage" class="mt-1 small"></div>
                            </div>

                            <!-- Available Discounts Section -->
                            <div class="mb-3" id="discountSection">
                                <label class="form-label small fw-semibold">
                                    <i class="bi bi-percent me-1 text-success"></i>Diskon Tersedia
                                </label>
                                <div id="availableDiscounts">
                                    <!-- Discounts will be loaded dynamically -->
                                </div>
                            </div>

                            <!-- Applied Discount Display -->
                            <div id="appliedDiscount" class="d-none mb-3">
                                <div class="bg-success bg-opacity-10 border border-success rounded p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-success fw-semibold">
                                                <i class="bi bi-check-circle me-1"></i><span id="discountName"></span>
                                            </small>
                                            <div class="small text-muted" id="discountDescription"></div>
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger" id="removeDiscount" title="Hapus diskon">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span class="fw-semibold" id="subtotal">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2" id="discountRow" style="display: none;">
                                <span class="text-success">Diskon:</span>
                                <span class="fw-semibold text-success" id="discountAmount">- Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Biaya Admin:</span>
                                <span class="fw-semibold">Rp 2.500</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">Total:</span>
                                <span class="fw-bold text-primary fs-5" id="total-amount">Rp 0</span>
                            </div>

                            <!-- Checkout Button -->
                            <form id="payment-form" action="{{ route('user.payments.create-invoice') }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" id="payment-pendaftar-id" name="pendaftar_id" value="{{ $pendaftar->id }}">
                                <input type="hidden" id="payment-amount" name="amount" value="">
                                <input type="hidden" id="payment-items" name="items" value="">
                                <input type="hidden" id="payment-promo-code" name="promo_code" value="">
                                <input type="hidden" id="payment-discount-id" name="discount_id" value="">
                            </form>

                            <button class="btn btn-primary btn-lg w-100 mb-2" id="checkout-btn" disabled>
                                <i class="bi bi-credit-card me-2"></i>
                                Bayar Sekarang
                            </button>
                            <button class="btn btn-outline-danger btn-sm w-100" id="clear-cart">
                                <i class="bi bi-trash me-1"></i>
                                Kosongkan Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bill Detail Modal -->
    <div class="modal fade" id="billDetailModal" tabindex="-1" aria-labelledby="billDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="billDetailModalLabel">
                        <i class="bi bi-receipt me-2"></i>Detail Tagihan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="bill-detail-content">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="add-to-cart-from-modal">
                        <i class="bi bi-cart-plus me-1"></i>Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Fallback AOS if main CDN fails
        if (typeof AOS === 'undefined') {
            console.log('Loading AOS fallback...');
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js';
            script.onload = function() {
                if (typeof AOS !== 'undefined') {
                    AOS.init({
                        duration: 800,
                        easing: 'ease-in-out',
                        once: true
                    });
                    console.log('AOS fallback loaded successfully');
                }
            };
            document.head.appendChild(script);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize AOS with fallback
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });
            console.log('AOS initialized successfully');
        } else {
            console.warn('AOS not loaded, animations disabled');
            // Remove AOS attributes from elements to prevent issues
            document.querySelectorAll('[data-aos]').forEach(el => {
                el.removeAttribute('data-aos');
                el.removeAttribute('data-aos-delay');
            });
        }

        // Shopping cart functionality
        let cart = [];
        let currentBillDetail = null;
        let appliedDiscount = null;
        let appliedPromoCode = null;

        // Promo codes data (active only)
        const promoCodes = {
            'STUDENT2024': {
                code: 'STUDENT2024',
                name: 'Diskon Siswa Baru 2024',
                description: 'Diskon khusus untuk pendaftaran siswa baru',
                type: 'percentage',
                value: 15,
                minAmount: 500000,
                maxDiscount: 150000,
                isActive: true,
                validUntil: '2024-12-31',
                usageLimit: 100,
                usedCount: 25
            },
            'EARLYBIRD': {
                code: 'EARLYBIRD',
                name: 'Early Bird Special',
                description: 'Diskon untuk pendaftar awal',
                type: 'fixed',
                value: 100000,
                minAmount: 800000,
                maxDiscount: 100000,
                isActive: true,
                validUntil: '2024-10-31',
                usageLimit: 50,
                usedCount: 12
            },
            'SIBLING20': {
                code: 'SIBLING20',
                name: 'Diskon Saudara Kandung',
                description: 'Diskon untuk anak kedua dan seterusnya',
                type: 'percentage',
                value: 20,
                minAmount: 400000,
                maxDiscount: 200000,
                isActive: true,
                validUntil: '2024-12-31',
                usageLimit: 200,
                usedCount: 45
            },
            'EXPIRED': {
                code: 'EXPIRED',
                name: 'Kode Expired',
                description: 'Kode promo yang sudah tidak aktif',
                type: 'percentage',
                value: 25,
                minAmount: 300000,
                maxDiscount: 250000,
                isActive: false,
                validUntil: '2024-08-31',
                usageLimit: 100,
                usedCount: 100
            }
        };

        // Available discounts (active only)
        const availableDiscounts = [
            {
                id: 'newcomer',
                name: 'Diskon Siswa Baru',
                description: 'Berlaku untuk semua biaya pendaftaran',
                type: 'percentage',
                value: 10,
                minAmount: 400000,
                maxDiscount: 100000,
                isActive: true,
                applicableFor: ['registration'],
                icon: 'bi-person-plus'
            },
            {
                id: 'fullpackage',
                name: 'Paket Lengkap',
                description: 'Diskon jika memilih semua tagihan',
                type: 'fixed',
                value: 75000,
                minAmount: 1200000,
                maxDiscount: 75000,
                isActive: true,
                applicableFor: ['all'],
                icon: 'bi-box-seam'
            },
            {
                id: 'loyalty',
                name: 'Member Setia',
                description: 'Diskon untuk keluarga yang sudah terdaftar',
                type: 'percentage',
                value: 12,
                minAmount: 600000,
                maxDiscount: 120000,
                isActive: true,
                applicableFor: ['spp', 'uniform'],
                icon: 'bi-heart'
            },
            {
                id: 'inactive',
                name: 'Diskon Tidak Aktif',
                description: 'Diskon yang sudah tidak berlaku',
                type: 'percentage',
                value: 30,
                minAmount: 200000,
                maxDiscount: 300000,
                isActive: false,
                applicableFor: ['all'],
                icon: 'bi-x-circle'
            }
        ];

        // Bill data
        const bills = {
            1: {
                id: 1,
                name: 'Biaya Pendaftaran {{ strtoupper($pendaftar->jenjang ?? "SD") }}',
                description: 'Biaya formulir pendaftaran peserta didik baru',
                amount: 425000,
                dueDate: '{{ now()->addDays(7)->format("d M Y") }}',
                details: 'Biaya ini merupakan biaya wajib untuk proses pendaftaran peserta didik baru jenjang {{ strtoupper($pendaftar->jenjang ?? "SD") }} tahun ajaran {{ date("Y") }}/{{ date("Y") + 1 }}.'
            },
            2: {
                id: 2,
                name: 'SPP Bulan Pertama',
                description: 'Sumbangan Pembinaan Pendidikan bulan pertama',
                amount: 350000,
                dueDate: '{{ now()->addDays(14)->format("d M Y") }}',
                details: 'SPP bulan pertama untuk kegiatan belajar mengajar dan operasional sekolah.'
            },
            3: {
                id: 3,
                name: 'Uang Pangkal',
                description: 'Biaya pengembangan sarana dan prasarana',
                amount: 225000,
                dueDate: '{{ now()->addDays(21)->format("d M Y") }}',
                details: 'Biaya pengembangan dan pemeliharaan sarana prasarana sekolah untuk menunjang kegiatan pembelajaran.'
            },
            4: {
                id: 4,
                name: 'Biaya Seragam & Buku',
                description: 'Paket seragam sekolah dan buku pembelajaran',
                amount: 250000,
                dueDate: '{{ now()->addDays(30)->format("d M Y") }}',
                details: 'Paket lengkap seragam sekolah dan buku-buku pembelajaran sesuai kurikulum yang berlaku.'
            }
        };

        // Add to cart with confirmation
        // Promo code functions
        function applyPromoCode() {
            const promoInput = document.getElementById('promoCodeInput');
            const promoMessage = document.getElementById('promoMessage');
            const code = promoInput.value.trim().toUpperCase();

            if (!code) {
                showPromoMessage('Masukkan kode promo terlebih dahulu', 'warning');
                return;
            }

            if (cart.length === 0) {
                showPromoMessage('Keranjang masih kosong', 'warning');
                return;
            }

            const promoCode = promoCodes[code];
            if (!promoCode) {
                showPromoMessage('Kode promo tidak valid', 'danger');
                return;
            }

            if (!promoCode.isActive) {
                showPromoMessage('Kode promo sudah tidak berlaku', 'danger');
                return;
            }

            if (promoCode.usedCount >= promoCode.usageLimit) {
                showPromoMessage('Kode promo sudah mencapai batas penggunaan', 'danger');
                return;
            }

            const currentDate = new Date();
            const validUntil = new Date(promoCode.validUntil);
            if (currentDate > validUntil) {
                showPromoMessage('Kode promo sudah expired', 'danger');
                return;
            }

            const subtotal = calculateSubtotal();
            if (subtotal < promoCode.minAmount) {
                showPromoMessage(`Minimum transaksi Rp ${promoCode.minAmount.toLocaleString('id-ID')} untuk menggunakan kode ini`, 'warning');
                return;
            }

            // Apply promo code
            appliedPromoCode = promoCode;
            appliedDiscount = null; // Clear manual discount
            showPromoMessage(`Kode promo "${promoCode.name}" berhasil diterapkan!`, 'success');
            promoInput.value = '';

            updateCartUI();
        }

        function removePromoCode() {
            appliedPromoCode = null;
            showPromoMessage('Kode promo dihapus', 'info');
            updateCartUI();
        }

        function showPromoMessage(message, type) {
            const promoMessage = document.getElementById('promoMessage');
            promoMessage.innerHTML = `
                <div class="alert alert-${type} alert-sm mb-2 p-2" role="alert">
                    <small>${message}</small>
                </div>
            `;
            setTimeout(() => {
                promoMessage.innerHTML = '';
            }, 4000);
        }

        function loadAvailableDiscounts() {
            const discountsList = document.getElementById('availableDiscounts');
            if (!discountsList) {
                console.warn('Available discounts container not found');
                return;
            }

            const activeDiscounts = availableDiscounts.filter(discount => discount.isActive);

            if (activeDiscounts.length === 0) {
                discountsList.innerHTML = '<p class="text-muted small mb-0">Tidak ada diskon tersedia saat ini</p>';
                return;
            }

            discountsList.innerHTML = activeDiscounts.map(discount => {
                const discountText = discount.type === 'percentage'
                    ? `${discount.value}%`
                    : `Rp ${discount.value.toLocaleString('id-ID')}`;
                const maxDiscountText = discount.maxDiscount
                    ? ` (maks Rp ${discount.maxDiscount.toLocaleString('id-ID')})`
                    : '';

                return `
                    <div class="discount-item p-2 border rounded mb-2 cursor-pointer"
                         onclick="applyDiscount('${discount.id}')"
                         style="cursor: pointer; transition: all 0.2s;"
                         onmouseover="this.style.backgroundColor='#f8f9fa'"
                         onmouseout="this.style.backgroundColor='transparent'">
                        <div class="d-flex align-items-center">
                            <i class="${discount.icon} text-primary me-2"></i>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small">${discount.name}</div>
                                <div class="text-muted small">${discount.description}</div>
                                <div class="text-success small">
                                    Diskon ${discountText}${maxDiscountText}
                                    (min. Rp ${discount.minAmount.toLocaleString('id-ID')})
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }        function applyDiscount(discountId) {
            const discount = availableDiscounts.find(d => d.id === discountId);
            if (!discount || !discount.isActive) {
                Swal.fire({
                    icon: 'error',
                    title: 'Diskon Tidak Valid',
                    text: 'Diskon yang dipilih tidak tersedia',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            if (cart.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Keranjang Kosong',
                    text: 'Tambahkan item ke keranjang terlebih dahulu',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            const subtotal = calculateSubtotal();
            if (subtotal < discount.minAmount) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Minimum Transaksi',
                    text: `Minimum transaksi Rp ${discount.minAmount.toLocaleString('id-ID')} untuk menggunakan diskon ini`,
                    timer: 3000,
                    showConfirmButton: false
                });
                return;
            }

            // Apply discount
            appliedDiscount = discount;
            appliedPromoCode = null; // Clear promo code

            Swal.fire({
                icon: 'success',
                title: 'Diskon Diterapkan',
                text: `${discount.name} berhasil diterapkan!`,
                timer: 2000,
                showConfirmButton: false
            });

            updateCartUI();
        }

        function removeAppliedDiscount() {
            appliedDiscount = null;

            Swal.fire({
                icon: 'info',
                title: 'Diskon Dihapus',
                text: 'Diskon telah dihapus dari keranjang',
                timer: 2000,
                showConfirmButton: false
            });

            updateCartUI();
        }

        function calculateDiscount() {
            const subtotal = calculateSubtotal();

            if (appliedPromoCode) {
                const promo = appliedPromoCode;
                if (promo.type === 'percentage') {
                    const discountAmount = (subtotal * promo.value) / 100;
                    return Math.min(discountAmount, promo.maxDiscount || discountAmount);
                } else {
                    return Math.min(promo.value, subtotal);
                }
            }

            if (appliedDiscount) {
                const discount = appliedDiscount;
                if (discount.type === 'percentage') {
                    const discountAmount = (subtotal * discount.value) / 100;
                    return Math.min(discountAmount, discount.maxDiscount || discountAmount);
                } else {
                    return Math.min(discount.value, subtotal);
                }
            }

            return 0;
        }

        function addToCart(billId, showConfirmation = true) {
            const bill = bills[billId];
            if (!bill) {
                console.error('Bill not found:', billId);
                return false;
            }

            const existingItem = cart.find(item => item.id === billId);
            if (existingItem) {
                if (showConfirmation) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Tagihan Sudah Ada',
                        text: `${bill.name} sudah ada di keranjang`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
                return false;
            }

            cart.push({...bill});
            updateCartUI();
            updateCheckboxes();

            if (showConfirmation) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Ditambahkan',
                    text: `${bill.name} telah ditambahkan ke keranjang`,
                    timer: 1500,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            }

            console.log('Added to cart:', bill.name);
            return true;
        }

        // Remove from cart with confirmation
        function removeFromCart(billId, showConfirmation = true) {
            const bill = bills[billId];
            const originalLength = cart.length;
            cart = cart.filter(item => item.id !== parseInt(billId));

            if (cart.length !== originalLength) {
                updateCartUI();
                updateCheckboxes();

                if (showConfirmation && bill) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Tagihan Dihapus',
                        text: `${bill.name} telah dihapus dari keranjang`,
                        timer: 1500,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true
                    });
                }
                console.log('Removed from cart:', bill?.name || billId);
                return true;
            }
            return false;
        }

        // Clear cart with confirmation
        function clearCart() {
            if (cart.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Keranjang Kosong',
                    text: 'Tidak ada item dalam keranjang',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            Swal.fire({
                title: 'Kosongkan Keranjang?',
                text: `Apakah Anda yakin ingin menghapus ${cart.length} item dari keranjang?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Kosongkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    cart = [];
                    updateCartUI();
                    updateCheckboxes();

                    Swal.fire({
                        icon: 'success',
                        title: 'Keranjang Dikosongkan',
                        text: 'Semua item telah dihapus dari keranjang',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    console.log('Cart cleared');
                }
            });
        }

        // Add all bills to cart with confirmation
        function addAllToCart() {
            const availableBills = Object.values(bills).filter(bill =>
                !cart.find(item => item.id === bill.id)
            );

            if (availableBills.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Semua Item Sudah Dipilih',
                    text: 'Semua tagihan sudah ada di keranjang',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            const totalAmount = availableBills.reduce((sum, bill) => sum + bill.amount, 0);

            Swal.fire({
                title: 'Pilih Semua Tagihan?',
                html: `
                    <div class="text-start">
                        <p class="mb-3">Anda akan menambahkan ${availableBills.length} tagihan ke keranjang:</p>
                        <ul class="list-unstyled">
                            ${availableBills.map(bill =>
                                `<li class="mb-2">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    <strong>${bill.name}</strong><br>
                                    <small class="text-muted ms-4">Rp ${bill.amount.toLocaleString('id-ID')}</small>
                                </li>`
                            ).join('')}
                        </ul>
                        <hr>
                        <div class="text-center">
                            <strong>Total Tambahan: Rp ${totalAmount.toLocaleString('id-ID')}</strong>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Pilih Semua',
                cancelButtonText: 'Batal',
                width: '500px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Add all bills with animation
                    availableBills.forEach((bill, index) => {
                        setTimeout(() => {
                            addToCart(bill.id, false); // No individual confirmation
                        }, index * 200);
                    });

                    // Show success message after all items are added
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Semua Tagihan Ditambahkan!',
                            text: `${availableBills.length} tagihan berhasil ditambahkan ke keranjang`,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }, availableBills.length * 200 + 100);
                }
            });
        }

        // Update cart UI
        function updateCartUI() {
            const cartCount = document.getElementById('cart-count');
            const cartItems = document.getElementById('cart-items');
            const cartSummary = document.getElementById('cart-summary');
            const emptyCart = document.getElementById('empty-cart');
            const checkoutBtn = document.getElementById('checkout-btn');
            const selectedInfo = document.getElementById('selected-info');

            // Update counters
            if (cartCount) cartCount.textContent = cart.length;
            if (selectedInfo) selectedInfo.textContent = `${cart.length} dari 4 item dipilih`;

            if (cart.length === 0) {
                // Show empty state
                if (emptyCart) emptyCart.style.display = 'block';
                if (cartSummary) cartSummary.classList.add('d-none');
                if (checkoutBtn) checkoutBtn.disabled = true;

                // Remove all cart items
                const existingItems = cartItems ? cartItems.querySelectorAll('.cart-item') : [];
                existingItems.forEach(item => item.remove());
            } else {
                // Hide empty state
                if (emptyCart) emptyCart.style.display = 'none';
                if (cartSummary) cartSummary.classList.remove('d-none');
                if (checkoutBtn) checkoutBtn.disabled = false;

                // Clear existing cart items
                const existingItems = cartItems ? cartItems.querySelectorAll('.cart-item') : [];
                existingItems.forEach(item => item.remove());

                // Add new cart items
                cart.forEach(item => {
                    const cartItemHTML = `
                        <div class="cart-item border rounded p-2 mb-2" style="background: #f8f9fa;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-1 small">${item.name}</h6>
                                    <p class="text-muted small mb-1">${item.description}</p>
                                    <span class="fw-bold text-primary small">Rp ${item.amount.toLocaleString('id-ID')}</span>
                                </div>
                                <button class="btn btn-sm btn-outline-danger ms-2 remove-item-btn" data-bill-id="${item.id}" type="button">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    if (cartItems) {
                        cartItems.insertAdjacentHTML('beforeend', cartItemHTML);
                    }
                });

                // Attach event listeners to new remove buttons
                const removeButtons = cartItems ? cartItems.querySelectorAll('.remove-item-btn') : [];
                removeButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const billId = parseInt(this.getAttribute('data-bill-id'));
                        removeFromCart(billId);
                    });
                });

                // Update summary
                const subtotal = calculateSubtotal();
                const discount = calculateDiscount();
                const adminFee = 2500;
                const total = subtotal - discount + adminFee;

                const subtotalElement = document.getElementById('subtotal');
                const discountElement = document.getElementById('discountAmount');
                const discountRow = document.getElementById('discountRow');
                const totalElement = document.getElementById('total-amount');

                if (subtotalElement) {
                    subtotalElement.textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
                }
                if (discountElement && discountRow) {
                    if (discount > 0) {
                        discountElement.textContent = `- Rp ${discount.toLocaleString('id-ID')}`;
                        discountRow.style.display = 'flex';
                        discountRow.classList.add('discount-row');
                    } else {
                        discountRow.style.display = 'none';
                    }
                }
                if (totalElement) {
                    totalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
                }

                // Update applied discount display
                updateAppliedDiscountDisplay();
            }
        }

        function calculateSubtotal() {
            return cart.reduce((sum, item) => sum + item.amount, 0);
        }

        function updateAppliedDiscountDisplay() {
            const appliedDiscountElement = document.getElementById('appliedDiscount');
            if (!appliedDiscountElement) return;

            if (appliedPromoCode) {
                const discount = appliedPromoCode;
                const discountText = discount.type === 'percentage'
                    ? `${discount.value}%`
                    : `Rp ${discount.value.toLocaleString('id-ID')}`;

                appliedDiscountElement.innerHTML = `
                    <div class="applied-discount-item bg-success bg-opacity-10 p-2 rounded border border-success border-opacity-25">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold small text-success">
                                    <i class="bi bi-tag me-1"></i>
                                    ${discount.name}
                                </div>
                                <div class="text-muted small">${discount.description}</div>
                                <div class="text-success small">Diskon ${discountText}</div>
                            </div>
                            <button class="btn btn-sm btn-outline-danger" onclick="removePromoCode()" type="button">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                `;
            } else if (appliedDiscount) {
                const discount = appliedDiscount;
                const discountText = discount.type === 'percentage'
                    ? `${discount.value}%`
                    : `Rp ${discount.value.toLocaleString('id-ID')}`;

                appliedDiscountElement.innerHTML = `
                    <div class="applied-discount-item bg-primary bg-opacity-10 p-2 rounded border border-primary border-opacity-25">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold small text-primary">
                                    <i class="${discount.icon} me-1"></i>
                                    ${discount.name}
                                </div>
                                <div class="text-muted small">${discount.description}</div>
                                <div class="text-primary small">Diskon ${discountText}</div>
                            </div>
                            <button class="btn btn-sm btn-outline-danger" onclick="removeAppliedDiscount()" type="button">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                `;
            } else {
                appliedDiscountElement.innerHTML = '';
            }
        }

        // Update checkboxes
        function updateCheckboxes() {
            const checkboxes = document.querySelectorAll('.bill-checkbox');
            checkboxes.forEach(checkbox => {
                const billId = parseInt(checkbox.value);
                const isInCart = cart.some(item => item.id === billId);
                const billItem = checkbox.closest('.bill-item');

                // Update checkbox state
                checkbox.checked = isInCart;

                // Update visual state of bill item
                if (isInCart) {
                    billItem?.classList.add('selected');
                } else {
                    billItem?.classList.remove('selected');
                }
            });

            // Update select all checkbox
            const selectAllCheckbox = document.getElementById('selectAll');
            if (selectAllCheckbox) {
                const totalBills = Object.keys(bills).length;
                const selectedCount = cart.length;

                if (selectedCount === 0) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else if (selectedCount === totalBills) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                }
            }
        }

        // Handle checkout process
        function handleCheckout() {
            if (cart.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Keranjang Kosong',
                    text: 'Pilih minimal satu tagihan untuk melakukan pembayaran',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            const subtotal = calculateSubtotal();
            const discount = calculateDiscount();
            const adminFee = 2500;
            const totalAmount = subtotal - discount + adminFee;

            // Validate minimum amount (Rp 100,000)
            if (totalAmount < 100000) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Jumlah Minimum',
                    text: 'Total pembayaran minimal Rp 100.000',
                    timer: 3000,
                    showConfirmButton: false
                });
                return;
            }

            // Validate maximum amount (Rp 10,000,000)
            if (totalAmount > 10000000) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Jumlah Maksimum',
                    text: 'Total pembayaran maksimal Rp 10.000.000',
                    timer: 3000,
                    showConfirmButton: false
                });
                return;
            }

            // Prepare cart items data
            const cartItems = cart.map(item => ({
                id: item.id,
                name: item.name,
                amount: item.amount
            }));

            // Show confirmation dialog
            const discountText = discount > 0 ? `\nDiskon: -Rp ${discount.toLocaleString('id-ID')}` : '';
            const confirmationText = `Konfirmasi pembayaran:\n\nSubtotal: Rp ${subtotal.toLocaleString('id-ID')}${discountText}\nBiaya Admin: Rp ${adminFee.toLocaleString('id-ID')}\nTotal: Rp ${totalAmount.toLocaleString('id-ID')}\n\nAnda akan diarahkan ke halaman pembayaran Xendit.`;

            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: confirmationText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Bayar Sekarang',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    return new Promise((resolve) => {
                        // Show loading
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Menyiapkan halaman pembayaran',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit payment form
                        submitPaymentForm(totalAmount, cartItems);
                        resolve();
                    });
                }
            });
        }

        function submitPaymentForm(totalAmount, cartItems) {
            console.log('=== SUBMIT PAYMENT FORM DEBUG ===');
            console.log('Total Amount:', totalAmount);
            console.log('Cart Items:', cartItems);
            console.log('Applied Promo Code:', appliedPromoCode);
            console.log('Applied Discount:', appliedDiscount);

            // Check if form exists
            const form = document.getElementById('payment-form');
            if (!form) {
                console.error('Payment form not found!');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Form pembayaran tidak ditemukan. Silakan refresh halaman.'
                });
                return;
            }

            // Populate form data
            const amountField = document.getElementById('payment-amount');
            const itemsField = document.getElementById('payment-items');
            const promoField = document.getElementById('payment-promo-code');
            const discountField = document.getElementById('payment-discount-id');

            if (amountField) {
                amountField.value = totalAmount;
                console.log('Amount field set:', amountField.value);
            } else {
                console.error('Amount field not found');
            }

            if (itemsField) {
                itemsField.value = JSON.stringify(cartItems);
                console.log('Items field set:', itemsField.value);
            } else {
                console.error('Items field not found');
            }

            // Add promo code if applied
            if (appliedPromoCode && promoField) {
                promoField.value = appliedPromoCode.code;
                console.log('Promo code set:', promoField.value);
            }

            // Add discount if applied
            if (appliedDiscount && discountField) {
                discountField.value = appliedDiscount.id;
                console.log('Discount ID set:', discountField.value);
            }

            console.log('Form action:', form.action);
            console.log('Form method:', form.method);

            // Submit form
            console.log('Submitting form...');
            form.submit();
        }        // Show bill detail
        function showBillDetail(billId) {
            const bill = bills[billId];
            if (!bill) return;

            currentBillDetail = bill;

            const content = `
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="fw-bold">${bill.name}</h5>
                        <p class="text-muted">${bill.description}</p>
                        <div class="bg-light rounded p-3 mb-3">
                            <h6 class="fw-semibold mb-2">Detail Tagihan:</h6>
                            <p class="mb-0">${bill.details}</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-warning text-dark me-2">
                                <i class="bi bi-clock me-1"></i>Belum Dibayar
                            </span>
                            <small class="text-muted">Jatuh tempo: ${bill.dueDate}</small>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <h6 class="text-muted mb-1">Total Tagihan</h6>
                            <h3 class="fw-bold text-primary mb-0">Rp ${bill.amount.toLocaleString('id-ID')}</h3>
                        </div>
                    </div>
                </div>
            `;

            const billDetailContent = document.getElementById('bill-detail-content');
            if (billDetailContent) {
                billDetailContent.innerHTML = content;
            }

            // Update modal button
            const addToCartBtn = document.getElementById('add-to-cart-from-modal');
            const isInCart = cart.some(item => item.id === billId);

            if (addToCartBtn) {
                if (isInCart) {
                    addToCartBtn.innerHTML = '<i class="bi bi-check me-1"></i>Sudah di Keranjang';
                    addToCartBtn.disabled = true;
                    addToCartBtn.classList.remove('btn-primary');
                    addToCartBtn.classList.add('btn-success');
                } else {
                    addToCartBtn.innerHTML = '<i class="bi bi-cart-plus me-1"></i>Tambah ke Keranjang';
                    addToCartBtn.disabled = false;
                    addToCartBtn.classList.remove('btn-success');
                    addToCartBtn.classList.add('btn-primary');
                }
            }

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('billDetailModal'));
            modal.show();
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Initializing payment dashboard...');

            // Mobile touch improvements
            if ('ontouchstart' in window) {
                document.body.classList.add('touch-device');

                // Add touch feedback for bill items
                document.querySelectorAll('.bill-item').forEach(item => {
                    item.addEventListener('touchstart', function() {
                        this.style.transform = 'scale(0.98)';
                    });

                    item.addEventListener('touchend', function() {
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 100);
                    });
                });
            }

            // Setup checkbox event listeners with improved mobile handling
            const checkboxes = document.querySelectorAll('.bill-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const billId = parseInt(this.value);
                    const billItem = this.closest('.bill-item');

                    console.log('Checkbox changed:', billId, 'checked:', this.checked);

                    if (this.checked) {
                        if (addToCart(billId)) {
                            billItem?.classList.add('selected');
                            // Visual feedback
                            this.parentElement.style.transform = 'scale(1.1)';
                            setTimeout(() => {
                                this.parentElement.style.transform = '';
                            }, 200);
                        } else {
                            // If add failed, uncheck the checkbox
                            this.checked = false;
                        }
                    } else {
                        if (removeFromCart(billId)) {
                            billItem?.classList.remove('selected');
                        }
                    }
                });

                // Prevent double-tap zoom on mobile
                checkbox.addEventListener('touchend', function(e) {
                    e.preventDefault();
                    this.click();
                });
            });

            // Setup select all checkbox with improved feedback and confirmation
            const selectAllCheckbox = document.getElementById('selectAll');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const isChecking = this.checked;

                    console.log('Select all checkbox changed:', isChecking);

                    // Visual feedback
                    this.parentElement.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        this.parentElement.style.transform = '';
                    }, 200);

                    if (isChecking) {
                        // Add all bills to cart with confirmation
                        addAllToCart();
                    } else {
                        // Remove all items from cart with confirmation
                        if (cart.length > 0) {
                            Swal.fire({
                                title: 'Hapus Semua Tagihan?',
                                text: `Apakah Anda yakin ingin menghapus ${cart.length} tagihan dari keranjang?`,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: 'Ya, Hapus Semua',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    cart = [];
                                    updateCartUI();
                                    updateCheckboxes();

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Semua Tagihan Dihapus',
                                        text: 'Keranjang telah dikosongkan',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                } else {
                                    // If cancelled, check the select all checkbox again
                                    this.checked = true;
                                }
                            });
                        }
                    }
                });
            }

            // Initialize available discounts
            loadAvailableDiscounts();

            // Setup promo code input
            const promoCodeInput = document.getElementById('promoCodeInput');
            if (promoCodeInput) {
                promoCodeInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        applyPromoCode();
                    }
                });
            }

            // Setup view detail buttons with improved mobile handling
            document.querySelectorAll('.view-detail').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Visual feedback
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 100);

                    const billId = parseInt(this.getAttribute('data-bill-id'));
                    showBillDetail(billId);
                });
            });

            // Setup clear cart button with confirmation
            const clearCartBtn = document.getElementById('clear-cart');
            if (clearCartBtn) {
                clearCartBtn.addEventListener('click', function() {
                    clearCart(); // This function now has built-in confirmation
                });
            }

            // Setup checkout button
            const checkoutBtn = document.getElementById('checkout-btn');
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', function() {
                    handleCheckout();
                });
            }

            // Setup add to cart from modal
            const addToCartFromModalBtn = document.getElementById('add-to-cart-from-modal');
            if (addToCartFromModalBtn) {
                addToCartFromModalBtn.addEventListener('click', function() {
                    if (currentBillDetail && addToCart(currentBillDetail.id, false)) {
                        // Visual feedback
                        this.innerHTML = '<i class="bi bi-check-circle me-1"></i>Berhasil Ditambahkan';
                        this.classList.remove('btn-primary');
                        this.classList.add('btn-success');

                        // Show success toast
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Ditambahkan',
                            text: `${currentBillDetail.name} telah ditambahkan ke keranjang`,
                            timer: 1500,
                            showConfirmButton: false,
                            position: 'top-end',
                            toast: true
                        });

                        setTimeout(() => {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('billDetailModal'));
                            if (modal) modal.hide();
                        }, 1000);
                    }
                });
            }

            // Handle bill item clicks (for mobile-friendly interaction)
            document.querySelectorAll('.bill-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    // Don't trigger if clicking on buttons or checkboxes
                    if (e.target.matches('button, input, .btn, .form-check-input, .view-detail')) {
                        return;
                    }

                    const checkbox = this.querySelector('.bill-checkbox');
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            });

            // Responsive handling
            function handleResize() {
                const isMobile = window.innerWidth < 768;
                const cartSection = document.querySelector('.sticky-top');

                if (isMobile && cartSection) {
                    cartSection.classList.remove('sticky-top');
                } else if (!isMobile && cartSection) {
                    cartSection.classList.add('sticky-top');
                }
            }

            // Initial resize check
            handleResize();

            // Listen for resize events
            window.addEventListener('resize', handleResize);

            // Initialize UI
            updateCartUI();

            // Debug info
            console.log('=== Payment Dashboard Debug Info ===');
            console.log('Payment dashboard initialized successfully');
            console.log('Bills data:', bills);
            console.log('Touch device:', 'ontouchstart' in window);
            console.log('Total bills available:', Object.keys(bills).length);
            console.log('Cart initial state:', cart);
            console.log('SweetAlert2 loaded:', typeof Swal !== 'undefined');
            console.log('AOS loaded:', typeof AOS !== 'undefined');
            console.log('=====================================');
        });

        // Fallback alert function if SweetAlert fails
        function showAlert(title, text, icon = 'info') {
            if (typeof Swal !== 'undefined') {
                return Swal.fire({
                    icon: icon,
                    title: title,
                    text: text,
                    confirmButtonText: 'OK'
                });
            } else {
                // Fallback to native alert
                alert(title + '\n\n' + text);
                return Promise.resolve({ isConfirmed: true });
            }
        }

        // Fallback confirm function if SweetAlert fails
        function showConfirm(title, text, confirmText = 'Ya', cancelText = 'Batal') {
            if (typeof Swal !== 'undefined') {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText
                });
            } else {
                // Fallback to native confirm
                const result = confirm(title + '\n\n' + text);
                return Promise.resolve({ isConfirmed: result });
            }
        }

        // Override functions to use fallbacks
        if (typeof Swal === 'undefined') {
            console.warn('SweetAlert2 not loaded, using fallback functions');

            // Override cart functions to use fallback alerts
            const originalAddAllToCart = addAllToCart;
            addAllToCart = function() {
                const availableBills = Object.values(bills).filter(bill =>
                    !cart.find(item => item.id === bill.id)
                );

                if (availableBills.length === 0) {
                    showAlert('Info', 'Semua tagihan sudah ada di keranjang');
                    return;
                }

                const billList = availableBills.map(bill => `- ${bill.name} (Rp ${bill.amount.toLocaleString('id-ID')})`).join('\n');
                const totalAmount = availableBills.reduce((sum, bill) => sum + bill.amount, 0);

                showConfirm(
                    'Pilih Semua Tagihan?',
                    `Anda akan menambahkan ${availableBills.length} tagihan:\n\n${billList}\n\nTotal: Rp ${totalAmount.toLocaleString('id-ID')}`
                ).then(result => {
                    if (result.isConfirmed) {
                        availableBills.forEach(bill => {
                            addToCart(bill.id, false);
                        });
                        showAlert('Berhasil', `${availableBills.length} tagihan berhasil ditambahkan ke keranjang`, 'success');
                    }
                });
            };
        }
    </script>
    @endpush

    @push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- AOS CSS Fallback -->
    <script>
        // Check if AOS CSS loaded, if not load fallback
        setTimeout(function() {
            const testEl = document.createElement('div');
            testEl.className = 'aos-init';
            testEl.style.visibility = 'hidden';
            testEl.style.position = 'absolute';
            document.body.appendChild(testEl);

            const computed = window.getComputedStyle(testEl);
            if (computed.opacity !== '0') {
                console.log('Loading AOS CSS fallback...');
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css';
                document.head.appendChild(link);
            }
            document.body.removeChild(testEl);
        }, 100);
    </script>
    <style>
        .bill-item {
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid #e9ecef !important;
        }

        .bill-item:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
            border-color: #0d6efd !important;
        }

        .cart-item {
            background: #f8f9fa;
            transition: all 0.3s ease;
            border: 1px solid #dee2e6 !important;
        }

        .cart-item:hover {
            background: #e9ecef;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .form-check-input:focus {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .sticky-top {
            z-index: 1020;
        }

        /* Promo code and discount styles */
        .discount-item {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .discount-item:hover {
            background-color: #f8f9fa !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }

        .applied-discount-item {
            animation: slideInDown 0.3s ease-out;
        }

        .promo-message {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .discount-row {
            animation: slideInDown 0.3s ease-out;
        }

        /* Responsive improvements */
        @media (max-width: 991.98px) {
            .sticky-top {
                position: relative !important;
                top: auto !important;
            }

            .bill-item .col-1 {
                flex: 0 0 auto;
                width: 8.33333333%;
            }

            .bill-item .col-8 {
                flex: 0 0 auto;
                width: 66.66666667%;
            }

            .bill-item .col-3 {
                flex: 0 0 auto;
                width: 25%;
            }
        }

        @media (max-width: 767.98px) {
            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
            }

            .card-body {
                padding: 1rem;
            }

            .bill-item {
                padding: 1rem !important;
                margin-bottom: 1rem !important;
            }

            .bill-item .row {
                --bs-gutter-x: 0.5rem;
            }

            .bill-item .col-1 {
                flex: 0 0 auto;
                width: 10%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .bill-item .col-8 {
                flex: 0 0 auto;
                width: 55%;
                padding-right: 0.5rem;
            }

            .bill-item .col-3 {
                flex: 0 0 auto;
                width: 35%;
                text-align: center !important;
            }

            .bill-item h6 {
                font-size: 0.9rem;
                line-height: 1.3;
            }

            .bill-item .text-muted.small {
                font-size: 0.75rem;
            }

            .bill-item h5 {
                font-size: 1rem;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }

            .badge {
                font-size: 0.65rem;
                padding: 0.35em 0.5em;
            }

            .cart-item {
                padding: 0.75rem !important;
            }

            .cart-item h6 {
                font-size: 0.85rem;
            }

            .cart-item .small {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 575.98px) {
            .bill-item {
                padding: 0.75rem !important;
            }

            .bill-item .col-1 {
                width: 12%;
            }

            .bill-item .col-8 {
                width: 50%;
            }

            .bill-item .col-3 {
                width: 38%;
            }

            .bill-item h6 {
                font-size: 0.85rem;
                margin-bottom: 0.25rem;
            }

            .bill-item .badge {
                display: none;
            }

            .bill-item h5 {
                font-size: 0.9rem;
                margin-bottom: 0.25rem;
            }

            .btn-sm {
                padding: 0.2rem 0.4rem;
                font-size: 0.7rem;
            }

            .btn-sm i {
                font-size: 0.7rem;
            }
        }

        /* Summary cards responsive */
        @media (max-width: 767.98px) {
            .summary-card {
                margin-bottom: 1rem;
            }

            .summary-card .card-body {
                padding: 1rem;
            }

            .summary-card h3 {
                font-size: 1.5rem;
            }

            .summary-card .fs-4 {
                font-size: 1.2rem !important;
            }
        }

        /* Form elements responsive */
        .form-check-input {
            width: 1.2em;
            height: 1.2em;
        }

        @media (max-width: 575.98px) {
            .form-check-input {
                width: 1em;
                height: 1em;
            }
        }

        /* Modal responsive */
        @media (max-width: 575.98px) {
            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-content {
                border-radius: 0.5rem;
            }
        }

        /* Button improvements */
        .btn {
            border-radius: 0.5rem;
        }

        .btn-lg {
            border-radius: 0.75rem;
        }

        /* Card improvements */
        .card {
            border-radius: 1rem;
            border: none;
        }

        .rounded-3 {
            border-radius: 0.75rem !important;
        }

        /* Animation improvements */
        .bill-item.selected {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* Loading state */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Cart improvements */
        .cart-item .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
        }

        .cart-item .btn-outline-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        /* Touch device improvements */
        .touch-device .bill-item {
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
        }

        .touch-device .btn {
            -webkit-tap-highlight-color: transparent;
        }

        /* Enhanced hover and active states for mobile */
        @media (hover: none) and (pointer: coarse) {
            .bill-item:active {
                background-color: rgba(13, 110, 253, 0.1);
                transform: scale(0.98);
            }

            .btn:active {
                transform: scale(0.95);
            }
        }

        /* Accessibility improvements */
        .form-check-input:focus-visible {
            outline: 2px solid #0d6efd;
            outline-offset: 2px;
        }

        .btn:focus-visible {
            outline: 2px solid #0d6efd;
            outline-offset: 2px;
        }

        /* Loading animations */
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .btn:disabled .bi-hourglass-split {
            animation: pulse 1s infinite;
        }

        /* Smooth transitions for cart items */
        .cart-item {
            transition: all 0.3s ease, opacity 0.3s ease, transform 0.3s ease;
        }

        /* Bill item selection visual feedback */
        .bill-item.selected {
            border-color: #0d6efd !important;
            background-color: rgba(13, 110, 253, 0.05);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* Modal improvements for mobile */
        @media (max-width: 575.98px) {
            .modal-dialog {
                margin: 0.25rem;
                width: calc(100% - 0.5rem);
                max-width: none;
            }

            .modal-content {
                border-radius: 1rem;
            }

            .modal-header {
                padding: 1rem;
                border-bottom: 1px solid #dee2e6;
            }

            .modal-body {
                padding: 1rem;
            }

            .modal-footer {
                padding: 1rem;
                border-top: 1px solid #dee2e6;
            }
        }

        /* Sticky cart improvements */
        @media (min-width: 992px) {
            .sticky-top {
                top: 2rem !important;
            }
        }

        /* Better focus indicators */
        *:focus {
            outline: none;
        }

        .form-check-input:focus,
        .btn:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        /* Improved checkbox styling */
        .form-check-input {
            border-width: 2px;
            border-color: #ced4da;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .form-check-input:indeterminate {
            background-color: #0d6efd;
            border-color: #0d6efd;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10h8'/%3e%3c/svg%3e");
        }

        /* SweetAlert2 Custom Styling */
        .swal2-popup {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            border-radius: 1rem;
        }

        .swal2-title {
            font-weight: 600;
            color: #212529;
        }

        .swal2-content {
            color: #6c757d;
        }

        .swal2-confirm {
            border-radius: 0.5rem;
            padding: 0.5rem 1.5rem;
        }

        .swal2-cancel {
            border-radius: 0.5rem;
            padding: 0.5rem 1.5rem;
        }

        /* Toast positioning for mobile */
        @media (max-width: 768px) {
            .swal2-toast {
                font-size: 0.875rem;
            }
        }

        /* Loading animation for SweetAlert */
        .swal2-loading .swal2-styled.swal2-confirm {
            background-color: #0d6efd !important;
        }
    </style>
    @endpush
</x-app-layout>

