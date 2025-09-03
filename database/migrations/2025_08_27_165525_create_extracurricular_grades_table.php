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
        Schema::create('extracurricular_grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pendaftar_id');

            $table->string('nama_ekstrakurikuler', 100);
            $table->enum('kategori', ['Wajib', 'Pilihan'])->default('Pilihan');
            $table->string('pembina', 100)->nullable();

            $table->enum('nilai', ['A', 'B', 'C', 'D']);
            $table->enum('predikat', ['Sangat Baik', 'Baik', 'Cukup', 'Kurang']);
            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->foreign('pendaftar_id')->references('id')->on('pendaftars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extracurricular_grades');
    }
};
