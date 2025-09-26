# Xendit API Permissions untuk Sistem PPDB

## 🔐 **Rekomendasi Permission Settings:**

### ✅ **Money-in Products: WRITE**
**Alasan:** Sistem perlu bisa membuat payment (VA, E-wallet, OTC)
```
Permission: WRITE
Fungsi: Create payment requests, invoices, virtual accounts
Status: WAJIB untuk menerima pembayaran
```

### ❌ **Money-out Products: NONE**
**Alasan:** PPDB tidak perlu transfer dana keluar
```
Permission: NONE
Fungsi: Disbursements, payouts (tidak relevan untuk sekolah)
Status: TIDAK DIPERLUKAN
```

### ✅ **Balance: READ**
**Alasan:** Monitor saldo untuk reconciliation
```
Permission: READ
Fungsi: Cek saldo account, monitoring dana masuk
Status: PENTING untuk laporan keuangan
```

### ✅ **Report: READ**
**Alasan:** Ambil laporan transaksi dan settlement
```
Permission: READ
Fungsi: Download transaction reports, settlement reports
Status: WAJIB untuk administrasi
```

### ✅ **Transaction: READ**
**Alasan:** Cek status pembayaran dan history
```
Permission: READ
Fungsi: Get payment status, transaction details
Status: WAJIB untuk konfirmasi pembayaran
```

### ⚠️ **xenPlatform Account: NONE**
**Alasan:** Tidak menggunakan multi-vendor
```
Permission: NONE
Fungsi: Marketplace features (tidak digunakan)
Status: TIDAK DIPERLUKAN
```

### ⚠️ **xenPlatform Account Holder: NONE**
**Alasan:** Tidak menggunakan sub-accounts
```
Permission: NONE
Fungsi: Manage sub-accounts (tidak digunakan)
Status: TIDAK DIPERLUKAN
```

### ⚠️ **xenPlatform Split Payments: NONE**
**Alasan:** Tidak ada bagi hasil payment
```
Permission: NONE
Fungsi: Split revenue (tidak digunakan)
Status: TIDAK DIPERLUKAN
```

### ⚠️ **xenShield Transaction Assessments: READ** (Opsional)
**Alasan:** Monitor fraud jika diperlukan
```
Permission: READ atau NONE
Fungsi: Fraud detection monitoring
Status: OPSIONAL (bisa diaktifkan nanti)
```

---

## 📋 **Ringkasan Permission yang Direkomendasikan:**

```
✅ Money-in products: WRITE
❌ Money-out products: NONE
✅ Balance: READ
✅ Report: READ
✅ Transaction: READ (jika ada opsi ini)
❌ xenPlatform Account: NONE
❌ xenPlatform Account Holder: NONE
❌ xenPlatform Split Payments: NONE
⚠️ xenShield Transaction Assessments: READ (opsional)
```

---

## 🔍 **Penjelasan Detail Permission:**

### **WRITE Permission:**
- Bisa membuat payment requests baru
- Bisa update payment status
- Bisa cancel payment
- **HANYA untuk Money-in products**

### **READ Permission:**
- Bisa ambil data/informasi
- Bisa download reports
- Bisa cek status dan balance
- **Untuk monitoring dan reporting**

### **NONE Permission:**
- Tidak ada akses sama sekali
- API key tidak bisa akses fitur ini
- **Untuk fitur yang tidak digunakan**

---

## ⚡ **Quick Setup:**

```
Money-in products: WRITE ← Wajib untuk terima pembayaran
Money-out products: NONE ← Tidak perlu transfer keluar
Balance: READ ← Wajib untuk monitor saldo
Report: READ ← Wajib untuk laporan
Transaction: READ ← Wajib untuk cek status payment
xenPlatform Account: NONE ← Tidak pakai marketplace
xenPlatform Account Holder: NONE ← Tidak pakai sub-account
xenPlatform Split Payments: NONE ← Tidak pakai bagi hasil
xenShield Transaction Assessments: READ ← Opsional untuk fraud detection
```

---

## 🛡️ **Security Best Practices:**

### ✅ **DO (Lakukan):**
- Set permission minimal yang diperlukan
- Gunakan WRITE hanya untuk yang benar-benar butuh
- Aktifkan READ untuk monitoring
- Set NONE untuk fitur yang tidak digunakan

### ❌ **DON'T (Jangan):**
- Berikan WRITE permission untuk semua
- Aktifkan Money-out jika tidak perlu transfer
- Berikan akses xenPlatform jika tidak pakai marketplace
- Set permission berlebihan "untuk jaga-jaga"

---

## 🔧 **Implementasi di Kode Laravel:**

### Fitur yang Akan Berfungsi:
```php
// ✅ BISA (Money-in: WRITE)
Xendit\VirtualAccount::create($params);
Xendit\EWallet::createPayment($params);
Xendit\Retail::create($params);

// ✅ BISA (Balance: READ)
Xendit\Balance::retrieve();

// ✅ BISA (Report: READ)
Xendit\Transaction::retrieve($id);
Xendit\Transaction::getAll();

// ❌ TIDAK BISA (Money-out: NONE)
Xendit\Disbursement::create($params); // Error 403
```

### Error yang Mungkin Terjadi:
```
Permission: NONE → HTTP 403 Forbidden
Permission: READ + mencoba WRITE → HTTP 403 Forbidden
Permission: WRITE + fitur disabled → HTTP 400 Bad Request
```

---

## 🧪 **Testing Permission:**

### Test Money-in (harus berhasil):
```bash
curl -X POST https://api.xendit.co/virtual_accounts \
  -H "Authorization: Basic $(echo -n SECRET_KEY: | base64)" \
  -H "Content-Type: application/json" \
  -d '{"external_id":"test-123","bank_code":"BCA","name":"Test"}'
```

### Test Balance (harus berhasil):
```bash
curl -X GET https://api.xendit.co/balance \
  -H "Authorization: Basic $(echo -n SECRET_KEY: | base64)"
```

### Test Money-out (harus error 403):
```bash
curl -X POST https://api.xendit.co/disbursements \
  -H "Authorization: Basic $(echo -n SECRET_KEY: | base64)" \
  -H "Content-Type: application/json" \
  -d '{"external_id":"test","amount":10000}'
```

---

## 📞 **Jika Ada Masalah:**

### Permission Error 403:
1. Cek permission setting di dashboard
2. Regenerate API key jika perlu
3. Tunggu 5-10 menit untuk propagasi

### Feature Not Available:
1. Cek apakah produk sudah diaktifkan
2. Verifikasi business account
3. Contact Xendit support

---

**Setting ini akan memberikan akses yang cukup untuk sistem PPDB tanpa risiko keamanan berlebihan. Apakah ada permission lain yang muncul di dashboard Anda?**
