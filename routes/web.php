<?php

use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentDetailController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\DataController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Health Check Routes (no authentication required)
Route::prefix('health')->name('health.')->group(function () {
    Route::get('/', [HealthCheckController::class, 'basic'])->name('basic');
    Route::get('/comprehensive', [HealthCheckController::class, 'comprehensive'])->name('comprehensive');
    Route::get('/metrics', [HealthCheckController::class, 'metrics'])->name('metrics');
    Route::get('/dashboard', [HealthCheckController::class, 'dashboard'])->name('dashboard');
    Route::get('/readiness', [HealthCheckController::class, 'readiness'])->name('readiness');
    Route::get('/liveness', [HealthCheckController::class, 'liveness'])->name('liveness');
    Route::post('/restart', [HealthCheckController::class, 'markRestart'])->name('restart');
});

// System Status Dashboard (admin only)
Route::get('/system-status', function () {
    return view('dashboard.system-status');
})->middleware(['auth', 'admin'])->name('system.status');

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
    Route::get('/dashboard/data', [UserDashboardController::class, 'getDashboardData'])->name('dashboard.data');

    // Data completion routes
    Route::get('/data', [DataController::class, 'index'])->name('data');
    Route::get('/data/student', [DataController::class, 'student'])->name('data.student');
    Route::post('/data/student', [DataController::class, 'storeStudent'])->name('data.student.store');

    Route::get('/data/parent', [DataController::class, 'parent'])->name('data.parent');
    Route::post('/data/parent', [DataController::class, 'storeParent'])->name('data.parent.store');

    Route::get('/data/academic', [DataController::class, 'academic'])->name('data.academic');
    Route::post('/data/academic', [DataController::class, 'storeAcademic'])->name('data.academic.store');

    Route::get('/data/health', [DataController::class, 'health'])->name('data.health');
    Route::post('/data/health', [DataController::class, 'storeHealth'])->name('data.health.store');

    Route::get('/data/documents', [DataController::class, 'documents'])->name('data.documents');
    Route::post('/data/documents', [DataController::class, 'storeDocuments'])->name('data.documents.store');
    Route::delete('/data/documents/{id}', [DataController::class, 'destroyDocument'])->name('data.documents.destroy');

    Route::get('/data/achievements', [DataController::class, 'achievements'])->name('data.achievements');
    Route::post('/data/achievements', [DataController::class, 'storeAchievements'])->name('data.achievements.store');
    Route::delete('/data/achievements/{id}', [DataController::class, 'destroyAchievement'])->name('data.achievements.destroy');

    Route::get('/data/review', [DataController::class, 'review'])->name('data.review');
    Route::post('/data/submit', [DataController::class, 'submit'])->name('data.submit');
});

// Profile routes (accessible by both admin and user)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Secure File Routes
Route::middleware('auth')->group(function () {
    Route::get('/file/download/{path}', [FileController::class, 'download'])->name('file.download');
    Route::get('/file/info/{path}', [FileController::class, 'info'])->name('file.info');
});

// Pendaftar routes
Route::post('/pendaftaran', [PendaftarController::class, 'store'])->name('pendaftaran.store');

Route::middleware('auth')->group(function () {
    Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar');

    // Place specific routes before wildcard routes - IMPORTANT!
    Route::post('/pendaftar/bulk-verify', [PendaftarController::class, 'bulkVerify'])->name('pendaftar.bulk-verify');
    Route::delete('/pendaftar/bulk-delete', [PendaftarController::class, 'bulkDelete'])->name('pendaftar.bulk-delete');
    Route::patch('/pendaftar/bulk-update-status', [PendaftarController::class, 'bulkUpdateStatus'])->name('pendaftar.bulk-update-status');

    // Then the wildcard routes
    Route::get('/pendaftar/{id}/validasi', [PendaftarController::class, 'validasi'])->name('pendaftar.validasi');
    Route::patch('/pendaftar/{id}', [PendaftarController::class, 'update'])->name('pendaftar.update');
    Route::delete('/pendaftar', [PendaftarController::class, 'destroy'])->name('pendaftar.destroy');
});

// Payment Routes (User & Admin) - REMOVE ALL DEMO ROUTES
Route::middleware('auth')->group(function () {
    // Main payment routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payments/create-invoice', [PaymentController::class, 'createInvoice'])->name('payment.create-invoice');

    // Success & Failed routes - REQUIRED ROUTES
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');

    // Transaction routes
    Route::get('/transactions', [PaymentController::class, 'transactions'])->name('transactions.index');
    Route::get('/transactions/{id}', [PaymentController::class, 'transactionDetail'])->name('transactions.show');

    // Payment utilities
    Route::post('/payment/cleanup-expired', [PaymentController::class, 'cleanupAllExpiredPayments'])->name('payment.cleanup');
    Route::get('/payment/debug', [PaymentController::class, 'debugPaymentMode'])->name('payment.debug');

    Route::get('/admin/transactions/export', [AdminTransactionController::class, 'export'])->name('admin.transactions.export');
    Route::get('/admin/transactions/export/pdf', [AdminTransactionController::class, 'exportPdf'])->name('admin.transactions.export.pdf');
    Route::get('/admin/transactions/print', [AdminTransactionController::class, 'printView'])->name('admin.transactions.print');
});

// Public webhook route (no auth required, no CSRF, but with enhanced security)
Route::post('/webhook/xendit', [PaymentController::class, 'webhook'])
    ->name('payment.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->middleware(['secure.webhook']);

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/logs', [App\Http\Controllers\LogController::class, 'viewLogs'])->name('logs.view');
    Route::post('/logs/clear', [App\Http\Controllers\LogController::class, 'clearLogs'])->name('logs.clear');
    Route::get('/logs/download', [App\Http\Controllers\LogController::class, 'downloadLogs'])->name('logs.download');
    Route::get('/logs/stream', [App\Http\Controllers\LogController::class, 'streamLogs'])->name('logs.stream');
    Route::get('/debug/payment-methods', [PaymentController::class, 'debugPaymentMethods'])->name('admin.debug.payment-methods');
});
