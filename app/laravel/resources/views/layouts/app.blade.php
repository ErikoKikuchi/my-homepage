<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ページ名 – からだ散歩</title>
    <link rel="icon" href="/images/122060.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@300;400;500&display=swap" rel="stylesheet">
    @if(!app()->environment('testing') && !config('app.vite_disabled'))
        @vite('resources/js/app.js')
        @yield('css')
    @endif
    <link rel="stylesheet" href="/css/style.css" />
    <script type="module" src="/js/components/site-nav.js"></script>
    <script type="module" src="/js/components/site-footer.jcds"></script>
</head>

<body>
    <site-nav></site-nav>
    <header class="page-header">
        <p class="section-label">セクション名</p>
        <h1></h1>
        <p class="lead"></p>
    </header>
    <main>
        <section class="section">
            @yield('content')
        </section>
    </main>
    <site-footer></site-footer>
</body>

</html>