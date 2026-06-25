@extends ('layouts.pilates')

@vite (['resources/js/pages/pilates/pilates-mypage.js'])

@section ('pilates-header')
    <h1
        class="font-light text-2xl tracking-[0.04em] leading-[1.4] text-forest-dark text-center mt-10"
    >
        予約内容の確認/キャンセル
    </h1>
@endsection

@section ('pilates-content')
    <table class="mx-auto border-collapse m-10">
        <tr class="border-b">
            <th class="text-left pr-5 pl-4 pt-4 py-2 text-forest-dark">日付</th>
            <td class="pr-4 pl-5 pt-4 py-2 text-left">
                {{ $booking['date'] }}
            </td>
        </tr>
        <tr class="border-b">
            <th class="text-left pr-5 pl-4 pt-4 py-2 text-forest-dark">時間</th>
            <td class="pr-4 pl-5 pt-4 py-2 text-left">
                {{ $booking['start_time'] }} ~ {{ $booking['end_time'] }}
            </td>
        </tr>
        <tr class="border-b">
            <th class="text-left pr-5 pl-4 pt-4 py-2 text-forest-dark">場所</th>
            <td class="pr-4 pl-5 pt-4 py-2 text-left">
                {{ $booking['location'] }}
            </td>
        </tr>
        <tr class="border-b">
            <th class="text-left pr-5 pl-4 pt-4 py-2 text-forest-dark">
                参加人数
            </th>
            <td class="pr-4 pl-5 pt-4 py-2 text-left">
                {{ $booking['participants'] }}名
            </td>
        </tr>
        <tr>
            <th class="text-left pr-5 pl-4 pt-4 py-2 text-forest-dark">備考</th>
            <td class="pr-4 pl-5 pt-4 py-2 text-left">
                {{ $booking['note'] }}
            </td>
        </tr>
    </table>
    </div>
    <div class="text-center">
        <form
            method="post"
            action="{{ route('pilates.user.reservation.cancel', $reservation)}}"
        >
            @csrf
            @method ('patch')
            <button
                type="submit"
                class="border border-forest-dark bg-forest-dark text-white px-4 py-2 rounded hover:bg-forest"
            >
                キャンセル
            </button>
        </form>
    </div>
@endsection
