@csrf

<div class="flex flex-col gap-6">
    <div>
        <label class="block text-sm text-forest-dark mb-1" for="date"
            >日付 <span class="text-red-600">*</span></label
        >
        <input
            type="date"
            name="date"
            id="date"
            value="{{ old('date', isset($lessonSlot) ? $lessonSlot->date->format('Y-m-d') : '') }}"
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
                    {{ old('lesson_template_id', $lessonSlot->lesson_template_id ?? '') === $lessonTemplate->id ? 'selected' : '' }}
                >
                    {{ $lessonTemplate->start_time }} 〜 {{ $lessonTemplate->end_time }}
                </option>
            @endforeach

            @isset ($lessonSlot)
                @if (!$lessonSlot->lessonTemplate->is_active)
                    <option
                        value="{{ $lessonSlot->lessonTemplate->id }}"
                        selected
                    >
                        {{ $lessonSlot->lessonTemplate->start_time }} 〜 {{ $lessonSlot->lessonTemplate->end_time }}(無効化済み)
                    </option>
                @endif
            @endisset
        </select>
        @error ('lesson_template_id')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm text-forest-dark mb-1" for="location_id"
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
                    {{ old('location_id', $lessonSlot->location_id ?? '') === $location->id ? 'selected' : '' }}
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
