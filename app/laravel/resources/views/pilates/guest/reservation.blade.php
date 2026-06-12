@extends ('layouts.pilates')

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
        <div id="this-month ">
            <h2 class="title">今月の空き状況</h2>
            <div class="weekdays grid grid-cols-7">
                <div>日</div>
                <div>月</div>
                <div>火</div>
                <div>水</div>
                <div>木</div>
                <div>金</div>
                <div>土</div>
            </div>
            <div class="dates grid grid-cols-7">
                @for ($i = 0; $i < 42; $i++)
                    <div class="date">
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
        <div id="next-month">
            <h2 class="title">来月の空き状況</h2>
            <div class="weekdays grid grid-cols-7">
                <div>日</div>
                <div>月</div>
                <div>火</div>
                <div>水</div>
                <div>木</div>
                <div>金</div>
                <div>土</div>
            </div>
            <div class="dates grid grid-cols-7">
                @for ($i = 0; $i < 42; $i++)
                    <div class="date">
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
    </div>

@endsection
