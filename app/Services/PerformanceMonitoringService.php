<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerformanceMonitoringService
{
    private const METRICS_TTL = 300; // 5 minutes

    /**
     * Collect system performance metrics
     */
    public function collectMetrics(): array
    {
        return Cache::remember('system_metrics', self::METRICS_TTL, function() {
            return [
                'database' => $this->getDatabaseMetrics(),
                'cache' => $this->getCacheMetrics(),
                'memory' => $this->getMemoryMetrics(),
                'storage' => $this->getStorageMetrics(),
                'response_times' => $this->getResponseTimeMetrics(),
                'timestamp' => now()
            ];
        });
    }

    /**
     * Get database performance metrics
     */
    private function getDatabaseMetrics(): array
    {
        try {
            // Connection count
            $connections = DB::select("
                SELECT
                    count(*) as total,
                    count(*) FILTER (WHERE state = 'active') as active,
                    count(*) FILTER (WHERE state = 'idle') as idle
                FROM pg_stat_activity
                WHERE datname = current_database()
            ")[0];

            // Query performance
            $slowQueries = DB::select("
                SELECT count(*) as slow_query_count
                FROM pg_stat_statements
                WHERE mean_exec_time > 1000
            ")[0] ?? (object)['slow_query_count' => 0];

            // Cache hit ratio
            $cacheRatio = DB::select("
                SELECT
                    round(
                        sum(heap_blks_hit) / (sum(heap_blks_hit) + sum(heap_blks_read) + 0.0001) * 100,
                        2
                    ) as cache_hit_ratio
                FROM pg_statio_user_tables
            ")[0] ?? (object)['cache_hit_ratio' => 0];

            // Table sizes
            $tableSize = DB::select("
                SELECT
                    pg_size_pretty(pg_database_size(current_database())) as database_size,
                    pg_database_size(current_database()) as database_size_bytes
            ")[0];

            return [
                'connections' => [
                    'total' => $connections->total,
                    'active' => $connections->active,
                    'idle' => $connections->idle
                ],
                'performance' => [
                    'slow_queries' => $slowQueries->slow_query_count,
                    'cache_hit_ratio' => $cacheRatio->cache_hit_ratio
                ],
                'storage' => [
                    'database_size' => $tableSize->database_size,
                    'database_size_bytes' => $tableSize->database_size_bytes
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Failed to collect database metrics: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get cache performance metrics
     */
    private function getCacheMetrics(): array
    {
        try {
            if (config('cache.default') === 'redis') {
                $redis = app('redis')->connection('cache');
                $info = $redis->info();

                return [
                    'hit_rate' => $this->calculateRedisHitRate($info),
                    'memory_usage' => $info['used_memory_human'] ?? '0B',
                    'memory_usage_bytes' => $info['used_memory'] ?? 0,
                    'connected_clients' => $info['connected_clients'] ?? 0,
                    'total_commands' => $info['total_commands_processed'] ?? 0,
                    'keyspace_hits' => $info['keyspace_hits'] ?? 0,
                    'keyspace_misses' => $info['keyspace_misses'] ?? 0
                ];
            }

            return ['type' => 'non-redis', 'status' => 'limited_metrics'];

        } catch (\Exception $e) {
            Log::error('Failed to collect cache metrics: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Calculate Redis hit rate
     */
    private function calculateRedisHitRate(array $info): float
    {
        $hits = $info['keyspace_hits'] ?? 0;
        $misses = $info['keyspace_misses'] ?? 0;
        $total = $hits + $misses;

        return $total > 0 ? round(($hits / $total) * 100, 2) : 0;
    }

    /**
     * Get memory usage metrics
     */
    private function getMemoryMetrics(): array
    {
        $memoryLimit = ini_get('memory_limit');
        $memoryUsage = memory_get_usage(true);
        $memoryPeakUsage = memory_get_peak_usage(true);

        return [
            'limit' => $memoryLimit,
            'usage' => $this->formatBytes($memoryUsage),
            'usage_bytes' => $memoryUsage,
            'peak_usage' => $this->formatBytes($memoryPeakUsage),
            'peak_usage_bytes' => $memoryPeakUsage,
            'usage_percentage' => $this->getMemoryUsagePercentage($memoryUsage, $memoryLimit)
        ];
    }

    /**
     * Get storage metrics
     */
    private function getStorageMetrics(): array
    {
        $storagePath = storage_path();
        $totalSpace = disk_total_space($storagePath);
        $freeSpace = disk_free_space($storagePath);
        $usedSpace = $totalSpace - $freeSpace;

        return [
            'total_space' => $this->formatBytes($totalSpace),
            'total_space_bytes' => $totalSpace,
            'used_space' => $this->formatBytes($usedSpace),
            'used_space_bytes' => $usedSpace,
            'free_space' => $this->formatBytes($freeSpace),
            'free_space_bytes' => $freeSpace,
            'usage_percentage' => round(($usedSpace / $totalSpace) * 100, 2)
        ];
    }

    /**
     * Get response time metrics from logs
     */
    private function getResponseTimeMetrics(): array
    {
        // This would typically integrate with your logging system
        // For now, return cached data or defaults
        return Cache::get('response_time_metrics', [
            'average_response_time' => 0,
            'median_response_time' => 0,
            'p95_response_time' => 0,
            'p99_response_time' => 0,
            'total_requests' => 0,
            'slow_requests' => 0
        ]);
    }

    /**
     * Record response time
     */
    public function recordResponseTime(float $responseTime, string $endpoint = ''): void
    {
        try {
            $key = 'response_times:' . date('Y-m-d-H'); // Hourly buckets
            $times = Cache::get($key, []);

            $times[] = [
                'time' => $responseTime,
                'endpoint' => $endpoint,
                'timestamp' => microtime(true)
            ];

            // Keep only last 1000 requests per hour
            if (count($times) > 1000) {
                $times = array_slice($times, -1000);
            }

            Cache::put($key, $times, 3600); // 1 hour

            // Update aggregated metrics
            $this->updateAggregatedMetrics($times);

        } catch (\Exception $e) {
            Log::error('Failed to record response time: ' . $e->getMessage());
        }
    }

    /**
     * Update aggregated response time metrics
     */
    private function updateAggregatedMetrics(array $times): void
    {
        if (empty($times)) return;

        $responseTimes = array_column($times, 'time');
        sort($responseTimes);

        $count = count($responseTimes);
        $average = array_sum($responseTimes) / $count;
        $median = $responseTimes[intval($count / 2)];
        $p95 = $responseTimes[intval($count * 0.95)];
        $p99 = $responseTimes[intval($count * 0.99)];
        $slowRequests = count(array_filter($responseTimes, fn($time) => $time > 1000));

        Cache::put('response_time_metrics', [
            'average_response_time' => round($average, 2),
            'median_response_time' => round($median, 2),
            'p95_response_time' => round($p95, 2),
            'p99_response_time' => round($p99, 2),
            'total_requests' => $count,
            'slow_requests' => $slowRequests
        ], 3600);
    }

    /**
     * Get system health status
     */
    public function getHealthStatus(): array
    {
        $metrics = $this->collectMetrics();
        $status = 'healthy';
        $issues = [];

        // Check database health
        if (isset($metrics['database']['performance']['cache_hit_ratio'])) {
            if ($metrics['database']['performance']['cache_hit_ratio'] < 95) {
                $issues[] = 'Low database cache hit ratio';
                $status = 'warning';
            }
        }

        // Check memory usage
        if (isset($metrics['memory']['usage_percentage'])) {
            if ($metrics['memory']['usage_percentage'] > 80) {
                $issues[] = 'High memory usage';
                $status = 'warning';
            }
            if ($metrics['memory']['usage_percentage'] > 95) {
                $status = 'critical';
            }
        }

        // Check storage usage
        if (isset($metrics['storage']['usage_percentage'])) {
            if ($metrics['storage']['usage_percentage'] > 85) {
                $issues[] = 'High disk usage';
                $status = 'warning';
            }
            if ($metrics['storage']['usage_percentage'] > 95) {
                $status = 'critical';
            }
        }

        // Check response times
        if (isset($metrics['response_times']['average_response_time'])) {
            if ($metrics['response_times']['average_response_time'] > 1000) {
                $issues[] = 'High average response time';
                $status = 'warning';
            }
        }

        return [
            'status' => $status,
            'issues' => $issues,
            'timestamp' => now(),
            'metrics_summary' => [
                'memory_usage' => $metrics['memory']['usage_percentage'] ?? 0,
                'disk_usage' => $metrics['storage']['usage_percentage'] ?? 0,
                'avg_response_time' => $metrics['response_times']['average_response_time'] ?? 0,
                'cache_hit_rate' => $metrics['cache']['hit_rate'] ?? 0
            ]
        ];
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Calculate memory usage percentage
     */
    private function getMemoryUsagePercentage(int $usage, string $limit): float
    {
        $limitBytes = $this->parseMemoryLimit($limit);

        if ($limitBytes <= 0) return 0;

        return round(($usage / $limitBytes) * 100, 2);
    }

    /**
     * Parse memory limit string to bytes
     */
    private function parseMemoryLimit(string $limit): int
    {
        if ($limit === '-1') return PHP_INT_MAX;

        $value = (int) $limit;
        $unit = strtolower(substr($limit, -1));

        switch ($unit) {
            case 'g': return $value * 1024 * 1024 * 1024;
            case 'm': return $value * 1024 * 1024;
            case 'k': return $value * 1024;
            default: return $value;
        }
    }

    /**
     * Alert on performance issues
     */
    public function checkForAlerts(): array
    {
        $health = $this->getHealthStatus();
        $alerts = [];

        if ($health['status'] === 'critical') {
            $alerts[] = [
                'level' => 'critical',
                'message' => 'System is in critical state',
                'issues' => $health['issues'],
                'timestamp' => now()
            ];

            // Log critical issues
            Log::critical('System in critical state', $health);
        } elseif ($health['status'] === 'warning') {
            $alerts[] = [
                'level' => 'warning',
                'message' => 'System performance warnings detected',
                'issues' => $health['issues'],
                'timestamp' => now()
            ];

            // Log warnings
            Log::warning('System performance warnings', $health);
        }

        return $alerts;
    }
}
