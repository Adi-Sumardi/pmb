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
        Schema::table('spp_bulk_settings', function (Blueprint $table) {
            $table->string('academic_year')->default('2025/2026')->after('discount_percentage');
            $table->date('start_date')->nullable()->after('academic_year');
            $table->date('end_date')->nullable()->after('start_date');
            $table->boolean('allow_partial_refund')->default(false)->after('end_date');
            $table->boolean('auto_apply_discount')->default(true)->after('allow_partial_refund');
            $table->boolean('show_savings_info')->default(true)->after('auto_apply_discount');
            $table->decimal('min_payment_amount', 15, 2)->nullable()->after('show_savings_info');
            $table->decimal('max_payment_amount', 15, 2)->nullable()->after('min_payment_amount');

            // Add indexes
            $table->index(['academic_year', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spp_bulk_settings', function (Blueprint $table) {
            $table->dropColumn([
                'academic_year',
                'start_date',
                'end_date',
                'allow_partial_refund',
                'auto_apply_discount',
                'show_savings_info',
                'min_payment_amount',
                'max_payment_amount'
            ]);
        });
    }
};
