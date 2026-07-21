<?php

namespace App\Http\Controllers\Pilates\User;

use App\Http\Controllers\Controller;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\Reservation;
use Illuminate\Http\Request;
use App\Http\Requests\Pilates\User\StoreReservationRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Auth\User;


class ReservationController extends Controller
{
    public function index(Request $request){
        return view('pilates.mypage');
    }

    public function create(Request $request)
    {
        $user = auth('web')->user();
        $date = $request->query('date');
        $time = $request->query('time');
        $carbonDate = Carbon::parse($date);
        $carbonTime = Carbon::parse($time);

        $slot = LessonSlot::where('date', $date)
        ->whereHas('lessonTemplate', fn($q) => $q->whereTime('start_time', $time))
        ->firstOrFail();

        return view('pages.pilates.guest.reservation-detail', [
            'date' => $date,
            'time'=>$time,
            'dateFormatted' => $carbonDate->isoFormat('M月D日(ddd)'), // 表示用
            'timeFormatted' => $carbonTime->format('H:i'),   
            'venueNote' => $slot->venueNote(),
            'name'=>$user->name,
        ]);
    }
    public function store(StoreReservationRequest $request)
    {
        $reservationData = $request->validated();
        /** @var \App\Models\Auth\User $user */
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
            if (isset($reservationData['phone']) && $user->phone !== $reservationData['phone']) {
                $user->update(['phone' => $reservationData['phone']]);
            }

            $slots->reservations()->create([
                'user_id'          => $user->id,
                'participants'=>$reservationData['participants'],
                'participants_name'=>$reservationData['participants_name'] ,
                'participants_phone'=>$reservationData['participants_phone'] ,
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
    public function show(Reservation $reservation)
    {
        $user=auth('web')->user();
        $this->authorize('view', $reservation);
        $booking=[
            'participants' => $reservation->participants,
            'date' => $reservation->lessonSlot->date->format('Y年m月d日'),
            'location' => $reservation->status === 'waiting_venue'
                ? '施設調整中'
                : $reservation->lessonSlot->location->name,
            'note'=>$reservation->note,
            'start_time'=>$reservation->lessonSlot->lessonTemplate->start_time,
            'end_time'=>$reservation->lessonSlot->lessonTemplate->end_time,
        ];

        return view('pages.pilates.user.pilates-cancellation',[
            'booking'=>$booking,
            'user'=>$user,
            'reservation'=>$reservation
        ]);
    }

    public function archive(Request $request)
    {
        /** @var User $user */
        $user=auth('web')->user();

        $query = $user->reservations()
            ->past()
            ->with(['lessonSlot', 'location'])
            ->paginate(10);
        $pastReservations= $query->through(fn($reservation) => [
                'uuid' => $reservation->uuid,
                'date'=>$reservation->lessonSlot->date->format('Y年m月d日'),
                'location'=>$reservation->reservations()->location->name,
                'participants' => $reservation->participants,
            ]);

        return view('pages.pilates.user.past-reservation',[
            'pastReservations' => $pastReservations,
        ]);
    }
}
