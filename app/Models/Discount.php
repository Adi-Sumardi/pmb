<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'target',
        'school_level',
        'minimum_amount',
        'max_usage',
        'start_date',
        'end_date',
        'status',
        'description',
        'conditions'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'conditions' => 'array',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where(function ($q) use ($now) {
            $q->where(function ($subQ) use ($now) {
                $subQ->whereNull('start_date')
                     ->orWhere('start_date', '<=', $now);
            })->where(function ($subQ) use ($now) {
                $subQ->whereNull('end_date')
                     ->orWhere('end_date', '>=', $now);
            });
        });
    }

    // Accessors
    public function getIsValidAttribute()
    {
        $now = Carbon::now();
        return $this->status === 'active' &&
               ($this->start_date === null || $this->start_date <= $now) &&
               ($this->end_date === null || $this->end_date >= $now);
    }

    // Methods
    public function calculateDiscount($amount)
    {
        if (!$this->is_valid) {
            return 0;
        }

        if ($this->minimum_amount && $amount < $this->minimum_amount) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($amount * $this->value) / 100;
        }

        return $this->value;
    }

    public function isApplicable($amount)
    {
        return $this->is_valid &&
               ($this->minimum_amount === null || $amount >= $this->minimum_amount);
    }
}
