<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicSubject;

class AcademicSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            // SD Subjects
            ['kode_mapel' => 'SD001', 'nama_mapel' => 'Pendidikan Agama dan Budi Pekerti', 'jenjang' => 'SD', 'kelas_mulai' => 1, 'kelas_selesai' => 6],
            ['kode_mapel' => 'SD002', 'nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan', 'jenjang' => 'SD', 'kelas_mulai' => 1, 'kelas_selesai' => 6],
            ['kode_mapel' => 'SD003', 'nama_mapel' => 'Bahasa Indonesia', 'jenjang' => 'SD', 'kelas_mulai' => 1, 'kelas_selesai' => 6],
            ['kode_mapel' => 'SD004', 'nama_mapel' => 'Matematika', 'jenjang' => 'SD', 'kelas_mulai' => 1, 'kelas_selesai' => 6],
            ['kode_mapel' => 'SD005', 'nama_mapel' => 'Ilmu Pengetahuan Alam', 'jenjang' => 'SD', 'kelas_mulai' => 4, 'kelas_selesai' => 6],
            ['kode_mapel' => 'SD006', 'nama_mapel' => 'Ilmu Pengetahuan Sosial', 'jenjang' => 'SD', 'kelas_mulai' => 4, 'kelas_selesai' => 6],
            ['kode_mapel' => 'SD007', 'nama_mapel' => 'Seni Budaya dan Prakarya', 'jenjang' => 'SD', 'kelas_mulai' => 1, 'kelas_selesai' => 6],
            ['kode_mapel' => 'SD008', 'nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'jenjang' => 'SD', 'kelas_mulai' => 1, 'kelas_selesai' => 6],
            ['kode_mapel' => 'SD009', 'nama_mapel' => 'Bahasa Inggris', 'jenjang' => 'SD', 'kelas_mulai' => 1, 'kelas_selesai' => 6, 'kategori' => 'Muatan Lokal'],

            // SMP Subjects
            ['kode_mapel' => 'SMP001', 'nama_mapel' => 'Pendidikan Agama dan Budi Pekerti', 'jenjang' => 'SMP', 'kelas_mulai' => 7, 'kelas_selesai' => 9],
            ['kode_mapel' => 'SMP002', 'nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan', 'jenjang' => 'SMP', 'kelas_mulai' => 7, 'kelas_selesai' => 9],
            ['kode_mapel' => 'SMP003', 'nama_mapel' => 'Bahasa Indonesia', 'jenjang' => 'SMP', 'kelas_mulai' => 7, 'kelas_selesai' => 9],
            ['kode_mapel' => 'SMP004', 'nama_mapel' => 'Matematika', 'jenjang' => 'SMP', 'kelas_mulai' => 7, 'kelas_selesai' => 9],
            ['kode_mapel' => 'SMP005', 'nama_mapel' => 'Ilmu Pengetahuan Alam', 'jenjang' => 'SMP', 'kelas_mulai' => 7, 'kelas_selesai' => 9],
            ['kode_mapel' => 'SMP006', 'nama_mapel' => 'Ilmu Pengetahuan Sosial', 'jenjang' => 'SMP', 'kelas_mulai' => 7, 'kelas_selesai' => 9],
            ['kode_mapel' => 'SMP007', 'nama_mapel' => 'Bahasa Inggris', 'jenjang' => 'SMP', 'kelas_mulai' => 7, 'kelas_selesai' => 9],
            ['kode_mapel' => 'SMP008', 'nama_mapel' => 'Seni Budaya', 'jenjang' => 'SMP', 'kelas_mulai' => 7, 'kelas_selesai' => 9],
            ['kode_mapel' => 'SMP009', 'nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'jenjang' => 'SMP', 'kelas_mulai' => 7, 'kelas_selesai' => 9],
            ['kode_mapel' => 'SMP010', 'nama_mapel' => 'Prakarya', 'jenjang' => 'SMP', 'kelas_mulai' => 7, 'kelas_selesai' => 9],

            // SMA Subjects - Mata Pelajaran Wajib
            ['kode_mapel' => 'SMA001', 'nama_mapel' => 'Pendidikan Agama dan Budi Pekerti', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12],
            ['kode_mapel' => 'SMA002', 'nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12],
            ['kode_mapel' => 'SMA003', 'nama_mapel' => 'Bahasa Indonesia', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12],
            ['kode_mapel' => 'SMA004', 'nama_mapel' => 'Matematika', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12],
            ['kode_mapel' => 'SMA005', 'nama_mapel' => 'Sejarah Indonesia', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12],
            ['kode_mapel' => 'SMA006', 'nama_mapel' => 'Bahasa Inggris', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12],

            // SMA IPA - Peminatan
            ['kode_mapel' => 'SMA007', 'nama_mapel' => 'Matematika Peminatan', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12, 'kategori' => 'Pilihan'],
            ['kode_mapel' => 'SMA008', 'nama_mapel' => 'Fisika', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12, 'kategori' => 'Pilihan'],
            ['kode_mapel' => 'SMA009', 'nama_mapel' => 'Kimia', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12, 'kategori' => 'Pilihan'],
            ['kode_mapel' => 'SMA010', 'nama_mapel' => 'Biologi', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12, 'kategori' => 'Pilihan'],

            // SMA IPS - Peminatan
            ['kode_mapel' => 'SMA011', 'nama_mapel' => 'Geografi', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12, 'kategori' => 'Pilihan'],
            ['kode_mapel' => 'SMA012', 'nama_mapel' => 'Sejarah', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12, 'kategori' => 'Pilihan'],
            ['kode_mapel' => 'SMA013', 'nama_mapel' => 'Sosiologi', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12, 'kategori' => 'Pilihan'],
            ['kode_mapel' => 'SMA014', 'nama_mapel' => 'Ekonomi', 'jenjang' => 'SMA', 'kelas_mulai' => 10, 'kelas_selesai' => 12, 'kategori' => 'Pilihan'],

            // SMK Subjects - Contoh untuk beberapa jurusan
            ['kode_mapel' => 'SMK001', 'nama_mapel' => 'Pendidikan Agama dan Budi Pekerti', 'jenjang' => 'SMK', 'kelas_mulai' => 10, 'kelas_selesai' => 12],
            ['kode_mapel' => 'SMK002', 'nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan', 'jenjang' => 'SMK', 'kelas_mulai' => 10, 'kelas_selesai' => 12],
            ['kode_mapel' => 'SMK003', 'nama_mapel' => 'Bahasa Indonesia', 'jenjang' => 'SMK', 'kelas_mulai' => 10, 'kelas_selesai' => 12],
            ['kode_mapel' => 'SMK004', 'nama_mapel' => 'Matematika', 'jenjang' => 'SMK', 'kelas_mulai' => 10, 'kelas_selesai' => 12],
            ['kode_mapel' => 'SMK005', 'nama_mapel' => 'Bahasa Inggris', 'jenjang' => 'SMK', 'kelas_mulai' => 10, 'kelas_selesai' => 12],
            ['kode_mapel' => 'SMK006', 'nama_mapel' => 'Seni Budaya', 'jenjang' => 'SMK', 'kelas_mulai' => 10, 'kelas_selesai' => 12],
            ['kode_mapel' => 'SMK007', 'nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'jenjang' => 'SMK', 'kelas_mulai' => 10, 'kelas_selesai' => 12],

            // SMK Kejuruan - TKJ (Teknik Komputer dan Jaringan)
            ['kode_mapel' => 'SMK101', 'nama_mapel' => 'Pemrograman Dasar', 'jenjang' => 'SMK', 'kelas_mulai' => 10, 'kelas_selesai' => 12, 'kategori' => 'Pilihan', 'kkm' => 80.00],
            ['kode_mapel' => 'SMK102', 'nama_mapel' => 'Komputer dan Jaringan Dasar', 'jenjang' => 'SMK', 'kelas_mulai' => 10, 'kelas_selesai' => 12, 'kategori' => 'Pilihan', 'kkm' => 80.00],
            ['kode_mapel' => 'SMK103', 'nama_mapel' => 'Administrasi Infrastruktur Jaringan', 'jenjang' => 'SMK', 'kelas_mulai' => 11, 'kelas_selesai' => 12, 'kategori' => 'Pilihan', 'kkm' => 80.00],
        ];

        foreach ($subjects as $subject) {
            AcademicSubject::create($subject);
        }

        $this->command->info('Academic subjects seeded successfully!');
    }
}
