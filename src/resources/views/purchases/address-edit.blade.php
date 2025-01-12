@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/address.css') }}">
@endsection

@section('content')
<div class="address-container">
    <h1 class="page-title">住所の変更</h1>
    <form action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}" method="POST" class="address-form" novalidate>
        @csrf
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input
                type="text"
                id="postal_code"
                name="postal_code"
                value="{{ old('postal_code', $user->address->postal_code ?? '') }}"
                class="form-input @error('postal_code') is-invalid @enderror"
                required
                pattern="\d{3}-\d{4}"
                placeholder="123-4567"
            >
            @error('postal_code')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input
                type="text"
                id="address"
                name="address"
                value="{{ old('address', $user->address->address ?? '') }}"
                class="form-input @error('address') is-invalid @enderror"
                required
            >
            @error('address')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名（任意）</label>
            <input
                type="text"
                id="building"
                name="building"
                value="{{ old('building', $user->address->building ?? '') }}"
                class="form-input @error('building') is-invalid @enderror"
                placeholder="建物名を入力（任意）"
            >
            @error('building')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="update-button">更新する</button>
    </form>
</div>
@endsection
