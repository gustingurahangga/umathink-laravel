<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['username' => 'admin', 'namalengkap' => 'contohnamalengkap', 'email' => 'admin@gmail.com', 'role' => 'admin', 'status' => 'active', 'password' => 'admin']);
        User::create(['username' => 'staff', 'namalengkap' => 'contohnamalengkap', 'email' => 'staff@gmail.com', 'role' => 'staff', 'status' => 'active', 'password' => 'staff']);
        User::create(['username' => 'customer', 'namalengkap' => 'contohnamalengkap', 'email' => 'customer@gmail.com', 'role' => 'customer', 'status' => 'active', 'password' => 'customer']);
    }
}
