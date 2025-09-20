<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pendaftar;
use App\Models\User;
use Faker\Factory as Faker;

class PendaftarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $units = ['TK', 'SD', 'SMP', 'SMA'];
        $statuses = ['pending', 'diverifikasi'];
        $overallStatuses = ['Draft', 'Diverifikasi', 'Sudah Bayar', 'Observasi', 'Tes Tulis', 'Praktek Shalat & BTQ', 'Wawancara', 'Psikotest', 'Lulus', 'Tidak Lulus'];
        $jenjangs = ['TK A', 'TK B', 'Kelas 1', 'Kelas 2', 'Kelas 3', 'Kelas 4', 'Kelas 5', 'Kelas 6', 'Kelas 7', 'Kelas 8', 'Kelas 9', 'Kelas 10', 'Kelas 11', 'Kelas 12'];

        // Create some dummy users first
        $users = [];
        for ($i = 0; $i < 50; $i++) {
            $users[] = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 'user'
            ]);
        }

        // Create pendaftar data with realistic distribution
        foreach ($units as $unit) {
            $countForUnit = rand(8, 15); // 8-15 pendaftar per unit

            for ($i = 0; $i < $countForUnit; $i++) {
                $status = $faker->randomElement($statuses);
                $overallStatus = $faker->randomElement($overallStatuses);
                $sudahBayar = in_array($overallStatus, ['Sudah Bayar', 'Lulus']) ? true : $faker->boolean(30);

                // Different date ranges for variety
                $createdDate = $faker->dateTimeBetween('-30 days', 'now');

                Pendaftar::create([
                    'user_id' => $faker->randomElement($users)->id,
                    'no_pendaftaran' => 'REG' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'nama_murid' => $faker->name,
                    'nisn' => $faker->numerify('##########'),
                    'tanggal_lahir' => $faker->dateTimeBetween('-18 years', '-6 years'),
                    'alamat' => $faker->address,
                    'jenjang' => $faker->randomElement($jenjangs),
                    'unit' => $unit,
                    'asal_sekolah' => $faker->company . ' School',
                    'nama_sekolah' => 'YAPI ' . $unit,
                    'kelas' => $faker->randomElement(['A', 'B', 'C']),
                    'nama_ayah' => $faker->name('male'),
                    'telp_ayah' => $faker->phoneNumber,
                    'nama_ibu' => $faker->name('female'),
                    'telp_ibu' => $faker->phoneNumber,
                    'foto_murid_path' => 'uploads/photos/' . $faker->uuid . '.jpg',
                    'foto_murid_mime' => 'image/jpeg',
                    'foto_murid_size' => rand(100000, 500000),
                    'akta_kelahiran_path' => 'uploads/documents/' . $faker->uuid . '.pdf',
                    'akta_kelahiran_mime' => 'application/pdf',
                    'akta_kelahiran_size' => rand(100000, 300000),
                    'kartu_keluarga_path' => 'uploads/documents/' . $faker->uuid . '.pdf',
                    'kartu_keluarga_mime' => 'application/pdf',
                    'kartu_keluarga_size' => rand(100000, 300000),
                    'bukti_pendaftaran' => $faker->boolean(70),
                    'bukti_pendaftaran_path' => $faker->boolean(70) ? 'uploads/receipts/' . $faker->uuid . '.jpg' : null,
                    'bukti_pendaftaran_mime' => $faker->boolean(70) ? 'image/jpeg' : null,
                    'bukti_pendaftaran_size' => $faker->boolean(70) ? rand(50000, 200000) : null,
                    'payment_amount' => rand(200000, 500000),
                    'status' => $status,
                    'sudah_bayar_formulir' => $sudahBayar,
                    'current_status' => $status,
                    'overall_status' => $overallStatus,
                    'created_at' => $createdDate,
                    'updated_at' => $createdDate,
                ]);
            }
        }

        $this->command->info('Created ' . Pendaftar::count() . ' pendaftar records with realistic data distribution.');
    }
}
