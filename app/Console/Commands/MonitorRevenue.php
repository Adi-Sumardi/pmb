<?php

namespace App\Console\Commands;

use App\Services\RevenueCalculationService;
use Illuminate\Console\Command;

class MonitorRevenue extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'revenue:monitor
                            {--clear-cache : Clear revenue cache before monitoring}
                            {--export= : Export revenue data to file (csv, json)}';

    /**
     * The console command description.
     */
    protected $description = 'Monitor real-time revenue and payment status';

    protected RevenueCalculationService $revenueService;

    /**
     * Create a new command instance.
     */
    public function __construct(RevenueCalculationService $revenueService)
    {
        parent::__construct();
        $this->revenueService = $revenueService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $clearCache = $this->option('clear-cache');
        $exportFormat = $this->option('export');

        $this->info("💰 Revenue Monitoring System");
        $this->info("📅 Date: " . now()->format('Y-m-d H:i:s'));

        if ($clearCache) {
            $this->revenueService->clearCache();
            $this->info("🗑️  Revenue cache cleared");
        }

        // Get revenue streams
        $streams = $this->revenueService->getRevenueStreams();

        $this->info("\n💵 Current Revenue Streams:");

        $table = [];
        $totalRevenue = 0;

        foreach ($streams as $stream) {
            $table[] = [
                'Source' => $stream['source'],
                'Amount' => 'Rp ' . number_format($stream['amount'], 0, ',', '.'),
                'Description' => $stream['description']
            ];
            $totalRevenue += $stream['amount'];
        }

        $this->table(['Source', 'Amount', 'Description'], $table);

        $this->info("💎 Total Revenue: Rp " . number_format($totalRevenue, 0, ',', '.'));

        // Get detailed statistics
        $this->showDetailedStats();

        // Export if requested
        if ($exportFormat) {
            $this->exportRevenue($streams, $exportFormat);
        }

        return 0;
    }

    /**
     * Show detailed payment statistics
     */
    private function showDetailedStats(): void
    {
        $this->info("\n📊 Detailed Payment Statistics:");

        // Registration payments
        $registrationStats = $this->revenueService->getRegistrationStats();

        $this->info("\n🎯 Registration Payments:");
        $this->table(['Status', 'Count', 'Amount'], [
            ['PAID', $registrationStats['paid_count'], 'Rp ' . number_format($registrationStats['paid_amount'], 0, ',', '.')],
            ['PENDING', $registrationStats['pending_count'], 'Rp ' . number_format($registrationStats['pending_amount'], 0, ',', '.')],
            ['EXPIRED', $registrationStats['expired_count'], 'Rp ' . number_format($registrationStats['expired_amount'], 0, ',', '.')],
            ['TOTAL', $registrationStats['total_count'], 'Rp ' . number_format($registrationStats['total_amount'], 0, ',', '.')]
        ]);

        // Bill payments by type
        $billStats = $this->revenueService->getBillStats();

        $this->info("\n📋 Bill Payments by Type:");
        $billTable = [];
        foreach ($billStats as $type => $stats) {
            $billTable[] = [
                'Type' => ucfirst(str_replace('_', ' ', $type)),
                'Paid Count' => $stats['paid_count'],
                'Paid Amount' => 'Rp ' . number_format($stats['paid_amount'], 0, ',', '.'),
                'Pending Count' => $stats['pending_count'],
                'Pending Amount' => 'Rp ' . number_format($stats['pending_amount'], 0, ',', '.')
            ];
        }

        $this->table(['Type', 'Paid Count', 'Paid Amount', 'Pending Count', 'Pending Amount'], $billTable);
    }

    /**
     * Export revenue data
     */
    private function exportRevenue(array $streams, string $format): void
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "revenue_export_{$timestamp}.{$format}";
        $path = storage_path("app/exports/{$filename}");

        // Create exports directory if it doesn't exist
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        switch ($format) {
            case 'csv':
                $this->exportToCsv($streams, $path);
                break;
            case 'json':
                $this->exportToJson($streams, $path);
                break;
            default:
                $this->error("Unsupported export format: {$format}");
                return;
        }

        $this->info("📄 Revenue data exported to: {$path}");
    }

    /**
     * Export to CSV format
     */
    private function exportToCsv(array $streams, string $path): void
    {
        $handle = fopen($path, 'w');

        // Headers
        fputcsv($handle, ['Source', 'Amount', 'Description', 'Export Date']);

        // Data
        foreach ($streams as $stream) {
            fputcsv($handle, [
                $stream['source'],
                $stream['amount'],
                $stream['description'],
                now()->format('Y-m-d H:i:s')
            ]);
        }

        fclose($handle);
    }

    /**
     * Export to JSON format
     */
    private function exportToJson(array $streams, string $path): void
    {
        $data = [
            'export_date' => now()->format('Y-m-d H:i:s'),
            'total_revenue' => array_sum(array_column($streams, 'amount')),
            'streams' => $streams,
            'detailed_stats' => [
                'registration' => $this->revenueService->getRegistrationStats(),
                'bills' => $this->revenueService->getBillStats()
            ]
        ];

        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
    }
}
