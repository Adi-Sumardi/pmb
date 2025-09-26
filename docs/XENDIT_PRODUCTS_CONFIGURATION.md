# Konfigurasi Produk Xendit untuk Sistem PPDB

## Produk Xendit yang Relevan untuk PPDB:

### 1. **Money-in Products** (Menerima Pembayaran):

#### ✅ **Virtual Accounts** - PRIORITAS TINGGI
**Untuk:** Pembayaran uang pangkal, SPP, dll via transfer bank
- **Bank:** BCA, BNI, BRI, Mandiri, Permata
- **Setting:**
  - Enable semua bank virtual account
  - Set expiration time: 24-48 jam
  - Enable callback notifications

#### ✅ **E-wallets** - PRIORITAS TINGGI  
**Untuk:** Pembayaran via OVO, DANA, LinkAja, ShopeePay
- **Setting:**
  - Enable OVO, DANA, LinkAja, ShopeePay
  - Set success/failure redirect URLs
  - Enable mobile-friendly checkout

#### ✅ **Retail Outlets (OTC)** - PRIORITAS SEDANG
**Untuk:** Pembayaran di Alfamart, Indomaret
- **Setting:**
  - Enable Alfamart & Indomaret
  - Set expiration: 1-3 hari
  - Customer instruction templates

#### ⚠️ **Credit Card** - OPSIONAL
**Untuk:** Pembayaran kartu kredit (jika diperlukan)
- **Setting:**
  - Enable Visa, MasterCard
  - Set 3DS authentication
  - Fraud detection rules

#### ⚠️ **Invoices** - OPSIONAL
**Untuk:** Tagihan manual/terstruktur
- **Setting:**
  - Custom invoice templates
  - Auto-reminder emails
  - Payment terms configuration

#### ❌ **Recurring Payments** - TIDAK PERLU
*Untuk subscription/berlangganan (tidak relevan untuk PPDB)*

#### ❌ **PayLater** - TIDAK PERLU
*Kredit/cicilan (tidak cocok untuk pembayaran sekolah)*

### 2. **Money-out Products** (Mengirim Uang):

#### ❌ **Disbursements** - TIDAK PERLU
*Untuk transfer dana ke pihak ketiga (tidak relevan untuk PPDB)*

#### ❌ **Batch Disbursements** - TIDAK PERLU
*Transfer massal (tidak diperlukan sekolah)*

#### ❌ **Payout Link** - TIDAK PERLU
*Link pembayaran ke vendor (tidak relevan)*

### 3. **Reporting & Monitoring:**

#### ✅ **Balance** - WAJIB
**Setting:**
- Real-time balance monitoring
- Auto settlement ke rekening sekolah
- Balance threshold alerts

#### ✅ **Report** - WAJIB
**Setting:**
- Daily transaction reports
- Monthly reconciliation reports
- Export ke Excel/PDF

#### ✅ **Transaction** - WAJIB
**Setting:**
- Real-time transaction monitoring
- Payment status webhooks
- Transaction search & filter

### 4. **Advanced Features:**

#### ❌ **xenPlatform** - TIDAK PERLU
*Untuk marketplace/multi-vendor (tidak relevan)*

#### ❌ **Split Payments** - TIDAK PERLU
*Untuk bagi hasil payment (tidak diperlukan)*

#### ⚠️ **xenShield** - OPSIONAL
**Fraud protection** - bisa berguna jika ada transaksi mencurigakan

---

## Setting Konfigurasi di Dashboard Xendit:

### 1. **API Keys & Webhooks:**
```
✅ Generate Production API Keys:
   - Public Key: xnd_public_production_... (✅ sudah ada)
   - Secret Key: xnd_secret_production_... (⏳ perlu diambil)

✅ Setup Webhooks:
   - Webhook URL: https://yapi-alazhar.id/api/xendit/webhook
   - Events: payment.paid, payment.failed, payment.expired
   - Verification Token: Ka07Ag4933w6nhSy45eHYFWMbGKFLtBtKiP20utwaT5f65te (✅ sudah ada)
```

### 2. **Virtual Accounts Settings:**
```
✅ Enable Banks:
   - BCA Virtual Account ✓
   - BNI Virtual Account ✓  
   - BRI Virtual Account ✓
   - Mandiri Virtual Account ✓
   - Permata Virtual Account ✓

✅ Configuration:
   - Default Expiration: 48 hours
   - Allow Partial Payment: No
   - Auto Settlement: Yes (daily)
```

### 3. **E-wallet Settings:**
```
✅ Enable Wallets:
   - OVO ✓
   - DANA ✓
   - LinkAja ✓
   - ShopeePay ✓

✅ Configuration:
   - Success URL: https://yapi-alazhar.id/payment/success
   - Failure URL: https://yapi-alazhar.id/payment/failed
   - Mobile Optimization: Yes
```

### 4. **Retail Outlets (OTC):**
```
✅ Enable Outlets:
   - ALFAMART ✓
   - INDOMARET ✓

✅ Configuration:
   - Default Expiration: 3 days
   - Customer Message: "Pembayaran PPDB Al-Azhar"
   - Receipt Template: Custom
```

### 5. **Security & Compliance:**
```
✅ Security Settings:
   - IP Whitelist: [IP VPS Anda]
   - HTTPS Only: Yes
   - API Rate Limiting: Default

✅ Compliance:
   - PCI DSS Compliance: Auto
   - Data Retention: 7 years
   - Privacy Settings: GDPR Compliant
```

### 6. **Notification Settings:**
```
✅ Email Notifications:
   - Transaction Success: Yes
   - Transaction Failed: Yes
   - Daily Reports: Yes
   - Send to: admin@yapi-alazhar.id

✅ WhatsApp Integration:
   - Via custom webhook ke sistem WhatsApp
   - Status update otomatis ke orang tua
```

### 7. **Settlement Configuration:**
```
✅ Bank Account Settings:
   - Bank: [Bank sekolah]
   - Account Number: [Rekening sekolah]
   - Account Name: [Nama yayasan]
   - Auto Settlement: Daily at 23:00 WIB

✅ Settlement Reports:
   - Email daily settlement report
   - Include fee breakdown
   - Tax invoice generation
```

---

## Langkah Setup di Dashboard Xendit:

### Phase 1: Basic Setup (Wajib)
1. **API Keys & Webhooks** ← *Sedang proses (butuh secret key)*
2. **Virtual Accounts** - Enable BCA, BNI, BRI, Mandiri
3. **Balance & Settlement** - Setup rekening sekolah

### Phase 2: Payment Methods (Prioritas)
4. **E-wallets** - Enable OVO, DANA, LinkAja, ShopeePay
5. **Retail Outlets** - Enable Alfamart, Indomaret
6. **Success/Failure URLs** - Set redirect pages

### Phase 3: Monitoring & Reports (Wajib)
7. **Transaction Monitoring** - Real-time dashboard
8. **Daily Reports** - Auto email reports
9. **Reconciliation** - Monthly financial reports

### Phase 4: Advanced (Opsional)
10. **Credit Card** - Jika diperlukan
11. **xenShield** - Fraud protection
12. **Custom Branding** - Logo sekolah di payment page

---

## Yang Perlu Dikonfigurasi di Kode Laravel:

### Environment Variables (.env):
```env
# Xendit Configuration
XENDIT_SECRET_KEY=xnd_secret_production_... # ← PERLU DIISI
XENDIT_WEBHOOK_TOKEN=Ka07Ag4933w6nhSy45eHYFWMbGKFLtBtKiP20utwaT5f65te

# Payment Methods
XENDIT_ENABLE_VA=true
XENDIT_ENABLE_EWALLET=true
XENDIT_ENABLE_OTC=true
XENDIT_ENABLE_CREDIT_CARD=false

# Settlement
XENDIT_AUTO_SETTLEMENT=true
XENDIT_SETTLEMENT_BANK=BCA
XENDIT_SETTLEMENT_ACCOUNT=1234567890
```

### Payment Controller Configuration:
```php
// Supported payment methods
$paymentMethods = [
    'va' => ['BCA', 'BNI', 'BRI', 'MANDIRI', 'PERMATA'],
    'ewallet' => ['OVO', 'DANA', 'LINKAJA', 'SHOPEEPAY'],
    'otc' => ['ALFAMART', 'INDOMARET']
];
```

---

## Estimasi Biaya Xendit:

### Transaction Fees:
- **Virtual Account**: Rp 5,000 per transaksi
- **E-wallet**: 0.7% + Rp 1,000 per transaksi  
- **Retail Outlets**: Rp 5,500 per transaksi
- **Credit Card**: 2.9% + Rp 2,000 per transaksi

### Settlement:
- **Auto Settlement**: GRATIS
- **Manual Settlement**: Rp 6,500 per transfer

---

## Rekomendasi untuk PPDB Al-Azhar:

### ✅ **WAJIB Aktifkan:**
1. Virtual Accounts (semua bank)
2. E-wallets (OVO, DANA, LinkAja, ShopeePay)
3. Balance monitoring & settlement
4. Transaction reports
5. Webhook notifications

### ⚠️ **OPSIONAL:**
1. Retail Outlets (Alfamart/Indomaret) - jika ada permintaan
2. Credit Card - jika orang tua meminta
3. xenShield - jika khawatir fraud

### ❌ **TIDAK PERLU:**
1. Recurring payments
2. PayLater
3. Disbursements
4. xenPlatform features

**Apakah Anda ingin saya buatkan checklist langkah-langkah setup di dashboard Xendit?**
