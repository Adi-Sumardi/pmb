<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id',
        'jenjang_sebelumnya',
        'nama_sekolah_sebelumnya',
        'npsn_sekolah_sebelumnya',
        'alamat_sekolah_sebelumnya',
        'kelas_terakhir',
        'tahun_lulus',
        'no_ijazah',
        'no_skhun',
        'rata_rata_nilai',
        'nilai_bahasa_indonesia',
        'nilai_matematika',
        'nilai_ipa',
        'nilai_ips',
        'nilai_bahasa_inggris',
        'ranking_kelas',
        'jumlah_siswa_sekelas',
        'prestasi_akademik',
        'prestasi_non_akademik',
        'organisasi_yang_diikuti',
        'jabatan_organisasi'
    ];

    protected $casts = [
        'rata_rata_nilai' => 'decimal:2',
        'nilai_bahasa_indonesia' => 'decimal:2',
        'nilai_matematika' => 'decimal:2',
        'nilai_ipa' => 'decimal:2',
        'nilai_ips' => 'decimal:2',
        'nilai_bahasa_inggris' => 'decimal:2',
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
