<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('academic_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftar_id');

            // Pendidikan Sebelumnya
            $table->enum('jenjang_sebelumnya', ['Sanggar Bermain', 'Kelompok Bermain', 'TKA', 'TKB', 'SD', 'SMP', 'SMA', 'SMK'])->nullable();
            $table->string('nama_sekolah_sebelumnya', 200)->nullable();
            $table->string('npsn_sekolah_sebelumnya', 20)->nullable();
            $table->text('alamat_sekolah_sebelumnya')->nullable();
            $table->string('kelas_terakhir', 10)->nullable();
            $table->year('tahun_lulus')->nullable();
            $table->string('no_ijazah', 50)->nullable();
            $table->string('no_skhun', 50)->nullable();

            // Nilai Akademik
            $table->decimal('rata_rata_nilai', 4, 2)->nullable();
            $table->decimal('nilai_bahasa_indonesia', 4, 2)->nullable();
            $table->decimal('nilai_matematika', 4, 2)->nullable();
            $table->decimal('nilai_ipa', 4, 2)->nullable();
            $table->decimal('nilai_ips', 4, 2)->nullable();
            $table->decimal('nilai_bahasa_inggris', 4, 2)->nullable();

            // Prestasi Akademik
            $table->integer('ranking_kelas')->nullable();
            $table->integer('jumlah_siswa_sekelas')->nullable();
            $table->text('prestasi_akademik')->nullable();
            $table->text('prestasi_non_akademik')->nullable();

            // Organisasi/Ekstrakurikuler
            $table->text('organisasi_yang_diikuti')->nullable();
            $table->text('jabatan_organisasi')->nullable();

            $table->timestamps();

            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_histories');
    }
};
