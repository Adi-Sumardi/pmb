<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentUploadRequest;
use App\Http\Requests\SecureStudentDataRequest;
use App\Http\Requests\SecureParentDataRequest;
use App\Services\SecureFileUploadService;
use App\Services\SecurityValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Pendaftar;
use App\Models\Payment;
use App\Models\StudentBill;
use App\Models\BillPayment;
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
use Illuminate\Support\Facades\Log;

class DataController extends Controller
{
    private SecureFileUploadService $uploadService;

    public function __construct(SecureFileUploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }
    public function index()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        if (!$pendaftar) {
            return redirect()->route('user.dashboard')->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Check payment status using StudentBill system
        $formulirBill = StudentBill::where('pendaftar_id', $pendaftar->id)
            ->where('bill_type', 'registration_fee')
            ->first();

        $isPaid = false;
        if ($formulirBill && $formulirBill->payment_status === 'paid') {
            $isPaid = true;
        } else {
            // Fallback check for old payment system
            $payment = Payment::where('pendaftar_id', $pendaftar->id)->where('status', 'paid')->first();
            $isPaid = $payment ? true : false;
        }

        if (!$isPaid) {
            return redirect()->route('user.payments.index')->with('error', 'Silakan lakukan pembayaran formulir terlebih dahulu.');
        }

        // Get completion status for each section
        $completionStatus = $this->getCompletionStatus($pendaftar);

        return view('user.data.index', compact('pendaftar', 'completionStatus'));
    }

    public function student()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        if (!$pendaftar) {
            return redirect()->route('dashboard')->with('error', 'Data pendaftar tidak ditemukan.');
        }

        $studentDetail = StudentDetail::where('pendaftar_id', $pendaftar->id)->first();

        return view('user.data.student', compact('pendaftar', 'studentDetail'));
    }

    public function storeStudent(SecureStudentDataRequest $request)
    {
        // Validation and sanitization is handled by SecureStudentDataRequest
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        if (!$pendaftar) {
            return redirect()->route('dashboard')->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Additional security check
        if (!SecurityValidationService::validateUserPermissions($user, 'store_student_data', $pendaftar)) {
            abort(403, 'Unauthorized access');
        }

        // Find existing record or create new one
        $studentDetail = StudentDetail::updateOrCreate(
            ['pendaftar_id' => $pendaftar->id],
            $request->all()
        );

        // Update NISN in pendaftar table if needed
        if ($pendaftar->nisn !== $request->nisn) {
            $pendaftar->nisn = $request->nisn;
            $pendaftar->save();
        }

        return redirect()->route('user.data.index')->with('success', 'Data siswa berhasil disimpan. Silahkan lengkapi data orang tua.');
    }

    public function parent()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();
        $parentDetail = ParentDetail::where('pendaftar_id', $pendaftar->id)->first();

        return view('user.data.parent', compact('pendaftar', 'parentDetail'));
    }

    public function storeParent(SecureParentDataRequest $request)
    {
        // Validation and sanitization is handled by SecureParentDataRequest
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        if (!$pendaftar) {
            return redirect()->route('dashboard')->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Additional security check
        if (!SecurityValidationService::validateUserPermissions($user, 'store_parent_data', $pendaftar)) {
            abort(403, 'Unauthorized access');
        }

        ParentDetail::updateOrCreate(
            ['pendaftar_id' => $pendaftar->id],
            $request->validated() // Use validated data instead of all()
        );

        return redirect()->route('user.data.index')->with('success', 'Data orang tua berhasil disimpan.');
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
            'nama_sekolah_sebelumnya' => 'required|string|max:255',
            'alamat_sekolah_sebelumnya' => 'required|string',
            'jenjang_sebelumnya' => 'required|string|max:50',
            'tahun_lulus' => 'required|integer',
            'npsn_sekolah_sebelumnya' => 'nullable|string|max:20',
            'kelas_terakhir' => 'nullable|string|max:10',
            'no_ijazah' => 'nullable|string|max:50',
            'no_skhun' => 'nullable|string|max:50',
            'rata_rata_nilai' => 'nullable|numeric|between:0,100',
            'nilai_bahasa_indonesia' => 'nullable|numeric|between:0,100',
            'nilai_matematika' => 'nullable|numeric|between:0,100',
            'nilai_ipa' => 'nullable|numeric|between:0,100',
            'nilai_ips' => 'nullable|numeric|between:0,100',
            'nilai_bahasa_inggris' => 'nullable|numeric|between:0,100',
            'ranking_kelas' => 'nullable|integer|min:1',
            'jumlah_siswa_sekelas' => 'nullable|integer|min:1',
            'prestasi_akademik' => 'nullable|string',
            'prestasi_non_akademik' => 'nullable|string',
            'organisasi_yang_diikuti' => 'nullable|string',
            'jabatan_organisasi' => 'nullable|string',
        ]);

        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        AcademicHistory::updateOrCreate(
            ['pendaftar_id' => $pendaftar->id],
            $request->all()
        );

        return redirect()->route('user.data.index')->with('success', 'Data riwayat akademik berhasil disimpan.');
    }

    public function health()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();
        $studentDetail = StudentDetail::where('pendaftar_id', $pendaftar->id)->first();
        $healthRecord = HealthRecord::where('pendaftar_id', $pendaftar->id)->first();

        return view('user.data.health', compact('pendaftar', 'healthRecord', 'studentDetail'));
    }

    public function storeHealth(Request $request)
    {
        $request->validate([
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

        return redirect()->route('user.data.index')->with('success', 'Data kesehatan berhasil disimpan.');
    }

    public function documents()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();
        $documents = Document::where('pendaftar_id', $pendaftar->id)->get();

        return view('user.data.documents', compact('pendaftar', 'documents'));
    }

    public function storeDocuments(DocumentUploadRequest $request)
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        if (!$pendaftar) {
            return redirect()->route('user.data.student')->with('error', 'Anda harus melengkapi data diri terlebih dahulu.');
        }

        $uploadResult = $this->uploadService->uploadFile(
            $request->file('file'),
            'documents/' . $pendaftar->id
        );

        if (!$uploadResult['success']) {
            return redirect()->back()
                ->with('error', 'Upload gagal: ' . $uploadResult['error'])
                ->withInput();
        }

        try {
            Document::create([
                'pendaftar_id' => $pendaftar->id,
                'document_type' => $request->document_type,
                'document_name' => $request->document_name,
                'file_path' => $uploadResult['path'],
                'file_size' => $uploadResult['size'],
                'mime_type' => $uploadResult['mime_type']
            ]);

            Log::info('Document uploaded successfully', [
                'pendaftar_id' => $pendaftar->id,
                'document_type' => $request->document_type,
                'file_size' => $uploadResult['size']
            ]);

            return redirect()->route('user.data.documents')
                ->with('success', 'Dokumen berhasil diupload dengan aman.');

        } catch (\Exception $e) {
            // Cleanup uploaded file if database insert fails
            $this->uploadService->deleteFile($uploadResult['path']);

            Log::error('Document save failed', [
                'pendaftar_id' => $pendaftar->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan dokumen.')
                ->withInput();
        }
    }

    public function destroyDocument($id)
    {
        try {
            $user = Auth::user();
            $pendaftar = Pendaftar::where('user_id', $user->id)->first();

            if (!$pendaftar) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pendaftar tidak ditemukan'
                ], 404);
            }

            $document = Document::where('id', $id)
                            ->where('pendaftar_id', $pendaftar->id)
                            ->first();

            if (!$document) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dokumen tidak ditemukan atau Anda tidak memiliki akses'
                ], 404);
            }

            // Hapus file dari storage menggunakan secure service
            if ($document->file_path) {
                $this->uploadService->deleteFile($document->file_path);
            }

            $documentName = $document->document_name;
            $document->delete();

            Log::info('Document deleted successfully', [
                'document_id' => $id,
                'pendaftar_id' => $pendaftar->id,
                'document_name' => $documentName
            ]);

            return response()->json([
                'success' => true,
                'message' => "Dokumen $documentName berhasil dihapus"
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus dokumen'
            ], 500);
        }
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
        $achievements = Achievement::where('pendaftar_id', $pendaftar->id)->get();

        return view('user.data.review', compact(
            'pendaftar', 'studentDetail', 'parentDetail', 'academicHistory',
            'healthRecord', 'documents', 'achievements'
        ));
    }

    public function submit(Request $request)
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        // Update status to submitted
        $pendaftar->update(['current_status' => 'submitted']);

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

        // Achievements
        $achievementsCount = Achievement::where('pendaftar_id', $pendaftar->id)->count();
        $status['achievements'] = $achievementsCount >= 1;

        return $status;
    }

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

    private function isHealthRecordComplete($healthRecord)
    {
        // Data kesehatan dianggap lengkap jika objek healthRecord ada
        // karena kebanyakan data kesehatan bersifat opsional
        return $healthRecord !== null;
    }
}
