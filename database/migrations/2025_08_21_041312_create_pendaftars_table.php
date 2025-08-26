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
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('no_pendaftaran')->unique();
            // Data murid
            $table->string('nama_murid');
            $table->string('nisn', 20)->nullable()->unique();
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('jenjang'); // contoh: SD / SMP / SMA
            $table->string('unit'); // unit/sekolah tujuan
            $table->string('asal_sekolah')->nullable();
            $table->string('nama_sekolah')->nullable();
            $table->string('kelas')->nullable();

            // Data orang tua
            $table->string('nama_ayah');
            $table->string('telp_ayah', 20);
            $table->string('nama_ibu');
            $table->string('telp_ibu', 20);

            $table->string('foto_murid_path')->nullable();
            $table->string('foto_murid_mime')->nullable();
            $table->integer('foto_murid_size')->nullable();

            $table->string('akta_kelahiran_path')->nullable();
            $table->string('akta_kelahiran_mime')->nullable();
            $table->integer('akta_kelahiran_size')->nullable();

            $table->string('kartu_keluarga_path')->nullable();
            $table->string('kartu_keluarga_mime')->nullable();
            $table->integer('kartu_keluarga_size')->nullable();

            $table->string('bukti_pendaftaran')->nullable();
            $table->string('bukti_pendaftaran_path')->nullable();
            $table->string('bukti_pendaftaran_mime')->nullable();
            $table->integer('bukti_pendaftaran_size')->nullable();

            // Status
            $table->decimal('payment_amount', 10, 0)->default(0);
            $table->enum('status', ['pending', 'diverifikasi'])->default('pending');
            $table->boolean('sudah_bayar_formulir')->default(false);

            $table->timestamps();

            // Index agar pencarian lebih cepat
            $table->index(['nama_murid', 'nisn']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};
