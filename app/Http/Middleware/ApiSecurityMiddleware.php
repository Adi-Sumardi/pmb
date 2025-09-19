<?php

namespace App\Http\Middleware;

use App\Services\SecureLoggingService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiSecurityMiddleware
{
    public function __construct(
        private SecureLoggingService $secureLogging
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Validate API request structure
        if (!$this->validateApiRequest($request)) {
            $this->logSecurityEvent($request, 'invalid_api_request');
            return response()->json(['error' => 'Invalid API request'], 400);
        }
        
        // Check for suspicious patterns
        if ($this->detectSuspiciousActivity($request)) {
            $this->logSecurityEvent($request, 'suspicious_activity');
            return response()->json(['error' => 'Request blocked'], 403);
        }
        
        // Validate content length
        if (!$this->validateContentLength($request)) {
            $this->logSecurityEvent($request, 'content_length_exceeded');
            return response()->json(['error' => 'Request too large'], 413);
        }
        
        // Check request frequency per IP
        if (!$this->checkRequestFrequency($request)) {
            $this->logSecurityEvent($request, 'rate_limit_exceeded');
            return response()->json(['error' => 'Too many requests'], 429);
        }
        
        $response = $next($request);
        
        // Add security headers to API responses
        $this->addApiSecurityHeaders($response);
        
        // Log successful API access
        $this->logApiAccess($request, $response);
        
        return $response;
    }
    
    /**
     * Validate basic API request structure
     */
    private function validateApiRequest(Request $request): bool
    {
        // Check for required headers
        $requiredHeaders = ['Accept', 'Content-Type'];
        foreach ($requiredHeaders as $header) {
            if (!$request->hasHeader($header)) {
                return false;
            }
        }
        
        // Validate Content-Type for POST/PUT requests
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            $contentType = $request->header('Content-Type');
            $allowedTypes = [
                'application/json',
                'application/x-www-form-urlencoded',
                'multipart/form-data'
            ];
            
            $isValidType = false;
            foreach ($allowedTypes as $type) {
                if (str_starts_with($contentType, $type)) {
                    $isValidType = true;
                    break;
                }
            }
            
            if (!$isValidType) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Detect suspicious activity patterns
     */
    private function detectSuspiciousActivity(Request $request): bool
    {
        $suspicious = false;
        
        // Check for SQL injection patterns
        $sqlPatterns = [
            '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER)\b)/i',
            '/(\b(UNION|OR|AND)\s+\b)/i',
            '/(\'|\"|;|--|\*|\/\*|\*\/)/i'
        ];
        
        $requestData = $request->all();
        $queryString = $request->getQueryString();
        $checkString = json_encode($requestData) . ' ' . $queryString;
        
        foreach ($sqlPatterns as $pattern) {
            if (preg_match($pattern, $checkString)) {
                $suspicious = true;
                break;
            }
        }
        
        // Check for XSS patterns
        $xssPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/javascript:/i',
            '/on\w+\s*=/i'
        ];
        
        foreach ($xssPatterns as $pattern) {
            if (preg_match($pattern, $checkString)) {
                $suspicious = true;
                break;
            }
        }
        
        // Check for path traversal
        if (preg_match('/(\.\.[\/\\\\])/i', $checkString)) {
            $suspicious = true;
        }
        
        return $suspicious;
    }
    
    /**
     * Validate content length
     */
    private function validateContentLength(Request $request): bool
    {
        $maxSize = config('api.max_request_size', 10 * 1024 * 1024); // 10MB default
        $contentLength = $request->header('Content-Length', 0);
        
        return $contentLength <= $maxSize;
    }
    
    /**
     * Check request frequency per IP
     */
    private function checkRequestFrequency(Request $request): bool
    {
        $ip = $request->ip();
        $key = "api_requests:{$ip}";
        $maxRequests = config('api.max_requests_per_minute', 60);
        
        $requestCount = Cache::get($key, 0);
        
        if ($requestCount >= $maxRequests) {
            return false;
        }
        
        Cache::put($key, $requestCount + 1, 60); // 1 minute window
        
        return true;
    }
    
    /**
     * Add security headers to API responses
     */
    private function addApiSecurityHeaders(Response $response): void
    {
        $response->headers->set('X-API-Version', '1.0');
        $response->headers->set('X-Rate-Limit-Remaining', $this->getRemainingRequests());
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
    }
    
    /**
     * Get remaining request count for current IP
     */
    private function getRemainingRequests(): int
    {
        $ip = request()->ip();
        $key = "api_requests:{$ip}";
        $maxRequests = config('api.max_requests_per_minute', 60);
        $currentCount = Cache::get($key, 0);
        
        return max(0, $maxRequests - $currentCount);
    }
    
    /**
     * Log security events
     */
    private function logSecurityEvent(Request $request, string $event): void
    {
        $this->secureLogging->logSecurityEvent($event, [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'timestamp' => now()
        ]);
    }
    
    /**
     * Log successful API access
     */
    private function logApiAccess(Request $request, Response $response): void
    {
        $this->secureLogging->logActivity([
            'type' => 'api_access',
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'status_code' => $response->getStatusCode(),
            'user_id' => Auth::user()?->id,
            'timestamp' => now()
        ]);
    }
}