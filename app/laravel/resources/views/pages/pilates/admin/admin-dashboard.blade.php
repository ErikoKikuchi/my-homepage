@extends ('layouts.pilates')

@vite (['resources/js/pages/pilates/pilates-admin-dashboard.js'])

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        管理者ダッシュボード
    </h1>
@endsection

@section ('pilates-content')
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">クイックメニュー</p>
        <div class="flex gap-2">
            <a
                href="#"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
            ></a>
        </div>
    </section>
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">やることリスト</p>
        <ul class="space-y-3">
            <li class="text-sm text-forest-dark/80">
                （プレースホルダー：後で動的化）
            </li>
        </ul>
    </section>
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">セッション一覧</p>
    </section>
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">スケジュール管理</p>
    </section>
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">クライアント管理</p>
    </section>
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">会計管理</p>
    </section>
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">BodyMind管理</p>
    </section>
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">マスタ管理</p>
    </section>
@endsection
