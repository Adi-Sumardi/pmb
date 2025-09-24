<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Pendaftar;
use App\Models\StudentBill;
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
     * Calculate Xendit transaction fees to be charged to user
     */
    private function calculateXenditFees(float $amount): array
    {
        $fees = [];

        // Bank Transfer / Virtual Account fees
        $bankTransferFee = 4000; // Fixed fee per transaction

        // Credit Card fees (2.9% + Rp 2.000)
        $creditCardFee = ($amount * 0.029) + 2000;

        // E-Wallet fees (varies by provider)
        $eWalletFee = $amount * 0.02; // 2% for most e-wallets

        // Convenience Store fees
        $convenienceStoreFee = 5000; // Fixed fee

        // QRIS fees
        $qrisFee = $amount * 0.007; // 0.7%

        return [
            'bank_transfer' => $bankTransferFee,
            'credit_card' => $creditCardFee,
            'ewallet' => $eWalletFee,
            'convenience_store' => $convenienceStoreFee,
            'qris' => $qrisFee,
            'default_fee' => $bankTransferFee // Default to cheapest option
        ];
    }

    /**
     * Get the best fee option for user display
     */
    private function getBestFeeOption(float $amount): array
    {
        $fees = $this->calculateXenditFees($amount);

        // Find the lowest fee option
        $minFee = min($fees['bank_transfer'], $fees['qris']);
        $recommendedMethod = $fees['bank_transfer'] <= $fees['qris'] ? 'Bank Transfer' : 'QRIS';

        return [
            'min_fee' => $minFee,
            'recommended_method' => $recommendedMethod,
            'all_fees' => $fees
        ];
    }

    /**
     * Validate and apply discount/voucher
     */
    public function validateDiscount(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0'
        ]);

        $discount = \App\Models\Discount::active()
            ->valid()
            ->where('code', $request->code)
            ->first();

        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak valid atau sudah kedaluwarsa'
            ]);
        }

        if (!$discount->isApplicable($request->amount)) {
            $minAmount = (float) $discount->minimum_amount;
            return response()->json([
                'success' => false,
                'message' => "Minimum pembelian untuk voucher ini adalah Rp " . number_format($minAmount, 0, ',', '.')
            ]);
        }

        $discountAmount = $discount->calculateDiscount($request->amount);

        return response()->json([
            'success' => true,
            'discount' => [
                'id' => $discount->id,
                'name' => $discount->name,
                'code' => $discount->code,
                'type' => $discount->type,
                'value' => $discount->value,
                'discount_amount' => $discountAmount,
                'description' => $discount->description
            ],
            'message' => 'Voucher berhasil diterapkan!'
        ]);
    }

    /**
     * Internal discount validation that returns array (not JsonResponse)
     */
    private function validateDiscountInternal(string $code, float $amount): array
    {
        $discount = \App\Models\Discount::active()
            ->valid()
            ->where('code', $code)
            ->first();

        if (!$discount) {
            return [
                'success' => false,
                'message' => 'Kode voucher tidak valid atau sudah kedaluwarsa'
            ];
        }

        if (!$discount->isApplicable($amount)) {
            $minAmount = (float) $discount->minimum_amount;
            return [
                'success' => false,
                'message' => "Minimum pembelian untuk voucher ini adalah Rp " . number_format($minAmount, 0, ',', '.')
            ];
        }

        $discountAmount = $discount->calculateDiscount($amount);

        return [
            'success' => true,
            'discount' => [
                'id' => $discount->id,
                'name' => $discount->name,
                'code' => $discount->code,
                'type' => $discount->type,
                'value' => $discount->value,
                'discount_amount' => $discountAmount,
                'description' => $discount->description
            ],
            'message' => 'Voucher berhasil diterapkan!'
        ];
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
     * Get formulir amount based on school unit (updated amounts per request)
     */
    private function getFormulirAmountByUnit(string $unit): int
    {
        // Exact unit name mappings based on user requirements
        $formulirAmounts = [
            // RA Sakinah = Rp.100.000
            'RA Sakinah' => 100000,
            'RA Sakinah - Kebayoran' => 100000,

            // PG Sakinah = Rp 400.000
            'PG Sakinah' => 400000,
            'PG Sakinah - Rawamangun' => 400000,
            'Playgroup Sakinah' => 400000,
            'Playgroup Sakinah - Rawamangun' => 400000,

            // TKIA 13 = Rp 450.000
            'TKIA 13' => 450000,
            'TK Islam Al Azhar 13' => 450000,
            'TK Islam Al Azhar 13 - Rawamangun' => 450000,

            // SDIA 13 = Rp 550.000
            'SDIA 13' => 550000,
            'SD Islam Al Azhar 13' => 550000,
            'SD Islam Al Azhar 13 - Rawamangun' => 550000,

            // SMPIA 12 = Rp 550.000
            'SMPIA 12' => 550000,
            'SMP Islam Al Azhar 12' => 550000,
            'SMP Islam Al Azhar 12 - Rawamangun' => 550000,

            // SMPIA 55 = Rp 550.000
            'SMPIA 55' => 550000,
            'SMP Islam Al Azhar 55' => 550000,
            'SMP Islam Al Azhar 55 - Jatimakmur' => 550000,

            // SMAIA 33 = Rp 550.000
            'SMAIA 33' => 550000,
            'SMA Islam Al Azhar 33' => 550000,
            'SMA Islam Al Azhar 33 - Jatimakmur' => 550000,
        ];

        // Check exact match first
        if (isset($formulirAmounts[$unit])) {
            return $formulirAmounts[$unit];
        }

        // Fallback: check partial matches for flexibility
        $unitLower = strtolower($unit);

        if (strpos($unitLower, 'ra') !== false && strpos($unitLower, 'sakinah') !== false) {
            return 100000; // RA Sakinah
        }

        if (strpos($unitLower, 'playgroup') !== false && strpos($unitLower, 'sakinah') !== false) {
            return 400000; // PG Sakinah
        }

        if (strpos($unitLower, 'tk') !== false && strpos($unitLower, 'azhar') !== false && strpos($unitLower, '13') !== false) {
            return 450000; // TKIA 13
        }

        if (strpos($unitLower, 'sd') !== false && strpos($unitLower, 'azhar') !== false && strpos($unitLower, '13') !== false) {
            return 550000; // SDIA 13
        }

        if (strpos($unitLower, 'smp') !== false && strpos($unitLower, 'azhar') !== false && strpos($unitLower, '12') !== false) {
            return 550000; // SMPIA 12
        }

        if (strpos($unitLower, 'smp') !== false && strpos($unitLower, 'azhar') !== false && strpos($unitLower, '55') !== false) {
            return 550000; // SMPIA 55
        }

        if (strpos($unitLower, 'sma') !== false && strpos($unitLower, 'azhar') !== false && strpos($unitLower, '33') !== false) {
            return 550000; // SMAIA 33
        }

        // Fallback untuk jenjang umum jika unit spesifik tidak ditemukan
        if (strpos($unitLower, 'ra') !== false || strpos($unitLower, 'raudhatul') !== false) {
            return 100000; // Default RA
        }

        if (strpos($unitLower, 'pg') !== false || strpos($unitLower, 'playgroup') !== false) {
            return 400000; // Default PG
        }

        if (strpos($unitLower, 'tk') !== false || strpos($unitLower, 'taman kanak') !== false) {
            return 450000; // Default TK (TKIA 13 rate)
        }

        if (strpos($unitLower, 'sd') !== false || strpos($unitLower, 'sekolah dasar') !== false) {
            return 550000; // Default SD (SDIA 13 rate)
        }

        if (strpos($unitLower, 'smp') !== false || strpos($unitLower, 'sekolah menengah pertama') !== false) {
            return 550000; // Default SMP (SMPIA rate)
        }

        if (strpos($unitLower, 'sma') !== false || strpos($unitLower, 'sekolah menengah atas') !== false) {
            return 550000; // Default SMA (SMAIA rate)
        }

        // Edge case terakhir: analisis dari nama unit untuk deteksi jenjang
        if (preg_match('/\b(sanggar|kelompok)\b/i', $unit)) {
            return 100000; // Kemungkinan RA level
        }

        if (preg_match('/\b(tka|tkb)\b/i', $unit)) {
            return 450000; // TK level
        }

        // Log untuk debugging dan berikan harga berdasarkan asumsi jenjang tertinggi
        Log::warning('Unit formulir tidak dapat diidentifikasi - menggunakan harga SD default', [
            'unit' => $unit,
            'unit_lower' => $unitLower,
            'default_amount' => 550000
        ]);

        return 550000; // Default untuk edge case (SD/SMP/SMA rate - yang paling umum)
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
    private function generateExternalId(Pendaftar $pendaftar, array $cartItems = []): string
    {
        $prefix = $this->generatePaymentPrefix($cartItems);

        // Generate short student name (first 3 chars of first name)
        $studentName = $this->getShortStudentName($pendaftar->nama_murid);

        // Generate short unit code
        $unitCode = $this->getShortUnitCode($pendaftar->unit);

        return sprintf(
            '%s-%s%s-%02d%02d%04d-%d-%s',
            $prefix,
            $studentName,
            $unitCode,
            now()->month,
            now()->day,
            $pendaftar->id,
            time(),
            Str::random(3)
        );
    }

    /**
     * Generate student name for external ID (full name, cleaned)
     */
    private function getShortStudentName(string $fullName): string
    {
        // Remove special characters and spaces, keep only alphanumeric
        $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', $fullName);

        // Convert to uppercase and limit length to avoid too long external IDs
        $cleanName = strtoupper($cleanName);

        // Limit to 15 characters to keep external ID manageable
        return substr($cleanName, 0, 15) ?: 'STUDENT';
    }

    /**
     * Generate short unit code for external ID
     */
    private function getShortUnitCode(string $unit): string
    {
        // Mapping common units to short codes
        $unitMappings = [
            'TK Islam Al Azhar 13 - Rawamangun' => 'TK13',
            'SD Islam Al Azhar 13 - Rawamangun' => 'SD13',
            'SMP Islam Al Azhar 13 - Rawamangun' => 'SMP13',
            'SMA Islam Al Azhar 13 - Rawamangun' => 'SMA13',
            'TK Islam Al Azhar' => 'TK',
            'SD Islam Al Azhar' => 'SD',
            'SMP Islam Al Azhar' => 'SMP',
            'SMA Islam Al Azhar' => 'SMA'
        ];

        // Check if exact mapping exists
        if (isset($unitMappings[$unit])) {
            return $unitMappings[$unit];
        }

        // Generate code from unit name
        $cleanUnit = preg_replace('/[^a-zA-Z0-9\s]/', '', $unit);
        $words = explode(' ', trim($cleanUnit));

        // Try to extract meaningful abbreviation
        if (count($words) >= 2) {
            $code = '';
            foreach ($words as $word) {
                if (in_array(strtolower($word), ['tk', 'sd', 'smp', 'sma', 'ma', 'mi'])) {
                    $code .= strtoupper($word);
                } elseif (preg_match('/\d+/', $word)) {
                    $code .= $word;
                } elseif (strlen($word) > 2 && empty($code)) {
                    $code .= strtoupper(substr($word, 0, 2));
                }

                if (strlen($code) >= 4) break;
            }

            return $code ?: 'UNIT';
        }

        // Fallback: take first 4 characters
        return strtoupper(substr($cleanUnit, 0, 4)) ?: 'UNIT';
    }    /**
     * Generate payment prefix based on cart items
     */
    private function generatePaymentPrefix(array $cartItems): string
    {
        if (empty($cartItems)) {
            return 'PPDB-PMB';
        }

        // Count bill types in cart
        $billTypes = [];
        foreach ($cartItems as $item) {
            if (isset($item['bill_id'])) {
                $bill = StudentBill::find($item['bill_id']);
                if ($bill) {
                    $billTypes[] = $bill->bill_type;
                }
            } else {
                // Fallback: detect from item name
                $itemName = strtolower($item['name'] ?? '');
                if (strpos($itemName, 'spp') !== false) {
                    $billTypes[] = 'spp';
                } elseif (strpos($itemName, 'formulir') !== false || strpos($itemName, 'pendaftaran') !== false) {
                    $billTypes[] = 'registration_fee';
                } elseif (strpos($itemName, 'uang pangkal') !== false) {
                    $billTypes[] = 'uang_pangkal';
                } elseif (strpos($itemName, 'seragam') !== false) {
                    $billTypes[] = 'uniform';
                } elseif (strpos($itemName, 'buku') !== false) {
                    $billTypes[] = 'books';
                } else {
                    $billTypes[] = 'other';
                }
            }
        }

        $uniqueTypes = array_unique($billTypes);

        // Single payment type
        if (count($uniqueTypes) === 1) {
            switch ($uniqueTypes[0]) {
                case 'registration_fee':
                    return 'FORMULIR';
                case 'spp':
                    return 'SPP';
                case 'uang_pangkal':
                    return 'UP';
                case 'uniform':
                    return 'SERAGAM';
                case 'books':
                    return 'BUKU';
                case 'supplies':
                    return 'ALAT';
                case 'activity':
                    return 'KEGIATAN';
                default:
                    return 'LAINNYA';
            }
        }

        // Multiple payment types - create combined prefix
        if (count($uniqueTypes) > 1) {
            $prefixes = [];
            foreach ($uniqueTypes as $type) {
                switch ($type) {
                    case 'registration_fee':
                        $prefixes[] = 'F';
                        break;
                    case 'spp':
                        $prefixes[] = 'S';
                        break;
                    case 'uang_pangkal':
                        $prefixes[] = 'U';
                        break;
                    case 'uniform':
                        $prefixes[] = 'R';
                        break;
                    case 'books':
                        $prefixes[] = 'B';
                        break;
                    case 'supplies':
                        $prefixes[] = 'A';
                        break;
                    case 'activity':
                        $prefixes[] = 'K';
                        break;
                    default:
                        $prefixes[] = 'L';
                }
            }
            return 'MIX-' . implode('', $prefixes);
        }

        return 'PPDB-PMB';
    }

    /**
     * Generate payment type description for display
     */
    private function generatePaymentTypeDescription(array $cartItems): string
    {
        if (empty($cartItems)) {
            return 'Pembayaran PPDB';
        }

        // Count bill types in cart
        $billTypes = [];
        foreach ($cartItems as $item) {
            if (isset($item['bill_id']) && $item['bill_id']) {
                $bill = StudentBill::find($item['bill_id']);
                if ($bill) {
                    $billTypes[] = $bill->bill_type;
                }
            } else {
                // Fallback: detect from item name
                $itemName = strtolower($item['name'] ?? '');
                if (strpos($itemName, 'spp') !== false) {
                    $billTypes[] = 'spp';
                } elseif (strpos($itemName, 'formulir') !== false || strpos($itemName, 'pendaftaran') !== false) {
                    $billTypes[] = 'registration_fee';
                } elseif (strpos($itemName, 'uang pangkal') !== false) {
                    $billTypes[] = 'uang_pangkal';
                } elseif (strpos($itemName, 'seragam') !== false) {
                    $billTypes[] = 'uniform';
                } elseif (strpos($itemName, 'buku') !== false) {
                    $billTypes[] = 'books';
                } else {
                    $billTypes[] = 'other';
                }
            }
        }

        $uniqueTypes = array_unique($billTypes);

        // Single payment type
        if (count($uniqueTypes) === 1) {
            switch ($uniqueTypes[0]) {
                case 'registration_fee':
                    return 'Pembayaran Formulir PPDB';
                case 'spp':
                    return 'Pembayaran SPP';
                case 'uang_pangkal':
                    return 'Pembayaran Uang Pangkal';
                case 'uniform':
                    return 'Pembayaran Seragam';
                case 'books':
                    return 'Pembayaran Buku';
                case 'supplies':
                    return 'Pembayaran Alat Tulis';
                case 'activity':
                    return 'Pembayaran Kegiatan';
                default:
                    return 'Pembayaran PPDB';
            }
        }

        // Multiple payment types
        if (count($uniqueTypes) > 1) {
            $typeNames = [];
            foreach ($uniqueTypes as $type) {
                switch ($type) {
                    case 'registration_fee':
                        $typeNames[] = 'Formulir';
                        break;
                    case 'spp':
                        $typeNames[] = 'SPP';
                        break;
                    case 'uang_pangkal':
                        $typeNames[] = 'Uang Pangkal';
                        break;
                    case 'uniform':
                        $typeNames[] = 'Seragam';
                        break;
                    case 'books':
                        $typeNames[] = 'Buku';
                        break;
                    case 'supplies':
                        $typeNames[] = 'Alat Tulis';
                        break;
                    case 'activity':
                        $typeNames[] = 'Kegiatan';
                        break;
                    default:
                        $typeNames[] = 'Lainnya';
                }
            }

            if (count($typeNames) === 2) {
                return sprintf('Pembayaran %s & %s', $typeNames[0], $typeNames[1]);
            } else {
                return sprintf('Pembayaran %s & %d lainnya', $typeNames[0], count($typeNames) - 1);
            }
        }

        return 'Pembayaran PPDB';
    }

    /**
     * Get cart items from payment metadata
     */
    private function getCartItemsFromMetadata(Payment $payment): array
    {
        // Try to get cart items from payment metadata
        if (!empty($payment->metadata['cart_items'])) {
            return $payment->metadata['cart_items'];
        }

        // Fallback: get bills from related student bills if no cart data
        $bills = StudentBill::where('pendaftar_id', $payment->pendaftar_id)
            ->where('total_amount', '>', 0)
            ->orderBy('bill_type')
            ->get();

        $cartItems = [];
        foreach ($bills as $bill) {
            $cartItems[] = [
                'bill_id' => $bill->id,
                'name' => $this->getBillTypeInfo($bill->bill_type)['label'],
                'description' => $bill->description ?? $this->getBillTypeInfo($bill->bill_type)['description'],
                'amount' => (int) $bill->total_amount,
                'quantity' => 1,
                'bill_type' => $bill->bill_type
            ];
        }

        // If no bills found, create a fallback item based on payment amount
        if (empty($cartItems)) {
            $cartItems[] = [
                'bill_id' => null,
                'name' => 'Pembayaran PPDB',
                'description' => 'Pembayaran administrasi pendaftaran',
                'amount' => $payment->amount,
                'quantity' => 1,
                'bill_type' => 'registration_fee'
            ];
        }

        return $cartItems;
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
     * ✅ HANDLE PAYMENT SUCCESS - UPDATE STATUS & STUDENT BILLS
     */
    private function handlePaymentSuccess(Payment $payment, array $webhookData = []): void
    {
        Log::info('=== HANDLING PAYMENT SUCCESS ===', [
            'payment_id' => $payment->id,
            'external_id' => $payment->external_id,
            'current_status' => $payment->status,
            'pendaftar_id' => $payment->pendaftar_id,
            'has_metadata' => isset($payment->metadata)
        ]);

        DB::transaction(function() use ($payment, $webhookData) {
            // Update payment status to PAID
            $updateData = [
                'status' => self::STATUS_PAID,
                'paid_at' => now(),
                'xendit_response' => array_merge($payment->xendit_response ?? [], $webhookData)
            ];

            $payment->update($updateData);

            // Handle cart-based payments with StudentBill updates
            if (isset($payment->metadata['cart_items']) && !empty($payment->metadata['cart_items'])) {
                $this->updateStudentBillsFromCart($payment);
            } else {
                // Fallback for legacy single payment
                $this->updateLegacyPayment($payment);
            }

            Log::info('✅ Payment success processed successfully', [
                'payment_id' => $payment->id,
                'external_id' => $payment->external_id,
                'student_name' => $payment->pendaftar->nama_murid,
                'amount' => $payment->amount,
                'paid_at' => $payment->paid_at,
                'has_cart_items' => isset($payment->metadata['cart_items']),
                'cart_items_count' => count($payment->metadata['cart_items'] ?? [])
            ]);
        });
    }

    /**
     * Update StudentBill records based on cart payment
     */
    private function updateStudentBillsFromCart(Payment $payment): void
    {
        Log::info('Updating StudentBills from cart payment', [
            'payment_id' => $payment->id,
            'cart_items' => $payment->metadata['cart_items'] ?? []
        ]);

        $cartItems = $payment->metadata['cart_items'] ?? [];
        $updatedBills = [];

        foreach ($cartItems as $item) {
            // Use 'id' from cart items (which represents student_bill_id)
            $billId = $item['bill_id'] ?? $item['id'] ?? null;

            if ($billId) {
                $studentBill = \App\Models\StudentBill::find($billId);

                if ($studentBill && $studentBill->payment_status === 'pending') {
                    Log::info('Updating StudentBill from cart item', [
                        'student_bill_id' => $studentBill->id,
                        'bill_name' => $studentBill->description,
                        'bill_type' => $studentBill->bill_type,
                        'paid_amount' => $studentBill->remaining_amount
                    ]);

                    // Update the student bill status
                    $studentBill->update([
                        'payment_status' => 'paid',
                        'paid_amount' => $studentBill->remaining_amount,
                        'remaining_amount' => 0,
                        'paid_at' => now()
                    ]);

                    // Create a bill payment record to track the relationship
                    \App\Models\BillPayment::create([
                        'student_bill_id' => $studentBill->id,
                        'pendaftar_id' => $payment->pendaftar_id,
                        'payment_number' => 'PAY-' . date('Y') . '-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                        'external_transaction_id' => $payment->external_id,
                        'invoice_id' => $payment->invoice_id,
                        'amount' => $studentBill->paid_amount,
                        'payment_method' => 'virtual_account', // Default for Xendit payments
                        'payment_channel' => $payment->xendit_response['bank_code'] ?? 'XENDIT',
                        'status' => 'completed',
                        'payment_date' => $payment->paid_at,
                        'confirmed_at' => now(),
                        'verified_by' => null, // Auto-verified for successful payments
                        'verified_at' => now()
                    ]);

                    $updatedBills[] = [
                        'bill_id' => $studentBill->id,
                        'name' => $studentBill->description,
                        'amount' => $studentBill->paid_amount
                    ];
                } else {
                    Log::warning('StudentBill not found or already paid', [
                        'bill_id' => $billId,
                        'student_bill_exists' => $studentBill ? 'yes' : 'no',
                        'payment_status' => $studentBill->payment_status ?? 'N/A'
                    ]);
                }
            } else {
                Log::warning('No bill_id found in cart item', [
                    'cart_item' => $item
                ]);
            }
        }

        // Update pendaftar status if all bills are paid
        $this->updatePendaftarStatusFromBills($payment->pendaftar);

        Log::info('StudentBills updated from cart payment', [
            'payment_id' => $payment->id,
            'updated_bills' => $updatedBills,
            'total_bills_updated' => count($updatedBills)
        ]);
    }

    /**
     * Update legacy single payment (backward compatibility)
     */
    private function updateLegacyPayment(Payment $payment): void
    {
        Log::info('Processing legacy payment', ['payment_id' => $payment->id]);

        // Update pendaftar for legacy payments
        $payment->pendaftar->update([
            'sudah_bayar_formulir' => true,
            'overall_status' => 'Sudah Bayar',
            'current_status' => 'Sudah Bayar'
        ]);
    }

    /**
     * Update pendaftar status based on StudentBill status
     */
    private function updatePendaftarStatusFromBills(\App\Models\Pendaftar $pendaftar): void
    {
        $unpaidBills = \App\Models\StudentBill::where('pendaftar_id', $pendaftar->id)
            ->where('payment_status', 'pending')
            ->where('remaining_amount', '>', 0)
            ->count();

        $paidBills = \App\Models\StudentBill::where('pendaftar_id', $pendaftar->id)
            ->where('payment_status', 'paid')
            ->count();

        // Check if registration fee (formulir) is paid
        $registrationFeePaid = \App\Models\StudentBill::where('pendaftar_id', $pendaftar->id)
            ->where('bill_type', 'registration_fee')
            ->where('payment_status', 'paid')
            ->exists();

        if ($unpaidBills === 0 && $paidBills > 0) {
            // All bills are paid - using valid enum value
            $pendaftar->update([
                'sudah_bayar_formulir' => true,
                'overall_status' => 'Sudah Bayar',
                'current_status' => 'Fully Paid',
                'data_completion_status' => $registrationFeePaid ? 'incomplete' : $pendaftar->data_completion_status
            ]);

            Log::info('Pendaftar marked as fully paid', [
                'pendaftar_id' => $pendaftar->id,
                'paid_bills' => $paidBills
            ]);
        } elseif ($paidBills > 0) {
            // Partial payment - using valid enum value
            $pendaftar->update([
                'overall_status' => 'Sudah Bayar',
                'current_status' => 'Partial Payment',
                'data_completion_status' => $registrationFeePaid ? 'incomplete' : $pendaftar->data_completion_status
            ]);

            Log::info('Pendaftar marked as partially paid', [
                'pendaftar_id' => $pendaftar->id,
                'paid_bills' => $paidBills,
                'unpaid_bills' => $unpaidBills
            ]);
        }

        // Specifically handle registration fee payment - activate data entry stage
        if ($registrationFeePaid && $pendaftar->data_completion_status !== 'complete') {
            $pendaftar->update([
                'sudah_bayar_formulir' => true,
                'data_completion_status' => 'incomplete'
            ]);

            Log::info('Registration fee paid - activating data entry stage', [
                'pendaftar_id' => $pendaftar->id,
                'data_completion_status' => 'incomplete'
            ]);
        }
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

        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        if (!$pendaftar) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        // Get active bills (unpaid) using new StudentBill system with intelligent filtering
        $query = \App\Models\StudentBill::where('pendaftar_id', $pendaftar->id)
            ->where('payment_status', 'pending')
            ->where('remaining_amount', '>', 0);

        // Apply intelligent bill filtering based on student status
        $studentStatus = $pendaftar->student_status ?? 'inactive';
        $isActiveStudent = $studentStatus === 'active';

        // If student is not active, only show registration_fee and uang_pangkal bills
        if (!$isActiveStudent) {
            $query->whereIn('bill_type', ['registration_fee', 'uang_pangkal']);
        }
        // If student is active, show all bill types including SPP, seragam, buku

        $activeBills = $query->orderBy('due_date', 'asc')->get();

        // Get paid bills for history (apply same filtering logic)
        $paidQuery = \App\Models\StudentBill::where('pendaftar_id', $pendaftar->id)
            ->where('payment_status', 'paid');

        if (!$isActiveStudent) {
            $paidQuery->whereIn('bill_type', ['registration_fee', 'uang_pangkal']);
        }

        $paidBills = $paidQuery->orderBy('paid_at', 'desc')->get();

        // Calculate summary data
        $totalUnpaidAmount = $activeBills->sum('remaining_amount');
        $totalPaidAmount = $paidBills->sum('total_amount');
        $totalBillsCount = $activeBills->count();

        // Calculate Xendit fees for potential payment
        $paymentFees = $totalUnpaidAmount > 0 ? $this->getBestFeeOption($totalUnpaidAmount) : null;

        // Get available discounts/vouchers from admin system
        $availableDiscounts = \App\Models\Discount::active()
            ->valid()
            ->where(function($query) use ($totalUnpaidAmount) {
                $query->whereNull('minimum_amount')
                      ->orWhere('minimum_amount', '<=', $totalUnpaidAmount);
            })
            ->orderBy('value', 'desc')
            ->get();

        // For backward compatibility, still get old payments if any
        $oldPayments = $pendaftar->payments()->orderBy('created_at', 'desc')->get();

        return view('user.payment.index', compact(
            'pendaftar',
            'activeBills',
            'paidBills',
            'totalUnpaidAmount',
            'totalPaidAmount',
            'totalBillsCount',
            'paymentFees',
            'availableDiscounts',
            'oldPayments',
            'studentStatus',
            'isActiveStudent'
        ));
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
        Log::info('=== PAYMENT CREATE INVOICE CALLED ===', [
            'request_data' => $request->all(),
            'route_name' => request()->route()->getName(),
            'url' => request()->url(),
            'method' => request()->method(),
            'user_id' => Auth::id()
        ]);

        // Debug cart items raw data
        Log::info('=== DEBUG CART ITEMS RAW ===', [
            'items_raw' => $request->items,
            'items_type' => gettype($request->items),
            'items_length' => strlen($request->items ?? ''),
            'first_50_chars' => substr($request->items ?? '', 0, 50)
        ]);

        $request->validate([
            'pendaftar_id' => 'required|exists:pendaftars,id',
            'amount' => 'required|numeric|min:10000|max:50000000', // Increased range for cart payments
            'items' => 'nullable|string',
            'promo_code' => 'nullable|string|max:50',
            'discount_id' => 'nullable|string|max:50'
        ]);

        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)
            ->where('id', $request->pendaftar_id)
            ->first();

        if (!$pendaftar) {
            Log::error('Pendaftar not found');
            return back()->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        Log::info('Processing cart payment with discount support', [
            'pendaftar_id' => $pendaftar->id,
            'cart_amount' => $request->amount,
            'items' => $request->items,
            'promo_code' => $request->promo_code,
            'discount_id' => $request->discount_id
        ]);

        // SECURITY FIX: Enhanced cart items validation
        $cartItems = [];
        if ($request->items) {
            try {
                // SECURITY FIX: Validate input length first
                if (strlen($request->items) > 10000) {
                    throw new \Exception('Cart data too large');
                }

                // SECURITY FIX: Sanitize input before processing
                $sanitizedItems = \App\Services\SecurityValidationService::sanitizeInput($request->items);

                // Decode HTML entities first, then parse JSON
                $decodedItems = html_entity_decode($sanitizedItems);

                // SECURITY FIX: Additional JSON validation
                if (empty($decodedItems) || !is_string($decodedItems)) {
                    throw new \Exception('Invalid cart data format');
                }

                $cartItems = json_decode($decodedItems, true, 10, JSON_THROW_ON_ERROR);

                // SECURITY FIX: Comprehensive structure validation
                if (!is_array($cartItems)) {
                    throw new \Exception('Cart items must be an array');
                }

                if (count($cartItems) > 50) {
                    throw new \Exception('Too many cart items');
                }

                // SECURITY FIX: Validate each cart item structure
                foreach ($cartItems as $index => $item) {
                    if (!is_array($item)) {
                        throw new \Exception("Cart item at index {$index} must be an array");
                    }

                    // Required fields validation
                    $requiredFields = ['name', 'amount'];
                    foreach ($requiredFields as $field) {
                        if (!isset($item[$field])) {
                            throw new \Exception("Cart item at index {$index} missing required field: {$field}");
                        }
                    }

                    // Validate amount
                    if (!is_numeric($item['amount']) || $item['amount'] < 0 || $item['amount'] > 100000000) {
                        throw new \Exception("Invalid amount in cart item at index {$index}");
                    }

                    // Validate quantity if present
                    if (isset($item['quantity']) && (!is_numeric($item['quantity']) || $item['quantity'] < 1 || $item['quantity'] > 100)) {
                        throw new \Exception("Invalid quantity in cart item at index {$index}");
                    }

                    // Validate bill_id if present
                    if (isset($item['bill_id']) && !empty($item['bill_id']) && (!is_numeric($item['bill_id']) || $item['bill_id'] < 1)) {
                        throw new \Exception("Invalid bill_id in cart item at index {$index}");
                    }

                    // Sanitize string fields
                    if (isset($item['name'])) {
                        $cartItems[$index]['name'] = \App\Services\SecurityValidationService::sanitizeInput($item['name']);
                    }
                    if (isset($item['description'])) {
                        $cartItems[$index]['description'] = \App\Services\SecurityValidationService::sanitizeInput($item['description']);
                    }
                }

                Log::info('Cart items validated successfully', [
                    'items_count' => count($cartItems),
                    'total_amount' => array_sum(array_column($cartItems, 'amount'))
                ]);

            } catch (\JsonException $e) {
                Log::error('JSON parsing error', [
                    'error' => $e->getMessage(),
                    'input_length' => strlen($request->items)
                ]);
                return back()->with('error', 'Format JSON keranjang tidak valid');
            } catch (\Exception $e) {
                Log::error('Cart validation error', [
                    'error' => $e->getMessage(),
                    'input_length' => strlen($request->items ?? '')
                ]);
                return back()->with('error', 'Validasi keranjang gagal: ' . $e->getMessage());
            }
        }

        // Validate discount if provided
        $appliedDiscount = null;
        $discountAmount = 0;

        if ($request->promo_code || $request->discount_id) {
            $discountValidation = $this->validateDiscountInternal($request->promo_code ?? $request->discount_id, $request->amount);

            if (!$discountValidation['success']) {
                Log::error('Discount validation failed', $discountValidation);
                return back()->with('error', $discountValidation['message']);
            }

            $appliedDiscount = $discountValidation['discount'];
            $discountAmount = $appliedDiscount['discount_amount'];            Log::info('Discount applied', [
                'discount_code' => $appliedDiscount['code'],
                'discount_amount' => $discountAmount,
                'original_amount' => $request->amount,
                'final_amount' => $request->amount
            ]);
        }

        // Calculate transaction fees
        // Note: $request->amount already includes transaction fee from frontend
        // We need to extract the subtotal and transaction fee separately
        $totalAmountFromFrontend = (float) $request->amount;

        // Calculate what the subtotal should be by parsing cart items
        $calculatedSubtotal = 0;
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                $calculatedSubtotal += ($item['amount'] ?? 0) * ($item['quantity'] ?? 1);
            }
        }

        // Calculate expected transaction fee based on subtotal
        $bestFeeOption = $this->getBestFeeOption($calculatedSubtotal);
        $expectedTransactionFee = $bestFeeOption['min_fee'];

        // Verify if frontend sent correct total
        $expectedTotal = $calculatedSubtotal + $expectedTransactionFee - $discountAmount;

        // Use calculated values for consistency
        $paymentAmount = $calculatedSubtotal; // This is the real subtotal
        $transactionFee = $expectedTransactionFee;
        $finalAmount = $paymentAmount + $transactionFee - $discountAmount;

        Log::info('Payment calculation completed', [
            'cart_subtotal' => $calculatedSubtotal,
            'frontend_total' => $totalAmountFromFrontend,
            'expected_total' => $expectedTotal,
            'discount_amount' => $discountAmount,
            'transaction_fee' => $transactionFee,
            'final_amount' => $finalAmount,
            'recommended_method' => $bestFeeOption['recommended_method'],
            'difference' => $totalAmountFromFrontend - $expectedTotal
        ]);

        // Create payment record in database first
        $externalId = $this->generateExternalId($pendaftar, $cartItems);

        return DB::transaction(function() use ($pendaftar, $paymentAmount, $finalAmount, $cartItems, $appliedDiscount, $discountAmount, $transactionFee, $externalId, $bestFeeOption) {

            // Cleanup expired payments first
            $this->cleanupExpiredPayments($pendaftar->id);

            // Check for existing active payment
            $activePendingPayment = Payment::where('pendaftar_id', $pendaftar->id)
                ->where('status', self::STATUS_PENDING)
                ->where('created_at', '>', now()->subHour())
                ->first();

            if ($activePendingPayment) {
                Log::info('Found active pending payment', [
                    'payment_id' => $activePendingPayment->id,
                    'invoice_url' => $activePendingPayment->invoice_url
                ]);
                return redirect()->away($activePendingPayment->invoice_url);
            }

            // Create new Xendit invoice with cart data
            return $this->createXenditInvoiceWithCart(
                $pendaftar,
                $paymentAmount,
                $finalAmount,
                $cartItems,
                $appliedDiscount,
                $discountAmount,
                $transactionFee,
                $externalId,
                $bestFeeOption
            );
        });
    }

    private function createXenditInvoice(Pendaftar $pendaftar)
    {
        // Calculate current unit-based amount instead of using old payment_amount field
        $currentFormulirAmount = $this->getFormulirAmountByUnit($pendaftar->unit);

        Log::info('=== CREATE XENDIT INVOICE START ===', [
            'pendaftar_id' => $pendaftar->id,
            'unit' => $pendaftar->unit,
            'old_payment_amount' => $pendaftar->payment_amount,
            'new_calculated_amount' => $currentFormulirAmount
        ]);

        try {
            if (!$this->isValidApiKey()) {
                Log::error('Invalid Xendit API Key');
                throw new \Exception('Xendit API Key tidak valid atau tidak dikonfigurasi');
            }

            $user = Auth::user();

            // Create cart items for formulir payment using current unit-based amount
            $formulirCartItems = [
                [
                    'bill_id' => null,
                    'name' => 'Biaya Formulir Pendaftaran',
                    'amount' => $currentFormulirAmount
                ]
            ];

            $externalId = $this->generateExternalId($pendaftar, $formulirCartItems);
            $jenjangName = $this->getJenjangName($pendaftar->jenjang);

            Log::info('Xendit invoice data prepared', [
                'external_id' => $externalId,
                'amount' => $currentFormulirAmount,
                'student' => $pendaftar->nama_murid,
                'jenjang' => $jenjangName
            ]);

            // ✅ PERBAIKI KONFIGURASI INVOICE DATA
            $invoiceData = [
                'external_id' => $externalId,
                'amount' => (int)$currentFormulirAmount,
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
                        'transfer_amount' => (int)$currentFormulirAmount
                    ],
                    [
                        'bank_code' => 'BNI',
                        'collection_type' => 'POOL',
                        'transfer_amount' => (int)$currentFormulirAmount
                    ],
                    [
                        'bank_code' => 'BRI',
                        'collection_type' => 'POOL',
                        'transfer_amount' => (int)$currentFormulirAmount
                    ],
                    [
                        'bank_code' => 'MANDIRI',
                        'collection_type' => 'POOL',
                        'transfer_amount' => (int)$currentFormulirAmount
                    ],
                    [
                        'bank_code' => 'PERMATA',
                        'collection_type' => 'POOL',
                        'transfer_amount' => (int)$currentFormulirAmount
                    ],
                    [
                        'bank_code' => 'CIMB',
                        'collection_type' => 'POOL',
                        'transfer_amount' => (int)$currentFormulirAmount
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
                        'price' => (int)$currentFormulirAmount,
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
                'amount' => $currentFormulirAmount,
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

    private function createXenditInvoiceWithCart(
        Pendaftar $pendaftar,
        float $paymentAmount,
        float $finalAmount,
        array $cartItems,
        ?array $appliedDiscount,
        float $discountAmount,
        float $transactionFee,
        string $externalId,
        array $bestFeeOption
    ) {
        Log::info('=== CREATE XENDIT INVOICE WITH CART START ===', [
            'pendaftar_id' => $pendaftar->id,
            'payment_amount' => $paymentAmount,
            'final_amount' => $finalAmount,
            'cart_items_count' => count($cartItems),
            'discount_applied' => $appliedDiscount !== null,
            'discount_amount' => $discountAmount,
            'transaction_fee' => $transactionFee,
            'recommended_method' => $bestFeeOption['recommended_method']
        ]);

        try {
            if (!$this->isValidApiKey()) {
                Log::error('Invalid Xendit API Key');
                throw new \Exception('Xendit API Key tidak valid atau tidak dikonfigurasi');
            }

            $user = Auth::user();
            $jenjangName = $this->getJenjangName($pendaftar->jenjang);

            // Generate payment type description from cart items
            $paymentTypeDesc = $this->generatePaymentTypeDescription($cartItems);

            // Build description with payment type, cart items and discount info
            $description = sprintf(
                '%s - %s - %s - %s',
                $paymentTypeDesc,
                $jenjangName,
                $pendaftar->unit,
                $pendaftar->nama_murid
            );

            if ($appliedDiscount) {
                $description .= sprintf(' (Diskon: %s)', $appliedDiscount['code']);
            }

            // Build items array for invoice
            $invoiceItems = [];

            // Add cart items
            foreach ($cartItems as $item) {
                $invoiceItems[] = [
                    'name' => $item['name'] ?? 'Item Pembayaran',
                    'quantity' => 1,
                    'price' => (int) $item['amount'],
                    'category' => 'Education'
                ];
            }

            // Add discount as negative item if applied
            if ($appliedDiscount && $discountAmount > 0) {
                $invoiceItems[] = [
                    'name' => sprintf('Diskon %s', $appliedDiscount['code']),
                    'quantity' => 1,
                    'price' => -(int) $discountAmount,
                    'category' => 'Discount'
                ];
            }

            // Add transaction fee
            $invoiceItems[] = [
                'name' => sprintf('Biaya Transaksi (%s)', $bestFeeOption['recommended_method']),
                'quantity' => 1,
                'price' => (int) $transactionFee,
                'category' => 'Fee'
            ];

            $invoiceData = [
                'external_id' => $externalId,
                'amount' => (int) $finalAmount,
                'description' => $description,
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
                'should_exclude_credit_card' => false,
                'should_send_email' => true,
                'should_authenticate_credit_card' => true,
                'locale' => 'id',

                // Payment methods configuration
                'available_banks' => [
                    ['bank_code' => 'BCA', 'collection_type' => 'POOL', 'transfer_amount' => (int) $finalAmount],
                    ['bank_code' => 'BNI', 'collection_type' => 'POOL', 'transfer_amount' => (int) $finalAmount],
                    ['bank_code' => 'BRI', 'collection_type' => 'POOL', 'transfer_amount' => (int) $finalAmount],
                    ['bank_code' => 'MANDIRI', 'collection_type' => 'POOL', 'transfer_amount' => (int) $finalAmount],
                    ['bank_code' => 'PERMATA', 'collection_type' => 'POOL', 'transfer_amount' => (int) $finalAmount],
                    ['bank_code' => 'CIMB', 'collection_type' => 'POOL', 'transfer_amount' => (int) $finalAmount]
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
                ],
                'available_direct_debits' => [],

                // Detailed items breakdown
                'items' => $invoiceItems,

                // Customer notifications
                'customer_notification_preference' => [
                    'invoice_created' => ['email', 'sms'],
                    'invoice_reminder' => ['email', 'sms'],
                    'invoice_paid' => ['email', 'sms']
                ],

                // Fees configuration
                'fees' => [
                    [
                        'type' => 'xendit_fee',
                        'value' => 0 // Fee included in amount
                    ]
                ]
            ];

            Log::info('Sending cart payment request to Xendit', [
                'external_id' => $externalId,
                'final_amount' => $finalAmount,
                'items_count' => count($invoiceItems),
                'discount_applied' => $appliedDiscount !== null
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
                Log::error('Xendit API Error for cart payment', [
                    'status' => $response->status(),
                    'response' => $errorResponse,
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

            // Create payment record with cart data
            $payment = Payment::create([
                'pendaftar_id' => $pendaftar->id,
                'external_id' => $externalId,
                'invoice_id' => $responseData['id'],
                'invoice_url' => $responseData['invoice_url'],
                'amount' => $finalAmount,
                'status' => self::STATUS_PENDING,
                'xendit_response' => $responseData,
                'expires_at' => now()->addHour(),
                // Store cart metadata
                'metadata' => [
                    'cart_items' => $cartItems,
                    'applied_discount' => $appliedDiscount,
                    'discount_amount' => $discountAmount,
                    'transaction_fee' => $transactionFee,
                    'subtotal' => $paymentAmount,
                    'final_amount' => $finalAmount
                ]
            ]);

            Log::info('Cart payment record created successfully', [
                'payment_id' => $payment->id,
                'invoice_url' => $responseData['invoice_url'],
                'final_amount' => $finalAmount,
                'has_discount' => $appliedDiscount !== null
            ]);

            return redirect()->away($responseData['invoice_url']);

        } catch (\Exception $e) {
            Log::error('=== CREATE XENDIT CART INVOICE ERROR ===', [
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

        // Get transaction types and cart items for dynamic display
        $transactionTypes = $this->getTransactionTypes($payment);
        $cartItems = $this->getCartItemsFromMetadata($payment);
        $paymentTypeDescription = $this->generatePaymentTypeDescription($cartItems);

        return view('user.payment.success', compact(
            'payment',
            'pendaftar',
            'jenjangName',
            'transactionTypes',
            'paymentTypeDescription',
            'cartItems'
        ));
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

        // Get additional data if payment exists
        $transactionTypes = [];
        $cartItems = [];
        $paymentTypeDescription = 'Pembayaran PPDB';

        if ($payment) {
            $transactionTypes = $this->getTransactionTypes($payment);
            $cartItems = $this->getCartItemsFromMetadata($payment);
            $paymentTypeDescription = $this->generatePaymentTypeDescription($cartItems);
        }

        return view('user.payment.failed', compact(
            'payment',
            'pendaftar',
            'jenjangName',
            'transactionTypes',
            'cartItems',
            'paymentTypeDescription'
        ));
    }

    public function transactions()
    {
        $user = Auth::user();

        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        // Get student status information for context
        $studentStatus = $pendaftar ? ($pendaftar->student_status ?? 'inactive') : 'inactive';
        $isActiveStudent = $studentStatus === 'active';

        $payments = Payment::whereHas('pendaftar', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['pendaftar'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        // Collect all bill IDs from metadata to reduce queries
        $billIds = [];
        foreach ($payments as $payment) {
            if ($payment->metadata && isset($payment->metadata['cart_items'])) {
                foreach ($payment->metadata['cart_items'] as $item) {
                    if (isset($item['bill_id'])) {
                        $billIds[] = $item['bill_id'];
                    }
                }
            }
        }

        // Preload all bills at once for better performance
        $bills = StudentBill::whereIn('id', array_unique($billIds))->get()->keyBy('id');

        // Add transaction type data to each payment with preloaded bills
        foreach ($payments as $payment) {
            $payment->transaction_types = $this->getTransactionTypesWithBills($payment, $bills);
            $payment->primary_type = $this->getPrimaryTransactionType($payment);
        }

        return view('user.transactions.index', compact('payments', 'studentStatus', 'isActiveStudent', 'pendaftar'));
    }

    public function transactionDetail($id)
    {
        $user = Auth::user();

        $payment = Payment::whereHas('pendaftar', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['pendaftar'])
        ->findOrFail($id);

        // Add transaction type data
        $payment->transaction_types = $this->getTransactionTypes($payment);
        $payment->primary_type = $this->getPrimaryTransactionType($payment);

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
     * Handle production webhook (with verification for ALL environments)
     */
    private function handleProductionWebhook(Request $request)
    {
        Log::info('=== PROCESSING WEBHOOK ===', [
            'environment' => $this->getXenditEnvironment(),
            'data' => $request->all()
        ]);

        // SECURITY FIX: Always verify webhook signature regardless of environment
        if (!$this->verifyWebhookSignature($request)) {
            Log::warning('Unauthorized webhook attempt', [
                'received_token' => $request->header('x-callback-token'),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'environment' => $this->getXenditEnvironment()
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
        // SECURITY FIX: Enhanced authorization check
        $user = Auth::user();
        if (!$user || !$user->isAdmin() || !$user->is_active) {
            Log::warning('Unauthorized admin access attempt', [
                'user_id' => $user?->id,
                'role' => $user?->role,
                'is_active' => $user?->is_active,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            abort(403, 'Unauthorized access');
        }

        // Build query dengan filter - SECURITY FIX: Add input validation
        $request->validate([
            'status' => 'nullable|in:PENDING,PAID,EXPIRED,FAILED,CANCELLED',
            'jenjang' => 'nullable|string|max:50',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'search' => 'nullable|string|max:255'
        ]);

        $query = Payment::with(['pendaftar.user']);

        // Filter by status - using validated input
        if ($request->filled('status')) {
            $query->where('status', $request->validated()['status']);
        }

        // Filter by jenjang - using validated input
        if ($request->filled('jenjang')) {
            $query->whereHas('pendaftar', function($q) use ($request) {
                $q->where('jenjang', $request->validated()['jenjang']);
            });
        }

        // Filter by date range - using validated input
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->validated()['date_from']);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->validated()['date_to']);
        }

        // Search by name or registration number - using parameter binding
        if ($request->filled('search')) {
            $search = $request->validated()['search'];
            $query->whereHas('pendaftar', function($q) use ($search) {
                $q->where('nama_murid', 'like', '%' . $search . '%')
                  ->orWhere('no_pendaftaran', 'like', '%' . $search . '%');
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
        // SECURITY FIX: Enhanced authorization check
        $user = Auth::user();
        if (!$user || !$user->isAdmin() || !$user->is_active) {
            Log::warning('Unauthorized admin access attempt', [
                'user_id' => $user?->id,
                'role' => $user?->role,
                'is_active' => $user?->is_active,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            abort(403, 'Unauthorized access');
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

    /**
     * Get transaction types from payment metadata and related bills
     */
    private function getTransactionTypes($payment)
    {
        $types = [];

        // Try to get from metadata first (for new payments)
        if ($payment->metadata && isset($payment->metadata['cart_items'])) {
            foreach ($payment->metadata['cart_items'] as $item) {
                if (isset($item['bill_id'])) {
                    $bill = StudentBill::find($item['bill_id']);
                    if ($bill) {
                        $typeInfo = $this->getBillTypeInfo($bill->bill_type, $bill);
                        // Check if type already exists to avoid duplicates
                        $exists = false;
                        foreach ($types as $existingType) {
                            if ($existingType['type'] === $typeInfo['type'] && $existingType['label'] === $typeInfo['label']) {
                                $exists = true;
                                break;
                            }
                        }
                        if (!$exists) {
                            $types[] = $typeInfo;
                        }
                    }
                }
            }
        }

        // Fallback: try to infer from payment amount and existing bills (for old payments)
        if (empty($types) && $payment->pendaftar) {
            $possibleBills = StudentBill::where('pendaftar_id', $payment->pendaftar->id)
                ->where('total_amount', '<=', $payment->amount * 1.1) // Allow 10% tolerance for fees
                ->where('total_amount', '>=', $payment->amount * 0.9)
                ->where('payment_status', 'paid')
                ->get();

            foreach ($possibleBills as $bill) {
                $typeInfo = $this->getBillTypeInfo($bill->bill_type, $bill);
                $exists = false;
                foreach ($types as $existingType) {
                    if ($existingType['type'] === $typeInfo['type']) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $types[] = $typeInfo;
                }
            }
        }

        return $types;
    }

    /**
     * Get primary transaction type for display
     */
    private function getPrimaryTransactionType($payment)
    {
        $types = $this->getTransactionTypes($payment);

        if (empty($types)) {
            return [
                'type' => 'other',
                'label' => 'Pembayaran',
                'color' => 'secondary',
                'icon' => 'credit-card'
            ];
        }

        // Priority order: registration_fee > uang_pangkal > spp > others
        $priority = ['registration_fee', 'uang_pangkal', 'spp', 'uniform', 'books', 'supplies', 'activity', 'other'];

        foreach ($priority as $priorityType) {
            foreach ($types as $type) {
                if ($type['type'] === $priorityType) {
                    return $type;
                }
            }
        }

        return $types[0];
    }

    /**
     * Get bill type information with styling
     */
    private function getBillTypeInfo($billType, $bill = null)
    {
        $typeMap = [
            'registration_fee' => [
                'type' => 'registration_fee',
                'label' => 'Formulir Pendaftaran',
                'color' => 'primary',
                'icon' => 'file-earmark-text'
            ],
            'uang_pangkal' => [
                'type' => 'uang_pangkal',
                'label' => 'Uang Pangkal',
                'color' => 'success',
                'icon' => 'piggy-bank'
            ],
            'spp' => [
                'type' => 'spp',
                'label' => $this->getSppLabel($bill),
                'color' => 'info',
                'icon' => 'calendar-month'
            ],
            'uniform' => [
                'type' => 'uniform',
                'label' => 'Seragam',
                'color' => 'warning',
                'icon' => 'person-square'
            ],
            'books' => [
                'type' => 'books',
                'label' => 'Buku',
                'color' => 'secondary',
                'icon' => 'book'
            ],
            'supplies' => [
                'type' => 'supplies',
                'label' => 'Alat Tulis',
                'color' => 'dark',
                'icon' => 'pencil'
            ],
            'activity' => [
                'type' => 'activity',
                'label' => 'Kegiatan',
                'color' => 'danger',
                'icon' => 'activity'
            ],
            'other' => [
                'type' => 'other',
                'label' => 'Lainnya',
                'color' => 'secondary',
                'icon' => 'three-dots'
            ]
        ];

        return $typeMap[$billType] ?? $typeMap['other'];
    }

    /**
     * Get SPP label with month information
     */
    /**
     * Get SPP label with month information
     */
    private function getSppLabel($bill)
    {
        if (!$bill || !$bill->month) {
            return 'SPP';
        }

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $monthName = $months[$bill->month] ?? '';
        return $monthName ? "SPP $monthName" : 'SPP';
    }

    /**
     * Get transaction types with preloaded bills for performance
     */
    private function getTransactionTypesWithBills($payment, $bills)
    {
        $types = [];

        // Try to get from metadata first (for new payments)
        if ($payment->metadata && isset($payment->metadata['cart_items'])) {
            foreach ($payment->metadata['cart_items'] as $item) {
                if (isset($item['bill_id']) && isset($bills[$item['bill_id']])) {
                    $bill = $bills[$item['bill_id']];
                    $typeInfo = $this->getBillTypeInfo($bill->bill_type, $bill);
                    // Check if type already exists to avoid duplicates
                    $exists = false;
                    foreach ($types as $existingType) {
                        if ($existingType['type'] === $typeInfo['type'] && $existingType['label'] === $typeInfo['label']) {
                            $exists = true;
                            break;
                        }
                    }
                    if (!$exists) {
                        $types[] = $typeInfo;
                    }
                }
            }
        }

        // Fallback for payments without metadata
        if (empty($types)) {
            $types = $this->getTransactionTypes($payment);
        }

        return $types;
    }

    /**
     * Debug payment data - temporary method
     */
    public function debugPaymentData()
    {
        $user = Auth::user();

        // Get the latest successful payment for the user
        $payment = Payment::whereHas('pendaftar', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('status', 'completed')
        ->orderBy('paid_at', 'desc')
        ->first();

        if (!$payment) {
            return response()->json(['error' => 'No successful payment found']);
        }

        $cartItems = $this->getCartItemsFromMetadata($payment);
        $transactionTypes = $this->getTransactionTypes($payment);
        $paymentTypeDescription = $this->generatePaymentTypeDescription($cartItems);

        return response()->json([
            'payment_id' => $payment->id,
            'external_id' => $payment->external_id,
            'payment_metadata' => $payment->metadata,
            'cart_items' => $cartItems,
            'transaction_types' => $transactionTypes,
            'payment_type_description' => $paymentTypeDescription,
            'transaction_fee' => $payment->metadata['transaction_fee'] ?? 0,
            'discount_amount' => $payment->metadata['discount_amount'] ?? 0,
            'total_amount' => $payment->amount,
            'formatted_amount' => $payment->formatted_amount,
        ]);
    }
}
