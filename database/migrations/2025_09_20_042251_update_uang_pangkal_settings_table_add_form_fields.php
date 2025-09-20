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
        Schema::table('uang_pangkal_settings', function (Blueprint $table) {
            $table->string('academic_year')->default('2025/2026')->after('amount');
            $table->boolean('allow_installments')->default(false)->after('academic_year');
            $table->integer('max_installments')->default(2)->after('allow_installments');
            $table->decimal('first_installment_percentage', 5, 2)->default(50.00)->after('max_installments');
            $table->boolean('include_registration')->default(true)->after('first_installment_percentage');
            $table->boolean('include_uniform')->default(false)->after('include_registration');
            $table->boolean('include_books')->default(false)->after('include_uniform');
            $table->boolean('include_supplies')->default(false)->after('include_books');

            // Add indexes
            $table->index(['academic_year', 'status']);
            $table->index(['allow_installments', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('uang_pangkal_settings', function (Blueprint $table) {
            $table->dropColumn([
                'academic_year',
                'allow_installments',
                'max_installments',
                'first_installment_percentage',
                'include_registration',
                'include_uniform',
                'include_books',
                'include_supplies'
            ]);
        });
    }
};
