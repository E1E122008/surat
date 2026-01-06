<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Menggunakan updateOrCreate agar bisa update jika user sudah ada
        \App\Models\User::updateOrCreate(
            ['email' => 'intership25@gmail.com'],
            [
                'name' => 'Intership',
                'password' => \Illuminate\Support\Facades\Hash::make('1nt3rsh1p25'),
                'role' => 'admin',
                'dinas' => 'Magang',
            ]
        );
        
        \App\Models\User::updateOrCreate(
            ['email' => 'birohukum@gmail.com'],
            [
                'name' => 'Biro Hukum',
                'password' => \Illuminate\Support\Facades\Hash::make('b1r0hukum!'),
                'role' => 'admin',
                'dinas' => 'Biro Hukum',
            ]
        );
        
        \App\Models\User::updateOrCreate(
            ['email' => 'kepalabirohukum@gmail.com'],
            [
                'name' => 'Kepala Biro Hukum',
                'password' => \Illuminate\Support\Facades\Hash::make('k3p4lb1r0hukum!'),
                'role' => 'user',
                'dinas' => 'Biro Hukum',
            ]
        );
    }
} 