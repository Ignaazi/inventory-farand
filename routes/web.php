<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SparepartRequestController; 
use Illuminate\Support\Facades\Route;
use App\Models\SparepartRequest; // Import Model untuk API stats

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

    // 4. Request Sparepart Routes (Untuk User Biasa)
    Route::prefix('requests')->group(function () {
        Route::get('/in', [SparepartRequestController::class, 'createIn'])->name('requests.in');
        Route::get('/out', [SparepartRequestController::class, 'createOut'])->name('requests.out');
        Route::post('/store', [SparepartRequestController::class, 'store'])->name('requests.store');
        Route::get('/history', [SparepartRequestController::class, 'history'])->name('requests.history');
    });

    // 5. Approval Sparepart Routes (Untuk Admin/Approver)
    Route::prefix('approvals')->group(function () {
        Route::get('/in', [SparepartRequestController::class, 'indexIn'])->name('approvals.in');
        Route::get('/out', [SparepartRequestController::class, 'indexOut'])->name('approvals.out');
        
        // --- ADDED: Route untuk melihat History yang sudah di Approve/Reject ---
        Route::get('/history', [SparepartRequestController::class, 'approvalHistory'])->name('approvals.history');
        
        Route::post('/{id}/process', [SparepartRequestController::class, 'process'])->name('approvals.process');
    });

    // 6. Cleaning & Monitoring
    Route::get('/cleaning', function() { return view('cleaning.index'); })->name('cleaning.index');
    Route::get('/monitoring', function() { return view('monitoring.index'); })->name('monitoring.index');

    // Route lines
    Route::post('/lines', [SparepartController::class, 'storeLine'])->name('lines.store');

    // --- API UNTUK INBOX SIDEBAR REALTIME ---
Route::get('/api/sidebar-stats', function () {
    // Pastikan status 'pending' atau 'waiting_approval' sesuai dengan yang ada di database kamu
    return [
        'reqIn'  => \App\Models\SparepartRequest::where('type', 'in')->where('status', 'pending')->count(),
        'reqOut' => \App\Models\SparepartRequest::where('type', 'out')->where('status', 'pending')->count(),
        'appIn'  => \App\Models\SparepartRequest::where('type', 'in')->where('status', 'pending')->count(), 
        'appOut' => \App\Models\SparepartRequest::where('type', 'out')->where('status', 'pending')->count(),
    ];
});
});

require __DIR__.'/auth.php';