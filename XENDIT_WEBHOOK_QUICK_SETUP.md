# 🎯 QUICK GUIDE: Setting Webhook Xendit Interface Baru

## ✅ **YANG PERLU DIISI WEBHOOK URL:**

### **1. FIXED VIRTUAL ACCOUNTS**
```
Field: Webhook URL
Isi: https://yapi-alazhar.id/api/xendit/webhook
Events: FVA paid, FVA created
Tombol: Test → Save
Status: ✅ WAJIB (untuk pembayaran Bank Transfer)
```

### **2. RETAIL OUTLETS (OTC)**
```
Field: Webhook URL  
Isi: https://yapi-alazhar.id/api/xendit/webhook
Events: Retail outlets (OTC) paid
Tombol: Test → Save
Status: ✅ WAJIB (untuk Alfamart/Indomaret)
```

### **3. E-WALLETS** (jika ada di list)
```
Field: Webhook URL
Isi: https://yapi-alazhar.id/api/xendit/webhook
Events: eWallet Payment Status
Tombol: Test → Save
Status: ✅ WAJIB (untuk OVO/DANA/LinkAja/ShopeePay)
```

### **4. INVOICES** (jika ada di list)
```
Field: Webhook URL
Isi: https://yapi-alazhar.id/api/xendit/webhook
Events: Invoices paid
Tombol: Test → Save
Status: ✅ WAJIB (untuk tagihan/invoice)
```

### **5. PAYMENT REQUESTS/CARDS** (jika ada di list)
```
Field: Webhook URL
Isi: https://yapi-alazhar.id/api/xendit/webhook
Events: Payment succeeded/failed
Tombol: Test → Save
Status: ⚠️ OPSIONAL (untuk kartu kredit)
```

---

## ❌ **YANG TIDAK PERLU DIISI (SKIP):**

### **DISBURSEMENT**
```
Field: Webhook URL
Action: [KOSONGKAN - jangan isi]
Reason: Transfer keluar, tidak digunakan PPDB
```

### **PAYOUT LINK**
```
Field: Webhook URL
Action: [KOSONGKAN - jangan isi]  
Reason: Pembayaran ke vendor, tidak digunakan PPDB
```

### **RECURRING/SUBSCRIPTION** (jika ada)
```
Field: Webhook URL
Action: [KOSONGKAN - jangan isi]
Reason: Langganan bulanan, tidak digunakan PPDB
```

---

## 🚀 **Langkah Eksekusi Cepat:**

### **Untuk Setiap Kategori yang Diperlukan:**
```
1. Cari section di halaman webhook
2. Copy-paste URL: https://yapi-alazhar.id/api/xendit/webhook
3. Klik "Test" (harus muncul success/hijau)
4. Klik "Save"
5. Lanjut ke section berikutnya
```

### **Verification Token (jika diminta):**
```
Ka07Ag4933w6nhSy45eHYFWMbGKFLtBtKiP20utwaT5f65te
```

---

## 🧪 **Testing:**

### **Test Success Indicators:**
- ✅ Tombol "Test" menunjukkan status hijau/success
- ✅ Tidak ada error message
- ✅ Bisa klik "Save" tanpa error

### **Jika Test Gagal:**
```
1. Cek URL spelling: https://yapi-alazhar.id/api/xendit/webhook
2. Pastikan domain sudah pointing ke VPS
3. Pastikan SSL certificate aktif
4. Cek verification token jika diminta
```

---

## 📊 **Summary Konfigurasi:**

### **Total Kategori yang Diisi: 2-5 kategori**
- **FIXED VIRTUAL ACCOUNTS** ← Wajib
- **RETAIL OUTLETS (OTC)** ← Wajib
- **E-WALLETS** ← Jika ada
- **INVOICES** ← Jika ada
- **PAYMENT REQUESTS** ← Opsional

### **Total Kategori yang Diabaikan: 3+ kategori**
- **DISBURSEMENT** ← Skip
- **PAYOUT LINK** ← Skip  
- **RECURRING** ← Skip

**Dengan konfigurasi ini, sistem PPDB akan menerima notifikasi untuk semua metode pembayaran yang relevan!** 🎓
