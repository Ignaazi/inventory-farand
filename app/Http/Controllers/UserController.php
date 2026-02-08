<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user
     */
    public function index()
    {
        $users = User::orderBy('role', 'asc')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Menyimpan user baru (Create)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nik'      => 'required|string|unique:users,nik',
            'role'     => 'required|in:admin,leader,operator',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'nik'      => $request->nik,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Update data user (Termasuk NIK dan Role)
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik'  => ['required', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,leader,operator',
        ]);

        $user->name = $request->name;
        $user->nik = $request->nik;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
    }

    /**
     * Menghapus user (Delete)
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }

    /**
     * Fungsi khusus update role (Tetap dipertahankan sesuai route lama)
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,leader,operator',
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Role berhasil diperbarui!');
    }
}