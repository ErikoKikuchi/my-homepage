@if ($section === 'pilates')
    <a href="{{ route('pilates.login') }}">ログイン</a>
@else
    <a href="{{ route('thinkmotion.login') }}">ログイン</a>
@endif

