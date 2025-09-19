<?php

namespace App\Console\Commands;

use App\Services\DatabaseOptimizationService;
use Illuminate\Console\Command;

class RefreshMaterializedViews extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:refresh-views';

    /**
     * The console command description.
     */
    protected $description = 'Refresh all materialized views';

    public function __construct(
        private DatabaseOptimizationService $databaseService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Refreshing materialized views...');

        try {
            $this->databaseService->refreshMaterializedViews();
            $this->info('✅ Materialized views refreshed successfully');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to refresh materialized views: ' . $e->getMessage());
            return 1;
        }
    }
}