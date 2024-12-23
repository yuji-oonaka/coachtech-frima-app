@extends('layouts.app')

@section('title', 'ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endsection

@section('content')
<div class="auth-content">
    <div class="login-form">
        <h1>ログイン</h1>

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf
            <div class="form-group">
                <label for="email">ユーザー名/メールアドレス</label>
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

            <button type="submit" class="login-button">ログインする</button>
        </form>

        <div class="register-link">
            <a href="{{ route('register') }}">会員登録はこちら</a>
        </div>
    </div>
</div>
@endsection
