<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProgresPendaftaranController extends Controller
{
    /**
     * Display the registration progress with unit-based summary.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $unit = $request->get('unit', 'all'); // Default to 'all' units
        $academicYear = $request->get('academic_year', '2026/2027'); // Default to current academic year

        // Get available units for tabs
        $availableUnits = Pendaftar::select('unit')
            ->distinct()
            ->orderBy('unit')
            ->pluck('unit')
            ->filter()
            ->values();

        // Base query for individual students
        $query = Pendaftar::with('user')
            ->select('id', 'user_id', 'nama_murid', 'no_pendaftaran', 'unit', 'jenjang', 'status', 'overall_status', 'sudah_bayar_formulir', 'student_status', 'student_activated_at', 'student_status_notes', 'academic_year', 'created_at')
            ->orderBy('created_at', 'desc');

        // Apply academic year filter
        if ($academicYear && $academicYear !== '') {
            $query->where('academic_year', $academicYear);
        }

        // Apply unit filter
        if ($unit !== 'all') {
            $query->where('unit', $unit);
        }

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_murid', 'ILIKE', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'ILIKE', "%{$search}%")
                  ->orWhere('unit', 'ILIKE', "%{$search}%")
                  ->orWhere('jenjang', 'ILIKE', "%{$search}%")
                  ->orWhere('status', 'ILIKE', "%{$search}%")
                  ->orWhere('overall_status', 'ILIKE', "%{$search}%");
            });
        }

        $studentsData = $query->paginate(15);
        $studentsData->appends($request->query());

        // Overall statistics (filtered by unit and academic year if selected)
        $statsQuery = Pendaftar::query();

        // Apply academic year filter to stats
        if ($academicYear && $academicYear !== '') {
            $statsQuery->where('academic_year', $academicYear);
        }

        if ($unit !== 'all') {
            $statsQuery->where('unit', $unit);
        }

        $totalPendaftar = $statsQuery->count();
        $totalApproved = $statsQuery->where('status', 'diverifikasi')->count();
        $totalPending = $statsQuery->where('status', 'pending')->count();
        $totalRejected = $statsQuery->where('overall_status', 'Tidak Lulus')->count();

        // Payment statistics using sudah_bayar_formulir
        $totalPaid = $statsQuery->where('sudah_bayar_formulir', true)->count();
        $totalPaymentPending = $statsQuery->where('sudah_bayar_formulir', false)->count();
        $totalPaymentFailed = $statsQuery->where('overall_status', 'Tidak Lulus')->count();

        // Unit statistics (filtered by academic year if selected)
        $unitStatsQuery = Pendaftar::select('unit as school_unit', DB::raw('COUNT(*) as count'))
            ->groupBy('unit')
            ->orderBy('count', 'desc');

        if ($academicYear && $academicYear !== '') {
            $unitStatsQuery->where('academic_year', $academicYear);
        }

        $unitStats = $unitStatsQuery->get();

        // Handle AJAX requests
        if ($request->ajax()) {
            $html = view('admin.progres-pendaftaran.partials.table', compact('studentsData'))->render();
            $pagination = $studentsData->links()->toHtml();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'statistics' => [
                    'totalPendaftar' => $totalPendaftar,
                    'totalApproved' => $totalApproved,
                    'totalPending' => $totalPending,
                    'totalRejected' => $totalRejected,
                    'totalPaid' => $totalPaid,
                    'totalPaymentPending' => $totalPaymentPending,
                    'totalPaymentFailed' => $totalPaymentFailed
                ]
            ]);
        }

        return view('admin.progres-pendaftaran.index', compact(
            'studentsData',
            'search',
            'unit',
            'academicYear',
            'availableUnits',
            'totalPendaftar',
            'totalApproved',
            'totalPending',
            'totalRejected',
            'totalPaid',
            'totalPaymentPending',
            'totalPaymentFailed',
            'unitStats'
        ));
    }

    /**
     * Update student status
     */
    public function updateStudentStatus(Request $request, $id)
    {
        $request->validate([
            'student_status' => 'required|in:inactive,active,graduated,dropped_out,transferred',
            'student_status_notes' => 'nullable|string|max:500',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);

        $oldStatus = $pendaftar->student_status;
        $pendaftar->student_status = $request->student_status;
        $pendaftar->student_status_notes = $request->student_status_notes;

        // Set activation date if status changed to active
        if ($request->student_status === 'active' && $oldStatus !== 'active') {
            $pendaftar->student_activated_at = now();
        }

        $pendaftar->save();

        return response()->json([
            'success' => true,
            'message' => 'Status siswa berhasil diperbarui',
            'data' => [
                'student_status' => $pendaftar->student_status,
                'student_activated_at' => $pendaftar->student_activated_at,
                'student_status_notes' => $pendaftar->student_status_notes,
            ]
        ]);
    }

    /**
     * Get student status management modal data
     */
    public function getStudentStatusModal($id)
    {
        $pendaftar = Pendaftar::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pendaftar->id,
                'nama_murid' => $pendaftar->nama_murid,
                'no_pendaftaran' => $pendaftar->no_pendaftaran,
                'unit' => $pendaftar->unit,
                'student_status' => $pendaftar->student_status ?? 'inactive',
                'student_activated_at' => $pendaftar->student_activated_at,
                'student_status_notes' => $pendaftar->student_status_notes,
            ]
        ]);
    }

    /**
     * Bulk update overall status
     */
    public function bulkUpdateOverallStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:pendaftars,id',
            'overall_status' => 'required|in:Draft,Diverifikasi,Sudah Bayar,Observasi,Tes Tulis,Praktek Shalat & BTQ,Wawancara,Psikotest,Lulus,Tidak Lulus',
        ]);

        try {
            $updatedCount = Pendaftar::whereIn('id', $request->ids)
                ->update([
                    'overall_status' => $request->overall_status,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => "Status keseluruhan {$updatedCount} siswa berhasil diperbarui menjadi '{$request->overall_status}'",
                'data' => [
                    'updated_count' => $updatedCount,
                    'overall_status' => $request->overall_status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk update overall status error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status keseluruhan siswa'
            ], 500);
        }
    }
}
