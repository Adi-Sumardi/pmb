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
        Schema::create('academic_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel', 20)->unique();
            $table->string('nama_mapel', 100);
            $table->enum('kategori', ['Wajib', 'Muatan Lokal', 'Pilihan'])->default('Wajib');
            $table->enum('jenjang', ['SD', 'SMP', 'SMA', 'SMK']);
            $table->integer('kelas_mulai')->nullable();
            $table->integer('kelas_selesai')->nullable();
            $table->decimal('kkm', 4, 2)->default(75.00);
            $table->decimal('bobot_ujian', 3, 2)->default(0.30);
            $table->decimal('bobot_tugas', 3, 2)->default(0.40);
            $table->decimal('bobot_harian', 3, 2)->default(0.30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_subjects');
    }
};
