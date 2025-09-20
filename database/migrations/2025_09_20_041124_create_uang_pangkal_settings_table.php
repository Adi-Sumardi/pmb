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
        Schema::create('uang_pangkal_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('school_level'); // SD, SMP, SMA, etc.
            $table->decimal('amount', 15, 2); // Uang pangkal amount
            $table->enum('school_origin', ['internal', 'external']); // Internal/External student
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['school_level', 'school_origin', 'status']);
            $table->index(['school_origin', 'status']);
            $table->index('status');

            // Unique constraint to prevent duplicate settings
            $table->unique(['school_level', 'school_origin'], 'unique_school_level_origin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uang_pangkal_settings');
    }
};
