<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecureWebhookMiddleware
{
    /**
     * Handle an incoming webhook request with enhanced security
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if this is a webhook request
        if (!$this->isWebhookRequest($request)) {
            return response()->json(['error' => 'Invalid webhook request'], 400);
        }

        // 2. Rate limiting per IP
        if (!$this->checkRateLimit($request)) {
            Log::warning('Webhook rate limit exceeded', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return response()->json(['error' => 'Rate limit exceeded'], 429);
        }

        // 3. IP Whitelist (Xendit IPs)
        if (!$this->isFromTrustedSource($request)) {
            Log::warning('Webhook from untrusted IP', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'headers' => $request->headers->all()
            ]);
            
            // In production, you might want to block this
            // For development/testing, we'll allow but log
            if (app()->environment('production')) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        }

        // 4. Verify webhook signature (more robust than CSRF)
        if (!$this->verifyWebhookSignature($request)) {
            Log::warning('Invalid webhook signature', [
                'ip' => $request->ip(),
                'received_token' => $request->header('x-callback-token'),
                'body_hash' => hash('sha256', $request->getContent())
            ]);
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // 5. Validate payload structure
        if (!$this->validatePayloadStructure($request)) {
            Log::warning('Invalid webhook payload structure', [
                'data' => $request->all()
            ]);
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        // 6. Prevent replay attacks
        if (!$this->checkReplayProtection($request)) {
            Log::warning('Potential replay attack detected', [
                'timestamp' => $request->header('x-timestamp'),
                'ip' => $request->ip()
            ]);
            return response()->json(['error' => 'Request too old'], 400);
        }

        // 7. Log successful webhook validation
        Log::info('Webhook security validation passed', [
            'ip' => $request->ip(),
            'external_id' => $request->input('external_id'),
            'status' => $request->input('status')
        ]);

        return $next($request);
    }

    /**
     * Check if this is a valid webhook request
     */
    private function isWebhookRequest(Request $request): bool
    {
        return $request->isMethod('POST') && 
               $request->is('webhook/*') &&
               $request->hasHeader('x-callback-token');
    }

    /**
     * Rate limiting per IP address
     */
    private function checkRateLimit(Request $request): bool
    {
        $ip = $request->ip();
        $key = "webhook_rate_limit:{$ip}";
        $maxAttempts = 100; // Max 100 webhook requests per minute per IP
        $decayMinutes = 1;

        $attempts = cache()->get($key, 0);
        
        if ($attempts >= $maxAttempts) {
            return false;
        }

        cache()->put($key, $attempts + 1, $decayMinutes * 60);
        return true;
    }

    /**
     * Check if request comes from trusted source (Xendit IPs)
     */
    private function isFromTrustedSource(Request $request): bool
    {
        $clientIp = $request->ip();
        
        // Xendit IP ranges (these should be updated periodically)
        $trustedIpRanges = [
            // Xendit webhook IPs (example - get actual IPs from Xendit docs)
            '147.139.0.0/16',     // Xendit primary range
            '103.20.0.0/16',      // Additional range
            '127.0.0.1',          // Localhost for testing
            '::1',                // IPv6 localhost
        ];

        // Also allow Cloudflare IPs if behind Cloudflare
        $cloudflareIps = config('trustedproxy.proxies', []);
        $trustedIpRanges = array_merge($trustedIpRanges, $cloudflareIps);

        foreach ($trustedIpRanges as $range) {
            if ($this->ipInRange($clientIp, $range)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if IP is in range
     */
    private function ipInRange(string $ip, string $range): bool
    {
        if (strpos($range, '/') === false) {
            // Single IP
            return $ip === $range;
        }

        list($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;

        return ($ip & $mask) == $subnet;
    }

    /**
     * Verify webhook signature using multiple methods
     */
    private function verifyWebhookSignature(Request $request): bool
    {
        $receivedToken = $request->header('x-callback-token');
        $expectedToken = env('XENDIT_WEBHOOK_TOKEN');

        if (empty($expectedToken)) {
            Log::error('Webhook verification token not configured');
            return false;
        }

        // Method 1: Simple token comparison (current method)
        if (hash_equals($expectedToken, $receivedToken ?? '')) {
            return true;
        }

        // Method 2: HMAC signature verification (if Xendit provides)
        $timestamp = $request->header('x-timestamp');
        $signature = $request->header('x-signature');
        
        if ($timestamp && $signature) {
            $payload = $request->getContent();
            $expectedSignature = hash_hmac('sha256', $timestamp . $payload, $expectedToken);
            
            if (hash_equals($signature, $expectedSignature)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate webhook payload structure
     */
    private function validatePayloadStructure(Request $request): bool
    {
        $data = $request->all();

        // Required fields for Xendit webhook
        $requiredFields = ['external_id', 'status'];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return false;
            }
        }

        // Validate status values
        $validStatuses = ['PENDING', 'PAID', 'SETTLED', 'EXPIRED', 'FAILED', 'CANCELLED'];
        if (!in_array(strtoupper($data['status']), $validStatuses)) {
            return false;
        }

        // Validate external_id format
        if (!preg_match('/^PPDB-PMB\d+-\d+-[A-Za-z0-9]+$/', $data['external_id'])) {
            return false;
        }

        return true;
    }

    /**
     * Prevent replay attacks by checking timestamp
     */
    private function checkReplayProtection(Request $request): bool
    {
        $timestamp = $request->header('x-timestamp');
        
        if (!$timestamp) {
            // If no timestamp header, use current time for basic validation
            return true;
        }

        $requestTime = (int) $timestamp;
        $currentTime = time();
        $tolerance = 300; // 5 minutes tolerance

        // Request should not be older than 5 minutes
        if ($currentTime - $requestTime > $tolerance) {
            return false;
        }

        // Request should not be from the future (with 1 minute tolerance)
        if ($requestTime - $currentTime > 60) {
            return false;
        }

        return true;
    }
}