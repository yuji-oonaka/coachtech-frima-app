@extends('layouts.app')

@section('title', "マイページ")

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/show.css') }}">
@endsection

@section('content')
<div class="profile">
    <div class="profile__user-info">
        <div class="profile__image">
            <img src="{{ asset('storage/' . $user->profile_img_url) }}" alt="" class="profile__image-img">
        </div>
        <h1 class="profile__user-name">{{ $user->name }}</h1>
        <a href="{{ route('profile.edit') }}" class="profile__edit-button">プロフィールを編集</a>
    </div>
    <div class="profile__item">
        <div class="profile__tabs">
            <ul class="profile__tab-list">
                <li class="profile__tab-item {{ $tab === 'sell' ? 'profile__tab-item--active' : '' }}">
                    <a href="{{ route('profile.show', ['tab' => 'sell']) }}" class="profile__tab-link">出品した商品</a>
                </li>
                <li class="profile__tab-item {{ $tab === 'buy' ? 'profile__tab-item--active' : '' }}">
                    <a href="{{ route('profile.show', ['tab' => 'buy']) }}" class="profile__tab-link">購入した商品</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="profile__items-grid">
        @forelse($items as $item)
        <a href="{{ route('items.show', $item->id) }}" class="profile__item-link">
            <div class="profile__item-card">
                <div class="profile__item-image-wrapper">
                    @if($tab === 'sell' && $item->status === '売却済み')
                        <div class="profile__item-sold-label">Sold</div>
                    @endif
                    <img src="{{ $item->img_url }}" alt="{{ $item->name }}" class="profile__item-image">
                </div>
                <p class="profile__item-name">{{ $item->name }}</p>
            </div>
        </a>
        @empty
        <p class="profile__no-items">表示する商品がありません。</p>
        @endforelse
    </div>
</div>
@endsection
