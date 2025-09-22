<?php

use App\Http\Controllers\HealthCheckController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\DataController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public registration route
Route::post('/pendaftaran', [PendaftarController::class, 'store'])->name('pendaftaran.store');

// Public webhook route (no auth required, no CSRF, but with enhanced security)
Route::post('/webhook/xendit', [PaymentController::class, 'webhook'])
    ->name('payment.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->middleware(['secure.webhook']);

// Health Check Routes
Route::prefix('health')->name('health.')->group(function () {
    Route::get('/', [HealthCheckController::class, 'basic'])->name('basic');
    Route::get('/comprehensive', [HealthCheckController::class, 'comprehensive'])->name('comprehensive');
    Route::get('/metrics', [HealthCheckController::class, 'metrics'])->name('metrics');
    Route::get('/dashboard', [HealthCheckController::class, 'dashboard'])->name('dashboard');
    Route::get('/readiness', [HealthCheckController::class, 'readiness'])->name('readiness');
    Route::get('/liveness', [HealthCheckController::class, 'liveness'])->name('liveness');
    Route::post('/restart', [HealthCheckController::class, 'markRestart'])->name('restart');
});

/*
|--------------------------------------------------------------------------
| Main Dashboard Route - Role-based Redirect
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| User Routes (Students/Parents)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'user.role'])->prefix('user')->name('user.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [UserDashboardController::class, 'getDashboardData'])->name('dashboard.data');

    // Data Completion Routes
    Route::prefix('data')->name('data.')->group(function () {
        Route::get('/', [DataController::class, 'index'])->name('index');

        // Student Data
        Route::get('/student', [DataController::class, 'student'])->name('student');
        Route::post('/student', [DataController::class, 'storeStudent'])->name('student.store');

        // Parent Data
        Route::get('/parent', [DataController::class, 'parent'])->name('parent');
        Route::post('/parent', [DataController::class, 'storeParent'])->name('parent.store');

        // Academic Data
        Route::get('/academic', [DataController::class, 'academic'])->name('academic');
        Route::post('/academic', [DataController::class, 'storeAcademic'])->name('academic.store');

        // Health Data
        Route::get('/health', [DataController::class, 'health'])->name('health');
        Route::post('/health', [DataController::class, 'storeHealth'])->name('health.store');

        // Documents
        Route::get('/documents', [DataController::class, 'documents'])->name('documents');
        Route::post('/documents', [DataController::class, 'storeDocuments'])->name('documents.store');
        Route::delete('/documents/{id}', [DataController::class, 'destroyDocument'])->name('documents.destroy');

        // Achievements
        Route::get('/achievements', [DataController::class, 'achievements'])->name('achievements');
        Route::post('/achievements', [DataController::class, 'storeAchievements'])->name('achievements.store');
        Route::delete('/achievements/{id}', [DataController::class, 'destroyAchievement'])->name('achievements.destroy');

        // Review & Submit
        Route::get('/review', [DataController::class, 'review'])->name('review');
        Route::post('/submit', [DataController::class, 'submit'])->name('submit');
    });

    // User Payment Routes
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::post('/create-invoice', [PaymentController::class, 'createInvoice'])->name('create-invoice');
        Route::post('/validate-discount', [PaymentController::class, 'validateDiscount'])->name('validate-discount');
        Route::get('/success', [PaymentController::class, 'success'])->name('success');
        Route::get('/failed', [PaymentController::class, 'failed'])->name('failed');
        Route::post('/cleanup-expired', [PaymentController::class, 'cleanupAllExpiredPayments'])->name('cleanup-expired');
    });

    // User Transaction Routes
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [PaymentController::class, 'transactions'])->name('index');
        Route::get('/{id}', [PaymentController::class, 'transactionDetail'])->name('show');
    });

});

/*
|--------------------------------------------------------------------------
| Shared Routes (Both Admin & User)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Secure File Routes
    Route::get('/file/download/{path}', [FileController::class, 'download'])->name('file.download');
    Route::get('/file/info/{path}', [FileController::class, 'info'])->name('file.info');
});

/*
|--------------------------------------------------------------------------
| Include Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

require __DIR__.'/auth.php';

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
