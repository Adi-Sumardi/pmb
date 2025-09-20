<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'school_level',
        'specific_level',
        'amount',
        'is_mandatory',
        'available_sizes',
        'supplier',
        'stock_quantity',
        'track_stock',
        'status',
        'description',
        'sort_order'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_mandatory' => 'boolean',
        'track_stock' => 'boolean',
        'sort_order' => 'integer',
        'stock_quantity' => 'integer',
        'available_sizes' => 'array',
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

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    public function scopeOptional($query)
    {
        return $query->where('is_mandatory', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format((float) $this->amount, 0, ',', '.');
    }

    // Methods
    public function isMandatoryForLevel($schoolLevel)
    {
        return $this->is_mandatory &&
               $this->is_active &&
               $this->school_level === $schoolLevel;
    }

    public function isAvailableForLevel($schoolLevel)
    {
        return $this->is_active && $this->school_level === $schoolLevel;
    }

    // Static methods
    public static function getActiveBySchoolLevel($schoolLevel)
    {
        return static::active()
                    ->bySchoolLevel($schoolLevel)
                    ->ordered()
                    ->get();
    }

    public static function getMandatoryBySchoolLevel($schoolLevel)
    {
        return static::active()
                    ->bySchoolLevel($schoolLevel)
                    ->mandatory()
                    ->ordered()
                    ->get();
    }

    public static function getOptionalBySchoolLevel($schoolLevel)
    {
        return static::active()
                    ->bySchoolLevel($schoolLevel)
                    ->optional()
                    ->ordered()
                    ->get();
    }

    public static function getByCategory($category, $schoolLevel = null)
    {
        $query = static::active()->byCategory($category);

        if ($schoolLevel) {
            $query->bySchoolLevel($schoolLevel);
        }

        return $query->ordered()->get();
    }
}
