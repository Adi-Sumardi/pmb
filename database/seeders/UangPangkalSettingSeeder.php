<?php

namespace Database\Seeders;

use App\Models\UangPangkalSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UangPangkalSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $uangPangkalSettings = [
            [
                'name' => 'Uang Pangkal Playgroup Internal',
                'school_level' => 'playgroup',
                'amount' => 8000000,
                'school_origin' => 'internal',
                'academic_year' => '2025/2026',
                'allow_installments' => true,
                'max_installments' => 3,
                'first_installment_percentage' => 50.00,
                'include_registration' => true,
                'include_uniform' => true,
                'include_books' => true,
                'include_supplies' => false,
                'status' => 'active',
                'description' => 'Uang pangkal untuk playgroup siswa internal YAPI, sudah termasuk biaya pendaftaran, seragam, dan buku.'
            ],
            [
                'name' => 'Uang Pangkal Playgroup Eksternal',
                'school_level' => 'playgroup',
                'amount' => 9000000,
                'school_origin' => 'external',
                'academic_year' => '2025/2026',
                'allow_installments' => true,
                'max_installments' => 3,
                'first_installment_percentage' => 50.00,
                'include_registration' => true,
                'include_uniform' => true,
                'include_books' => true,
                'include_supplies' => false,
                'status' => 'active',
                'description' => 'Uang pangkal untuk playgroup siswa eksternal, sudah termasuk biaya pendaftaran, seragam, dan buku.'
            ],
            [
                'name' => 'Uang Pangkal TK Internal',
                'school_level' => 'tk',
                'amount' => 9500000,
                'school_origin' => 'internal',
                'academic_year' => '2025/2026',
                'allow_installments' => true,
                'max_installments' => 4,
                'first_installment_percentage' => 50.00,
                'include_registration' => true,
                'include_uniform' => true,
                'include_books' => true,
                'include_supplies' => true,
                'status' => 'active',
                'description' => 'Uang pangkal untuk TK siswa internal YAPI, paket lengkap termasuk alat tulis.'
            ],
            [
                'name' => 'Uang Pangkal TK Eksternal',
                'school_level' => 'tk',
                'amount' => 10500000,
                'school_origin' => 'external',
                'academic_year' => '2025/2026',
                'allow_installments' => true,
                'max_installments' => 4,
                'first_installment_percentage' => 50.00,
                'include_registration' => true,
                'include_uniform' => true,
                'include_books' => true,
                'include_supplies' => true,
                'status' => 'active',
                'description' => 'Uang pangkal untuk TK siswa eksternal, paket lengkap termasuk alat tulis.'
            ],
            [
                'name' => 'Uang Pangkal SD Internal',
                'school_level' => 'sd',
                'amount' => 12000000,
                'school_origin' => 'internal',
                'academic_year' => '2025/2026',
                'allow_installments' => true,
                'max_installments' => 6,
                'first_installment_percentage' => 40.00,
                'include_registration' => true,
                'include_uniform' => true,
                'include_books' => true,
                'include_supplies' => true,
                'status' => 'active',
                'description' => 'Uang pangkal untuk SD siswa internal YAPI, paket lengkap dengan cicilan hingga 6 bulan.'
            ],
            [
                'name' => 'Uang Pangkal SD Eksternal',
                'school_level' => 'sd',
                'amount' => 14000000,
                'school_origin' => 'external',
                'academic_year' => '2025/2026',
                'allow_installments' => true,
                'max_installments' => 6,
                'first_installment_percentage' => 40.00,
                'include_registration' => true,
                'include_uniform' => true,
                'include_books' => true,
                'include_supplies' => true,
                'status' => 'active',
                'description' => 'Uang pangkal untuk SD siswa eksternal, paket lengkap dengan cicilan hingga 6 bulan.'
            ],
            [
                'name' => 'Uang Pangkal SMP Internal',
                'school_level' => 'smp',
                'amount' => 15000000,
                'school_origin' => 'internal',
                'academic_year' => '2025/2026',
                'allow_installments' => true,
                'max_installments' => 8,
                'first_installment_percentage' => 35.00,
                'include_registration' => true,
                'include_uniform' => true,
                'include_books' => true,
                'include_supplies' => true,
                'status' => 'active',
                'description' => 'Uang pangkal untuk SMP siswa internal YAPI, cicilan fleksibel hingga 8 bulan.'
            ],
            [
                'name' => 'Uang Pangkal SMP Eksternal',
                'school_level' => 'smp',
                'amount' => 17000000,
                'school_origin' => 'external',
                'academic_year' => '2025/2026',
                'allow_installments' => true,
                'max_installments' => 8,
                'first_installment_percentage' => 35.00,
                'include_registration' => true,
                'include_uniform' => true,
                'include_books' => true,
                'include_supplies' => true,
                'status' => 'active',
                'description' => 'Uang pangkal untuk SMP siswa eksternal, cicilan fleksibel hingga 8 bulan.'
            ],
            [
                'name' => 'Uang Pangkal SMA Internal',
                'school_level' => 'sma',
                'amount' => 18000000,
                'school_origin' => 'internal',
                'academic_year' => '2025/2026',
                'allow_installments' => true,
                'max_installments' => 10,
                'first_installment_percentage' => 30.00,
                'include_registration' => true,
                'include_uniform' => true,
                'include_books' => true,
                'include_supplies' => true,
                'status' => 'active',
                'description' => 'Uang pangkal untuk SMA siswa internal YAPI, cicilan hingga 10 bulan.'
            ],
            [
                'name' => 'Uang Pangkal SMA Eksternal',
                'school_level' => 'sma',
                'amount' => 20000000,
                'school_origin' => 'external',
                'academic_year' => '2025/2026',
                'allow_installments' => true,
                'max_installments' => 10,
                'first_installment_percentage' => 30.00,
                'include_registration' => true,
                'include_uniform' => true,
                'include_books' => true,
                'include_supplies' => true,
                'status' => 'inactive',
                'description' => 'Uang pangkal untuk SMA siswa eksternal, cicilan hingga 10 bulan. Saat ini nonaktif.'
            ]
        ];

        foreach ($uangPangkalSettings as $setting) {
            UangPangkalSetting::create($setting);
        }
    }
}
