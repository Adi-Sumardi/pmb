<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Main dashboard route - redirect based on role
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    try {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    } catch (\Exception $e) {
        // Fallback if method doesn't exist
        return redirect()->route('user.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// User Routes
Route::middleware(['auth', 'user.role'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
});

// Profile routes (accessible by both admin and user)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Pendaftar routes
Route::post('/pendaftaran', [PendaftarController::class, 'store'])->name('pendaftaran.store');

Route::middleware('auth')->group(function () {
    Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar');
    Route::get('/pendaftar/{id}/validasi', [PendaftarController::class, 'validasi'])->name('pendaftar.validasi');
    Route::patch('/pendaftar/{id}', [PendaftarController::class, 'update'])->name('pendaftar.update');
    Route::delete('/pendaftar', [PendaftarController::class, 'destroy'])->name('pendaftar.destroy');
    Route::post('/pendaftar/bulk-verify', [PendaftarController::class, 'bulkVerify'])->name('pendaftar.bulk-verify');
    Route::delete('/pendaftar/bulk-delete', [PendaftarController::class, 'bulkDelete'])->name('pendaftar.bulk-delete');
});

Route::middleware('auth')->group(function () {
    // Payment routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payments/create-invoice', [PaymentController::class, 'createInvoice'])->name('payment.create-invoice');

    // Demo payment routes
    Route::get('/pembayaran/demo/{external_id}', [PaymentController::class, 'demo'])->name('payment.demo');
    Route::post('/pembayaran/demo/{external_id}/pay', [PaymentController::class, 'demoPayment'])->name('payment.demo.pay');

    // Success & Failed routes
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');
});

Route::post('/webhook/xendit', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Test webhook route (remove in production)
Route::get('/test-webhook', function() {
    return response()->json([
        'webhook_url' => route('payment.webhook'),
        'app_url' => env('APP_URL'),
        'xendit_token' => env('XENDIT_WEBHOOK_TOKEN'),
        'api_key_format' => str_starts_with(env('XENDIT_SECRET_KEY'), 'xnd_') ? 'Valid' : 'Invalid'
    ]);
});

require __DIR__.'/auth.php';
