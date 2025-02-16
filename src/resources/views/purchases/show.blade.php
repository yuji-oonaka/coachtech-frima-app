@extends('layouts.app')

@section('title', "{$item->name} - 商品購入")

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/show.css') }}">
@endsection

@section('content')
<div class="purchase">
    <div class="purchase__left">
        <div class="purchase__product-summary">
            <div class="purchase__product-image">
                <img class="purchase__image" src="{{ $item->img_url }}" alt="{{ $item->name }}">
            </div>
            <div class="purchase__product-details">
                <h1 class="purchase__product-name">{{ $item->name }}</h1>
                <p class="purchase__product-price">
                    <span class="purchase__price-yen">¥</span>{{ number_format($item->price) }}
                </p>
            </div>
        </div>

        <hr class="purchase__divider">

        <div class="purchase__payment-section">
            <h2 class="purchase__section-title">支払い方法</h2>
            <div class="purchase__payment-select-wrapper">
                <div class="purchase__payment-select" id="paymentSelect">
                    <div class="purchase__selected-option">選択してください</div>
                    <div class="purchase__select-arrow">▼</div>
                </div>
                <div class="purchase__payment-options" style="display: none;">
                    <div class="purchase__payment-option" data-value="コンビニ支払い">
                        <span class="purchase__checkmark">✓</span>
                        <span class="purchase__option-text">コンビニ支払い</span>
                    </div>
                    <div class="purchase__payment-option" data-value="カード支払い">
                        <span class="purchase__checkmark">✓</span>
                        <span class="purchase__option-text">カード支払い</span>
                    </div>
                </div>
            </div>
            @error('payment_method')
                <span class="purchase__error">{{ $message }}</span>
            @enderror
        </div>

        <hr class="purchase__divider">

        <div class="purchase__shipping-section">
            <div class="purchase__shipping-header">
                <h2 class="purchase__section-title">配送先</h2>
                <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}" class="purchase__change-address">変更する</a>
            </div>
            <div class="purchase__address-info">
                <p class="purchase__postal-code">〒 {{ $shippingAddress['postal_code'] ?? 'XXX-YYYY' }}</p>
                <p class="purchase__address-text">
                    {{ $shippingAddress['address'] ?? '住所が登録されていません' }}
                    @if(isset($shippingAddress['building']) && $shippingAddress['building'])
                        {{ $shippingAddress['building'] }}
                    @endif
                </p>
            </div>
            @error('shipping_postal_code')
                <span class="purchase__error">{{ $message }}</span>
            @enderror
            @error('shipping_address')
                <span class="purchase__error">{{ $message }}</span>
            @enderror
        </div>

        <hr class="purchase__divider">
    </div>

    <div class="purchase__right">
        <div class="purchase__confirm-surface">
            <div class="purchase__price-summary">
                <span class="purchase__label">商品代金</span>
                <span class="purchase__value">¥{{ number_format($item->price) }}</span>
            </div>
            <hr class="purchase__confirm-divider">
            <div class="purchase__payment-info">
                <span class="purchase__label">支払い方法</span>
                <span class="purchase__value purchase__payment-method-display">選択してください</span>
            </div>
        </div>

        @if ($errors->any())
            <div class="purchase__alert purchase__alert--danger">
                <ul class="purchase__alert-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('purchase.process', $item->id) }}" method="POST" class="purchase__form" id="purchaseForm" target="_blank">
            @csrf
            <input type="hidden" name="payment_method" id="paymentMethod" value="">
            <input type="hidden" name="shipping_postal_code" value="{{ $shippingAddress['postal_code'] }}">
            <input type="hidden" name="shipping_address" value="{{ $shippingAddress['address'] }}">
            <input type="hidden" name="shipping_building" value="{{ $shippingAddress['building'] ?? '' }}">

            <button type="submit" class="purchase__button" id="purchaseButton">購入する</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentSelect = document.getElementById('paymentSelect');
    const paymentOptions = document.querySelector('.purchase__payment-options');
    const selectedOption = document.querySelector('.purchase__selected-option');
    const options = document.querySelectorAll('.purchase__payment-option');
    const paymentMethodDisplay = document.querySelector('.purchase__payment-method-display');
    const paymentMethodInput = document.getElementById('paymentMethod');

    // 初期状態設定
    selectedOption.textContent = '選択してください';
    paymentMethodDisplay.textContent = '選択してください';
    paymentMethodInput.value = '';

    paymentSelect.addEventListener('click', function() {
        paymentOptions.style.display = paymentOptions.style.display === 'none' ? 'block' : 'none';
    });

    options.forEach(option => {
        option.addEventListener('click', function() {
            const value = this.getAttribute('data-value');

            options.forEach(opt => {
                opt.classList.remove('purchase__payment-option--selected');
                opt.querySelector('.purchase__checkmark').style.display = 'none';
            });

            this.classList.add('purchase__payment-option--selected');
            this.querySelector('.purchase__checkmark').style.display = 'inline-block';

            selectedOption.textContent = value;
            paymentMethodDisplay.textContent = value;
            paymentMethodInput.value = value;

            paymentOptions.style.display = 'none';
        });
    });

    document.addEventListener('click', function(e) {
        if (!paymentSelect.contains(e.target) && !paymentOptions.contains(e.target)) {
            paymentOptions.style.display = 'none';
        }
    });

    const purchaseForm = document.getElementById('purchaseForm');
    const purchaseButton = document.getElementById('purchaseButton');

    purchaseButton.addEventListener('click', function(e) {
        e.preventDefault();
        const paymentMethod = paymentMethodInput.value;

        if (paymentMethod === 'コンビニ支払い') {
            purchaseForm.target = '_blank';
        } else {
            purchaseForm.target = '_self';
        }

        purchaseForm.submit();
    });
});
</script>
@endsection
