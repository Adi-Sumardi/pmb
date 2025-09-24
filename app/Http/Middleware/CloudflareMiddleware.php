<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CloudflareMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Trust Cloudflare proxy
        $this->setTrustedProxies($request);

        // Handle Cloudflare-specific headers
        $this->handleCloudflareHeaders($request);

        $response = $next($request);

        // Add Cloudflare-compatible headers
        $this->addCloudflareHeaders($response);

        return $response;
    }

    /**
     * Set trusted proxies for Cloudflare - SECURITY FIX: Dynamic IP ranges
     */
    private function setTrustedProxies(Request $request): void
    {
        // SECURITY FIX: Get Cloudflare IPs from cache or fetch dynamically
        $cloudflareIps = $this->getCloudflareIpRanges();

        // SECURITY FIX: Additional validation before trusting
        if ($this->isFromCloudflare($request) && $this->validateCloudflareRequest($request)) {
            $request->setTrustedProxies($cloudflareIps, Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO);
        }
    }

    /**
     * Get Cloudflare IP ranges with caching and fallback
     */
    private function getCloudflareIpRanges(): array
    {
        // Try to get from cache first
        $cacheKey = 'cloudflare_ip_ranges';
        $cachedIps = cache()->get($cacheKey);

        if ($cachedIps) {
            return $cachedIps;
        }

        // Try to fetch from Cloudflare API
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get('https://www.cloudflare.com/ips-v4');
            $ipv4Ranges = [];

            if ($response->successful()) {
                $ipv4Ranges = array_filter(explode("\n", trim($response->body())));
            }

            $response = \Illuminate\Support\Facades\Http::timeout(5)->get('https://www.cloudflare.com/ips-v6');
            $ipv6Ranges = [];

            if ($response->successful()) {
                $ipv6Ranges = array_filter(explode("\n", trim($response->body())));
            }

            $allRanges = array_merge($ipv4Ranges, $ipv6Ranges);

            if (!empty($allRanges)) {
                // Cache for 24 hours
                cache()->put($cacheKey, $allRanges, now()->addHours(24));
                return $allRanges;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to fetch Cloudflare IP ranges', [
                'error' => $e->getMessage()
            ]);
        }

        // Fallback to hardcoded ranges (updated as of 2024)
        $fallbackRanges = [
            // IPv4 ranges
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
            // IPv6 ranges
            '2400:cb00::/32',
            '2606:4700::/32',
            '2803:f800::/32',
            '2405:b500::/32',
            '2405:8100::/32',
            '2a06:98c0::/29',
            '2c0f:f248::/32',
        ];

        // Cache fallback for 1 hour
        cache()->put($cacheKey, $fallbackRanges, now()->addHour());

        return $fallbackRanges;
    }

    /**
     * Enhanced Cloudflare request validation
     */
    private function validateCloudflareRequest(Request $request): bool
    {
        // Check for required Cloudflare headers
        $requiredHeaders = ['CF-RAY', 'CF-Connecting-IP'];
        foreach ($requiredHeaders as $header) {
            if (!$request->hasHeader($header)) {
                return false;
            }
        }

        // Validate CF-RAY format (should be hex-datacenter)
        $cfRay = $request->header('CF-RAY');
        if (!preg_match('/^[a-f0-9]+-[A-Z]{3}$/', $cfRay)) {
            \Illuminate\Support\Facades\Log::warning('Invalid CF-RAY format', [
                'cf_ray' => $cfRay,
                'ip' => $request->ip()
            ]);
            return false;
        }

        // Validate CF-Connecting-IP format
        $cfConnectingIp = $request->header('CF-Connecting-IP');
        if (!filter_var($cfConnectingIp, FILTER_VALIDATE_IP)) {
            \Illuminate\Support\Facades\Log::warning('Invalid CF-Connecting-IP', [
                'cf_connecting_ip' => $cfConnectingIp,
                'ip' => $request->ip()
            ]);
            return false;
        }

        return true;
    }

    /**
     * Check if request is from Cloudflare
     */
    private function isFromCloudflare(Request $request): bool
    {
        return $request->hasHeader('CF-RAY') ||
               $request->hasHeader('CF-Connecting-IP') ||
               $request->hasHeader('CF-IPCountry');
    }

    /**
     * Handle Cloudflare-specific headers
     */
    private function handleCloudflareHeaders(Request $request): void
    {
        // Get real IP from Cloudflare
        if ($request->hasHeader('CF-Connecting-IP')) {
            $request->server->set('REMOTE_ADDR', $request->header('CF-Connecting-IP'));
        }

        // Store Cloudflare country code
        if ($request->hasHeader('CF-IPCountry')) {
            $request->attributes->set('cloudflare_country', $request->header('CF-IPCountry'));
        }

        // Store Cloudflare Ray ID for debugging
        if ($request->hasHeader('CF-RAY')) {
            $request->attributes->set('cloudflare_ray', $request->header('CF-RAY'));
        }
    }

    /**
     * Add headers that work well with Cloudflare
     */
    private function addCloudflareHeaders(Response $response): void
    {
        // Cache control for static assets
        if ($this->isStaticAsset()) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        }

        // Add CF-Cache-Status aware headers
        $response->headers->set('X-Cloudflare-Compatible', 'true');
    }

    /**
     * Check if the current request is for a static asset
     */
    private function isStaticAsset(): bool
    {
        $path = request()->path();
        return preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/', $path);
    }
}
