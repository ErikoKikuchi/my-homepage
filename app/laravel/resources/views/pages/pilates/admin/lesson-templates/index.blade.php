@extends ('layouts.pilates')

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        レッスン時間帯テンプレート管理
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
            <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest">時間帯一覧</p>

            <a
                href="{{ route('pilates.admin.lesson-templates.create') }}"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >新規登録</a
            >
        </div>

        <div class="flex flex-col gap-4">
            @forelse ($lessonTemplates as $lessonTemplate)
                <div
                    class="border border-forest-dark/20 rounded-lg p-6 flex items-center justify-between {{ !$lessonTemplate->is_active ? 'opacity-50' : '' }}"
                >
                    <div>
                        <p class="font-medium text-forest-dark">
                            {{ $lessonTemplate->start_time }} 〜 {{ $lessonTemplate->end_time }}
                            @unless ($lessonTemplate->is_active)
                                <span class="text-xs text-red-600">(無効)</span>
                            @endunless
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a
                            href="{{ route('pilates.admin.lesson-templates.edit', $lessonTemplate) }}"
                            class="px-4 py-2 rounded border border-forest-dark bg-forest-dark hover:bg-forest text-white"
                            >編集</a
                        >
                        <form
                            action="{{ route('pilates.admin.lesson-templates.destroy', $lessonTemplate) }}"
                            method="POST"
                            onsubmit="
                                return confirm(
                                    '削除しますか？(使用中のテンプレートは削除できません)',
                                );
                            "
                        >
                            @csrf
                            @method ('DELETE')
                            <button
                                type="submit"
                                class="px-4 py-2 rounded border border-red-600 text-red-600 hover:bg-red-600 hover:text-white"
                            >
                                削除
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-forest-dark/70">登録されている時間帯テンプレートはありません。</p>
            @endforelse
        </div>
        <div class="mt-4">
            <a
                href="{{ route('pilates.admin.lesson-slots.index') }}"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >スケジュール管理に戻る</a
            >
        </div>
    </section>
@endsection
