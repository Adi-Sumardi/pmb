<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_level',
        'school_origin',
        'amount',
        'academic_year',
        'status',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
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

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
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
    public function isApplicableFor($schoolLevel, $schoolOrigin, $academicYear = null)
    {
        $year = $academicYear ?? '2025/2026';

        return $this->is_active &&
               $this->school_level === $schoolLevel &&
               $this->school_origin === $schoolOrigin &&
               $this->academic_year === $year;
    }

    // Static methods
    public static function getForLevel($schoolLevel, $academicYear = null)
    {
        $year = $academicYear ?? '2025/2026';

        return static::active()
                    ->bySchoolLevel($schoolLevel)
                    ->byAcademicYear($year)
                    ->orderBy('school_origin')
                    ->get();
    }

    public static function getAmount($schoolLevel, $schoolOrigin, $academicYear = null)
    {
        $year = $academicYear ?? '2025/2026';

        $setting = static::active()
                        ->bySchoolLevel($schoolLevel)
                        ->bySchoolOrigin($schoolOrigin)
                        ->byAcademicYear($year)
                        ->first();

        return $setting ? $setting->amount : 0;
    }

    public static function getCurrentYearSettings()
    {
        return static::active()
                    ->byAcademicYear('2025/2026')
                    ->orderBy('school_level')
                    ->orderBy('school_origin')
                    ->get();
    }
}
