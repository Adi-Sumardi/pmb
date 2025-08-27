<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id', 'foto_siswa_path', 'akta_kelahiran_path', 'kartu_keluarga_path',
        'ijazah_path', 'skhun_path', 'raport_terakhir_path', 'surat_pindah_path',
        'surat_kelakuan_baik_path', 'ktp_ayah_path', 'ktp_ibu_path', 'ktp_wali_path',
        'slip_gaji_ayah_path', 'slip_gaji_ibu_path', 'surat_keterangan_kerja_path',
        'surat_sehat_path', 'surat_vaksin_path', 'sertifikat_prestasi_paths',
        'surat_tidak_mampu_path', 'surat_yatim_piatu_path', 'dokumen_lainnya_paths'
    ];

    protected $casts = [
        'sertifikat_prestasi_paths' => 'array',
        'dokumen_lainnya_paths' => 'array',
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
