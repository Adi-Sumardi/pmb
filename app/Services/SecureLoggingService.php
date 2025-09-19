<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SecureLoggingService
{
    private DataEncryptionService $encryptionService;

    public function __construct(DataEncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    /**
     * Log info with sanitized data
     */
    public function logInfo(string $message, array $context = []): void
    {
        $sanitizedContext = $this->encryptionService->sanitizeForLogging($context);
        Log::info($message, $sanitizedContext);
    }

    /**
     * Log warning with sanitized data
     */
    public function logWarning(string $message, array $context = []): void
    {
        $sanitizedContext = $this->encryptionService->sanitizeForLogging($context);
        Log::warning($message, $sanitizedContext);
    }

    /**
     * Log error with sanitized data
     */
    public function logError(string $message, array $context = []): void
    {
        $sanitizedContext = $this->encryptionService->sanitizeForLogging($context);
        Log::error($message, $sanitizedContext);
    }

    /**
     * Log debug with sanitized data
     */
    public function logDebug(string $message, array $context = []): void
    {
        $sanitizedContext = $this->encryptionService->sanitizeForLogging($context);
        Log::debug($message, $sanitizedContext);
    }

    /**
     * Log user activity securely
     */
    public function logUserActivity(string $action, $user = null, array $additionalData = []): void
    {
        $context = [
            'action' => $action,
            'user_id' => $user ? $user->id : null,
            'user_email' => $user ? $this->maskEmail($user->email) : null,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ];

        $context = array_merge($context, $this->encryptionService->sanitizeForLogging($additionalData));

        Log::channel('activity')->info('User activity: ' . $action, $context);
    }

    /**
     * Log general activity events
     */
    public function logActivity(array $context = []): void
    {
        $sanitizedContext = $this->encryptionService->sanitizeForLogging($context);
        Log::channel('activity')->info('Activity logged', $sanitizedContext);
    }

    /**
     * Log security events
     */
    public function logSecurityEvent(string $event, array $context = []): void
    {
        $securityContext = [
            'event' => $event,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ];

        $securityContext = array_merge($securityContext, $this->encryptionService->sanitizeForLogging($context));

        Log::channel('security')->warning('Security event: ' . $event, $securityContext);
    }

    /**
     * Log payment events with special handling
     */
    public function logPaymentEvent(string $event, array $context = []): void
    {
        // Remove any sensitive payment data
        $sensitiveFields = ['card_number', 'cvv', 'api_key', 'secret_key', 'callback_token'];
        $cleanContext = $context;

        foreach ($sensitiveFields as $field) {
            if (isset($cleanContext[$field])) {
                $cleanContext[$field] = '***';
            }
        }

        // Mask external_id for privacy but keep structure for debugging
        if (isset($cleanContext['external_id'])) {
            $externalId = $cleanContext['external_id'];
            if (strlen($externalId) > 8) {
                $cleanContext['external_id'] = substr($externalId, 0, 4) . '***' . substr($externalId, -4);
            }
        }

        $cleanContext = $this->encryptionService->sanitizeForLogging($cleanContext);

        Log::channel('payment')->info('Payment event: ' . $event, $cleanContext);
    }

    /**
     * Log file operations
     */
    public function logFileOperation(string $operation, array $context = []): void
    {
        // Remove file content from logs
        unset($context['file_content']);

        $fileContext = [
            'operation' => $operation,
            'user_id' => Auth::id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString()
        ];

        $fileContext = array_merge($fileContext, $this->encryptionService->sanitizeForLogging($context));

        Log::channel('file')->info('File operation: ' . $operation, $fileContext);
    }

    /**
     * Mask email for logging
     */
    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return '***';
        }

        $username = $parts[0];
        $domain = $parts[1];

        if (strlen($username) <= 2) {
            $maskedUsername = '***';
        } else {
            $maskedUsername = substr($username, 0, 1) . '***' . substr($username, -1);
        }

        return $maskedUsername . '@' . $domain;
    }

    /**
     * Create audit trail for important actions
     */
    public function createAuditTrail(string $action, $model, array $changes = []): void
    {
        $auditData = [
            'action' => $action,
            'model' => get_class($model),
            'model_id' => $model->id ?? null,
            'user_id' => Auth::id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'changes' => $this->encryptionService->sanitizeForLogging($changes),
            'timestamp' => now()->toDateTimeString()
        ];

        Log::channel('audit')->info('Audit trail: ' . $action, $auditData);
    }
}
