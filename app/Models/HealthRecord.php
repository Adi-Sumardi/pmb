<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id', 'riwayat_penyakit', 'alergi', 'obat_yang_dikonsumsi',
        'cacat_jasmani', 'keterangan_cacat', 'bcg', 'polio', 'dpt', 'campak',
        'hepatitis_b', 'kebutuhan_khusus', 'keterangan_kebutuhan_khusus',
        'kondisi_mata', 'kondisi_telinga', 'kondisi_gigi'
    ];

    protected $casts = [
        'bcg' => 'boolean',
        'polio' => 'boolean',
        'dpt' => 'boolean',
        'campak' => 'boolean',
        'hepatitis_b' => 'boolean',
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
