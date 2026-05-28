@extends('layouts.app')

@section('css')
    @vite('resources/js/pages/thinkmotion/top-thinkmotion.js')
@endsection

@section('header')
    <p class="section-label">ThinkMotion</p>
    <h1>書くために考えるのではなく、<br>考えを磨くために書く。</h1>
    <p class="lead">臨床で生まれた思考を記録し、言語化し、検討し、読み合うためのプラットフォーム</p>
@endsection

@section('content')
    <!-- Concept -->
    <section class="section">
        <p class="section-title">Concept — 思考を磨くプロセス</p>
        <div class="concept-body">
            <div class="concept-step">
                <p class="step-number">01 &nbsp;残す</p>
                <p class="step-desc">違和感や判断の背景を、その場で記録する</p>
            </div>
            <div class="concept-step">
                <p class="step-number">02 &nbsp;言語化する</p>
                <p class="step-desc">なぜそう考えたのかを言葉にし、構造を明確にする</p>
            </div>
            <div class="concept-step">
                <p class="step-number">03 &nbsp;検討する</p>
                <p class="step-desc">症例を通して、思考の精度を上げる</p>
            </div>
            <div class="concept-step">
                <p class="step-number">04 &nbsp;読む</p>
                <p class="step-desc">他者の記録を読み、自分の思考に取り込む</p>
            </div>
            <p class="concept-closing">この循環を繰り返すことで、思考は磨かれていく</p>
        </div>
    </section>
    <div class="divider"> </div>
    <div class="contents">Contents</div>
    <div class="divider"> </div>
    <!-- 思考の記録を見る -->
    <section class="section">
        <p class="section-title">思考の記録を見る</p>
        <div class="block">
            <p class="block-lead">思考がどのように磨かれていくか、その過程をたどる</p>
            <div class="article-list">
                <!-- 最新記事：あとでLaravel側から動的に差し替える -->
                <div class="article-item">
                    <span class="article-date">2026.04.18</span>
                    <a href="#" class="article-title">痛みの訴えと動作観察が一致しないとき、何を優先するか</a>
                </div>
                <div class="article-item">
                    <span class="article-date">2026.04.12</span>
                    <a href="#" class="article-title">「改善した」の定義を患者と合わせるために</a>
                </div>
                <div class="article-item">
                    <span class="article-date">2026.04.05</span>
                    <a href="#" class="article-title">経験年数より思考の密度が問われる場面</a>
                </div>
            </div>
        </div>
    </section>
    <div class="divider"> </div>
    <!-- 思考を検討する -->
    <section class="section">
        <p class="section-title">思考を記録し、深める</p>
        <div class="block">
            <span class="restricted-tag">専門家限定「テーマ別ルーム」</span>
            <p class="block-lead">整形疾患・脳血管障害・神経内科疾患・内部障害などに分かれ各テーマの思考を深める場</p>
        </div>
        <div class="block">
            <span class="restricted-tag">専門家限定「症例検討ルーム」</span>
            <p class="block-lead">判断の理由や優先順位を、症例を通して深める場。登録・ログイン後にアクセスできます。</p>
        </div>
    </section>
    <!-- ページ末尾CTA -->
    <section class="cta-section">
        <div class="cta-row">
            <a href="{{ route('thinkmotion.guest.index') }}" class="btn btn-outline">思考の記録を見る</a>
            <a href="{{ route('login') }}?from=thinkmotion" class="btn btn-primary">思考の記録をはじめる</a>
        </div>
    </section>

    <!-- フッター -->
    <footer class="site-footer">
        <div class="footer-top">
            <div class="footer-links">
                <a href="/thinkmotion/how-to-use">使い方</a>
                <a href="/thinkmotion/terms">利用規約</a>
            </div>
        </div>
    </footer>
@endsection