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
                href="{{ route('pilates.admin.session.index') }}"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >セッション一覧</a
            >
            <a
                href="{{ route('pilates.admin.schedule.index') }}"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >スケジュール管理</a
            >
            <a
                href="{{ route('pilates.admin.clients.index') }}"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >クライアント管理</a
            >
            <a
                href="{{ route('pilates.admin.accounting.index') }}"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >会計管理</a
            >
            <a
                href="{{ route('pilates.admin.bodymind.index') }}"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >BodyMind管理</a
            >
            <a
                href="{{ route('pilates.admin.location.index') }}"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >マスタ管理</a
            >
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
    <!--開発メモ（サイト機能要望・お客さんからのフィードバック等はNotionで管理）-->
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">開発メモ</p>
        <a
            href="https://app.notion.com/p/39d63551227080bfb153fc77b48c4341?source=copy_link"
            target="_blank"
            rel="noopener noreferrer"
            class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white inline-block"
            >Notionを開く</a
        >
    </section>

@endsection
