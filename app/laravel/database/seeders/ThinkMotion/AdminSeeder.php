<?php

namespace Database\Seeders\ThinkMotion;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Auth\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::on('mysql')->create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123456'),
        ]);
    }
}
