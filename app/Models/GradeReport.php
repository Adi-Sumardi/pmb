<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id', 'tahun_ajaran', 'semester', 'kelas', 'jenjang',
        'nama_sekolah', 'npsn', 'alamat_sekolah', 'nama_kepala_sekolah',
        'nip_kepala_sekolah', 'nama_wali_kelas', 'nip_wali_kelas',
        'rata_rata_kelas', 'ranking_kelas', 'jumlah_siswa_kelas',
        'sakit', 'izin', 'tanpa_keterangan', 'status_kenaikan',
        'catatan_wali_kelas', 'catatan_orang_tua', 'tanggal_raport'
    ];

    protected $casts = [
        'rata_rata_kelas' => 'decimal:2',
        'tanggal_raport' => 'date',
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function subjectGrades()
    {
        return $this->hasMany(SubjectGrade::class);
    }

    public function extracurricularGrades()
    {
        return $this->hasMany(ExtracurricularGrade::class);
    }

    public function characterAssessment()
    {
        return $this->hasOne(CharacterAssessment::class);
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    public function getDisplayNameAttribute()
    {
        return "Raport {$this->kelas} - {$this->semester} {$this->tahun_ajaran}";
    }

    public function getTotalHadirAttribute()
    {
        // Asumsi 1 semester = 6 bulan, 1 bulan = 20 hari sekolah
        $totalHariSekolah = 120;
        return $totalHariSekolah - ($this->sakit + $this->izin + $this->tanpa_keterangan);
    }

    public function getPersentaseKehadiranAttribute()
    {
        $totalHariSekolah = 120;
        $hadir = $this->getTotalHadirAttribute();
        return round(($hadir / $totalHariSekolah) * 100, 2);
    }
}
