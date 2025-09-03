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
        // Drop the existing table if it exists
        Schema::dropIfExists('documents');

        // Create the table with generic structure
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftar_id');
            $table->string('document_type');
            $table->string('document_name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_verified')->default(false);
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

        // Recreate the original structure if needed
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftar_id');

            // Dokumen Siswa
            $table->string('ijazah_path', 500)->nullable();
            $table->string('skhun_path', 500)->nullable();
            $table->string('raport_terakhir_path', 500)->nullable();
            $table->string('surat_pindah_path', 500)->nullable();
            $table->string('surat_kelakuan_baik_path', 500)->nullable();

            // Dokumen Orang Tua
            $table->string('ktp_ayah_path', 500)->nullable();
            $table->string('ktp_ibu_path', 500)->nullable();
            $table->string('ktp_wali_path', 500)->nullable();

            // Dokumen Kesehatan
            $table->string('surat_sehat_path', 500)->nullable();
            $table->string('surat_vaksin_path', 500)->nullable();

            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->onDelete('cascade');
        });
    }
};
