@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
<div class="product-listing">
    <div class="tabs">
        <ul>
            <li class="{{ !request()->routeIs('items.mylist') ? 'active' : '' }}">
                <a href="{{ route('items.index') }}">おすすめ</a>
            </li>
            <li class="{{ request()->routeIs('items.mylist') ? 'active' : '' }}">
                <a href="{{ route('items.mylist') }}">マイリスト</a>
            </li>
        </ul>
    </div>

    @if(isset($keyword) && $keyword)
        <div class="search-result-message">
            <p>「{{ $keyword }}」の検索結果</p>
        </div>
    @endif

    <div class="product-grid">
        @if(request()->routeIs('items.mylist'))
            @auth
                @if($items->isEmpty())
                    <p class="no-items-message">マイリストに商品が登録されていません</p>
                @else
                    @foreach ($items as $item)
                    <div class="product-item">
                        <div class="product-image-wrapper">
                            @if($item->status === '売却済み')
                                <div class="sold-label"></div>
                            @endif
                            <img src="{{ $item->img_url }}" alt="{{ $item->name }}" class="product-image">
                        </div>
                        <p class="product-name">{{ $item->name }}</p>
                        <p class="product-price">¥{{ number_format($item->price) }}</p>
                    </div>
                    @endforeach
                @endif
            @else
            @endauth
        @else
            @if($items->isEmpty())
                <p class="no-items-message">検索結果がありません</p>
            @else
                @foreach ($items as $item)
                <div class="product-item">
                    <div class="product-image-wrapper">
                        @if($item->status === '売却済み')
                            <div class="sold-label">
                                Sold
                            </div>
                        @endif
                        <img src="{{ $item->img_url }}" alt="{{ $item->name }}" class="product-image">
                    </div>
                    <p class="product-name">{{ $item->name }}</p>
                    <p class="product-price">¥{{ number_format($item->price) }}</p>
                </div>
                @endforeach
            @endif
        @endif
    </div>
</div>
@endsection
