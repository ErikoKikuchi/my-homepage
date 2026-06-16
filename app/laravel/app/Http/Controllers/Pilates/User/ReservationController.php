<?php

namespace App\Http\Controllers\Pilates\User;

use App\Http\Controllers\Controller;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\Reservation;
use Illuminate\Http\Request;
use App\Http\Requests\Pilates\User\StoreReservationRequest;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index(Request $request){
        return view('pilates.user.mypage');
    }

    public function create(Request $request)
    {
        $date = $request->input('date');
        $dayOfWeek = \Carbon\Carbon::parse($date)->dayOfWeek;
        $times = ['10:00', '11:00', '12:00'];

        return view('pilates.guest.reservation-detail', [
            'date' => $date,
            'isWednesday' => $dayOfWeek === 3,
            'times'  =>$times,
        ]);
    }
    public function store(StoreReservationRequest $request)
    {
        $reservationData = $request->validated();
        $user = auth('web')->user();

        DB::transaction(function() use ($reservationData, $user, $request) {
            $slot = LessonSlot::whereDate('date', $reservationData['date'])
            ->whereHas('lessonTemplate', function($q) use ($reservationData) {
                $q->where('start_time', $reservationData['time']);
            })->lockForUpdate() ->firstOrFail();

            // そのスロットにすでに予約があるかチェック
            $alreadyReserved = $slot->reservations()
                ->whereNotIn('status', ['cancelled'])
                ->exists();
            
            if ($alreadyReserved) {
                throw new \Exception('このスロットはすでに予約済みです');
            }

            $reservationInfo=Reservation::create([
                'lesson_slot_id'   => $slot->id,
                'user_id'          => $user->id,
                'participants'=>$reservationData['participants'],
                'participants_name'=>$reservationData['participants_name'] ,
                'note'=>$reservationData['note'] ,
                'status'=>'pending',
            ]);

            $locations = [$reservationData['first_place'] => ['priority' => 1]];
                if (!empty($reservationData['second_place'])) {
                    $locations[$reservationData['second_place']] = ['priority' => 2];
                }
            $reservationInfo->location()->attach($locations);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => '予約が完了しました！'
            ]);
        }

        return redirect()->route('pilates.mypage');
    }
}
