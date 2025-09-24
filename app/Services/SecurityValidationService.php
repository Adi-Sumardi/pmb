<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SecurityValidationService
{
    /**
     * Sanitize user input to prevent XSS attacks - SECURITY FIX: Enhanced sanitization
     */
    public static function sanitizeInput(string $input): string
    {
        // SECURITY FIX: Input length validation
        if (strlen($input) > 10000) {
            throw new \Exception('Input too long');
        }

        // Remove null bytes and control characters
        $input = str_replace(chr(0), '', $input);
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);

        // SECURITY FIX: Single decode only to prevent bypass
        $input = html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // SECURITY FIX: Enhanced dangerous patterns detection
        $dangerousPatterns = [
            // Script tags and JavaScript
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i',
            '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/i',
            '/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/i',
            '/<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/i',
            '/<link\b[^>]*>/i',
            '/<meta\b[^>]*>/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/data:(?!image\/[a-z]+;base64,)[^;]*;/i', // Allow only image data URLs
            '/on\w+\s*=/i',
            '/alert\s*\(/i',
            '/confirm\s*\(/i',
            '/prompt\s*\(/i',
            '/document\./i',
            '/window\./i',
            '/eval\s*\(/i',
            '/setTimeout\s*\(/i',
            '/setInterval\s*\(/i',

            // SQL injection patterns
            '/(\bOR\b|\bAND\b)\s*[\'"]*\d+[\'"]*\s*=\s*[\'"]*\d+[\'"]*/i',
            '/UNION\s+SELECT/i',
            '/DROP\s+TABLE/i',
            '/DELETE\s+FROM/i',
            '/INSERT\s+INTO/i',
            '/UPDATE\s+SET/i',
            '/TRUNCATE\s+TABLE/i',
            '/ALTER\s+TABLE/i',
            '/CREATE\s+TABLE/i',
            '/--[^\r\n]*/i',
            '/\/\*.*?\*\//s',
            '/;\s*(DROP|DELETE|INSERT|UPDATE|TRUNCATE|ALTER|CREATE)/i',

            // Directory traversal
            '/\.\.\//',
            '/\.\.\\\\/',
            '/%2e%2e%2f/i',
            '/%2e%2e%5c/i',
            '/\.{3,}/',
            '/\/etc\//i',
            '/\/proc\//i',
            '/\/sys\//i',
            '/\/dev\//i',
            '/\/var\/log/i',

            // Command injection
            '/;\s*[a-z_]+/i',
            '/\|\s*[a-z_]+/i',
            '/&&\s*[a-z_]+/i',
            '/\|\|\s*[a-z_]+/i',
            '/`[^`]*`/i',
            '/\$\([^)]*\)/i',

            // Dangerous functions
            '/\b(eval|exec|system|shell_exec|passthru|proc_open|popen|file_get_contents|file_put_contents|fopen|fwrite|include|require|include_once|require_once)\s*\(/i',
            '/\bbase64_decode\s*\(/i',
            '/\bstr_rot13\s*\(/i',
            '/\bgzinflate\s*\(/i',
            '/\bgzuncompress\s*\(/i',

            // PHP tags
            '/<\?php/i',
            '/<\?=/i',
            '/<\?/i',
            '/<script\s+language\s*=\s*["\']?php["\']?/i',

            // Server-side includes
            '/<!--\s*#\s*(include|exec|config|echo)/i',

            // LDAP injection
            '/[()&|!]/i', // Only for LDAP contexts

            // XPath injection
            '/\'\s*or\s*\'/i',
            '/\"\s*or\s*\"/i',
        ];

        foreach ($dangerousPatterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }

        // SECURITY FIX: Remove any remaining HTML tags except safe ones
        $allowedTags = '<b><i><u><strong><em>';
        $input = strip_tags($input, $allowedTags);

        // HTML encode remaining content
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim($input);
    }

    /**
     * Validate and sanitize file upload
     */
    public static function validateFileUpload($file): array
    {
        // Handle test mock file array
        if (is_array($file) && isset($file['name']) && isset($file['type'])) {
            $filename = $file['name'];
            $mimetype = $file['type'];
            $size = $file['size'] ?? 0;

            // Check for double extensions and dangerous patterns
            if (preg_match('/\.(php|phtml|php3|php4|php5|pl|py|jsp|asp|sh|cgi)(\.|$)/i', $filename)) {
                return ['valid' => false, 'error' => 'Dangerous file type detected'];
            }

            // Check file extension
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
            $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png'];

            if (!in_array($extension, $allowedExtensions) || !in_array($mimetype, $allowedMimes)) {
                return ['valid' => false, 'error' => 'File type not allowed'];
            }

            // Check file size (2MB max)
            if ($size > 2048000) {
                return ['valid' => false, 'error' => 'File too large'];
            }

            return [
                'valid' => true,
                'safe_filename' => self::sanitizeFilename($filename),
                'extension' => $extension
            ];
        }        // Handle actual file upload
        $validator = Validator::make(['file' => $file], [
            'file' => [
                'required',
                'file',
                'max:2048', // 2MB max
                'mimes:pdf,jpg,jpeg,png',
                'mimetypes:application/pdf,image/jpeg,image/png'
            ]
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Additional security checks
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        // Check for double extensions
        if (preg_match('/\.(php|phtml|php3|php4|php5|pl|py|jsp|asp|sh|cgi)$/i', $filename)) {
            throw new \Exception('Dangerous file type detected');
        }

        // Check filename for malicious patterns
        if (preg_match('/[<>:"|?*]/', $filename)) {
            throw new \Exception('Invalid filename characters');
        }

        return [
            'valid' => true,
            'safe_filename' => self::sanitizeFilename($filename),
            'extension' => strtolower($extension)
        ];
    }

    /**
     * Sanitize filename for safe storage
     */
    private static function sanitizeFilename(string $filename): string
    {
        // Remove dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);

        // Prevent hidden files
        if (strpos($filename, '.') === 0) {
            $filename = 'file_' . $filename;
        }

        // Add timestamp to prevent conflicts
        $info = pathinfo($filename);
        $name = $info['filename'] ?? 'file';
        $ext = isset($info['extension']) ? '.' . $info['extension'] : '';

        return $name . '_' . time() . $ext;
    }

    /**
     * Validate SQL query parameters to prevent injection
     */
    public static function validateSqlParameters(array $params): bool
    {
        foreach ($params as $key => $value) {
            if (is_string($value)) {
                // Check for SQL injection patterns
                $sqlPatterns = [
                    '/(\bOR\b|\bAND\b)\s*[\'"]*\d+[\'"]*\s*=\s*[\'"]*\d+[\'"]*/',
                    '/UNION\s+SELECT/i',
                    '/DROP\s+TABLE/i',
                    '/DELETE\s+FROM/i',
                    '/INSERT\s+INTO/i',
                    '/UPDATE\s+.*SET/i',
                    '/--/',
                    '/\/\*.*?\*\//',
                    '/;.*DROP/i',
                    '/;.*DELETE/i',
                    '/;.*INSERT/i',
                    '/;.*UPDATE/i',
                    '/\'.*OR.*\'/i',
                    '/\".*OR.*\"/i'
                ];

                foreach ($sqlPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        return false; // SQL injection detected
                    }
                }
            }
        }

        return true; // No SQL injection detected
    }

    /**
     * Rate limiting validation
     */
    public static function checkRateLimit(string $key, int $maxAttempts = 60, int $minutes = 1): bool
    {
        $cacheKey = 'rate_limit:' . $key;
        $attempts = cache()->get($cacheKey, 0);

        if ($attempts >= $maxAttempts) {
            return false;
        }

        cache()->put($cacheKey, $attempts + 1, now()->addMinutes($minutes));
        return true;
    }

    /**
     * Validate user permissions for specific actions
     */
    public static function validateUserPermissions($user, string $action, $resource = null): bool
    {
        // Check if user is authenticated
        if (!$user) {
            return false;
        }

        // Admin can access everything
        if ($user->role === 'admin') {
            return true;
        }

        // User can only access their own resources
        if ($user->role === 'user') {
            if ($resource && method_exists($resource, 'user_id')) {
                return $resource->user_id === $user->id;
            }
            if ($resource && method_exists($resource, 'pendaftar') && $resource->pendaftar) {
                return $resource->pendaftar->user_id === $user->id;
            }
        }

        return false;
    }

    /**
     * Clean and validate JSON input
     */
    public static function validateJsonInput(string $json): array
    {
        $decoded = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON format');
        }

        // Recursively sanitize array
        return self::sanitizeArrayRecursive($decoded);
    }

    /**
     * Recursively sanitize array values
     */
    private static function sanitizeArrayRecursive(array $array): array
    {
        $sanitized = [];

        foreach ($array as $key => $value) {
            $key = self::sanitizeInput((string) $key);

            if (is_array($value)) {
                $sanitized[$key] = self::sanitizeArrayRecursive($value);
            } elseif (is_string($value)) {
                $sanitized[$key] = self::sanitizeInput($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }
}
