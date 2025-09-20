<?php

namespace Database\Seeders;

use App\Models\SppSetting;
use Illuminate\Database\Seeder;

class SppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sppSettings = [
            // TK SPP Settings
            [
                'name' => 'SPP TK A (Internal)',
                'school_level' => 'tk',
                'school_origin' => 'internal',
                'amount' => 150000.00,
                'academic_year' => '2024/2025',
                'status' => 'active',
                'description' => 'SPP untuk siswa TK A yang berasal dari internal YAPI'
            ],
            [
                'name' => 'SPP TK A (External)',
                'school_level' => 'tk',
                'school_origin' => 'external',
                'amount' => 200000.00,
                'academic_year' => '2024/2025',
                'status' => 'active',
                'description' => 'SPP untuk siswa TK A yang berasal dari luar YAPI'
            ],
            [
                'name' => 'SPP TK B (Internal)',
                'school_level' => 'tk',
                'school_origin' => 'internal',
                'amount' => 175000.00,
                'academic_year' => '2025/2026',
                'status' => 'active',
                'description' => 'SPP untuk siswa TK B yang berasal dari internal YAPI'
            ],
            [
                'name' => 'SPP TK B (External)',
                'school_level' => 'tk',
                'school_origin' => 'external',
                'amount' => 225000.00,
                'academic_year' => '2025/2026',
                'status' => 'active',
                'description' => 'SPP untuk siswa TK B yang berasal dari luar YAPI'
            ],

            // SD SPP Settings
            [
                'name' => 'SPP SD Kelas 1-2 (Internal)',
                'school_level' => 'sd',
                'school_origin' => 'internal',
                'amount' => 300000.00,
                'academic_year' => '2024/2025',
                'status' => 'active',
                'description' => 'SPP untuk siswa SD kelas 1-2 yang berasal dari internal YAPI'
            ],
            [
                'name' => 'SPP SD Kelas 1-2 (External)',
                'school_level' => 'sd',
                'school_origin' => 'external',
                'amount' => 400000.00,
                'academic_year' => '2024/2025',
                'status' => 'active',
                'description' => 'SPP untuk siswa SD kelas 1-2 yang berasal dari luar YAPI'
            ],
            [
                'name' => 'SPP SD Kelas 3-4 (Internal)',
                'school_level' => 'sd',
                'school_origin' => 'internal',
                'amount' => 350000.00,
                'academic_year' => '2025/2026',
                'status' => 'active',
                'description' => 'SPP untuk siswa SD kelas 3-4 yang berasal dari internal YAPI'
            ],
            [
                'name' => 'SPP SD Kelas 3-4 (External)',
                'school_level' => 'sd',
                'school_origin' => 'external',
                'amount' => 450000.00,
                'academic_year' => '2025/2026',
                'status' => 'active',
                'description' => 'SPP untuk siswa SD kelas 3-4 yang berasal dari luar YAPI'
            ],
            [
                'name' => 'SPP SD Kelas 5-6 (Internal)',
                'school_level' => 'sd',
                'school_origin' => 'internal',
                'amount' => 400000.00,
                'academic_year' => '2023/2024',
                'status' => 'active',
                'description' => 'SPP untuk siswa SD kelas 5-6 yang berasal dari internal YAPI'
            ],
            [
                'name' => 'SPP SD Kelas 5-6 (External)',
                'school_level' => 'sd',
                'school_origin' => 'external',
                'amount' => 500000.00,
                'academic_year' => '2023/2024',
                'status' => 'active',
                'description' => 'SPP untuk siswa SD kelas 5-6 yang berasal dari luar YAPI'
            ],

            // SMP SPP Settings
            [
                'name' => 'SPP SMP Kelas 7 (Internal)',
                'school_level' => 'smp',
                'school_origin' => 'internal',
                'amount' => 500000.00,
                'academic_year' => '2024/2025',
                'status' => 'active',
                'description' => 'SPP untuk siswa SMP kelas 7 yang berasal dari internal YAPI'
            ],
            [
                'name' => 'SPP SMP Kelas 7 (External)',
                'school_level' => 'smp',
                'school_origin' => 'external',
                'amount' => 650000.00,
                'academic_year' => '2024/2025',
                'status' => 'active',
                'description' => 'SPP untuk siswa SMP kelas 7 yang berasal dari luar YAPI'
            ],
            [
                'name' => 'SPP SMP Kelas 8 (Internal)',
                'school_level' => 'smp',
                'school_origin' => 'internal',
                'amount' => 550000.00,
                'academic_year' => '2025/2026',
                'status' => 'active',
                'description' => 'SPP untuk siswa SMP kelas 8 yang berasal dari internal YAPI'
            ],
            [
                'name' => 'SPP SMP Kelas 8 (External)',
                'school_level' => 'smp',
                'school_origin' => 'external',
                'amount' => 700000.00,
                'academic_year' => '2025/2026',
                'status' => 'active',
                'description' => 'SPP untuk siswa SMP kelas 8 yang berasal dari luar YAPI'
            ],
            [
                'name' => 'SPP SMP Kelas 9 (Internal)',
                'school_level' => 'smp',
                'school_origin' => 'internal',
                'amount' => 600000.00,
                'academic_year' => '2023/2024',
                'status' => 'active',
                'description' => 'SPP untuk siswa SMP kelas 9 yang berasal dari internal YAPI'
            ],
            [
                'name' => 'SPP SMP Kelas 9 (External)',
                'school_level' => 'smp',
                'school_origin' => 'external',
                'amount' => 750000.00,
                'academic_year' => '2023/2024',
                'status' => 'active',
                'description' => 'SPP untuk siswa SMP kelas 9 yang berasal dari luar YAPI'
            ],

            // SMA SPP Settings
            [
                'name' => 'SPP SMA Kelas 10 IPA (Internal)',
                'school_level' => 'sma',
                'school_origin' => 'internal',
                'amount' => 700000.00,
                'academic_year' => '2024/2025',
                'status' => 'active',
                'description' => 'SPP untuk siswa SMA kelas 10 jurusan IPA yang berasal dari internal YAPI'
            ],
            [
                'name' => 'SPP SMA Kelas 10 IPA (External)',
                'school_level' => 'sma',
                'school_origin' => 'external',
                'amount' => 900000.00,
                'academic_year' => '2024/2025',
                'status' => 'active',
                'description' => 'SPP untuk siswa SMA kelas 10 jurusan IPA yang berasal dari luar YAPI'
            ],
            [
                'name' => 'SPP SMA Kelas 10 IPS (Internal)',
                'school_level' => 'sma',
                'school_origin' => 'internal',
                'amount' => 650000.00,
                'academic_year' => '2025/2026',
                'status' => 'active',
                'description' => 'SPP untuk siswa SMA kelas 10 jurusan IPS yang berasal dari internal YAPI'
            ],
            [
                'name' => 'SPP SMA Kelas 10 IPS (External)',
                'school_level' => 'sma',
                'school_origin' => 'external',
                'amount' => 850000.00,
                'academic_year' => '2025/2026',
                'status' => 'active',
                'description' => 'SPP untuk siswa SMA kelas 10 jurusan IPS yang berasal dari luar YAPI'
            ],

            // Inactive/Special SPP Settings
            [
                'name' => 'SPP TK Promo (Expired)',
                'school_level' => 'playgroup',
                'school_origin' => 'internal',
                'amount' => 100000.00,
                'academic_year' => '2023/2024',
                'status' => 'inactive',
                'description' => 'SPP promo Playgroup yang sudah tidak berlaku untuk tahun ajaran sebelumnya'
            ],
            [
                'name' => 'SPP SD Beasiswa (Non-aktif)',
                'school_level' => 'playgroup',
                'school_origin' => 'external',
                'amount' => 0.00,
                'academic_year' => '2023/2024',
                'status' => 'inactive',
                'description' => 'SPP khusus siswa beasiswa Playgroup yang sedang tidak aktif'
            ]
        ];

        foreach ($sppSettings as $setting) {
            SppSetting::updateOrCreate(
                [
                    'name' => $setting['name'],
                    'school_level' => $setting['school_level'],
                    'school_origin' => $setting['school_origin']
                ],
                $setting
            );
        }
    }
}
