<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
use App\Models\AcademicSubject;

class DataController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        // Check payment status
        $payment = Payment::where('pendaftar_id', $pendaftar->id)->where('status', 'paid')->first();
        if (!$payment) {
            return redirect()->route('payment.index')->with('error', 'Silakan lakukan pembayaran terlebih dahulu.');
        }

        // Get completion status for each section
        $completionStatus = $this->getCompletionStatus($pendaftar);

        return view('user.data.index', compact('pendaftar', 'completionStatus'));
    }

    public function student()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();
        $studentDetail = StudentDetail::where('pendaftar_id', $pendaftar->id)->first();

        return view('user.data.student', compact('pendaftar', 'studentDetail'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:100',
            'kewarganegaraan' => 'required|string|max:100',
            'alamat_lengkap' => 'required|string',
            'kode_pos' => 'required|string|max:10',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'anak_ke' => 'nullable|integer',
            'jumlah_saudara' => 'nullable|integer',
            'hobi' => 'nullable|string|max:255',
            'cita_cita' => 'nullable|string|max:255'
        ]);

        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        StudentDetail::updateOrCreate(
            ['pendaftar_id' => $pendaftar->id],
            $request->all()
        );

        return redirect()->route('user.data')->with('success', 'Data siswa berhasil disimpan.');
    }

    public function parent()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();
        $parentDetail = ParentDetail::where('pendaftar_id', $pendaftar->id)->first();

        return view('user.data.parent', compact('pendaftar', 'parentDetail'));
    }

    public function storeParent(Request $request)
    {
        $request->validate([
            'nama_ayah' => 'required|string|max:255',
            'tempat_lahir_ayah' => 'nullable|string|max:255',
            'tanggal_lahir_ayah' => 'nullable|date',
            'pendidikan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ayah' => 'required|string|max:255',
            'penghasilan_ayah' => 'nullable|integer',
            'nama_ibu' => 'required|string|max:255',
            'tempat_lahir_ibu' => 'nullable|string|max:255',
            'tanggal_lahir_ibu' => 'nullable|date',
            'pendidikan_ibu' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'required|string|max:255',
            'penghasilan_ibu' => 'nullable|integer',
            'nama_wali' => 'nullable|string|max:255',
            'alamat_orangtua' => 'required|string',
            'no_telepon_orangtua' => 'required|string|max:20'
        ]);

        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        ParentDetail::updateOrCreate(
            ['pendaftar_id' => $pendaftar->id],
            $request->all()
        );

        return redirect()->route('user.data')->with('success', 'Data orang tua berhasil disimpan.');
    }

    public function academic()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();
        $academicHistory = AcademicHistory::where('pendaftar_id', $pendaftar->id)->first();

        return view('user.data.academic', compact('pendaftar', 'academicHistory'));
    }

    public function storeAcademic(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'alamat_sekolah' => 'required|string',
            'tahun_masuk' => 'required|integer',
            'tahun_lulus' => 'required|integer',
            'jenjang_sekolah' => 'required|string|max:50',
            'rata_rata_nilai' => 'nullable|numeric|between:0,100'
        ]);

        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        AcademicHistory::updateOrCreate(
            ['pendaftar_id' => $pendaftar->id],
            $request->all()
        );

        return redirect()->route('user.data')->with('success', 'Data riwayat akademik berhasil disimpan.');
    }

    public function health()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();
        $healthRecord = HealthRecord::where('pendaftar_id', $pendaftar->id)->first();

        return view('user.data.health', compact('pendaftar', 'healthRecord'));
    }

    public function storeHealth(Request $request)
    {
        $request->validate([
            'tinggi_badan' => 'required|integer',
            'berat_badan' => 'required|integer',
            'golongan_darah' => 'required|string|max:5',
            'riwayat_penyakit' => 'nullable|string',
            'alergi' => 'nullable|string',
            'obat_yang_dikonsumsi' => 'nullable|string'
        ]);

        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        HealthRecord::updateOrCreate(
            ['pendaftar_id' => $pendaftar->id],
            $request->all()
        );

        return redirect()->route('user.data')->with('success', 'Data kesehatan berhasil disimpan.');
    }

    public function documents()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();
        $documents = Document::where('pendaftar_id', $pendaftar->id)->get();

        return view('user.data.documents', compact('pendaftar', 'documents'));
    }

    public function storeDocuments(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|max:100',
            'document_name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            Document::create([
                'pendaftar_id' => $pendaftar->id,
                'document_type' => $request->document_type,
                'document_name' => $request->document_name,
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);
        }

        return redirect()->route('user.data.documents')->with('success', 'Dokumen berhasil diupload.');
    }

    public function grades()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        $gradeReports = GradeReport::where('pendaftar_id', $pendaftar->id)
            ->with('subjectGrades.academicSubject')
            ->orderBy('tahun_ajaran', 'desc')
            ->orderBy('semester', 'desc')
            ->get();

        // Pastikan academicSubjects tidak null
        $academicSubjects = AcademicSubject::active()
            ->orderBy('kategori')
            ->orderBy('nama_mapel')
            ->get();

        // Jika tidak ada data, berikan collection kosong
        if($academicSubjects->isEmpty()) {
            $academicSubjects = collect();
        }

        return view('user.data.grades', compact('pendaftar', 'gradeReports', 'academicSubjects'));
    }

    public function storeGrades(Request $request)
    {
        $request->validate([
            'semester' => 'required|integer|between:1,8',
            'tahun_ajaran' => 'required|string|max:20',
            'subjects' => 'required|array',
            'subjects.*.academic_subject_id' => 'required|exists:academic_subjects,id',
            'subjects.*.nilai' => 'required|numeric|between:0,100'
        ]);

        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        // Create or update grade report
        $gradeReport = GradeReport::updateOrCreate(
            [
                'pendaftar_id' => $pendaftar->id,
                'semester' => $request->semester,
                'tahun_ajaran' => $request->tahun_ajaran
            ]
        );

        // Store subject grades
        foreach ($request->subjects as $subjectData) {
            SubjectGrade::updateOrCreate(
                [
                    'grade_report_id' => $gradeReport->id,
                    'academic_subject_id' => $subjectData['academic_subject_id']
                ],
                [
                    'nilai' => $subjectData['nilai']
                ]
            );
        }

        return redirect()->route('user.data')->with('success', 'Data nilai berhasil disimpan.');
    }

    public function achievements()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();
        $achievements = Achievement::where('pendaftar_id', $pendaftar->id)->get();

        return view('user.data.achievements', compact('pendaftar', 'achievements'));
    }

    public function storeAchievements(Request $request)
    {
        $request->validate([
            'nama_prestasi' => 'required|string|max:255',
            'jenis_prestasi' => 'required|string|max:100',
            'tingkat' => 'required|string|max:50',
            'tahun' => 'required|integer',
            'penyelenggara' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        Achievement::create([
            'pendaftar_id' => $pendaftar->id,
            'nama_prestasi' => $request->nama_prestasi,
            'jenis_prestasi' => $request->jenis_prestasi,
            'tingkat' => $request->tingkat,
            'tahun' => $request->tahun,
            'penyelenggara' => $request->penyelenggara,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('user.data.achievements')->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function review()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        // Get all data for review
        $studentDetail = StudentDetail::where('pendaftar_id', $pendaftar->id)->first();
        $parentDetail = ParentDetail::where('pendaftar_id', $pendaftar->id)->first();
        $academicHistory = AcademicHistory::where('pendaftar_id', $pendaftar->id)->first();
        $healthRecord = HealthRecord::where('pendaftar_id', $pendaftar->id)->first();
        $documents = Document::where('pendaftar_id', $pendaftar->id)->get();
        $gradeReports = GradeReport::where('pendaftar_id', $pendaftar->id)->with('subjectGrades.academicSubject')->get();
        $achievements = Achievement::where('pendaftar_id', $pendaftar->id)->get();

        return view('user.data.review', compact(
            'pendaftar', 'studentDetail', 'parentDetail', 'academicHistory',
            'healthRecord', 'documents', 'gradeReports', 'achievements'
        ));
    }

    public function submit(Request $request)
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        // Update status to submitted
        $pendaftar->update(['status' => 'submitted']);

        return redirect()->route('user.dashboard')->with('success', 'Data pendaftaran berhasil disubmit untuk review.');
    }

    private function getCompletionStatus($pendaftar)
    {
        $status = [];

        // Student Details
        $studentDetail = StudentDetail::where('pendaftar_id', $pendaftar->id)->first();
        $status['student'] = $studentDetail && $this->isStudentDetailComplete($studentDetail);

        // Parent Details
        $parentDetail = ParentDetail::where('pendaftar_id', $pendaftar->id)->first();
        $status['parent'] = $parentDetail && $this->isParentDetailComplete($parentDetail);

        // Academic History
        $academicHistory = AcademicHistory::where('pendaftar_id', $pendaftar->id)->first();
        $status['academic'] = $academicHistory && $this->isAcademicHistoryComplete($academicHistory);

        // Health Records
        $healthRecord = HealthRecord::where('pendaftar_id', $pendaftar->id)->first();
        $status['health'] = $healthRecord && $this->isHealthRecordComplete($healthRecord);

        // Documents
        $documentsCount = Document::where('pendaftar_id', $pendaftar->id)->count();
        $status['documents'] = $documentsCount >= 3;

        // Grade Reports
        $gradeReportsCount = GradeReport::where('pendaftar_id', $pendaftar->id)->count();
        $status['grades'] = $gradeReportsCount >= 1;

        // Achievements
        $achievementsCount = Achievement::where('pendaftar_id', $pendaftar->id)->count();
        $status['achievements'] = $achievementsCount >= 1;

        return $status;
    }

    private function isStudentDetailComplete($studentDetail)
    {
        $requiredFields = [
            'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
            'agama', 'kewarganegaraan', 'alamat_lengkap', 'kode_pos',
            'no_telepon', 'email'
        ];

        foreach ($requiredFields as $field) {
            if (empty($studentDetail->$field)) {
                return false;
            }
        }

        return true;
    }

    private function isParentDetailComplete($parentDetail)
    {
        $requiredFields = [
            'nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu',
            'alamat_orangtua', 'no_telepon_orangtua'
        ];

        foreach ($requiredFields as $field) {
            if (empty($parentDetail->$field)) {
                return false;
            }
        }

        return true;
    }

    private function isAcademicHistoryComplete($academicHistory)
    {
        $requiredFields = [
            'nama_sekolah', 'alamat_sekolah', 'tahun_lulus', 'jenjang_sekolah'
        ];

        foreach ($requiredFields as $field) {
            if (empty($academicHistory->$field)) {
                return false;
            }
        }

        return true;
    }

    private function isHealthRecordComplete($healthRecord)
    {
        $requiredFields = [
            'tinggi_badan', 'berat_badan', 'golongan_darah'
        ];

        foreach ($requiredFields as $field) {
            if (empty($healthRecord->$field)) {
                return false;
            }
        }

        return true;
    }
}
