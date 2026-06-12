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
    <div
        id="calendar"
        data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
        data-login-url="{{ route('login') }}"
    >
        <div class="calendar-controls flex gap-10 flex-wrap justify-center">
            <button id="prev-month-btn" class="btn btn-outline">前月</button>
            <button id="next-month-btn" class="btn btn-outline">翌月</button>
        </div>
        <div id="this-month-calendar">
            <h2 class="font-light text-xl text-forest-dark p-4">
                今月の空き状況
            </h2>
            <div class="weekdays grid grid-cols-7 p-2 text-center">
                <div>日</div>
                <div>月</div>
                <div>火</div>
                <div>水</div>
                <div>木</div>
                <div>金</div>
                <div>土</div>
            </div>
            <div class="dates grid grid-cols-7 gap-1 auto-rows-[minmax(0,3fr)]">
                @for ($i = 0; $i < 42; $i++)
                    <div
                        class="date border border-accent items-center text-center"
                    >
                        @if ($i - $dayOfWeek<0)
                            @php 
                                $date = null;
                            @endphp
                        @elseif ($i-$daysInMonth-$dayOfWeek>=0)
                            @php 
                                $date = null;
                            @endphp
                        @else
                            @php 
                                $date = $i-$dayOfWeek+1;
                            @endphp
                        @endif
                        {{ $date ??''}}
                    </div>
                @endfor
            </div>
        </div>
        <div id="next-month-calendar" class="hidden">
            <h2 class="text-xl text-forest-dark p-4">来月の空き状況</h2>
            <div class="weekdays grid grid-cols-7 p-2 text-center">
                <div>日</div>
                <div>月</div>
                <div>火</div>
                <div>水</div>
                <div>木</div>
                <div>金</div>
                <div>土</div>
            </div>
            <div class="dates grid grid-cols-7 gap-1 auto-rows-[minmax(0,3fr)]">
                @for ($i = 0; $i < 42; $i++)
                    <div
                        class="date border border-accent items-center text-center"
                    >
                        @if ($i - $dayOfWeekNext<0)
                            @php 
                                $date = null;
                            @endphp
                        @elseif ($i-$daysInMonthNext-$dayOfWeekNext>=0)
                            @php 
                                $date = null;
                            @endphp
                        @else
                            @php 
                                $date = $i-$dayOfWeekNext+1;
                            @endphp
                        @endif
                        {{ $date ??''}}
                    </div>
                @endfor
            </div>
        </div>
        <div class="m-2 pb-1">
            <div class="m-2 pb-1">
                <p>会員登録せずにピラティス体験希望の方は <a href="http://" class="text-xm text-blue-500 hover:text-blue-700 border-b">こちらへ</a></p>
            </div>
            <p class="text-xs text-muted pb-1 pl-4">＊カレンダー上で空き枠を確認後お申し込みください。</p>
            <p class="text-xs text-muted pb-1 pl-4">＊ユーザー登録されている方が優先となりますのでご了承ください。</p>
        </div>
    </div>

@endsection
