# Panduan Detail Setting Webhook Xendit untuk PPDB

## 🎯 **Webhook Configuration Dashboard**

### 📍 **Lokasi Setting:**
```
Login → Settings → Webhooks → Add New Webhook
```

### 🔧 **Basic Configuration:**
```
Webhook URL: https://yapi-alazhar.id/api/xendit/webhook
Verification Token: Ka07Ag4933w6nhSy45eHYFWMbGKFLtBtKiP20utwaT5f65te
```

---

## ✅ **Events yang WAJIB Diaktifkan untuk PPDB:**

### 1. **FIXED VIRTUAL ACCOUNTS**
```
☑️ FVA paid
   → Notifikasi ketika pembayaran VA berhasil
   → Penting untuk: BCA, BNI, BRI, Mandiri VA payments

☐ FVA created (tidak perlu)
   → Hanya notifikasi VA dibuat, bukan payment
```

### 2. **RETAIL OUTLETS (OTC)**
```
☑️ Retail outlets (OTC) paid
   → Notifikasi pembayaran di Alfamart/Indomaret berhasil
   → Penting untuk: Pembayaran offline
```

### 3. **E-WALLETS**
```
☑️ eWallet Payment Status
   → Notifikasi status pembayaran OVO, DANA, LinkAja, ShopeePay
   → Penting untuk: Digital wallet payments
```

### 4. **PAYMENT REQUESTS V2**
```
☑️ Payment Succeeded
   → Notifikasi payment berhasil (semua metode)
   → WAJIB untuk update status pembayaran

☑️ Payment Failed
   → Notifikasi payment gagal
   → Penting untuk retry logic

☑️ Payment Pending
   → Notifikasi payment menunggu (opsional)
   → Berguna untuk tracking
```

### 5. **INVOICES**
```
☑️ Invoices paid
   → Notifikasi invoice/tagihan sudah dibayar
   → Penting jika menggunakan invoice system

☑️ Also notify when invoice expired
   → Notifikasi tagihan kadaluarsa
   → Penting untuk follow-up pembayaran
```

---

## ⚠️ **Events OPSIONAL (Aktifkan Jika Diperlukan):**

### 6. **CARDS** (Jika Mengaktifkan Kartu Kredit)
```
☐ Cards authentication
   → Notifikasi autentikasi kartu kredit
   → Aktifkan jika menerima pembayaran kartu kredit

☐ Cards tokenization
   → Notifikasi tokenisasi kartu (recurring payment)
   → Tidak perlu untuk PPDB
```

### 7. **QR CODES** (Jika Menggunakan QR Payment)
```
☐ QR code paid & refunded
   → Notifikasi pembayaran via QR code
   → Aktifkan jika menggunakan QRIS
```

### 8. **REPORT**
```
☐ Balance and Transactions report
   → Notifikasi laporan otomatis
   → Berguna untuk monitoring keuangan
```

---

## ❌ **Events yang TIDAK PERLU Diaktifkan:**

### **DISBURSEMENT**
```
☐ Disbursement sent
☐ Batch disbursement sent
☐ Payouts v2
→ Untuk transfer keluar, tidak digunakan PPDB
```

### **PAYOUT LINK**
```
☐ Payout Links
→ Untuk pembayaran ke vendor, tidak digunakan PPDB
```

### **XenPlatform**
```
☐ Account Created
☐ Account Updated
☐ Account Suspension
☐ Split Payment Status
→ Untuk marketplace, tidak digunakan PPDB
```

### **RECURRING**
```
☐ Recurring
→ Untuk subscription/berlangganan, tidak digunakan PPDB
```

### **PAYLATER**
```
☐ Paylater Payment Status
→ Untuk kredit/cicilan, tidak cocok untuk sekolah
```

---

## 🔧 **Langkah-Langkah Setting (Interface Baru):**

### **Konsep Interface Baru:**
- Setiap kategori punya field **Webhook URL** sendiri
- Isi URL yang sama untuk semua kategori yang dibutuhkan
- Tombol **Test** dan **Save** untuk setiap kategori

---

## ✅ **KATEGORI yang PERLU DIISI URL (WAJIB):**

### **1. FIXED VIRTUAL ACCOUNTS**
```
Webhook URL: https://yapi-alazhar.id/api/xendit/webhook
Events: FVA paid, FVA created
Action: Klik "Test" → "Save"
```

### **2. RETAIL OUTLETS (OTC)**  
```
Webhook URL: https://yapi-alazhar.id/api/xendit/webhook
Events: Retail outlets (OTC) paid
Action: Klik "Test" → "Save"
```

### **3. E-WALLETS** (jika ada)
```
Webhook URL: https://yapi-alazhar.id/api/xendit/webhook
Events: eWallet Payment Status
Action: Klik "Test" → "Save"
```

### **4. INVOICES** (jika ada)
```
Webhook URL: https://yapi-alazhar.id/api/xendit/webhook
Events: Invoices paid, Invoice expired
Action: Klik "Test" → "Save"
```

### **5. PAYMENT REQUESTS** (jika ada)
```
Webhook URL: https://yapi-alazhar.id/api/xendit/webhook
Events: Payment Success, Payment Failed
Action: Klik "Test" → "Save"
```

---

## ❌ **KATEGORI yang TIDAK PERLU DIISI (SKIP):**

### **DISBURSEMENT**
```
Webhook URL: [KOSONGKAN]
Reason: Untuk transfer keluar, tidak digunakan PPDB
```

### **PAYOUT LINK**
```
Webhook URL: [KOSONGKAN]  
Reason: Untuk pembayaran vendor, tidak digunakan PPDB
```

### **RECURRING/SUBSCRIPTION**
```
Webhook URL: [KOSONGKAN]
Reason: Untuk langganan bulanan, tidak digunakan PPDB
```

---

## 🎯 **Step-by-Step Execution:**

### **Step 1: FIXED VIRTUAL ACCOUNTS**
```
1. Cari section "FIXED VIRTUAL ACCOUNTS"
2. Di field "Webhook URL", isi: https://yapi-alazhar.id/api/xendit/webhook
3. Klik tombol "Test" (harus berhasil/hijau)
4. Klik tombol "Save"
5. ✅ Selesai untuk Virtual Accounts
```

### **Step 2: RETAIL OUTLETS (OTC)**
```
1. Cari section "RETAIL OUTLETS (OTC)"
2. Di field "Webhook URL", isi: https://yapi-alazhar.id/api/xendit/webhook
3. Klik tombol "Test" (harus berhasil/hijau)
4. Klik tombol "Save"
5. ✅ Selesai untuk Retail Outlets
```

### **Step 3: Ulangi untuk Kategori Lain**
```
Lakukan hal yang sama untuk:
- E-WALLETS (jika ada)
- INVOICES (jika ada)
- PAYMENT REQUESTS (jika ada)
```

### **Step 4: Skip Kategori Tidak Perlu**
```
JANGAN isi Webhook URL untuk:
- DISBURSEMENT
- PAYOUT LINK
- RECURRING/SUBSCRIPTION
```

---

## 🧪 **Testing Webhook:**

### **Test dari Dashboard:**
```
1. Klik "Test" pada webhook yang sudah dibuat
2. Pilih event type: "Payment Succeeded"
3. Klik "Send Test"
4. Cek apakah aplikasi menerima webhook
```

### **Test Manual:**
```bash
curl -X POST https://yapi-alazhar.id/api/xendit/webhook \
  -H "Content-Type: application/json" \
  -H "x-callback-token: Ka07Ag4933w6nhSy45eHYFWMbGKFLtBtKiP20utwaT5f65te" \
  -d '{
    "id": "test-payment-123",
    "status": "PAID",
    "amount": 100000,
    "external_id": "test-ppdb-001"
  }'
```

---

## 📊 **Webhook Response yang Diharapkan:**

### **Successful Response:**
```json
{
  "status": "success",
  "message": "Webhook received and processed"
}
```

### **Error Response:**
```json
{
  "status": "error",
  "message": "Invalid webhook token"
}
```

---

## 🚨 **Troubleshooting:**

### **Webhook Tidak Jalan:**
```
1. Cek URL accessible: curl https://yapi-alazhar.id/api/xendit/webhook
2. Cek verification token match
3. Cek firewall tidak block port 443
4. Cek SSL certificate valid
5. Cek Laravel log: tail -f storage/logs/laravel.log
```

### **Event Tidak Terkirim:**
```
1. Pastikan event sudah dicentang
2. Cek webhook masih aktif
3. Test manual dari dashboard
4. Cek webhook history di dashboard
```

### **Verification Token Error:**
```
1. Pastikan token match di .env file
2. Cek header x-callback-token
3. Regenerate token jika perlu
```

---

## 📝 **Summary Checklist:**

### ✅ **Minimal Configuration untuk PPDB:**
```
□ Webhook URL: https://yapi-alazhar.id/api/xendit/webhook
□ Verification Token: Ka07Ag4933w6nhSy45eHYFWMbGKFLtBtKiP20utwaT5f65te
□ FVA paid ✓
□ Retail outlets (OTC) paid ✓
□ eWallet Payment Status ✓
□ Payment Succeeded ✓
□ Payment Failed ✓
□ Invoices paid ✓
□ Invoice expired notifications ✓
```

### 🎯 **Total Events Diaktifkan: 7 events**
- Cukup untuk semua fitur PPDB
- Tidak berlebihan
- Security optimal

**Dengan konfigurasi ini, sistem akan menerima notifikasi untuk semua jenis pembayaran yang relevan untuk PPDB!**
