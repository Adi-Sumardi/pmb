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
        Schema::create('student_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftars')->onDelete('cascade');
            $table->foreignId('uang_pangkal_setting_id')->nullable()->constrained('uang_pangkal_settings')->onDelete('set null');

            // Bill details
            $table->string('bill_number')->unique(); // Format: UP-2025-0001
            $table->enum('bill_type', [
                'uang_pangkal',          // Uang pangkal
                'spp',                   // SPP bulanan
                'registration_fee',      // Biaya pendaftaran/formulir
                'uniform',               // Seragam
                'books',                 // Buku
                'supplies',              // Alat tulis
                'activity',              // Kegiatan
                'other'                  // Lainnya
            ]);
            $table->string('description');

            // Financial details
            $table->decimal('total_amount', 12, 2);     // Total tagihan
            $table->decimal('paid_amount', 12, 2)->default(0);     // Sudah dibayar
            $table->decimal('remaining_amount', 12, 2); // Sisa tagihan (computed)
            $table->decimal('discount_amount', 12, 2)->default(0); // Diskon
            $table->decimal('late_fee', 12, 2)->default(0);        // Denda keterlambatan

            // Payment status
            $table->enum('payment_status', [
                'pending',      // Belum dibayar
                'partial',      // Dibayar sebagian
                'paid',         // Lunas
                'overdue',      // Terlambat
                'cancelled',    // Dibatalkan
                'waived'        // Dibebaskan
            ])->default('pending');

            // Installment support
            $table->boolean('allow_installments')->default(false);
            $table->integer('total_installments')->nullable(); // Total cicilan
            $table->integer('paid_installments')->default(0);  // Cicilan yang sudah dibayar
            $table->decimal('installment_amount', 12, 2)->nullable(); // Nominal per cicilan

            // Due dates
            $table->date('due_date');
            $table->date('grace_period_end')->nullable(); // Akhir masa tenggang
            $table->date('late_fee_start_date')->nullable(); // Mulai denda

            // Academic period
            $table->string('academic_year'); // 2025/2026
            $table->enum('semester', ['ganjil', 'genap'])->nullable();
            $table->integer('month')->nullable(); // Untuk SPP bulanan

            // Timestamps
            $table->timestamp('issued_at'); // Kapan tagihan diterbitkan
            $table->timestamp('paid_at')->nullable(); // Kapan lunas
            $table->timestamp('first_payment_at')->nullable(); // Pembayaran pertama
            $table->timestamp('last_payment_at')->nullable(); // Pembayaran terakhir

            // Admin tracking
            $table->unsignedBigInteger('issued_by')->nullable(); // Admin yang menerbitkan
            $table->unsignedBigInteger('verified_by')->nullable(); // Admin yang verifikasi
            $table->text('notes')->nullable(); // Catatan

            $table->timestamps();

            // Foreign keys
            $table->foreign('issued_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for performance
            $table->index(['pendaftar_id', 'bill_type']);
            $table->index(['payment_status', 'due_date']);
            $table->index(['academic_year', 'semester']);
            $table->index(['bill_type', 'due_date']);
            $table->index(['payment_status', 'pendaftar_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_bills');
    }
};
