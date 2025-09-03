<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id',
        'nilai_tugas',
        'nilai_harian',
        'nilai_uts',
        'nilai_uas',
        'nilai_praktik',
        'nilai_pengetahuan',
        'nilai_keterampilan',
        'nilai_akhir',
        'predikat',
        'status_ketuntasan',
        'remedial_nilai',
        'deskripsi_pengetahuan',
        'deskripsi_keterampilan'
    ];

    protected $casts = [
        'nilai_tugas' => 'decimal:2',
        'nilai_harian' => 'decimal:2',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_praktik' => 'decimal:2',
        'nilai_pengetahuan' => 'decimal:2',
        'nilai_keterampilan' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
        'remedial_nilai' => 'decimal:2',
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function getIsTuntasAttribute()
    {
        return $this->status_ketuntasan === 'Tuntas';
    }

    public function getPredikatColorAttribute()
    {
        return match($this->predikat) {
            'A' => 'success',
            'B' => 'info',
            'C' => 'warning',
            'D' => 'danger',
            default => 'secondary'
        };
    }

    // Auto calculate nilai akhir
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $subject = $model->academicSubject;
            if ($subject) {
                // Hitung nilai akhir berdasarkan bobot
                $nilaiAkhir = 0;
                $totalBobot = 0;

                if ($model->nilai_harian) {
                    $nilaiAkhir += $model->nilai_harian * $subject->bobot_harian;
                    $totalBobot += $subject->bobot_harian;
                }
                if ($model->nilai_tugas) {
                    $nilaiAkhir += $model->nilai_tugas * $subject->bobot_tugas;
                    $totalBobot += $subject->bobot_tugas;
                }
                if ($model->nilai_uas) {
                    $nilaiAkhir += $model->nilai_uas * $subject->bobot_ujian;
                    $totalBobot += $subject->bobot_ujian;
                }

                if ($totalBobot > 0) {
                    $model->nilai_akhir = round($nilaiAkhir / $totalBobot, 2);
                }

                // Set predikat berdasarkan nilai akhir
                $model->predikat = match(true) {
                    $model->nilai_akhir >= 85 => 'A',
                    $model->nilai_akhir >= 70 => 'B',
                    $model->nilai_akhir >= 55 => 'C',
                    default => 'D'
                };

                // Set status ketuntasan
                $model->status_ketuntasan = $model->nilai_akhir >= $subject->kkm ? 'Tuntas' : 'Belum Tuntas';
            }
        });
    }
}
