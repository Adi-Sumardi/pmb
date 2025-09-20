<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgresPendaftaranController extends Controller
{
    /**
     * Display the registration progress with unit-based summary.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $unit = $request->get('unit', 'all'); // Default to 'all' units

        // Get available units for tabs
        $availableUnits = Pendaftar::select('unit')
            ->distinct()
            ->orderBy('unit')
            ->pluck('unit')
            ->filter()
            ->values();

        // Base query for individual students
        $query = Pendaftar::with('user')
            ->select('id', 'user_id', 'nama_murid', 'no_pendaftaran', 'unit', 'jenjang', 'status', 'overall_status', 'sudah_bayar_formulir', 'created_at')
            ->orderBy('created_at', 'desc');

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

        // Overall statistics (filtered by unit if selected)
        $statsQuery = Pendaftar::query();
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

        // Unit statistics (always show all units)
        $unitStats = Pendaftar::select('unit as school_unit', DB::raw('COUNT(*) as count'))
            ->groupBy('unit')
            ->orderBy('count', 'desc')
            ->get();

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
}
