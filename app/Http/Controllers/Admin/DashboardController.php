<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\User;
use App\Models\StudentBill;
use App\Models\BillPayment;
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
            'pending_pendaftar' => Pendaftar::where('overall_status', 'Draft')->count(),
            'verified_pendaftar' => Pendaftar::where('overall_status', 'Diverifikasi')->count(),
            'paid_pendaftar' => Pendaftar::where('sudah_bayar_formulir', true)->count(),
        ];

        // Student Status Statistics
        $studentStats = [
            'active_students' => Pendaftar::activeStudents()->count(),
            'inactive_students' => Pendaftar::where('student_status', 'inactive')->count(),
            'graduated_students' => Pendaftar::where('student_status', 'graduated')->count(),
            'dropped_out_students' => Pendaftar::where('student_status', 'dropped_out')->count(),
            'transferred_students' => Pendaftar::where('student_status', 'transferred')->count(),
        ];

        // Billing Statistics
        $billingStats = [
            'total_bills' => StudentBill::count(),
            'paid_bills' => StudentBill::where('remaining_amount', '<=', 0)->count(),
            'unpaid_bills' => StudentBill::where('remaining_amount', '>', 0)->count(),
            'total_revenue' => BillPayment::where('status', 'confirmed')->sum('amount'),
            'pending_payments' => BillPayment::where('status', 'pending')->count(),
        ];

        // Get recent pendaftar
        $recent_pendaftar = Pendaftar::latest()->limit(5)->get();
        $recent_users = User::where('role', 'user')->latest()->limit(5)->get();

        // Recent student status changes
        $recentStatusChanges = Pendaftar::whereNotNull('student_activated_at')
            ->orWhereNotNull('student_status_notes')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Unit-specific statistics for the unit analysis table
        $unit_stats = Pendaftar::select('unit')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN overall_status = ? THEN 1 ELSE 0 END) as verified', ['Diverifikasi'])
            ->selectRaw('SUM(CASE WHEN overall_status = ? THEN 1 ELSE 0 END) as pending', ['Draft'])
            ->selectRaw('SUM(CASE WHEN sudah_bayar_formulir = true THEN 1 ELSE 0 END) as paid')
            ->selectRaw('SUM(CASE WHEN student_status = ? THEN 1 ELSE 0 END) as active_students', ['active'])
            ->selectRaw('SUM(CASE WHEN student_status = ? THEN 1 ELSE 0 END) as inactive_students', ['inactive'])
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
        $yearly_trends = Pendaftar::select(DB::raw('EXTRACT(MONTH FROM created_at) as month'))
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

        // Student status distribution for analytics
        $statusDistribution = Pendaftar::select('student_status')
            ->selectRaw('COUNT(*) as total')
            ->whereNotNull('student_status')
            ->groupBy('student_status')
            ->get();

        // Bill analytics
        $billAnalytics = [
            'monthly_revenue' => BillPayment::where('status', 'confirmed')
                ->whereMonth('confirmed_at', now()->month)
                ->sum('amount'),
            'weekly_revenue' => BillPayment::where('status', 'confirmed')
                ->where('confirmed_at', '>=', now()->subDays(7))
                ->sum('amount'),
            'overdue_bills' => StudentBill::where('remaining_amount', '>', 0)
                ->where('due_date', '<', now())
                ->count(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'studentStats',
            'billingStats',
            'recent_pendaftar',
            'recent_users',
            'recentStatusChanges',
            'unit_stats',
            'weekly_trends',
            'monthly_trends',
            'yearly_trends',
            'unit_distribution',
            'statusDistribution',
            'billAnalytics'
        ));
    }
}
