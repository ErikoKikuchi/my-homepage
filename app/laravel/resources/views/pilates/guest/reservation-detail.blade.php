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
            <div class="font-bold">1、開催場所のご案内</div>
            <p class="text-xl m-2">{{ $venueNote }}</p>
            <p class="text-sm text-gray-500 m-2">特定の場所を希望する場合は備考欄にご記入ください</p>
        </div>
        <div class="text-xl m-2 flex flex-col items-start mb-10 gap-4">
            <label for="participants" class="font-bold"
                >2、参加人数を選択してください</label
            >
            <select
                class="text-xl m-2 border border-forest h-10 w-30 pl-2"
                id="participants"
            >
                <option value="1">1名</option>
                <option value="2">2名</option>
                <option value="3">3名</option>
                <option value="4">4名</option>
            </select>
        </div>
        <div class="text-xl m-2 items-center mb-10 w-full">
            参加者名(任意)
            <input
                type="text"
                name="name"
                id="participants_name"
                class="text-xl m-2 border border-forest h-10 w-full pl-2"
                placeholder="＊2名以上でご予約した場合は参加者名をご入力ください。"
            />
        </div>
        <div class="text-xl m-2 items-center mb-10 gap-4">
            <div class="font-bold">3、備考(任意)</div>
            <textarea
                type="text"
                name="text"
                id="note"
                class="text-xl m-2 border border-forest h-20 w-full pl-2"
                placeholder="事前に講師に伝えることがあればご記入ください。"
            ></textarea>
        </div>
        <div class="m-2 mb-10 gap-4 flex flex-col">
            <p class="text-xl font-bold">4、ご確認事項/キャンセルポリシー</p>
            <p class="text-xl">＊実施場所は予約申請後に確保し、確定後にご連絡いたします。施設状況等によりご希望に添えない場合は、別施設または日程をご相談させていただきます。</p>
            <p class="text-xl">＊LINEにて連絡をいたしますので、お済みでない方は事前にマイページにて登録をお願いいたします。</p>
            <p class="text-xl">＊公共施設では冠婚葬祭・選挙等により急な予定変更依頼がある可能性がありますので、ご了承ください。</p>
            <p class="text-xl">＊キャンセルは前日の正午12：00までにお願いします。それ以降のお客様都合のキャンセルは一律500円いただきます。</p>
        </div>
        <div class="reserve-button text-xl m-2 text-center mb-10">
            <button
                type="submit"
                id="reservation-confirm"
                class="border border-forest bg-forest text-white h-10 w-3xs cursor-pointer hover:bg-forest-dark"
            >
                予約申請 確認画面へ
            </button>
            <div class="m-2 pb-1">
                <div class="m-2 pb-1">
                    <p>会員登録せずにピラティス体験希望の方はこちらへ <a href="http://" class="text-xm text-blue-500 hover:text-blue-700 border-b">体験レッスン予約フォーム</a></p>
                </div>
                <p class="text-sm text-muted pb-1 pl-4">＊カレンダー上で空き枠を確認後お申し込みください。</p>
                <p class="text-sm text-muted pb-1 pl-4">＊体験レッスンの日程はお申し込み後に確定となります。</p>
                <p class="text-sm text-muted pb-1 pl-4">＊お申し込みのタイミングによっては、ご希望日時の変更をお願いする場合があります。</p>
            </div>
        </div>
    </div>
    <div
        id="confirm-modal"
        class="hidden fixed inset-0 bg-black/50 flex items-center justify-center"
    >
        <div class="bg-white p-8 rounded-lg min-w-xs flex flex-col gap-5">
            <h3 class="text-2xl font-bold text-forest-dark">
                予約申請内容の確認
            </h3>
            <div class="flex gap-4">
                <span class="text-muted w-24 text-xl">日付</span>
                <span id="modal-date" class="text-muted text-xl"></span>
            </div>
            <div class="flex gap-4">
                <span class="text-muted w-24 text-xl">時間</span>
                <span id="modal-time" class="text-muted text-xl"></span>
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
                    予約を申請する
                </button>
            </div>
        </div>
    </div>
@endsection
