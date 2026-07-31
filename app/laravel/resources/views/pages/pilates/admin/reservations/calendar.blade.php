@extends ('layouts.pilates')
@vite (['resources/js/pages/pilates/admin-reservation.js'])

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        予約確認カレンダー・代理予約
    </h1>
@endsection

@section ('pilates-content')
    <div id="admin-calendar" data-week="{{ $weekStart }}">
        <div
            class="calendar-controls flex gap-20 justify-center items-center mb-2"
        >
            <button id="prev-week-btn" class="btn btn-outline">前週</button>
            <button id="next-week-btn" class="btn btn-outline">翌週</button>
        </div>
        <div id="admin-calendar-form">
            <div
                class="weekdays grid grid-cols-7 p-2 text-center text-xl border border-forest-dark bg-forest-dark text-white font-bold"
            >
                <div>日</div>
                <div>月</div>
                <div>火</div>
                <div>水</div>
                <div>木</div>
                <div>金</div>
                <div>土</div>
            </div>
            <div class="dates grid grid-cols-7 gap-1 auto-rows-[minmax(0,3fr)]">
                <div><!--JS側でfetchして描画--></div>
            </div>
        </div>
    </div>
@endsection
