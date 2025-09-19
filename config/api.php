<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for API security and rate limiting
    |
    */

    'max_request_size' => env('API_MAX_REQUEST_SIZE', 10 * 1024 * 1024), // 10MB
    'max_requests_per_minute' => env('API_MAX_REQUESTS_PER_MINUTE', 60),
    'timeout' => env('API_TIMEOUT', 30), // seconds

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Rate limiting configuration for different API endpoints
    |
    */

    'rate_limits' => [
        'default' => [
            'attempts' => 60,
            'decay_minutes' => 1
        ],
        'auth' => [
            'attempts' => 5,
            'decay_minutes' => 1
        ],
        'file_upload' => [
            'attempts' => 10,
            'decay_minutes' => 1
        ],
        'payment' => [
            'attempts' => 3,
            'decay_minutes' => 1
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    |
    | Various security settings for API endpoints
    |
    */

    'security' => [
        'require_https' => env('API_REQUIRE_HTTPS', true),
        'allowed_origins' => explode(',', env('API_ALLOWED_ORIGINS', '*')),
        'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
        'allowed_headers' => [
            'Accept',
            'Authorization',
            'Content-Type',
            'X-Requested-With',
            'X-CSRF-TOKEN'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for file uploads via API
    |
    */

    'uploads' => [
        'max_file_size' => env('API_MAX_FILE_SIZE', 5 * 1024 * 1024), // 5MB
        'allowed_mimes' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ],
        'scan_for_malware' => env('API_SCAN_UPLOADS', true)
    ]
];
