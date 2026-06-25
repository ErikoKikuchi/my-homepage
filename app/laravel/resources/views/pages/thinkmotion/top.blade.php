@extends ('layouts.app')

@section ('header')
    <p class="font-gothic text-xs tracking-[.2em] text-forest mb-4 uppercase">ThinkMotion</p>
    <h1
        class="font-light mb-5 tracking-[0.04em] leading-[1.4] text-forest-dark"
    >
        書くために考えるのではなく、<br />考えを磨くために書く。
    </h1>
    <p class="text-[0.95rem] font-gothic text-forest leading-[1.9] max-w-240">臨床で生まれた思考を記録し、言語化し、検討し、読み合うためのプラットフォーム</p>
@endsection

@section ('content')
    <!-- Concept -->
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <div class="w-50 h-px bg-forest/40 my-14 mx-auto"></div>
        <div class="w-50 my-0 mx-auto text-center">Concept</div>
        <div class="w-50 h-px bg-forest/40 my-14 mx-auto"></div>
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">思考を磨くプロセス</p>
        <div class="border-l border-forest/38 pl-7">
            <div class="mb-7">
                <p class="text-xs tracking-[0.15em] text-forest mb-1">01 &nbsp;残す</p>
                <p class="text-sm/1.8">違和感や判断の背景を、その場で記録する</p>
            </div>
            <div class="mb-7">
                <p class="text-xs tracking-[0.15em] text-forest mb-1">02 &nbsp;言語化する</p>
                <p class="text-sm/1.8">なぜそう考えたのかを言葉にし、構造を明確にする</p>
            </div>
            <div class="mb-7">
                <p class="text-xs tracking-[0.15em] text-forest mb-1">03 &nbsp;検討する</p>
                <p class="text-sm/1.8">症例を通して、思考の精度を上げる</p>
            </div>
            <div class="mb-7">
                <p class="text-xs tracking-[0.15em] text-forest mb-1">04 &nbsp;読む</p>
                <p class="text-sm/1.8">他者の記録を読み、自分の思考に取り込む</p>
            </div>
            <p class="mt-7 pt-6 border-t border-muted/10 text-sm text-forest italic">この循環を繰り返すことで、思考は磨かれていく</p>
        </div>
    </section>
    <!-- 思考の記録を見る -->
    <section
        class="max-w-200 my-0 mx-auto py-16 px-8 border-t border-forest-dark/12"
    >
        <div class="w-50 h-px bg-forest/40 my-14 mx-auto"></div>
        <div class="w-50 my-0 mx-auto text-center">Contents</div>
        <div class="w-50 h-px bg-forest/40 my-14 mx-auto"></div>

        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">思考の記録を見る</p>
        <div class="border border-muted/15 rounded-[3px] px-8">
            <p class="text-sm text-forest py-6 leading-[1.8]">思考がどのように磨かれていくか、その過程をたどる</p>
            <div class="flex flex-col mb-6">
                <!-- 最新記事：あとでLaravel側から動的に差し替える -->
                <div
                    class="px-0 py-3.5 border-b border-muted/10 first:border-t gap-4 items-baseline flex first:border-muted/10"
                >
                    <span class="text-[11px] tracking-[0.06em] shrink-0"
                        >2026.04.18</span
                    >
                    <a
                        href="#"
                        class="text-[14px] no-underline hover:text-forest-dark"
                        >痛みの訴えと動作観察が一致しないとき、何を優先するか</a
                    >
                </div>
                <div
                    class="px-0 py-3.5 border-b border-muted/10 first:border-t gap-4 items-baseline flex first:border-muted/10"
                >
                    <span class="text-[11px] tracking-[0.06em] shrink-0"
                        >2026.04.12</span
                    >
                    <a
                        href="#"
                        class="text-[14px] no-underline hover:text-forest-dark"
                        >「改善した」の定義を患者と合わせるために</a
                    >
                </div>
                <div
                    class="px-0 py-3.5 border-b border-muted/10 first:border-t gap-4 items-baseline flex first:border-muted/10"
                >
                    <span class="text-[11px] tracking-[0.06em] shrink-0"
                        >2026.04.05</span
                    >
                    <a
                        href="#"
                        class="text-[14px] no-underline hover:text-forest-dark"
                        >経験年数より思考の密度が問われる場面</a
                    >
                </div>
            </div>
        </div>
    </section>
    <div class="my-14 mx-auto"></div>
    <!-- 思考を検討する -->
    <section class="max-w-200 my-0 mx-auto py-16 px-8">
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10">思考を記録し、深める</p>
        <div
            class="border border-muted/15 rounded-[3px] px-8 py-8 flex gap-3 flex-col"
        >
            <span class="text-base">専門家限定「テーマ別ルーム」</span>
            <p class="text-sm text-forest mb-6 leading-[1.8]">整形疾患・脳血管障害・神経内科疾患・内部障害などに分かれ各テーマの思考を深める場</p>
        </div>
        <div
            class="border border-muted/15 rounded-[3px] px-8 py-8 flex gap-3 flex-col"
        >
            <span class="text-base">専門家限定「症例検討ルーム」</span>
            <p class="text-sm text-forest mb-6 leading-[1.8]">判断の理由や優先順位を、症例を通して深める場。</p>
        </div>
        <p class="font-gothic text-xs tracking-[0.18em] uppercase text-forest mb-10 py-2 px-8">-登録・ログイン後にアクセスできます。-</p>
    </section>
    <!-- ページ末尾CTA -->
    <section class="mb-18 py-14 px-0 border-y border-muted/12 text-center">
        <div class="flex gap-10 flex-wrap justify-center">
            <a
                href="{{ route('thinkmotion.guest.index') }}"
                class="btn btn-outline"
                >思考の記録を見る</a
            >
            <a
                href="{{ route('login') }}?from=thinkmotion"
                class="btn btn-primary"
                >思考の記録をはじめる</a
            >
        </div>
    </section>
    <!-- フッター -->
    <footer class="site-footer">
        <div class="flex items-center justify-between mb-4">
            <div class="flex gap-6">
                <a
                    class="px-3 text-xs tracking-[0.06em] transition-[0.2s] hover:text-forest-dark"
                    href="/thinkmotion/how-to-use"
                    >使い方</a
                >
                <a
                    class="px-3 text-xs tracking-[0.06em] transition-[0.2s] hover:text-forest-dark"
                    href="/thinkmotion/terms"
                    >利用規約</a
                >
            </div>
        </div>
    </footer>
@endsection
