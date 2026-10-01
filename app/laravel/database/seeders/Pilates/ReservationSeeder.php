<?php

namespace Database\Seeders\Pilates;

use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\Reservation;
use App\Models\Auth\User;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'pilates@example.com')->first();
        $lessonSlot=LessonSlot::inRandomOrder()->first();


        Reservation::create([
            'lesson_slot_id' => $lessonSlot->id,
            'user_id' =>  $user->id,
            'participants' => 1,
            'status' => 'waiting_venue',
        ]);
    }
}
