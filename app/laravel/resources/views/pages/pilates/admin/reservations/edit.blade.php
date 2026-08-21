@extends ('layouts.pilates')

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        reservation edit
    </h1>
@endsection

@section ('pilates-content')
    @if (session('error'))
        <div
            class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4"
        >
            {{ session('error') }}
        </div>
    @endif
    <div class="flex flex-col gap-8">
        {{-- 変更前(読み取り専用) --}}
        <div class="bg-forest-dark/5 rounded p-4">
            <h2 class="text-sm text-forest-dark/70 mb-2">変更前</h2>
            <dl class="grid grid-cols-2 gap-y-1 text-sm">
                <dt class="text-forest-dark/60">日付</dt>
                <dd>
                    {{ $reservation->lessonSlot->date->format('Y/m/d (D)') }}
                </dd>

                <dt class="text-forest-dark/60">時間帯</dt>
                <dd>
                    {{ $reservation->lessonSlot->lessonTemplate->start_time }} 〜 {{ $reservation->lessonSlot->lessonTemplate->end_time }}
                </dd>

                <dt class="text-forest-dark/60">開催場所</dt>
                <dd>{{ $reservation->location->name ?? '未定' }}</dd>

                <dt class="text-forest-dark/60">お名前</dt>
                <dd>{{ $reservation->user->name }}</dd>
            </dl>
        </div>

        {{-- 変更後(入力フォーム) --}}
        <form
            method="POST"
            action="{{ route('pilates.admin.reservation.update', $reservation) }}"
        >
            @csrf
            @method ('PUT')

            <h2 class="text-sm text-forest-dark/70 mb-2">変更後</h2>

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
                        value="{{ old('date') }}"
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

            <button
                type="submit"
                class="mt-6 bg-forest-dark text-white px-4 py-2 rounded"
            >
                変更する
            </button>
        </form>

        {{-- キャンセル操作は別フォーム(destroy) --}}
        <form
            method="POST"
            action="{{ route('pilates.admin.reservation.destroy', $reservation) }}"
            onsubmit="return confirm('この予約をキャンセルしますか？');"
        >
            @csrf
            @method ('DELETE')
            <button type="submit" class="text-red-600 text-sm underline">
                この予約をキャンセルする
            </button>
        </form>
    </div>
@endsection
