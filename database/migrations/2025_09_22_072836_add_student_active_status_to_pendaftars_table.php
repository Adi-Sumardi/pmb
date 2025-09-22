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
            // Add student active status for enrolled students
            $table->enum('student_status', [
                'inactive', 'active', 'graduated', 'dropped_out', 'transferred'
            ])->default('inactive')->after('uang_pangkal_paid_at');

            $table->timestamp('student_activated_at')->nullable()->after('student_status');
            $table->text('student_status_notes')->nullable()->after('student_activated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn(['student_status', 'student_activated_at', 'student_status_notes']);
        });
    }
};
