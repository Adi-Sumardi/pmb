# Laravel Application - Cloudflare Deployment Guide

## 🌐 Overview

Aplikasi PMB YAPI Al Azhar telah dikonfigurasi untuk deployment optimal di belakang Cloudflare proxy. Konfigurasi ini mencakup keamanan, performa, dan kompatibilitas penuh dengan layanan Cloudflare.

## 📋 Fitur Cloudflare yang Dikonfigurasi

### 🔒 Keamanan
- **Trusted Proxies**: Konfigurasi IP ranges Cloudflare untuk deteksi IP client yang benar
- **Security Headers**: Header keamanan yang kompatibel dengan Cloudflare
- **Content Security Policy**: CSP yang dioptimasi untuk CDN Cloudflare
- **SSL/TLS**: Konfigurasi untuk Full (strict) mode Cloudflare

### ⚡ Performa
- **Cache Headers**: Header cache yang optimal untuk Cloudflare CDN
- **Static Assets**: Konfigurasi khusus untuk caching aset statis
- **Compression**: Header yang mendukung kompresi Cloudflare

### 🔍 Monitoring
- **Cloudflare Headers**: Parsing header CF-Ray, CF-Connecting-IP, dll
- **Logging**: Log yang mencakup informasi Cloudflare
- **Error Tracking**: Integrasi dengan Cloudflare Analytics

## 🛠️ File Konfigurasi

### Middleware yang Ditambahkan
1. **CloudflareMiddleware.php**: Middleware khusus untuk menangani request dari Cloudflare
2. **SecurityHeadersMiddleware.php**: Diperbarui untuk kompatibilitas Cloudflare

### File Konfigurasi
- `config/trustedproxy.php`: IP ranges Cloudflare dan header yang dipercaya
- `config/cors.php`: CORS configuration untuk Cloudflare
- `.env.cloudflare`: Template environment untuk production

### Script Deployment
- `scripts/deploy-cloudflare.sh`: Script otomatis untuk deployment

## 🚀 Panduan Deployment

### 1. Persiapan Environment
```bash
# Copy file environment untuk production
cp .env.cloudflare .env

# Edit dan sesuaikan nilai-nilai berikut:
# - APP_URL (domain Anda)
# - DB_* (konfigurasi database production)
# - SESSION_DOMAIN (domain untuk cookies)
# - CLOUDFLARE_* (API credentials)
```

### 2. Menjalankan Script Deployment
```bash
./scripts/deploy-cloudflare.sh
```

### 3. Konfigurasi di Cloudflare Dashboard

#### SSL/TLS Settings
- **SSL/TLS encryption mode**: Full (strict)
- **Always Use HTTPS**: On
- **HSTS**: Enabled (max-age: 31536000)
- **Automatic HTTPS Rewrites**: On
- **Opportunistic Encryption**: On
- **TLS version**: 1.3 preferred

#### Security Settings
- **Security Level**: Medium atau High
- **DDoS Protection**: Enabled
- **Web Application Firewall**: Enabled
- **Rate Limiting**: Configured sesuai kebutuhan

#### Performance Settings
- **Auto Minify**: JavaScript, CSS, HTML
- **Brotli Compression**: Enabled
- **Rocket Loader**: Optional (test compatibility)

#### Page Rules (Recommended)
1. **Static Assets**: `yourdomain.com/build/*`
   - Cache Level: Cache Everything
   - Edge Cache TTL: 1 year
   - Browser Cache TTL: 1 year

2. **API Routes**: `yourdomain.com/api/*`
   - Cache Level: Bypass
   - Security Level: High

3. **Admin Panel**: `yourdomain.com/admin/*`
   - Cache Level: Bypass
   - Security Level: High
   - Always Use HTTPS: On

## 🔧 Header Konfigurasi

### Headers yang Dikirim ke Client
```
Content-Security-Policy: Optimized for Cloudflare CDN
Strict-Transport-Security: max-age=31536000; includeSubDomains; preload
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: camera=(), microphone=(), geolocation=()
```

### Headers dari Cloudflare yang Diproses
```
CF-Connecting-IP: Real client IP address
CF-RAY: Unique request identifier
CF-IPCountry: Client country code
CF-Visitor: Original protocol information
CF-Cache-Status: Cache status information
```

## 🔍 Monitoring dan Debugging

### Mengecek Status Cloudflare
```php
// Dalam controller atau middleware
if (request()->hasHeader('CF-RAY')) {
    $ray = request()->header('CF-RAY');
    $country = request()->header('CF-IPCountry');
    $realIP = request()->header('CF-Connecting-IP');
    
    logger()->info('Cloudflare Request', [
        'ray' => $ray,
        'country' => $country,
        'ip' => $realIP
    ]);
}
```

### Testing Headers
```bash
# Test security headers
curl -I https://yourdomain.com

# Test dengan CF headers (simulasi)
curl -H "CF-Connecting-IP: 1.2.3.4" -H "CF-RAY: test123" https://yourdomain.com
```

## ⚠️ Troubleshooting

### Masalah Umum dan Solusi

1. **IP Address tidak terdeteksi dengan benar**
   - Pastikan trusted proxies dikonfigurasi dengan benar
   - Periksa header CF-Connecting-IP

2. **Session tidak persistent**
   - Periksa SESSION_DOMAIN di .env
   - Pastikan cookies menggunakan HTTPS

3. **CSP errors**
   - Tambahkan domain yang diperlukan ke CSP
   - Gunakan Cloudflare Analytics untuk memonitor violations

4. **Cache issues**
   - Gunakan Cloudflare Purge Cache
   - Periksa Cache-Control headers

## 📊 Performance Monitoring

### Metrics yang Dipantau
- **Response Time**: Melalui Cloudflare Analytics
- **Cache Hit Rate**: Target > 90% untuk static assets
- **Security Events**: WAF blocks, DDoS mitigations
- **Error Rate**: 4xx/5xx responses

### Cloudflare Analytics Integration
- Ray ID logging untuk request tracking
- Country-based analytics
- Performance insights

## 🔄 Maintenance

### Update IP Ranges Cloudflare
IP ranges Cloudflare dapat berubah. Update berkala:
```bash
# Download latest IP ranges
curl https://www.cloudflare.com/ips-v4 > cloudflare-ips-v4.txt
curl https://www.cloudflare.com/ips-v6 > cloudflare-ips-v6.txt

# Update config/trustedproxy.php dengan IP ranges terbaru
```

### Cache Invalidation
```bash
# Clear Laravel caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Purge Cloudflare cache via API
curl -X POST "https://api.cloudflare.com/client/v4/zones/{zone_id}/purge_cache" \
     -H "Authorization: Bearer {api_token}" \
     -H "Content-Type: application/json" \
     --data '{"purge_everything":true}'
```

## 📚 Resources

- [Cloudflare IP Ranges](https://www.cloudflare.com/ips/)
- [Cloudflare SSL/TLS Documentation](https://developers.cloudflare.com/ssl/)
- [Laravel Trusted Proxies](https://laravel.com/docs/requests#configuring-trusted-proxies)
- [Content Security Policy Guide](https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP)

---

**Catatan**: Konfigurasi ini dioptimalkan untuk lingkungan production. Untuk development, gunakan konfigurasi standar Laravel tanpa middleware Cloudflare.
