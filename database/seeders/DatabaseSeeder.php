<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'nama' => 'Administrator',
            'email' => 'admin@pencaksilat.com',
            'password' => Hash::make('password'),
            'role' => 'pendaftaran',
        ]);

        Admin::create([
            'nama' => 'Admin Pertandingan',
            'email' => 'pertandingan@pencaksilat.com',
            'password' => Hash::make('password'),
            'role' => 'pertandingan',
        ]);
    }
}