@extends ('layouts.pilates')

@vite (['resources/js/pages/pilates/pilates-admin-client-show.js'])

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        クライアント詳細
    </h1>
@endsection

@section ('pilates-content')
    {{-- 基本情報 --}}
    <div
        class="border border-forest-dark/20 rounded-lg p-6 flex flex-col gap-4 mb-8"
    >
        <div class="flex items-center justify-between">
            <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest">基本情報</p>
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 text-sm text-forest-dark">
                    <input
                        type="checkbox"
                        id="line-linked-toggle"
                        data-user-id="{{ $user->id }}"
                        {{ $user->client->line_linked ? 'checked' : '' }}
                    />
                    LINE連携
                </label>
                <label class="flex items-center gap-2 text-sm text-forest-dark">
                    <input
                        type="checkbox"
                        id="is-active-toggle"
                        data-user-id="{{ $user->id }}"
                        {{ $user->client->is_active ? 'checked' : '' }}
                    />
                    在籍中
                </label>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span
                id="client-name-display"
                class="text-xl font-medium text-forest-dark"
            >
                {{ $user->client->name }}
            </span>
            <button
                type="button"
                id="name-edit-trigger"
                class="text-sm text-forest underline"
            >
                編集
            </button>
        </div>

        <div id="name-edit-form" class="hidden flex items-center gap-2">
            <input
                type="text"
                id="name-edit-input"
                value="{{ $user->client->name }}"
                class="border border-forest-dark/30 rounded px-3 py-1"
            />
            <button
                type="button"
                id="name-edit-save"
                data-user-id="{{ $user->id }}"
                class="px-3 py-1 rounded bg-forest text-white"
            >
                保存
            </button>
            <button
                type="button"
                id="name-edit-cancel"
                class="px-3 py-1 rounded border border-forest-dark/30"
            >
                キャンセル
            </button>
        </div>

        <div class="flex gap-6 text-sm text-forest-dark/70">
            <span
                >性別: {{ ['male' => '男性', 'female' => '女性', 'other' => 'その他'][$user->client->gender] }}</span
            >
            <span>電話番号: {{ $user->phone ?? '未登録' }}</span>
            <span>職業: {{ $user->client->occupation ?? '未登録' }}</span>
            @if ($user->client->has_unpaid_fee)
                <span class="text-red-600">🚩 未払いあり</span>
            @endif
        </div>
    </div>
    {{-- カルテ情報(読み取り専用) --}}
    <div
        class="border border-forest-dark/20 rounded-lg p-6 flex flex-col gap-4 mb-8"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest">カルテ情報</p>
        <div>
            <p class="text-sm text-forest-dark/70 mb-1">身体所見</p>
            <p class="text-forest-dark">{{ $user->client->body_notes ?? '記載なし' }}</p>
        </div>
        <div>
            <p class="text-sm text-forest-dark/70 mb-1">性格・特性</p>
            <p class="text-forest-dark">{{ $user->client->personality_notes ?? '記載なし' }}</p>
        </div>
    </div>
    {{-- 関連情報リンク --}}
    <div
        class="border border-forest-dark/20 rounded-lg p-6 flex flex-col gap-3"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-2">関連情報</p>
        <a href="#" class="text-forest-dark underline"
            >ゴール設定(ゴール/アウトルック/ホープ)</a
        >
        <a href="#" class="text-forest-dark underline">セッション履歴</a>
        <a href="#" class="text-forest-dark underline">予約履歴</a>
        <a href="#" class="text-forest-dark underline">初回問診情報</a>
        <a href="#" class="text-forest-dark underline"
            >会計情報(回数券購入等)</a
        >
        <a href="#" class="text-forest-dark underline"
            >トレーニングログ緊急メッセージ一覧</a
        >
    </div>

@endsection
