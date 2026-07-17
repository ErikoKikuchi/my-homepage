@extends ('layouts.pilates')

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        レッスン枠作成
    </h1>
@endsection

@section ('pilates-content')
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <form
            method="POST"
            action="{{ route('pilates.admin.lesson-slots.store') }}"
        >
            @include ('pages.pilates.admin._lesson-slots-form')

            <div class="mt-8 flex gap-4">
                <button
                    type="submit"
                    class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >
                    作成する
                </button>
                <a
                    href="{{ route('pilates.admin.lesson-slots.index') }}"
                    class="px-4 py-2 rounded border border-forest-dark text-forest-dark"
                >
                    キャンセル
                </a>
            </div>
        </form>
    </section>
@endsection
