<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SecurityValidationService
{
    /**
     * Sanitize user input to prevent XSS attacks
     */
    public static function sanitizeInput(string $input): string
    {
        // Remove null bytes
        $input = str_replace(chr(0), '', $input);

        // Decode HTML entities and Unicode sequences
        $input = html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $input = html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8'); // Double decode

        // Handle Unicode escapes
        $input = preg_replace('/\\\\u([0-9a-fA-F]{4})/', '&#x$1;', $input);
        $input = html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove dangerous patterns
        $dangerousPatterns = [
            // Script tags
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/data:/i',
            '/on\w+\s*=/i',
            '/alert\s*\(/i',

            // SQL injection patterns
            '/(\bOR\b|\bAND\b)\s*\d+\s*=\s*\d+/i',
            '/UNION\s+SELECT/i',
            '/DROP\s+TABLE/i',
            '/DELETE\s+FROM/i',
            '/INSERT\s+INTO/i',
            '/UPDATE\s+SET/i',
            '/--/',
            '/\/\*.*?\*\//',

            // Directory traversal
            '/\.\.\//',
            '/\.\.\\\\/',
            '/%2e%2e%2f/i',
            '/%2e%2e%5c/i',
            '/\.{2,}/',
            '/\/etc\//i',

            // Dangerous functions
            '/\beval\s*\(/i',
            '/\bexec\s*\(/i',
            '/\bsystem\s*\(/i',
            '/\bshell_exec\s*\(/i',
            '/\bpassthru\s*\(/i',
            '/\bproc_open\s*\(/i',
            '/\bfile_get_contents\s*\(/i',
            '/\bbase64_decode\s*\(/i',
        ];        foreach ($dangerousPatterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }

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
