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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftar_id');

            // Data Prestasi
            $table->string('nama_prestasi', 200);
            $table->enum('kategori', ['Akademik', 'Non-Akademik', 'Olahraga', 'Seni', 'Lainnya']);
            $table->enum('tingkat', ['Kelas', 'Sekolah', 'Kecamatan', 'Kabupaten/Kota', 'Provinsi', 'Nasional', 'Internasional']);
            $table->enum('juara', ['1', '2', '3', 'Harapan 1', 'Harapan 2', 'Harapan 3', 'Peserta']);

            // Detail Event
            $table->string('nama_event', 200)->nullable();
            $table->string('penyelenggara', 200)->nullable();
            $table->date('tanggal_event')->nullable();
            $table->string('tempat_event', 200)->nullable();

            // Dokumen
            $table->string('sertifikat_path', 500)->nullable();
            $table->string('foto_kegiatan_path', 500)->nullable();

            $table->timestamps();

            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
