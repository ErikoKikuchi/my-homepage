@extends ('layouts.pilates')

@vite (['resources/js/pages/pilates/reservation-detail.js'])

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        ピラティス日時選択画面
    </h1>
@endsection

@section ('pilates-content')
    <div class="reservation-form" data-date="{{ $date }}">
        <div class="text-xl m-2">{{ $date }}の予約</div>
        <div class="text-xl m-2 flex flex-col items-center mb-10 gap-4">
            レッスン開始時間
            <!--管理画面で作った時間の枠を持ってくる-->
            @foreach ($times as $time)
                <div class="text-xl m-2 items-center">
                    {{ $time }}
                    <button
                        class="time-btn text-xl m-2 bg-forest cursor-pointer text-white w-30 h-10 hover:bg-forest-dark"
                        type="button"
                        data-time="{{ $time }}"
                    >
                        選択
                    </button>
                </div>
            @endforeach
        </div>
        <div
            class="text-xl m-2 flex flex-col items-center mb-10 gap-4"
            id="reserve-place"
        >
            場所の選択
            <div>
                <label for="first-place">第一希望の場所　　　　</label>
                <select
                    class="text-xl m-2 border border-forest h-10"
                    id="first-place"
                >
                    @if ($isWednesday)
                        <option value="5">beauty Ruby</option>
                    @else
                        <option value="1"><!--ownだが現在はなし--></option>
                        <option value="2">遠浅公民館</option>
                        <option value="3">早来スポーツセンター</option>
                        <option value="4">町内会館</option>
                    @endif
                </select>
            </div>
            <div>
                <label for="second-place">第二希望の場所（任意）</label>
                @if (!$isWednesday)
                    <select
                        class="text-xl m-2 border border-forest h-10"
                        id="second-place"
                    >
                        第二希望
                        <option value="1"><!--ownだが現在はなし--></option>
                        <option value="2">遠浅公民館</option>
                        <option value="3">早来スポーツセンター</option>
                        <option value="4">町内会館</option>
                    </select>
                @endif
            </div>
        </div>
        <div class="text-xl m-2 flex flex-col items-center mb-10 gap-4">
            <label for="first-place">人数</label>
            <select
                class="text-xl m-2 border border-forest h-10 w-30"
                id="participants"
            >
                <option value="1">1名</option>
                <option value="2">2名</option>
                <option value="3">3名</option>
                <option value="4">4名</option>
            </select>
            <p>*複数名でご予約した場合ピラティスのグループレッスンのみの対応となります。</p>
        </div>
        <div class="text-xl m-2 flex flex-col items-center mb-10 gap-4">
            参加者名
            <input
                type="text"
                name="name"
                id="participants_name"
                class="text-xl m-2 border border-forest h-10 w-150"
                placeholder="複数名で参加予定の方はご記入ください"
            />
        </div>
        <div class="text-xl m-2 flex flex-col items-center mb-10 gap-4">
            備考
            <textarea
                type="text"
                name="text"
                id="note"
                class="text-xl m-2 border border-forest h-40 w-150"
                placeholder="事前に講師に伝えることがあればご記入ください。"
            ></textarea>
        </div>
        <div class="reserve-button text-xl m-2 text-center mb-10">
            @if (auth()->check())
                <button
                    type="submit"
                    id="reservation-confirm"
                    class="border border-forest bg-forest text-white h-10 w-3xs cursor-pointer hover:bg-forest-dark"
                >
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
    <div
        id="confirm-modal"
        class="hidden fixed inset-0 bg-black/50 flex items-center justify-center"
    >
        <div class="bg-white p-8 rounded-lg min-w-xs flex flex-col gap-5">
            <h3 class="text-2xl font-bold text-forest-dark">予約内容の確認</h3>
            <div class="flex gap-4">
                <span class="text-muted w-24 text-xl">日付</span>
                <span id="modal-date" class="text-muted text-xl"></span>
            </div>
            <div class="flex gap-4">
                <span class="text-muted w-24 text-xl">時間</span>
                <span id="modal-time" class="text-muted text-xl"></span>
            </div>
            <div class="flex gap-4">
                <span class="text-muted w-24 text-xl">第一希望</span>
                <span id="modal-place" class="text-muted text-xl"></span>
            </div>
            <div class="flex gap-4">
                <span class="text-muted w-24 text-xl">第二希望</span>
                <span id="modal-place2" class="text-muted text-xl"></span>
            </div>
            <div class="flex gap-4">
                <span class="text-muted w-24 text-xl">人数</span>
                <span id="modal-participants" class="text-muted text-xl"></span>
            </div>
            <div class="flex gap-4">
                <span class="text-muted w-24 text-xl">参加者名</span>
                <span
                    id="modal-participants-name"
                    class="text-muted text-xl"
                ></span>
            </div>
            <div class="flex gap-4">
                <span class="text-muted w-24 text-xl">備考</span>
                <span id="modal-note" class="text-muted text-xl"></span>
            </div>
            <div class="flex gap-4 justify-center mt-4">
                <button id="modal-cancel" class="btn btn-outline">
                    キャンセル
                </button>
                <button id="modal-submit" class="btn bg-forest text-white">
                    予約する
                </button>
            </div>
        </div>
    </div>
@endsection
