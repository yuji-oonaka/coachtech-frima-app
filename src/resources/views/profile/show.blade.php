@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/show.css') }}">
@endsection

@section('content')
<div class="profile-container">
    <div class="user-info">
        <div class="profile-image">
            <img src="{{ $user->profile_img_url ?? asset('images/default-avatar.png') }}" alt="">
        </div>
        <h1 class="user-name">{{ $user->name }}</h1>
        <a href="{{ route('profile.edit') }}" class="edit-profile">プロフィールを編集</a>
    </div>

    <div class="item-tabs">
        <div class="tab {{ $tab === 'sell' ? 'active' : '' }}">
            <a href="{{ route('profile.show', ['tab' => 'sell']) }}">出品した商品</a>
        </div>
        <div class="tab {{ $tab === 'buy' ? 'active' : '' }}">
            <a href="{{ route('profile.show', ['tab' => 'buy']) }}">購入した商品</a>
        </div>
    </div>

    <h2>
        @if($tab === 'sell')
            出品した商品
        @elseif($tab === 'buy')
            購入した商品
        @endif
    </h2>

    <div class="items-grid">
        @forelse($items as $item)
        <a href="{{ route('items.show', $item->id) }}" class="item-card">
            <div class="item-image">
                @if($tab === 'sell' && $item->status === '売却済み')
                    <div class="sold-label">Sold</div>
                @endif
                <img src="{{ $item->img_url }}" alt="{{ $item->name }}">
            </div>
            <p class="item-name">{{ $item->name }}</p>
            <p class="item-price">¥{{ number_format($item->price) }}</p>
        </a>
        @empty
        <p>表示する商品がありません。</p>
        @endforelse
    </div>
</div>
@endsection
