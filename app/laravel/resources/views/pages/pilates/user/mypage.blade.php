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
    @if (session('message'))
        <div class="message bg-forest/30 border border-accent rounded-2xl">
            <p class="pl-3 text-forest-dark whitespace-pre-line">{{session('message')}}</p>
        </div>
    @endif
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">クイックメニュー</p>
        <div class="flex gap-2">
            @if ($notLineLinkedClient)
                <a
                    href="#line"
                    class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
                >
                    LINE登録
                </a>
            @endif
            <a
                href="#reservation"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
            >
                予約
            </a>

            <a
                href="#reservation-index"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
            >
                予約一覧
            </a>

            <a
                href="#tickets"
                class="px-4 py-2 rounded border border-forest bg-forest hover:bg-forest-dark text-white"
            >
                回数券購入
            </a>
        </div>
    </section>
    <!--次回の予約情報・回数券残数表示-->
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">次回のご予約</p>
        <div
            class="border border-forest-dark rounded-lg p-6 bg-forest/20"
            id="line"
        >
            @if ($nextReservationInfo)
                <p>予約日：{{ $nextReservationInfo['date'] }}</p>
                <p>開催場所：{{ $nextReservationInfo['location'] }}</p>
                <p>回数券残数：{{ $remainingTicketCounts }}枚</p>
                <p>ご予約は、現在の回数券残数に1回分を加えた回数までを目安にお願いいたします。 目安を超えるご予約をご希望の場合はご相談ください。</p>

            @else
                <p>次回のご予約はありません</p>
                <p>回数券残数：{{ $remainingTicketCounts }}枚</p>
                <p>ご予約は、現在の回数券残数に1回分を加えた回数までを目安にお願いいたします。 目安を超えるご予約をご希望の場合はご相談ください。</p>
            @endif
        </div>
    </section>
    <!--LINE登録案内（フラグたってない人）-->
    @if ($notLineLinkedClient)
        <section
            class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
        >
            <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">LINE登録</p>
            <div class="flex flex-col gap-10 items-center">
                <img
                    class="w-30 h-30"
                    src="
                {{ "/images/line-qr.png" }}"
                    alt="LINE登録用QRコード"
                />
                <p>予約後の場所のご案内・リマインド等の直接のやり取りは LINEで行っておりますので、ご登録お願いいたします。</p>
            </div>
        </section>
    @else

    @endif
    <!--ご予約-->
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
        id="reservation"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">ご予約はこちら</p>
        <a
            href="{{ route('pilates.guest.index') }}"
            class="px-4 py-2 rounded border border-accent bg-forest-dark text-white pointer hover:bg-forest"
            >ご予約はこちら</a
        >
    </section>
    <!--ご予約一覧-->
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
        id="reservation-index"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">ご予約一覧はこちら</p>
        <div class="flex gap-5 flex-col">
            <p>今後のご予約</p>
            @foreach ( $upcomingReservations as $upcoming)
                <table class="table-fixed">
                    <tr class="w-full h-10 border rounded">
                        <th class="text-center">日付</th>
                        <th class="text-center">場所</th>
                        <th class="text-center"></th>
                    </tr>
                    <tr class="w-full h-14 border rounded">
                        <td class="text-center">{{ $upcoming['date'] }}</td>
                        <td class="text-center">{{ $upcoming['location'] }}</td>
                        <td class="text-center">
                            <a
                                href="{{ route('pilates.user.reservation.show', $upcoming['uuid'])}}"
                                class="border border-forest-dark bg-forest-dark text-white px-4 py-2 rounded hover:bg-forest"
                                >確認/キャンセル</a
                            >
                        </td>
                    </tr>
                </table>
            @endforeach

            <p>過去のご予約</p>
            <a
                href="{{ route('pilates.past.reservation') }}"
                class="px-4 py-2 rounded border border-accent bg-forest text-white pointer hover:bg-forest/80"
                >過去のご予約はこちら</a
            >
        </div>
    </section>
    <!--回数券購入-->
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
        id="tickets"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">回数券購入</p>
        <a
            href="{{ route('pilates.tickets') }}"
            class="px-4 py-2 rounded border border-accent bg-forest-dark text-white pointer hover:bg-forest"
            >回数券の購入はこちら</a
        >
    </section>
    <!--自主トレログへのリンク-->
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
        id="training-log"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">自主トレログ</p>
    </section>
    <footer class="site-footer">
        <div class="flex items-center justify-between mb-4">
            <a
                class="px-3 text-xs tracking-[0.06em] transition-[0.2s] hover:text-forest-dark"
                href="/pilates/terms"
                >利用規約(Pilates)</a
            >
        </div>
    </footer>

@endsection
