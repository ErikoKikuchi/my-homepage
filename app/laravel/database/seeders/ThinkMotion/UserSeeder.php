<?php

namespace Database\Seeders\ThinkMotion;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'ピラティス花子',
            'email'=>'pilates@example.com',
            'password'=>Hash::make('password123456'),
            'profile_completed'=>false,
            'bookshelf_public'=>true,
            'is_client'=>true,
            'is_medical'=>false,
        ]);
        User::create([
            'name'=>'ピラティス療法士',
            'email'=>'pilatespt@example.com',
            'password'=>Hash::make('password123456'),
            'profile_completed'=>false,
            'bookshelf_public'=>true,
            'is_client'=>true,
            'is_medical'=>true,
        ]);
        User::create([
            'name'=>'テスト療法士',
            'email'=>'testpt@example.com',
            'password'=>Hash::make('password123456'),
            'profile_completed'=>false,
            'bookshelf_public'=>true,
            'is_client'=>false,
            'is_medical'=>true,
        ]);
    }
}
