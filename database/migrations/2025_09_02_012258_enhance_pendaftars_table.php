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
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->string('current_status')->after('asal_sekolah')->nullable();
            $table->enum('overall_status', ['Draft', 'Diverifikasi', 'Sudah Bayar', 'Observasi', 'Tes Tulis', 'Praktek Shalat & BTQ', 'Wawancara', 'Psikotest', 'Lulus', 'Tidak Lulus'])->default('Draft')->after('current_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn(['current_status', 'overall_status']);
        });
    }
};
