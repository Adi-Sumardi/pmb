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
        Schema::table('payments', function (Blueprint $table) {
            // Add new timestamp columns
            $table->timestamp('expires_at')->nullable()->after('amount');
            $table->timestamp('expired_at')->nullable()->after('paid_at');
            $table->timestamp('failed_at')->nullable()->after('expired_at');

            // Update status column to enum with more options
            $table->dropColumn('status');
        });

        // Add status column back with enum
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('status', ['PENDING', 'PAID', 'EXPIRED', 'FAILED', 'CANCELLED'])
                  ->default('PENDING')
                  ->after('amount');
        });

        // Add new indexes
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['pendaftar_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Remove added columns
            $table->dropColumn(['expires_at', 'expired_at', 'failed_at']);

            // Remove indexes
            $table->dropIndex(['pendaftar_id', 'status']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['expires_at']);

            // Revert status to string
            $table->dropColumn('status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('status')->default('PENDING')->after('amount');
        });
    }
};
