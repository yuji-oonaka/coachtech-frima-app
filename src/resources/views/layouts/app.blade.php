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
            <a href="{{ route('items.index') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="header-logo">
            </a>
            <div class="search-box">
                <form action="{{ route('items.search') }}" method="GET">
                    <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
                </form>
            </div>
            @auth
                <form method="POST" action="{{ route('logout') }}" class="header-logout">
                    @csrf
                    <button type="submit" class="logout-button">ログアウト</button>
                </form>
            @endauth
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
