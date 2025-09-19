<?php

namespace App\Http\Controllers;

use App\Services\SecureFileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\Document;
use App\Models\Pendaftar;

class FileController extends Controller
{
    private SecureFileUploadService $uploadService;

    public function __construct(SecureFileUploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * Secure file download with access control
     */
    public function download(Request $request, $encodedPath)
    {
        try {
            $path = base64_decode($encodedPath);

            if (!$path) {
                abort(404, 'File not found');
            }

            // Verify user has access to this file
            if (!$this->canAccessFile($path)) {
                abort(403, 'Access denied');
            }

            // Check if file exists
            if (!Storage::disk('local')->exists($path)) {
                abort(404, 'File not found');
            }

            $file = Storage::disk('local')->get($path);
            $fullPath = Storage::disk('local')->path($path);
            $mimeType = mime_content_type($fullPath);
            $filename = basename($path);

            return Response::make($file, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);

        } catch (\Exception $e) {
            abort(404, 'File not found');
        }
    }

    /**
     * Check if current user can access the file
     */
    private function canAccessFile(string $path): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // For admin users, allow all access
        if ($user->isAdmin()) {
            return true;
        }

        // For regular users, check if file belongs to them
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        if (!$pendaftar) {
            return false;
        }

        // Check if this file belongs to the user's pendaftar
        $document = Document::where('file_path', $path)
                           ->where('pendaftar_id', $pendaftar->id)
                           ->exists();

        return $document;
    }

    /**
     * Get file info for preview
     */
    public function info(Request $request, $encodedPath)
    {
        try {
            $path = base64_decode($encodedPath);

            if (!$path || !$this->canAccessFile($path)) {
                return response()->json(['error' => 'Access denied'], 403);
            }

            if (!Storage::disk('local')->exists($path)) {
                return response()->json(['error' => 'File not found'], 404);
            }

            $size = Storage::disk('local')->size($path);
            $fullPath = Storage::disk('local')->path($path);
            $mimeType = mime_content_type($fullPath);
            $lastModified = Storage::disk('local')->lastModified($path);

            return response()->json([
                'path' => $path,
                'size' => $size,
                'mime_type' => $mimeType,
                'last_modified' => date('Y-m-d H:i:s', $lastModified),
                'download_url' => route('file.download', ['path' => $encodedPath])
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}