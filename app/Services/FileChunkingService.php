<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileChunkingService
{
    private const CHUNK_SIZE = 1024 * 1024; // 1MB chunks
    private const MAX_FILE_SIZE = 50 * 1024 * 1024; // 50MB max

    /**
     * Handle chunked file upload
     */
    public function handleChunkedUpload(Request $request): array
    {
        $chunkNumber = (int) $request->input('chunk_number', 0);
        $totalChunks = (int) $request->input('total_chunks', 1);
        $fileIdentifier = $request->input('file_identifier');
        $originalName = $request->input('original_name');

        if (!$fileIdentifier || !$originalName) {
            throw new \InvalidArgumentException('Missing file identifier or original name');
        }

        $chunk = $request->file('chunk');
        if (!$chunk || !$chunk->isValid()) {
            throw new \InvalidArgumentException('Invalid chunk file');
        }

        // Validate chunk size
        if ($chunk->getSize() > self::CHUNK_SIZE + 1024) { // Allow small buffer
            throw new \InvalidArgumentException('Chunk size too large');
        }

        // Store chunk temporarily
        $chunkPath = $this->storeChunk($chunk, $fileIdentifier, $chunkNumber);

        // Check if all chunks are uploaded
        if ($this->areAllChunksUploaded($fileIdentifier, $totalChunks)) {
            $finalFilePath = $this->assembleFile($fileIdentifier, $totalChunks, $originalName);
            $this->cleanupChunks($fileIdentifier, $totalChunks);

            return [
                'completed' => true,
                'file_path' => $finalFilePath,
                'message' => 'File upload completed'
            ];
        }

        return [
            'completed' => false,
            'chunk_number' => $chunkNumber,
            'total_chunks' => $totalChunks,
            'message' => 'Chunk uploaded successfully'
        ];
    }

    /**
     * Store individual chunk
     */
    private function storeChunk(UploadedFile $chunk, string $fileIdentifier, int $chunkNumber): string
    {
        $chunkName = "chunk_{$chunkNumber}";
        $chunkPath = "uploads/chunks/{$fileIdentifier}/{$chunkName}";

        // Ensure directory exists
        Storage::makeDirectory("uploads/chunks/{$fileIdentifier}");

        // Store chunk
        $stored = Storage::putFileAs(
            "uploads/chunks/{$fileIdentifier}",
            $chunk,
            $chunkName
        );

        if (!$stored) {
            throw new \RuntimeException('Failed to store chunk');
        }

        return $stored;
    }

    /**
     * Check if all chunks are uploaded
     */
    private function areAllChunksUploaded(string $fileIdentifier, int $totalChunks): bool
    {
        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = "uploads/chunks/{$fileIdentifier}/chunk_{$i}";
            if (!Storage::exists($chunkPath)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Assemble chunks into final file
     */
    private function assembleFile(string $fileIdentifier, int $totalChunks, string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
        $finalName = $safeName . '_' . time() . '.' . $extension;
        $finalPath = "uploads/documents/{$finalName}";

        // Create temporary file for assembly
        $tempFile = storage_path('app/temp/' . $fileIdentifier);
        $tempHandle = fopen($tempFile, 'wb');

        if (!$tempHandle) {
            throw new \RuntimeException('Cannot create temporary file');
        }

        try {
            // Combine all chunks
            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkPath = "uploads/chunks/{$fileIdentifier}/chunk_{$i}";
                $chunkContent = Storage::get($chunkPath);

                if ($chunkContent === false) {
                    throw new \RuntimeException("Cannot read chunk {$i}");
                }

                fwrite($tempHandle, $chunkContent);
            }

            fclose($tempHandle);

            // Validate assembled file
            $this->validateAssembledFile($tempFile, $originalName);

            // Move to final location
            $finalFullPath = Storage::putFile('uploads/documents', $tempFile);

            // Clean up temp file
            unlink($tempFile);

            return $finalFullPath;

        } catch (\Exception $e) {
            fclose($tempHandle);
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            throw $e;
        }
    }

    /**
     * Validate assembled file
     */
    private function validateAssembledFile(string $filePath, string $originalName): void
    {
        if (!file_exists($filePath)) {
            throw new \RuntimeException('Assembled file not found');
        }

        $fileSize = filesize($filePath);
        if ($fileSize > self::MAX_FILE_SIZE) {
            throw new \RuntimeException('File size exceeds maximum allowed');
        }

        // Validate file type using magic bytes
        $mimeType = mime_content_type($filePath);
        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if (!in_array($mimeType, $allowedMimes)) {
            throw new \RuntimeException('Invalid file type');
        }

        // Check for malicious content patterns
        $this->scanForMaliciousContent($filePath);
    }

    /**
     * Scan for malicious content
     */
    private function scanForMaliciousContent(string $filePath): void
    {
        $content = file_get_contents($filePath, false, null, 0, 8192); // Read first 8KB

        $maliciousPatterns = [
            '/<%\s*eval\s*\(/i',
            '/<\?php/i',
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i'
        ];

        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                throw new \RuntimeException('Malicious content detected');
            }
        }
    }

    /**
     * Clean up chunk files
     */
    private function cleanupChunks(string $fileIdentifier, int $totalChunks): void
    {
        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = "uploads/chunks/{$fileIdentifier}/chunk_{$i}";
            Storage::delete($chunkPath);
        }

        // Remove chunk directory
        Storage::deleteDirectory("uploads/chunks/{$fileIdentifier}");
    }

    /**
     * Generate unique file identifier
     */
    public function generateFileIdentifier(): string
    {
        return Str::uuid()->toString();
    }

    /**
     * Get upload progress
     */
    public function getUploadProgress(string $fileIdentifier, int $totalChunks): array
    {
        $uploadedChunks = 0;

        for ($i = 0; $i < $totalChunks; $i++) {
            $chunkPath = "uploads/chunks/{$fileIdentifier}/chunk_{$i}";
            if (Storage::exists($chunkPath)) {
                $uploadedChunks++;
            }
        }

        $progress = $totalChunks > 0 ? ($uploadedChunks / $totalChunks) * 100 : 0;

        return [
            'uploaded_chunks' => $uploadedChunks,
            'total_chunks' => $totalChunks,
            'progress_percentage' => round($progress, 2),
            'completed' => $uploadedChunks === $totalChunks
        ];
    }

    /**
     * Resume interrupted upload
     */
    public function resumeUpload(string $fileIdentifier): array
    {
        $chunksDir = "uploads/chunks/{$fileIdentifier}";

        if (!Storage::exists($chunksDir)) {
            return [
                'can_resume' => false,
                'message' => 'No previous upload found'
            ];
        }

        $chunkFiles = Storage::files($chunksDir);
        $uploadedChunks = [];

        foreach ($chunkFiles as $chunkFile) {
            $chunkName = basename($chunkFile);
            if (preg_match('/chunk_(\d+)/', $chunkName, $matches)) {
                $uploadedChunks[] = (int) $matches[1];
            }
        }

        sort($uploadedChunks);

        return [
            'can_resume' => true,
            'uploaded_chunks' => $uploadedChunks,
            'next_chunk' => $this->getNextMissingChunk($uploadedChunks),
            'message' => 'Upload can be resumed'
        ];
    }

    /**
     * Get next missing chunk number
     */
    private function getNextMissingChunk(array $uploadedChunks): int
    {
        $expected = 0;
        foreach ($uploadedChunks as $chunk) {
            if ($chunk !== $expected) {
                return $expected;
            }
            $expected++;
        }
        return $expected;
    }

    /**
     * Clean up old incomplete uploads
     */
    public function cleanupOldUploads(int $hoursOld = 24): int
    {
        $chunksDir = 'uploads/chunks';
        $cleaned = 0;

        if (!Storage::exists($chunksDir)) {
            return $cleaned;
        }

        $directories = Storage::directories($chunksDir);
        $cutoffTime = now()->subHours($hoursOld);

        foreach ($directories as $dir) {
            $lastModified = Storage::lastModified($dir);

            if ($lastModified && $lastModified < $cutoffTime->timestamp) {
                Storage::deleteDirectory($dir);
                $cleaned++;
            }
        }

        return $cleaned;
    }
}