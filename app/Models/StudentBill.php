<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class StudentBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id',
        'uang_pangkal_setting_id',
        'bill_number',
        'bill_type',
        'description',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'discount_amount',
        'late_fee',
        'payment_status',
        'allow_installments',
        'total_installments',
        'paid_installments',
        'installment_amount',
        'due_date',
        'grace_period_end',
        'late_fee_start_date',
        'academic_year',
        'semester',
        'month',
        'issued_at',
        'paid_at',
        'first_payment_at',
        'last_payment_at',
        'issued_by',
        'verified_by',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'installment_amount' => 'decimal:2',
        'allow_installments' => 'boolean',
        'total_installments' => 'integer',
        'paid_installments' => 'integer',
        'month' => 'integer',
        'due_date' => 'date',
        'grace_period_end' => 'date',
        'late_fee_start_date' => 'date',
        'issued_at' => 'datetime',
        'paid_at' => 'datetime',
        'first_payment_at' => 'datetime',
        'last_payment_at' => 'datetime',
    ];

    // Relationships
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function uangPangkalSetting(): BelongsTo
    {
        return $this->belongsTo(UangPangkalSetting::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(BillPayment::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopePartial($query)
    {
        return $query->where('payment_status', 'partial');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('payment_status', 'overdue')
                    ->orWhere(function($q) {
                        $q->whereIn('payment_status', ['pending', 'partial'])
                          ->where('due_date', '<', now());
                    });
    }

    public function scopeByBillType($query, $type)
    {
        return $query->where('bill_type', $type);
    }

    public function scopeForAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    // Accessors & Mutators
    public function getIsOverdueAttribute(): bool
    {
        return $this->payment_status !== 'paid' &&
               $this->due_date &&
               Carbon::parse($this->due_date)->isPast();
    }

    public function getPaymentProgressAttribute(): float
    {
        if ($this->total_amount <= 0) return 0;
        return ($this->paid_amount / $this->total_amount) * 100;
    }

    public function getInstallmentProgressAttribute(): float
    {
        if (!$this->allow_installments || $this->total_installments <= 0) return 0;
        return ($this->paid_installments / $this->total_installments) * 100;
    }

    public function getRemainingInstallmentsAttribute(): int
    {
        if (!$this->allow_installments) return 0;
        return max(0, $this->total_installments - $this->paid_installments);
    }

    // Methods
    public function updatePaymentStatus(): void
    {
        $this->remaining_amount = $this->total_amount - $this->paid_amount - $this->discount_amount;

        if ($this->remaining_amount <= 0) {
            $this->payment_status = 'paid';
            if (!$this->paid_at) {
                $this->paid_at = now();
            }
        } elseif ($this->paid_amount > 0) {
            $this->payment_status = 'partial';
        } elseif ($this->is_overdue) {
            $this->payment_status = 'overdue';
        } else {
            $this->payment_status = 'pending';
        }

        $this->save();
    }

    public function addPayment(float $amount, array $paymentData = []): BillPayment
    {
        $payment = $this->payments()->create(array_merge([
            'pendaftar_id' => $this->pendaftar_id,
            'amount' => $amount,
            'payment_date' => now(),
        ], $paymentData));

        // Update paid amount and installment count
        $this->paid_amount += $amount;
        if ($this->allow_installments) {
            $this->paid_installments++;
        }

        // Set first/last payment timestamps
        if (!$this->first_payment_at) {
            $this->first_payment_at = now();
        }
        $this->last_payment_at = now();

        $this->updatePaymentStatus();

        return $payment;
    }

    public function generateBillNumber(): string
    {
        $prefix = match($this->bill_type) {
            'uang_pangkal' => 'UP',
            'spp' => 'SPP',
            'registration_fee' => 'REG',
            'uniform' => 'UNI',
            'books' => 'BKS',
            'supplies' => 'SPL',
            'activity' => 'ACT',
            default => 'BILL'
        };

        $year = date('Y');
        $sequence = static::whereYear('created_at', $year)
                         ->where('bill_type', $this->bill_type)
                         ->count() + 1;

        return sprintf('%s-%s-%04d', $prefix, $year, $sequence);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bill) {
            if (!$bill->bill_number) {
                $bill->bill_number = $bill->generateBillNumber();
            }
            if (!$bill->issued_at) {
                $bill->issued_at = now();
            }
            // Calculate remaining amount
            $bill->remaining_amount = $bill->total_amount - $bill->paid_amount - $bill->discount_amount;
        });

        static::updating(function ($bill) {
            // Recalculate remaining amount
            $bill->remaining_amount = $bill->total_amount - $bill->paid_amount - $bill->discount_amount;
        });
    }
}
