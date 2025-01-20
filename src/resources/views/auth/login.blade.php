@extends('layouts.app')

@section('title', 'ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endsection

@section('content')
<main class="auth">
    <section class="auth__form auth__form--login">
        <h1 class="auth__title">ログイン</h1>

        <form method="POST" action="{{ route('login') }}" class="auth__form-body" novalidate>
            @csrf
            <div class="auth__form-group">
                <label for="email" class="auth__label">ユーザー名/メールアドレス</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="auth__input @error('email') auth__input--invalid @enderror"
                >
                @error('email')
                    <p class="auth__error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth__form-group">
                <label for="password" class="auth__label">パスワード</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="auth__input @error('password') auth__input--invalid @enderror"
                >
                @error('password')
                    <p class="auth__error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="auth__button auth__button--login">ログインする</button>
        </form>

        <div class="auth__link-container">
            <a href="{{ route('register') }}" class="auth__link">会員登録はこちら</a>
        </div>
    </section>
</main>
@endsection
