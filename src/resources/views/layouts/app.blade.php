<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) - coachtechフリマ</title>
    <link href="{{ asset('css/sanitize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/layouts/app.css') }}" rel="stylesheet">
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__content">
            <a href="{{ route('items.index') }}" class="header__logo-wrapper">
                <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="header__logo">
            </a>
            @if(!$hideNavigation)
            <div class="header__search">
                <form action="{{ route('items.search') }}" method="GET" class="header__search-form">
                    <input type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ request('keyword') }}" class="header__search-input">
                    @if(request()->routeIs('items.mylist'))
                        <input type="hidden" name="tab" value="mylist">
                    @endif
                </form>
            </div>
            <nav class="header__nav">
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="header__logout">
                        @csrf
                        <button type="submit" class="header__nav-link header__logout-button">ログアウト</button>
                    </form>
                    <a href="{{ route('profile.show') }}" class="header__nav-link">マイページ</a>
                    <a href="{{ route('items.create') }}" class="header__sell-button">出品</a>
                @else
                    <a href="{{ route('login') }}" class="header__nav-link">ログイン</a>
                    <a href="{{ route('login') }}" class="header__nav-link">マイページ</a>
                    <a href="{{ route('login') }}" class="header__sell-button">出品</a>
                @endauth
            </nav>
            @endif
        </div>
    </header>
    <main>
        @if (session('success'))
        <div class="message message--success" id="successMessage">
            {{ session('success') }}
        </div>
        @endif
        @yield('content')
        @yield('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.remove();
                }, 3000);
            }
        });
        </script>
    </main>
    <footer>
        <!-- フッターコンテンツ -->
    </footer>
</body>
</html>
