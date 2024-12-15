<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>フリマアプリ - @yield('title')</title>
    <link href="{{ asset('css/sanitize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/layouts/app.css') }}" rel="stylesheet">
    @yield('css')
</head>
<body>
    <header class="auth-header">
        <nav>
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="header-logo">
            </a>
        </nav>
    </header>


    <main>
        @yield('content')
    </main>

    <footer>
        <!-- フッターコンテンツ -->
    </footer>
</body>
</html>
