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
        Schema::create('multi_payments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // SPP, Uang Pangkal, Seragam, Buku, etc.
            $table->string('school_level'); // SD, SMP, SMA, etc.
            $table->decimal('amount', 15, 2); // Payment amount
            $table->boolean('is_mandatory')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0); // For ordering display
            $table->timestamps();

            // Indexes for better performance
            $table->index(['category', 'school_level', 'status']);
            $table->index(['is_mandatory', 'status']);
            $table->index(['school_level', 'status']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multi_payments');
    }
};
