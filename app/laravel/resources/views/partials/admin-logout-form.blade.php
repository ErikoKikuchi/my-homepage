@if ($section === 'pilates')
    <form method="POST" action="{{ route('pilates.admin.logout') }}">
        @csrf
        <button type="submit">ログアウト</button>
    </form>
@else
    <form method="POST" action="{{ route('thinkmotion.admin.logout') }}">
        @csrf
        <button type="submit">ログアウト</button>
    </form>
@endif
