# Webhook Security untuk Production

## 🔒 Mengapa CSRF TIDAK Cocok untuk Webhook?

**CSRF (Cross-Site Request Forgery) protection TIDAK TEPAT untuk webhook** karena:

1. **CSRF melindungi dari serangan browser** - webhook dipanggil oleh server eksternal (Xendit)
2. **Token CSRF berubah per session** - Xendit tidak bisa menyimpan token yang berubah-ubah
3. **Xendit tidak bisa mengakses DOM** untuk mendapatkan CSRF token
4. **Ada mekanisme keamanan yang lebih tepat** untuk webhook

## ✅ Mekanisme Keamanan Webhook yang Tepat

### 1. **Signature Verification (Paling Penting)**
```php
// Verifikasi menggunakan webhook token
hash_equals($expectedToken, $receivedToken)

// Verifikasi menggunakan HMAC (jika didukung)
hash_hmac('sha256', $timestamp . $payload, $secret)
```

### 2. **IP Whitelist**
```php
// Hanya terima webhook dari IP Xendit
$trustedIpRanges = [
    '147.139.0.0/16',     // Xendit primary range
    '103.20.0.0/16',      // Additional range
];
```

### 3. **Rate Limiting**
```php
// Maksimal 100 request per menit per IP
$maxAttempts = 100;
$decayMinutes = 1;
```

### 4. **Replay Attack Protection**
```php
// Request tidak boleh lebih dari 5 menit
$tolerance = 300;
if ($currentTime - $requestTime > $tolerance) {
    return false;
}
```

### 5. **Payload Validation**
```php
// Validasi struktur data dan format
- external_id harus format: PPDB-PMB\d+-\d+-[A-Za-z0-9]+
- status harus: PENDING, PAID, SETTLED, EXPIRED, FAILED, CANCELLED
```

## 🛡️ Implementasi Production Security

### File yang Dibuat/Dimodifikasi:

1. **`SecureWebhookMiddleware.php`** - Middleware keamanan webhook
2. **`routes/web.php`** - Route dengan middleware keamanan
3. **`bootstrap/app.php`** - Registrasi middleware

### Konfigurasi Environment:

```env
# Development
XENDIT_SECRET_KEY=xnd_development_...
XENDIT_WEBHOOK_TOKEN=your_webhook_token_here

# Production
XENDIT_SECRET_KEY=xnd_production_...
XENDIT_WEBHOOK_TOKEN=your_production_webhook_token_here
```

## 🔍 Monitoring & Logging

### Log Security Events:
```php
// Rate limit exceeded
Log::warning('Webhook rate limit exceeded', ['ip' => $ip]);

// Untrusted IP
Log::warning('Webhook from untrusted IP', ['ip' => $ip]);

// Invalid signature
Log::warning('Invalid webhook signature', ['ip' => $ip]);

// Replay attack
Log::warning('Potential replay attack detected', ['timestamp' => $timestamp]);
```

### Metrics yang Dipantau:
- **Failed Authentication Rate** - Jika tinggi, ada serangan
- **Requests from Unknown IPs** - Monitoring IP baru
- **Response Times** - Performance monitoring
- **Error Rates** - Kesehatan sistem

## 📋 Checklist Keamanan Production

### ✅ **Webhook Security Implemented:**
1. **No CSRF Protection** - ✅ Dihapus karena tidak tepat
2. **Signature Verification** - ✅ Token & HMAC verification
3. **IP Whitelist** - ✅ Hanya IP Xendit yang diterima
4. **Rate Limiting** - ✅ 100 requests/minute per IP
5. **Replay Protection** - ✅ Timestamp validation
6. **Payload Validation** - ✅ Structure & format validation
7. **Logging & Monitoring** - ✅ Comprehensive logging

### ✅ **Other API Endpoints:**
1. **Authenticated Endpoints** - ✅ Gunakan CSRF untuk web forms
2. **Public API Endpoints** - ✅ Gunakan API token authentication
3. **Admin Endpoints** - ✅ Double protection (auth + CSRF)

## 🔧 Testing Webhook Security

### 1. **Test Valid Webhook**
```bash
curl -X POST http://localhost:8000/webhook/xendit \
  -H "Content-Type: application/json" \
  -H "x-callback-token: your_webhook_token" \
  -d '{"external_id": "PPDB-PMB09190001-1758264483-v0bvyx", "status": "PAID"}'
```

### 2. **Test Invalid Token**
```bash
curl -X POST http://localhost:8000/webhook/xendit \
  -H "Content-Type: application/json" \
  -H "x-callback-token: invalid_token" \
  -d '{"external_id": "PPDB-PMB09190001-1758264483-v0bvyx", "status": "PAID"}'
```

### 3. **Test Rate Limiting**
```bash
# Send 101 requests rapidly
for i in {1..101}; do
  curl -X POST http://localhost:8000/webhook/xendit \
    -H "x-callback-token: your_token" \
    -d '{"external_id": "test", "status": "PAID"}'
done
```

## 🚀 Deployment Recommendations

### 1. **Environment Variables**
```bash
# Set proper webhook token in production
XENDIT_WEBHOOK_TOKEN=secure_random_string_here
```

### 2. **Firewall Rules**
```bash
# Allow only Xendit IPs at infrastructure level
iptables -A INPUT -s 147.139.0.0/16 -p tcp --dport 443 -j ACCEPT
```

### 3. **Cloudflare Settings**
```bash
# If using Cloudflare, configure Page Rules:
# webhook/* -> Security Level: High, Cache Level: Bypass
```

### 4. **Monitoring Setup**
```bash
# Set up alerts for:
- High failed webhook authentication rate (>5% in 5 min)
- Requests from unknown IPs
- High response times (>1000ms)
- Error rates (>1% in 5 min)
```

## ⚠️ Security Best Practices

### ✅ **DO:**
- Use signature verification instead of CSRF for webhooks
- Implement IP whitelisting
- Log all security events
- Monitor for anomalies
- Use HTTPS in production
- Validate all input data
- Implement rate limiting

### ❌ **DON'T:**
- Use CSRF protection for webhooks
- Accept webhooks from any IP
- Ignore failed authentication attempts
- Skip input validation
- Use HTTP in production
- Store sensitive data in logs

## 📊 Security vs Usability Balance

### Development Environment:
- **More Permissive** - Allow localhost, skip some validations
- **Extensive Logging** - Debug information included
- **Relaxed Rate Limits** - Higher limits for testing

### Production Environment:
- **Strict Security** - All validations enforced
- **Minimal Logging** - Security events only
- **Tight Rate Limits** - Conservative limits

---

**Kesimpulan**: Webhook security yang proper TIDAK menggunakan CSRF, tetapi menggunakan signature verification, IP whitelist, rate limiting, dan validasi payload yang ketat. Implementasi ini lebih aman dan sesuai dengan best practices industry untuk webhook security.
