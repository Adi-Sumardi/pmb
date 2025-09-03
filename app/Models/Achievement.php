<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id',
        'nama_prestasi',
        'kategori',
        'tingkat',
        'juara',
        'nama_event',
        'penyelenggara',
        'tanggal_event',
        'tempat_event',
        'sertifikat_path',
        'foto_kegiatan_path'
    ];

    protected $casts = [
        'tanggal_event' => 'date',
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function getJuaraColorAttribute()
    {
        return match($this->juara) {
            '1' => 'warning', // Gold
            '2' => 'secondary', // Silver
            '3' => 'danger', // Bronze
            default => 'info'
        };
    }

    public function getTingkatBadgeAttribute()
    {
        return match($this->tingkat) {
            'Internasional' => 'danger',
            'Nasional' => 'warning',
            'Provinsi' => 'info',
            'Kabupaten/Kota' => 'success',
            default => 'secondary'
        };
    }

    public function getDisplayJuaraAttribute()
    {
        return match($this->juara) {
            '1' => 'Juara 1',
            '2' => 'Juara 2',
            '3' => 'Juara 3',
            'Harapan 1' => 'Harapan 1',
            'Harapan 2' => 'Harapan 2',
            'Harapan 3' => 'Harapan 3',
            'Peserta' => 'Peserta',
            default => $this->juara
        };
    }
}
