@extends ('layouts.pilates')

@vite (['resources/js/pages/pilates/pilates-admin-dashboard.js'])

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        スケジュール管理
    </h1>
@endsection

@section ('pilates-content')
    <div class="flex items-center justify-between mb-10">
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest">スケジュール一覧</p>
        <div class="flex gap-2">
            <a
                href="{{ route('pilates.admin.lesson-templates.index') }}"
                class="px-4 py-2 rounded border border-forest-dark text-forest-dark hover:bg-forest-dark hover:text-white"
                >時間帯テンプレート管理</a
            >

            <a
                href="{{ route('pilates.admin.lesson-slots.create') }}"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >新規登録</a
            >
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @forelse ($lessonSlots as $lessonSlot)
            <div
                class="border border-forest-dark/20 rounded-lg p-6 flex justify-between"
            >
                <div class="flex flex-col items-center gap-2">
                    <div>
                        <p class="font-medium text-forest-dark">
                            {{ $lessonSlot->date->format('Y-m-d') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-forest-dark/70">
                            {{ $lessonSlot->lessonTemplate->start_time }} 〜 {{ $lessonSlot->lessonTemplate->end_time }}
                        </p>
                        <p class="text-sm text-forest-dark/70">{{ $lessonSlot->location?->name }}</p>
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <a
                        href="{{ route('pilates.admin.lesson-slots.edit', $lessonSlot) }}"
                        class="px-4 py-2 rounded border border-forest-dark bg-forest-dark hover:bg-forest text-white"
                        >編集</a
                    >
                    <form
                        action="{{ route('pilates.admin.lesson-slots.destroy', $lessonSlot) }}"
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
            <p class="text-sm text-forest-dark/70">登録されているレッスン枠はありません。</p>
        @endforelse
    </div>
@endsection
