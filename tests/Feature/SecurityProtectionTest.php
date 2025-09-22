<?php

use Tests\TestCase;
use App\Services\SecurityValidationService;
use App\Http\Middleware\ComprehensiveSecurityMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;

class SecurityProtectionTest extends TestCase
{
    protected $securityService;
    protected $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->securityService = new SecurityValidationService();
        $this->middleware = new ComprehensiveSecurityMiddleware();
    }

    /** @test */
    public function it_sanitizes_xss_attacks()
    {
        $maliciousInput = '<script>alert("XSS")</script>';
        $sanitized = $this->securityService->sanitizeInput($maliciousInput);

        $this->assertStringNotContainsString('<script>', $sanitized);
        $this->assertStringNotContainsString('alert', $sanitized);
    }

    /** @test */
    public function it_detects_sql_injection_attempts()
    {
        $sqlInjectionAttempts = [
            "'; DROP TABLE users; --",
            "admin' OR '1'='1",
            "UNION SELECT * FROM users",
            "' OR 1=1 --",
            "'; DELETE FROM students; --"
        ];

        foreach ($sqlInjectionAttempts as $injection) {
            $result = $this->securityService->validateSqlParameters(['input' => $injection]);
            $this->assertFalse($result, "Failed to detect SQL injection: {$injection}");
        }
    }

    /** @test */
    public function it_validates_file_uploads_securely()
    {
        // Test malicious file types
        $maliciousFiles = [
            ['name' => 'virus.php', 'type' => 'application/x-php'],
            ['name' => 'script.js', 'type' => 'application/javascript'],
            ['name' => 'malware.exe', 'type' => 'application/x-msdownload'],
            ['name' => 'hidden.php.jpg', 'type' => 'image/jpeg']
        ];

        foreach ($maliciousFiles as $file) {
            $result = $this->securityService->validateFileUpload($file);
            $this->assertFalse($result['valid'], "Failed to block malicious file: {$file['name']}");
        }

        // Test valid files
        $validFiles = [
            ['name' => 'document.pdf', 'type' => 'application/pdf', 'size' => 1024000],
            ['name' => 'photo.jpg', 'type' => 'image/jpeg', 'size' => 512000],
            ['name' => 'image.png', 'type' => 'image/png', 'size' => 256000]
        ];

        foreach ($validFiles as $file) {
            $result = $this->securityService->validateFileUpload($file);
            $this->assertTrue($result['valid'], "Failed to allow valid file: {$file['name']}");
        }
    }

    /** @test */
    public function it_implements_rate_limiting()
    {
        $ip = '192.168.1.1';
        $key = "rate_limit:{$ip}";

        // Clear any existing rate limit
        RateLimiter::clear($key);

        // Test normal requests
        for ($i = 1; $i <= 10; $i++) {
            $result = $this->securityService->checkRateLimit($ip);
            $this->assertTrue($result, "Rate limit triggered too early at request {$i}");
        }

        // Test rate limit exceeded
        for ($i = 11; $i <= 65; $i++) {
            $this->securityService->checkRateLimit($ip);
        }

        $result = $this->securityService->checkRateLimit($ip);
        $this->assertFalse($result, "Rate limit not enforced after exceeding limit");
    }

    /** @test */
    public function it_detects_suspicious_patterns()
    {
        $suspiciousInputs = [
            '../../../etc/passwd',
            '..\\..\\windows\\system32',
            'eval(',
            'base64_decode(',
            'file_get_contents(',
            'system(',
            'exec(',
            'shell_exec(',
            'passthru(',
            'proc_open('
        ];

        foreach ($suspiciousInputs as $input) {
            $sanitized = $this->securityService->sanitizeInput($input);
            $this->assertNotEquals($input, $sanitized, "Failed to sanitize suspicious input: {$input}");
        }
    }

    /** @test */
    public function middleware_blocks_xss_attempts()
    {
        $request = Request::create('/test', 'POST', [
            'name' => '<script>alert("XSS")</script>',
            'description' => '<img src=x onerror=alert(1)>'
        ]);

        try {
            $response = $this->middleware->handle($request, function ($req) {
                return new Response('OK');
            });
            $this->fail('Expected HttpException was not thrown');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            $this->assertEquals(400, $e->getStatusCode());
        }
    }

    /** @test */
    public function middleware_blocks_sql_injection_attempts()
    {
        $request = Request::create('/test', 'POST', [
            'search' => "'; DROP TABLE users; --",
            'filter' => "admin' OR '1'='1"
        ]);

        try {
            $response = $this->middleware->handle($request, function ($req) {
                return new Response('OK');
            });
            $this->fail('Expected HttpException was not thrown');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            $this->assertEquals(400, $e->getStatusCode());
        }
    }

    /** @test */
    public function it_validates_input_patterns()
    {
        // Test valid patterns
        $validInputs = [
            ['type' => 'name', 'value' => 'John Doe'],
            ['type' => 'email', 'value' => 'user@example.com'],
            ['type' => 'phone', 'value' => '+62812345678'],
            ['type' => 'nik', 'value' => '1234567890123456']
        ];

        foreach ($validInputs as $input) {
            $sanitized = $this->securityService->sanitizeInput($input['value']);
            $this->assertNotEmpty($sanitized, "Valid input was removed: {$input['value']}");
        }

        // Test invalid patterns
        $invalidInputs = [
            'javascript:alert(1)',
            'data:text/html,<script>alert(1)</script>',
            'vbscript:msgbox(1)',
            'onload=alert(1)',
            'onerror=alert(1)'
        ];

        foreach ($invalidInputs as $input) {
            $sanitized = $this->securityService->sanitizeInput($input);
            $this->assertStringNotContainsString('alert', $sanitized, "Failed to sanitize: {$input}");
            $this->assertStringNotContainsString('javascript:', $sanitized, "Failed to sanitize: {$input}");
        }
    }

    /** @test */
    public function it_prevents_directory_traversal()
    {
        $traversalAttempts = [
            '../../../etc/passwd',
            '..\\..\\windows\\system32',
            '....//....//....//etc//passwd',
            '..%2F..%2F..%2Fetc%2Fpasswd',
            '..%252F..%252F..%252Fetc%252Fpasswd'
        ];

        foreach ($traversalAttempts as $attempt) {
            $sanitized = $this->securityService->sanitizeInput($attempt);
            $this->assertStringNotContainsString('..', $sanitized, "Failed to prevent directory traversal: {$attempt}");
            $this->assertStringNotContainsString('/etc/', $sanitized, "Failed to prevent directory traversal: {$attempt}");
        }
    }

    /** @test */
    public function it_handles_unicode_attacks()
    {
        $unicodeAttacks = [
            'java\u0073cript:alert(1)',
            '<\u0073cript>alert(1)</\u0073cript>',
            '\u003cscript\u003ealert(1)\u003c/script\u003e',
            'javascript\u003aalert(1)'
        ];

        foreach ($unicodeAttacks as $attack) {
            $sanitized = $this->securityService->sanitizeInput($attack);
            $this->assertStringNotContainsString('alert', $sanitized, "Failed to handle unicode attack: {$attack}");
            $this->assertStringNotContainsString('script', $sanitized, "Failed to handle unicode attack: {$attack}");
        }
    }

    /** @test */
    public function security_helpers_work_correctly()
    {
        // Test safe_json function
        $data = ['name' => '<script>alert("XSS")</script>'];
        $json = safe_json($data);
        $this->assertStringNotContainsString('<script>', $json);

        // Test sanitize_output function
        $output = '<script>alert("XSS")</script>Hello';
        $sanitized = sanitize_output($output);
        $this->assertStringNotContainsString('<script>', $sanitized);
        $this->assertStringContainsString('Hello', $sanitized);
    }

    protected function tearDown(): void
    {
        // Clear rate limiting cache
        Cache::flush();
        RateLimiter::clear('*');
        parent::tearDown();
    }
}
