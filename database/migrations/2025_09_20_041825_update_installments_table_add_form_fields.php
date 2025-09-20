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
        Schema::table('installments', function (Blueprint $table) {
            $table->enum('payment_interval', ['monthly', 'bi_monthly', 'quarterly', 'semi_annually'])->default('monthly')->after('first_payment_percentage');
            $table->decimal('late_fee_percentage', 5, 2)->default(0)->after('payment_interval'); // 0-10%
            $table->integer('grace_period_days')->default(7)->after('late_fee_percentage'); // 0-30 days
            $table->boolean('auto_reminder')->default(true)->after('grace_period_days');
            $table->boolean('allow_early_payment')->default(true)->after('auto_reminder');
            $table->boolean('penalty_accumulative')->default(false)->after('allow_early_payment');

            // Add indexes
            $table->index(['payment_interval', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_interval',
                'late_fee_percentage',
                'grace_period_days',
                'auto_reminder',
                'allow_early_payment',
                'penalty_accumulative'
            ]);
        });
    }
};
