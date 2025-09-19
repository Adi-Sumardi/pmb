<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled Tasks
Schedule::command('system:monitor --alert')->everyFiveMinutes()
    ->name('system-health-check')
    ->description('Monitor system health and send alerts');

Schedule::command('system:optimize --cache')->hourly()
    ->name('cache-optimization')
    ->description('Optimize cache performance');

Schedule::command('system:optimize --files')->daily()
    ->name('file-cleanup')
    ->description('Clean up old files and chunks');

Schedule::command('system:optimize --database')->weekly()
    ->name('database-optimization')
    ->description('Optimize database performance');

Schedule::command('system:optimize --all')->weekly()
    ->name('full-system-optimization')
    ->description('Complete system optimization');

Schedule::command('system:monitor --report')->daily()
    ->name('daily-monitoring-report')
    ->description('Generate daily monitoring report');

// Backup materialized views
Schedule::command('db:refresh-views')->daily()
    ->name('refresh-materialized-views')
    ->description('Refresh database materialized views');

// Clear old logs
Schedule::command('log:clear --days=30')->weekly()
    ->name('log-cleanup')
    ->description('Clear logs older than 30 days');
