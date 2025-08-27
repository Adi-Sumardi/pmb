<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_mapel', 'nama_mapel', 'kategori', 'jenjang', 'kelas_mulai',
        'kelas_selesai', 'kkm', 'bobot_ujian', 'bobot_tugas', 'bobot_harian', 'is_active'
    ];

    protected $casts = [
        'kkm' => 'decimal:2',
        'bobot_ujian' => 'decimal:2',
        'bobot_tugas' => 'decimal:2',
        'bobot_harian' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function subjectGrades()
    {
        return $this->hasMany(SubjectGrade::class);
    }

    public function scopeByJenjang($query, $jenjang)
    {
        return $query->where('jenjang', $jenjang);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
