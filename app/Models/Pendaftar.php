<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    protected $table = 'pendaftars';

    protected $fillable = [
        'user_id',
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
        'payment_amount',
        'bukti_pendaftaran',
        'bukti_pendaftaran_path',
        'bukti_pendaftaran_mime',
        'bukti_pendaftaran_size',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'sudah_bayar_formulir' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latest();
    }

    // Relasi ke tabel kelengkapan data
    public function studentDetail()
    {
        return $this->hasOne(StudentDetail::class);
    }

    public function parentDetail()
    {
        return $this->hasOne(ParentDetail::class);
    }

    public function academicHistory()
    {
        return $this->hasOne(AcademicHistory::class);
    }

    public function healthRecord()
    {
        return $this->hasOne(HealthRecord::class);
    }

    public function documents()
    {
        return $this->hasOne(Document::class);
    }

    // Relasi ke tabel nilai dan raport
    public function gradeReports()
    {
        return $this->hasMany(GradeReport::class)->orderBy('tahun_ajaran', 'desc')->orderBy('semester', 'desc');
    }

    public function latestGradeReport()
    {
        return $this->hasOne(GradeReport::class)->latest();
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class)->orderBy('tanggal_event', 'desc');
    }

    /**
     * Hitung persentase kelengkapan data
     */
    public function getDataCompletionPercentage()
    {
        $totalSections = 7; // tambah 2 section baru (grade_reports, achievements)
        $completedSections = 0;

        // Cek setiap section
        if ($this->studentDetail && $this->isStudentDetailComplete()) {
            $completedSections++;
        }

        if ($this->parentDetail && $this->isParentDetailComplete()) {
            $completedSections++;
        }

        if ($this->academicHistory && $this->isAcademicHistoryComplete()) {
            $completedSections++;
        }

        if ($this->healthRecord && $this->isHealthRecordComplete()) {
            $completedSections++;
        }

        if ($this->documents && $this->isDocumentsComplete()) {
            $completedSections++;
        }

        // Cek raport
        if ($this->gradeReports()->exists()) {
            $completedSections++;
        }

        // Cek prestasi (optional, tapi tetap dihitung)
        $completedSections++; // selalu dianggap complete karena prestasi optional

        return round(($completedSections / $totalSections) * 100);
    }

    private function isStudentDetailComplete()
    {
        $required = ['nama_lengkap', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama'];
        foreach ($required as $field) {
            if (empty($this->studentDetail->$field)) {
                return false;
            }
        }
        return true;
    }

    private function isParentDetailComplete()
    {
        $required = ['nama_ayah', 'nama_ibu', 'no_hp_ayah', 'no_hp_ibu'];
        foreach ($required as $field) {
            if (empty($this->parentDetail->$field)) {
                return false;
            }
        }
        return true;
    }

    private function isAcademicHistoryComplete()
    {
        $required = ['nama_sekolah_sebelumnya', 'tahun_lulus'];
        foreach ($required as $field) {
            if (empty($this->academicHistory->$field)) {
                return false;
            }
        }
        return true;
    }

    private function isHealthRecordComplete()
    {
        // Health record bisa optional, jadi selalu return true
        return true;
    }

    private function isDocumentsComplete()
    {
        $required = ['foto_siswa_path', 'akta_kelahiran_path', 'kartu_keluarga_path'];
        foreach ($required as $field) {
            if (empty($this->documents->$field)) {
                return false;
            }
        }
        return true;
    }
}
