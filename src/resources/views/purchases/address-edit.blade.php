@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/address.css') }}">
@endsection

@section('content')
<div class="address-container">
    <h1 class="page-title">配送先住所の変更</h1>
    <form action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}" method="POST" class="address-form" novalidate>
        @csrf
        <input type="hidden" name="prefix" value="shipping_">
        <div class="form-group">
            <label for="shipping_postal_code">郵便番号</label>
            <input
                type="text"
                id="shipping_postal_code"
                name="shipping_postal_code"
                value="{{ old('shipping_postal_code', session('shipping_address.postal_code') ?? $user->address->postal_code ?? '') }}"
                class="form-input @error('shipping_postal_code') is-invalid @enderror"
                required
                pattern="\d{3}-\d{4}"
                placeholder="123-4567"
            >
            @error('shipping_postal_code')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="shipping_address">住所</label>
            <input
                type="text"
                id="shipping_address"
                name="shipping_address"
                value="{{ old('shipping_address', session('shipping_address.address') ?? $user->address->address ?? '') }}"
                class="form-input @error('shipping_address') is-invalid @enderror"
                required
            >
            @error('shipping_address')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="shipping_building">建物名（任意）</label>
            <input
                type="text"
                id="shipping_building"
                name="shipping_building"
                value="{{ old('shipping_building', session('shipping_address.building') ?? $user->address->building ?? '') }}"
                class="form-input @error('shipping_building') is-invalid @enderror"
                placeholder="建物名を入力（任意）"
            >
            @error('shipping_building')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="update-button">更新する</button>
    </form>
</div>
@endsection
