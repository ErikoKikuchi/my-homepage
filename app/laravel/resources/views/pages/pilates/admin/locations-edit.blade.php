@extends ('layouts.pilates')

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        場所情報の編集
    </h1>
@endsection

@section ('pilates-content')
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <form
            method="POST"
            action="{{ route('pilates.admin.location.update', $location) }}"
        >
            @method ('PUT')
            @include ('pages.pilates.admin._locations-form')

            <div class="flex gap-2 mt-10">
                <button
                    type="submit"
                    class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >
                    更新する
                </button>
                <a
                    href="{{ route('pilates.admin.location.index') }}"
                    class="px-4 py-2 rounded border border-forest-dark/30 text-forest-dark hover:bg-forest-dark/5"
                    >一覧に戻る</a
                >
            </div>
        </form>
        @if ($location->is_active)
            <form
                method="POST"
                action="{{ route('pilates.admin.location.archive', $location) }}"
                onsubmit="return confirm('この場所をアーカイブしますか？');"
                class="mt-4"
            >
                @csrf
                @method ('PATCH')
                <button
                    type="submit"
                    class="px-4 py-2 rounded border border-red-400 text-red-600 hover:bg-red-50"
                >
                    アーカイブする
                </button>
            </form>
        @else
            <form
                method="POST"
                action="{{ route('pilates.admin.location.restore', $location) }}"
                class="mt-4"
            >
                @csrf
                @method ('PATCH')
                <button
                    type="submit"
                    class="px-4 py-2 rounded border border-forest text-forest hover:bg-forest/10"
                >
                    有効にする
                </button>
            </form>
        @endif

        <form
            method="POST"
            action="{{ route('pilates.admin.location.destroy', $location) }}"
            onsubmit="return confirm('この場所を削除しますか？');"
            class="mt-4"
        >
            @csrf
            @method ('DELETE')
            <button
                type="submit"
                class="px-4 py-2 rounded border border-red-400 text-red-600 hover:bg-red-50"
            >
                削除する
            </button>
        </form>
    </section>
@endsection
