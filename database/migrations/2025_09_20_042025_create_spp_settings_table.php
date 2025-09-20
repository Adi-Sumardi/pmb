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
        Schema::create('spp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('school_level'); // playgroup, tk, sd, smp, sma
            $table->enum('school_origin', ['internal', 'external']); // Internal/External
            $table->decimal('amount', 15, 2); // SPP amount
            $table->string('academic_year')->default('2025/2026'); // Academic year
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['school_level', 'school_origin', 'status']);
            $table->index(['academic_year', 'status']);
            $table->index('status');

            // Unique constraint to prevent duplicate settings
            $table->unique(['school_level', 'school_origin', 'academic_year'], 'unique_spp_setting');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spp_settings');
    }
};
