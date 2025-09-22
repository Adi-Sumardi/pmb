<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security settings for the PPDB application.
    | These settings help protect against various security threats.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Input Validation & Sanitization
    |--------------------------------------------------------------------------
    */
    'input_validation' => [
        'enable_sanitization' => env('SECURITY_INPUT_SANITIZATION', true),
        'max_input_length' => env('SECURITY_MAX_INPUT_LENGTH', 10000),
        'allowed_html_tags' => [],
        'strip_tags' => true,
        'escape_html' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limiting' => [
        'enabled' => env('SECURITY_RATE_LIMITING', true),
        'requests_per_minute' => env('SECURITY_REQUESTS_PER_MINUTE', 100),
        'login_attempts' => env('SECURITY_LOGIN_ATTEMPTS', 5),
        'login_lockout_minutes' => env('SECURITY_LOGIN_LOCKOUT', 15),
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    */
    'file_upload' => [
        'max_size' => env('SECURITY_MAX_FILE_SIZE', 2048), // KB
        'allowed_extensions' => ['pdf', 'jpg', 'jpeg', 'png'],
        'allowed_mime_types' => [
            'application/pdf',
            'image/jpeg',
            'image/jpg',
            'image/png'
        ],
        'validate_content' => true,
        'scan_for_malware' => true,
        'quarantine_suspicious' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | XSS Protection
    |--------------------------------------------------------------------------
    */
    'xss_protection' => [
        'enabled' => env('SECURITY_XSS_PROTECTION', true),
        'strip_dangerous_tags' => true,
        'escape_output' => true,
        'content_security_policy' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | SQL Injection Protection
    |--------------------------------------------------------------------------
    */
    'sql_injection' => [
        'enabled' => env('SECURITY_SQL_INJECTION_PROTECTION', true),
        'block_suspicious_queries' => true,
        'log_attempts' => true,
        'auto_block_ip' => false, // Set to true in production
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    */
    'session' => [
        'timeout_minutes' => env('SESSION_TIMEOUT', 120),
        'regenerate_on_login' => true,
        'secure_cookies' => env('SESSION_SECURE_COOKIES', false),
        'same_site' => 'lax',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Security
    |--------------------------------------------------------------------------
    */
    'authentication' => [
        'password_min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => false,
        'prevent_password_reuse' => 5,
        'two_factor_enabled' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | CSRF Protection
    |--------------------------------------------------------------------------
    */
    'csrf' => [
        'enabled' => true,
        'exclude_routes' => [
            'api/*',
            'webhook/*',
            'payments/webhook',
        ],
        'regenerate_token' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    */
    'headers' => [
        'x_frame_options' => 'DENY',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'strict_transport_security' => 'max-age=31536000; includeSubDomains; preload',
        'content_security_policy' => [
            'enabled' => true,
            'default_src' => "'self'",
            'script_src' => "'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            'style_src' => "'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com",
            'font_src' => "'self' https://fonts.gstatic.com",
            'img_src' => "'self' data: https:",
            'connect_src' => "'self'",
            'frame_ancestors' => "'none'",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring & Logging
    |--------------------------------------------------------------------------
    */
    'monitoring' => [
        'log_security_events' => true,
        'alert_on_attacks' => true,
        'track_failed_logins' => true,
        'track_suspicious_activity' => true,
        'retention_days' => 90,
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Security
    |--------------------------------------------------------------------------
    */
    'ip_security' => [
        'whitelist_enabled' => false,
        'whitelist' => [],
        'blacklist_enabled' => true,
        'blacklist' => [],
        'auto_block_enabled' => false,
        'auto_block_threshold' => 50, // violations per hour
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Security
    |--------------------------------------------------------------------------
    */
    'database' => [
        'encrypt_sensitive_data' => true,
        'use_prepared_statements' => true,
        'validate_queries' => true,
        'log_slow_queries' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | API Security
    |--------------------------------------------------------------------------
    */
    'api' => [
        'rate_limit_per_minute' => 60,
        'require_authentication' => true,
        'validate_content_type' => true,
        'max_request_size' => '10M',
    ],

    /*
    |--------------------------------------------------------------------------
    | Emergency Response
    |--------------------------------------------------------------------------
    */
    'emergency' => [
        'lockdown_enabled' => false,
        'maintenance_mode_on_attack' => false,
        'emergency_contacts' => [
            // 'admin@example.com'
        ],
        'auto_backup_on_attack' => false,
    ],
];
