<?php

namespace Database\Seeders;

use App\Models\MultiPayment;
use Illuminate\Database\Seeder;

class MultiPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $multiPayments = [
            // Seragam & Perlengkapan Sekolah
            [
                'name' => 'Seragam Putih Abu-abu',
                'category' => 'seragam',
                'school_level' => 'sd',
                'specific_level' => 'SD Kelas 1-6',
                'amount' => 300000,
                'is_mandatory' => true,
                'available_sizes' => ['S', 'M', 'L', 'XL'],
                'supplier' => 'Toko Seragam Jaya',
                'stock_quantity' => 150,
                'track_stock' => true,
                'status' => 'active',
                'description' => 'Seragam putih abu-abu untuk siswa SD, termasuk kemeja dan celana/rok',
                'sort_order' => 1
            ],
            [
                'name' => 'Seragam Pramuka',
                'category' => 'seragam',
                'school_level' => 'smp',
                'specific_level' => 'SMP Kelas 7-9',
                'amount' => 350000,
                'is_mandatory' => true,
                'available_sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'supplier' => 'Supplier Pramuka Nusantara',
                'stock_quantity' => 120,
                'track_stock' => true,
                'status' => 'active',
                'description' => 'Seragam pramuka lengkap untuk kegiatan ekstrakurikuler wajib',
                'sort_order' => 2
            ],
            [
                'name' => 'Seragam Osis',
                'category' => 'seragam',
                'school_level' => 'sma',
                'specific_level' => 'SMA Kelas 10-12',
                'amount' => 400000,
                'is_mandatory' => false,
                'available_sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'supplier' => 'Konveksi Modern',
                'stock_quantity' => 80,
                'track_stock' => true,
                'status' => 'active',
                'description' => 'Seragam OSIS untuk pengurus dan anggota organisasi siswa',
                'sort_order' => 3
            ],

            // Buku & Materi Pembelajaran
            [
                'name' => 'Paket Buku Pelajaran TK',
                'category' => 'buku',
                'school_level' => 'tk',
                'specific_level' => 'TK A & TK B',
                'amount' => 800000,
                'is_mandatory' => true,
                'available_sizes' => null,
                'supplier' => 'Penerbit Pendidikan Nusantara',
                'stock_quantity' => 200,
                'track_stock' => true,
                'status' => 'active',
                'description' => 'Paket lengkap buku pelajaran untuk TK A dan TK B tahun ajaran 2024/2025',
                'sort_order' => 4
            ],
            [
                'name' => 'Buku LKS Matematika SD',
                'category' => 'buku',
                'school_level' => 'sd',
                'specific_level' => 'SD Kelas 4-6',
                'amount' => 150000,
                'is_mandatory' => false,
                'available_sizes' => null,
                'supplier' => 'Penerbit Erlangga',
                'stock_quantity' => 300,
                'track_stock' => true,
                'status' => 'active',
                'description' => 'Lembar Kerja Siswa Matematika untuk kelas tinggi SD',
                'sort_order' => 5
            ],

            // Perlengkapan & Alat Tulis
            [
                'name' => 'Tas Sekolah Berlogo',
                'category' => 'perlengkapan',
                'school_level' => 'sd',
                'specific_level' => 'SD Kelas 1-6',
                'amount' => 250000,
                'is_mandatory' => true,
                'available_sizes' => ['Small', 'Medium', 'Large'],
                'supplier' => 'Produsen Tas Berkualitas',
                'stock_quantity' => 180,
                'track_stock' => true,
                'status' => 'active',
                'description' => 'Tas sekolah resmi dengan logo sekolah, tahan lama dan ergonomis',
                'sort_order' => 6
            ],
            [
                'name' => 'Set Alat Tulis Lengkap',
                'category' => 'perlengkapan',
                'school_level' => 'smp',
                'specific_level' => 'SMP Kelas 7-9',
                'amount' => 120000,
                'is_mandatory' => false,
                'available_sizes' => null,
                'supplier' => 'Toko Alat Tulis Modern',
                'stock_quantity' => 250,
                'track_stock' => true,
                'status' => 'active',
                'description' => 'Set lengkap alat tulis: pulpen, pensil, penghapus, penggaris, dll',
                'sort_order' => 7
            ],

            // Kegiatan & Ekstrakurikuler
            [
                'name' => 'Biaya Kegiatan Study Tour',
                'category' => 'kegiatan',
                'school_level' => 'sma',
                'specific_level' => 'SMA Kelas 11',
                'amount' => 1500000,
                'is_mandatory' => false,
                'available_sizes' => null,
                'supplier' => 'Travel Edukasi Indonesia',
                'stock_quantity' => 0,
                'track_stock' => false,
                'status' => 'active',
                'description' => 'Biaya kegiatan study tour ke museum dan tempat bersejarah untuk kelas 11',
                'sort_order' => 8
            ],
            [
                'name' => 'Iuran Ekstrakurikuler Musik',
                'category' => 'kegiatan',
                'school_level' => 'smp',
                'specific_level' => 'SMP Kelas 7-9',
                'amount' => 200000,
                'is_mandatory' => false,
                'available_sizes' => null,
                'supplier' => 'Instruktur Musik Profesional',
                'stock_quantity' => 0,
                'track_stock' => false,
                'status' => 'active',
                'description' => 'Biaya bulanan untuk mengikuti ekstrakurikuler musik dan band',
                'sort_order' => 9
            ],

            // Teknologi & Digital
            [
                'name' => 'Akses Platform E-Learning',
                'category' => 'teknologi',
                'school_level' => 'sma',
                'specific_level' => 'SMA Kelas 10-12',
                'amount' => 500000,
                'is_mandatory' => true,
                'available_sizes' => null,
                'supplier' => 'Platform Pembelajaran Digital',
                'stock_quantity' => 0,
                'track_stock' => false,
                'status' => 'active',
                'description' => 'Akses penuh ke platform e-learning untuk pembelajaran online dan hybrid',
                'sort_order' => 10
            ]
        ];

        foreach ($multiPayments as $payment) {
            MultiPayment::create($payment);
        }
    }
}
