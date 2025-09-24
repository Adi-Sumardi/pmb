# SISTEM PEMBAYARAN COMPREHENSIVE DESIGN
## SPP, UANG PANGKAL, DAN MULTI PAYMENT

### OVERVIEW ARSITEKTUR SISTEM

```
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│   REGISTRATION  │    │   SPP BULANAN    │    │  UANG PANGKAL   │
│   (One-time)    │    │   (Recurring)    │    │  (One-time)     │
└─────────────────┘    └──────────────────┘    └─────────────────┘
         │                       │                       │
         v                       v                       v
┌─────────────────┐    ┌──────────────────┐    ┌─────────────────┐
│ Payment Table   │    │ StudentBill      │    │ StudentBill     │
│ (Xendit Direct) │    │ + BillPayment    │    │ + BillPayment   │
└─────────────────┘    └──────────────────┘    └─────────────────┘
                                │
                                v
                    ┌──────────────────┐
                    │  MULTI PAYMENT   │
                    │ (Seragam, Buku,  │
                    │  Kegiatan, dll)  │
                    └──────────────────┘
```

### 1. SPP BULANAN SYSTEM (Recurring Payment)

#### A. Settings Configuration
```php
// SppSetting Model - Already exists ✅
SppSetting::create([
    'name' => 'SPP TK A 2025/2026',
    'school_level' => 'tka',
    'school_origin' => 'internal', // internal/external
    'amount' => 350000,
    'academic_year' => '2025/2026',
    'status' => 'active',
    'description' => 'SPP bulanan untuk TK A'
]);
```

#### B. Auto-Generate Monthly Bills
```php
// Command: php artisan spp:generate-monthly-bills
// Generate 12 bills (Juli - Juni) untuk setiap siswa aktif
foreach ($activeStudents as $student) {
    for ($month = 7; $month <= 12; $month++) { // Juli - Desember
        StudentBill::create([
            'pendaftar_id' => $student->id,
            'bill_type' => 'spp',
            'description' => "SPP " . Carbon::create(2025, $month)->format('F Y'),
            'total_amount' => $sppSetting->amount,
            'due_date' => Carbon::create(2025, $month, 10), // Jatuh tempo tanggal 10
            'academic_year' => '2025/2026',
            'month' => $month,
            'payment_status' => 'pending'
        ]);
    }
}
```

#### C. Xendit Virtual Account Integration
```php
// SPP menggunakan Virtual Account untuk auto-debit
// Setiap siswa dapat Virtual Account number yang sama sepanjang tahun
$virtualAccount = [
    'external_id' => 'SPP-' . $student->id . '-2025',
    'bank_code' => 'BNI', // atau BRI, Mandiri
    'name' => $student->nama_murid,
    'account_number' => $student->virtual_account_number,
    'is_closed' => false,
    'expected_amount' => $sppSetting->amount
];
```

### 2. UANG PANGKAL SYSTEM (One-time Annual)

#### A. Settings Configuration
```php
// UangPangkalSetting Model - Already exists ✅
UangPangkalSetting::create([
    'name' => 'Uang Pangkal TK A 2025/2026',
    'school_level' => 'tka',
    'school_origin' => 'internal',
    'amount' => 2500000,
    'academic_year' => '2025/2026',
    'allow_installments' => true,
    'max_installments' => 3,
    'first_installment_percentage' => 50.00,
    'status' => 'active'
]);
```

#### B. Auto-Generate at Academic Year Start
```php
// Command: php artisan uang-pangkal:generate-yearly-bills
foreach ($newStudents as $student) {
    StudentBill::create([
        'pendaftar_id' => $student->id,
        'uang_pangkal_setting_id' => $upSetting->id,
        'bill_type' => 'uang_pangkal',
        'description' => 'Uang Pangkal Tahun Ajaran 2025/2026',
        'total_amount' => $upSetting->amount,
        'allow_installments' => $upSetting->allow_installments,
        'total_installments' => $upSetting->max_installments,
        'installment_amount' => $upSetting->amount / $upSetting->max_installments,
        'due_date' => Carbon::create(2025, 7, 15), // 15 Juli
        'academic_year' => '2025/2026',
        'payment_status' => 'pending'
    ]);
}
```

### 3. MULTI PAYMENT SYSTEM (Flexible Fees)

#### A. Additional Fee Types
```php
// Extend StudentBill untuk berbagai jenis pembayaran
$feeTypes = [
    'seragam' => [
        'name' => 'Seragam Sekolah',
        'amount' => 450000,
        'required' => true
    ],
    'buku' => [
        'name' => 'Buku Pelajaran',
        'amount' => 350000,
        'required' => true
    ],
    'kegiatan' => [
        'name' => 'Biaya Kegiatan',
        'amount' => 200000,
        'required' => false
    ],
    'ekskul' => [
        'name' => 'Ekstrakurikuler',
        'amount' => 150000,
        'required' => false
    ],
    'study_tour' => [
        'name' => 'Study Tour',
        'amount' => 500000,
        'required' => false
    ]
];
```

#### B. Cart-Based Checkout System
```php
// User dapat pilih multiple items dalam satu transaksi
$cartItems = [
    ['bill_id' => 123, 'type' => 'seragam', 'amount' => 450000],
    ['bill_id' => 124, 'type' => 'buku', 'amount' => 350000],
    ['bill_id' => 125, 'type' => 'kegiatan', 'amount' => 200000]
];

// Total: Rp 1.000.000 + fees
$payment = Payment::create([
    'pendaftar_id' => $student->id,
    'amount' => 1003000, // dengan fees
    'metadata' => [
        'cart_items' => $cartItems,
        'transaction_fee' => 3000,
        'payment_type' => 'multi_payment'
    ]
]);
```

### 4. PAYMENT GATEWAY STRATEGY

#### A. Payment Method per Type
```php
$paymentStrategies = [
    'registration_fee' => [
        'methods' => ['BANK_TRANSFER', 'QR_CODE', 'RETAIL_OUTLET'],
        'gateway' => 'xendit',
        'table' => 'payments'
    ],
    'spp' => [
        'methods' => ['VIRTUAL_ACCOUNT'],
        'gateway' => 'xendit',
        'table' => 'student_bills + bill_payments',
        'recurring' => true
    ],
    'uang_pangkal' => [
        'methods' => ['BANK_TRANSFER', 'QR_CODE', 'VIRTUAL_ACCOUNT'],
        'gateway' => 'xendit',
        'table' => 'student_bills + bill_payments',
        'installments' => true
    ],
    'multi_payment' => [
        'methods' => ['BANK_TRANSFER', 'QR_CODE', 'RETAIL_OUTLET'],
        'gateway' => 'xendit',
        'table' => 'student_bills + bill_payments',
        'cart_based' => true
    ]
];
```

#### B. Webhook Handler Logic
```php
// Enhanced webhook handler
private function handlePaymentSuccess(Payment $payment, array $webhookData): void
{
    if ($payment->metadata['payment_type'] === 'registration_fee') {
        // Registration: Update Payment table only (no BillPayment)
        $this->updateRegistrationPayment($payment);
    } else {
        // SPP/Uang Pangkal/Multi: Update StudentBill + create BillPayment
        $this->updateStudentBillsFromCart($payment);
    }
}
```

### 5. USER EXPERIENCE FLOW

#### A. Dashboard Siswa
```php
// Dashboard menampilkan:
1. Status Registration Fee (PAID/PENDING)
2. SPP Bulanan (Outstanding bills)
3. Uang Pangkal (if applicable)
4. Additional Fees (seragam, buku, dll)
5. Payment History
```

#### B. Payment Options
```php
// User dapat:
1. Bayar SPP per bulan (Virtual Account)
2. Bayar SPP beberapa bulan sekaligus (Cart)
3. Bayar Uang Pangkal (full/installment)
4. Bayar Multiple fees sekaligus (Cart)
5. Download receipt & invoice
```

### 6. ADMIN MANAGEMENT

#### A. Revenue Tracking
```php
// Enhanced RevenueCalculationService
$revenueStreams = [
    'registration_revenue' => 'Payment table',
    'spp_revenue' => 'BillPayment where bill_type = spp',
    'uang_pangkal_revenue' => 'BillPayment where bill_type = uang_pangkal',
    'additional_fees_revenue' => 'BillPayment where bill_type IN (seragam, buku, kegiatan)',
    'total_revenue' => 'Sum of all above'
];
```

#### B. Bill Management
```php
// Admin dapat:
1. Generate monthly SPP bills
2. Create custom fees (study tour, etc)
3. Manage installment payments
4. Send payment reminders
5. Export payment reports
6. Handle manual payments
```

### 7. NOTIFICATION SYSTEM

#### A. Auto Reminders
```php
// Command: php artisan payments:send-reminders
// Kirim reminder:
- SPP H-3 sebelum jatuh tempo
- Uang Pangkal H-7 sebelum jatuh tempo
- Overdue notifications
- Payment success confirmations
```

### NEXT STEPS IMPLEMENTATION:

1. **Setup SPP & Uang Pangkal Settings**
2. **Create Bill Generation Commands**  
3. **Enhance Payment Controller**
4. **Create User Payment Interface**
5. **Setup Xendit Virtual Account**
6. **Test End-to-End Flow**

Mau mulai implement yang mana dulu?
