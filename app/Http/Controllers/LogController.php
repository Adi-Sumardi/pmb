<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function viewLogs(Request $request)
    {
        // Hanya admin yang bisa akses
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Hanya di development
        if (env('APP_ENV') !== 'local') {
            abort(404, 'Not available in production');
        }

        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            return view('admin.logs', [
                'logContent' => 'Log file not found',
                'filter' => '',
                'lines' => 100
            ]);
        }

        // Get filter parameters
        $filter = $request->get('filter', '');
        $lines = (int) $request->get('lines', 100); // Default 100 lines

        // Read log file
        $logContent = file_get_contents($logFile);

        // Filter by keyword if provided
        if ($filter) {
            $logLines = explode("\n", $logContent);
            $filteredLines = array_filter($logLines, function($line) use ($filter) {
                return stripos($line, $filter) !== false;
            });
            $logContent = implode("\n", $filteredLines);
        }

        // Get last N lines
        $logLines = explode("\n", $logContent);
        $logLines = array_slice($logLines, -$lines);
        $logContent = implode("\n", $logLines);

        return view('admin.logs', compact('logContent', 'filter', 'lines'));
    }

    public function clearLogs()
    {
        // Hanya admin yang bisa akses
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Hanya di development
        if (env('APP_ENV') !== 'local') {
            return response()->json(['error' => 'Not available in production'], 404);
        }

        $logFile = storage_path('logs/laravel.log');

        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
            return response()->json(['success' => true, 'message' => 'Logs cleared successfully']);
        }

        return response()->json(['error' => 'Log file not found'], 404);
    }

    public function downloadLogs()
    {
        // Hanya admin yang bisa akses
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Hanya di development
        if (env('APP_ENV') !== 'local') {
            abort(404, 'Not available in production');
        }

        $logFile = storage_path('logs/laravel.log');

        if (file_exists($logFile)) {
            return response()->download($logFile, 'laravel-' . date('Y-m-d-H-i-s') . '.log');
        }

        return back()->with('error', 'Log file not found');
    }

    public function streamLogs(Request $request)
    {
        // Hanya admin yang bisa akses
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $logFile = storage_path('logs/laravel.log');

        if (!file_exists($logFile)) {
            return response()->json(['error' => 'Log file not found'], 404);
        }

        $filter = $request->get('filter', '');
        $lines = (int) $request->get('lines', 50);

        $logContent = file_get_contents($logFile);

        // Filter by keyword if provided
        if ($filter) {
            $logLines = explode("\n", $logContent);
            $filteredLines = array_filter($logLines, function($line) use ($filter) {
                return stripos($line, $filter) !== false;
            });
            $logContent = implode("\n", $filteredLines);
        }

        // Get last N lines
        $logLines = explode("\n", $logContent);
        $logLines = array_slice($logLines, -$lines);
        $logContent = implode("\n", $logLines);

        return response()->json([
            'content' => $logContent,
            'timestamp' => now()->toISOString()
        ]);
    }
}
