<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Content Security Policy - Cloudflare optimized
        $csp = $this->buildCloudflareOptimizedCSP($request);
        $response->headers->set('Content-Security-Policy', $csp);

        // Strict Transport Security
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // X-Frame-Options
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy
        $permissionsPolicy = 'camera=(), microphone=(), geolocation=(), interest-cohort=()';
        $response->headers->set('Permissions-Policy', $permissionsPolicy);

        // Remove server information
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');

        return $response;
    }

    /**
     * Build Cloudflare-optimized Content Security Policy
     */
    private function buildCloudflareOptimizedCSP(Request $request): string
    {
        $isProduction = app()->environment('production');

        // Base CSP directives
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net",
            "img-src 'self' data: blob: https:",
            "connect-src 'self' https://api.xendit.co https://cdn.jsdelivr.net",
            "frame-src 'none'",
            "object-src 'none'",
            "base-uri 'self'"
        ];

        // Add Cloudflare-specific domains if behind Cloudflare
        if ($this->isBehindCloudflare($request)) {
            // Allow Cloudflare's own scripts for security features
            $csp[1] .= " https://static.cloudflareinsights.com";
            // Allow Cloudflare analytics and monitoring
            $csp[4] .= " https://cloudflareinsights.com";
        }

        // Production-specific CSP
        if ($isProduction) {
            // Remove unsafe-eval in production for better security
            $csp[1] = str_replace(" 'unsafe-eval'", "", $csp[1]);
            // Add report-uri for CSP violations (optional)
            // $csp[] = "report-uri /csp-report";
        }

        return implode('; ', $csp);
    }

    /**
     * Check if the request is behind Cloudflare
     */
    private function isBehindCloudflare(Request $request): bool
    {
        return $request->hasHeader('CF-RAY') ||
               $request->hasHeader('CF-Connecting-IP') ||
               $request->hasHeader('CF-IPCountry');
    }
}
