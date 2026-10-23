<?php

namespace Database\Seeders\ThinkMotion;

use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => 'pilates@example.com',
            'password' => Hash::make('password123456'),
            'profile_completed' => false,
            'bookshelf_public' => true,
            'is_pilates_user' => true,
            'is_client' => false,
            'is_medical' => false,
        ]);
        User::create([
            'email' => 'pilates-mania@example.com',
            'password' => Hash::make('password123456'),
            'profile_completed' => false,
            'bookshelf_public' => true,
            'is_pilates_user' => true,
            'is_client' => false,
            'is_medical' => false,
        ]);
        User::create([
            'email' => 'pilatespt@example.com',
            'password' => Hash::make('password123456'),
            'profile_completed' => false,
            'bookshelf_public' => true,
            'is_pilates_user' => true,
            'is_client' => false,
            'is_medical' => true,
        ]);
        User::create([
            'email' => 'testpt@example.com',
            'password' => Hash::make('password123456'),
            'profile_completed' => false,
            'bookshelf_public' => true,
            'is_pilates_user' => false,
            'is_client' => false,
            'is_medical' => true,
        ]);
    }
}
