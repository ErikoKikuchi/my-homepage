@extends ('layouts.pilates')

@vite (['resources/js/pages/pilates/pilates-reservation.js'])

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        ピラティス空き状況確認カレンダー
    </h1>
@endsection

@section ('pilates-content')
    <div id="calendar" data-month="{{ $month }}">
        <div class="calendar-controls flex gap-10 flex-wrap justify-center">
            <button id="prev-month-btn" class="btn btn-outline">前月</button>
            <button id="next-month-btn" class="btn btn-outline">翌月</button>
        </div>
        <div id="calendar-form">
            <h2 class="font-light text-xl text-forest-dark p-4">
                {{ $month }}月の空き状況
            </h2>
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
                @foreach ($cells as $cell)
                    <div></div>
                @endforeach
            </div>
        </div>
        <div class="explanation">
            <p class="text-xs text-muted pb-1 pl-4">〇：空きあり</p>
            <p class="text-xs text-muted pb-1 pl-4">✕：空き無し</p>
        </div>
    </div>
    <div class="time-select hidden">
        <h2 class="font-light text-xl text-forest-dark p-4"></h2>
        <div class="flex flex-col items-start">
            <!--JSで描画-->
        </div>
        <p class="bg-forest/20 border border-forest text-sm">＊レッスンは60分間です</p>
    </div>
@endsection
