<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_pendaftar' => Pendaftar::count(),
            'pending_pendaftar' => Pendaftar::where('status', 'pending')->count(),
            'verified_pendaftar' => Pendaftar::where('status', 'diverifikasi')->count(),
        ];

        $recent_pendaftar = Pendaftar::latest()->limit(5)->get();
        $recent_users = User::where('role', 'user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_pendaftar', 'recent_users'));
    }
}
