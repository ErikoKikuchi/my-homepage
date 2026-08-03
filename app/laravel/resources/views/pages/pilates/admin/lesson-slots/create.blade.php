@extends ('layouts.pilates')
@vite (['resources/js/pages/pilates/admin-lesson-slot-create.js'])

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
        @if (session('error'))
            <div
                class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4"
            >
                {{ session('error') }}
            </div>
        @endif
        <form
            method="POST"
            action="{{ route('pilates.admin.lesson-slots.store') }}"
        >
            @csrf

            <div class="flex flex-col gap-6">
                <div
                    id="date-picker"
                    data-old-dates="{{ old('dates') ? implode(',', old('dates')) : '' }}"
                >
                    <label
                        class="block text-sm text-forest-dark mb-1"
                        for="date-input"
                        >日付(複数選択可)
                        <span class="text-red-600">*</span></label
                    >
                    <div class="flex gap-2">
                        <input
                            type="date"
                            id="date-input"
                            class="border border-forest-dark/30 rounded px-3 py-2"
                        />
                        <button
                            type="button"
                            id="add-date-btn"
                            class="bg-forest-dark/10 text-forest-dark px-3 py-2 rounded"
                        >
                            追加
                        </button>
                    </div>

                    <ul
                        id="selected-dates"
                        class="flex flex-wrap gap-2 mt-3"
                    ></ul>

                    @error ('dates')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    @error ('dates.*')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label
                        class="block text-sm text-forest-dark mb-1"
                        for="lesson_template_id"
                        >時間帯 <span class="text-red-600">*</span></label
                    >
                    <select
                        name="lesson_template_id"
                        id="lesson_template_id"
                        class="w-full border border-forest-dark/30 rounded px-3 py-2"
                    >
                        <option value="">選択してください</option>
                        @foreach ($lessonTemplates as $lessonTemplate)
                            <option
                                value="{{ $lessonTemplate->id }}"
                                {{ old('lesson_template_id') === $lessonTemplate->id ? 'selected' : '' }}
                            >
                                {{ $lessonTemplate->start_time }} 〜 {{ $lessonTemplate->end_time }}
                            </option>
                        @endforeach
                    </select>
                    @error ('lesson_template_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label
                        class="block text-sm text-forest-dark mb-1"
                        for="location_id"
                        >開催場所(固定する場合のみ選択)</label
                    >
                    <select
                        name="location_id"
                        id="location_id"
                        class="w-full border border-forest-dark/30 rounded px-3 py-2"
                    >
                        <option value="">未定(予約時に確保)</option>
                        @foreach ($locations as $location)
                            <option
                                value="{{ $location->id }}"
                                {{ old('location_id') === $location->id ? 'selected' : '' }}
                            >
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                    @error ('location_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

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
