<?php

namespace App\Console\Commands;

use App\Services\RevenueCalculationService;
use Illuminate\Console\Command;

class ClearRevenueCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revenue:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear revenue calculation cache';

    /**
     * Execute the console command.
     */
    public function handle(RevenueCalculationService $revenueService)
    {
        $this->info('Clearing revenue cache...');

        $revenueService->clearCache();

        $this->info('Revenue cache cleared successfully!');

        // Show current revenue streams (separated income sources)
        $this->info('Current Revenue Streams:');
        $streams = $revenueService->getRevenueStreams();

        $this->table(
            ['Income Source', 'Amount', 'Description'],
            [
                [
                    'Registration',
                    'Rp ' . number_format($streams['registration_revenue']['amount'], 0, ',', '.'),
                    $streams['registration_revenue']['description']
                ],
                [
                    'Bills/SPP',
                    'Rp ' . number_format($streams['bill_revenue']['amount'], 0, ',', '.'),
                    $streams['bill_revenue']['description']
                ],
                [
                    'Uang Pangkal',
                    'Rp ' . number_format($streams['uang_pangkal_revenue']['amount'], 0, ',', '.'),
                    $streams['uang_pangkal_revenue']['description']
                ]
            ]
        );

        // Show transaction counts
        $regStats = $revenueService->getRegistrationStats();
        $billStats = $revenueService->getBillStats();
        $uangPangkalStats = $revenueService->getUangPangkalStats();

        $this->info('Transaction Summary:');
        $this->table(
            ['Type', 'Paid', 'Pending', 'Failed', 'Total'],
            [
                [
                    'Registration',
                    $regStats['paid_transactions'],
                    $regStats['pending_transactions'],
                    $regStats['failed_transactions'],
                    $regStats['total_transactions']
                ],
                [
                    'Bills/SPP',
                    $billStats['paid_transactions'],
                    $billStats['pending_transactions'],
                    $billStats['failed_transactions'],
                    $billStats['total_transactions']
                ],
                [
                    'Uang Pangkal',
                    $uangPangkalStats['paid_transactions'],
                    $uangPangkalStats['pending_transactions'],
                    $uangPangkalStats['failed_transactions'],
                    $uangPangkalStats['total_transactions']
                ]
            ]
        );
    }
}
