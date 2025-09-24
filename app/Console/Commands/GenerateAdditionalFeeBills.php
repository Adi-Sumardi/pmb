<?php

namespace App\Console\Commands;

use App\Models\Pendaftar;
use App\Models\StudentBill;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateAdditionalFeeBills extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'additional-fees:generate-bills
                            {--academic-year=2025/2026 : Academic year to generate bills for}
                            {--fee-types=uniform,books,activity : Comma-separated fee types to generate}
                            {--dry-run : Show what would be generated without actually creating}';

    /**
     * The console command description.
     */
    protected $description = 'Generate additional fee bills (seragam, buku, kegiatan, dll) for students';

    /**
     * Fee type configurations with amounts per school level
     * Note: bill_type must match database enum constraint
     */
    private array $feeConfigurations = [
        'uniform' => [
            'name' => 'Seragam Sekolah',
            'description' => 'Paket seragam lengkap',
            'amounts' => [
                'sanggar' => 300000,
                'kelompok' => 350000,
                'tka' => 400000,
                'tkb' => 400000,
                'sd' => 450000,
                'smp' => 500000,
                'sma' => 550000
            ],
            'required' => true,
            'due_months_from_now' => 1
        ],
        'books' => [
            'name' => 'Buku Pelajaran',
            'description' => 'Paket buku pelajaran satu tahun',
            'amounts' => [
                'sanggar' => 200000,
                'kelompok' => 250000,
                'tka' => 300000,
                'tkb' => 300000,
                'sd' => 400000,
                'smp' => 500000,
                'sma' => 600000
            ],
            'required' => true,
            'due_months_from_now' => 1
        ],
        'activity' => [
            'name' => 'Biaya Kegiatan',
            'description' => 'Biaya kegiatan ekstrakurikuler dan acara sekolah',
            'amounts' => [
                'sanggar' => 150000,
                'kelompok' => 200000,
                'tka' => 250000,
                'tkb' => 250000,
                'sd' => 300000,
                'smp' => 400000,
                'sma' => 500000
            ],
            'required' => false,
            'due_months_from_now' => 2
        ],
        'supplies' => [
            'name' => 'Alat Tulis',
            'description' => 'Paket alat tulis dan perlengkapan sekolah',
            'amounts' => [
                'sanggar' => 100000,
                'kelompok' => 120000,
                'tka' => 150000,
                'tkb' => 150000,
                'sd' => 200000,
                'smp' => 250000,
                'sma' => 300000
            ],
            'required' => false,
            'due_months_from_now' => 1
        ],
        'other' => [
            'name' => 'Biaya Lainnya',
            'description' => 'Biaya lain-lain (study tour, dll)',
            'amounts' => [
                'sanggar' => 200000,
                'kelompok' => 300000,
                'tka' => 400000,
                'tkb' => 400000,
                'sd' => 500000,
                'smp' => 750000,
                'sma' => 1000000
            ],
            'required' => false,
            'due_months_from_now' => 3
        ]
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $academicYear = $this->option('academic-year');
        $feeTypesInput = $this->option('fee-types');
        $isDryRun = $this->option('dry-run');

        $feeTypes = array_map('trim', explode(',', $feeTypesInput));

        $this->info("📚 Generating Additional Fee Bills for Academic Year: {$academicYear}");
        $this->info("💼 Fee Types: " . implode(', ', $feeTypes));

        if ($isDryRun) {
            $this->warn("🔍 DRY RUN MODE - No bills will be created");
        }

        // Validate fee types
        $invalidTypes = array_diff($feeTypes, array_keys($this->feeConfigurations));
        if (!empty($invalidTypes)) {
            $this->error('❌ Invalid fee types: ' . implode(', ', $invalidTypes));
            $this->info('Available types: ' . implode(', ', array_keys($this->feeConfigurations)));
            return 1;
        }

        // Get all active students
        $activeStudents = Pendaftar::where('sudah_bayar_formulir', true)
            ->where('overall_status', '!=', 'Draft')
            ->with('user')
            ->get();

        if ($activeStudents->isEmpty()) {
            $this->error('❌ No active students found');
            return 1;
        }

        $this->info("👥 Found {$activeStudents->count()} active students");

        $totalGenerated = 0;
        $skippedExisting = 0;

        // Progress bar
        $totalOperations = $activeStudents->count() * count($feeTypes);
        $progressBar = $this->output->createProgressBar($totalOperations);
        $progressBar->start();

        DB::transaction(function () use ($activeStudents, $feeTypes, $academicYear, $isDryRun, &$totalGenerated, &$skippedExisting, $progressBar) {
            foreach ($activeStudents as $student) {
                $schoolLevel = $this->determineSchoolLevel($student);

                foreach ($feeTypes as $feeType) {
                    $progressBar->advance();

                    $config = $this->feeConfigurations[$feeType];
                    $amount = $config['amounts'][$schoolLevel] ?? $config['amounts']['sd']; // Default to SD

                    // Check if bill already exists
                    $existingBill = StudentBill::where('pendaftar_id', $student->id)
                        ->where('bill_type', $feeType)
                        ->where('academic_year', $academicYear)
                        ->first();

                    if ($existingBill) {
                        $skippedExisting++;
                        continue;
                    }

                    $dueDate = Carbon::now()->addMonths($config['due_months_from_now']);

                    if (!$isDryRun) {
                        StudentBill::create([
                            'pendaftar_id' => $student->id,
                            'bill_type' => $feeType,
                            'description' => "{$config['name']} - {$student->nama_murid}",
                            'total_amount' => $amount,
                            'paid_amount' => 0,
                            'remaining_amount' => $amount,
                            'due_date' => $dueDate,
                            'academic_year' => $academicYear,
                            'payment_status' => 'pending',
                            'notes' => $config['description'],
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
        $this->info("✅ Additional Fee Bills Generation Summary:");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Students', $activeStudents->count()],
                ['Fee Types', count($feeTypes)],
                ['Bills Generated', $totalGenerated],
                ['Skipped (Existing)', $skippedExisting]
            ]
        );

        // Show breakdown by fee type and level
        if (!$isDryRun && $totalGenerated > 0) {
            $this->info("📊 Fee Breakdown:");
            $breakdown = [];

            foreach ($feeTypes as $feeType) {
                $config = $this->feeConfigurations[$feeType];
                $totalAmount = 0;
                $studentCount = 0;

                foreach ($activeStudents as $student) {
                    $schoolLevel = $this->determineSchoolLevel($student);
                    $amount = $config['amounts'][$schoolLevel] ?? $config['amounts']['sd'];
                    $totalAmount += $amount;
                    $studentCount++;
                }

                $breakdown[] = [
                    'Fee Type' => $config['name'],
                    'Students' => $studentCount,
                    'Total Amount' => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
                    'Required' => $config['required'] ? 'Yes' : 'No'
                ];
            }

            $this->table(
                ['Fee Type', 'Students', 'Total Amount', 'Required'],
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

        // Map jenjang to school_level
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
