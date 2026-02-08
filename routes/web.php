<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SparepartRequestController; // Pastikan nanti buat controller ini
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

    // 3. Inventory & Sparepart (Stock) Routes
    Route::get('/spareparts', [SparepartController::class, 'index'])->name('spareparts.index');
    Route::post('/spareparts', [SparepartController::class, 'store'])->name('spareparts.store');
    Route::put('/spareparts/{id}', [SparepartController::class, 'update'])->name('spareparts.update');
    Route::delete('/spareparts/{id}', [SparepartController::class, 'destroy'])->name('spareparts.destroy');

    // 4. Request Sparepart Routes
    Route::prefix('requests')->group(function () {
        Route::get('/in', [SparepartRequestController::class, 'createIn'])->name('requests.in');
        Route::get('/out', [SparepartRequestController::class, 'createOut'])->name('requests.out');
        Route::post('/store', [SparepartRequestController::class, 'store'])->name('requests.store');
        Route::get('/history', [SparepartRequestController::class, 'history'])->name('requests.history');
    });

    // 5. Approval Sparepart Routes
    Route::prefix('approvals')->group(function () {
        Route::get('/in', [SparepartRequestController::class, 'indexIn'])->name('approvals.in');
        Route::get('/out', [SparepartRequestController::class, 'indexOut'])->name('approvals.out');
        Route::post('/{id}/process', [SparepartRequestController::class, 'process'])->name('approvals.process');
    });

    // 6. Cleaning & Monitoring (Placeholder for next step)
    Route::get('/cleaning', function() { return view('cleaning.index'); })->name('cleaning.index');
    Route::get('/monitoring', function() { return view('monitoring.index'); })->name('monitoring.index');

    // Route lines (Opsional/Legacy)
    Route::post('/lines', [SparepartController::class, 'storeLine'])->name('lines.store');
});

require __DIR__.'/auth.php';