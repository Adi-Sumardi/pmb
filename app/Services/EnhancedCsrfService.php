<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EnhancedCsrfService
{
    private const TOKEN_LIFETIME = 3600; // 1 hour
    private const MAX_TOKENS_PER_SESSION = 10;

    /**
     * Generate a new CSRF token
     */
    public function generateToken(string $sessionId): string
    {
        $token = Str::random(40);
        $hash = Hash::make($token);

        // Store token with expiration
        $key = "csrf_token:{$sessionId}:{$token}";
        Cache::put($key, $hash, self::TOKEN_LIFETIME);

        // Clean up old tokens for this session
        $this->cleanupOldTokens($sessionId);

        return $token;
    }

    /**
     * Verify CSRF token
     */
    public function verifyToken(string $sessionId, string $token): bool
    {
        if (empty($token) || empty($sessionId)) {
            return false;
        }

        $key = "csrf_token:{$sessionId}:{$token}";
        $storedHash = Cache::get($key);

        if (!$storedHash) {
            return false;
        }

        $isValid = Hash::check($token, $storedHash);

        if ($isValid) {
            // Remove token after use (one-time use)
            Cache::forget($key);
        }

        return $isValid;
    }

    /**
     * Validate request has valid CSRF token
     */
    public function validateRequest(Request $request): bool
    {
        $sessionId = $request->session()->getId();
        $token = $request->header('X-CSRF-TOKEN')
               ?? $request->input('_token');

        if (!$token) {
            return false;
        }

        return $this->verifyToken($sessionId, $token);
    }

    /**
     * Clean up old tokens for a session
     */
    private function cleanupOldTokens(string $sessionId): void
    {
        $pattern = "csrf_token:{$sessionId}:*";
        $keys = Cache::get("csrf_tokens:{$sessionId}", []);

        // Keep only recent tokens
        if (count($keys) >= self::MAX_TOKENS_PER_SESSION) {
            $tokensToRemove = array_slice($keys, 0, count($keys) - self::MAX_TOKENS_PER_SESSION + 1);

            foreach ($tokensToRemove as $tokenKey) {
                Cache::forget($tokenKey);
            }

            $keys = array_slice($keys, count($tokensToRemove));
        }

        Cache::put("csrf_tokens:{$sessionId}", $keys, self::TOKEN_LIFETIME);
    }

    /**
     * Get current CSRF token for session
     */
    public function getCurrentToken(Request $request): string
    {
        $sessionId = $request->session()->getId();
        $existingToken = $request->session()->get('_token');

        if ($existingToken && $this->isTokenValid($sessionId, $existingToken)) {
            return $existingToken;
        }

        $newToken = $this->generateToken($sessionId);
        $request->session()->put('_token', $newToken);

        return $newToken;
    }

    /**
     * Check if token exists and is valid
     */
    private function isTokenValid(string $sessionId, string $token): bool
    {
        $key = "csrf_token:{$sessionId}:{$token}";
        return Cache::has($key);
    }

    /**
     * Clear all tokens for a session
     */
    public function clearSessionTokens(string $sessionId): void
    {
        $keys = Cache::get("csrf_tokens:{$sessionId}", []);

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        Cache::forget("csrf_tokens:{$sessionId}");
    }
}
