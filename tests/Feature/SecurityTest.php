<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Services\SecurityValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test XSS protection in input sanitization
     */
    public function test_xss_input_sanitization()
    {
        $maliciousInput = '<script>alert("XSS")</script>';
        $sanitized = SecurityValidationService::sanitizeInput($maliciousInput);

        $this->assertStringNotContainsString('<script>', $sanitized);
        $this->assertStringNotContainsString('alert', $sanitized);
    }

    /**
     * Test SQL injection protection
     */
    public function test_sql_injection_protection()
    {
        $maliciousParams = [
            'name' => "'; DROP TABLE users; --",
            'email' => "admin@test.com' UNION SELECT password FROM users --"
        ];

        $sanitized = SecurityValidationService::validateSqlParameters($maliciousParams);

        $this->assertStringNotContainsString('DROP TABLE', $sanitized['name']);
        $this->assertStringNotContainsString('UNION SELECT', $sanitized['email']);
    }

    /**
     * Test file upload security
     */
    public function test_malicious_file_upload_prevention()
    {
        // Create a fake malicious file
        $maliciousFile = UploadedFile::fake()->create('malicious.php', 100);

        $this->expectException(\Exception::class);
        SecurityValidationService::validateFileUpload($maliciousFile);
    }

    /**
     * Test valid file upload
     */
    public function test_valid_file_upload()
    {
        $validFile = UploadedFile::fake()->image('document.jpg', 1000, 1000);

        $result = SecurityValidationService::validateFileUpload($validFile);

        $this->assertTrue($result['validated']);
        $this->assertStringContainsString('.jpg', $result['safe_filename']);
    }

    /**
     * Test user permission validation
     */
    public function test_user_permission_validation()
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);

        // Admin should have access to everything
        $this->assertTrue(SecurityValidationService::validateUserPermissions($admin, 'any_action'));

        // User should have limited access
        $this->assertTrue(SecurityValidationService::validateUserPermissions($user, 'user_action'));
    }

    /**
     * Test rate limiting
     */
    public function test_rate_limiting()
    {
        $key = 'test_user_' . time();

        // Should allow first 60 attempts
        for ($i = 0; $i < 60; $i++) {
            $this->assertTrue(SecurityValidationService::checkRateLimit($key, 60, 1));
        }

        // Should block 61st attempt
        $this->assertFalse(SecurityValidationService::checkRateLimit($key, 60, 1));
    }

    /**
     * Test JSON validation
     */
    public function test_json_validation()
    {
        $validJson = '{"name": "test", "value": 123}';
        $result = SecurityValidationService::validateJsonInput($validJson);

        $this->assertIsArray($result);
        $this->assertEquals('test', $result['name']);

        // Test malicious JSON
        $maliciousJson = '{"script": "<script>alert(1)</script>"}';
        $result = SecurityValidationService::validateJsonInput($maliciousJson);

        $this->assertStringNotContainsString('<script>', $result['script']);
    }

    /**
     * Test authentication middleware
     */
    public function test_authentication_required()
    {
        // Test accessing protected route without authentication
        $response = $this->get('/user/data');
        $response->assertRedirect('/login');

        // Test accessing admin route without admin role
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    /**
     * Test CSRF protection
     */
    public function test_csrf_protection()
    {
        $user = User::factory()->create();

        // Test POST request without CSRF token
        $response = $this->actingAs($user)->post('/user/data/student', [
            'nama_lengkap' => 'Test User'
        ]);

        $response->assertStatus(419); // CSRF token mismatch
    }

    /**
     * Test secure headers
     */
    public function test_security_headers()
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
    }

    /**
     * Test input length limits
     */
    public function test_input_length_limits()
    {
        $longInput = str_repeat('A', 10001); // Exceeds max length
        $sanitized = SecurityValidationService::sanitizeInput($longInput);

        $this->assertLessThanOrEqual(10000, strlen($sanitized));
    }

    /**
     * Test dangerous filename prevention
     */
    public function test_dangerous_filename_prevention()
    {
        $dangerousFile = UploadedFile::fake()->create('../../etc/passwd.txt', 100);

        $this->expectException(\Exception::class);
        SecurityValidationService::validateFileUpload($dangerousFile);
    }
}
