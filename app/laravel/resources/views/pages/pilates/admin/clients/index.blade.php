@extends ('layouts.pilates')
@vite (['resources/js/pages/pilates/pilates-admin-clients.js'])

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        クライアント管理
    </h1>
@endsection

@section ('pilates-content')
    <div class="flex items-center justify-between mb-10">
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest">クライアント一覧</p>
        <a
            href="{{ route('pilates.admin.clients.archive') }}"
            class="text-sm text-forest-dark underline"
            >アーカイブを見る</a
        >
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @forelse ($clients as $user)
            <div
                class="border border-forest-dark/20 rounded-lg p-6 flex items-center justify-between"
            >
                <div class="flex items-center gap-3">
                    @if ($user->is_client)
                        <span
                            class="text-xs px-2 py-1 rounded bg-forest text-white"
                            >クライアント</span
                        >
                    @endif

                    @if ($user->client?->has_unpaid_fee)
                        <span class="text-red-600" title="未払いあり">🚩</span>
                    @endif

                    <div>
                        <p class="font-medium text-forest-dark flex items-center gap-2">
                            {{ $user->name }}
                            @if ($user->client?->line_linked)
                                <span
                                    class="text-xs text-green-600"
                                    title="LINE連携済み"
                                    >LINE</span
                                >
                            @endif
                        </p>
                        <p class="text-sm text-forest-dark/70">
                            {{ $user->phone ?? '電話番号未登録' }}
                        </p>
                        <p class="text-sm text-forest-dark/70">
                            直近予約日: {{ $user->latest_reservation_date }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-2">
                    @if ($user->is_client)
                        <a
                            href="{{ route('pilates.admin.clients.show', $user->client) }}"
                            class="px-4 py-2 rounded border border-forest-dark text-forest-dark hover:bg-forest-dark hover:text-white"
                            >詳細</a
                        >
                    @else
                        <button
                            type="button"
                            class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                            data-client-register-modal
                            data-user-id="{{ $user->id }}"
                            data-user-name="{{ $user->name }}"
                        >
                            クライアント化
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-sm text-forest-dark/70">対象のユーザーがいません。</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $clients->links() }}</div>
    {{-- クライアント化モーダル --}}
    <div
        id="client-register-modal"
        class="hidden fixed inset-0 bg-black/50 flex items-center justify-center"
    >
        <div class="bg-white p-8 rounded-lg min-w-xs flex flex-col gap-5">
            <h3 class="text-2xl font-bold text-forest-dark">
                クライアント登録
            </h3>
            <p class="text-muted text-lg">
                <span id="modal-user-name"></span>
                さんをクライアントとして登録します。
            </p>

            <div class="flex flex-col gap-2">
                <span class="text-muted text-sm">性別</span>
                <div class="flex gap-4">
                    <label class="flex items-center gap-1">
                        <input type="radio" name="gender" value="female" />
                        女性
                    </label>
                    <label class="flex items-center gap-1">
                        <input type="radio" name="gender" value="male" />
                        男性
                    </label>
                    <label class="flex items-center gap-1">
                        <input type="radio" name="gender" value="other" />
                        その他
                    </label>
                </div>
                <p id="modal-gender-error" class="hidden text-red-600 text-sm">性別を選択してください</p>
            </div>

            <div class="flex gap-4 justify-center mt-4">
                <button
                    id="client-modal-cancel"
                    type="button"
                    class="btn btn-outline"
                >
                    キャンセル
                </button>
                <button
                    id="client-modal-submit"
                    type="button"
                    class="btn bg-forest text-white"
                >
                    登録する
                </button>
            </div>
        </div>
    </div>
@endsection
