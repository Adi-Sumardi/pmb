<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic statistics
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_pendaftar' => Pendaftar::count(),
            'pending_pendaftar' => Pendaftar::where('status', 'pending')->count(),
            'verified_pendaftar' => Pendaftar::where('status', 'diverifikasi')->count(),
            'paid_pendaftar' => Pendaftar::where('sudah_bayar_formulir', true)->count(),
        ];

        // Get recent pendaftar
        $recent_pendaftar = Pendaftar::latest()->limit(5)->get();
        $recent_users = User::where('role', 'user')->latest()->limit(5)->get();

        // Unit-specific statistics for the unit analysis table
        $unit_stats = Pendaftar::select('unit')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = "diverifikasi" THEN 1 ELSE 0 END) as verified')
            ->selectRaw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending')
            ->selectRaw('SUM(CASE WHEN sudah_bayar_formulir = true THEN 1 ELSE 0 END) as paid')
            ->groupBy('unit')
            ->get();

        // Weekly registration trends (last 7 days)
        $weekly_trends = Pendaftar::select(DB::raw('DATE(created_at) as date'))
            ->selectRaw('COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly registration trends (last 30 days)
        $monthly_trends = Pendaftar::select(DB::raw('DATE(created_at) as date'))
            ->selectRaw('COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Yearly registration trends
        $yearly_trends = Pendaftar::select(DB::raw('MONTH(created_at) as month'))
            ->selectRaw('COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Distribution by unit for pie chart
        $unit_distribution = Pendaftar::select('unit')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('unit')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_pendaftar',
            'recent_users',
            'unit_stats',
            'weekly_trends',
            'monthly_trends',
            'yearly_trends',
            'unit_distribution'
        ));
    }
}
