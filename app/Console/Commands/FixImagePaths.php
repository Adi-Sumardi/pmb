<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Storage;

class FixImagePaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:image-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix image paths from uploads/photos to uploads/foto_murid';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix image paths...');

        $pendaftars = Pendaftar::whereNotNull('foto_murid_path')
            ->where('foto_murid_path', 'LIKE', 'uploads/photos/%')
            ->get();

        $this->info("Found {$pendaftars->count()} records with old photo paths");

        $fixed = 0;
        $notFound = 0;

        foreach ($pendaftars as $pendaftar) {
            $oldPath = $pendaftar->foto_murid_path;
            $filename = basename($oldPath);
            $newPath = "uploads/foto_murid/{$filename}";

            // Check if the file exists in the new location
            if (Storage::disk('public')->exists($newPath)) {
                $pendaftar->update(['foto_murid_path' => $newPath]);
                $this->line("✅ Fixed: {$pendaftar->nama_murid} - {$oldPath} → {$newPath}");
                $fixed++;
            } else {
                $this->error("❌ File not found: {$newPath} for {$pendaftar->nama_murid}");
                $notFound++;
            }
        }

        $this->info("\n=== Summary ===");
        $this->info("Fixed: {$fixed} records");
        $this->error("Not found: {$notFound} records");

        return Command::SUCCESS;
    }
}
