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
        Schema::create('student_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftar_id');

            // Data Pribadi Lengkap
            $table->string('nama_lengkap');
            $table->string('nama_panggilan', 100)->nullable();
            $table->string('nisn', 20)->unique()->nullable();
            $table->string('nik', 20)->unique()->nullable();
            $table->string('no_kk', 20)->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu', 'Lainnya'])->nullable();
            $table->string('kewarganegaraan', 50)->default('Indonesia');
            $table->string('bahasa_sehari_hari', 100)->nullable();

            // Data Fisik
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', 'Tidak Tahu'])->nullable();

            // Alamat Lengkap
            $table->text('alamat_lengkap')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kota_kabupaten', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->decimal('jarak_ke_sekolah', 5, 2)->nullable();
            $table->string('transportasi', 100)->nullable();

            // Data Tinggal
            $table->enum('tinggal_dengan', ['Orang Tua', 'Wali', 'Kos', 'Asrama', 'Panti Asuhan', 'Lainnya'])->nullable();
            $table->integer('anak_ke')->nullable();
            $table->integer('jumlah_saudara_kandung')->nullable();
            // Data Prestasi/Khusus
            $table->text('hobi')->nullable();
            $table->string('cita_cita', 100)->nullable();

            // Data Kesehatan Mental
            $table->text('kepribadian')->nullable();
            $table->text('kesulitan_belajar')->nullable();

            $table->timestamps();

            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_details');
    }
};
