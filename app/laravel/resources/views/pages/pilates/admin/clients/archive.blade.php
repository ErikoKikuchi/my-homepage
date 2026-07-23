@extends ('layouts.pilates')

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        アーカイブ済みクライアント
    </h1>
@endsection

@section ('pilates-content')
    <div class="flex items-center justify-between mb-10">
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest">アーカイブ一覧</p>

        <a
            href="{{ route('pilates.admin.clients.index') }}"
            class="px-4 py-2 rounded border border-forest-dark text-forest-dark hover:bg-forest-dark hover:text-white"
            >クライアント一覧に戻る</a
        >
    </div>
    <div class="flex flex-col gap-4">
        @forelse ($clients as $user)
            <div
                class="border border-forest-dark/20 rounded-lg p-6 flex items-center justify-between"
            >
                <div class="flex items-center gap-3">
                    @if ($user->client?->has_unpaid_fee)
                        <span class="text-red-600" title="未払いあり">🚩</span>
                    @endif
                    <div>
                        <p class="font-medium text-forest-dark">{{ $user->name }}</p>
                        <p class="text-sm text-forest-dark/70">{{ $user->phone ?? '電話番号未登録' }}</p>
                        <p class="text-sm text-forest-dark/70">直近予約日: {{ $user->latest_reservation_date }}</p>
                    </div>
                </div>

                <a
                    href="{{ route('pilates.admin.clients.show', $user) }}"
                    class="px-4 py-2 rounded border border-forest-dark text-forest-dark hover:bg-forest-dark hover:text-white"
                    >詳細</a
                >
            </div>
        @empty
            <p class="text-sm text-forest-dark/70">アーカイブされたクライアントはいません。</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $clients->links() }}</div>
@endsection
