<?php

namespace Database\Seeders;

use App\Models\SppBulkSetting;
use Illuminate\Database\Seeder;

class SppBulkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sppBulkSettings = [
            // TK - 3 Bulan
            [
                'name' => 'Promo SPP TK 3 Bulan',
                'school_level' => 'tk',
                'months_count' => 3,
                'discount_percentage' => 5.00,
                'academic_year' => '2024/2025',
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
                'allow_partial_refund' => true,
                'auto_apply_discount' => true,
                'show_savings_info' => true,
                'min_payment_amount' => 1000000.00,
                'max_payment_amount' => 5000000.00,
                'status' => 'active',
                'description' => 'Promo pembayaran SPP TK untuk 3 bulan sekaligus dengan diskon 5%'
            ],

            // TK - 6 Bulan
            [
                'name' => 'Promo SPP TK 6 Bulan Premium',
                'school_level' => 'tk',
                'months_count' => 6,
                'discount_percentage' => 8.00,
                'academic_year' => '2024/2025',
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
                'allow_partial_refund' => true,
                'auto_apply_discount' => true,
                'show_savings_info' => true,
                'min_payment_amount' => 2000000.00,
                'max_payment_amount' => 8000000.00,
                'status' => 'active',
                'description' => 'Promo premium pembayaran SPP TK untuk 6 bulan dengan diskon ekstra'
            ],

            // SD - 3 Bulan
            [
                'name' => 'Hemat SPP SD Triwulan',
                'school_level' => 'sd',
                'months_count' => 3,
                'discount_percentage' => 6.00,
                'academic_year' => '2024/2025',
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
                'allow_partial_refund' => true,
                'auto_apply_discount' => true,
                'show_savings_info' => true,
                'min_payment_amount' => 1200000.00,
                'max_payment_amount' => 6000000.00,
                'status' => 'active',
                'description' => 'Program hemat pembayaran SPP SD untuk 3 bulan sekaligus'
            ],

            // SD - 6 Bulan
            [
                'name' => 'Super Hemat SPP SD 6 Bulan',
                'school_level' => 'sd',
                'months_count' => 6,
                'discount_percentage' => 10.00,
                'academic_year' => '2024/2025',
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
                'allow_partial_refund' => true,
                'auto_apply_discount' => true,
                'show_savings_info' => true,
                'min_payment_amount' => 2400000.00,
                'max_payment_amount' => 10000000.00,
                'status' => 'active',
                'description' => 'Program super hemat untuk pembayaran SPP SD 6 bulan dengan diskon maksimal'
            ],

            // SD - 12 Bulan
            [
                'name' => 'SPP SD Full Year Ultimate',
                'school_level' => 'sd',
                'months_count' => 12,
                'discount_percentage' => 15.00,
                'academic_year' => '2024/2025',
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
                'allow_partial_refund' => false,
                'auto_apply_discount' => true,
                'show_savings_info' => true,
                'min_payment_amount' => 4800000.00,
                'max_payment_amount' => 15000000.00,
                'status' => 'active',
                'description' => 'Paket ultimate pembayaran SPP SD untuk setahun penuh dengan diskon terbesar'
            ],

            // SMP - 3 Bulan
            [
                'name' => 'Promo SPP SMP Triwulan',
                'school_level' => 'smp',
                'months_count' => 3,
                'discount_percentage' => 7.00,
                'academic_year' => '2024/2025',
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
                'allow_partial_refund' => true,
                'auto_apply_discount' => true,
                'show_savings_info' => true,
                'min_payment_amount' => 1500000.00,
                'max_payment_amount' => 7500000.00,
                'status' => 'active',
                'description' => 'Promo pembayaran SPP SMP untuk 3 bulan dengan benefit khusus'
            ],

            // SMP - 6 Bulan
            [
                'name' => 'Paket Hemat SPP SMP 6 Bulan',
                'school_level' => 'smp',
                'months_count' => 6,
                'discount_percentage' => 12.00,
                'academic_year' => '2024/2025',
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
                'allow_partial_refund' => true,
                'auto_apply_discount' => true,
                'show_savings_info' => true,
                'min_payment_amount' => 3000000.00,
                'max_payment_amount' => 12000000.00,
                'status' => 'active',
                'description' => 'Paket hemat untuk SPP SMP 6 bulan dengan diskon menarik'
            ],

            // SMP - 12 Bulan
            [
                'name' => 'SPP SMP Annual Package',
                'school_level' => 'smp',
                'months_count' => 12,
                'discount_percentage' => 18.00,
                'academic_year' => '2024/2025',
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
                'allow_partial_refund' => false,
                'auto_apply_discount' => true,
                'show_savings_info' => true,
                'min_payment_amount' => 6000000.00,
                'max_payment_amount' => 18000000.00,
                'status' => 'active',
                'description' => 'Paket tahunan SPP SMP dengan diskon terbaik untuk komitmen setahun'
            ],

            // SMA - 3 Bulan
            [
                'name' => 'Promo SPP SMA Kuartal',
                'school_level' => 'sma',
                'months_count' => 3,
                'discount_percentage' => 8.00,
                'academic_year' => '2024/2025',
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
                'allow_partial_refund' => true,
                'auto_apply_discount' => true,
                'show_savings_info' => true,
                'min_payment_amount' => 1800000.00,
                'max_payment_amount' => 9000000.00,
                'status' => 'active',
                'description' => 'Promo khusus pembayaran SPP SMA untuk 3 bulan dengan fasilitas lengkap'
            ],

            // SMA - 6 Bulan
            [
                'name' => 'SPP SMA Semester Package',
                'school_level' => 'sma',
                'months_count' => 6,
                'discount_percentage' => 14.00,
                'academic_year' => '2024/2025',
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
                'allow_partial_refund' => true,
                'auto_apply_discount' => true,
                'show_savings_info' => true,
                'min_payment_amount' => 3600000.00,
                'max_payment_amount' => 14000000.00,
                'status' => 'active',
                'description' => 'Paket semester untuk SPP SMA dengan bonus program ekstrakurikuler'
            ],

            // SMA - 12 Bulan
            [
                'name' => 'SPP SMA Premium Annual',
                'school_level' => 'sma',
                'months_count' => 12,
                'discount_percentage' => 20.00,
                'academic_year' => '2024/2025',
                'start_date' => '2024-07-01',
                'end_date' => '2025-06-30',
                'allow_partial_refund' => false,
                'auto_apply_discount' => true,
                'show_savings_info' => true,
                'min_payment_amount' => 7200000.00,
                'max_payment_amount' => 25000000.00,
                'status' => 'active',
                'description' => 'Paket premium tahunan SPP SMA dengan diskon tertinggi dan benefit eksklusif'
            ],

            // Special - Expired/Inactive
            [
                'name' => 'Promo Awal Tahun TK Premium (Expired)',
                'school_level' => 'tk',
                'months_count' => 12,
                'discount_percentage' => 12.00,
                'academic_year' => '2023/2024',
                'start_date' => '2023-07-01',
                'end_date' => '2024-06-30',
                'allow_partial_refund' => true,
                'auto_apply_discount' => false,
                'show_savings_info' => true,
                'min_payment_amount' => 2000000.00,
                'max_payment_amount' => 10000000.00,
                'status' => 'inactive',
                'description' => 'Promo tahunan TK premium yang sudah berakhir untuk referensi tahun ajaran sebelumnya'
            ]
        ];

        foreach ($sppBulkSettings as $setting) {
            SppBulkSetting::updateOrCreate(
                [
                    'school_level' => $setting['school_level'],
                    'months_count' => $setting['months_count']
                ],
                $setting
            );
        }
    }
}
