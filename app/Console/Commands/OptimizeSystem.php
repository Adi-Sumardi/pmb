<?php

namespace App\Console\Commands;

use App\Services\CacheOptimizationService;
use App\Services\DatabaseOptimizationService;
use App\Services\FileChunkingService;
use App\Services\PerformanceMonitoringService;
use Illuminate\Console\Command;

class OptimizeSystem extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'system:optimize
                            {--cache : Optimize cache}
                            {--database : Optimize database}
                            {--files : Clean up old files}
                            {--all : Run all optimizations}';

    /**
     * The console command description.
     */
    protected $description = 'Optimize system performance and clean up resources';

    public function __construct(
        private CacheOptimizationService $cacheService,
        private DatabaseOptimizationService $databaseService,
        private FileChunkingService $fileService,
        private PerformanceMonitoringService $performanceService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting system optimization...');

        $runAll = $this->option('all');

        if ($runAll || $this->option('cache')) {
            $this->optimizeCache();
        }

        if ($runAll || $this->option('database')) {
            $this->optimizeDatabase();
        }

        if ($runAll || $this->option('files')) {
            $this->cleanupFiles();
        }

        if ($runAll) {
            $this->warmupSystem();
            $this->generateReport();
        }

        $this->info('✅ System optimization completed!');

        return 0;
    }

    /**
     * Optimize cache system
     */
    private function optimizeCache(): void
    {
        $this->info('🔄 Optimizing cache system...');

        // Clear old caches
        $this->cacheService->clearAllCaches();
        $this->line('   - Cleared old caches');

        // Warm up critical caches
        $this->cacheService->warmUpCaches();
        $this->line('   - Warmed up critical caches');

        // Get cache statistics
        $stats = $this->cacheService->getCacheStats();
        if (!isset($stats['error'])) {
            $this->line("   - Cache hit rate: {$stats['hit_rate']}%");
            $this->line("   - Memory usage: {$stats['used_memory']}");
        }

        $this->info('✅ Cache optimization completed');
    }

    /**
     * Optimize database
     */
    private function optimizeDatabase(): void
    {
        $this->info('🔄 Optimizing database...');

        // Create optimization indexes
        $indexes = $this->databaseService->createOptimizationIndexes();
        if (!empty($indexes)) {
            $this->line('   - Created indexes: ' . implode(', ', $indexes));
        }

        // Optimize PostgreSQL configuration
        $optimizations = $this->databaseService->optimizePostgreSQLConfig();
        foreach ($optimizations as $optimization) {
            $this->line("   - {$optimization}");
        }

        // Create materialized views
        $views = $this->databaseService->createReportingViews();
        if (!empty($views)) {
            $this->line('   - Created materialized views: ' . implode(', ', $views));
        }

        // Refresh materialized views
        $this->databaseService->refreshMaterializedViews();
        $this->line('   - Refreshed materialized views');

        $this->info('✅ Database optimization completed');
    }

    /**
     * Clean up old files
     */
    private function cleanupFiles(): void
    {
        $this->info('🔄 Cleaning up old files...');

        // Clean up old chunked uploads
        $cleaned = $this->fileService->cleanupOldUploads(24); // 24 hours old
        $this->line("   - Cleaned {$cleaned} old upload chunks");

        // Clean up Laravel logs (keep last 30 days)
        $this->call('log:clear', ['--days' => 30]);
        $this->line('   - Cleaned old log files');

        // Clear compiled views and routes
        $this->call('view:clear');
        $this->call('route:clear');
        $this->call('config:clear');
        $this->line('   - Cleared compiled files');

        $this->info('✅ File cleanup completed');
    }

    /**
     * Warm up system
     */
    private function warmupSystem(): void
    {
        $this->info('🔥 Warming up system...');

        // Cache configurations
        $this->call('config:cache');
        $this->line('   - Cached configuration');

        // Cache routes
        $this->call('route:cache');
        $this->line('   - Cached routes');

        // Cache views
        $this->call('view:cache');
        $this->line('   - Cached views');

        // Cache events
        $this->call('event:cache');
        $this->line('   - Cached events');

        $this->info('✅ System warmup completed');
    }

    /**
     * Generate optimization report
     */
    private function generateReport(): void
    {
        $this->info('📊 Generating optimization report...');

        $health = $this->performanceService->getHealthStatus();
        $metrics = $this->performanceService->collectMetrics();

        $this->newLine();
        $this->line('=== SYSTEM HEALTH REPORT ===');
        $this->line("Overall Status: {$health['status']}");

        if (!empty($health['issues'])) {
            $this->line('Issues Found:');
            foreach ($health['issues'] as $issue) {
                $this->line("  - {$issue}");
            }
        }

        $this->newLine();
        $this->line('=== PERFORMANCE METRICS ===');

        if (isset($metrics['memory'])) {
            $this->line("Memory Usage: {$metrics['memory']['usage']} ({$metrics['memory']['usage_percentage']}%)");
        }

        if (isset($metrics['storage'])) {
            $this->line("Disk Usage: {$metrics['storage']['used_space']} ({$metrics['storage']['usage_percentage']}%)");
        }

        if (isset($metrics['database']['connections'])) {
            $conn = $metrics['database']['connections'];
            $this->line("DB Connections: {$conn['active']} active, {$conn['idle']} idle");
        }

        if (isset($metrics['cache']['hit_rate'])) {
            $this->line("Cache Hit Rate: {$metrics['cache']['hit_rate']}%");
        }

        $this->newLine();
        $this->info('✅ Report generation completed');
    }
}
