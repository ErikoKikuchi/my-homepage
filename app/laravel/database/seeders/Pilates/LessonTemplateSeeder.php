<?php

namespace Database\Seeders\Pilates;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pilates\LessonTemplate;

class LessonTemplateSeeder extends Seeder
{
    protected $connection = 'client_db';
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LessonTemplate::create([
            'start_time' => '09:00:00',
            'end_time'   => '10:00:00',
            'is_active'  => true,
        ]);
        LessonTemplate::create([
            'start_time' => '13:00:00',
            'end_time'   => '14:00:00',
            'is_active'  => true,
        ]);
        LessonTemplate::create([
            'start_time' => '14:00:00',
            'end_time'   => '15:00:00',
            'is_active'  => true,
        ]);
    }
}
