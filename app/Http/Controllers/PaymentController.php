<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentController extends Controller
{
    private $xenditApiKey;
    private $xenditWebhookToken;

    // Payment status constants
    const STATUS_PENDING = 'PENDING';
    const STATUS_PAID = 'PAID';
    const STATUS_EXPIRED = 'EXPIRED';
    const STATUS_FAILED = 'FAILED';
    const STATUS_CANCELLED = 'CANCELLED';

    // Invoice duration in seconds (1 hour)
    const INVOICE_DURATION = 3600;

    public function __construct()
    {
        $this->xenditApiKey = env('XENDIT_SECRET_KEY');
        $this->xenditWebhookToken = env('XENDIT_WEBHOOK_TOKEN');

        if (!$this->xenditApiKey) {
            Log::error('Xendit API Key not configured');
        }
    }

    /**
     * Always use Xendit - NO DEMO MODE
     */
    private function isDemoMode(): bool
    {
        return false; // NEVER use demo mode
    }

    private function isProductionMode(): bool
    {
        return true; // Always production mode
    }

    /**
     * Get Xendit base URL
     */
    private function getXenditBaseUrl(): string
    {
        return 'https://api.xendit.co';
    }

    /**
     * Get Xendit environment based on API key
     */
    private function getXenditEnvironment(): string
    {
        if (str_contains($this->xenditApiKey ?? '', 'development') ||
            str_contains($this->xenditApiKey ?? '', 'test')) {
            return 'test';
        }
        return 'live';
    }

    /**
     * Get webhook URL for external systems
     */
    private function getWebhookUrl(): string
    {
        return route('payment.webhook');
    }

    /**
     * Check if API key is valid format
     */
    private function isValidApiKey(): bool
    {
        $isValid = !empty($this->xenditApiKey) &&
                   (str_contains($this->xenditApiKey, 'xnd_') ||
                    str_contains($this->xenditApiKey, 'development_'));

        Log::info('API Key validation', [
            'has_key' => !empty($this->xenditApiKey),
            'key_prefix' => substr($this->xenditApiKey ?? '', 0, 10),
            'is_valid' => $isValid
        ]);

        return $isValid;
    }

    /**
     * Get payment timeout in minutes
     */
    private function getPaymentTimeout(): int
    {
        return self::INVOICE_DURATION / 60; // Convert to minutes
    }

    /**
     * Format amount to rupiah
     */
    private function formatRupiah(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    /**
     * Get payment gateway name
     */
    private function getPaymentGateway(): string
    {
        return 'Xendit';
    }

    /**
     * Check if payment method is available
     */
    private function isPaymentMethodAvailable(string $method): bool
    {
        $availableMethods = [
            'BANK_TRANSFER',
            'CREDIT_CARD',
            'EWALLET',
            'QR_CODE',
            'RETAIL_OUTLET'
        ];

        return in_array(strtoupper($method), $availableMethods);
    }

    /**
     * Get payment amount based on jenjang
     */
    private function getPaymentAmount(string $jenjang): int
    {
        $paymentAmounts = [
            'sanggar' => 325000,
            'kelompok' => 325000,
            'tka' => 355000,
            'tkb' => 355000,
            'sd' => 425000,
            'smp' => 455000,
            'sma' => 525000,
        ];

        return $paymentAmounts[strtolower($jenjang)] ?? 350000;
    }

    /**
     * Get jenjang display name
     */
    private function getJenjangName(string $jenjang): string
    {
        $jenjangNames = [
            'sanggar' => 'Sanggar Bermain',
            'kelompok' => 'Kelompok Bermain',
            'tka' => 'TK A',
            'tkb' => 'TK B',
            'sd' => 'SD',
            'smp' => 'SMP',
            'sma' => 'SMA',
        ];

        return $jenjangNames[strtolower($jenjang)] ?? strtoupper($jenjang);
    }

    /**
     * Format phone number to international format
     */
    private function formatPhoneNumber(?string $phone): string
    {
        if (empty($phone)) {
            return '+6281234567890';
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '+62' . substr($phone, 1);
        }

        if (!str_starts_with($phone, '+62')) {
            return '+62' . $phone;
        }

        return $phone;
    }

    /**
     * Generate unique external ID
     */
    private function generateExternalId(Pendaftar $pendaftar): string
    {
        return sprintf(
            'PPDB-PMB%02d%02d%04d-%d-%s',
            now()->month,
            now()->day,
            $pendaftar->id,
            time(),
            Str::random(6)
        );
    }

    /**
     * Check if payment is expired (more than 1 hour)
     */
    private function isPaymentExpired(Payment $payment): bool
    {
        return $payment->created_at->addHour()->isPast();
    }

    /**
     * Cleanup expired payments
     */
    private function cleanupExpiredPayments(int $pendaftarId): void
    {
        $expiredPayments = Payment::where('pendaftar_id', $pendaftarId)
            ->where('status', self::STATUS_PENDING)
            ->where('created_at', '<', now()->subHour())
            ->get();

        foreach ($expiredPayments as $payment) {
            $payment->update([
                'status' => self::STATUS_EXPIRED,
                'expired_at' => now()
            ]);

            Log::info('Payment expired automatically', [
                'payment_id' => $payment->id,
                'external_id' => $payment->external_id
            ]);
        }
    }

    /**
     * Map Xendit status to internal status
     */
    private function mapXenditStatus(string $xenditStatus): string
    {
        $statusMap = [
            'PENDING' => self::STATUS_PENDING,
            'PAID' => self::STATUS_PAID,
            'SETTLED' => self::STATUS_PAID,
            'EXPIRED' => self::STATUS_EXPIRED,
            'FAILED' => self::STATUS_FAILED,
            'CANCELLED' => self::STATUS_CANCELLED,
        ];

        return $statusMap[strtoupper($xenditStatus)] ?? self::STATUS_FAILED;
    }

    /**
     * ✅ HANDLE PAYMENT SUCCESS - UPDATE STATUS & PENDAFTAR
     */
    private function handlePaymentSuccess(Payment $payment, array $webhookData = []): void
    {
        Log::info('=== HANDLING PAYMENT SUCCESS ===', [
            'payment_id' => $payment->id,
            'external_id' => $payment->external_id,
            'current_status' => $payment->status,
            'pendaftar_id' => $payment->pendaftar_id
        ]);

        DB::transaction(function() use ($payment, $webhookData) {
            // Update payment status to PAID
            $updateData = [
                'status' => self::STATUS_PAID,
                'paid_at' => now(),
                'xendit_response' => array_merge($payment->xendit_response ?? [], $webhookData)
            ];

            $payment->update($updateData);

            // Update pendaftar - mark as paid and update statuses
            $payment->pendaftar->update([
                'sudah_bayar_formulir' => true,
                'overall_status' => 'Sudah Bayar',
                'current_status' => 'Sudah Bayar'
            ]);

            Log::info('✅ Payment success processed successfully', [
                'payment_id' => $payment->id,
                'external_id' => $payment->external_id,
                'student_name' => $payment->pendaftar->nama_murid,
                'amount' => $payment->amount,
                'paid_at' => $payment->paid_at,
                'pendaftar_sudah_bayar' => true,
                'overall_status' => 'Sudah Bayar'
            ]);
        });
    }

    /**
     * ✅ HANDLE PAYMENT FAILURE/CANCELLATION
     */
    private function handlePaymentFailure(Payment $payment, string $status, array $webhookData = []): void
    {
        Log::info('=== HANDLING PAYMENT FAILURE ===', [
            'payment_id' => $payment->id,
            'external_id' => $payment->external_id,
            'new_status' => $status,
            'current_status' => $payment->status
        ]);

        DB::transaction(function() use ($payment, $status, $webhookData) {
            $updateData = [
                'status' => $status,
                'xendit_response' => array_merge($payment->xendit_response ?? [], $webhookData)
            ];

            // Set appropriate timestamp based on status
            switch ($status) {
                case self::STATUS_EXPIRED:
                    $updateData['expired_at'] = now();
                    break;
                case self::STATUS_FAILED:
                case self::STATUS_CANCELLED:
                    $updateData['failed_at'] = now();
                    break;
            }

            $payment->update($updateData);

            // Set sudah_bayar_formulir to false
            // We don't change overall_status to avoid overwriting admin-set statuses
            $payment->pendaftar->update([
                'sudah_bayar_formulir' => false
            ]);

            Log::info('Payment failure processed', [
                'payment_id' => $payment->id,
                'external_id' => $payment->external_id,
                'new_status' => $status,
                'timestamp' => now(),
                'pendaftar_sudah_bayar' => false
            ]);
        });
    }

    /**
     * ✅ PROCESS PAYMENT STATUS CHANGE
     */
    private function processPaymentStatusChange(Payment $payment, string $newStatus, array $webhookData = []): bool
    {
        $oldStatus = $payment->status;

        // Don't update if already in final state
        if (in_array($oldStatus, [self::STATUS_PAID, self::STATUS_FAILED, self::STATUS_CANCELLED])) {
            Log::info('Payment already in final state, skipping update', [
                'external_id' => $payment->external_id,
                'current_status' => $oldStatus,
                'attempted_status' => $newStatus
            ]);
            return false;
        }

        // Handle different status changes
        switch ($newStatus) {
            case self::STATUS_PAID:
                $this->handlePaymentSuccess($payment, $webhookData);
                return true;

            case self::STATUS_EXPIRED:
            case self::STATUS_FAILED:
            case self::STATUS_CANCELLED:
                $this->handlePaymentFailure($payment, $newStatus, $webhookData);
                return true;

            case self::STATUS_PENDING:
                // No action needed for pending status
                Log::info('Payment remains pending', [
                    'external_id' => $payment->external_id
                ]);
                return false;

            default:
                Log::warning('Unknown payment status', [
                    'external_id' => $payment->external_id,
                    'status' => $newStatus
                ]);
                return false;
        }
    }

    /**
     * ✅ CHECK PAYMENT STATUS DIRECTLY FROM XENDIT API
     */
    private function checkPaymentStatusFromXendit(Payment $payment): void
    {
        if (!$payment->invoice_id) {
            return;
        }

        try {
            $response = Http::withBasicAuth($this->xenditApiKey, '')
                ->timeout(10)
                ->get($this->getXenditBaseUrl() . '/v2/invoices/' . $payment->invoice_id);

            if ($response->successful()) {
                $invoiceData = $response->json();
                $xenditStatus = $invoiceData['status'] ?? 'PENDING';
                $newStatus = $this->mapXenditStatus($xenditStatus);

                Log::info('Checked payment status from Xendit', [
                    'payment_id' => $payment->id,
                    'xendit_status' => $xenditStatus,
                    'mapped_status' => $newStatus,
                    'current_status' => $payment->status
                ]);

                if ($newStatus !== $payment->status) {
                    $this->processPaymentStatusChange($payment, $newStatus, $invoiceData);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error checking payment status from Xendit', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function index()
    {
        $user = Auth::user();

        $pendaftar = Pendaftar::where('user_id', $user->id)
            ->with(['payments' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->first();

        if (!$pendaftar) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        // Cleanup expired payments
        $this->cleanupExpiredPayments($pendaftar->id);

        return view('user.payment.index', compact('pendaftar'));
    }

    public function adminIndex()
    {
        $pendaftars = Pendaftar::with(['latestPayment'])
            ->select('id', 'nama_murid', 'no_pendaftaran', 'unit', 'jenjang', 'payment_amount', 'sudah_bayar_formulir', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.payment.index', compact('pendaftars'));
    }

    public function createInvoice(Request $request)
    {
        // TAMBAHKAN DEBUG INI
        Log::info('=== PAYMENT CREATE INVOICE CALLED ===', [
            'request_data' => $request->all(),
            'route_name' => request()->route()->getName(),
            'url' => request()->url(),
            'method' => request()->method(),
            'user_id' => Auth::id()
        ]);

        $request->validate([
            'pendaftar_id' => 'required|exists:pendaftars,id',
            'amount' => 'required|numeric|min:100000|max:10000000',
            'items' => 'nullable|string',
            'promo_code' => 'nullable|string|max:50',
            'discount_id' => 'nullable|string|max:50'
        ]);

        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)
            ->where('id', $request->pendaftar_id)
            ->first();

        Log::info('Pendaftar found', [
            'pendaftar_id' => $pendaftar->id ?? 'NOT_FOUND',
            'sudah_bayar' => $pendaftar->sudah_bayar_formulir ?? 'N/A'
        ]);

        // Log cart data
        Log::info('Cart data received', [
            'items' => $request->items,
            'promo_code' => $request->promo_code,
            'discount_id' => $request->discount_id,
            'amount' => $request->amount
        ]);

        if (!$pendaftar) {
            Log::error('Pendaftar not found');
            return back()->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        if ($pendaftar->sudah_bayar_formulir) {
            Log::info('Payment already completed');
            return back()->with('info', 'Pembayaran sudah lunas.');
        }

        // SHOPPING CART: Skip amount validation for flexible cart payments
        Log::info('Skipping amount validation for shopping cart payment', [
            'request_amount' => $request->amount,
            'pendaftar_base_amount' => $pendaftar->payment_amount
        ]);

        Log::info('Starting payment creation...');

        // ALWAYS use Xendit - never demo
        return DB::transaction(function() use ($pendaftar) {
            Log::info('Inside transaction for payment creation');

            // Cleanup expired payments first
            $this->cleanupExpiredPayments($pendaftar->id);

            // Check for existing active payment (within 1 hour)
            $activePendingPayment = Payment::where('pendaftar_id', $pendaftar->id)
                ->where('status', self::STATUS_PENDING)
                ->where('created_at', '>', now()->subHour())
                ->first();

            if ($activePendingPayment) {
                Log::info('Found active pending payment', [
                    'payment_id' => $activePendingPayment->id,
                    'external_id' => $activePendingPayment->external_id,
                    'invoice_url' => $activePendingPayment->invoice_url
                ]);

                // Direct redirect to Xendit URL
                return redirect()->away($activePendingPayment->invoice_url);
            }

            Log::info('No active payment found, creating new Xendit invoice');

            // Create new Xendit invoice
            return $this->createXenditInvoice($pendaftar);
        });
    }

    private function createXenditInvoice(Pendaftar $pendaftar)
    {
        Log::info('=== CREATE XENDIT INVOICE START ===', [
            'pendaftar_id' => $pendaftar->id,
            'amount' => $pendaftar->payment_amount
        ]);

        try {
            if (!$this->isValidApiKey()) {
                Log::error('Invalid Xendit API Key');
                throw new \Exception('Xendit API Key tidak valid atau tidak dikonfigurasi');
            }

            $user = Auth::user();
            $externalId = $this->generateExternalId($pendaftar);
            $jenjangName = $this->getJenjangName($pendaftar->jenjang);

            Log::info('Xendit invoice data prepared', [
                'external_id' => $externalId,
                'amount' => $pendaftar->payment_amount,
                'student' => $pendaftar->nama_murid,
                'jenjang' => $jenjangName
            ]);

            // ✅ PERBAIKI KONFIGURASI INVOICE DATA
            $invoiceData = [
                'external_id' => $externalId,
                'amount' => (int)$pendaftar->payment_amount,
                'description' => sprintf(
                    'Biaya Pendaftaran PPDB %s - %s - %s',
                    $jenjangName,
                    $pendaftar->unit,
                    $pendaftar->nama_murid
                ),
                'invoice_duration' => self::INVOICE_DURATION,
                'customer' => [
                    'given_names' => $pendaftar->nama_murid,
                    'email' => $user->email,
                    'mobile_number' => $this->formatPhoneNumber($pendaftar->telp_ayah ?? '08123456789'),
                    'addresses' => [
                        [
                            'city' => 'Jakarta',
                            'country' => 'Indonesia',
                            'postal_code' => '12345',
                            'state' => 'DKI Jakarta',
                            'street_line1' => $pendaftar->alamat ?? 'Jakarta'
                        ]
                    ]
                ],
                'success_redirect_url' => route('user.payments.success'),
                'failure_redirect_url' => route('user.payments.failed'),
                'currency' => 'IDR',
                'webhook_url' => route('payment.webhook'),

                // ✅ HAPUS payment_methods array dan gunakan konfigurasi individual
                // 'payment_methods' => [...], // JANGAN GUNAKAN INI

                // ✅ KONFIGURASI PAYMENT METHODS INDIVIDUAL - CARA YANG BENAR
                'should_exclude_credit_card' => false,
                'should_send_email' => true,
                'should_authenticate_credit_card' => true,
                'locale' => 'id',

                // ✅ BANK TRANSFER / VIRTUAL ACCOUNT - AKTIFKAN SEMUA
                'available_banks' => [
                    [
                        'bank_code' => 'BCA',
                        'collection_type' => 'POOL',
                        'transfer_amount' => (int)$pendaftar->payment_amount
                    ],
                    [
                        'bank_code' => 'BNI',
                        'collection_type' => 'POOL',
                        'transfer_amount' => (int)$pendaftar->payment_amount
                    ],
                    [
                        'bank_code' => 'BRI',
                        'collection_type' => 'POOL',
                        'transfer_amount' => (int)$pendaftar->payment_amount
                    ],
                    [
                        'bank_code' => 'MANDIRI',
                        'collection_type' => 'POOL',
                        'transfer_amount' => (int)$pendaftar->payment_amount
                    ],
                    [
                        'bank_code' => 'PERMATA',
                        'collection_type' => 'POOL',
                        'transfer_amount' => (int)$pendaftar->payment_amount
                    ],
                    [
                        'bank_code' => 'CIMB',
                        'collection_type' => 'POOL',
                        'transfer_amount' => (int)$pendaftar->payment_amount
                    ]
                ],

                // ✅ E-WALLET - AKTIFKAN SEMUA
                'available_ewallets' => [
                    [
                        'ewallet_type' => 'OVO'
                    ],
                    [
                        'ewallet_type' => 'DANA'
                    ],
                    [
                        'ewallet_type' => 'LINKAJA'
                    ],
                    [
                        'ewallet_type' => 'SHOPEEPAY'
                    ]
                ],

                // ✅ RETAIL OUTLETS - AKTIFKAN ALFAMART & INDOMARET
                'available_retail_outlets' => [
                    [
                        'retail_outlet_name' => 'ALFAMART'
                    ],
                    [
                        'retail_outlet_name' => 'INDOMARET'
                    ]
                ],

                // ✅ QRIS - AKTIFKAN QR CODE
                'available_direct_debits' => [],

                // ✅ TAMBAHAN KONFIGURASI UNTUK MEMASTIKAN SEMUA METODE MUNCUL
                'items' => [
                    [
                        'name' => sprintf('Biaya Pendaftaran PPDB %s - %s', $jenjangName, $pendaftar->unit),
                        'quantity' => 1,
                        'price' => (int)$pendaftar->payment_amount,
                        'category' => 'Education'
                    ]
                ],

                // ✅ METADATA UNTUK TRACKING
                'customer_notification_preference' => [
                    'invoice_created' => ['email', 'sms'],
                    'invoice_reminder' => ['email', 'sms'],
                    'invoice_paid' => ['email', 'sms']
                ],

                // ✅ FEES - BIAYA DITANGGUNG CUSTOMER
                'fees' => [
                    [
                        'type' => 'xendit_fee',
                        'value' => 0 // Fee ditanggung merchant
                    ]
                ]
            ];

            Log::info('Sending request to Xendit with ALL payment methods', [
                'url' => $this->getXenditBaseUrl() . '/v2/invoices',
                'external_id' => $externalId,
                'available_banks_count' => count($invoiceData['available_banks']),
                'available_ewallets_count' => count($invoiceData['available_ewallets']),
                'available_retail_outlets_count' => count($invoiceData['available_retail_outlets']),
                'webhook_url' => $invoiceData['webhook_url'],
                'amount' => $invoiceData['amount']
            ]);

            $response = Http::withBasicAuth($this->xenditApiKey, '')
                ->timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'PPDB-YAPI/1.0'
                ])
                ->post($this->getXenditBaseUrl() . '/v2/invoices', $invoiceData);

            Log::info('Xendit response received', [
                'status' => $response->status(),
                'successful' => $response->successful()
            ]);

            if (!$response->successful()) {
                $errorResponse = $response->json();
                Log::error('Xendit API Error', [
                    'status' => $response->status(),
                    'response' => $errorResponse,
                    'body' => $response->body(),
                    'sent_data' => $invoiceData
                ]);

                throw new \Exception(
                    'Xendit API Error: ' . ($errorResponse['message'] ?? 'Unknown error') . ' (Status: ' . $response->status() . ')'
                );
            }

            $responseData = $response->json();

            if (!isset($responseData['invoice_url'])) {
                Log::error('Missing invoice_url in response', $responseData);
                throw new \Exception('Response Xendit tidak lengkap - missing invoice_url');
            }

            // ✅ LOG DETAIL PAYMENT METHODS YANG TERSEDIA DARI RESPONSE
            Log::info('✅ Xendit invoice created with payment methods details', [
                'invoice_id' => $responseData['id'],
                'external_id' => $externalId,
                'invoice_url' => $responseData['invoice_url'],

                // Detail payment methods dari response
                'available_payment_methods' => $responseData['available_payment_methods'] ?? 'not_provided',
                'available_banks' => $responseData['available_banks'] ?? 'not_provided',
                'available_ewallets' => $responseData['available_ewallets'] ?? 'not_provided',
                'available_retail_outlets' => $responseData['available_retail_outlets'] ?? 'not_provided',
                'available_direct_debits' => $responseData['available_direct_debits'] ?? 'not_provided',

                // Summary
                'total_payment_methods' => count($responseData['available_payment_methods'] ?? []),
                'response_status' => $responseData['status'] ?? 'unknown'
            ]);

            // Create payment record
            $payment = Payment::create([
                'pendaftar_id' => $pendaftar->id,
                'external_id' => $externalId,
                'invoice_id' => $responseData['id'],
                'invoice_url' => $responseData['invoice_url'],
                'amount' => $pendaftar->payment_amount,
                'status' => self::STATUS_PENDING,
                'xendit_response' => $responseData,
                'expires_at' => now()->addHour(),
            ]);

            Log::info('Payment record created successfully', [
                'payment_id' => $payment->id,
                'invoice_url' => $responseData['invoice_url'],
                'all_methods_configured' => true
            ]);

            Log::info('=== REDIRECTING TO XENDIT WITH ALL PAYMENT METHODS ===', [
                'invoice_url' => $responseData['invoice_url'],
                'expected_methods' => ['Credit Card', 'Virtual Account (BCA, BNI, BRI, Mandiri, Permata, CIMB)', 'E-Wallet (OVO, DANA, LinkAja, ShopeePay)', 'Retail (Alfamart, Indomaret)']
            ]);

            // DIRECT REDIRECT to Xendit invoice URL
            return redirect()->away($responseData['invoice_url']);

        } catch (\Exception $e) {
            Log::error('=== CREATE XENDIT INVOICE ERROR ===', [
                'message' => $e->getMessage(),
                'pendaftar_id' => $pendaftar->id,
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function success()
    {
        $user = Auth::user();

        // Get latest payment for this user - bisa PAID atau PENDING yang baru saja dibuat
        $payment = Payment::whereHas('pendaftar', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereIn('status', [self::STATUS_PAID, self::STATUS_PENDING])
        ->with(['pendaftar'])
        ->orderBy('created_at', 'desc')
        ->first();

        if (!$payment) {
            return redirect()->route('user.payments.index')
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // ✅ Jika status masih PENDING, coba cek ulang status dari Xendit
        if ($payment->status === self::STATUS_PENDING) {
            try {
                Log::info('Payment status is PENDING, checking from Xendit...', [
                    'payment_id' => $payment->id,
                    'external_id' => $payment->external_id
                ]);

                $this->checkPaymentStatusFromXendit($payment);
                $payment->refresh(); // Refresh data payment

                Log::info('Payment status after checking Xendit', [
                    'payment_id' => $payment->id,
                    'status' => $payment->status,
                    'sudah_bayar_formulir' => $payment->pendaftar->sudah_bayar_formulir
                ]);
            } catch (\Exception $e) {
                Log::warning('Failed to check payment status from Xendit', [
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Get pendaftar data
        $pendaftar = $payment->pendaftar;
        $jenjangName = $this->getJenjangName($pendaftar->jenjang);

        return view('user.payment.success', compact('payment', 'pendaftar', 'jenjangName'));
    }

    public function failed()
    {
        $user = Auth::user();

        // Get latest failed/expired payment for this user
        $payment = Payment::whereHas('pendaftar', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereIn('status', [self::STATUS_FAILED, self::STATUS_EXPIRED, self::STATUS_CANCELLED])
        ->with(['pendaftar'])
        ->orderBy('updated_at', 'desc')
        ->first();

        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        if (!$pendaftar) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        $jenjangName = $this->getJenjangName($pendaftar->jenjang);

        return view('user.payment.failed', compact('payment', 'pendaftar', 'jenjangName'));
    }

    public function transactions()
    {
        $user = Auth::user();

        $payments = Payment::whereHas('pendaftar', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['pendaftar'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('user.transactions.index', compact('payments'));
    }

    public function transactionDetail($id)
    {
        $user = Auth::user();

        $payment = Payment::whereHas('pendaftar', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['pendaftar'])
        ->findOrFail($id);

        $jenjangName = $this->getJenjangName($payment->pendaftar->jenjang);

        return view('user.transactions.show', compact('payment', 'jenjangName'));
    }

    /**
     * ✅ UPDATED: Handle payment status update from webhook
     */
    private function handlePaymentStatusUpdate(array $data): void
    {
        $externalId = $data['external_id'];
        $newStatus = $this->mapXenditStatus($data['status']);

        Log::info('Processing payment status update', [
            'external_id' => $externalId,
            'new_status' => $newStatus,
            'webhook_data' => $data
        ]);

        $payment = Payment::where('external_id', $externalId)->first();

        if (!$payment) {
            Log::warning('Payment not found for webhook', [
                'external_id' => $externalId,
                'status' => $newStatus
            ]);
            throw new \Exception('Payment not found: ' . $externalId);
        }

        // ✅ Use our new method for processing status change
        $statusChanged = $this->processPaymentStatusChange($payment, $newStatus, $data);

        if ($statusChanged) {
            Log::info('Payment status updated successfully via webhook', [
                'external_id' => $externalId,
                'payment_id' => $payment->id,
                'old_status' => $payment->getOriginal('status'),
                'new_status' => $newStatus,
                'student' => $payment->pendaftar->nama_murid,
                'sudah_bayar_formulir' => $payment->pendaftar->sudah_bayar_formulir
            ]);
        }
    }

    /**
     * Webhook endpoint untuk Xendit
     */
    public function webhook(Request $request)
    {
        Log::info('=== WEBHOOK RECEIVED ===', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'environment' => $this->getXenditEnvironment()
        ]);

        try {
            // Always handle as production webhook
            return $this->handleProductionWebhook($request);
        } catch (\Exception $e) {
            Log::error('=== WEBHOOK ERROR ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'error' => 'Webhook processing failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle production webhook (with verification for live environment)
     */
    private function handleProductionWebhook(Request $request)
    {
        Log::info('=== PROCESSING WEBHOOK ===', [
            'environment' => $this->getXenditEnvironment(),
            'data' => $request->all()
        ]);

        // Untuk testing, skip signature verification jika development
        if ($this->getXenditEnvironment() === 'live') {
            if (!$this->verifyWebhookSignature($request)) {
                Log::warning('Unauthorized webhook attempt', [
                    'received_token' => $request->header('x-callback-token'),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            Log::info('Skipping signature verification for test environment');
        }

        $data = $request->all();

        // Validate required fields
        if (!$this->validateWebhookData($data)) {
            Log::warning('Invalid webhook data received', $data);
            return response()->json(['error' => 'Invalid data'], 400);
        }

        try {
            $this->handlePaymentStatusUpdate($data);

            Log::info('=== WEBHOOK PROCESSED SUCCESSFULLY ===', [
                'external_id' => $data['external_id'],
                'status' => $data['status'],
                'environment' => $this->getXenditEnvironment()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully',
                'environment' => $this->getXenditEnvironment()
            ]);
        } catch (\Exception $e) {
            Log::error('=== WEBHOOK PROCESSING FAILED ===', [
                'error' => $e->getMessage(),
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Webhook processing failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify webhook signature for live environment
     */
    private function verifyWebhookSignature(Request $request): bool
    {
        $receivedToken = $request->header('x-callback-token');
        $expectedToken = $this->xenditWebhookToken;

        if (empty($expectedToken)) {
            Log::error('Webhook token not configured');
            return false;
        }

        return hash_equals($expectedToken, $receivedToken ?? '');
    }

    /**
     * Validate webhook data structure
     */
    private function validateWebhookData(array $data): bool
    {
        $requiredFields = ['external_id', 'status'];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return false;
            }
        }

        // Validate status is one of expected values
        $validStatuses = ['PENDING', 'PAID', 'SETTLED', 'EXPIRED', 'FAILED', 'CANCELLED'];
        if (!in_array(strtoupper($data['status']), $validStatuses)) {
            return false;
        }

        return true;
    }

    /**
     * Manual cleanup expired payments (for cron job)
     */
    public function cleanupAllExpiredPayments()
    {
        $expiredCount = Payment::where('status', self::STATUS_PENDING)
            ->where('created_at', '<', now()->subHour())
            ->update([
                'status' => self::STATUS_EXPIRED,
                'expired_at' => now()
            ]);

        Log::info('Bulk cleanup expired payments', ['count' => $expiredCount]);

        return response()->json([
            'success' => true,
            'expired_count' => $expiredCount
        ]);
    }

    /**
     * Admin methods for payment management
     */
    public function adminTransactions(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Build query dengan filter
        $query = Payment::with(['pendaftar.user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by jenjang
        if ($request->filled('jenjang')) {
            $query->whereHas('pendaftar', function($q) use ($request) {
                $q->where('jenjang', $request->jenjang);
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by name or registration number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pendaftar', function($q) use ($search) {
                $q->where('nama_murid', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate statistics
        $stats = [
            'total_transactions' => Payment::count(),
            'paid_transactions' => Payment::where('status', self::STATUS_PAID)->count(),
            'pending_transactions' => Payment::where('status', self::STATUS_PENDING)->count(),
            'failed_transactions' => Payment::whereIn('status', [
                self::STATUS_FAILED,
                self::STATUS_EXPIRED,
                self::STATUS_CANCELLED
            ])->count(),
            'total_revenue' => Payment::where('status', self::STATUS_PAID)->sum('amount')
        ];

        return view('admin.transactions.index', compact('payments', 'stats'));
    }

    public function adminTransactionDetail($id)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $payment = Payment::with(['pendaftar.user'])->findOrFail($id);
        $jenjangName = $this->getJenjangName($payment->pendaftar->jenjang);

        return view('admin.transactions.show', compact('payment', 'jenjangName'));
    }

    /**
     * ✅ UPDATED: Admin confirm payment using our new method
     */
    public function confirmPayment(Request $request, $id)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $payment = Payment::findOrFail($id);

        if ($payment->status === self::STATUS_PAID) {
            return back()->with('info', 'Payment already confirmed');
        }

        // ✅ Use our new method for consistency
        $adminData = [
            'admin_confirmed' => true,
            'confirmed_by' => Auth::id(),
            'confirmed_at' => now()->toISOString(),
            'confirmation_source' => 'admin_manual'
        ];

        $this->handlePaymentSuccess($payment, $adminData);

        Log::info('Payment manually confirmed by admin', [
            'payment_id' => $payment->id,
            'external_id' => $payment->external_id,
            'admin_id' => Auth::id()
        ]);

        return back()->with('success', 'Payment confirmed successfully');
    }

    /**
     * ✅ MANUAL TEST UNTUK MARK PAYMENT AS SUCCESS (untuk testing)
     */
    public function testPaymentSuccess(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $externalId = $request->input('external_id');

        if (!$externalId) {
            return response()->json(['error' => 'external_id required'], 400);
        }

        $payment = Payment::where('external_id', $externalId)->first();

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        if ($payment->status === self::STATUS_PAID) {
            return response()->json(['error' => 'Payment already paid'], 400);
        }

        // Simulate success webhook data
        $testData = [
            'external_id' => $externalId,
            'status' => 'PAID',
            'paid_amount' => $payment->amount,
            'paid_at' => now()->toISOString(),
            'payment_method' => 'BANK_TRANSFER',
            'payment_channel' => 'BCA',
            'test_mode' => true,
            'manual_test' => true,
            'tested_by' => Auth::id()
        ];

        Log::info('=== MANUAL PAYMENT SUCCESS TEST ===', [
            'external_id' => $externalId,
            'payment_id' => $payment->id,
            'admin_id' => Auth::id()
        ]);

        try {
            $this->handlePaymentSuccess($payment, $testData);

            return response()->json([
                'success' => true,
                'message' => 'Payment marked as success',
                'payment_id' => $payment->id,
                'external_id' => $externalId,
                'student_name' => $payment->pendaftar->nama_murid,
                'amount' => $payment->amount,
                'paid_at' => $payment->fresh()->paid_at,
                'sudah_bayar_formulir' => $payment->pendaftar->fresh()->sudah_bayar_formulir
            ]);
        } catch (\Exception $e) {
            Log::error('Manual payment success test failed', [
                'error' => $e->getMessage(),
                'external_id' => $externalId
            ]);

            return response()->json([
                'error' => 'Failed to mark payment as success',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Debug payment configuration
     */
    public function debugPaymentMode()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'APP_ENV' => env('APP_ENV'),
            'API_KEY_PREFIX' => substr($this->xenditApiKey ?? '', 0, 15) . '...',
            'xendit_environment' => $this->getXenditEnvironment(),
            'hasValidApiKey' => $this->isValidApiKey(),
            'webhook_url' => $this->getWebhookUrl(),
            'always_xendit' => true
        ]);
    }

    /**
     * Get available payment methods
     */
    private function getAvailablePaymentMethods(): array
    {
        return [
            'BANK_TRANSFER' => [
                'name' => 'Virtual Account',
                'description' => 'Transfer bank melalui ATM, Internet Banking, atau Mobile Banking',
                'banks' => ['BCA', 'BNI', 'BRI', 'Mandiri', 'Permata', 'CIMB']
            ],
            'EWALLET' => [
                'name' => 'E-Wallet',
                'description' => 'Pembayaran digital melalui aplikasi dompet digital',
                'providers' => ['OVO', 'DANA', 'LinkAja', 'ShopeePay']
            ],
            'QR_CODE' => [
                'name' => 'QRIS',
                'description' => 'Scan QR Code untuk pembayaran instan',
                'providers' => ['QRIS Universal']
            ],
            'CREDIT_CARD' => [
                'name' => 'Kartu Kredit/Debit',
                'description' => 'Pembayaran menggunakan kartu kredit atau debit',
                'cards' => ['Visa', 'Mastercard', 'JCB', 'AMEX']
            ],
            'RETAIL_OUTLET' => [
                'name' => 'Retail Store',
                'description' => 'Bayar di toko retail terdekat',
                'outlets' => ['Alfamart', 'Indomaret']
            ]
        ];
    }

    public function debugPaymentMethods(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $testAmount = $request->get('amount', 350000);

        // Test invoice data
        $testInvoiceData = [
            'external_id' => 'TEST-DEBUG-' . time(),
            'amount' => (int)$testAmount,
            'description' => 'Test Payment Methods Debug',
            'invoice_duration' => 3600,
            'customer' => [
                'given_names' => 'Test Customer',
                'email' => 'test@example.com',
                'mobile_number' => '+6281234567890'
            ],
            'currency' => 'IDR',

            // Test semua konfigurasi
            'should_exclude_credit_card' => false,
            'should_send_email' => false, // Disable email untuk test

            'available_banks' => [
                ['bank_code' => 'BCA', 'collection_type' => 'POOL'],
                ['bank_code' => 'BNI', 'collection_type' => 'POOL'],
                ['bank_code' => 'BRI', 'collection_type' => 'POOL'],
                ['bank_code' => 'MANDIRI', 'collection_type' => 'POOL']
            ],

            'available_ewallets' => [
                ['ewallet_type' => 'OVO'],
                ['ewallet_type' => 'DANA'],
                ['ewallet_type' => 'LINKAJA'],
                ['ewallet_type' => 'SHOPEEPAY']
            ],

            'available_retail_outlets' => [
                ['retail_outlet_name' => 'ALFAMART'],
                ['retail_outlet_name' => 'INDOMARET']
            ]
        ];

        try {
            $response = Http::withBasicAuth($this->xenditApiKey, '')
                ->timeout(30)
                ->post($this->getXenditBaseUrl() . '/v2/invoices', $testInvoiceData);

            if ($response->successful()) {
                $responseData = $response->json();

                return response()->json([
                    'success' => true,
                    'invoice_id' => $responseData['id'],
                    'invoice_url' => $responseData['invoice_url'],
                    'available_payment_methods' => $responseData['available_payment_methods'] ?? [],
                    'available_banks' => $responseData['available_banks'] ?? [],
                    'available_ewallets' => $responseData['available_ewallets'] ?? [],
                    'available_retail_outlets' => $responseData['available_retail_outlets'] ?? [],
                    'total_methods' => count($responseData['available_payment_methods'] ?? []),
                    'raw_response' => $responseData
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'sent_data' => $testInvoiceData
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'sent_data' => $testInvoiceData
            ], 500);
        }
    }
}
