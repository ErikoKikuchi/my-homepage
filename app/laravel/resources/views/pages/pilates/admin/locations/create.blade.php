@extends ('layouts.pilates')

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        場所の新規登録
    </h1>
@endsection

@section ('pilates-content')
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <form
            method="POST"
            action="{{ route('pilates.admin.location.store') }}"
        >
            @include ('pages.pilates.admin.locations._form')

            <div class="flex gap-2 mt-10">
                <button
                    type="submit"
                    class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >
                    登録する
                </button>
                <a
                    href="{{ route('pilates.admin.location.index') }}"
                    class="px-4 py-2 rounded border border-forest-dark/30 text-forest-dark hover:bg-forest-dark/5"
                    >一覧に戻る</a
                >
            </div>
        </form>
    </section>
@endsection
