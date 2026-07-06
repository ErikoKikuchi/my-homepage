@extends ('layouts.app')

@section ('subnav')
    <nav
        class="pilates-subnav mt-(--site-nav-height) flex gap-4 justify-end pr-2 text-nav-text bg-nav-bg border-b border-muted/10 sticky top-(--site-nav-height)"
    ></nav>
@endsection

@section ('header')
    @yield ('code-header')
@endsection

@section ('content')
    @yield ('code-content')
@endsection
