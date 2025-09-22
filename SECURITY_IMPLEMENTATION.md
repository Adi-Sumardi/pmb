# Security Implementation Documentation

## Implementasi Keamanan PPDB Backend

Dokumentasi ini menjelaskan implementasi komprehensif sistem keamanan untuk aplikasi PPDB (Penerimaan Peserta Didik Baru).

## Overview

Sistem keamanan ini melindungi aplikasi dari berbagai jenis serangan cyber termasuk:
- Cross-Site Scripting (XSS)
- SQL Injection
- Directory Traversal
- File Upload Attacks
- Rate Limiting Attacks
- Unicode Attacks

## Komponen Keamanan

### 1. SecurityValidationService (`app/Services/SecurityValidationService.php`)

Service utama untuk validasi dan sanitasi input:

#### Metode Utama:
- `sanitizeInput(string $input)`: Membersihkan input dari pola berbahaya
- `validateFileUpload($file)`: Validasi upload file dengan whitelist extension
- `validateSqlParameters(array $params)`: Deteksi SQL injection patterns
- `checkRateLimit(string $key)`: Rate limiting per IP/user
- `validateUserPermissions()`: Validasi permission user

#### Pola Berbahaya yang Diblokir:
```php
// XSS Patterns
- <script> tags
- javascript: URLs
- onload/onerror handlers
- alert() functions

// SQL Injection Patterns
- UNION SELECT
- DROP TABLE
- OR/AND conditions
- SQL comments (-- dan /**/)

// Directory Traversal
- ../ patterns
- /etc/ paths
- URL encoded traversal

// Dangerous Functions
- eval(), exec(), system()
- file_get_contents()
- base64_decode()
```

### 2. ComprehensiveSecurityMiddleware (`app/Http/Middleware/ComprehensiveSecurityMiddleware.php`)

Middleware global yang memproses setiap request:

#### Fitur:
- Rate limiting per IP
- XSS detection dan blocking
- SQL injection detection
- Suspicious activity monitoring
- CSRF validation enhancement
- Security headers injection

### 3. Secure Request Classes

#### SecureStudentDataRequest (`app/Http/Requests/SecureStudentDataRequest.php`)
- Validasi komprehensif data siswa
- Regex patterns untuk format data
- Input sanitization otomatis

#### SecureParentDataRequest (`app/Http/Requests/SecureParentDataRequest.php`)
- Validasi data orang tua/wali
- Validasi NIK, nomor telepon
- Format validation untuk semua field

### 4. Security Helpers (`app/Helpers/SecurityHelpers.php`)

Helper functions untuk output yang aman:
- `safe_json($data)`: JSON encoding yang aman dari XSS
- `sanitize_output($content)`: Sanitasi output HTML
- `security_headers()`: Set security headers

### 5. Configuration (`config/security.php`)

Konfigurasi terpusat untuk semua aspek keamanan:
```php
'xss_protection' => true,
'rate_limiting' => [
    'requests_per_minute' => 60,
    'max_attempts_per_hour' => 100
],
'file_upload' => [
    'max_size' => 2048, // KB
    'allowed_extensions' => ['pdf', 'jpg', 'jpeg', 'png']
]
```

## Implementasi di Controller

### DataController Update

Controller telah diupdate untuk menggunakan secure request validation:

```php
public function storeStudent(SecureStudentDataRequest $request)
{
    // Validasi otomatis melalui SecureStudentDataRequest
    $validatedData = $request->validated();
    
    // Data sudah tersanitasi secara otomatis
    // Simpan ke database...
}
```

## Testing

### Security Test Suite (`tests/Feature/SecurityProtectionTest.php`)

Test komprehensif untuk semua aspek keamanan:

#### Test Coverage:
- ✅ XSS Attack Prevention
- ✅ SQL Injection Detection
- ✅ File Upload Security
- ✅ Rate Limiting
- ✅ Directory Traversal Prevention
- ✅ Unicode Attack Handling
- ✅ Middleware Security Blocks
- ✅ Helper Function Safety

### Menjalankan Security Tests

```bash
php artisan test tests/Feature/SecurityProtectionTest.php
```

## Aktivasi Sistem Keamanan

Sistem keamanan diaktifkan secara otomatis melalui:

1. **Bootstrap Application** (`bootstrap/app.php`)
   - Middleware teregistrasi secara global

2. **Composer Autoload** (`composer.json`)
   - Security helpers auto-loaded

3. **Service Registration**
   - SecurityValidationService tersedia di seluruh aplikasi

## Best Practices

### Input Validation
```php
// ✅ Gunakan secure request classes
public function store(SecureStudentDataRequest $request)

// ✅ Manual sanitization jika diperlukan
$clean = SecurityValidationService::sanitizeInput($input);
```

### Output Rendering
```php
// ✅ Gunakan helper untuk JSON
echo safe_json($data);

// ✅ Sanitasi output HTML
echo sanitize_output($content);

// ❌ Hindari raw output
echo $userInput; // Berbahaya!
```

### File Upload
```php
// ✅ Validasi melalui service
$result = SecurityValidationService::validateFileUpload($file);
if (!$result['valid']) {
    // Handle error
}
```

## Monitoring dan Logging

Sistem mencatat aktivitas mencurigakan:
- Attemp XSS attacks
- SQL injection attempts
- Rate limit violations
- File upload violations

Log tersimpan di: `storage/logs/security.log`

## Performance Impact

Implementasi keamanan dirancang dengan performa minimal impact:
- Regex patterns dioptimasi
- Caching untuk rate limiting
- Lazy loading untuk security services

## Maintenance

### Update Security Patterns

Untuk menambah pola deteksi baru, edit `SecurityValidationService`:

```php
$dangerousPatterns = [
    // Tambah pattern baru di sini
    '/new_attack_pattern/i',
];
```

### Monitoring Rate Limits

Check rate limiting status:
```php
$isLimited = SecurityValidationService::checkRateLimit($ip);
```

## Compliance

Implementasi ini memenuhi standar:
- OWASP Top 10 Security Risks
- PHP Security Best Practices
- Laravel Security Guidelines

## Support

Untuk pertanyaan atau update keamanan, silakan buat issue atau pull request di repository ini.

---

**⚠️ PENTING**: Sistem keamanan ini harus selalu diperbarui mengikuti perkembangan threat landscape dan vulnerability baru.
