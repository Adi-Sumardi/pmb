<?php

use App\Http\Controllers\PendaftarController;
use App\Http\Controllers\ProfileController;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/pendaftaran', [PendaftarController::class, 'store'])->name('pendaftaran.store');

Route::middleware('auth')->group(function () {
    Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar');
    Route::get('/pendaftar/{id}/validasi', [PendaftarController::class, 'validasi'])->name('pendaftar.validasi');
    Route::patch('/pendaftar/{id}', [PendaftarController::class, 'update'])->name('pendaftar.update');
    Route::delete('/pendaftar', [PendaftarController::class, 'destroy'])->name('pendaftar.destroy');
});

require __DIR__.'/auth.php';
