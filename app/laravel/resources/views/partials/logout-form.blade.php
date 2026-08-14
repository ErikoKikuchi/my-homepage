{{ $authenticatedLinks ?? '' }}
@if ($section === 'pilates')
    <form method="POST" action="{{ route('pilates.logout') }}">
        @csrf
        <button type="submit">ログアウト</button>
    </form>
@else
    <form method="POST" action="{{ route('thinkmotion.logout') }}">
        @csrf
        <button type="submit">ログアウト</button>
    </form>
@endif
