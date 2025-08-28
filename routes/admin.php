<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PendaftarController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // Bulk Actions untuk Users
    Route::post('users/bulk-action', [UserManagementController::class, 'bulkAction'])->name('users.bulk-action');
    Route::post('users/bulk-delete', [UserManagementController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::post('users/bulk-activate', [UserManagementController::class, 'bulkActivate'])->name('users.bulk-activate');
    Route::post('users/bulk-deactivate', [UserManagementController::class, 'bulkDeactivate'])->name('users.bulk-deactivate');

    // Pendaftar Management (Admin can access all)
    Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar.index');
    Route::get('/pendaftar/{pendaftar}/validasi', [PendaftarController::class, 'validasi'])->name('pendaftar.validasi');
    Route::patch('/pendaftar/{pendaftar}', [PendaftarController::class, 'update'])->name('pendaftar.update');

    // Admin Transactions
    Route::get('/transactions', [PaymentController::class, 'adminTransactions'])->name('transactions.index');
    Route::get('/transactions/{id}', [PaymentController::class, 'adminTransactionDetail'])->name('transactions.show');
    Route::post('/transactions/{id}/confirm', [PaymentController::class, 'confirmPayment'])->name('transactions.confirm');

    Route::post('/test-webhook', [PaymentController::class, 'testWebhook'])->name('payment.test-webhook');
});
