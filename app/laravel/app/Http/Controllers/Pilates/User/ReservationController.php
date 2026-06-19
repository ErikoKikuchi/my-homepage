<?php

namespace App\Http\Controllers\Pilates\User;

use App\Http\Controllers\Controller;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\Location;
use Illuminate\Http\Request;
use App\Http\Requests\Pilates\User\StoreReservationRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request){
        return view('pilates.user.mypage');
    }

    public function create(Request $request)
    {
        $date = $request->query('date');
        $time = $request->query('time');
        $isWednesday = Carbon::parse($date)->dayOfWeek === 3;
        $dayOfWeek = Carbon::parse($date)->isoFormat('ddd');
        

        if ($isWednesday) {
            $locations = Location::where('name', 'beauty Ruby')->get();
        } else {
            $locations = Location::where('name', '!=', 'beauty Ruby')->get();
        }

        return view('pilates.guest.reservation-detail', [
            'date' => $date,
            'locations' => $locations,
            'dayOfWeek'=>$dayOfWeek,
            'time'=>$time,
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
                ->whereNotIn('status', ['cancelled'])
                ->exists();
            
            if ($alreadyReserved) {
                throw new \Exception('このスロットはすでに予約済みです');
            }

            $reservationInfo=$slots->reservations()->create([
                'user_id'          => $user->id,
                'participants'=>$reservationData['participants'],
                'participants_name'=>$reservationData['participants_name'] ,
                'note'=>$reservationData['note'] ,
                'status'=>'waiting_venue',
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
