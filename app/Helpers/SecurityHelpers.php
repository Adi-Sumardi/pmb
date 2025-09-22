<?php

if (!function_exists('safe_json')) {
    /**
     * Safely encode data to JSON for use in JavaScript
     * This prevents XSS attacks by properly escaping HTML entities
     */
    function safe_json($data, $options = 0): string
    {
        // Encode the data with proper escaping
        $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Unable to encode data to JSON: ' . json_last_error_msg());
        }

        return $json;
    }
}

if (!function_exists('sanitize_output')) {
    /**
     * Sanitize output for safe display in HTML
     */
    function sanitize_output($value): string
    {
        if (is_null($value)) {
            return '';
        }

        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('secure_url')) {
    /**
     * Generate a secure URL with proper escaping
     */
    function secure_url($path, $parameters = []): string
    {
        $url = url($path, $parameters);
        return filter_var($url, FILTER_SANITIZE_URL) ?: '';
    }
}

if (!function_exists('csrf_meta_tags')) {
    /**
     * Generate CSRF meta tags for JavaScript usage
     */
    function csrf_meta_tags(): string
    {
        return '<meta name="csrf-token" content="' . csrf_token() . '">';
    }
}

if (!function_exists('security_headers')) {
    /**
     * Generate security-related meta tags
     */
    function security_headers(): string
    {
        $nonce = base64_encode(random_bytes(16));

        return implode("\n", [
            '<meta name="csrf-token" content="' . csrf_token() . '">',
            '<meta name="security-nonce" content="' . $nonce . '">',
            '<meta name="viewport" content="width=device-width, initial-scale=1">',
            '<meta http-equiv="X-Content-Type-Options" content="nosniff">',
            '<meta http-equiv="X-Frame-Options" content="DENY">',
            '<meta http-equiv="X-XSS-Protection" content="1; mode=block">',
        ]);
    }
}
