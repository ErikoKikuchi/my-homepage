<?php

namespace Database\Seeders\Pilates;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\LessonTemplate;

class LessonSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $morning = LessonTemplate::where('start_time', '09:00:00')->first();
        $afternoon2 = LessonTemplate::where('start_time', '14:00:00')->first();

        LessonSlot::create([
            'lesson_template_id' => $morning->id,
            'date'               => '2026-06-27',
            'location_id'        => null,
            'is_active'          => true,
        ]);
        LessonSlot::create([
            'lesson_template_id' => $morning->id,
            'date'               => '2026-06-28',
            'location_id'        => null,
            'is_active'          => true,
        ]);
        LessonSlot::create([
            'lesson_template_id' => $morning->id,
            'date'               => '2026-06-28',
            'location_id'        => null,
            'is_active'          => true,
        ]);
        LessonSlot::create([
            'lesson_template_id' => $afternoon2->id,
            'date'               => '2026-06-30',
            'location_id'        => null,
            'is_active'          => true,
        ]);
        LessonSlot::create([
            'lesson_template_id' => $afternoon2->id,
            'date'               => '2026-06-30',
            'location_id'        => null,
            'is_active'          => true,
        ]);
    }
}
