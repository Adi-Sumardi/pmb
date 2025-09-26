# Panduan Konfigurasi Xendit Payment Gateway

## Penjelasan Key yang Dibutuhkan

Anda sudah memberikan:
- **Public Key**: `xnd_public_production_Wl73VHkLIb0QhRi4PSdH8RrBl3LjfA7zfPKlZOvdNw2uHGr6wdyqALKO4QfcYTQy`
- **Webhook Token**: `Ka07Ag4933w6nhSy45eHYFWMbGKFLtBtKiP20utwaT5f65te`

## Yang Masih Dibutuhkan: SECRET KEY

### Perbedaan Public Key vs Secret Key:

1. **Public Key** (yang sudah Anda berikan):
   - Digunakan di frontend/client side
   - Aman untuk diekspos di browser
   - Untuk membuat payment token/checkout

2. **Secret Key** (yang masih dibutuhkan):
   - Digunakan di backend/server side
   - RAHASIA, tidak boleh diekspos
   - Untuk verifikasi payment, webhook, dan operasi sensitive

## Cara Mendapatkan Secret Key:

### 1. Login ke Xendit Dashboard
- Kunjungi: https://dashboard.xendit.co/login
- Login dengan akun Xendit Anda

### 2. Navigasi ke API Keys
- Setelah login, pergi ke menu **"Settings"** atau **"Developer"**
- Pilih **"API Keys"** atau **"Keys & Webhooks"**

### 3. Temukan Secret Key
- Cari key yang bertipe **"Secret Key"** atau **"Private Key"**
- Format biasanya: `xnd_secret_production_...` (untuk production)
- JANGAN BAGIKAN key ini ke siapapun kecuali developer

### 4. Copy Secret Key
- Klik tombol "Show" atau "Reveal" 
- Copy seluruh string secret key
- Simpan dengan aman

## Setelah Mendapat Secret Key:

### Update Konfigurasi VPS:
```bash
# Edit file deploy-to-vps.sh
# Ganti bagian:
XENDIT_SECRET_KEY=[TUNGGU_SECRET_KEY_DARI_DASHBOARD]

# Dengan:
XENDIT_SECRET_KEY=xnd_secret_production_XXXXX...
```

### Atau Langsung Set di Server:
```bash
# Setelah deployment, edit .env di server:
nano /var/www/ppdb/.env

# Update line:
XENDIT_SECRET_KEY=xnd_secret_production_XXXXX...
```

## Konfigurasi Lengkap yang Sudah Siap:

### Environment Variables (.env):
```env
# Payment Gateway (Xendit)
XENDIT_SECRET_KEY=xnd_secret_production_XXXXX... # <- PERLU DIISI
XENDIT_WEBHOOK_TOKEN=Ka07Ag4933w6nhSy45eHYFWMbGKFLtBtKiP20utwaT5f65te # ✅ SUDAH SIAP

# Webhook URL (otomatis tersedia setelah deployment):
XENDIT_WEBHOOK_URL=https://yapi-alazhar.id/api/xendit/webhook
```

### JavaScript Frontend (sudah dikonfigurasi):
```javascript
// Public key untuk frontend
const xenditPublicKey = 'xnd_public_production_Wl73VHkLIb0QhRi4PSdH8RrBl3LjfA7zfPKlZOvdNw2uHGr6wdyqALKO4QfcYTQy';
```

## Keamanan Secret Key:

### ⚠️ SANGAT PENTING:
1. **JANGAN** bagikan secret key di grup WhatsApp
2. **JANGAN** commit secret key ke GitHub
3. **JANGAN** berikan ke orang selain developer
4. **SELALU** simpan di environment variables server

### ✅ Cara Aman:
1. Set langsung di server via SSH
2. Gunakan secure file transfer jika perlu
3. Hapus dari terminal history setelah digunakan

## Testing Payment Gateway:

### Setelah Secret Key diset:
```bash
# Test koneksi ke Xendit
curl -X GET https://api.xendit.co/balance \
  -H "Authorization: Basic base64(SECRET_KEY:)"
```

### Dari aplikasi Laravel:
```bash
php artisan tinker
Xendit::setApiKey(env('XENDIT_SECRET_KEY'));
// Test API connection
```

## Troubleshooting:

### Jika Payment Gagal:
1. Cek secret key sudah benar
2. Cek webhook URL accessible: `https://yapi-alazhar.id/api/xendit/webhook`
3. Cek log Laravel: `tail -f /var/www/ppdb/storage/logs/laravel.log`

### Jika Webhook Tidak Jalan:
1. Pastikan webhook token match
2. Test webhook dari Xendit dashboard
3. Cek firewall tidak block webhook

## Status Konfigurasi Saat Ini:

✅ **Sudah Siap:**
- Public Key (untuk frontend)
- Webhook Token (untuk verifikasi webhook)
- Webhook URL endpoint
- Payment controller dan logic
- Database tables untuk payment

⏳ **Menunggu:**
- Secret Key dari dashboard Xendit

🚀 **Setelah Secret Key diset:**
- Semua konfigurasi payment gateway akan lengkap
- Siap untuk deployment ke VPS
- Payment system akan berfungsi 100%

---

**Langkah Selanjutnya:**
1. Login ke Xendit dashboard
2. Dapatkan secret key
3. Update konfigurasi
4. Deploy ke VPS dengan script otomatis
5. Test payment system

**Butuh bantuan lebih lanjut dengan Xendit dashboard?** Beritahu saya!
