<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheOptimizationService
{
    private const DEFAULT_TTL = 3600; // 1 hour

    /**
     * Cache user data with optimized structure
     */
    public function cacheUserData($user, int $ttl = self::DEFAULT_TTL): void
    {
        $key = "user_data:{$user->id}";
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'cached_at' => now()->timestamp
        ];

        Cache::put($key, $data, $ttl);
    }

    /**
     * Get cached user data
     */
    public function getCachedUserData(int $userId): ?array
    {
        return Cache::get("user_data:{$userId}");
    }

    /**
     * Cache pendaftar data for quick access
     */
    public function cachePendaftarData($pendaftar, int $ttl = self::DEFAULT_TTL): void
    {
        $key = "pendaftar_data:{$pendaftar->id}";
        $data = [
            'id' => $pendaftar->id,
            'user_id' => $pendaftar->user_id,
            'registration_number' => $pendaftar->registration_number,
            'status' => $pendaftar->status,
            'cached_at' => now()->timestamp
        ];

        Cache::put($key, $data, $ttl);

        // Also cache by user_id for quick lookup
        Cache::put("pendaftar_by_user:{$pendaftar->user_id}", $pendaftar->id, $ttl);
    }

    /**
     * Cache frequently accessed configurations
     */
    public function cacheApplicationSettings(): void
    {
        $settings = [
            'registration_open' => config('app.registration_open', true),
            'max_file_size' => config('filesystems.max_upload_size', 5242880),
            'allowed_file_types' => config('filesystems.allowed_types', []),
            'contact_info' => config('app.contact_info', []),
            'cached_at' => now()->timestamp
        ];

        Cache::put('app_settings', $settings, 86400); // 24 hours
    }

    /**
     * Cache database query results
     */
    public function cacheQuery(string $key, callable $callback, int $ttl = self::DEFAULT_TTL)
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Cache paginated results
     */
    public function cachePaginatedQuery(string $baseKey, int $page, int $perPage, callable $callback, int $ttl = 1800): array
    {
        $key = "{$baseKey}:page_{$page}:per_{$perPage}";

        return Cache::remember($key, $ttl, function() use ($callback) {
            $result = $callback();
            return [
                'data' => $result->items(),
                'total' => $result->total(),
                'per_page' => $result->perPage(),
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'cached_at' => now()->timestamp
            ];
        });
    }

    /**
     * Clear user-related caches
     */
    public function clearUserCache(int $userId): void
    {
        $keys = [
            "user_data:{$userId}",
            "pendaftar_by_user:{$userId}"
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Clear any pendaftar data if exists
        $pendaftarId = Cache::get("pendaftar_by_user:{$userId}");
        if ($pendaftarId) {
            Cache::forget("pendaftar_data:{$pendaftarId}");
        }
    }

    /**
     * Clear all application caches
     */
    public function clearAllCaches(): void
    {
        Cache::flush();
    }

    /**
     * Warm up critical caches
     */
    public function warmUpCaches(): void
    {
        // Cache application settings
        $this->cacheApplicationSettings();

        // Cache frequently accessed data
        $this->cacheQuery('active_registrations_count', function() {
            return \App\Models\Pendaftar::where('status', 'active')->count();
        }, 600); // 10 minutes

        $this->cacheQuery('recent_payments_count', function() {
            return \App\Models\Payment::where('created_at', '>=', now()->subDays(7))->count();
        }, 1800); // 30 minutes
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats(): array
    {
        try {
            $redis = Redis::connection();
            $info = $redis->info();

            return [
                'connected_clients' => $info['connected_clients'] ?? 0,
                'used_memory' => $info['used_memory_human'] ?? '0B',
                'keyspace_hits' => $info['keyspace_hits'] ?? 0,
                'keyspace_misses' => $info['keyspace_misses'] ?? 0,
                'hit_rate' => $this->calculateHitRate($info),
                'total_keys' => $this->getTotalKeys($redis)
            ];
        } catch (\Exception $e) {
            return ['error' => 'Redis connection failed: ' . $e->getMessage()];
        }
    }

    /**
     * Calculate cache hit rate
     */
    private function calculateHitRate(array $info): float
    {
        $hits = $info['keyspace_hits'] ?? 0;
        $misses = $info['keyspace_misses'] ?? 0;
        $total = $hits + $misses;

        return $total > 0 ? round(($hits / $total) * 100, 2) : 0;
    }

    /**
     * Get total number of keys in Redis
     */
    private function getTotalKeys($redis): int
    {
        try {
            $databases = $redis->config('get', 'databases');
            $totalKeys = 0;

            for ($i = 0; $i < ($databases['databases'] ?? 16); $i++) {
                $redis->select($i);
                $totalKeys += $redis->dbsize();
            }

            $redis->select(0); // Reset to default database
            return $totalKeys;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Implement cache tags for better cache invalidation
     */
    public function cacheWithTags(array $tags, string $key, $value, int $ttl = self::DEFAULT_TTL): void
    {
        if (config('cache.default') === 'redis') {
            Cache::tags($tags)->put($key, $value, $ttl);
        } else {
            // Fallback for cache stores that don't support tags
            Cache::put($key, $value, $ttl);

            // Store tag associations for manual cleanup
            foreach ($tags as $tag) {
                $tagKeys = Cache::get("tag:{$tag}", []);
                $tagKeys[] = $key;
                Cache::put("tag:{$tag}", array_unique($tagKeys), $ttl);
            }
        }
    }

    /**
     * Clear cache by tags
     */
    public function clearCacheByTags(array $tags): void
    {
        if (config('cache.default') === 'redis') {
            Cache::tags($tags)->flush();
        } else {
            // Manual cleanup for cache stores without tag support
            foreach ($tags as $tag) {
                $tagKeys = Cache::get("tag:{$tag}", []);
                foreach ($tagKeys as $key) {
                    Cache::forget($key);
                }
                Cache::forget("tag:{$tag}");
            }
        }
    }
}