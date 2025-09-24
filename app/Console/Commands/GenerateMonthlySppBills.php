<?php

namespace App\Console\Commands;

use App\Models\Pendaftar;
use App\Models\SppSetting;
use App\Models\StudentBill;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateMonthlySppBills extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'spp:generate-monthly-bills
                            {--academic-year=2025/2026 : Academic year to generate bills for}
                            {--start-month=7 : Starting month (1-12)}
                            {--end-month=6 : Ending month (1-12)}
                            {--dry-run : Show what would be generated without actually creating}';

    /**
     * The console command description.
     */
    protected $description = 'Generate monthly SPP bills for all active students';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $academicYear = $this->option('academic-year');
        $startMonth = (int) $this->option('start-month');
        $endMonth = (int) $this->option('end-month');
        $isDryRun = $this->option('dry-run');

        $this->info("🎓 Generating SPP Bills for Academic Year: {$academicYear}");
        $this->info("📅 Period: Month {$startMonth} to {$endMonth}");

        if ($isDryRun) {
            $this->warn("🔍 DRY RUN MODE - No bills will be created");
        }

        // Get all active students (paid registration)
        $activeStudents = Pendaftar::where('sudah_bayar_formulir', true)
            ->where('overall_status', '!=', 'Draft')
            ->with('user')
            ->get();

        if ($activeStudents->isEmpty()) {
            $this->error('❌ No active students found');
            return 1;
        }

        $this->info("👥 Found {$activeStudents->count()} active students");

        // Get all active SPP settings
        $sppSettings = SppSetting::active()
            ->where('academic_year', $academicYear)
            ->get()
            ->keyBy('school_level');

        if ($sppSettings->isEmpty()) {
            $this->error('❌ No active SPP settings found for academic year ' . $academicYear);
            return 1;
        }

        $this->info("⚙️ Found SPP settings for: " . $sppSettings->keys()->implode(', '));

        // Generate month range
        $months = $this->generateMonthRange($startMonth, $endMonth, $academicYear);
        $this->info("📆 Generating {$months->count()} months of bills");

        $totalGenerated = 0;
        $skippedExisting = 0;

        // Progress bar
        $progressBar = $this->output->createProgressBar($activeStudents->count() * $months->count());
        $progressBar->start();

        DB::transaction(function () use ($activeStudents, $sppSettings, $months, $academicYear, $isDryRun, &$totalGenerated, &$skippedExisting, $progressBar) {
            foreach ($activeStudents as $student) {
                // Determine student's school level
                $schoolLevel = $this->determineSchoolLevel($student);
                $sppSetting = $sppSettings->get($schoolLevel);

                if (!$sppSetting) {
                    $this->warn("⚠️ No SPP setting found for {$student->nama_murid} (level: {$schoolLevel})");
                    continue;
                }

                foreach ($months as $monthData) {
                    $progressBar->advance();

                    // Check if bill already exists
                    $existingBill = StudentBill::where('pendaftar_id', $student->id)
                        ->where('bill_type', 'spp')
                        ->where('academic_year', $academicYear)
                        ->where('month', $monthData['month'])
                        ->first();

                    if ($existingBill) {
                        $skippedExisting++;
                        continue;
                    }

                    if (!$isDryRun) {
                        StudentBill::create([
                            'pendaftar_id' => $student->id,
                            'bill_type' => 'spp',
                            'description' => "SPP {$monthData['name']} - {$student->nama_murid}",
                            'total_amount' => $sppSetting->amount,
                            'paid_amount' => 0,
                            'remaining_amount' => $sppSetting->amount,
                            'due_date' => $monthData['due_date'],
                            'academic_year' => $academicYear,
                            'month' => $monthData['month'],
                            'payment_status' => 'pending',
                            'notes' => "SPP bulanan untuk {$sppSetting->name}",
                            'issued_at' => now()
                        ]);
                    }

                    $totalGenerated++;
                }
            }
        });

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info("✅ SPP Bills Generation Summary:");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Students', $activeStudents->count()],
                ['Total Months', $months->count()],
                ['Bills Generated', $totalGenerated],
                ['Skipped (Existing)', $skippedExisting],
                ['SPP Settings Used', $sppSettings->count()]
            ]
        );

        if ($isDryRun) {
            $this->info("🔍 This was a dry run. Use without --dry-run to actually create bills.");
        }

        return 0;
    }

    /**
     * Generate month range for academic year
     */
    private function generateMonthRange(int $startMonth, int $endMonth, string $academicYear): \Illuminate\Support\Collection
    {
        $months = collect();
        $currentYear = (int) substr($academicYear, 0, 4);

        // Handle academic year crossing calendar year
        if ($startMonth > $endMonth) {
            // July 2025 - June 2026
            for ($month = $startMonth; $month <= 12; $month++) {
                $months->push($this->getMonthData($month, $currentYear));
            }
            for ($month = 1; $month <= $endMonth; $month++) {
                $months->push($this->getMonthData($month, $currentYear + 1));
            }
        } else {
            // Same calendar year
            for ($month = $startMonth; $month <= $endMonth; $month++) {
                $months->push($this->getMonthData($month, $currentYear));
            }
        }

        return $months;
    }

    /**
     * Get month data with due date
     */
    private function getMonthData(int $month, int $year): array
    {
        $date = Carbon::create($year, $month, 1);

        return [
            'month' => $month,
            'year' => $year,
            'name' => $date->format('F Y'),
            'due_date' => Carbon::create($year, $month, 10) // Due on 10th of each month
        ];
    }

    /**
     * Determine school level from student data
     */
    private function determineSchoolLevel(Pendaftar $student): string
    {
        $jenjang = strtolower($student->jenjang);
        $unit = strtolower($student->unit ?? '');

        // Map jenjang to school_level used in SppSetting
        $levelMap = [
            'sanggar' => 'sanggar',
            'kelompok' => 'kelompok',
            'tka' => 'tka',
            'tk a' => 'tka',
            'tkb' => 'tkb',
            'tk b' => 'tkb',
            'sd' => 'sd',
            'smp' => 'smp',
            'sma' => 'sma'
        ];

        // Check jenjang first
        if (isset($levelMap[$jenjang])) {
            return $levelMap[$jenjang];
        }

        // Check unit for additional clues
        foreach ($levelMap as $key => $level) {
            if (str_contains($unit, $key)) {
                return $level;
            }
        }

        // Default fallback
        return 'sd';
    }
}
