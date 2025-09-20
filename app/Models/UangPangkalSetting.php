<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UangPangkalSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_level',
        'amount',
        'school_origin',
        'academic_year',
        'allow_installments',
        'max_installments',
        'first_installment_percentage',
        'include_registration',
        'include_uniform',
        'include_books',
        'include_supplies',
        'status',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'first_installment_percentage' => 'decimal:2',
        'allow_installments' => 'boolean',
        'max_installments' => 'integer',
        'include_registration' => 'boolean',
        'include_uniform' => 'boolean',
        'include_books' => 'boolean',
        'include_supplies' => 'boolean',
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

    public function scopeBySchoolOrigin($query, $origin)
    {
        return $query->where('school_origin', $origin);
    }

    public function scopeForInternal($query)
    {
        return $query->where('school_origin', 'internal');
    }

    public function scopeForExternal($query)
    {
        return $query->where('school_origin', 'external');
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

    public function getIsInternalAttribute()
    {
        return $this->school_origin === 'internal';
    }

    public function getIsExternalAttribute()
    {
        return $this->school_origin === 'external';
    }

    // Methods
    public function isApplicableFor($schoolLevel, $schoolOrigin)
    {
        return $this->is_active &&
               $this->school_level === $schoolLevel &&
               $this->school_origin === $schoolOrigin;
    }

    // Static methods
    public static function getForLevel($schoolLevel)
    {
        return static::active()
                    ->bySchoolLevel($schoolLevel)
                    ->orderBy('school_origin')
                    ->get();
    }

    public static function getAmount($schoolLevel, $schoolOrigin)
    {
        $setting = static::active()
                        ->bySchoolLevel($schoolLevel)
                        ->bySchoolOrigin($schoolOrigin)
                        ->first();

        return $setting ? $setting->amount : 0;
    }

    public static function getInternalAmount($schoolLevel)
    {
        return static::getAmount($schoolLevel, 'internal');
    }

    public static function getExternalAmount($schoolLevel)
    {
        return static::getAmount($schoolLevel, 'external');
    }

    public static function getActiveSettings()
    {
        return static::active()
                    ->orderBy('school_level')
                    ->orderBy('school_origin')
                    ->get();
    }
}
