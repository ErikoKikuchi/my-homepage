<?php

namespace App\Http\Controllers\Pilates\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function index(Request $request){
        $user=auth('web')->user();
        $client=$user->client;
        //次回予約用
        $nextReservation=$user->reservations()->active()->upComing()->get()->sortBy(fn($reservation) => $reservation->lessonSlot->date)->first();
        $nextReservationInfo=null;
        if($nextReservation){
            $nextReservationInfo=[
                'date' =>$nextReservation->lessonSlot->date->format('Y年m月d日'),
                'location'=>$nextReservation->status==='waiting_venue'?'施設調整中':$nextReservation->reservations()->location->name,
            ];
        };
        //回数券残数
        $remainingTicketCounts=$client?->remainingGuestPassCount()?? 0;

        //LINE登録
        $notLineLinkedClient=$user->clients()->notLineLinked()->get();
        //予約履歴
        $upcomingReservations=$user->reservations()->upComing()->get()->sortBy(fn($reservation) => $reservation->lessonSlot->date)->values()->map(fn ($reservation) => [
            'uuid' => $reservation->id,
            'date' => $reservation->lessonSlot->date->format('Y年m月d日'),
            'location' => $reservation->status === 'waiting_venue'
                ? '施設調整中'
                : $reservation->lessonSlot->location?->name,
        ])
        ->toArray();

        return view('pages.pilates.user.mypage',[
            'notLineLinkedClient' => $notLineLinkedClient,
            'nextReservationInfo'=> $nextReservationInfo,
            'remainingTicketCounts'=>$remainingTicketCounts,
            'upcomingReservations'=>$upcomingReservations,
        ]);
    }
}
