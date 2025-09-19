<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CORS Configuration for Cloudflare
    |--------------------------------------------------------------------------
    |
    | Cross-Origin Resource Sharing (CORS) configuration optimized for
    | applications deployed behind Cloudflare proxy.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        // Add your domain patterns here
        // 'https://yourdomain.com',
        // 'https://*.yourdomain.com',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN',
        // Cloudflare headers
        'CF-Ray',
        'CF-Connecting-IP',
        'CF-IPCountry',
        'CF-Visitor',
    ],

    'exposed_headers' => [
        'X-Rate-Limit-Remaining',
        'X-Rate-Limit-Retry-After',
        // Cloudflare debugging headers
        'CF-Ray',
        'CF-Cache-Status',
    ],

    'max_age' => 0,

    'supports_credentials' => true,

];