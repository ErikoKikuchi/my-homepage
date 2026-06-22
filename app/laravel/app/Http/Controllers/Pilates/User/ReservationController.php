<?php

namespace App\Http\Controllers\Pilates\User;

use App\Http\Controllers\Controller;
use App\Models\Pilates\LessonSlot;
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
        $date = $request->query('date');
        $time = $request->query('time');

        $slot = LessonSlot::where('date', $date)
        ->whereHas('lessonTemplate', fn($q) => $q->whereTime('start_time', $time))
        ->firstOrFail();

        return view('pilates.guest.reservation-detail', [
            'date' => $date,
            'time'=>$time,
            'venueNote' => $slot->venueNote(),
        ]);
    }
    public function store(StoreReservationRequest $request)
    {
        $reservationData = $request->validated();
        $user = auth('web')->user();

        DB::transaction(function() use ($reservationData, $user, $request) {
            $slots = LessonSlot::where('date', $reservationData['date'])
            ->whereHas('lessonTemplate', function($q) use ($reservationData) {
                $q->whereTime('start_time', $reservationData['time']);
            })->lockForUpdate() ->firstOrFail();

            // そのスロットにすでに予約があるかチェック
            $alreadyReserved = $slots->reservations()
                ->whereNotIn('status', ['canceled'])
                ->exists();
            
            if ($alreadyReserved) {
                throw new \Exception('このスロットはすでに予約済みです');
            }

            $slots->reservations()->create([
                'user_id'          => $user->id,
                'participants'=>$reservationData['participants'],
                'participants_name'=>$reservationData['participants_name'] ,
                'note'=>$reservationData['note'] ,
                'status'=>'waiting_venue',
            ]);

        });

        if ($request->expectsJson()) {
            session()->flash(
                'message',
                "予約申請を受け付けました。\n施設を確保後、LINEにて予約確定のご連絡をいたします。\n通常1〜2日以内にご連絡いたします。"
            );
        
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()->route('pilates.mypage');
    }
}
