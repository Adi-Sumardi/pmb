<?php

namespace App\Console\Commands;

use App\Models\Pendaftar;
use App\Models\UangPangkalSetting;
use App\Models\StudentBill;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateUangPangkalBills extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'uang-pangkal:generate-bills
                            {--academic-year=2025/2026 : Academic year to generate bills for}
                            {--due-date=2025-07-15 : Due date for uang pangkal payment}
                            {--dry-run : Show what would be generated without actually creating}';

    /**
     * The console command description.
     */
    protected $description = 'Generate uang pangkal bills for all active students';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $academicYear = $this->option('academic-year');
        $dueDate = Carbon::parse($this->option('due-date'));
        $isDryRun = $this->option('dry-run');

        $this->info("💰 Generating Uang Pangkal Bills for Academic Year: {$academicYear}");
        $this->info("📅 Due Date: {$dueDate->format('d F Y')}");

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

        // Get all active Uang Pangkal settings
        $upSettings = UangPangkalSetting::active()
            ->where('academic_year', $academicYear)
            ->get()
            ->keyBy('school_level');

        if ($upSettings->isEmpty()) {
            $this->error('❌ No active Uang Pangkal settings found for academic year ' . $academicYear);
            return 1;
        }

        $this->info("⚙️ Found Uang Pangkal settings for: " . $upSettings->keys()->implode(', '));

        $totalGenerated = 0;
        $skippedExisting = 0;

        // Progress bar
        $progressBar = $this->output->createProgressBar($activeStudents->count());
        $progressBar->start();

        DB::transaction(function () use ($activeStudents, $upSettings, $academicYear, $dueDate, $isDryRun, &$totalGenerated, &$skippedExisting, $progressBar) {
            foreach ($activeStudents as $student) {
                $progressBar->advance();

                // Determine student's school level
                $schoolLevel = $this->determineSchoolLevel($student);
                $upSetting = $upSettings->get($schoolLevel);

                if (!$upSetting) {
                    $this->warn("⚠️ No Uang Pangkal setting found for {$student->nama_murid} (level: {$schoolLevel})");
                    continue;
                }

                // Check if bill already exists
                $existingBill = StudentBill::where('pendaftar_id', $student->id)
                    ->where('bill_type', 'uang_pangkal')
                    ->where('academic_year', $academicYear)
                    ->first();

                if ($existingBill) {
                    $skippedExisting++;
                    continue;
                }

                if (!$isDryRun) {
                    StudentBill::create([
                        'pendaftar_id' => $student->id,
                        'uang_pangkal_setting_id' => $upSetting->id,
                        'bill_type' => 'uang_pangkal',
                        'description' => "Uang Pangkal {$academicYear} - {$student->nama_murid}",
                        'total_amount' => $upSetting->amount,
                        'paid_amount' => 0,
                        'remaining_amount' => $upSetting->amount,
                        'due_date' => $dueDate,
                        'academic_year' => $academicYear,
                        'allow_installments' => $upSetting->allow_installments,
                        'total_installments' => $upSetting->max_installments,
                        'paid_installments' => 0,
                        'installment_amount' => $upSetting->allow_installments ?
                            round($upSetting->amount / $upSetting->max_installments, 0) : null,
                        'payment_status' => 'pending',
                        'notes' => "Uang Pangkal untuk {$upSetting->name}",
                        'issued_at' => now()
                    ]);
                }

                $totalGenerated++;
            }
        });

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info("✅ Uang Pangkal Bills Generation Summary:");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Students', $activeStudents->count()],
                ['Bills Generated', $totalGenerated],
                ['Skipped (Existing)', $skippedExisting],
                ['UP Settings Used', $upSettings->count()]
            ]
        );

        // Show breakdown by school level
        if (!$isDryRun && $totalGenerated > 0) {
            $this->info("📊 Uang Pangkal Breakdown by Level:");
            $breakdown = [];
            foreach ($upSettings as $level => $setting) {
                $studentCount = $activeStudents->filter(function($student) use ($level) {
                    return $this->determineSchoolLevel($student) === $level;
                })->count();

                if ($studentCount > 0) {
                    $breakdown[] = [
                        'Level' => strtoupper($level),
                        'Students' => $studentCount,
                        'Amount per Student' => 'Rp ' . number_format((float) $setting->amount, 0, ',', '.'),
                        'Total Amount' => 'Rp ' . number_format((float) $setting->amount * $studentCount, 0, ',', '.'),
                        'Max Installments' => $setting->max_installments
                    ];
                }
            }

            $this->table(
                ['Level', 'Students', 'Amount per Student', 'Total Amount', 'Max Installments'],
                $breakdown
            );
        }

        if ($isDryRun) {
            $this->info("🔍 This was a dry run. Use without --dry-run to actually create bills.");
        }

        return 0;
    }

    /**
     * Determine school level from student data
     */
    private function determineSchoolLevel(Pendaftar $student): string
    {
        $jenjang = strtolower($student->jenjang);
        $unit = strtolower($student->unit ?? '');

        // Map jenjang to school_level used in UangPangkalSetting
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
