<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_level',
        'installment_count',
        'first_payment_percentage',
        'payment_interval',
        'late_fee_percentage',
        'grace_period_days',
        'auto_reminder',
        'allow_early_payment',
        'penalty_accumulative',
        'status',
        'description'
    ];

    protected $casts = [
        'installment_count' => 'integer',
        'first_payment_percentage' => 'decimal:2',
        'late_fee_percentage' => 'decimal:2',
        'grace_period_days' => 'integer',
        'auto_reminder' => 'boolean',
        'allow_early_payment' => 'boolean',
        'penalty_accumulative' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBySchoolLevel($query, $level)
    {
        return $query->where('school_level', $level);
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getRemainingPaymentPercentageAttribute()
    {
        return 100 - $this->first_payment_percentage;
    }

    public function getInstallmentPercentageAttribute()
    {
        if ($this->installment_count <= 1) {
            return 0;
        }

        return $this->remaining_payment_percentage / ($this->installment_count - 1);
    }

    // Methods
    public function calculateFirstPayment($totalAmount)
    {
        return ($totalAmount * $this->first_payment_percentage) / 100;
    }

    public function calculateInstallmentAmount($totalAmount)
    {
        if ($this->installment_count <= 1) {
            return 0;
        }

        $remainingAmount = $totalAmount - $this->calculateFirstPayment($totalAmount);
        return $remainingAmount / ($this->installment_count - 1);
    }

    public function getPaymentSchedule($totalAmount)
    {
        $schedule = [];

        // First payment
        $schedule[] = [
            'installment' => 1,
            'amount' => $this->calculateFirstPayment($totalAmount),
            'percentage' => $this->first_payment_percentage,
            'type' => 'first_payment'
        ];

        // Remaining installments
        $installmentAmount = $this->calculateInstallmentAmount($totalAmount);
        $installmentPercentage = $this->installment_percentage;

        for ($i = 2; $i <= $this->installment_count; $i++) {
            $schedule[] = [
                'installment' => $i,
                'amount' => $installmentAmount,
                'percentage' => $installmentPercentage,
                'type' => 'installment'
            ];
        }

        return $schedule;
    }
}
