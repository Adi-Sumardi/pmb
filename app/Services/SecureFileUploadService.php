<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class SecureFileUploadService
{
    private const MAX_FILE_SIZE = 2097152; // 2MB
    private const ALLOWED_EXTENSIONS = ['pdf', 'jpg', 'jpeg', 'png'];
    private const STORAGE_DISK = 'local';

    /**
     * Upload file securely with validation
     */
    public function uploadFile(UploadedFile $file, string $directory = 'documents'): array
    {
        try {
            // Validate file
            $this->validateFile($file);

            // Generate secure filename
            $filename = $this->generateSecureFilename($file);

            // Create directory if not exists
            $fullDirectory = $this->createSecureDirectory($directory);

            // Store file
            $path = $file->storeAs($fullDirectory, $filename, self::STORAGE_DISK);

            if (!$path) {
                throw new \Exception('Failed to store file');
            }

            // Log successful upload (without sensitive data)
            Log::info('File uploaded successfully', [
                'filename' => $filename,
                'directory' => $fullDirectory,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            return [
                'success' => true,
                'path' => $path,
                'filename' => $filename,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'original_name' => $file->getClientOriginalName()
            ];

        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate uploaded file
     */
    private function validateFile(UploadedFile $file): void
    {
        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \Exception('File size exceeds maximum allowed size (2MB)');
        }

        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new \Exception('File extension not allowed');
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        $allowedMimes = [
            'application/pdf',
            'image/jpeg',
            'image/jpg',
            'image/png'
        ];

        if (!in_array($mimeType, $allowedMimes)) {
            throw new \Exception('File MIME type not allowed');
        }

        // Validate file content/magic bytes
        $this->validateFileContent($file);
    }

    /**
     * Validate file content by checking magic bytes
     */
    private function validateFileContent(UploadedFile $file): void
    {
        $filePath = $file->getPathname();
        $handle = fopen($filePath, 'rb');

        if (!$handle) {
            throw new \Exception('Cannot read file content');
        }

        $header = fread($handle, 10);
        fclose($handle);

        $mimeType = $file->getMimeType();

        // Validate based on file type
        switch ($mimeType) {
            case 'application/pdf':
                if (strpos($header, '%PDF') !== 0) {
                    throw new \Exception('Invalid PDF file');
                }
                break;

            case 'image/jpeg':
            case 'image/jpg':
                if (bin2hex(substr($header, 0, 3)) !== 'ffd8ff') {
                    throw new \Exception('Invalid JPEG file');
                }
                break;

            case 'image/png':
                if (bin2hex(substr($header, 0, 8)) !== '89504e470d0a1a0a') {
                    throw new \Exception('Invalid PNG file');
                }
                break;
        }

        // Scan for malicious content patterns
        $this->scanForMaliciousContent($filePath);
    }

    /**
     * Scan file for potentially malicious content
     */
    private function scanForMaliciousContent(string $filePath): void
    {
        $content = file_get_contents($filePath, false, null, 0, 1024); // Read first 1KB

        // Check for common malicious patterns
        $maliciousPatterns = [
            '<?php',
            '<?=',
            '<script',
            'javascript:',
            'vbscript:',
            'onload=',
            'onerror=',
            'eval(',
            'base64_decode(',
            'shell_exec(',
            'system(',
            'exec(',
            'passthru('
        ];

        foreach ($maliciousPatterns as $pattern) {
            if (stripos($content, $pattern) !== false) {
                throw new \Exception('File contains potentially malicious content');
            }
        }
    }

    /**
     * Generate secure filename
     */
    private function generateSecureFilename(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $timestamp = now()->format('Y_m_d_H_i_s');
        $randomString = bin2hex(random_bytes(8));

        return "doc_{$timestamp}_{$randomString}.{$extension}";
    }

    /**
     * Create secure directory structure
     */
    private function createSecureDirectory(string $baseDirectory): string
    {
        $year = now()->year;
        $month = now()->format('m');

        $directory = "{$baseDirectory}/{$year}/{$month}";

        // Create directory if it doesn't exist
        if (!Storage::disk(self::STORAGE_DISK)->exists($directory)) {
            Storage::disk(self::STORAGE_DISK)->makeDirectory($directory);
        }

        return $directory;
    }

    /**
     * Delete file securely
     */
    public function deleteFile(string $path): bool
    {
        try {
            if (Storage::disk(self::STORAGE_DISK)->exists($path)) {
                Storage::disk(self::STORAGE_DISK)->delete($path);

                Log::info('File deleted successfully', [
                    'path' => $path
                ]);

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('File deletion failed', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Get file URL securely
     */
    public function getFileUrl(string $path): ?string
    {
        if (Storage::disk(self::STORAGE_DISK)->exists($path)) {
            // For local disk, we'll create a secure route instead of direct URL
            return route('file.download', ['path' => base64_encode($path)]);
        }

        return null;
    }

    /**
     * Validate file exists and user can access it
     */
    public function validateFileAccess(string $path, int $pendaftarId): bool
    {
        // Check if file exists
        if (!Storage::disk(self::STORAGE_DISK)->exists($path)) {
            return false;
        }

        // Additional security: verify file belongs to the user
        // This should be implemented based on your file ownership logic
        return true;
    }
}
