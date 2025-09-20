<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppBulkSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_level',
        'months_count',
        'discount_percentage',
        'academic_year',
        'start_date',
        'end_date',
        'allow_partial_refund',
        'auto_apply_discount',
        'show_savings_info',
        'min_payment_amount',
        'max_payment_amount',
        'status',
        'description'
    ];

    protected $casts = [
        'months_count' => 'integer',
        'discount_percentage' => 'decimal:2',
        'min_payment_amount' => 'decimal:2',
        'max_payment_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'allow_partial_refund' => 'boolean',
        'auto_apply_discount' => 'boolean',
        'show_savings_info' => 'boolean',
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

    public function scopeByMonthsCount($query, $months)
    {
        return $query->where('months_count', $months);
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getFormattedDiscountAttribute()
    {
        return number_format((float) $this->discount_percentage, 1) . '%';
    }

    // Methods
    public function calculateDiscount($baseAmount)
    {
        return ($baseAmount * $this->discount_percentage) / 100;
    }

    public function calculateDiscountedAmount($baseAmount)
    {
        return $baseAmount - $this->calculateDiscount($baseAmount);
    }

    public function getTotalAmount($monthlyAmount)
    {
        $totalAmount = $monthlyAmount * $this->months_count;
        return $this->calculateDiscountedAmount($totalAmount);
    }

    public function getSavings($monthlyAmount)
    {
        $totalWithoutDiscount = $monthlyAmount * $this->months_count;
        return $this->calculateDiscount($totalWithoutDiscount);
    }

    // Static methods
    public static function getActiveBySchoolLevel($schoolLevel)
    {
        return static::active()
                    ->bySchoolLevel($schoolLevel)
                    ->orderBy('months_count')
                    ->get();
    }

    public static function getBestDeal($schoolLevel)
    {
        return static::active()
                    ->bySchoolLevel($schoolLevel)
                    ->orderBy('discount_percentage', 'desc')
                    ->first();
    }

    public static function getByMonths($schoolLevel, $months)
    {
        return static::active()
                    ->bySchoolLevel($schoolLevel)
                    ->byMonthsCount($months)
                    ->first();
    }
}
