<?php

namespace App\Http\Controllers;

use App\Services\CacheOptimizationService;
use App\Services\DatabaseOptimizationService;
use App\Services\PerformanceMonitoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class HealthCheckController extends Controller
{
    public function __construct(
        private PerformanceMonitoringService $performanceMonitoring,
        private CacheOptimizationService $cacheOptimization,
        private DatabaseOptimizationService $databaseOptimization
    ) {}

    /**
     * Basic health check endpoint
     */
    public function basic(): JsonResponse
    {
        return response()->json([
            'status' => 'healthy',
            'service' => 'PPDB YAPI Backend',
            'version' => config('app.version', '1.0.0'),
            'timestamp' => now(),
            'environment' => app()->environment()
        ]);
    }

    /**
     * Comprehensive health check
     */
    public function comprehensive(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'redis' => $this->checkRedis(),
            'queue' => $this->checkQueue()
        ];

        $overallStatus = $this->determineOverallStatus($checks);

        return response()->json([
            'status' => $overallStatus,
            'service' => 'PPDB YAPI Backend',
            'checks' => $checks,
            'timestamp' => now()
        ], $overallStatus === 'healthy' ? 200 : 503);
    }

    /**
     * Get system metrics
     */
    public function metrics(): JsonResponse
    {
        $metrics = $this->performanceMonitoring->collectMetrics();

        return response()->json([
            'metrics' => $metrics,
            'health_status' => $this->performanceMonitoring->getHealthStatus(),
            'cache_stats' => $this->cacheOptimization->getCacheStats(),
            'database_metrics' => $this->databaseOptimization->getDatabaseMetrics(),
            'timestamp' => now()
        ]);
    }

    /**
     * Performance dashboard data
     */
    public function dashboard(): JsonResponse
    {
        $health = $this->performanceMonitoring->getHealthStatus();
        $metrics = $this->performanceMonitoring->collectMetrics();

        return response()->json([
            'overview' => [
                'status' => $health['status'],
                'issues_count' => count($health['issues']),
                'uptime' => $this->getUptime(),
                'last_restart' => $this->getLastRestart()
            ],
            'performance' => [
                'memory_usage' => $health['metrics_summary']['memory_usage'],
                'disk_usage' => $health['metrics_summary']['disk_usage'],
                'avg_response_time' => $health['metrics_summary']['avg_response_time'],
                'cache_hit_rate' => $health['metrics_summary']['cache_hit_rate']
            ],
            'database' => [
                'connections' => $metrics['database']['connections'] ?? [],
                'performance' => $metrics['database']['performance'] ?? []
            ],
            'cache' => $metrics['cache'] ?? [],
            'alerts' => $this->performanceMonitoring->checkForAlerts(),
            'timestamp' => now()
        ]);
    }

    /**
     * Service readiness check
     */
    public function readiness(): JsonResponse
    {
        $readyChecks = [
            'database_ready' => $this->isDatabaseReady(),
            'cache_ready' => $this->isCacheReady(),
            'storage_ready' => $this->isStorageReady(),
            'config_loaded' => $this->isConfigLoaded()
        ];

        $allReady = !in_array(false, $readyChecks);

        return response()->json([
            'ready' => $allReady,
            'checks' => $readyChecks,
            'timestamp' => now()
        ], $allReady ? 200 : 503);
    }

    /**
     * Service liveness check
     */
    public function liveness(): JsonResponse
    {
        // Basic liveness - if this endpoint responds, the service is alive
        return response()->json([
            'alive' => true,
            'timestamp' => now()
        ]);
    }

    /**
     * Check database connectivity
     */
    private function checkDatabase(): array
    {
        try {
            $start = microtime(true);
            DB::select('SELECT 1');
            $responseTime = (microtime(true) - $start) * 1000;

            return [
                'status' => 'healthy',
                'response_time_ms' => round($responseTime, 2),
                'connection' => DB::getDefaultConnection()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check cache connectivity
     */
    private function checkCache(): array
    {
        try {
            $start = microtime(true);
            $testKey = 'health_check_' . time();
            Cache::put($testKey, 'test', 60);
            $retrieved = Cache::get($testKey);
            Cache::forget($testKey);
            $responseTime = (microtime(true) - $start) * 1000;

            return [
                'status' => $retrieved === 'test' ? 'healthy' : 'unhealthy',
                'response_time_ms' => round($responseTime, 2),
                'driver' => config('cache.default')
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check storage accessibility
     */
    private function checkStorage(): array
    {
        try {
            $testFile = 'health_check_' . time() . '.txt';
            Storage::put($testFile, 'health check');
            $exists = Storage::exists($testFile);
            Storage::delete($testFile);

            return [
                'status' => $exists ? 'healthy' : 'unhealthy',
                'disk' => config('filesystems.default'),
                'writable' => is_writable(storage_path())
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check Redis connectivity
     */
    private function checkRedis(): array
    {
        try {
            $start = microtime(true);
            $redis = Redis::connection();
            $redis->ping();
            $responseTime = (microtime(true) - $start) * 1000;

            return [
                'status' => 'healthy',
                'response_time_ms' => round($responseTime, 2),
                'connection' => 'default'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check queue connectivity
     */
    private function checkQueue(): array
    {
        try {
            $queueConnection = config('queue.default');
            return [
                'status' => 'healthy',
                'connection' => $queueConnection
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Determine overall status from individual checks
     */
    private function determineOverallStatus(array $checks): string
    {
        foreach ($checks as $check) {
            if ($check['status'] === 'unhealthy') {
                return 'unhealthy';
            }
        }
        return 'healthy';
    }

    /**
     * Check if database is ready
     */
    private function isDatabaseReady(): bool
    {
        try {
            DB::select('SELECT 1');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if cache is ready
     */
    private function isCacheReady(): bool
    {
        try {
            Cache::put('readiness_check', true, 10);
            return Cache::get('readiness_check') === true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if storage is ready
     */
    private function isStorageReady(): bool
    {
        return is_writable(storage_path());
    }

    /**
     * Check if configuration is loaded
     */
    private function isConfigLoaded(): bool
    {
        return !empty(config('app.key')) && !empty(config('database.default'));
    }

    /**
     * Get application uptime
     */
    private function getUptime(): array
    {
        $uptimeFile = storage_path('app/uptime.txt');

        if (!file_exists($uptimeFile)) {
            file_put_contents($uptimeFile, time());
        }

        $startTime = (int) file_get_contents($uptimeFile);
        $uptime = time() - $startTime;

        return [
            'seconds' => $uptime,
            'human' => $this->secondsToHuman($uptime),
            'started_at' => date('Y-m-d H:i:s', $startTime)
        ];
    }

    /**
     * Get last restart time
     */
    private function getLastRestart(): ?string
    {
        $restartFile = storage_path('app/last_restart.txt');

        if (file_exists($restartFile)) {
            $lastRestart = (int) file_get_contents($restartFile);
            return date('Y-m-d H:i:s', $lastRestart);
        }

        return null;
    }

    /**
     * Convert seconds to human readable format
     */
    private function secondsToHuman(int $seconds): string
    {
        $units = [
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1
        ];

        $result = [];

        foreach ($units as $unit => $value) {
            if ($seconds >= $value) {
                $count = intval($seconds / $value);
                $result[] = $count . ' ' . $unit . ($count > 1 ? 's' : '');
                $seconds %= $value;
            }
        }

        return implode(', ', $result) ?: '0 seconds';
    }

    /**
     * Mark application restart
     */
    public function markRestart(): JsonResponse
    {
        file_put_contents(storage_path('app/last_restart.txt'), time());

        return response()->json([
            'message' => 'Restart marked successfully',
            'timestamp' => now()
        ]);
    }
}