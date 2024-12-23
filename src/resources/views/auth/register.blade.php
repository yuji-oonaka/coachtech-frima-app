@extends('layouts.app')

@section('title', '会員登録')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endsection

@section('content')
<div class="auth-content">
    <div class="register-form">
        <h1>会員登録</h1>

        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf
            <div class="form-group">
                <label for="name">ユーザー名</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    class="@error('name') is-invalid @enderror"
                >
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="@error('email') is-invalid @enderror"
                >
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="@error('password') is-invalid @enderror"
                >
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">確認用パスワード</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="@error('password_confirmation') is-invalid @enderror"
                >
                @error('password_confirmation')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="register-button">登録する</button>
        </form>

        <div class="login-link">
            <a href="{{ route('login') }}">ログインはこちら</a>
        </div>
    </div>
</div>
@endsection
