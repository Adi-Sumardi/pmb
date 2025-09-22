<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataSiswaController extends Controller
{
    /**
     * Display list of accepted students with filters
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $unit = $request->get('unit', 'all');
        $academicYear = $request->get('academic_year', '2026/2027');
        $studentStatus = $request->get('student_status', 'all');

        // Get available units for filter
        $availableUnits = Pendaftar::select('unit')
            ->distinct()
            ->orderBy('unit')
            ->pluck('unit')
            ->filter()
            ->values();

        // Base query for accepted students only
        $query = Pendaftar::with('user')
            ->select('id', 'user_id', 'nama_murid', 'nisn', 'no_pendaftaran', 'unit', 'jenjang', 'tanggal_lahir', 'status', 'overall_status', 'student_status', 'student_activated_at', 'student_status_notes', 'academic_year', 'created_at')
            ->where('overall_status', 'Lulus') // Only accepted students
            ->orderBy('nama_murid', 'asc');

        // Apply academic year filter
        if ($academicYear && $academicYear !== 'all') {
            $query->where('academic_year', $academicYear);
        }

        // Apply unit filter
        if ($unit !== 'all') {
            $query->where('unit', $unit);
        }

        // Apply student status filter
        if ($studentStatus !== 'all') {
            $query->where('student_status', $studentStatus);
        }

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_murid', 'ILIKE', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'ILIKE', "%{$search}%")
                  ->orWhere('nisn', 'ILIKE', "%{$search}%")
                  ->orWhere('unit', 'ILIKE', "%{$search}%")
                  ->orWhere('jenjang', 'ILIKE', "%{$search}%");
            });
        }

        $studentsData = $query->paginate(20);
        $studentsData->appends($request->query());

        // Statistics for accepted students
        $statsQuery = Pendaftar::where('overall_status', 'Lulus');

        // Apply academic year filter to stats
        if ($academicYear && $academicYear !== 'all') {
            $statsQuery->where('academic_year', $academicYear);
        }

        if ($unit !== 'all') {
            $statsQuery->where('unit', $unit);
        }

        $totalStudents = $statsQuery->count();
        $activeStudents = $statsQuery->where('student_status', 'active')->count();
        $inactiveStudents = $statsQuery->where('student_status', 'inactive')->count();
        $graduatedStudents = $statsQuery->where('student_status', 'graduated')->count();
        $droppedOutStudents = $statsQuery->where('student_status', 'dropped_out')->count();
        $transferredStudents = $statsQuery->where('student_status', 'transferred')->count();

        // Unit statistics for accepted students
        $unitStatsQuery = Pendaftar::select(['unit as school_unit', DB::raw('COUNT(*) as count')])
            ->where('overall_status', 'Lulus')
            ->groupBy('unit')
            ->orderBy('count', 'desc');

        if ($academicYear && $academicYear !== 'all') {
            $unitStatsQuery->where('academic_year', $academicYear);
        }

        $unitStats = $unitStatsQuery->get();

        // Handle AJAX requests
        if ($request->ajax()) {
            $html = view('admin.data-siswa.partials.table', compact('studentsData'))->render();
            $pagination = $studentsData->links()->toHtml();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'statistics' => [
                    'totalStudents' => $totalStudents,
                    'activeStudents' => $activeStudents,
                    'inactiveStudents' => $inactiveStudents,
                    'graduatedStudents' => $graduatedStudents,
                    'droppedOutStudents' => $droppedOutStudents,
                    'transferredStudents' => $transferredStudents
                ]
            ]);
        }

        return view('admin.data-siswa.index', compact(
            'studentsData',
            'search',
            'unit',
            'academicYear',
            'studentStatus',
            'availableUnits',
            'totalStudents',
            'activeStudents',
            'inactiveStudents',
            'graduatedStudents',
            'droppedOutStudents',
            'transferredStudents',
            'unitStats'
        ));
    }

    /**
     * Show student detail
     */
    public function show($id)
    {
        $student = Pendaftar::with('user')->findOrFail($id);

        if ($student->overall_status !== 'Lulus') {
            abort(404, 'Student not found or not accepted yet.');
        }

        return view('admin.data-siswa.detail', compact('student'));
    }

    /**
     * Update student status (individual)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'student_status' => 'required|in:inactive,active,graduated,dropped_out,transferred',
            'student_status_notes' => 'nullable|string|max:500',
        ]);

        try {
            $student = Pendaftar::findOrFail($id);

            if ($student->overall_status !== 'Lulus') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya siswa yang diterima yang dapat diubah statusnya.'
                ], 400);
            }

            $oldStatus = $student->student_status;
            $newStatus = $request->student_status;

            $student->update([
                'student_status' => $newStatus,
                'student_status_notes' => $request->student_status_notes,
                'student_activated_at' => $newStatus === 'active' ? now() : $student->student_activated_at,
            ]);

            // Log the status change
            Log::info('Student status updated', [
                'student_id' => $student->id,
                'student_name' => $student->nama_murid,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'notes' => $request->student_status_notes,
                'updated_by' => Auth::user()?->name ?? 'Unknown'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status siswa berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating student status', [
                'student_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status siswa.'
            ], 500);
        }
    }

    /**
     * Bulk update student status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:pendaftars,id',
            'student_status' => 'required|in:inactive,active,graduated,dropped_out,transferred',
            'student_status_notes' => 'nullable|string|max:500',
        ]);

        try {
            $studentIds = $request->student_ids;
            $newStatus = $request->student_status;
            $notes = $request->student_status_notes;

            // Verify all students are accepted
            $students = Pendaftar::whereIn('id', $studentIds)
                ->where('overall_status', 'Lulus')
                ->get();

            if ($students->count() !== count($studentIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beberapa siswa tidak ditemukan atau belum diterima.'
                ], 400);
            }

            // Update students
            $updateData = [
                'student_status' => $newStatus,
                'student_status_notes' => $notes,
            ];

            if ($newStatus === 'active') {
                $updateData['student_activated_at'] = now();
            }

            Pendaftar::whereIn('id', $studentIds)->update($updateData);

            // Log bulk update
            Log::info('Bulk student status updated', [
                'student_count' => count($studentIds),
                'new_status' => $newStatus,
                'notes' => $notes,
                'updated_by' => Auth::user()?->name ?? 'Unknown'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status ' . count($studentIds) . ' siswa berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error bulk updating student status', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status siswa.'
            ], 500);
        }
    }
}
