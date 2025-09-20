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
        Schema::table('discounts', function (Blueprint $table) {
            $table->string('code')->nullable()->after('name'); // Discount code
            $table->enum('target', ['uang_pangkal', 'spp', 'multi_payment', 'all'])->default('all')->after('value'); // Target payment type
            $table->string('school_level')->nullable()->after('target'); // School level filter
            $table->json('conditions')->nullable()->after('description'); // Terms and conditions

            // Add indexes for new fields
            $table->index('code');
            $table->index(['target', 'school_level']);
        });

        // Rename columns in separate statements to avoid issues
        Schema::table('discounts', function (Blueprint $table) {
            $table->renameColumn('valid_from', 'start_date');
            $table->renameColumn('valid_until', 'end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->renameColumn('start_date', 'valid_from');
            $table->renameColumn('end_date', 'valid_until');
        });

        Schema::table('discounts', function (Blueprint $table) {
            $table->dropIndex(['discounts_code_index']);
            $table->dropIndex(['discounts_target_school_level_index']);
            $table->dropColumn(['code', 'target', 'school_level', 'conditions']);
        });
    }
};
