<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat Role
        $adminRole = Role::create(['name' => 'Admin']);
        $operatorRole = Role::create(['name' => 'Operator']);
        $pegawaiRole = Role::create(['name' => 'Pegawai']);

        // 2. Membuat 1 Akun Administrator
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@kearsipan.local',
            'password' => Hash::make('password123'), // Password wajib di-hash
            'role_id' => $adminRole->id,
        ]);
    }
}