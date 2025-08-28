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
        return !empty($this->xenditApiKey) &&
               (str_starts_with($this->xenditApiKey, 'xnd_') ||
                str_starts_with($this->xenditApiKey, 'sk_'));
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
            'PPDB-%s-%d-%s',
            $pendaftar->no_pendaftaran,
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

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $pendaftars = Pendaftar::with(['latestPayment'])
                ->select('id', 'nama_murid', 'no_pendaftaran', 'unit', 'jenjang', 'payment_amount', 'sudah_bayar_formulir', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('payment.admin.index', compact('pendaftars'));
        }

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

        return view('payment.user.index', compact('pendaftar'));
    }

    public function createInvoice(Request $request)
    {
        $request->validate([
            'pendaftar_id' => 'required|exists:pendaftars,id',
            'amount' => 'required|numeric|min:100000|max:10000000'
        ]);

        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)
            ->where('id', $request->pendaftar_id)
            ->first();

        if (!$pendaftar) {
            return back()->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        if ($pendaftar->sudah_bayar_formulir) {
            return back()->with('info', 'Pembayaran sudah lunas.');
        }

        if ($request->amount != $pendaftar->payment_amount) {
            return back()->with('error', 'Jumlah pembayaran tidak sesuai.');
        }

        // ALWAYS use Xendit - never demo
        return DB::transaction(function() use ($pendaftar) {
            // Cleanup expired payments first
            $this->cleanupExpiredPayments($pendaftar->id);

            // Check for existing active payment (within 1 hour)
            $activePendingPayment = Payment::where('pendaftar_id', $pendaftar->id)
                ->where('status', self::STATUS_PENDING)
                ->where('created_at', '>', now()->subHour())
                ->first();

            if ($activePendingPayment) {
                Log::info('Redirecting to existing active payment', [
                    'payment_id' => $activePendingPayment->id,
                    'external_id' => $activePendingPayment->external_id,
                    'invoice_url' => $activePendingPayment->invoice_url
                ]);

                // Direct redirect to Xendit URL
                return redirect()->away($activePendingPayment->invoice_url);
            }

            // Create new Xendit invoice
            return $this->createXenditInvoice($pendaftar);
        });
    }

    private function createXenditInvoice(Pendaftar $pendaftar)
    {
        try {
            if (!$this->isValidApiKey()) {
                throw new \Exception('Xendit API Key tidak valid atau tidak dikonfigurasi');
            }

            $user = Auth::user();
            $externalId = $this->generateExternalId($pendaftar);
            $jenjangName = $this->getJenjangName($pendaftar->jenjang);

            Log::info('Creating Real Xendit Invoice', [
                'external_id' => $externalId,
                'amount' => $pendaftar->payment_amount,
                'student' => $pendaftar->nama_murid,
                'environment' => $this->getXenditEnvironment(),
                'api_key_prefix' => substr($this->xenditApiKey, 0, 10) . '...'
            ]);

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
                    'mobile_number' => $this->formatPhoneNumber($pendaftar->telp_ayah),
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
                'customer_notification_preference' => [
                    'invoice_created' => ['email'],
                    'invoice_reminder' => ['email'],
                    'invoice_paid' => ['email'],
                    'invoice_expired' => ['email']
                ],
                'success_redirect_url' => route('payment.success'),
                'failure_redirect_url' => route('payment.failed'),
                'currency' => 'IDR',
                'items' => [
                    [
                        'name' => 'Biaya Pendaftaran PPDB ' . $jenjangName,
                        'quantity' => 1,
                        'price' => (int)$pendaftar->payment_amount,
                        'category' => 'Education',
                        'url' => route('user.dashboard')
                    ]
                ],
                'fees' => [
                    [
                        'type' => 'ADMIN',
                        'value' => 0
                    ]
                ],
                // Enable all payment methods
                'payment_methods' => [
                    'BANK_TRANSFER',
                    'CREDIT_CARD',
                    'DEBIT_CARD',
                    'EWALLET',
                    'QR_CODE',
                    'RETAIL_OUTLET',
                    'CARDLESS_CREDIT'
                ],
                'should_exclude_credit_card' => false,
                'should_send_email' => true,
                'should_authenticate_credit_card' => true,
                'locale' => 'id'
            ];

            Log::info('Sending request to Xendit API', [
                'url' => $this->getXenditBaseUrl() . '/v2/invoices',
                'external_id' => $externalId
            ]);

            $response = Http::withBasicAuth($this->xenditApiKey, '')
                ->timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'PPDB-YAPI/1.0'
                ])
                ->post($this->getXenditBaseUrl() . '/v2/invoices', $invoiceData);

            if (!$response->successful()) {
                $errorResponse = $response->json();
                Log::error('Xendit Invoice Creation Failed', [
                    'status' => $response->status(),
                    'response' => $errorResponse,
                    'request_data' => $invoiceData
                ]);

                throw new \Exception(
                    $errorResponse['message'] ?? 'Gagal membuat invoice pembayaran: ' . $response->status()
                );
            }

            $responseData = $response->json();

            if (!isset($responseData['invoice_url'])) {
                Log::error('Xendit response missing invoice_url', [
                    'response' => $responseData
                ]);
                throw new \Exception('Response Xendit tidak lengkap - missing invoice_url');
            }

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

            Log::info('Xendit payment record created successfully', [
                'payment_id' => $payment->id,
                'external_id' => $externalId,
                'invoice_url' => $responseData['invoice_url'],
                'environment' => $this->getXenditEnvironment()
            ]);

            // DIRECT REDIRECT to Xendit invoice URL
            return redirect()->away($responseData['invoice_url']);

        } catch (\Exception $e) {
            Log::error('Payment Invoice Creation Error', [
                'message' => $e->getMessage(),
                'pendaftar_id' => $pendaftar->id,
                'environment' => $this->getXenditEnvironment(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat membuat invoice: ' . $e->getMessage());
        }
    }

    public function success()
    {
        $user = Auth::user();

        // Get latest payment for this user
        $payment = Payment::whereHas('pendaftar', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('status', self::STATUS_PAID)
        ->with(['pendaftar'])
        ->orderBy('paid_at', 'desc')
        ->first();

        if (!$payment) {
            return redirect()->route('payment.index')
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // Get pendaftar data
        $pendaftar = $payment->pendaftar;
        $jenjangName = $this->getJenjangName($pendaftar->jenjang);

        return view('payment.success', compact('payment', 'pendaftar', 'jenjangName'));
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

        return view('payment.failed', compact('payment', 'pendaftar', 'jenjangName'));
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

        return view('transactions.user.index', compact('payments'));
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

        return view('transactions.user.show', compact('payment', 'jenjangName'));
    }

    /**
     * Handle payment status update from webhook
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

        $oldStatus = $payment->status;

        // Don't update if already in final state
        if (in_array($oldStatus, [self::STATUS_PAID, self::STATUS_FAILED, self::STATUS_CANCELLED])) {
            Log::info('Payment already in final state, skipping update', [
                'external_id' => $externalId,
                'current_status' => $oldStatus,
                'attempted_status' => $newStatus
            ]);
            return;
        }

        DB::transaction(function() use ($payment, $data, $newStatus, $oldStatus, $externalId) {
            // Update payment record
            $updateData = [
                'status' => $newStatus,
                'xendit_response' => array_merge($payment->xendit_response ?? [], $data)
            ];

            // Set timestamp based on status
            switch ($newStatus) {
                case self::STATUS_PAID:
                    $updateData['paid_at'] = now();
                    break;
                case self::STATUS_EXPIRED:
                    $updateData['expired_at'] = now();
                    break;
                case self::STATUS_FAILED:
                case self::STATUS_CANCELLED:
                    $updateData['failed_at'] = now();
                    break;
            }

            $payment->update($updateData);

            // Update pendaftar status if payment is successful
            if ($newStatus === self::STATUS_PAID) {
                $payment->pendaftar->update(['sudah_bayar_formulir' => true]);

                Log::info('Payment marked as paid', [
                    'external_id' => $externalId,
                    'payment_id' => $payment->id,
                    'student' => $payment->pendaftar->nama_murid,
                    'amount' => $payment->amount
                ]);
            }

            Log::info('Payment status updated successfully', [
                'external_id' => $externalId,
                'payment_id' => $payment->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'student' => $payment->pendaftar->nama_murid
            ]);
        });
    }

    /**
     * Webhook endpoint untuk Xendit
     */
    public function webhook(Request $request)
    {
        Log::info('Webhook received', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'environment' => $this->getXenditEnvironment()
        ]);

        // Always handle as production webhook (no demo handling)
        return $this->handleProductionWebhook($request);
    }

    /**
     * Handle production webhook (with verification for live environment)
     */
    private function handleProductionWebhook(Request $request)
    {
        Log::info('Processing webhook', [
            'environment' => $this->getXenditEnvironment()
        ]);

        // Verify webhook signature only for live environment
        if ($this->getXenditEnvironment() === 'live' && !$this->verifyWebhookSignature($request)) {
            Log::warning('Unauthorized webhook attempt', [
                'received_token' => $request->header('x-callback-token'),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->all();

        // Validate required fields
        if (!$this->validateWebhookData($data)) {
            Log::warning('Invalid webhook data received', $data);
            return response()->json(['error' => 'Invalid data'], 400);
        }

        try {
            $this->handlePaymentStatusUpdate($data);

            Log::info('Webhook processed successfully', [
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
            Log::error('Webhook processing failed', [
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
    public function adminTransactions()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $payments = Payment::with(['pendaftar.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.transactions.index', compact('payments'));
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

    public function confirmPayment(Request $request, $id)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $payment = Payment::findOrFail($id);

        if ($payment->status === self::STATUS_PAID) {
            return back()->with('info', 'Payment already confirmed');
        }

        DB::transaction(function() use ($payment) {
            $payment->update([
                'status' => self::STATUS_PAID,
                'paid_at' => now(),
                'xendit_response' => array_merge($payment->xendit_response ?? [], [
                    'admin_confirmed' => true,
                    'confirmed_by' => Auth::id(),
                    'confirmed_at' => now()->toISOString()
                ])
            ]);

            $payment->pendaftar->update(['sudah_bayar_formulir' => true]);
        });

        Log::info('Payment manually confirmed by admin', [
            'payment_id' => $payment->id,
            'external_id' => $payment->external_id,
            'admin_id' => Auth::id()
        ]);

        return back()->with('success', 'Payment confirmed successfully');
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
            'XENDIT_MODE' => env('XENDIT_MODE'),
            'PAYMENT_MODE' => env('PAYMENT_MODE'),
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
}
