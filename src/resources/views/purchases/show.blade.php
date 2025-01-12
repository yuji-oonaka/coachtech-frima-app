@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/show.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    <div class="purchase-left">
        <div class="product-summary">
            <div class="product-image">
                <img src="{{ $item->img_url }}" alt="{{ $item->name }}">
            </div>
            <div class="product-details">
                <h1 class="product-name">{{ $item->name }}</h1>
                <p class="product-price">
                    <span class="price-yen">¥</span>{{ number_format($item->price) }}
                </p>
            </div>
        </div>

        <hr class="divider">

        <div class="payment-section">
            <h2>支払い方法</h2>
            <div class="payment-select-wrapper">
                <div class="payment-select" id="paymentSelect">
                    <div class="selected-option">選択してください</div>
                </div>
                <div class="payment-options" style="display: none;">
                    <div class="payment-option" data-value="コンビニ支払い">
                        <span class="checkmark">✓</span>
                        <span class="option-text">コンビニ支払い</span>
                    </div>
                    <div class="payment-option" data-value="クレジットカード">
                        <span class="checkmark">✓</span>
                        <span class="option-text">クレジットカード</span>
                    </div>
                </div>
            </div>
        </div>
        <hr class="divider">

        <div class="shipping-section">
            <div class="shipping-header">
                <h2>配送先</h2>
                <a href="{{ route('purchase.address.update', ['item_id' => $item->id]) }}" class="change-address">変更する</a>
            </div>
            <div class="address-info">
            <p class="postal-code">〒 {{ $shippingAddress['postal_code'] ?? 'XXX-YYYY' }}</p>
            <p class="address-text">
                {{ $shippingAddress['address'] ?? '住所が登録されていません' }}
                @if(isset($shippingAddress['building']) && $shippingAddress['building'])
                    {{ $shippingAddress['building'] }}
                @endif
            </p>
        </div>

        </div>
        <hr class="shipping-divider">
    </div>

    <div class="purchase-right">
        <div class="confirm-surface">
            <div class="price-summary">
                <span class="label">商品代金</span>
                <span class="value">¥{{ number_format($item->price) }}</span>
            </div>
            <hr class="confirm-divider">
            <div class="payment-info">
                <span class="label">支払い方法</span>
                <span class="value payment-method-display">選択してください</span>
            </div>
        </div>
        <form action="{{ route('purchase.process', $item->id) }}" method="POST" id="purchaseForm">
            @csrf
            <input type="hidden" name="payment_method" id="paymentMethod" value="">
            <button type="submit" class="purchase-button">購入する</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentSelect = document.getElementById('paymentSelect');
    const paymentOptions = document.querySelector('.payment-options');
    const selectedOption = document.querySelector('.selected-option');
    const options = document.querySelectorAll('.payment-option');
    const paymentMethodDisplay = document.querySelector('.payment-method-display');
    const paymentMethodInput = document.getElementById('paymentMethod');

    paymentSelect.addEventListener('click', function() {
        paymentOptions.style.display = paymentOptions.style.display === 'none' ? 'block' : 'none';
    });

    options.forEach(option => {
        option.addEventListener('click', function() {
            const value = this.getAttribute('data-value');

            options.forEach(opt => {
                opt.classList.remove('selected');
                opt.querySelector('.checkmark').style.display = 'none';
            });

            this.classList.add('selected');
            this.querySelector('.checkmark').style.display = 'inline-block';

            selectedOption.textContent = value;
            paymentMethodDisplay.textContent = value;
            paymentMethodInput.value = value;

            paymentOptions.style.display = 'none';
        });
    });

    // クリック外での閉じる処理
    document.addEventListener('click', function(e) {
        if (!paymentSelect.contains(e.target) && !paymentOptions.contains(e.target)) {
            paymentOptions.style.display = 'none';
        }
    });
});

document.getElementById('purchaseForm').addEventListener('submit', function(e) {
    if (!setPaymentMethod()) {
        e.preventDefault(); // フォームの送信を中止
    }
});

function setPaymentMethod() {
    const displayText = document.querySelector('.payment-method-display').textContent;
    const paymentMethodInput = document.getElementById('paymentMethod');
    if (displayText !== '選択してください') {
        paymentMethodInput.value = displayText;
        return true;
    } else {
        alert('支払い方法を選択してください');
        return false;
    }
}
</script>
@endsection

