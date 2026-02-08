<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // 1. Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2. User Management Routes
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');

    // 3. Inventory & Sparepart Routes
    Route::get('/spareparts', [SparepartController::class, 'index'])->name('spareparts.index');
    Route::post('/spareparts', [SparepartController::class, 'store'])->name('spareparts.store');
    
    // TAMBAHKAN BARIS INI UNTUK EDIT/UPDATE
    Route::put('/spareparts/{id}', [SparepartController::class, 'update'])->name('spareparts.update');
    
    Route::delete('/spareparts/{id}', [SparepartController::class, 'destroy'])->name('spareparts.destroy');

    // Route lines bisa dihapus kalau memang sudah tidak pakai lines sama sekali
    Route::post('/lines', [SparepartController::class, 'storeLine'])->name('lines.store');
});

require __DIR__.'/auth.php';