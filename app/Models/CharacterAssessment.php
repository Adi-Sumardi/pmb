<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_report_id', 'sikap_spiritual', 'deskripsi_spiritual',
        'sikap_sosial', 'deskripsi_sosial', 'jujur', 'disiplin',
        'tanggung_jawab', 'santun', 'peduli', 'percaya_diri'
    ];

    public function gradeReport()
    {
        return $this->belongsTo(GradeReport::class);
    }

    public function getPredikatText($value)
    {
        return match($value) {
            'SB' => 'Sangat Baik',
            'B' => 'Baik',
            'C' => 'Cukup',
            'K' => 'Kurang',
            default => '-'
        };
    }

    public function getPredikatColor($value)
    {
        return match($value) {
            'SB' => 'success',
            'B' => 'info',
            'C' => 'warning',
            'K' => 'danger',
            default => 'secondary'
        };
    }
}
