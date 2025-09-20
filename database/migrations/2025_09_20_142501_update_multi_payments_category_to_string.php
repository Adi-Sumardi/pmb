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
        Schema::table('multi_payments', function (Blueprint $table) {
            // Drop the existing enum column
            $table->dropColumn('category');
        });

        Schema::table('multi_payments', function (Blueprint $table) {
            // Add new string column for unlimited category support
            $table->string('category')->after('name')->comment('Payment category - unlimited options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('multi_payments', function (Blueprint $table) {
            $table->dropColumn('category');
        });

        Schema::table('multi_payments', function (Blueprint $table) {
            // Restore enum with limited options
            $table->enum('category', ['books', 'uniforms', 'supplies', 'others'])->after('name');
        });
    }
};
