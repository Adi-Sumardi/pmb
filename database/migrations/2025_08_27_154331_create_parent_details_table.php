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
        Schema::create('parent_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftar_id');

            // Data Ayah
            $table->string('nama_ayah')->nullable();
            $table->string('nik_ayah', 20)->nullable();
            $table->string('tempat_lahir_ayah', 100)->nullable();
            $table->date('tanggal_lahir_ayah')->nullable();
            $table->enum('agama_ayah', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu', 'Lainnya'])->nullable();
            $table->string('kewarganegaraan_ayah', 50)->default('Indonesia');
            $table->enum('pendidikan_ayah', ['SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3', 'Tidak Sekolah'])->nullable();
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->string('jabatan_ayah', 100)->nullable();
            $table->string('instansi_ayah', 200)->nullable();
            $table->text('alamat_kantor_ayah')->nullable();
            $table->enum('penghasilan_ayah', ['Kurang dari 1 Juta', '1-2 Juta', '2-3 Juta', '3-5 Juta', '5-10 Juta', 'Lebih dari 10 Juta'])->nullable();
            $table->string('no_hp_ayah', 15)->nullable();
            $table->string('email_ayah', 100)->nullable();

            // Data Ibu
            $table->string('nama_ibu')->nullable();
            $table->string('nik_ibu', 20)->nullable();
            $table->string('tempat_lahir_ibu', 100)->nullable();
            $table->date('tanggal_lahir_ibu')->nullable();
            $table->enum('agama_ibu', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu', 'Lainnya'])->nullable();
            $table->string('kewarganegaraan_ibu', 50)->default('Indonesia');
            $table->enum('pendidikan_ibu', ['SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3', 'Tidak Sekolah'])->nullable();
            $table->string('pekerjaan_ibu', 100)->nullable();
            $table->string('jabatan_ibu', 100)->nullable();
            $table->string('instansi_ibu', 200)->nullable();
            $table->text('alamat_kantor_ibu')->nullable();
            $table->enum('penghasilan_ibu', ['Kurang dari 1 Juta', '1-2 Juta', '2-3 Juta', '3-5 Juta', '5-10 Juta', 'Lebih dari 10 Juta'])->nullable();
            $table->string('no_hp_ibu', 15)->nullable();
            $table->string('email_ibu', 100)->nullable();

            // Data Wali
            $table->string('nama_wali')->nullable();
            $table->string('nik_wali', 20)->nullable();
            $table->string('tempat_lahir_wali', 100)->nullable();
            $table->date('tanggal_lahir_wali')->nullable();
            $table->enum('agama_wali', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu', 'Lainnya'])->nullable();
            $table->enum('pendidikan_wali', ['SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3', 'Tidak Sekolah'])->nullable();
            $table->string('pekerjaan_wali', 100)->nullable();
            $table->enum('penghasilan_wali', ['Kurang dari 1 Juta', '1-2 Juta', '2-3 Juta', '3-5 Juta', '5-10 Juta', 'Lebih dari 10 Juta'])->nullable();
            $table->string('hubungan_dengan_siswa', 50)->nullable();
            $table->string('no_hp_wali', 15)->nullable();
            $table->string('email_wali', 100)->nullable();
            $table->text('alamat_wali')->nullable();

            // Status Keluarga
            $table->enum('status_keluarga', ['Lengkap', 'Yatim', 'Piatu', 'Yatim Piatu'])->nullable();
            $table->enum('status_pernikahan_ortu', ['Menikah', 'Cerai Hidup', 'Cerai Mati'])->nullable();

            $table->timestamps();

            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_details');
    }
};
