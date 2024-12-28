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
        <div class="header-content">
            <a href="{{ route('items.index') }}" class="logo-wrapper">
                <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="header-logo">
            </a>
            <div class="search-wrapper">
                <form action="{{ route('items.search') }}" method="GET" class="search-form">
                    <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
                    @if(request()->routeIs('items.mylist'))
                        <input type="hidden" name="tab" value="mylist">
                    @endif
                </form>
            </div>
            <nav class="header-nav">
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="header-logout">
                        @csrf
                        <button type="submit" class="nav-link">ログアウト</button>
                    </form>
                    <a href="{{ route('profile.show') }}" class="nav-link">マイページ</a>
                    <a href="{{ route('items.create') }}" class="sell-button">出品</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">ログイン</a>
                    <a href="{{ route('register') }}" class="nav-link">マイページ</a>
                    <a href="{{ route('login') }}" class="sell-button">出品</a>
                @endauth
            </nav>
        </div>
    </header>
    <main>
        @yield('content')
        @yield('scripts')
    </main>

    <footer>
        <!-- フッターコンテンツ -->
    </footer>
</body>
</html>
