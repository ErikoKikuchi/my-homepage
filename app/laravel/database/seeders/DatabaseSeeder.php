<?php

namespace Database\Seeders;

use Database\Seeders\Pilates\PilatesDatabaseSeeder;
use Database\Seeders\ThinkMotion\ThinkMotionDatabaseSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ThinkMotionDatabaseSeeder::class,
            PilatesDatabaseSeeder::class,
        ]);
    }
}
