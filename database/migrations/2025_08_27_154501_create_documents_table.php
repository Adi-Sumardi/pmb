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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftar_id');

            // Dokumen Siswa
            $table->string('foto_siswa_path', 500)->nullable();
            $table->string('akta_kelahiran_path', 500)->nullable();
            $table->string('kartu_keluarga_path', 500)->nullable();
            $table->string('ijazah_path', 500)->nullable();
            $table->string('skhun_path', 500)->nullable();
            $table->string('raport_terakhir_path', 500)->nullable();
            $table->string('surat_pindah_path', 500)->nullable();
            $table->string('surat_kelakuan_baik_path', 500)->nullable();

            // Dokumen Orang Tua
            $table->string('ktp_ayah_path', 500)->nullable();
            $table->string('ktp_ibu_path', 500)->nullable();
            $table->string('ktp_wali_path', 500)->nullable();
            $table->string('slip_gaji_ayah_path', 500)->nullable();
            $table->string('slip_gaji_ibu_path', 500)->nullable();
            $table->string('surat_keterangan_kerja_path', 500)->nullable();

            // Dokumen Kesehatan
            $table->string('surat_sehat_path', 500)->nullable();
            $table->string('surat_vaksin_path', 500)->nullable();

            // Dokumen Prestasi (JSON untuk multiple files)
            $table->json('sertifikat_prestasi_paths')->nullable();

            // Dokumen Tambahan
            $table->string('surat_tidak_mampu_path', 500)->nullable();
            $table->string('surat_yatim_piatu_path', 500)->nullable();
            $table->json('dokumen_lainnya_paths')->nullable();

            $table->timestamps();

            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
