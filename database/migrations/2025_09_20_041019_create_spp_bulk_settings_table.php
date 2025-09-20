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
        Schema::create('spp_bulk_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('school_level'); // SD, SMP, SMA, etc.
            $table->enum('months_count', ['3', '6', '12']); // 3, 6, or 12 months
            $table->decimal('discount_percentage', 5, 2); // 0.00 - 50.00%
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['school_level', 'status']);
            $table->index(['months_count', 'status']);
            $table->index('status');

            // Unique constraint to prevent duplicate settings
            $table->unique(['school_level', 'months_count'], 'unique_school_level_months');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spp_bulk_settings');
    }
};
