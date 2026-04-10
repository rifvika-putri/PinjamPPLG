<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin (Tanpa NISN/Kelas yang aneh-aneh)
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'nisn' => null, // Admin gak butuh NISN
            'kelas' => null,
        ]);

        // Akun Petugas (Contoh)
        User::create([
            'name' => 'Petugas',
            'email' => 'petugas@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
            'nisn' => null,
            'kelas' => null,
        ]);
    }
}