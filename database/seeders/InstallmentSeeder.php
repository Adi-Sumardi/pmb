<?php

namespace Database\Seeders;

use App\Models\Installment;
use Illuminate\Database\Seeder;

class InstallmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $installments = [
            // TK - Paket Hemat
            [
                'name' => 'Cicilan TK Paket Hemat',
                'school_level' => 'tk',
                'installment_count' => 3,
                'first_payment_percentage' => 40.00,
                'payment_interval' => 'monthly',
                'late_fee_percentage' => 2.50,
                'grace_period_days' => 7,
                'auto_reminder' => true,
                'allow_early_payment' => true,
                'penalty_accumulative' => false,
                'status' => 'active',
                'description' => 'Paket cicilan 3x untuk TK dengan pembayaran pertama 40% dan sisanya dibagi 2 bulan'
            ],

            // SD - Standard
            [
                'name' => 'Cicilan SD Reguler',
                'school_level' => 'sd',
                'installment_count' => 4,
                'first_payment_percentage' => 35.00,
                'payment_interval' => 'monthly',
                'late_fee_percentage' => 3.00,
                'grace_period_days' => 5,
                'auto_reminder' => true,
                'allow_early_payment' => true,
                'penalty_accumulative' => false,
                'status' => 'active',
                'description' => 'Paket cicilan 4x untuk SD dengan DP 35% dan cicilan bulanan'
            ],

            // SD - Fleksibel
            [
                'name' => 'Cicilan SD Fleksibel',
                'school_level' => 'sd',
                'installment_count' => 6,
                'first_payment_percentage' => 25.00,
                'payment_interval' => 'monthly',
                'late_fee_percentage' => 2.00,
                'grace_period_days' => 10,
                'auto_reminder' => true,
                'allow_early_payment' => true,
                'penalty_accumulative' => false,
                'status' => 'active',
                'description' => 'Paket cicilan 6x untuk SD dengan DP ringan 25% dan angsuran lebih kecil'
            ],

            // SMP - Standard
            [
                'name' => 'Cicilan SMP Reguler',
                'school_level' => 'smp',
                'installment_count' => 5,
                'first_payment_percentage' => 30.00,
                'payment_interval' => 'monthly',
                'late_fee_percentage' => 3.50,
                'grace_period_days' => 5,
                'auto_reminder' => true,
                'allow_early_payment' => true,
                'penalty_accumulative' => true,
                'status' => 'active',
                'description' => 'Paket cicilan 5x untuk SMP dengan pembayaran pertama 30%'
            ],

            // SMP - Extended
            [
                'name' => 'Cicilan SMP Extended',
                'school_level' => 'smp',
                'installment_count' => 8,
                'first_payment_percentage' => 20.00,
                'payment_interval' => 'monthly',
                'late_fee_percentage' => 2.50,
                'grace_period_days' => 7,
                'auto_reminder' => true,
                'allow_early_payment' => true,
                'penalty_accumulative' => false,
                'status' => 'active',
                'description' => 'Paket cicilan 8x untuk SMP dengan DP minimal 20% dan angsuran terjangkau'
            ],

            // SMA - Premium
            [
                'name' => 'Cicilan SMA Premium',
                'school_level' => 'sma',
                'installment_count' => 4,
                'first_payment_percentage' => 40.00,
                'payment_interval' => 'monthly',
                'late_fee_percentage' => 4.00,
                'grace_period_days' => 3,
                'auto_reminder' => true,
                'allow_early_payment' => true,
                'penalty_accumulative' => true,
                'status' => 'active',
                'description' => 'Paket cicilan premium SMA 4x dengan benefit khusus dan denda tegas'
            ],

            // SMA - Standard
            [
                'name' => 'Cicilan SMA Reguler',
                'school_level' => 'sma',
                'installment_count' => 6,
                'first_payment_percentage' => 30.00,
                'payment_interval' => 'monthly',
                'late_fee_percentage' => 3.00,
                'grace_period_days' => 5,
                'auto_reminder' => true,
                'allow_early_payment' => true,
                'penalty_accumulative' => false,
                'status' => 'active',
                'description' => 'Paket cicilan reguler SMA 6x dengan pembayaran seimbang'
            ],

            // SMA - Economy
            [
                'name' => 'Cicilan SMA Ekonomis',
                'school_level' => 'sma',
                'installment_count' => 10,
                'first_payment_percentage' => 15.00,
                'payment_interval' => 'monthly',
                'late_fee_percentage' => 2.00,
                'grace_period_days' => 10,
                'auto_reminder' => true,
                'allow_early_payment' => true,
                'penalty_accumulative' => false,
                'status' => 'active',
                'description' => 'Paket cicilan ekonomis SMA 10x dengan DP super ringan dan angsuran kecil'
            ],

            // Special - Alumni Discount
            [
                'name' => 'Cicilan Alumni Discount',
                'school_level' => 'sma',
                'installment_count' => 3,
                'first_payment_percentage' => 50.00,
                'payment_interval' => 'monthly',
                'late_fee_percentage' => 1.50,
                'grace_period_days' => 14,
                'auto_reminder' => true,
                'allow_early_payment' => true,
                'penalty_accumulative' => false,
                'status' => 'active',
                'description' => 'Paket khusus anak alumni dengan diskon dan fasilitas pembayaran istimewa'
            ],

            // Special - Scholarship
            [
                'name' => 'Cicilan Beasiswa Prestasi',
                'school_level' => 'smp',
                'installment_count' => 12,
                'first_payment_percentage' => 10.00,
                'payment_interval' => 'monthly',
                'late_fee_percentage' => 1.00,
                'grace_period_days' => 15,
                'auto_reminder' => true,
                'allow_early_payment' => true,
                'penalty_accumulative' => false,
                'status' => 'active',
                'description' => 'Paket cicilan khusus penerima beasiswa prestasi dengan sangat terjangkau'
            ]
        ];

        foreach ($installments as $installment) {
            Installment::create($installment);
        }
    }
}
