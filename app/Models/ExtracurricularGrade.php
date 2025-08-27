<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtracurricularGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_report_id', 'nama_ekstrakurikuler', 'kategori', 'pembina',
        'nilai', 'predikat', 'keterangan'
    ];

    public function gradeReport()
    {
        return $this->belongsTo(GradeReport::class);
    }

    public function getPredikatColorAttribute()
    {
        return match($this->predikat) {
            'Sangat Baik' => 'success',
            'Baik' => 'info',
            'Cukup' => 'warning',
            'Kurang' => 'danger',
            default => 'secondary'
        };
    }
}
