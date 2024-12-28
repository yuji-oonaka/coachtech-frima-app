@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/show.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <div class="purchase-content">
        <div class="item-summary">
            <div class="item-image">
                <img src="{{ $item->img_url }}" alt="{{ $item->name }}">
            </div>
            <div class="item-details">
                <h1 class="item-name">{{ $item->name }}</h1>
                <p class="item-price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>

        <div class="payment-section">
            <div class="payment-method">
                <h2>支払い方法</h2>
                <select name="payment_method" required>
                    <option value="" disabled selected>選択してください ▼</option>
                    <option value="convenience_store">コンビニ払い</option>
                </select>
            </div>

            <div class="delivery-address">
                <h2>配送先</h2>
                <div class="address-info">
                    <p>〒 XXX-YYYY</p>
                    <p>ここには住所と建物が入ります</p>
                    <a href="#" class="change-address">変更する</a>
                </div>
            </div>
        </div>

        <div class="purchase-summary">
            <div class="summary-row">
                <span class="label">商品代金</span>
                <span class="value">¥{{ number_format($item->price) }}</span>
            </div>
            <div class="summary-row">
                <span class="label">支払い方法</span>
                <span class="value">コンビニ払い</span>
            </div>
        </div>

        <form action="{{ route('purchase.store', $item->id) }}" method="POST">
            @csrf
            <button type="submit" class="purchase-button">購入する</button>
        </form>
    </div>
</div>
@endsection
