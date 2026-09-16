<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role; // Pastikan model Role Anda bernama Role
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat 3 Role Utama
        $adminRole = Role::create(['name' => 'Admin']);
        $operatorRole = Role::create(['name' => 'Operator']);
        $pegawaiRole = Role::create(['name' => 'Pegawai']);

        // 2. Buat Akun Admin Super
        User::create([
            'name' => 'Administrator JDIH',
            'email' => 'admin@kearsipan.com',
            'password' => Hash::make('password123'), // Password default
            'role_id' => $adminRole->id,
        ]);

        // (Opsional) Buat 1 Akun Operator untuk testing
        User::create([
            'name' => 'Operator Instansi',
            'email' => 'operator@kearsipan.com',
            'password' => Hash::make('password123'),
            'role_id' => $operatorRole->id,
        ]);
    }
}