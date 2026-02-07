<?php

namespace App\Http\Controllers;

use App\Models\User; // Import model User
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user
     */
    public function index()
    {
        // Ambil semua data user dari database
        $users = User::all();
        
        // Arahkan ke folder resources/views/users/index.blade.php
        return view('users.index', compact('users'));
    }

    /**
     * Update Role User (Admin/Leader/Operator) 
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,leader,operator',
        ]);

        $user->update([
            'role' => $request->role
        ]);

        return back()->with('success', 'Role berhasil diubah ke ' . $request->role);
    }
}