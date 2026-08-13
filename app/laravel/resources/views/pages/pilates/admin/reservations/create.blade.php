@extends ('layouts.pilates')
@vite(['resources/js/pages/pilates/admin-client-search.js'])

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        代理予約登録
    </h1>
@endsection

@section ('pilates-content')
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <div class="mb-6 text-forest-dark">
            <p>{{ $lessonSlot->date->format('Y年m月d日') }} {{ $lessonSlot->lessonTemplate->start_time }}〜{{ $lessonSlot->lessonTemplate->end_time }}</p>
        </div>

        <form
            method="POST"
            action="{{ route('pilates.admin.reservation.store', $lessonSlot) }}"
        >
            @csrf

            <div class="flex flex-col gap-6">
                <div class="relative">
                    <label class="block text-sm text-forest-dark mb-1" for="name">
                        お名前 <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        autocomplete="off"
                        placeholder="名前・フリガナ・電話番号で検索可能"
                        class="w-full border border-forest-dark/30 rounded px-3 py-2"
                    />
                    <ul
                        id="client-search-results"
                        class="hidden absolute z-10 w-full bg-white border border-forest-dark/30 rounded mt-1 max-h-60 overflow-y-auto"
                    ></ul>
                    @error ('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id') }}" />
                <div>
                    <label
                        class="block text-sm text-forest-dark mb-1"
                        for="phone"
                    >
                        電話番号(任意)
                    </label>
                    <input
                        type="tel"
                        name="phone"
                        id="phone"
                        value="{{ old('phone') }}"
                        class="w-full border border-forest-dark/30 rounded px-3 py-2"
                        placeholder="ご本人の連絡先、なければ紹介者の連絡先など。電話番号の変更はクライアント一覧より。"
                    />
                    @error ('phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label
                        class="block text-sm text-forest-dark mb-1"
                        for="phone"
                    >
                        関係性メモ
                    </label>
                    <input
                        type="text"
                        name="relationship_note"
                        id="relationship_note"
                        value="{{ old('relationship_note') }}"
                        class="w-full border border-forest-dark/30 rounded px-3 py-2"
                        placeholder="紹介元・家族情報"
                    />
                    @error ('relationship_note')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label
                        class="block text-sm text-forest-dark mb-1"
                        for="participants"
                    >
                        参加人数 <span class="text-red-600">*</span>
                    </label>
                    <select
                        name="participants"
                        id="participants"
                        class="w-full border border-forest-dark/30 rounded px-3 py-2"
                    >
                        <option
                            value="1"
                            {{ old('participants', 1) == 1 ? 'selected' : '' }}
                            >1名
                        </option>
                        <option
                            value="2"
                            {{ old('participants') == 2 ? 'selected' : '' }}
                            >2名
                        </option>
                        <option
                            value="3"
                            {{ old('participants') == 3 ? 'selected' : '' }}
                            >3名
                        </option>
                        <option
                            value="4"
                            {{ old('participants') == 4 ? 'selected' : '' }}
                            >4名
                        </option>
                    </select>
                    @error ('participants')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label
                        class="block text-sm text-forest-dark mb-1"
                        for="participants_name"
                    >
                        参加者名(任意)
                    </label>
                    <input
                        type="text"
                        name="participants_name"
                        id="participants_name"
                        value="{{ old('participants_name') }}"
                        class="w-full border border-forest-dark/30 rounded px-3 py-2"
                    />
                </div>

                <div>
                    <label
                        class="block text-sm text-forest-dark mb-1"
                        for="participants_phone"
                    >
                        参加者連絡先(任意)
                    </label>
                    <input
                        type="text"
                        name="participants_phone"
                        id="participants_phone"
                        value="{{ old('participants_phone') }}"
                        class="w-full border border-forest-dark/30 rounded px-3 py-2"
                    />
                </div>

                <div>
                    <label
                        class="block text-sm text-forest-dark mb-1"
                        for="note"
                    >
                        備考(任意)
                    </label>
                    <textarea
                        name="note"
                        id="note"
                        class="w-full border border-forest-dark/30 rounded px-3 py-2 h-24"
                        >{{ old('note') }}</textarea
                    >
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <button
                    type="submit"
                    class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >
                    登録する
                </button>
                <a
                    href="{{ route('pilates.admin.calendar') }}"
                    class="px-4 py-2 rounded border border-forest-dark text-forest-dark"
                >
                    キャンセル
                </a>
            </div>
        </form>
    </section>
@endsection
