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
        Schema::create('character_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grade_report_id');

            // Sikap Utama
            $table->enum('sikap_spiritual', ['SB', 'B', 'C', 'K']);
            $table->text('deskripsi_spiritual')->nullable();
            $table->enum('sikap_sosial', ['SB', 'B', 'C', 'K']);
            $table->text('deskripsi_sosial')->nullable();

            // Detail Sikap
            $table->enum('jujur', ['SB', 'B', 'C', 'K'])->nullable();
            $table->enum('disiplin', ['SB', 'B', 'C', 'K'])->nullable();
            $table->enum('tanggung_jawab', ['SB', 'B', 'C', 'K'])->nullable();
            $table->enum('santun', ['SB', 'B', 'C', 'K'])->nullable();
            $table->enum('peduli', ['SB', 'B', 'C', 'K'])->nullable();
            $table->enum('percaya_diri', ['SB', 'B', 'C', 'K'])->nullable();

            $table->timestamps();

            $table->foreign('grade_report_id')->references('id')->on('grade_reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_assessments');
    }
};
