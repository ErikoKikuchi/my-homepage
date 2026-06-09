<?php

namespace Database\Seeders\Pilates;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PilatesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(LocationSeeder::class);
        $this->call(LessonTemplateSeeder::class);
        $this->call(LessonSlotSeeder::class);
    }
}
