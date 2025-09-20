<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discounts = [
            [
                'name' => 'Diskon Early Bird',
                'code' => 'EARLY2025',
                'type' => 'percentage',
                'value' => 15.00,
                'target' => 'all',
                'minimum_amount' => 500000.00,
                'max_usage' => 100,
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
                'status' => 'active',
                'description' => 'Diskon khusus untuk pendaftar awal'
            ],
            [
                'name' => 'Diskon Siswa Berprestasi',
                'code' => 'PRESTASI50',
                'type' => 'fixed',
                'value' => 250000.00,
                'target' => 'uang_pangkal',
                'minimum_amount' => null,
                'max_usage' => 50,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => 'active',
                'description' => 'Diskon nominal untuk siswa berprestasi'
            ],
            [
                'name' => 'Diskon Alumni',
                'code' => 'ALUMNI10',
                'type' => 'percentage',
                'value' => 10.00,
                'target' => 'all',
                'minimum_amount' => 300000.00,
                'max_usage' => null,
                'start_date' => now(),
                'end_date' => null,
                'status' => 'active',
                'description' => 'Diskon khusus untuk anak alumni'
            ],
            [
                'name' => 'Diskon Program Beasiswa',
                'code' => 'BEASISWA50',
                'type' => 'percentage',
                'value' => 50.00,
                'target' => 'spp',
                'minimum_amount' => null,
                'max_usage' => 20,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => 'active',
                'description' => 'Diskon besar untuk program beasiswa'
            ],
            [
                'name' => 'Diskon Ramadan 2024',
                'code' => 'RAMADAN8',
                'type' => 'percentage',
                'value' => 8.00,
                'target' => 'multi_payment',
                'minimum_amount' => 200000.00,
                'max_usage' => 200,
                'start_date' => now()->subMonths(6),
                'end_date' => now()->subMonths(4),
                'status' => 'inactive',
                'description' => 'Diskon spesial bulan Ramadan tahun lalu'
            ],
            [
                'name' => 'Diskon Keluarga Besar',
                'code' => 'KELUARGA500',
                'type' => 'fixed',
                'value' => 500000.00,
                'target' => 'all',
                'minimum_amount' => 1000000.00,
                'max_usage' => 25,
                'start_date' => now(),
                'end_date' => now()->addMonths(12),
                'status' => 'active',
                'description' => 'Diskon untuk keluarga dengan banyak anak'
            ],
            [
                'name' => 'Diskon Guru',
                'code' => 'GURU12',
                'type' => 'percentage',
                'value' => 12.00,
                'target' => 'uang_pangkal',
                'minimum_amount' => 250000.00,
                'max_usage' => null,
                'start_date' => now(),
                'end_date' => null,
                'status' => 'active',
                'description' => 'Diskon khusus untuk guru dan tenaga pendidik'
            ],
            [
                'name' => 'Diskon Tahun Ajaran Baru',
                'code' => 'TAHUNAN350',
                'type' => 'fixed',
                'value' => 350000.00,
                'target' => 'spp',
                'minimum_amount' => 800000.00,
                'max_usage' => 100,
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'status' => 'active',
                'description' => 'Diskon khusus penyambutan tahun ajaran baru'
            ]
        ];

        foreach ($discounts as $discount) {
            \App\Models\Discount::create($discount);
        }
    }
}
