@csrf

<div class="flex flex-col gap-6">
    <div>
        <label class="block text-sm text-forest-dark mb-1" for="start_time"
            >開始時間 <span class="text-red-600">*</span></label
        >
        <input
            type="time"
            name="start_time"
            id="start_time"
            value="{{ old('start_time', $lessonTemplate->start_time ?? '') }}"
            class="w-full border border-forest-dark/30 rounded px-3 py-2"
        />
        @error ('start_time')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm text-forest-dark mb-1" for="end_time"
            >終了時間 <span class="text-red-600">*</span></label
        >
        <input
            type="time"
            name="end_time"
            id="end_time"
            value="{{ old('end_time', $lessonTemplate->end_time ?? '') }}"
            class="w-full border border-forest-dark/30 rounded px-3 py-2"
        />
        @error ('end_time')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex gap-8">
        <label class="flex items-center gap-2 text-sm text-forest-dark">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                {{ old('is_active', $lessonTemplate->is_active ?? true) ? 'checked' : '' }}
            />
            有効にする(新規スケジュール作成時の選択肢に表示)
        </label>
    </div>
</div>
