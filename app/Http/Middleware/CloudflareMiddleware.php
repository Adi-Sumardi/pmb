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
     * Set trusted proxies for Cloudflare
     */
    private function setTrustedProxies(Request $request): void
    {
        // Cloudflare IP ranges - these should be updated periodically
        $cloudflareIps = [
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

        // Set trusted proxies if request is from Cloudflare
        if ($this->isFromCloudflare($request)) {
            $request->setTrustedProxies($cloudflareIps, Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO);
        }
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