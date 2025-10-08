<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin LSP',
            'email' => 'admin@lsp.com',
            'password' => \Hash::make('password'),
            'role' => 'admin',
            'nama_lengkap' => 'Admin LSP',
            'nim' => null,
            'program_studi' => null,
            'fakultas' => null,
            'no_telepon' => '081234567890',
            'alamat' => 'Jl. Admin No. 1',
        ]);
    }
}
