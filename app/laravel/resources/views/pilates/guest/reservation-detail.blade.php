@extends ('layouts.pilates')

@vite (['resources/js/pages/pilates/reservation-detail.js'])

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        ピラティス予約画面
    </h1>
@endsection

@section ('pilates-content')
    <div
        class="reservation-form"
        data-date="{{ $date }}"
        data-time="{{ $time }}"
        data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
        data-login-url="{{ route('login') }}"
    >
        <div class="text-xl m-2 flex-col items-start mb-5" id="reserve-place">
            <div class="font-bold">1、開催場所を選択してください</div>
            <div class="text-xl m-1 flex items-center mb-10">
                <label for="first-place">第一希望の場所　　　　</label>
                <select
                    class="text-xl m-1 border border-forest h-10"
                    id="first-place"
                >
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}">
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="text-xl m-2 flex items-center mb-10">
                <label for="second-place">第二希望の場所（任意）</label>
                <select
                    class="text-xl m-1 border border-forest h-10"
                    id="second-place"
                >
                    <option value="">未選択</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}">
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <p class="text-xs">＊実施場所は予約申請後、講師で場所を確保したのち、確定の連絡をいたします。</p>
            <p class="text-xs">＊Lineにて連絡をいたしますので、お済みでない方は事前に登録をお願いいたします。</p>
            <p class="text-xs">＊公共施設では冠婚葬祭・選挙等により急な予定変更依頼がある可能性がありますので、ご了承ください。</p>
        </div>
        <div class="text-xl m-2 items-center mb-10 gap-4">
            <label for="participants" class="font-bold"
                >2、参加人数を選択してください</label
            >
            <select
                class="text-xl m-2 border border-forest h-10 w-30"
                id="participants"
            >
                <option value="1">1名</option>
                <option value="2">2名</option>
                <option value="3">3名</option>
                <option value="4">4名</option>
            </select>
            <p>＊2名以上でご予約した場合は参加者名をご入力ください。</p>
        </div>
        <div class="text-xl m-2 items-center mb-10 w-full">
            参加者名(任意)
            <input
                type="text"
                name="name"
                id="participants_name"
                class="text-xl m-2 border border-forest h-10 w-150"
                placeholder="複数名で参加予定の方はご記入ください"
            />
        </div>
        <div class="text-xl m-2 items-center mb-10 gap-4">
            3、備考(任意)
            <textarea
                type="text"
                name="text"
                id="note"
                class="text-xl m-2 border border-forest h-20 w-full"
                placeholder="事前に講師に伝えることがあればご記入ください。"
            ></textarea>
        </div>
        <div class="reserve-button text-xl m-2 text-center mb-10">
            <button
                type="submit"
                id="reservation-confirm"
                class="border border-forest bg-forest text-white h-10 w-3xs cursor-pointer hover:bg-forest-dark"
            >
                予約確認画面へ
            </button>
            <div class="m-2 pb-1">
                <div class="m-2 pb-1">
                    <p>会員登録せずにピラティス体験希望の方はこちらへ <a href="http://" class="text-xm text-blue-500 hover:text-blue-700 border-b">体験レッスン予約フォーム</a></p>
                </div>
                <p class="text-xs text-muted pb-1 pl-4">＊カレンダー上で空き枠を確認後お申し込みください。</p>
                <p class="text-xs text-muted pb-1 pl-4">＊体験レッスンの日程はお申し込み後に確定となります。</p>
                <p class="text-xs text-muted pb-1 pl-4">＊お申し込みのタイミングによっては、ご希望日時の変更をお願いする場合があります。</p>
            </div>
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
