@extends ('layouts.pilates')

@section ('pilates-header')
    <p class="font-gothic text-xs tracking-[0.2em] text-forest mb-4 uppercase">Pilates</p>
    <h1
        class="font-light mb-5 tracking-[0.04em] leading-[1.4] text-forest-dark"
    >
        身体を構造から読み解き、<br />
        自分で扱える状態へ。<br />
    </h1>
    <p class="text-[0.95rem] font-gothic text-forest leading-[1.9] max-w-240">その「なぜ」に、向き合います。</p>
@endsection

@section ('pilates-content')
    <!-- Concept -->
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">Concept</p>

        <div class="font-serif text-[0.95rem] leading-[2.2] text-text mb-10">
            <p>まずは、今の身体の状態を一緒に整理します。</p>
            <p>痛みや違和感だけでなく、全身のつながりから原因を探ります。</p>
            <p class="mt-4">その上でコンディションを整え、</p>
            <p>ピラティスを通して「動ける状態」をつくります。</p>
        </div>

        <p class="font-gothic text-xs tracking-[0.12em] text-forest mb-4">こんな方に向いています</p>
        <ul class="flex flex-col gap-3">
            <li
                class="font-gothic text-[0.88rem] leading-[1.8] text-text relative pl-5"
            >
                マッサージでは解決しきらない、身体の重さや凝りがある方
            </li>
            <li
                class="font-gothic text-[0.88rem] leading-[1.8] text-text relative pl-5"
            >
                自分の身体の使い方を知りたい方
            </li>
            <li
                class="font-gothic text-[0.88rem] leading-[1.8] text-text relative pl-5"
            >
                理屈も含めて理解しながら動きたい方
            </li>
        </ul>
    </section>
    <!-- Price -->
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">Price</p>

        <p class="font-gothic text-[0.8rem] text-muted mb-7 tracking-[0.06em]">1回 60分</p>

        <div
            class="grid gap-px mb-6 border"
            style="
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                background: rgba(47, 92, 63, 0.12);
                border-color: rgba(47, 92, 63, 0.12);
            "
        >
            <div class="bg-paper flex flex-col gap-1.5 px-6.5 py-5.5">
                <span
                    class="font-gothic text-[0.72rem] tracking-[0.1em] text-muted"
                    >1回券</span
                >
                <span
                    class="font-serif text-[1.4rem] text-forest-dark tracking-[-0.01em]"
                    >¥6,000</span
                >
            </div>
            <div class="bg-paper flex flex-col gap-1.5 px-6.5 py-5.5">
                <span
                    class="font-gothic text-[0.72rem] tracking-[0.1em] text-muted"
                    >3回券</span
                >
                <span
                    class="font-serif text-[1.4rem] text-forest-dark tracking-[-0.01em]"
                    >¥17,000</span
                >
            </div>
            <div class="bg-paper flex flex-col gap-1.5 px-6.5 py-5.5">
                <span
                    class="font-gothic text-[0.72rem] tracking-[0.1em] text-muted"
                    >10回券</span
                >
                <span
                    class="font-serif text-[1.4rem] text-forest-dark tracking-[-0.01em]"
                    >¥55,000</span
                >
            </div>
        </div>

        <p class="font-gothic text-[0.82rem] leading-[1.8] text-text mb-2.5">グループセッション（最大4名）は料金÷人数</p>
        <p class="font-gothic text-[0.78rem] leading-[1.8] text-muted pl-4" style="
                text-indent: -1em;
            ">※ 継続して身体の変化を見ていくことをおすすめしています。</p>
    </section>
    <!-- Location -->
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">Location</p>
        <p class="font-gothic text-[0.85rem] leading-[1.8] text-text">北海道安平町及び苫小牧駅周辺エリアで実施中。（詳細はご予約後にご案内しています。）</p>
    </section>
    <!-- CTA -->
    <section class="mb-18 py-14 px-0 border-y border-muted/12 text-center">
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">ご予約・お問い合わせ</p>
        <p class="font-serif text-base leading-[2] font-light text-text mb-8">身体を知るところから、始めてみませんか。<br />こちらからご予約いただけます。</p>
        <a href="{{ route('pilates.guest.index') }}" class="btn btn-primary"
            >空き確認・予約</a
        >
        <p class="font-gothic text-[0.78rem] mt-4">
            <a
                href="{{ config('services.static_site.url', 'http://localhost:5173') }}/src/contact/index.html"
                class="text-muted hover:text-forest no-underline"
                >お問い合わせはこちら →</a
            >
        </p>
    </section>
@endsection
