@extends ('layouts.pilates')

@vite (['resources/js/pages/pilates/pilates-mypage.js'])

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        マイページ
    </h1>
@endsection

@section ('pilates-content')
    @if ($pastReservations->isEmpty())
        <p>過去のご予約はありません。</p>
    @else
        {{-- 予約一覧 --}}
        @foreach ( $pastReservations as $past)
            <table class="table-fixed">
                <tr class="w-full h-10 border rounded">
                    <th class="text-center">日付</th>
                    <th class="text-center">場所</th>
                    <th class="text-center"></th>
                </tr>
                <tr class="w-full h-14 border rounded">
                    <td class="text-center">{{ $past['date'] }}</td>
                    <td class="text-center">{{ $past['location'] }}</td>
                    <td class="text-center">
                        <a
                            href="{{ route('pilates.user.reservation.show', $past['uuid'])}}"
                            class="border border-forest bg-forest text-white px-4 py-2 rounded"
                            >詳細</a
                        >
                    </td>
                </tr>
            </table>
        @endforeach
    @endif
@endsection
