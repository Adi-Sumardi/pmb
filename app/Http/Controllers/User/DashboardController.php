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
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get pendaftar data
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        // Get payment status - check via pendaftar_id instead of user_id
        $payment = null;
        $isPaid = false;
        if ($pendaftar) {
            $payment = Payment::where('pendaftar_id', $pendaftar->id)->where('status', 'paid')->first();
            $isPaid = $payment ? true : false;
        }

        $paymentDate = $payment ? Carbon::parse($payment->updated_at)->format('d M Y, H:i') : null;
        $paymentAmount = 150000; // Default payment amount

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

        // Determine registration status
        $registrationStatus = $pendaftar ? $pendaftar->overall_status : 'draft';

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
            'studentDetailComplete',
            'parentDetailComplete',
            'academicHistoryComplete',
            'healthRecordComplete',
            'documentsComplete',
            'gradeReportsComplete',
            'subjectGradesComplete',
            'extracurricularGradesComplete',
            'characterAssessmentComplete',
            'achievementsComplete'
        ));
    }

    /**
     * Get dashboard data for AJAX refresh
     */
    public function getDashboardData()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        // Check payment via pendaftar_id
        $payment = null;
        $isPaid = false;
        if ($pendaftar) {
            $payment = Payment::where('pendaftar_id', $pendaftar->id)->where('status', 'paid')->first();
            $isPaid = $payment ? true : false;
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
        $registrationStatus = $pendaftar ? $pendaftar->overall_status : 'draft';

        return response()->json([
            'isPaid' => $isPaid,
            'dataCompletion' => $dataCompletion,
            'completedSections' => $completedSections,
            'totalSections' => $totalSections,
            'registrationStatus' => $registrationStatus
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
}
