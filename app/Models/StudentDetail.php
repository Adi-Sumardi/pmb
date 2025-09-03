<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id',
        'nama_lengkap',
        'nama_panggilan',
        'nisn',
        'nik',
        'no_kk',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'kewarganegaraan',
        'bahasa_sehari_hari',
        'tinggi_badan',
        'berat_badan',
        'golongan_darah',
        'alamat_lengkap',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kota_kabupaten',
        'provinsi',
        'kode_pos',
        'jarak_ke_sekolah',
        'transportasi',
        'tinggal_dengan',
        'anak_ke',
        'jumlah_saudara_kandung',
        'hobi',
        'cita_cita',
        'kepribadian',
        'kesulitan_belajar'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tinggi_badan' => 'decimal:2',
        'berat_badan' => 'decimal:2',
        'jarak_ke_sekolah' => 'decimal:2',
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
