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
        Schema::create('bill_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_bill_id')->constrained('student_bills')->onDelete('cascade');
            $table->foreignId('pendaftar_id')->constrained('pendaftars')->onDelete('cascade');

            // Payment tracking
            $table->string('payment_number')->unique(); // Format: PAY-2025-0001
            $table->string('external_transaction_id')->nullable(); // ID dari gateway pembayaran
            $table->string('invoice_id')->nullable(); // ID invoice dari Xendit/gateway

            // Payment details
            $table->decimal('amount', 12, 2); // Nominal pembayaran
            $table->enum('payment_method', [
                'bank_transfer',
                'virtual_account',
                'e_wallet',
                'credit_card',
                'cash',
                'check',
                'other'
            ]);
            $table->string('payment_channel')->nullable(); // BCA, BRI, OVO, dll

            // Installment info
            $table->integer('installment_number')->nullable(); // Cicilan ke berapa
            $table->boolean('is_partial_payment')->default(false); // Apakah pembayaran sebagian

            // Status
            $table->enum('status', [
                'pending',      // Menunggu konfirmasi
                'processing',   // Sedang diproses
                'completed',    // Berhasil
                'failed',       // Gagal
                'cancelled',    // Dibatalkan
                'refunded'      // Dikembalikan
            ])->default('pending');

            // Gateway response
            $table->json('gateway_response')->nullable(); // Response dari payment gateway
            $table->string('gateway_reference')->nullable(); // Reference number dari gateway

            // Timestamps
            $table->timestamp('payment_date'); // Tanggal pembayaran
            $table->timestamp('confirmed_at')->nullable(); // Kapan dikonfirmasi
            $table->timestamp('failed_at')->nullable(); // Kapan gagal
            $table->timestamp('expired_at')->nullable(); // Kapan expired

            // File uploads
            $table->string('receipt_file_path')->nullable(); // Bukti pembayaran
            $table->string('receipt_file_name')->nullable();
            $table->string('receipt_file_mime')->nullable();
            $table->integer('receipt_file_size')->nullable();

            // Admin verification
            $table->unsignedBigInteger('verified_by')->nullable(); // Admin yang verifikasi
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->text('rejection_reason')->nullable(); // Alasan ditolak jika ada

            $table->timestamps();

            // Foreign keys
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');

            // Indexes
            $table->index(['student_bill_id', 'status']);
            $table->index(['pendaftar_id', 'payment_date']);
            $table->index(['status', 'payment_date']);
            $table->index(['payment_method', 'status']);
            $table->index(['installment_number', 'student_bill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_payments');
    }
};
