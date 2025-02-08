@extends('layouts.app')

@section('title', 'メールアドレスの確認')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endsection

@section('content')
<div class="auth">
    <section class="auth__verify">
        <h1 class="auth__title">メールアドレスの確認</h1>
        <p class="auth__message">登録したメールアドレスに確認リンクを送信しました。メールを確認し、リンクをクリックして認証を完了してください。</p>
        <form method="POST" action="{{ route('verification.send') }}" class="auth__form-body">
            @csrf
            <button type="submit" class="mail__button auth__button--resend">確認メールを再送信</button>
        </form>
    </section>
</div>
@endsection
