<?php

namespace App\Console\Commands;

use App\Services\PerformanceMonitoringService;
use App\Services\SecureLoggingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MonitorSystem extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'system:monitor
                            {--alert : Send alerts if issues found}
                            {--report : Generate monitoring report}';

    /**
     * The console command description.
     */
    protected $description = 'Monitor system health and performance';

    public function __construct(
        private PerformanceMonitoringService $performanceService,
        private SecureLoggingService $loggingService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Starting system monitoring...');

        $health = $this->performanceService->getHealthStatus();
        $metrics = $this->performanceService->collectMetrics();
        $alerts = $this->performanceService->checkForAlerts();

        $this->displayHealthStatus($health);

        if ($this->option('report')) {
            $this->generateDetailedReport($metrics, $health);
        }

        if (!empty($alerts) && $this->option('alert')) {
            $this->handleAlerts($alerts);
        }

        // Log monitoring results
        $this->loggingService->logUserActivity('system_monitoring', null, [
            'status' => $health['status'],
            'issues_count' => count($health['issues']),
            'alerts_count' => count($alerts)
        ]);

        $this->info('✅ System monitoring completed');

        return $health['status'] === 'healthy' ? 0 : 1;
    }

    /**
     * Display health status
     */
    private function displayHealthStatus(array $health): void
    {
        $statusColor = match($health['status']) {
            'healthy' => 'info',
            'warning' => 'warn',
            'critical' => 'error',
            default => 'line'
        };

        $this->newLine();
        $this->line('=== SYSTEM HEALTH STATUS ===');
        $this->{$statusColor}("Status: {$health['status']}");

        if (!empty($health['issues'])) {
            $this->warn('Issues Found:');
            foreach ($health['issues'] as $issue) {
                $this->line("  ⚠️  {$issue}");
            }
        } else {
            $this->info('  ✅ No issues detected');
        }

        $this->newLine();
        $this->line('=== QUICK METRICS ===');
        $metrics = $health['metrics_summary'];

        $this->displayMetric('Memory Usage', $metrics['memory_usage'], '%', 80, 95);
        $this->displayMetric('Disk Usage', $metrics['disk_usage'], '%', 85, 95);
        $this->displayMetric('Avg Response Time', $metrics['avg_response_time'], 'ms', 500, 1000);
        $this->displayMetric('Cache Hit Rate', $metrics['cache_hit_rate'], '%', 90, 95, true);
    }

    /**
     * Display metric with color coding
     */
    private function displayMetric(string $name, float $value, string $unit, float $warningThreshold, float $criticalThreshold, bool $higherIsBetter = false): void
    {
        $formatted = number_format($value, 2) . $unit;

        if ($higherIsBetter) {
            if ($value >= $criticalThreshold) {
                $this->info("  {$name}: {$formatted} ✅");
            } elseif ($value >= $warningThreshold) {
                $this->warn("  {$name}: {$formatted} ⚠️");
            } else {
                $this->error("  {$name}: {$formatted} ❌");
            }
        } else {
            if ($value >= $criticalThreshold) {
                $this->error("  {$name}: {$formatted} ❌");
            } elseif ($value >= $warningThreshold) {
                $this->warn("  {$name}: {$formatted} ⚠️");
            } else {
                $this->info("  {$name}: {$formatted} ✅");
            }
        }
    }

    /**
     * Generate detailed monitoring report
     */
    private function generateDetailedReport(array $metrics, array $health): void
    {
        $this->newLine();
        $this->line('=== DETAILED SYSTEM REPORT ===');

        // Database metrics
        if (isset($metrics['database'])) {
            $this->line('Database:');
            $db = $metrics['database'];

            if (isset($db['connections'])) {
                $conn = $db['connections'];
                $this->line("  - Connections: {$conn['total']} total, {$conn['active']} active");
            }

            if (isset($db['performance'])) {
                $perf = $db['performance'];
                $this->line("  - Slow Queries: {$perf['slow_queries']}");
                $this->line("  - Cache Hit Ratio: {$perf['cache_hit_ratio']}%");
            }

            if (isset($db['storage'])) {
                $storage = $db['storage'];
                $this->line("  - Database Size: {$storage['database_size']}");
            }
        }

        // Cache metrics
        if (isset($metrics['cache'])) {
            $this->line('Cache:');
            $cache = $metrics['cache'];

            if (isset($cache['hit_rate'])) {
                $this->line("  - Hit Rate: {$cache['hit_rate']}%");
            }

            if (isset($cache['memory_usage'])) {
                $this->line("  - Memory Usage: {$cache['memory_usage']}");
            }

            if (isset($cache['connected_clients'])) {
                $this->line("  - Connected Clients: {$cache['connected_clients']}");
            }
        }

        // Memory metrics
        if (isset($metrics['memory'])) {
            $this->line('Memory:');
            $memory = $metrics['memory'];
            $this->line("  - Usage: {$memory['usage']} ({$memory['usage_percentage']}%)");
            $this->line("  - Peak Usage: {$memory['peak_usage']}");
            $this->line("  - Limit: {$memory['limit']}");
        }

        // Storage metrics
        if (isset($metrics['storage'])) {
            $this->line('Storage:');
            $storage = $metrics['storage'];
            $this->line("  - Used: {$storage['used_space']} ({$storage['usage_percentage']}%)");
            $this->line("  - Free: {$storage['free_space']}");
            $this->line("  - Total: {$storage['total_space']}");
        }

        // Response time metrics
        if (isset($metrics['response_times'])) {
            $this->line('Response Times:');
            $rt = $metrics['response_times'];
            $this->line("  - Average: {$rt['average_response_time']}ms");
            $this->line("  - Median: {$rt['median_response_time']}ms");
            $this->line("  - 95th Percentile: {$rt['p95_response_time']}ms");
            $this->line("  - 99th Percentile: {$rt['p99_response_time']}ms");
            $this->line("  - Total Requests: {$rt['total_requests']}");
            $this->line("  - Slow Requests: {$rt['slow_requests']}");
        }
    }

    /**
     * Handle system alerts
     */
    private function handleAlerts(array $alerts): void
    {
        $this->newLine();
        $this->line('=== SYSTEM ALERTS ===');

        foreach ($alerts as $alert) {
            $levelColor = $alert['level'] === 'critical' ? 'error' : 'warn';
            $icon = $alert['level'] === 'critical' ? '🚨' : '⚠️';

            $this->{$levelColor}("{$icon} {$alert['message']}");

            if (!empty($alert['issues'])) {
                foreach ($alert['issues'] as $issue) {
                    $this->line("    - {$issue}");
                }
            }
        }

        // Send email alerts if configured
        $alertEmail = config('monitoring.alert_email');
        if ($alertEmail) {
            $this->sendEmailAlert($alerts, $alertEmail);
        }
    }

    /**
     * Send email alert
     */
    private function sendEmailAlert(array $alerts, string $email): void
    {
        try {
            $criticalAlerts = array_filter($alerts, fn($alert) => $alert['level'] === 'critical');

            if (!empty($criticalAlerts)) {
                // In a real implementation, you would send an email here
                $this->line("  📧 Critical alert email would be sent to: {$email}");

                // Log the alert
                $this->loggingService->logSecurityEvent('critical_system_alert', [
                    'alerts_count' => count($criticalAlerts),
                    'recipient' => $email
                ]);
            }
        } catch (\Exception $e) {
            $this->error("Failed to send alert email: {$e->getMessage()}");
        }
    }
}