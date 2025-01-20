@extends('layouts.app')

@section('title', '会員登録')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endsection

@section('content')
<div class="auth">
    <section class="auth__form auth__form--register">
        <h1 class="auth__title">会員登録</h1>

        <form method="POST" action="{{ route('register') }}" class="auth__form-body" novalidate>
            @csrf
            <div class="auth__form-group">
                <label for="name" class="auth__label">ユーザー名</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="auth__input @error('name') auth__input--invalid @enderror"
                >
                @error('name')
                    <p class="auth__error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth__form-group">
                <label for="email" class="auth__label">メールアドレス</label>
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

            <div class="auth__form-group">
                <label for="password-confirmation" class="auth__label">確認用パスワード</label>
                <input
                    type="password"
                    id="password-confirmation"
                    name="password_confirmation"
                    class="auth__input @error('password_confirmation') auth__input--invalid @enderror"
                >
                @error('password_confirmation')
                    <p class="auth__error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="auth__button auth__button--register">登録する</button>
        </form>

        <div class="auth__link-container">
            <a href="{{ route('login') }}" class="auth__link">ログインはこちら</a>
        </div>
    </section>
</div>
@endsection
