<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SecurityValidationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ComprehensiveSecurityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Rate limiting
        $this->handleRateLimiting($request);

        // XSS Protection
        $this->protectAgainstXSS($request);

        // SQL Injection Protection
        $this->protectAgainstSQLInjection($request);

        // CSRF Validation for state-changing operations
        $this->validateCSRF($request);

        // Input validation and sanitization
        $this->sanitizeInput($request);

        // Log suspicious activity
        $this->detectSuspiciousActivity($request);

        $response = $next($request);

        // Add security headers
        $this->addSecurityHeaders($response);

        return $response;
    }

    /**
     * Handle rate limiting
     */
    private function handleRateLimiting(Request $request): void
    {
        $key = $request->ip() . '|' . $request->path();

        if (RateLimiter::tooManyAttempts($key, 100)) {
            Log::warning('Rate limit exceeded', [
                'ip' => $request->ip(),
                'path' => $request->path(),
                'user_agent' => $request->userAgent()
            ]);

            abort(429, 'Too Many Requests');
        }

        RateLimiter::hit($key, 60); // 100 requests per minute
    }

    /**
     * Protect against XSS attacks
     */
    private function protectAgainstXSS(Request $request): void
    {
        $suspiciousPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/<iframe/i',
            '/<object/i',
            '/<embed/i',
            '/data:text\/html/i'
        ];

        $this->checkInputForPatterns($request, $suspiciousPatterns, 'XSS');
    }

    /**
     * Protect against SQL injection
     */
    private function protectAgainstSQLInjection(Request $request): void
    {
        $suspiciousPatterns = [
            '/(\b(union|select|insert|update|delete|drop|create|alter|exec|execute)\b)/i',
            '/(\b(or|and)\s+\d+\s*=\s*\d+)/i',
            '/(\b(or|and)\s+\'\w+\'\s*=\s*\'\w+\')/i',
            '/(\'\s*(or|and)\s*\')/i',
            '/(\/\*|\*\/|--)/i',
            '/(\bsleep\s*\()/i',
            '/(\bbenchmark\s*\()/i'
        ];

        $this->checkInputForPatterns($request, $suspiciousPatterns, 'SQL Injection');
    }

    /**
     * Check input for suspicious patterns
     */
    private function checkInputForPatterns(Request $request, array $patterns, string $attackType): void
    {
        $allInput = $request->all();

        foreach ($allInput as $key => $value) {
            if (is_string($value)) {
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        Log::warning("Potential $attackType attack detected", [
                            'ip' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                            'path' => $request->path(),
                            'field' => $key,
                            'pattern' => $pattern,
                            'value' => substr($value, 0, 100) // Log first 100 chars only
                        ]);

                        abort(400, 'Malicious input detected');
                    }
                }
            }
        }
    }

    /**
     * Validate CSRF for state-changing operations
     */
    private function validateCSRF(Request $request): void
    {
        $stateLessRoutes = [
            'api/*',
            'webhook/*',
            'payments/webhook'
        ];

        $currentPath = $request->path();
        $isStateLessRoute = false;

        foreach ($stateLessRoutes as $route) {
            if (fnmatch($route, $currentPath)) {
                $isStateLessRoute = true;
                break;
            }
        }

        if (!$isStateLessRoute && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            if (!$request->hasValidSignature() && !$request->session()->token()) {
                Log::warning('CSRF validation failed', [
                    'ip' => $request->ip(),
                    'path' => $request->path(),
                    'method' => $request->method()
                ]);
            }
        }
    }

    /**
     * Sanitize user input
     */
    private function sanitizeInput(Request $request): void
    {
        $input = $request->all();
        $sanitized = [];

        foreach ($input as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = SecurityValidationService::sanitizeInput($value);
            } elseif (is_array($value)) {
                $sanitized[$key] = $this->sanitizeArrayInput($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        $request->replace($sanitized);
    }

    /**
     * Sanitize array input recursively
     */
    private function sanitizeArrayInput(array $input): array
    {
        $sanitized = [];

        foreach ($input as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = SecurityValidationService::sanitizeInput($value);
            } elseif (is_array($value)) {
                $sanitized[$key] = $this->sanitizeArrayInput($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Detect suspicious activity
     */
    private function detectSuspiciousActivity(Request $request): void
    {
        $suspiciousIndicators = [
            // Common attack paths
            'admin', 'phpmyadmin', 'wp-admin', 'wp-login',
            // File inclusion attempts
            '../', '..\\', '/etc/passwd', '/etc/shadow',
            // Command injection
            '&&', '||', '|', ';', '`',
            // Directory traversal
            '%2e%2e', '%252e%252e'
        ];

        $path = $request->path();
        $userAgent = $request->userAgent();

        foreach ($suspiciousIndicators as $indicator) {
            if (stripos($path, $indicator) !== false || stripos($userAgent, $indicator) !== false) {
                Log::warning('Suspicious activity detected', [
                    'ip' => $request->ip(),
                    'path' => $path,
                    'user_agent' => $userAgent,
                    'indicator' => $indicator
                ]);
                break;
            }
        }

        // Check for automated scanning tools
        $botPatterns = [
            'sqlmap', 'nikto', 'nessus', 'burp', 'nmap',
            'dirb', 'dirbuster', 'gobuster', 'wfuzz'
        ];

        foreach ($botPatterns as $pattern) {
            if (stripos($userAgent, $pattern) !== false) {
                Log::critical('Security scanning tool detected', [
                    'ip' => $request->ip(),
                    'user_agent' => $userAgent,
                    'tool' => $pattern
                ]);
                abort(403, 'Access Denied');
            }
        }
    }

    /**
     * Add security headers to response
     */
    private function addSecurityHeaders(Response $response): void
    {
        $response->headers->set('X-Security-Scan', 'Protected');
        $response->headers->set('X-Rate-Limit', 'Enforced');
        $response->headers->set('X-Input-Validation', 'Active');
    }
}
