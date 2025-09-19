<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class DataEncryptionService
{
    /**
     * List of fields that should be encrypted
     */
    private const ENCRYPTED_FIELDS = [
        'nik', 'no_kk', 'nisn', 'telp_ayah', 'telp_ibu', 'telp_wali',
        'no_hp_ayah', 'no_hp_ibu', 'no_hp_wali', 'email_ayah', 'email_ibu', 'email_wali'
    ];

    /**
     * Encrypt sensitive data before storing
     */
    public function encryptData(array $data): array
    {
        $encryptedData = $data;

        foreach (self::ENCRYPTED_FIELDS as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                try {
                    $encryptedData[$field] = Crypt::encrypt($data[$field]);
                } catch (\Exception $e) {
                    Log::error('Encryption failed for field: ' . $field, [
                        'error' => $e->getMessage()
                    ]);
                    // Keep original value if encryption fails
                    $encryptedData[$field] = $data[$field];
                }
            }
        }

        return $encryptedData;
    }

    /**
     * Decrypt sensitive data when retrieving
     */
    public function decryptData(array $data): array
    {
        $decryptedData = $data;

        foreach (self::ENCRYPTED_FIELDS as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                try {
                    $decryptedData[$field] = Crypt::decrypt($data[$field]);
                } catch (\Exception $e) {
                    Log::warning('Decryption failed for field: ' . $field, [
                        'error' => $e->getMessage()
                    ]);
                    // Keep encrypted value if decryption fails
                    $decryptedData[$field] = $data[$field];
                }
            }
        }

        return $decryptedData;
    }

    /**
     * Encrypt specific field
     */
    public function encryptField(string $value): string
    {
        try {
            return Crypt::encrypt($value);
        } catch (\Exception $e) {
            Log::error('Field encryption failed', [
                'error' => $e->getMessage()
            ]);
            return $value;
        }
    }

    /**
     * Decrypt specific field
     */
    public function decryptField(string $encryptedValue): string
    {
        try {
            return Crypt::decrypt($encryptedValue);
        } catch (\Exception $e) {
            Log::warning('Field decryption failed', [
                'error' => $e->getMessage()
            ]);
            return $encryptedValue;
        }
    }

    /**
     * Check if field should be encrypted
     */
    public function shouldEncrypt(string $fieldName): bool
    {
        return in_array($fieldName, self::ENCRYPTED_FIELDS);
    }

    /**
     * Mask sensitive data for logging
     */
    public function maskForLogging(array $data): array
    {
        $maskedData = $data;

        foreach (self::ENCRYPTED_FIELDS as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                $value = $data[$field];
                if (strlen($value) > 4) {
                    $maskedData[$field] = substr($value, 0, 2) . '***' . substr($value, -2);
                } else {
                    $maskedData[$field] = '***';
                }
            }
        }

        // Also mask other sensitive fields
        $sensitiveFields = ['password', 'token', 'api_key', 'secret'];
        foreach ($sensitiveFields as $field) {
            if (isset($maskedData[$field])) {
                $maskedData[$field] = '***';
            }
        }

        return $maskedData;
    }

    /**
     * Sanitize data for safe logging
     */
    public function sanitizeForLogging(array $data): array
    {
        // Remove completely sensitive data that should never be logged
        $forbiddenFields = ['password', 'password_confirmation', 'token', 'secret', 'api_key'];

        $sanitized = $data;
        foreach ($forbiddenFields as $field) {
            unset($sanitized[$field]);
        }

        // Mask other sensitive data
        return $this->maskForLogging($sanitized);
    }
}