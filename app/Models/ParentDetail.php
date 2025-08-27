<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id', 'nama_ayah', 'nik_ayah', 'tempat_lahir_ayah', 'tanggal_lahir_ayah',
        'agama_ayah', 'kewarganegaraan_ayah', 'pendidikan_ayah', 'pekerjaan_ayah',
        'jabatan_ayah', 'instansi_ayah', 'alamat_kantor_ayah', 'penghasilan_ayah',
        'no_hp_ayah', 'email_ayah', 'nama_ibu', 'nik_ibu', 'tempat_lahir_ibu',
        'tanggal_lahir_ibu', 'agama_ibu', 'kewarganegaraan_ibu', 'pendidikan_ibu',
        'pekerjaan_ibu', 'jabatan_ibu', 'instansi_ibu', 'alamat_kantor_ibu',
        'penghasilan_ibu', 'no_hp_ibu', 'email_ibu', 'nama_wali', 'nik_wali',
        'tempat_lahir_wali', 'tanggal_lahir_wali', 'agama_wali', 'pendidikan_wali',
        'pekerjaan_wali', 'penghasilan_wali', 'hubungan_dengan_siswa', 'no_hp_wali',
        'email_wali', 'alamat_wali', 'status_keluarga', 'status_pernikahan_ortu'
    ];

    protected $casts = [
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
        'tanggal_lahir_wali' => 'date',
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
