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
        Schema::create('subject_grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftar_id');

            // Nilai Komponen
            $table->decimal('nilai_tugas', 4, 2)->nullable();
            $table->decimal('nilai_harian', 4, 2)->nullable();
            $table->decimal('nilai_uts', 4, 2)->nullable();
            $table->decimal('nilai_uas', 4, 2)->nullable();
            $table->decimal('nilai_praktik', 4, 2)->nullable();

            // Nilai Akhir
            $table->decimal('nilai_pengetahuan', 4, 2)->nullable();
            $table->decimal('nilai_keterampilan', 4, 2)->nullable();
            $table->decimal('nilai_akhir', 4, 2);
            $table->enum('predikat', ['A', 'B', 'C', 'D']);

            // Status
            $table->enum('status_ketuntasan', ['Tuntas', 'Belum Tuntas']);
            $table->decimal('remedial_nilai', 4, 2)->nullable();

            // Deskripsi
            $table->text('deskripsi_pengetahuan')->nullable();
            $table->text('deskripsi_keterampilan')->nullable();

            $table->timestamps();

            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_grades');
    }
};
