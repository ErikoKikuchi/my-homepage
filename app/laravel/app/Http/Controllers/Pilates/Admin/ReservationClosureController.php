<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pilates\LessonSlot;
use App\Models\Pilates\ReservationClosure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationClosureController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:'.now()->toDateString()],
            'period' => ['required', 'in:all_day,morning,afternoon'],
        ], [
            'date.after_or_equal' => '今日以降の日付を選択してください。',
        ], [
            'date' => '日付',
            'period' => '時間帯',
        ]);

        DB::connection('client_db')->transaction(function () use ($data) {
            // 予約申請と同じ枠をロックする。
            LessonSlot::where('date', $data['date'])->lockForUpdate()->get();
            ReservationClosure::upsert([$data], ['date'], ['period']);
        }, 3);

        return back()->with('message', '受付不可を設定しました。');
    }

    public function destroy(ReservationClosure $closure)
    {
        $closure->delete();

        return back()->with('message', '受付不可を解除しました。');
    }
}
