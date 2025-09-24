<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProgresPendaftaranController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PendaftarController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes (Require Admin Role)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | Admin Dashboard
    |----------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | System Status & Monitoring
    |----------------------------------------------------------------------
    */
    Route::get('/system-status', function () {
        return view('dashboard.system-status');
    })->name('system.status');

    /*
    |----------------------------------------------------------------------
    | User Management
    |----------------------------------------------------------------------
    */
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');

        // Bulk Actions
        Route::post('/bulk-action', [UserManagementController::class, 'bulkAction'])->name('bulk-action');
        Route::post('/bulk-delete', [UserManagementController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/bulk-activate', [UserManagementController::class, 'bulkActivate'])->name('bulk-activate');
        Route::post('/bulk-deactivate', [UserManagementController::class, 'bulkDeactivate'])->name('bulk-deactivate');
    });

    /*
    |----------------------------------------------------------------------
    | Pendaftar Management
    |----------------------------------------------------------------------
    */
    Route::prefix('pendaftar')->name('pendaftar.')->group(function () {
        Route::get('/', [PendaftarController::class, 'index'])->name('index');
        Route::get('/{pendaftar}/validasi', [PendaftarController::class, 'validasi'])->name('validasi');
        Route::patch('/{pendaftar}', [PendaftarController::class, 'update'])->name('update');
        Route::delete('/', [PendaftarController::class, 'destroy'])->name('destroy');

        // Bulk Actions - Move specific routes before wildcard routes
        Route::post('/bulk-verify', [PendaftarController::class, 'bulkVerify'])->name('bulk-verify');
        Route::delete('/bulk-delete', [PendaftarController::class, 'bulkDelete'])->name('bulk-delete');
        Route::patch('/bulk-update-status', [PendaftarController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
        Route::patch('/bulk-update-student-status', [PendaftarController::class, 'bulkUpdateStudentStatus'])->name('bulk-update-student-status');
    });

    /*
    |----------------------------------------------------------------------
    | Data Siswa Management (Accepted Students)
    |----------------------------------------------------------------------
    */
    Route::prefix('data-siswa')->name('data-siswa.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DataSiswaController::class, 'index'])->name('index');
        Route::patch('/{pendaftar}/update-status', [\App\Http\Controllers\Admin\DataSiswaController::class, 'updateStatus'])->name('update-status');
        Route::get('/{pendaftar}/detail', [\App\Http\Controllers\Admin\DataSiswaController::class, 'show'])->name('detail');
        Route::patch('/bulk-update-status', [\App\Http\Controllers\Admin\DataSiswaController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
    });

    /*
    |----------------------------------------------------------------------
    | Admin Payment & Transaction Management
    |----------------------------------------------------------------------
    */
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'adminIndex'])->name('index');
        Route::get('/debug', [PaymentController::class, 'debugPaymentMode'])->name('debug');
        Route::get('/debug/methods', [PaymentController::class, 'debugPaymentMethods'])->name('debug.methods');
        Route::post('/test-webhook', [PaymentController::class, 'testWebhook'])->name('test-webhook');
    });

    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [PaymentController::class, 'adminTransactions'])->name('index');

        // Export & Reporting - MUST be before /{id} route to avoid conflicts
        Route::get('/export', [TransactionController::class, 'export'])->name('export');
        Route::get('/export/pdf', [TransactionController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/print', [TransactionController::class, 'printView'])->name('print');

        // Detail routes - MUST be after specific routes
        Route::get('/{id}', [PaymentController::class, 'adminTransactionDetail'])->name('show');
        Route::post('/{id}/confirm', [PaymentController::class, 'confirmPayment'])->name('confirm');
    });

    /*
    |----------------------------------------------------------------------
    | System Logs & Monitoring
    |----------------------------------------------------------------------
    */
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [LogController::class, 'viewLogs'])->name('view');
        Route::post('/clear', [LogController::class, 'clearLogs'])->name('clear');
        Route::get('/download', [LogController::class, 'downloadLogs'])->name('download');
        Route::get('/stream', [LogController::class, 'streamLogs'])->name('stream');
    });

    /*
    |----------------------------------------------------------------------
    | Progres Pendaftaran
    |----------------------------------------------------------------------
    */
    Route::get('/progres-pendaftaran', [\App\Http\Controllers\Admin\ProgresPendaftaranController::class, 'index'])->name('progres-pendaftaran.index');
    Route::put('/progres-pendaftaran/{id}/status', [\App\Http\Controllers\Admin\ProgresPendaftaranController::class, 'updateStudentStatus'])->name('progres-pendaftaran.update-status');
    Route::get('/progres-pendaftaran/{id}/status-modal', [\App\Http\Controllers\Admin\ProgresPendaftaranController::class, 'getStudentStatusModal'])->name('progres-pendaftaran.status-modal');
    Route::patch('/progres-pendaftaran/bulk-update-overall-status', [\App\Http\Controllers\Admin\ProgresPendaftaranController::class, 'bulkUpdateOverallStatus'])->name('progres-pendaftaran.bulk-update-overall-status');

    /*
    |----------------------------------------------------------------------
    | Admin Settings Management
    |----------------------------------------------------------------------
    */
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('index');

        // Discount Management
        Route::prefix('discounts')->name('discounts.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DiscountController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\DiscountController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\DiscountController::class, 'store'])->name('store');
            Route::get('/{discount}/edit', [\App\Http\Controllers\Admin\DiscountController::class, 'edit'])->name('edit');
            Route::put('/{discount}', [\App\Http\Controllers\Admin\DiscountController::class, 'update'])->name('update');
            Route::delete('/{discount}', [\App\Http\Controllers\Admin\DiscountController::class, 'destroy'])->name('destroy');
        });

        // SPP Management
        Route::prefix('spp')->name('spp.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SppController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\SppController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\SppController::class, 'store'])->name('store');
            Route::get('/{spp}/edit', [\App\Http\Controllers\Admin\SppController::class, 'edit'])->name('edit');
            Route::put('/{spp}', [\App\Http\Controllers\Admin\SppController::class, 'update'])->name('update');
            Route::delete('/{spp}', [\App\Http\Controllers\Admin\SppController::class, 'destroy'])->name('destroy');
        });

        // Uang Pangkal Management
        Route::prefix('uang-pangkal')->name('uang-pangkal.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\UangPangkalController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\UangPangkalController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\UangPangkalController::class, 'store'])->name('store');
            Route::get('/{uangPangkal}/edit', [\App\Http\Controllers\Admin\UangPangkalController::class, 'edit'])->name('edit');
            Route::put('/{uangPangkal}', [\App\Http\Controllers\Admin\UangPangkalController::class, 'update'])->name('update');
            Route::delete('/{uangPangkal}', [\App\Http\Controllers\Admin\UangPangkalController::class, 'destroy'])->name('destroy');
        });

        // Multi Payment Management
        Route::prefix('multi-payments')->name('multi-payments.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MultiPaymentController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\MultiPaymentController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\MultiPaymentController::class, 'store'])->name('store');
            Route::get('/{multiPayment}/edit', [\App\Http\Controllers\Admin\MultiPaymentController::class, 'edit'])->name('edit');
            Route::put('/{multiPayment}', [\App\Http\Controllers\Admin\MultiPaymentController::class, 'update'])->name('update');
            Route::delete('/{multiPayment}', [\App\Http\Controllers\Admin\MultiPaymentController::class, 'destroy'])->name('destroy');
        });

        // Installment Settings
        Route::prefix('installments')->name('installments.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\InstallmentController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\InstallmentController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\InstallmentController::class, 'store'])->name('store');
            Route::get('/{installment}/edit', [\App\Http\Controllers\Admin\InstallmentController::class, 'edit'])->name('edit');
            Route::put('/{installment}', [\App\Http\Controllers\Admin\InstallmentController::class, 'update'])->name('update');
            Route::delete('/{installment}', [\App\Http\Controllers\Admin\InstallmentController::class, 'destroy'])->name('destroy');
        });

        // SPP Bulk Payment Settings
        Route::prefix('spp-bulk')->name('spp-bulk.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\SppBulkController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\SppBulkController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\SppBulkController::class, 'store'])->name('store');
            Route::get('/{sppBulkSetting}/edit', [\App\Http\Controllers\Admin\SppBulkController::class, 'edit'])->name('edit');
            Route::put('/{sppBulkSetting}', [\App\Http\Controllers\Admin\SppBulkController::class, 'update'])->name('update');
            Route::delete('/{sppBulkSetting}', [\App\Http\Controllers\Admin\SppBulkController::class, 'destroy'])->name('destroy');
        });
    });

});
