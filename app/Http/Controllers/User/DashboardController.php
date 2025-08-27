<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\Payment;
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

        // Cek data pendaftar user
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        // Cek status pembayaran dari tabel payments
        $payment = Payment::where('pendaftar_id', $pendaftar->id ?? 0)
                         ->where('status', 'PAID')
                         ->first();
        $isPaid = $payment ? true : false;

        // Status pendaftar dari tabel pendaftars field status
        $registrationStatus = $pendaftar ? $pendaftar->status : 'draft';

        // Hitung kelengkapan data berdasarkan field yang sudah diisi
        $dataCompletion = $this->calculateDataCompletion($pendaftar);

        // Data untuk statistik
        $paymentAmount = $pendaftar->payment_amount ?? 150000;
        $paymentDate = $payment ? $payment->paid_at : null;

        // Recent activities
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

        // User statistics
        $stats = [
            'my_applications' => $pendaftar ? 1 : 0,
            'pending_applications' => $registrationStatus === 'pending' ? 1 : 0,
            'verified_applications' => $registrationStatus === 'verified' ? 1 : 0,
        ];

        return view('user.dashboard', compact(
            'user',
            'pendaftar',
            'isPaid',
            'registrationStatus',
            'dataCompletion',
            'paymentAmount',
            'paymentDate',
            'stats',
            'recent_activities'
        ));
    }

    /**
     * Hitung persentase kelengkapan data
     */
    private function calculateDataCompletion($pendaftar)
    {
        if (!$pendaftar) return 0;

        $totalFields = 15; // Total field yang harus diisi
        $filledFields = 0;

        // Cek field yang sudah diisi
        if ($pendaftar->nama_murid) $filledFields++;
        if ($pendaftar->nisn) $filledFields++;
        if ($pendaftar->tanggal_lahir) $filledFields++;
        if ($pendaftar->alamat) $filledFields++;
        if ($pendaftar->jenjang) $filledFields++;
        if ($pendaftar->unit) $filledFields++;
        if ($pendaftar->asal_sekolah) $filledFields++;
        if ($pendaftar->nama_sekolah) $filledFields++;
        if ($pendaftar->kelas) $filledFields++;
        if ($pendaftar->nama_ayah) $filledFields++;
        if ($pendaftar->telp_ayah) $filledFields++;
        if ($pendaftar->nama_ibu) $filledFields++;
        if ($pendaftar->telp_ibu) $filledFields++;
        if ($pendaftar->foto_murid_path) $filledFields++;
        if ($pendaftar->akta_kelahiran_path) $filledFields++;

        return round(($filledFields / $totalFields) * 100);
    }

    /**
     * Demo payment - untuk testing
     */
    public function demoPayment()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        if (!$pendaftar) {
            return response()->json([
                'success' => false,
                'message' => 'Data pendaftar tidak ditemukan'
            ], 404);
        }

        // Cek apakah sudah ada payment PAID
        $existingPayment = Payment::where('pendaftar_id', $pendaftar->id)
                                 ->where('status', 'PAID')
                                 ->first();

        if ($existingPayment) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah lunas'
            ]);
        }

        // Buat atau update payment record
        $payment = Payment::updateOrCreate(
            [
                'pendaftar_id' => $pendaftar->id,
                'external_id' => 'DEMO-' . $pendaftar->no_pendaftaran . '-' . time()
            ],
            [
                'invoice_id' => 'DEMO_INV_' . time(),
                'amount' => $pendaftar->payment_amount ?? 150000,
                'status' => 'PAID',
                'paid_at' => now(),
                'xendit_response' => [
                    'demo_payment' => true,
                    'simulated_at' => now()->toISOString(),
                    'payment_method' => 'DEMO_BANK_TRANSFER',
                    'transaction_id' => 'DEMO_TXN_' . time()
                ]
            ]
        );

        // Update status pendaftar
        $pendaftar->update([
            'sudah_bayar_formulir' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Demo payment berhasil! Status pembayaran telah diubah menjadi PAID.',
            'data' => [
                'payment_status' => $payment->status,
                'amount' => $payment->amount,
                'paid_at' => $payment->paid_at->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Get real-time dashboard data
     */
    public function getDashboardData()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();

        // Cek status pembayaran
        $payment = Payment::where('pendaftar_id', $pendaftar->id ?? 0)
                         ->where('status', 'PAID')
                         ->first();
        $isPaid = $payment ? true : false;

        // Status pendaftar
        $registrationStatus = $pendaftar ? $pendaftar->status : 'draft';

        // Hitung kelengkapan data
        $dataCompletion = $this->calculateDataCompletion($pendaftar);

        return response()->json([
            'isPaid' => $isPaid,
            'registrationStatus' => $registrationStatus,
            'dataCompletion' => $dataCompletion,
            'paymentDate' => $payment ? $payment->paid_at->format('d F Y, H:i') : null,
            'pendaftarExists' => $pendaftar ? true : false
        ]);
    }
}
