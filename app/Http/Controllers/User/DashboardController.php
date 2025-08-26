<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Temporary data for testing - replace with actual logic later
        $isPaid = false; // Set to true for testing payment completed state
        $dataCompletion = 25; // Percentage 0-100
        $registrationStatus = 'draft'; // 'draft', 'pending', 'verified'

        // Real user statistics based on pendaftar data
        $stats = [
            'my_applications' => $isPaid ? 1 : 0,
            'pending_applications' => $registrationStatus === 'pending' ? 1 : 0,
            'verified_applications' => $registrationStatus === 'verified' ? 1 : 0,
        ];

        // If you want to show recent activity
        $recent_activities = collect([
            [
                'type' => 'login',
                'description' => 'Login ke sistem',
                'time' => now(),
                'icon' => 'box-arrow-in-right',
                'color' => 'blue'
            ],
            [
                'type' => 'profile_update',
                'description' => 'Profil diperbarui',
                'time' => $user->updated_at,
                'icon' => 'person',
                'color' => 'green'
            ]
        ]);

        return view('user.dashboard', compact(
            'stats',
            'user',
            'recent_activities',
            'isPaid',
            'dataCompletion',
            'registrationStatus'
        ));
    }
}
