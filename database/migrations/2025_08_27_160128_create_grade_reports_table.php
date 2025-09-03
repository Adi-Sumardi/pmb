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
        Schema::create('grade_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftar_id');

            // Identitas Raport
            $table->string('tahun_ajaran', 20);
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->string('kelas', 10);
            $table->enum('jenjang', ['Kelompok Bermain', 'Sanggar Bermain', 'TKA', 'TKB', 'SD', 'SMP', 'SMA', 'SMK']);

            // Data Sekolah
            $table->string('nama_sekolah', 200);
            $table->string('npsn', 20)->nullable();
            $table->text('alamat_sekolah')->nullable();
            $table->string('nama_kepala_sekolah', 100)->nullable();
            $table->string('nip_kepala_sekolah', 25)->nullable();
            $table->string('nama_wali_kelas', 100)->nullable();
            $table->string('nip_wali_kelas', 25)->nullable();

            // Statistik Nilai
            $table->decimal('rata_rata_kelas', 4, 2)->nullable();
            $table->integer('ranking_kelas')->nullable();
            $table->integer('jumlah_siswa_kelas')->nullable();

            // Kehadiran
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('tanpa_keterangan')->default(0);

            // Status dan Catatan
            $table->enum('status_kenaikan', ['Naik', 'Tinggal Kelas', 'Lulus', 'Tidak Lulus'])->nullable();
            $table->text('catatan_wali_kelas')->nullable();
            $table->text('catatan_orang_tua')->nullable();

            // Tanggal
            $table->date('tanggal_raport')->nullable();

            $table->timestamps();

            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->onDelete('cascade');
            $table->unique(['pendaftar_id', 'tahun_ajaran', 'semester'], 'unique_raport');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_reports');
    }
};
