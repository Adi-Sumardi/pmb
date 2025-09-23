<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftar;
use App\Models\Payment;
use App\Models\StudentDetail;
use App\Models\ParentDetail;
use App\Models\AcademicHistory;
use App\Models\HealthRecord;
use App\Models\Document;
use App\Models\GradeReport;
use App\Models\SubjectGrade;
use App\Models\ExtracurricularGrade;
use App\Models\CharacterAssessment;
use App\Models\Achievement;
use App\Models\StudentBill;
use App\Models\BillPayment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get pendaftar data
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        // Get payment status - check formulir payment via new billing system
        $payment = null;
        $isPaid = false;
        $formulirBill = null;

        if ($pendaftar) {
            // Calculate payment amount based on UNIT (not hardcoded)
            $paymentAmount = $this->getFormulirAmountByUnit($pendaftar->unit);

            // Check or create formulir payment through new billing system
            $formulirBill = StudentBill::where('pendaftar_id', $pendaftar->id)
                ->where('bill_type', 'registration_fee')
                ->first();

            // If no registration fee bill exists, create one with correct amount
            if (!$formulirBill) {
                $formulirBill = StudentBill::create([
                    'pendaftar_id' => $pendaftar->id,
                    'bill_type' => 'registration_fee',
                    'description' => 'Biaya Formulir Pendaftaran',
                    'total_amount' => $paymentAmount,
                    'paid_amount' => 0,
                    'remaining_amount' => $paymentAmount,
                    'due_date' => now()->addDays(7), // 7 days to pay
                    'academic_year' => now()->year . '/' . (now()->year + 1),
                    'semester' => null, // No semester needed for school registration
                    'payment_status' => 'pending',
                    'notes' => 'Biaya formulir pendaftaran peserta didik baru',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                // If bill exists but has old amount (150000), update it with correct unit-based amount
                if ($formulirBill->total_amount == 150000 && $paymentAmount != 150000) {
                    $formulirBill->update([
                        'total_amount' => $paymentAmount,
                        'remaining_amount' => $paymentAmount - $formulirBill->paid_amount
                    ]);
                }
            }

            // Use the amount from the bill (now guaranteed to be correct)
            $paymentAmount = $formulirBill->total_amount;

            if ($formulirBill) {
                $isPaid = $formulirBill->payment_status === 'paid';
                if ($isPaid) {
                    $latestPayment = BillPayment::where('student_bill_id', $formulirBill->id)
                        ->where('status', 'completed')
                        ->latest()
                        ->first();
                    $payment = $latestPayment;
                }
            } else {
                // Fallback to old payment system for existing data
                $payment = Payment::where('pendaftar_id', $pendaftar->id)->where('status', 'PAID')->first();
                $isPaid = $payment ? true : false;
            }
        }

        $paymentDate = $payment ? Carbon::parse($payment->updated_at)->format('d M Y, H:i') : null;

        // Initialize completion tracking
        $completedSections = 0;
        $totalSections = 6; // Total sections to track

        // Check each section completion
        $studentDetailComplete = false;
        $parentDetailComplete = false;
        $academicHistoryComplete = false;
        $healthRecordComplete = false;
        $documentsComplete = false;
        $gradeReportsComplete = false;
        $subjectGradesComplete = false;
        $extracurricularGradesComplete = false;
        $characterAssessmentComplete = false;
        $achievementsComplete = false;

        if ($pendaftar) {
            // 1. Student Details
            $studentDetail = StudentDetail::where('pendaftar_id', $pendaftar->id)->first();
            if ($studentDetail && $this->isStudentDetailComplete($studentDetail)) {
                $studentDetailComplete = true;
                $completedSections++;
            }

            // 2. Parent Details
            $parentDetail = ParentDetail::where('pendaftar_id', $pendaftar->id)->first();
            if ($parentDetail && $this->isParentDetailComplete($parentDetail)) {
                $parentDetailComplete = true;
                $completedSections++;
            }

            // 3. Academic History
            $academicHistory = AcademicHistory::where('pendaftar_id', $pendaftar->id)->first();
            if ($academicHistory && $this->isAcademicHistoryComplete($academicHistory)) {
                $academicHistoryComplete = true;
                $completedSections++;
            }

            // 4. Health Records (Optional)
            $healthRecord = HealthRecord::where('pendaftar_id', $pendaftar->id)->first();
            if ($healthRecord && $this->isHealthRecordComplete($healthRecord)) {
                $healthRecordComplete = true;
                $completedSections++;
            }

            // 5. Documents
            $documentsCount = Document::where('pendaftar_id', $pendaftar->id)->count();
            if ($documentsCount >= 3) { // Minimum 3 required documents
                $documentsComplete = true;
                $completedSections++;
            }

            // 6. Achievements (Optional - but counts toward completion)
            $achievementsCount = Achievement::where('pendaftar_id', $pendaftar->id)->count();
            if ($achievementsCount >= 1) {
                $achievementsComplete = true;
                $completedSections++;
            }
        }

        // Calculate overall completion percentage
        $dataCompletion = $totalSections > 0 ? round(($completedSections / $totalSections) * 100) : 0;

        // Determine registration status using new flow system
        $registrationStatus = 'draft';
        $currentStage = 'registration';
        $nextSteps = [];

        if ($pendaftar) {
            // Determine current stage based on new flow fields
            if (!$isPaid) {
                $registrationStatus = 'pending_payment';
                $currentStage = 'formulir_payment';
                $nextSteps = ['Bayar formulir pendaftaran'];
            } elseif ($pendaftar->data_completion_status === 'incomplete') {
                $registrationStatus = 'data_entry';
                $currentStage = 'data_entry';
                $nextSteps = ['Lengkapi semua data pendaftaran', 'Upload dokumen yang diperlukan'];
            } elseif ($pendaftar->data_completion_status === 'complete' && $pendaftar->admin_verification_status === 'pending') {
                $registrationStatus = 'pending_verification';
                $currentStage = 'admin_verification';
                $nextSteps = ['Menunggu verifikasi admin'];
            } elseif ($pendaftar->admin_verification_status === 'approved' && $pendaftar->test_status === 'not_scheduled') {
                $registrationStatus = 'verification_approved';
                $currentStage = 'test_scheduling';
                $nextSteps = ['Menunggu jadwal tes'];
            } elseif ($pendaftar->test_status === 'scheduled') {
                $registrationStatus = 'test_scheduled';
                $currentStage = 'test_phase';
                $nextSteps = ['Ikuti tes pada jadwal yang ditentukan'];
            } elseif ($pendaftar->test_status === 'completed' && $pendaftar->acceptance_status === 'pending') {
                $registrationStatus = 'test_completed';
                $currentStage = 'evaluation';
                $nextSteps = ['Menunggu hasil evaluasi'];
            } elseif ($pendaftar->acceptance_status === 'accepted' && $pendaftar->uang_pangkal_status !== 'paid') {
                $registrationStatus = 'accepted';
                $currentStage = 'uang_pangkal_payment';
                $nextSteps = ['Bayar uang pangkal untuk konfirmasi penerimaan'];
            } elseif ($pendaftar->uang_pangkal_status === 'paid') {
                $registrationStatus = 'enrolled';
                $currentStage = 'regular_billing';
                $nextSteps = ['Pantau tagihan SPP dan biaya lainnya'];
            } elseif ($pendaftar->acceptance_status === 'rejected') {
                $registrationStatus = 'rejected';
                $currentStage = 'completed';
                $nextSteps = [];
            } else {
                // Fallback to old system
                $registrationStatus = $pendaftar->overall_status ?? 'draft';
            }
        }

        // Get billing information
        $activeBills = [];
        $paidBills = [];
        $totalUnpaidAmount = 0;
        $studentStatus = 'inactive';
        $isActiveStudent = false;

        if ($pendaftar) {
            $studentStatus = $pendaftar->student_status ?? 'inactive';
            $isActiveStudent = $studentStatus === 'active';

            // Get all bills for this student
            $allBills = StudentBill::where('pendaftar_id', $pendaftar->id)->get();

            foreach ($allBills as $bill) {
                // Filter bills based on student status
                $shouldShowBill = true;

                // Only show SPP, uniform, book bills etc. for active students
                if (in_array($bill->bill_type, ['spp', 'uniform', 'book', 'activity', 'other_fee'])) {
                    $shouldShowBill = $isActiveStudent;
                }

                // Always show registration_fee and uang_pangkal regardless of student status
                if (in_array($bill->bill_type, ['registration_fee', 'uang_pangkal'])) {
                    $shouldShowBill = true;
                }

                if ($shouldShowBill) {
                    // Fix: gunakan payment_status instead of non-existent is_paid accessor
                    if ($bill->payment_status === 'paid') {
                        $paidBills[] = $bill;
                    } else {
                        $activeBills[] = $bill;
                        $totalUnpaidAmount += $bill->remaining_amount;
                    }
                }
            }
        }

        return view('user.dashboard', compact(
            'user',
            'pendaftar',
            'isPaid',
            'paymentDate',
            'paymentAmount',
            'dataCompletion',
            'completedSections',
            'totalSections',
            'registrationStatus',
            'currentStage',
            'nextSteps',
            'activeBills',
            'paidBills',
            'totalUnpaidAmount',
            'formulirBill',
            'studentDetailComplete',
            'parentDetailComplete',
            'academicHistoryComplete',
            'healthRecordComplete',
            'documentsComplete',
            'gradeReportsComplete',
            'subjectGradesComplete',
            'extracurricularGradesComplete',
            'characterAssessmentComplete',
            'achievementsComplete',
            'studentStatus',
            'isActiveStudent'
        ));
    }

    /**
     * Get dashboard data for AJAX refresh
     */
    public function getDashboardData()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        // Check payment via new billing system
        $payment = null;
        $isPaid = false;
        $formulirBill = null;
        if ($pendaftar) {
            // Check formulir payment through new billing system
            $formulirBill = StudentBill::where('pendaftar_id', $pendaftar->id)
                ->where('bill_type', 'registration_fee')
                ->first();

            if ($formulirBill) {
                $isPaid = $formulirBill->payment_status === 'paid';
                if ($isPaid) {
                    $latestPayment = BillPayment::where('student_bill_id', $formulirBill->id)
                        ->where('status', 'paid')
                        ->latest()
                        ->first();
                    $payment = $latestPayment;
                }
            } else {
                // Fallback to old payment system
                $payment = Payment::where('pendaftar_id', $pendaftar->id)->where('status', 'paid')->first();
                $isPaid = $payment ? true : false;
            }
        }

        // Recalculate completion
        $completedSections = 0;
        $totalSections = 9;

        if ($pendaftar) {
            // Check all sections again
            $studentDetail = StudentDetail::where('pendaftar_id', $pendaftar->id)->first();
            if ($studentDetail && $this->isStudentDetailComplete($studentDetail)) {
                $completedSections++;
            }

            $parentDetail = ParentDetail::where('pendaftar_id', $pendaftar->id)->first();
            if ($parentDetail && $this->isParentDetailComplete($parentDetail)) {
                $completedSections++;
            }

            $academicHistory = AcademicHistory::where('pendaftar_id', $pendaftar->id)->first();
            if ($academicHistory && $this->isAcademicHistoryComplete($academicHistory)) {
                $completedSections++;
            }

            $healthRecord = HealthRecord::where('pendaftar_id', $pendaftar->id)->first();
            if ($healthRecord && $this->isHealthRecordComplete($healthRecord)) {
                $completedSections++;
            }

            $documentsCount = Document::where('pendaftar_id', $pendaftar->id)->count();
            if ($documentsCount >= 3) {
                $completedSections++;
            }

            $achievementsCount = Achievement::where('pendaftar_id', $pendaftar->id)->count();
            if ($achievementsCount >= 1) {
                $completedSections++;
            }
        }

        $dataCompletion = $totalSections > 0 ? round(($completedSections / $totalSections) * 100) : 0;

        // Determine current stage using new flow system
        $registrationStatus = 'draft';
        $currentStage = 'registration';
        $nextSteps = [];

        if ($pendaftar) {
            // Determine current stage based on new flow fields
            if (!$isPaid) {
                $registrationStatus = 'pending_payment';
                $currentStage = 'formulir_payment';
                $nextSteps = ['Bayar formulir pendaftaran'];
            } elseif ($pendaftar->data_completion_status === 'incomplete') {
                $registrationStatus = 'data_entry';
                $currentStage = 'data_entry';
                $nextSteps = ['Lengkapi data pendaftaran'];
            } elseif ($pendaftar->data_completion_status === 'complete' && $pendaftar->admin_verification_status === 'pending') {
                $registrationStatus = 'pending_verification';
                $currentStage = 'admin_verification';
                $nextSteps = ['Menunggu verifikasi admin'];
            } elseif ($pendaftar->admin_verification_status === 'approved' && $pendaftar->test_status === 'not_scheduled') {
                $registrationStatus = 'verification_approved';
                $currentStage = 'test_scheduling';
                $nextSteps = ['Menunggu jadwal tes'];
            } elseif ($pendaftar->test_status === 'scheduled') {
                $registrationStatus = 'test_scheduled';
                $currentStage = 'test_phase';
                $nextSteps = ['Ikuti tes sesuai jadwal'];
            } elseif ($pendaftar->test_status === 'completed' && $pendaftar->acceptance_status === 'pending') {
                $registrationStatus = 'test_completed';
                $currentStage = 'evaluation';
                $nextSteps = ['Menunggu hasil evaluasi'];
            } elseif ($pendaftar->acceptance_status === 'accepted' && $pendaftar->uang_pangkal_status !== 'paid') {
                $registrationStatus = 'accepted';
                $currentStage = 'uang_pangkal_payment';
                $nextSteps = ['Bayar uang pangkal'];
            } elseif ($pendaftar->uang_pangkal_status === 'paid') {
                $registrationStatus = 'enrolled';
                $currentStage = 'regular_billing';
                $nextSteps = ['Pantau tagihan rutin'];
            } elseif ($pendaftar->acceptance_status === 'rejected') {
                $registrationStatus = 'rejected';
                $currentStage = 'completed';
                $nextSteps = [];
            } else {
                // Fallback to old system
                $registrationStatus = $pendaftar->overall_status ?? 'draft';
            }
        }

        // Get billing information
        $activeBills = [];
        $totalUnpaidAmount = 0;
        $studentStatus = 'inactive';
        $isActiveStudent = false;

        if ($pendaftar) {
            $studentStatus = $pendaftar->student_status ?? 'inactive';
            $isActiveStudent = $studentStatus === 'active';

            $allBills = StudentBill::where('pendaftar_id', $pendaftar->id)
                ->where('payment_status', '!=', 'paid')
                ->get();

            foreach ($allBills as $bill) {
                // Filter bills based on student status
                $shouldShowBill = true;

                // Only show SPP, uniform, book bills etc. for active students
                if (in_array($bill->bill_type, ['spp', 'uniform', 'book', 'activity', 'other_fee'])) {
                    $shouldShowBill = $isActiveStudent;
                }

                // Always show registration_fee and uang_pangkal regardless of student status
                if (in_array($bill->bill_type, ['registration_fee', 'uang_pangkal'])) {
                    $shouldShowBill = true;
                }

                if ($shouldShowBill) {
                    $activeBills[] = [
                        'id' => $bill->id,
                        'type' => $bill->bill_type,
                        'amount' => $bill->amount,
                        'remaining' => $bill->remaining_amount,
                        'due_date' => $bill->due_date ? Carbon::parse($bill->due_date)->format('Y-m-d') : null,
                        'description' => $bill->description
                    ];
                    $totalUnpaidAmount += $bill->remaining_amount;
                }
            }
        }

        return response()->json([
            'isPaid' => $isPaid,
            'dataCompletion' => $dataCompletion,
            'completedSections' => $completedSections,
            'totalSections' => $totalSections,
            'registrationStatus' => $registrationStatus,
            'currentStage' => $currentStage,
            'nextSteps' => $nextSteps,
            'activeBills' => $activeBills,
            'totalUnpaidAmount' => $totalUnpaidAmount,
            'studentStatus' => $studentStatus,
            'isActiveStudent' => $isActiveStudent
        ]);
    }

    /**
     * Check if student detail is complete
     */
    private function isStudentDetailComplete($studentDetail)
    {
        $requiredFields = [
            'nama_lengkap', 'nik', 'no_kk', 'tempat_lahir',
            'tanggal_lahir', 'jenis_kelamin', 'agama', 'alamat_lengkap',
            'kelurahan', 'kecamatan', 'kota_kabupaten', 'provinsi'
        ];

        foreach ($requiredFields as $field) {
            if (empty($studentDetail->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if parent detail is complete
     */
    private function isParentDetailComplete($parentDetail)
    {
        $requiredFields = [
            'nama_ayah', 'pekerjaan_ayah', 'no_hp_ayah',
            'nama_ibu', 'pekerjaan_ibu', 'no_hp_ibu'
        ];

        foreach ($requiredFields as $field) {
            if (empty($parentDetail->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if academic history is complete
     */
    private function isAcademicHistoryComplete($academicHistory)
    {
        $requiredFields = [
            'nama_sekolah_sebelumnya',
            'alamat_sekolah_sebelumnya',
            'jenjang_sebelumnya',
            'tahun_lulus'
        ];

        foreach ($requiredFields as $field) {
            if (empty($academicHistory->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if health record is complete
     */
    private function isHealthRecordComplete($healthRecord)
    {
        // Data kesehatan dianggap lengkap jika objek healthRecord ada
        // karena kebanyakan data kesehatan bersifat opsional
        return $healthRecord !== null;
    }

    /**
     * Check if character assessment is complete
     */
    private function isCharacterAssessmentComplete($characterAssessment)
    {
        $requiredFields = [
            'pendaftar_id',
            'sikap_spiritual',
            'deskripsi_spiritual',
            'sikap_sosial',
            'deskripsi_sosial',
            'jujur',
            'disiplin',
            'tanggung_jawab',
            'santun',
            'peduli',
            'percaya_diri'
        ];

        foreach ($requiredFields as $field) {
            if (empty($characterAssessment->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine registration status based on completion
     */
    private function determineRegistrationStatus($pendaftar, $completedSections, $totalSections)
    {
        if (!$pendaftar) {
            return 'draft';
        }

        $completionPercentage = ($completedSections / $totalSections) * 100;

        // Check if pendaftar has submitted for review
        if (isset($pendaftar->status)) {
            if ($pendaftar->status === 'submitted' || $pendaftar->status === 'verified') {
                return $pendaftar->status === 'verified' ? 'verified' : 'pending';
            }
        }

        // Auto-submit if completion is 100%
        if ($completionPercentage >= 100) {
            $pendaftar->update(['status' => 'submitted']);
            return 'pending';
        }

        return 'draft';
    }

    /**
     * Get formulir amount based on school unit (same as PendaftarController)
     */
    private function getFormulirAmountByUnit($unit)
    {
        return match($unit) {
            'RA Sakinah' => 100000,
            'PG Sakinah' => 400000,
            'TKIA 13' => 450000,
            'SDIA 13', 'SD Islam Al Azhar 13 - Rawamangun' => 550000,
            'SMPIA 12' => 550000,
            'SMPIA 55' => 550000,
            'SMAIA 33', 'SMA Islam Al Azhar 33 - Jatimakmur' => 550000,
            default => 0
        };
    }
}
