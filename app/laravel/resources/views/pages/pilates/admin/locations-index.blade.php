@extends ('layouts.pilates')

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        場所管理
    </h1>
@endsection

@section ('pilates-content')
    @if (session('message'))
        <div class="message bg-forest/30 border border-accent rounded-2xl">
            <p class="pl-3 text-forest-dark whitespace-pre-line">{{ session('message') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="message bg-red-100 border border-red-400 rounded-2xl">
            <p class="pl-3 text-red-700 whitespace-pre-line">{{ session('error') }}</p>
        </div>
    @endif
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <div class="flex items-center justify-between mb-10">
            <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest">場所一覧</p>
            <a
                href="{{ route('pilates.admin.location.create') }}"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >新規登録</a
            >
        </div>

        <div class="flex flex-col gap-4">
            @forelse ($locations as $location)
                <div
                    class="border border-forest-dark/20 rounded-lg p-6 flex items-center justify-between {{ !$location->is_active ? 'opacity-50' : '' }}"
                >
                    <div>
                        <p class="font-medium text-forest-dark">
                            {{ $location->name }}
                            @unless ($location->is_active)
                                <span class="text-xs text-red-600">(無効)</span>
                            @endunless
                            @unless ($location->is_bookable)
                                <span class="text-xs text-forest-dark/60"
                                    >(予約不可)</span
                                >
                            @endunless
                        </p>
                        <p class="text-sm text-forest-dark/70">{{ $location->address }}</p>
                        <p class="text-sm text-forest-dark/70">場所代（経費）：{{ $location->base_fee !== null ? number_format($location->base_fee) . '円' : '未設定' }} ／交通費加算：{{ number_format($location->price_addon_per_session) }}円</p>
                    </div>
                    <div class="flex gap-2">
                        <a
                            href="{{ route('pilates.admin.location.edit', $location) }}"
                            class="px-4 py-2 rounded border border-forest-dark bg-forest-dark hover:bg-forest text-white"
                            >編集</a
                        >
                    </div>
                </div>
            @empty
                <p class="text-sm text-forest-dark/70">登録されている場所はありません。</p>
            @endforelse
        </div>
    </section>
@endsection
