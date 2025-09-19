<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxies
    |--------------------------------------------------------------------------
    |
    | Set trusted proxy IP addresses for applications behind Cloudflare.
    | These are Cloudflare's current IP ranges that should be updated
    | periodically from https://www.cloudflare.com/ips/
    |
    */

    'proxies' => [
        // Cloudflare IPv4 ranges
        '103.21.244.0/22',
        '103.22.200.0/22',
        '103.31.4.0/22',
        '104.16.0.0/13',
        '104.24.0.0/14',
        '108.162.192.0/18',
        '131.0.72.0/22',
        '141.101.64.0/18',
        '162.158.0.0/15',
        '172.64.0.0/13',
        '173.245.48.0/20',
        '188.114.96.0/20',
        '190.93.240.0/20',
        '197.234.240.0/22',
        '198.41.128.0/17',

        // Cloudflare IPv6 ranges
        '2400:cb00::/32',
        '2606:4700::/32',
        '2803:f800::/32',
        '2405:b500::/32',
        '2405:8100::/32',
        '2a06:98c0::/29',
        '2c0f:f248::/32',
    ],

    /*
    |--------------------------------------------------------------------------
    | Trusted Headers
    |--------------------------------------------------------------------------
    |
    | Headers that should be trusted when determining client information.
    | These headers are set by Cloudflare and contain the real client data.
    |
    */

    'headers' => [
        \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR,
        \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST,
        \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT,
        \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cloudflare Headers
    |--------------------------------------------------------------------------
    |
    | Custom headers provided by Cloudflare that contain useful information
    | about the client and the request.
    |
    */

    'cloudflare_headers' => [
        'CF-Connecting-IP',      // Real client IP
        'CF-RAY',               // Cloudflare request ID
        'CF-IPCountry',         // Client country code
        'CF-Visitor',           // Original protocol (HTTP/HTTPS)
        'CF-Cache-Status',      // Cache status
    ],

];
