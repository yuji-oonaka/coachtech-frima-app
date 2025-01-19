@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
<div class="product-listing">
    <div class="product-listing__tabs">
        <ul class="product-listing__tab-list">
            <li class="product-listing__tab-item {{ $tab !== 'mylist' ? 'product-listing__tab-item--active' : '' }}">
                <a href="{{ request('keyword') ? route('items.index', ['keyword' => request('keyword')]) : route('items.index') }}" class="product-listing__tab-link">おすすめ</a>
            </li>
            <li class="product-listing__tab-item {{ $tab === 'mylist' ? 'product-listing__tab-item--active' : '' }}">
                <a href="{{ request('keyword') ? route('items.index', ['keyword' => request('keyword'), 'tab' => 'mylist']) : route('items.index', ['tab' => 'mylist']) }}" class="product-listing__tab-link">マイリスト</a>
            </li>
        </ul>
    </div>

    @if(isset($keyword) && $keyword)
        <div class="product-listing__search-result">
            <p class="product-listing__search-message">「{{ $keyword }}」の検索結果</p>
        </div>
    @endif

    <div class="product-listing__grid">
        @if($tab === 'mylist')
            @auth
                @if($items->isEmpty())
                    <p class="product-listing__no-items">マイリストに商品が登録されていません</p>
                @else
                    @foreach ($items as $item)
                    <a href="{{ route('items.show', $item->id) }}" class="product-listing__item-link">
                        <div class="product-listing__item">
                            <div class="product-listing__image-wrapper">
                                @if($item->status === '売却済み')
                                    <div class="product-listing__sold-label">Sold</div>
                                @endif
                                <img src="{{ $item->img_url }}" alt="{{ $item->name }}" class="product-listing__image">
                            </div>
                            <p class="product-listing__name">{{ $item->name }}</p>
                            <p class="product-listing__price">¥{{ number_format($item->price) }}</p>
                        </div>
                    </a>
                    @endforeach
                @endif
            @else
                <p class="product-listing__no-items">マイリストを利用するにはログインが必要です</p>
            @endauth
        @else
            @if($items->isEmpty())
                <p class="product-listing__no-items">検索結果がありません</p>
            @else
                @foreach ($items as $item)
                <a href="{{ route('items.show', $item->id) }}" class="product-listing__item-link">
                    <div class="product-listing__item">
                        <div class="product-listing__image-wrapper">
                            @if($item->status === '売却済み')
                                <div class="product-listing__sold-label">Sold</div>
                            @endif
                            <img src="{{ $item->img_url }}" alt="{{ $item->name }}" class="product-listing__image">
                        </div>
                        <p class="product-listing__name">{{ $item->name }}</p>
                        <p class="product-listing__price">¥{{ number_format($item->price) }}</p>
                    </div>
                </a>
                @endforeach
            @endif
        @endif
    </div>
</div>
@endsection
