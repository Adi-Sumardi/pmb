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
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftar_id');

            // Riwayat Kesehatan
            $table->text('riwayat_penyakit')->nullable();
            $table->text('alergi')->nullable();
            $table->text('obat_yang_dikonsumsi')->nullable();
            $table->enum('cacat_jasmani', ['Tidak Ada', 'Ringan', 'Sedang', 'Berat'])->default('Tidak Ada');
            $table->text('keterangan_cacat')->nullable();

            // Imunisasi
            $table->boolean('bcg')->default(false);
            $table->boolean('polio')->default(false);
            $table->boolean('dpt')->default(false);
            $table->boolean('campak')->default(false);
            $table->boolean('hepatitis_b')->default(false);

            // Kondisi Khusus
            $table->enum('kebutuhan_khusus', ['Tidak Ada', 'Lamban Belajar', 'Kesulitan Belajar', 'Berbakat', 'Lainnya'])->default('Tidak Ada');
            $table->text('keterangan_kebutuhan_khusus')->nullable();

            // Data Kesehatan Fisik
            $table->text('kondisi_mata')->nullable();
            $table->text('kondisi_telinga')->nullable();
            $table->text('kondisi_gigi')->nullable();

            $table->timestamps();

            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
