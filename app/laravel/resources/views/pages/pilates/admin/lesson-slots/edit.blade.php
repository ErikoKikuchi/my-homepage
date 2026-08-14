@extends ('layouts.pilates')

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        レッスン枠編集
    </h1>
@endsection

@section ('pilates-content')
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <form
            method="POST"
            action="{{ route('pilates.admin.lesson-slots.update', $lessonSlot) }}"
        >
            @csrf
            @method ('PUT')

            <div class="flex flex-col gap-6">
                <div>
                    <label
                        class="block text-sm text-forest-dark mb-1"
                        for="date"
                        >日付 <span class="text-red-600">*</span></label
                    >
                    <input
                        type="date"
                        name="date"
                        id="date"
                        value="{{ old('date', $lessonSlot->date->format('Y-m-d')) }}"
                        class="w-full border border-forest-dark/30 rounded px-3 py-2"
                    />
                    @error ('date')
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
                                {{ old('lesson_template_id', $lessonSlot->lesson_template_id) === $lessonTemplate->id ? 'selected' : '' }}
                            >
                                {{ $lessonTemplate->start_time }} 〜 {{ $lessonTemplate->end_time }}
                            </option>
                        @endforeach

                        @if (!$lessonSlot->lessonTemplate->is_active)
                            <option
                                value="{{ $lessonSlot->lessonTemplate->id }}"
                                selected
                            >
                                {{ $lessonSlot->lessonTemplate->start_time }} 〜 {{ $lessonSlot->lessonTemplate->end_time }}(無効化済み)
                            </option>
                        @endif
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
                                {{ old('location_id', $lessonSlot->location_id) === $location->id ? 'selected' : '' }}
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

            <button
                type="submit"
                class="mt-6 bg-forest-dark text-white px-4 py-2 rounded"
            >
                更新する
            </button>
        </form>
    </section>
@endsection
