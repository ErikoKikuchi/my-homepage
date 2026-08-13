<?php

namespace Database\Seeders\ThinkMotion;

use Illuminate\Database\Seeder;
use App\Models\Auth\Admin;
use App\Models\Auth\Section;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pilatesSection = Section::on('mysql')->firstOrCreate(
            ['key'=>'pilates'],
            ['label'=>'ピラティス']
        );
        $thinkmotionSection= Section::on('mysql')->firstOrCreate(
            ['key'=>'thinkmotion'],
            ['label'=>'ThinkMotion']
        );

        $thinkmotionAdmin = Admin::on('mysql')->create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123456'),
        ]);
        $thinkmotionAdmin->sections()->attach($thinkmotionSection->id);

        $pilatesAdmin = Admin::on('mysql')->create([
            'username' => 'admin-pilates',
            'email' => 'admin-pilates@example.com',
            'password' => Hash::make('password123456'),
        ]);
        $pilatesAdmin->sections()->attach($pilatesSection->id);
    }
}
