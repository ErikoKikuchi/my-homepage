@extends ('layouts.app')

@section ('subnav')
    <nav
        class="pilates-subnav mt-(--site-nav-height) flex gap-4 justify-end pr-2 text-nav-text bg-nav-bg border-b border-muted/10"
    >
        @auth
            <a href="{{ route('pilates.mypage') }}">マイページ</a>
            @if (auth()->user()->is_client && auth()->user()->client?->is_active)
                <a href="{{ route('pilates.user.training-log.index') }}"
                    >自主トレログ</a
                >
            @endif
            @if (auth()->user()->is_medical)
                <a href="{{ route('thinkmotion.mypage') }}"
                    >ThinkMotionマイページへ</a
                >
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        @else
            <a href="{{ route('login') }}">ログイン</a>
        @endauth
    </nav>
    @yield ('subnav')
@endsection

@section ('header')
    @yield ('pilates-header')
@endsection

@section ('content')
    @yield ('pilates-content')
@endsection
