{{-- resources/views/pilates/admin/reservations/pending.blade.php --}}
@extends ('layouts.pilates')
@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        会場確認待ちの予約
    </h1>
@endsection

@section ('pilates-content')
    <div class="max-w-4xl mx-auto p-6">
        @if (session('success'))
            <div class="bg-forest-light/20 text-forest-dark p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($reservations->isEmpty())
            <p class="text-gray-500">現在、会場確認待ちの予約はありません。</p>
        @else
            <div class="space-y-4">
                @foreach ($reservations as $reservation)
                    <div
                        class="border border-forest-light/40 rounded-lg p-4 flex flex-col gap-2"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-forest-dark">
                                    {{ $reservation->lessonSlot->date->format('Y年m月d日') }} {{ $reservation->lessonSlot->lessonTemplate->start_time }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $reservation->user->name }}様 ({{ $reservation->participants }}名)
                                </p>
                                <p class="text-sm text-gray-600">
                                    TEL: {{ $reservation->user->phone }}
                                </p>
                                @if ($reservation->note)
                                    <p class="text-sm text-gray-500 mt-1">
                                        備考: {{ $reservation->note }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex flex-col gap-2 items-end">
                                <a
                                    href="{{ $lineUrl }}
                                    target="
                                    _blank"
                                    rel="noopener"
                                    class="text-sm px-3 py-1.5 rounded border border-forest-dark text-forest-dark hover:bg-forest-light/10"
                                >
                                    LINEを開く
                                </a>

                                <form
                                    action="{{ route('pilates.admin.reservation.confirm', $reservation) }}"
                                    method="POST"
                                    class="flex flex-col gap-2 items-end"
                                >
                                    @csrf
                                    @method ('PATCH')

                                    <select
                                        name="location_id"
                                        required
                                        class="text-sm border border-forest-light rounded px-2 py-1"
                                    >
                                        <option value="">会場を選択</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button
                                        type="submit"
                                        class="text-sm px-3 py-1.5 rounded bg-forest-dark text-white hover:bg-forest-dark/90"
                                        onclick="
                                            return confirm(
                                                'この予約を確定しますか？',
                                            );
                                        "
                                    >
                                        確定する
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
