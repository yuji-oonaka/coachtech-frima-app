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
    <div class="item">
        <div class="item-tabs">
            <ul class="item-tab-list">
                <li class="item-tab-item {{ $tab === 'sell' ? 'item-tab-item--active' : '' }}">
                    <a href="{{ route('profile.show', ['tab' => 'sell']) }}" class="item-tab-link">出品した商品</a>
                </li>
                <li class="item-tab-item {{ $tab === 'buy' ? 'item-tab-item--active' : '' }}">
                    <a href="{{ route('profile.show', ['tab' => 'buy']) }}" class="item-tab-link">購入した商品</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="items-grid">
        @forelse($items as $item)
        <a href="{{ route('items.show', $item->id) }}" class="items-grid__item-link">
            <div class="items-grid__item">
                <div class="items-grid__image-wrapper">
                    @if($tab === 'sell' && $item->status === '売却済み')
                        <div class="items-grid__sold-label">Sold</div>
                    @endif
                    <img src="{{ $item->img_url }}" alt="{{ $item->name }}" class="items-grid__image">
                </div>
                <p class="items-grid__name">{{ $item->name }}</p>
            </div>
        </a>
        @empty
        <p class="items-grid__no-items">表示する商品がありません。</p>
        @endforelse
    </div>
</div>
@endsection
