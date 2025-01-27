@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/address.css') }}">
@endsection

@section('content')
<div class="address">
    <h1 class="address__title">住所の変更</h1>
    <form action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}" method="POST" class="address__form" novalidate>
        @csrf
        <input type="hidden" name="prefix" value="shipping_">
        <div class="address__form-group">
            <label for="shipping_postal_code" class="address__label">郵便番号</label>
            <input
                type="text"
                id="shipping_postal_code"
                name="shipping_postal_code"
                value="{{ old('shipping_postal_code', session('shipping_address.postal_code') ?? $user->address->postal_code ?? '') }}"
                class="address__input @error('shipping_postal_code') address__input--invalid @enderror"
                required
                pattern="\d{3}-\d{4}"
                placeholder="123-4567"
            >
            @error('shipping_postal_code')
                <div class="address__error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="address__form-group">
            <label for="shipping_address" class="address__label">住所</label>
            <input
                type="text"
                id="shipping_address"
                name="shipping_address"
                value="{{ old('shipping_address', session('shipping_address.address') ?? $user->address->address ?? '') }}"
                class="address__input @error('shipping_address') address__input--invalid @enderror"
                required
            >
            @error('shipping_address')
                <div class="address__error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="address__form-group">
            <label for="shipping_building" class="address__label">建物名（任意）</label>
            <input
                type="text"
                id="shipping_building"
                name="shipping_building"
                value="{{ old('shipping_building', session('shipping_address.building') ?? $user->address->building ?? '') }}"
                class="address__input @error('shipping_building') address__input--invalid @enderror"
                placeholder="建物名を入力（任意）"
            >
            @error('shipping_building')
                <div class="address__error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="address__submit-button">更新する</button>
    </form>
</div>
@endsection
