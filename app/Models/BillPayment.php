<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class BillPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_bill_id',
        'pendaftar_id',
        'payment_number',
        'external_transaction_id',
        'invoice_id',
        'amount',
        'payment_method',
        'payment_channel',
        'installment_number',
        'is_partial_payment',
        'status',
        'gateway_response',
        'gateway_reference',
        'payment_date',
        'confirmed_at',
        'failed_at',
        'expired_at',
        'receipt_file_path',
        'receipt_file_name',
        'receipt_file_mime',
        'receipt_file_size',
        'verified_by',
        'verified_at',
        'verification_notes',
        'rejection_reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_partial_payment' => 'boolean',
        'gateway_response' => 'array',
        'receipt_file_size' => 'integer',
        'installment_number' => 'integer',
        'payment_date' => 'datetime',
        'confirmed_at' => 'datetime',
        'failed_at' => 'datetime',
        'expired_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function studentBill(): BelongsTo
    {
        return $this->belongsTo(StudentBill::class);
    }

    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('verified_at');
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeByInstallment($query, $installmentNumber)
    {
        return $query->where('installment_number', $installmentNumber);
    }

    // Accessors
    public function getIsVerifiedAttribute(): bool
    {
        return !is_null($this->verified_at);
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getIsFailedAttribute(): bool
    {
        return $this->status === 'failed';
    }

    public function getFormattedPaymentDateAttribute(): string
    {
        return $this->payment_date ? $this->payment_date->format('d M Y H:i') : '-';
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getReceiptUrlAttribute(): ?string
    {
        return $this->receipt_file_path ? asset('storage/' . $this->receipt_file_path) : null;
    }

    // Methods
    public function markAsCompleted(?User $verifiedBy = null): void
    {
        $this->status = 'completed';
        $this->confirmed_at = now();

        if ($verifiedBy) {
            $this->verified_by = $verifiedBy->id;
            $this->verified_at = now();
        }

        $this->save();

        // Update parent bill payment status
        $this->studentBill->updatePaymentStatus();
    }

    public function markAsFailed(?string $reason = null): void
    {
        $this->status = 'failed';
        $this->failed_at = now();

        if ($reason) {
            $this->rejection_reason = $reason;
        }

        $this->save();
    }

    public function verify(User $admin, ?string $notes = null): void
    {
        $this->verified_by = $admin->id;
        $this->verified_at = now();

        if ($notes) {
            $this->verification_notes = $notes;
        }

        $this->save();
    }

    public function generatePaymentNumber(): string
    {
        $year = date('Y');
        $sequence = static::whereYear('created_at', $year)->count() + 1;

        return sprintf('PAY-%s-%06d', $year, $sequence);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (!$payment->payment_number) {
                $payment->payment_number = $payment->generatePaymentNumber();
            }
            if (!$payment->payment_date) {
                $payment->payment_date = now();
            }
        });

        static::updated(function ($payment) {
            // Update parent bill when payment status changes
            if ($payment->isDirty('status') && $payment->status === 'completed') {
                $payment->studentBill->updatePaymentStatus();
            }
        });
    }
}
