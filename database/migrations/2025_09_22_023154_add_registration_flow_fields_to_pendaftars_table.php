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
        Schema::table('pendaftars', function (Blueprint $table) {
            // Step 1: Form verification status (existing: status field)
            // Step 2: Login status (managed by user login)
            // Step 3: Form payment status (existing: sudah_bayar_formulir)

            // Step 4: Data completion status
            $table->enum('data_completion_status', [
                'incomplete', 'complete', 'verified'
            ])->default('incomplete')->after('sudah_bayar_formulir');
            $table->timestamp('data_completed_at')->nullable()->after('data_completion_status');
            $table->timestamp('data_verified_at')->nullable()->after('data_completed_at');
            $table->text('data_verification_notes')->nullable()->after('data_verified_at');

            // Step 5: Test management
            $table->enum('test_status', [
                'not_scheduled', 'scheduled', 'in_progress', 'completed', 'absent'
            ])->default('not_scheduled')->after('data_verification_notes');
            $table->datetime('test_scheduled_at')->nullable()->after('test_status');
            $table->decimal('test_score', 5, 2)->nullable()->after('test_scheduled_at');
            $table->text('test_notes')->nullable()->after('test_score');

            // Step 6: Acceptance status
            $table->enum('acceptance_status', [
                'pending', 'accepted', 'rejected', 'waiting_list'
            ])->default('pending')->after('test_notes');
            $table->timestamp('acceptance_decided_at')->nullable()->after('acceptance_status');
            $table->text('acceptance_notes')->nullable()->after('acceptance_decided_at');

            // Step 7: Uang Pangkal billing status
            $table->enum('uang_pangkal_status', [
                'not_generated', 'pending', 'partial', 'paid', 'overdue'
            ])->default('not_generated')->after('acceptance_notes');
            $table->decimal('uang_pangkal_total', 12, 2)->nullable()->after('uang_pangkal_status');
            $table->decimal('uang_pangkal_paid', 12, 2)->default(0)->after('uang_pangkal_total');
            $table->decimal('uang_pangkal_remaining', 12, 2)->nullable()->after('uang_pangkal_paid');
            $table->timestamp('uang_pangkal_generated_at')->nullable()->after('uang_pangkal_remaining');
            $table->timestamp('uang_pangkal_due_date')->nullable()->after('uang_pangkal_generated_at');

            // Step 8: SPP and other recurring payments
            $table->enum('spp_status', [
                'not_active', 'active', 'suspended'
            ])->default('not_active')->after('uang_pangkal_due_date');
            $table->timestamp('spp_activated_at')->nullable()->after('spp_status');

            // Overall registration progress tracking
            $table->enum('registration_stage', [
                'form_submitted',           // Form pendaftaran disubmit
                'admin_verification',       // Menunggu verifikasi admin
                'payment_form',            // Harus bayar formulir
                'data_completion',         // Mengisi data lengkap
                'data_validation',         // Admin validasi data
                'test_phase',              // Tahap test
                'acceptance_review',       // Review penerimaan
                'accepted',                // Diterima - tagihan uang pangkal muncul
                'uang_pangkal_payment',    // Proses pembayaran uang pangkal
                'enrolled',                // Terdaftar penuh - SPP aktif
                'rejected'                 // Ditolak
            ])->default('form_submitted')->after('spp_activated_at');

            // Tracking timestamps for audit
            $table->timestamp('last_stage_updated_at')->nullable()->after('registration_stage');
            $table->json('stage_history')->nullable()->after('last_stage_updated_at');

            // Admin who handled each stage
            $table->unsignedBigInteger('verified_by')->nullable()->after('stage_history');
            $table->unsignedBigInteger('data_verified_by')->nullable()->after('verified_by');
            $table->unsignedBigInteger('test_managed_by')->nullable()->after('data_verified_by');
            $table->unsignedBigInteger('acceptance_decided_by')->nullable()->after('test_managed_by');

            // Foreign key constraints
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('data_verified_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('test_managed_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('acceptance_decided_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for better performance
            $table->index(['registration_stage', 'created_at']);
            $table->index(['data_completion_status', 'data_completed_at']);
            $table->index(['test_status', 'test_scheduled_at']);
            $table->index(['acceptance_status', 'acceptance_decided_at']);
            $table->index(['uang_pangkal_status', 'uang_pangkal_due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['data_verified_by']);
            $table->dropForeign(['test_managed_by']);
            $table->dropForeign(['acceptance_decided_by']);

            // Drop indexes
            $table->dropIndex(['registration_stage', 'created_at']);
            $table->dropIndex(['data_completion_status', 'data_completed_at']);
            $table->dropIndex(['test_status', 'test_scheduled_at']);
            $table->dropIndex(['acceptance_status', 'acceptance_decided_at']);
            $table->dropIndex(['uang_pangkal_status', 'uang_pangkal_due_date']);

            // Drop all added columns
            $table->dropColumn([
                'data_completion_status',
                'data_completed_at',
                'data_verified_at',
                'data_verification_notes',
                'test_status',
                'test_scheduled_at',
                'test_score',
                'test_notes',
                'acceptance_status',
                'acceptance_decided_at',
                'acceptance_notes',
                'uang_pangkal_status',
                'uang_pangkal_total',
                'uang_pangkal_paid',
                'uang_pangkal_remaining',
                'uang_pangkal_generated_at',
                'uang_pangkal_due_date',
                'spp_status',
                'spp_activated_at',
                'registration_stage',
                'last_stage_updated_at',
                'stage_history',
                'verified_by',
                'data_verified_by',
                'test_managed_by',
                'acceptance_decided_by'
            ]);
        });
    }
};
