<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Devyy',
            'email' => 'intership25@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('1nt3rsh1p25'),
            'role' => 'admin',
            'dinas' => 'Magang',
        ]);
        \App\Models\User::create([
            'name' => 'Biro Hukum',
            'email' => 'birohukum@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('b1r0hukum!'),
            'role' => 'admin',
            'dinas' => 'Biro Hukum',
        ]);
        \App\Models\User::create([
            'name' => 'Kepala Biro Hukum',
            'email' => 'kepalabirohukum@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('k3p4lb1r0hukum!'),
            'role' => 'user',
            'dinas' => 'Biro Hukum',
        ]);
    }
} 