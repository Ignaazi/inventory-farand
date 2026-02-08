<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat akun Admin default
        User::create([
            'name'     => 'Super Admin',
            'nik'      => 'admin', // NIK untuk login
            'email'    => 'admin@sparepart.sys', // Tetap isi buat formalitas
            'role'     => 'admin',
            'password' => Hash::make('admin123'), // Password login
        ]);

        // Opsional: Tambah satu akun Operator buat ngetes beda Role
        User::create([
            'name'     => 'Operator Produksi',
            'nik'      => 'user',
            'role'     => 'operator',
            'password' => Hash::make('user123'),
        ]);
    }
}