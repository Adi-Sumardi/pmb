# Checklist Setup Dashboard Xendit untuk PPDB Al-Azhar

## 🎯 Langkah-Langkah Setup (Berurutan):

### ✅ **STEP 1: API Keys & Security**
```
□ Login ke dashboard.xendit.co
□ Masuk ke Settings → API Keys
□ Copy Secret Key (xnd_secret_production_...)
□ Simpan di tempat aman
□ Set IP Whitelist: 103.129.149.246 (IP VPS)
□ Enable HTTPS Only
```

### ✅ **STEP 2: Webhooks Configuration**
```
□ Masuk ke Settings → Webhooks
□ Add New Webhook:
   - URL: https://yapi-alazhar.id/api/xendit/webhook
   - Verification Token: Ka07Ag4933w6nhSy45eHYFWMbGKFLtBtKiP20utwaT5f65te

□ Select Events untuk PPDB (checklist events yang diperlukan):
   WAJIB AKTIFKAN:
   ☑️ FVA paid (Virtual Account pembayaran berhasil)
   ☑️ Retail outlets (OTC) paid (Alfamart/Indomaret berhasil)
   ☑️ eWallet Payment Status (OVO/DANA/LinkAja/ShopeePay)
   ☑️ Payment Succeeded (Payment Request V2)
   ☑️ Payment Failed (Payment Request V2)
   ☑️ Invoices paid (Invoice pembayaran berhasil)
   ☑️ Also notify when invoice expired (Tagihan kadaluarsa)

   OPSIONAL (bisa diaktifkan nanti):
   ☐ Cards authentication (jika pakai kartu kredit)
   ☐ QR code paid (jika pakai QR payment)
   ☐ Balance and Transactions report (laporan otomatis)

   TIDAK PERLU DIAKTIFKAN:
   ☐ Disbursement sent (transfer keluar - tidak digunakan)
   ☐ Payout Links (tidak digunakan)
   ☐ XenPlatform events (marketplace - tidak digunakan)
   ☐ Recurring (subscription - tidak digunakan)

□ Test webhook connection
□ Save configuration
```

### ✅ **STEP 3: Virtual Accounts (Bank Transfer)**
```
□ Masuk ke Products → Virtual Accounts
□ Enable Banks:
   ☑️ BCA Virtual Account
   ☑️ BNI Virtual Account  
   ☑️ BRI Virtual Account
   ☑️ Mandiri Virtual Account
   ☑️ Permata Virtual Account
□ Set Default Expiration: 48 hours
□ Disable Partial Payment
□ Enable Auto Settlement
```

### ✅ **STEP 4: E-wallets (Digital Payment)**
```
□ Masuk ke Products → E-wallets
□ Enable Wallets:
   ☑️ OVO
   ☑️ DANA
   ☑️ LinkAja
   ☑️ ShopeePay
□ Set Redirect URLs:
   - Success: https://yapi-alazhar.id/payment/success
   - Failure: https://yapi-alazhar.id/payment/failed
   - Cancel: https://yapi-alazhar.id/payment/cancel
□ Enable Mobile Optimization
```

### ✅ **STEP 5: Retail Outlets (Minimarket)**
```
□ Masuk ke Products → Retail Outlets
□ Enable Outlets:
   ☑️ ALFAMART
   ☑️ INDOMARET
□ Set Configuration:
   - Default Expiration: 3 days
   - Customer Message: "Pembayaran PPDB Al-Azhar Yogyakarta"
   - Receipt Template: Custom (dengan logo sekolah)
```

### ✅ **STEP 6: Settlement (Transfer ke Rekening Sekolah)**
```
□ Masuk ke Settings → Settlement
□ Add Bank Account:
   - Bank: [Bank sekolah - contoh: BCA]
   - Account Number: [Nomor rekening yayasan]
   - Account Name: [Nama pemegang rekening]
   - Branch: [Cabang bank]
□ Set Auto Settlement:
   - Schedule: Daily at 23:00 WIB
   - Minimum Amount: Rp 100,000
   - Enable weekends: No
□ Enable Settlement Notifications
```

### ✅ **STEP 7: Reports & Notifications**
```
□ Masuk ke Settings → Notifications
□ Email Settings:
   - Daily Transaction Report: ☑️ Enabled
   - Settlement Report: ☑️ Enabled
   - Failed Payment Alert: ☑️ Enabled
   - Send to: admin@yapi-alazhar.id
□ Report Schedule:
   - Daily: 07:00 WIB
   - Weekly: Monday 08:00 WIB
   - Monthly: 1st day 09:00 WIB
```

### ✅ **STEP 8: Security & Compliance**
```
□ Masuk ke Settings → Security
□ Enable Features:
   ☑️ Two-Factor Authentication (2FA)
   ☑️ IP Whitelisting
   ☑️ API Rate Limiting
   ☑️ HTTPS Enforcement
□ Set Login Restrictions:
   - Max Failed Attempts: 5
   - Account Lockout: 30 minutes
   - Session Timeout: 4 hours
```

### ⚠️ **STEP 9: Testing (Opsional tapi Disarankan)**
```
□ Masuk ke Tools → API Testing
□ Test Virtual Account:
   - Create test VA
   - Simulate payment
   - Verify webhook received
□ Test E-wallet:
   - Create test payment
   - Complete payment flow
   - Check settlement
□ Test Webhook:
   - Send test webhook
   - Verify application response
```

### ✅ **STEP 10: Branding & Customization**
```
□ Masuk ke Settings → Branding
□ Upload Logo: Logo Al-Azhar Yogyakarta
□ Set Colors:
   - Primary: #1B5E20 (hijau sekolah)
   - Secondary: #FFC107 (emas)
□ Custom Messages:
   - Success: "Pembayaran berhasil! Terima kasih."
   - Failure: "Pembayaran gagal. Silakan coba lagi."
   - Pending: "Menunggu konfirmasi pembayaran..."
```

---

## 📋 **Informasi yang Perlu Disiapkan:**

### Bank Account Details:
```
Bank Name: ________________
Account Number: ________________
Account Holder: ________________
Branch: ________________
SWIFT Code: ________________ (jika ada)
```

### Contact Information:
```
Admin Email: admin@yapi-alazhar.id
Finance Email: finance@yapi-alazhar.id
Technical Email: tech@yapi-alazhar.id
Phone Number: ________________
WhatsApp Number: ________________
```

### Business Information:
```
Business Name: Yayasan Al-Azhar Yogyakarta
Business Type: Educational Institution
Tax ID (NPWP): ________________
Business Address: ________________
```

---

## 🔍 **Verifikasi Setelah Setup:**

### ✅ **Checklist Verifikasi:**
```
□ Secret Key tersimpan aman
□ Webhook URL accessible dari internet
□ Virtual Account bisa generate
□ E-wallet redirect berfungsi
□ Settlement account terkonfigurasi
□ Email notifications terkirim
□ Transaction dapat ditrack
□ Balance monitoring aktif
□ Reports otomatis terkirim
□ Security settings optimal
```

### 🧪 **Test Scenarios:**
```
□ Test Payment: Rp 100,000 via BCA VA
□ Test Webhook: Verify callback received
□ Test Settlement: Check auto transfer
□ Test Reports: Verify email delivery
□ Test Security: Try invalid webhook
```

---

## ⚡ **Quick Setup Priority:**

### 🚨 **URGENT (Hari 1):**
1. API Keys & Webhooks
2. Virtual Accounts (BCA, BNI, BRI, Mandiri)
3. Settlement Configuration

### ⏰ **HIGH PRIORITY (Hari 2-3):**
4. E-wallets (OVO, DANA, LinkAja, ShopeePay)
5. Reports & Notifications
6. Security Settings

### 📅 **NORMAL PRIORITY (Minggu 1):**
7. Retail Outlets (Alfamart, Indomaret)
8. Branding & Customization
9. Advanced Testing

### 🔮 **FUTURE (Jika Diperlukan):**
10. Credit Card Integration
11. xenShield Fraud Protection
12. Advanced Analytics

---

## 🆘 **Troubleshooting Common Issues:**

### ❌ **Webhook Tidak Jalan:**
```
□ Cek URL accessible: curl https://yapi-alazhar.id/api/xendit/webhook
□ Cek verification token match
□ Cek firewall tidak block
□ Test manual dari dashboard
```

### ❌ **Payment Gagal:**
```
□ Cek secret key valid
□ Cek amount format (integer, not float)
□ Cek currency = "IDR"
□ Cek external_id unique
```

### ❌ **Settlement Tertunda:**
```
□ Cek bank account valid
□ Cek minimum amount reached
□ Cek settlement schedule
□ Contact Xendit support
```

**Apakah ada step tertentu yang ingin saya jelaskan lebih detail?**
