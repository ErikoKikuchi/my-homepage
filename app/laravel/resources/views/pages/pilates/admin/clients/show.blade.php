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
    <section class="max-w-200 my-0 mx-auto pt-10 px-8">
        <a
            href="{{ route('pilates.admin.clients.index') }}"
            class="text-sm text-forest hover:text-forest-dark"
            >← クライアント一覧へ戻る</a
        >
    </section>
    {{-- 1. 基本情報エリア --}}
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">基本情報</p>

        <div class="space-y-6">
            {{-- name: 表示⇄編集切替 --}}
            <div class="flex items-center gap-4" data-field="name">
                <span class="w-28 shrink-0 text-sm text-forest-dark/60"
                    >氏名</span
                >

                <div class="flex-1">
                    <div class="js-display flex items-center gap-3">
                        <span
                            class="js-display-value text-forest-dark"
                            >{{ $user->name }}</span
                        >
                        <button
                            type="button"
                            class="js-start-edit text-xs text-forest hover:text-forest-dark underline"
                        >
                            編集
                        </button>
                    </div>

                    <form
                        class="js-edit-form hidden items-center gap-3"
                        data-patch-url="{{ route('pilates.admin.clients.update', $user) }}"
                    >
                        @csrf
                        @method ('PATCH')
                        <input
                            type="text"
                            name="name"
                            value="{{ $user->name }}"
                            class="js-name-input border border-forest-dark/20 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-forest"
                        />
                        <button
                            type="submit"
                            class="px-3 py-1.5 rounded bg-forest hover:bg-forest-dark text-white text-xs"
                        >
                            保存
                        </button>
                        <button
                            type="button"
                            class="js-cancel-edit text-xs text-forest-dark/60 hover:text-forest-dark"
                        >
                            キャンセル
                        </button>
                        <span
                            class="js-name-error text-xs text-red-600 hidden"
                        ></span>
                    </form>
                </div>
            </div>

            {{-- gender: 表示のみ --}}
            <div class="flex items-center gap-4">
                <span class="w-28 shrink-0 text-sm text-forest-dark/60"
                    >性別</span
                >
                <span
                    class="text-forest-dark"
                    >{{ $user->client->gender }}</span
                >
            </div>

            {{-- line_linked: トグル --}}
            <div
                class="flex items-center gap-4"
                data-field="line_linked"
                data-patch-url="{{ route('pilates.admin.clients.update', $user) }}"
            >
                <span class="w-28 shrink-0 text-sm text-forest-dark/60"
                    >LINE連携</span
                >
                <label class="inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        name="line_linked"
                        class="js-toggle-input sr-only peer"
                        {{ $user->client->line_linked ? 'checked' : '' }}
                    />
                    <span
                        class="w-10 h-5 rounded-full bg-forest-dark/20 peer-checked:bg-forest transition-colors relative before:content-[''] before:absolute before:top-0.5 before:left-0.5 before:w-4 before:h-4 before:bg-white before:rounded-full before:transition-transform peer-checked:before:translate-x-5"
                    ></span>
                </label>
            </div>

            {{-- is_active: トグル + confirm --}}
            <div
                class="flex items-center gap-4"
                data-field="is_active"
                data-patch-url="{{ route('pilates.admin.clients.update', $user) }}"
                data-confirm-message="アーカイブ状態に変更します。よろしいですか？"
            >
                <span class="w-28 shrink-0 text-sm text-forest-dark/60"
                    >現在利用中</span
                >
                <label class="inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        class="js-toggle-input sr-only peer"
                        {{ $user->client->is_active ? 'checked' : '' }}
                    />
                    <span
                        class="w-10 h-5 rounded-full bg-forest-dark/20 peer-checked:bg-forest transition-colors relative before:content-[''] before:absolute before:top-0.5 before:left-0.5 before:w-4 before:h-4 before:bg-white before:rounded-full before:transition-transform peer-checked:before:translate-x-5"
                    ></span>
                </label>
                <span class="text-xs text-forest-dark/50">
                    {{ $user->client->is_active ? '（有効）' : '（アーカイブ済み）' }}
                </span>
            </div>
        </div>
    </section>
    {{-- 2. カルテ関連（読み取り専用） --}}
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">カルテ情報（参照のみ）</p>

        <div class="space-y-6">
            <div>
                <p class="text-sm text-forest-dark/60 mb-2">身体の特徴・所見</p>
                <p class="text-forest-dark whitespace-pre-wrap">
                    {{ $user->client->body_notes ?: '記録なし' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-forest-dark/60 mb-2">性格・傾向</p>
                <p class="text-forest-dark whitespace-pre-wrap">
                    {{ $user->client->personality_notes ?: '記録なし' }}
                </p>
            </div>
        </div>

        <p class="text-xs text-forest-dark/40 mt-6">※ 編集はカルテ機能実装後、カルテ記載画面から行ってください。</p>
    </section>
    {{-- 3. 導線のみ（実体未実装） --}}
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">関連情報</p>

        <div class="flex flex-wrap gap-2">
            {{-- 実体未実装のため一旦 href="#"。各機能実装時に route() へ差し替え --}}
            <a
                href="#"
                class="px-4 py-2 rounded border border-forest-dark/20 text-forest-dark/40 cursor-not-allowed pointer-events-none"
                >ゴール設定</a
            >
            <a
                href="#"
                class="px-4 py-2 rounded border border-forest-dark/20 text-forest-dark/40 cursor-not-allowed pointer-events-none"
                >セッション履歴</a
            >
            <a
                href="#"
                class="px-4 py-2 rounded border border-forest-dark/20 text-forest-dark/40 cursor-not-allowed pointer-events-none"
                >予約履歴</a
            >
            <a
                href="#"
                class="px-4 py-2 rounded border border-forest-dark/20 text-forest-dark/40 cursor-not-allowed pointer-events-none"
                >初回問診情報</a
            >
            <a
                href="#"
                class="px-4 py-2 rounded border border-forest-dark/20 text-forest-dark/40 cursor-not-allowed pointer-events-none"
                >会計情報</a
            >
            <a
                href="#"
                class="px-4 py-2 rounded border border-forest-dark/20 text-forest-dark/40 cursor-not-allowed pointer-events-none"
                >トレーニングログ緊急メッセージ</a
            >
        </div>
    </section>

@endsection
