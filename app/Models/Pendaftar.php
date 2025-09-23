<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $no_pendaftaran
 * @property string $nama_murid
 * @property string $nisn
 * @property string $unit
 * @property string $jenjang
 * @property string $overall_status
 * @property string $student_status
 * @property \Carbon\Carbon $student_activated_at
 * @property string $student_status_notes
 * @property string $academic_year
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Payment> $payments
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\StudentBill> $studentBills
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\BillPayment> $billPayments
 */
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
        'academic_year',
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
        'current_status',
        'overall_status',
        // New registration flow fields
        'data_completion_status',
        'data_completed_at',
        'data_verified_at',
        'data_verification_notes',
        'test_status',
        'test_scheduled_at',
        'test_score',
        'test_notes',
        'acceptance_status',
        'acceptance_decided_at',
        'acceptance_notes',
        'uang_pangkal_status',
        'uang_pangkal_total',
        'uang_pangkal_paid',
        'uang_pangkal_remaining',
        'uang_pangkal_generated_at',
        'uang_pangkal_due_date',
        'spp_status',
        'spp_activated_at',
        'registration_stage',
        'last_stage_updated_at',
        'stage_history',
        'verified_by',
        'data_verified_by',
        'test_managed_by',
        'acceptance_decided_by',
        // Student status fields
        'student_status',
        'student_activated_at',
        'student_status_notes',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'sudah_bayar_formulir' => 'boolean',
        // New field casts
        'data_completed_at' => 'datetime',
        'data_verified_at' => 'datetime',
        'test_scheduled_at' => 'datetime',
        'test_score' => 'decimal:2',
        'acceptance_decided_at' => 'datetime',
        'uang_pangkal_total' => 'decimal:2',
        'uang_pangkal_paid' => 'decimal:2',
        'uang_pangkal_remaining' => 'decimal:2',
        'uang_pangkal_generated_at' => 'datetime',
        'uang_pangkal_due_date' => 'datetime',
        'spp_activated_at' => 'datetime',
        'last_stage_updated_at' => 'datetime',
        'stage_history' => 'array',
        'student_activated_at' => 'datetime',
    ];

    // Scopes
    public function scopeActiveStudents($query)
    {
        return $query->where('student_status', 'active');
    }

    public function scopeEnrolledStudents($query)
    {
        return $query->whereIn('student_status', ['active', 'graduated']);
    }

    /**
     * Get the user that owns the pendaftar.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, Pendaftar>
     */
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

    // New billing relationships
    public function studentBills()
    {
        return $this->hasMany(StudentBill::class);
    }

    public function billPayments()
    {
        return $this->hasMany(BillPayment::class);
    }

    public function formulirBills()
    {
        return $this->hasMany(StudentBill::class)->where('bill_type', 'registration_fee');
    }

    public function uangPangkalBills()
    {
        return $this->hasMany(StudentBill::class)->where('bill_type', 'uang_pangkal');
    }

    public function sppBills()
    {
        return $this->hasMany(StudentBill::class)->where('bill_type', 'spp');
    }

    // Admin relationship for tracking
    public function verifiedByAdmin()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function dataVerifiedByAdmin()
    {
        return $this->belongsTo(User::class, 'data_verified_by');
    }

    public function testManagedByAdmin()
    {
        return $this->belongsTo(User::class, 'test_managed_by');
    }

    public function acceptanceDecidedByAdmin()
    {
        return $this->belongsTo(User::class, 'acceptance_decided_by');
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

    // New registration flow methods
    public function updateRegistrationStage(string $newStage, ?User $admin = null): void
    {
        $oldStage = $this->registration_stage;
        $this->registration_stage = $newStage;
        $this->last_stage_updated_at = now();

        // Update stage history
        $history = $this->stage_history ?? [];
        $history[] = [
            'from' => $oldStage,
            'to' => $newStage,
            'updated_at' => now()->toISOString(),
            'updated_by' => $admin?->id
        ];
        $this->stage_history = $history;

        $this->save();
    }

    public function canProgressToStage(string $stage): bool
    {
        return match($stage) {
            'admin_verification' => $this->registration_stage === 'form_submitted',
            'payment_form' => $this->registration_stage === 'admin_verification' && $this->status === 'diverifikasi',
            'data_completion' => $this->registration_stage === 'payment_form' && $this->sudah_bayar_formulir,
            'data_validation' => $this->registration_stage === 'data_completion' && $this->data_completion_status === 'complete',
            'test_phase' => $this->registration_stage === 'data_validation' && $this->data_completion_status === 'verified',
            'acceptance_review' => $this->registration_stage === 'test_phase' && $this->test_status === 'completed',
            'accepted' => $this->registration_stage === 'acceptance_review' && $this->acceptance_status === 'accepted',
            'uang_pangkal_payment' => $this->registration_stage === 'accepted',
            'enrolled' => $this->registration_stage === 'uang_pangkal_payment' && $this->uang_pangkal_status === 'paid',
            default => false
        };
    }

    public function getCurrentStageLabel(): string
    {
        return match($this->registration_stage) {
            'form_submitted' => 'Form Disubmit',
            'admin_verification' => 'Verifikasi Admin',
            'payment_form' => 'Pembayaran Formulir',
            'data_completion' => 'Melengkapi Data',
            'data_validation' => 'Validasi Data',
            'test_phase' => 'Tahap Test',
            'acceptance_review' => 'Review Penerimaan',
            'accepted' => 'Diterima',
            'uang_pangkal_payment' => 'Pembayaran Uang Pangkal',
            'enrolled' => 'Terdaftar',
            'rejected' => 'Ditolak',
            default => 'Unknown'
        };
    }

    public function generateFormulirBill($issuedById = null): ?StudentBill
    {
        // Check if formulir bill already exists
        $existingBill = $this->studentBills()
            ->where('bill_type', 'registration_fee')
            ->first();

        if ($existingBill) {
            return $existingBill;
        }

        // Get formulir amount based on jenjang/unit
        $formulirAmount = $this->getFormulirAmountByUnit();

        // Validate amount - return null if invalid
        if ($formulirAmount <= 0) {
            return null;
        }

        $bill = $this->studentBills()->create([
            'bill_type' => 'registration_fee',
            'description' => "Biaya Formulir Pendaftaran {$this->unit} - {$this->nama_murid}",
            'total_amount' => $formulirAmount,
            'remaining_amount' => $formulirAmount,
            'due_date' => now()->addDays(7), // 7 days to pay formulir
            'allow_installments' => false, // Formulir tidak bisa dicicil
            'total_installments' => 1,
            'installment_amount' => $formulirAmount,
            'academic_year' => date('Y') . '/' . (date('Y') + 1),
            'issued_at' => now(),
            'issued_by' => $issuedById,
            'payment_status' => 'pending'
        ]);

        return $bill;
    }

    /**
     * Get formulir amount based on unit/jenjang
     */
    public function getFormulirAmountByUnit(): int
    {
        // Check by specific unit name first
        if (str_contains($this->unit, 'TK Islam Al Azhar 13') || $this->unit === 'TKIA 13') {
            return 450000;
        }

        // Check by unit and jenjang combination
        return match(true) {
            // RA (Raudhatul Athfal) - 100.000
            str_contains(strtolower($this->unit ?? ''), 'ra sakinah') ||
            $this->unit === 'RA' ||
            str_contains(strtolower($this->unit ?? ''), 'raudhatul athfal') => 100000,

            // PG Sakinah - 400.000
            str_contains(strtolower($this->unit ?? ''), 'pg sakinah') => 400000,

            // TKIA 13 (TK Islam Al Azhar 13) - 450.000
            str_contains(strtolower($this->unit ?? ''), 'tk islam al azhar 13') ||
            $this->unit === 'TKIA 13' ||
            ($this->unit === 'TK' && str_contains(strtolower($this->nama_sekolah ?? ''), 'azhar')) => 450000,

            // SDIA 13 (SD Islam Al Azhar 13) - 550.000
            str_contains(strtolower($this->unit ?? ''), 'sd islam al azhar 13') ||
            $this->unit === 'SDIA 13' ||
            ($this->unit === 'SD' && str_contains(strtolower($this->nama_sekolah ?? ''), 'azhar')) => 550000,

            // SMPIA 12 (SMP Islam Al Azhar 12) - 550.000
            str_contains(strtolower($this->unit ?? ''), 'smp islam al azhar 12') ||
            $this->unit === 'SMPIA 12' ||
            ($this->unit === 'SMP' && str_contains(strtolower($this->nama_sekolah ?? ''), 'azhar 12')) => 550000,

            // SMPIA 55 (SMP Islam Al Azhar 55) - 550.000
            str_contains(strtolower($this->unit ?? ''), 'smp islam al azhar 55') ||
            $this->unit === 'SMPIA 55' ||
            ($this->unit === 'SMP' && str_contains(strtolower($this->nama_sekolah ?? ''), 'azhar 55')) => 550000,

            // SMAIA 33 (SMA Islam Al Azhar 33) - 550.000
            str_contains(strtolower($this->unit ?? ''), 'sma islam al azhar 33') ||
            $this->unit === 'SMAIA 33' ||
            ($this->unit === 'SMA' && str_contains(strtolower($this->nama_sekolah ?? ''), 'azhar 33')) => 550000,

            // Fallback berdasarkan unit umum - HARGA SESUAI JENJANG
            $this->unit === 'RA' => 100000, // RA
            $this->unit === 'PG' => 400000, // PG
            $this->unit === 'TK' => 450000, // TK Islam Al Azhar 13
            $this->unit === 'SD' => 550000, // SD Islam Al Azhar 13
            $this->unit === 'SMP' => 550000, // SMP Islam Al Azhar
            $this->unit === 'SMA' => 550000, // SMA Islam Al Azhar

            // Fallback berdasarkan jenjang jika unit tidak terdeteksi
            strtolower($this->jenjang ?? '') === 'ra' => 100000,
            strtolower($this->jenjang ?? '') === 'pg' => 400000,
            strtolower($this->jenjang ?? '') === 'tk' => 450000,
            strtolower($this->jenjang ?? '') === 'sd' => 550000,
            strtolower($this->jenjang ?? '') === 'smp' => 550000,
            strtolower($this->jenjang ?? '') === 'sma' => 550000,

            // Edge case: deteksi dari pola nama untuk jenjang yang tidak standar
            str_contains(strtolower($this->unit ?? ''), 'sanggar') ||
            str_contains(strtolower($this->unit ?? ''), 'kelompok') => 100000, // Kemungkinan RA level

            str_contains(strtolower($this->unit ?? ''), 'tka') ||
            str_contains(strtolower($this->unit ?? ''), 'tkb') => 450000, // TK level

            default => 550000 // Default untuk edge case (SD/SMP/SMA rate - yang paling umum)
        };
    }

    public function generateUangPangkalBill($issuedById = null): ?StudentBill
    {
        if ($this->registration_stage !== 'accepted') {
            return null;
        }

        // Get uang pangkal setting based on student's unit/level
        $setting = UangPangkalSetting::active()
            ->bySchoolLevel($this->jenjang)
            ->first();

        if (!$setting) {
            return null;
        }

        // Check if bill already exists
        $existingBill = $this->uangPangkalBills()->first();
        if ($existingBill) {
            return $existingBill;
        }

        // Create new bill
        $bill = $this->studentBills()->create([
            'uang_pangkal_setting_id' => $setting->id,
            'bill_type' => 'uang_pangkal',
            'description' => "Uang Pangkal {$this->jenjang} - {$this->nama_murid}",
            'total_amount' => $setting->amount,
            'remaining_amount' => $setting->amount,
            'due_date' => now()->addDays(30), // 30 days to pay
            'allow_installments' => $setting->allow_installments,
            'total_installments' => $setting->max_installments,
            'installment_amount' => $setting->allow_installments ?
                ($setting->amount / $setting->max_installments) : null,
            'academic_year' => $setting->academic_year,
            'issued_at' => now(),
            'issued_by' => $issuedById
        ]);        // Update pendaftar status
        $this->uang_pangkal_status = 'pending';
        $this->uang_pangkal_total = $setting->amount;
        $this->uang_pangkal_remaining = $setting->amount;
        $this->uang_pangkal_generated_at = now();
        $this->uang_pangkal_due_date = $bill->due_date;
        $this->updateRegistrationStage('uang_pangkal_payment');

        return $bill;
    }

    public function getUangPangkalPaymentProgress(): float
    {
        if (!$this->uang_pangkal_total || $this->uang_pangkal_total <= 0) {
            return 0;
        }

        return ($this->uang_pangkal_paid / $this->uang_pangkal_total) * 100;
    }

    public function hasOutstandingBills(): bool
    {
        return $this->studentBills()
            ->whereIn('payment_status', ['pending', 'partial', 'overdue'])
            ->exists();
    }

    public function getTotalOutstandingAmount(): float
    {
        return $this->studentBills()
            ->whereIn('payment_status', ['pending', 'partial', 'overdue'])
            ->sum('remaining_amount');
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
