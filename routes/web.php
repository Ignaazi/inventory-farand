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

    // 2. User Management Routes (Wajib Ada)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');

    // 3. Inventory & Sparepart Routes
    Route::get('/spareparts', [SparepartController::class, 'index'])->name('spareparts.index');
    Route::post('/spareparts', [SparepartController::class, 'store'])->name('spareparts.store');
    Route::post('/lines', [SparepartController::class, 'storeLine'])->name('lines.store');
    Route::delete('/spareparts/{id}', [SparepartController::class, 'destroy'])->name('spareparts.destroy');
});

require __DIR__.'/auth.php';