<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    protected $table = 'pendaftars';

    protected $fillable = [
        'no_pendaftaran',
        'nama_murid',
        'nisn',
        'tanggal_lahir',
        'alamat',
        'jenjang',
        'unit',
        'asal_sekolah',
        'nama_sekolah',
        'kelas',
        'nama_ayah',
        'telp_ayah',
        'nama_ibu',
        'telp_ibu',
        'foto_murid_path',
        'foto_murid_mime',
        'foto_murid_size',
        'akta_kelahiran_path',
        'akta_kelahiran_mime',
        'akta_kelahiran_size',
        'kartu_keluarga_path',
        'kartu_keluarga_mime',
        'kartu_keluarga_size',
        'status',
        'sudah_bayar_formulir',
        'bukti_pendaftaran',
        'bukti_pendaftaran_path',
        'bukti_pendaftaran_mime',
        'bukti_pendaftaran_size',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'sudah_bayar_formulir' => 'boolean',
    ];
}
