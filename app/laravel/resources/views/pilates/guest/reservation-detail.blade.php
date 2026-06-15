@extends ('layouts.pilates')

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        ピラティス日時選択画面
    </h1>
@endsection

@section ('pilates-content')
    <div class="reservation-form">
        <div class="">{{ $date }}の予約</div>
        <div class="">時間</div>
        <!--管理画面で作った時間の枠を持ってくる-->
        <div class="">
            場所の選択
            <select class>
                第一希望
                @if ($isWednesday)
                    <option value="5">beauty Ruby</option>
                @else
                    <option value="1"><!--ownだが現在はなし--></option>
                    <option value="2">遠浅公民館</option>
                    <option value="3">早来スポーツセンター</option>
                    <option value="4">町内会館</option>
                @endif
            </select>
            @if (!$isWednesday)
                <select class>
                    第二希望
                    <option value="1"><!--ownだが現在はなし--></option>
                    <option value="2">遠浅公民館</option>
                    <option value="3">早来スポーツセンター</option>
                    <option value="4">町内会館</option>
                </select>
            @endif
        </div>
        <div class="reserve-button">
            @if (auth()->check())
                <button type="submit" id="reservation-confirm">
                    予約確認画面へ
                </button>
            @else
                <a href="http://">ログイン</a>
                <div class="m-2 pb-1">
                    <div class="m-2 pb-1">
                        <p>会員登録せずにピラティス体験希望の方はこちらへ <a href="http://" class="text-xm text-blue-500 hover:text-blue-700 border-b">体験レッスン予約フォーム</a></p>
                    </div>
                    <p class="text-xs text-muted pb-1 pl-4">＊カレンダー上で空き枠を確認後お申し込みください。</p>
                    <p class="text-xs text-muted pb-1 pl-4">＊体験レッスンの日程はお申し込み後に確定となります。</p>
                    <p class="text-xs text-muted pb-1 pl-4">＊お申し込みのタイミングによっては、ご希望日時の変更をお願いする場合があります。</p>
                </div>
            @endif
        </div>
    </div>
@endsection
