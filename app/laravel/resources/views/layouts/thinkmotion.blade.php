@extends ('layouts.app')

@section ('subnav')
    <nav
        class="thinkmotion-subnav mt-(--site-nav-height) flex gap-4 justify-end pr-2 text-nav-text bg-nav-bg border-b border-muted/10 sticky top-(--site-nav-height)"
    >
        @auth
            <a href="{{ route('thinkmotion.mypage') }}">マイページ</a>
            <!--マイページの中に投稿・プロフィール編集の動線-->
            <!--るーむへの動線-->
            <!--るーむ管理への動線-->
            <!--みんなの本棚への動線-->
            @if (auth()->user()->is_pilates_user)
                <a href="{{ route('pilates.mypage') }}">Pilatesマイページへ</a>
            @endif
            @include ('partials.logout-form')
        @else
            @include ('partials.login-link')
        @endauth
    </nav>
@endsection

@section ('header')
    @yield ('thinkmotion-header')
@endsection

@section ('content')
    @yield ('thinkmotion-content')
@endsection
