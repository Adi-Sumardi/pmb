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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2); // For percentage or fixed amount
            $table->decimal('minimum_amount', 10, 2)->nullable(); // Minimum purchase amount
            $table->integer('max_usage')->nullable(); // Maximum usage limit
            $table->datetime('valid_from')->nullable();
            $table->datetime('valid_until')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['status', 'valid_from', 'valid_until']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
