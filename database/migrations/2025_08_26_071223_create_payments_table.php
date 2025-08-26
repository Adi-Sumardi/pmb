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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained()->onDelete('cascade');
            $table->string('external_id')->unique();
            $table->string('invoice_id')->nullable();
            $table->string('invoice_url')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('PENDING'); // PENDING, PAID, EXPIRED
            $table->timestamp('paid_at')->nullable();
            $table->json('xendit_response')->nullable();
            $table->timestamps();

            $table->index(['external_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
