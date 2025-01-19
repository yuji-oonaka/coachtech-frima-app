@extends('layouts.app')

@section('title', 'メールアドレスの確認')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endsection

@section('content')
<div class="auth-content">
    <div class="verify-email-container">
        <h1>メールアドレスの確認</h1>
        <p>登録したメールアドレスに確認リンクを送信しました。メールを確認し、リンクをクリックして認証を完了してください。</p>
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="register-button">確認メールを再送信</button>
        </form>
    </div>
</div>
@endsection
