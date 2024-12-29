@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
<div class="item-detail">
    <div class="item-left">
        <div class="item-image">
            @if($item->status === '売却済み')
                <div class="sold-label">Sold</div>
            @endif
            <img src="{{ $item->img_url }}" alt="{{ $item->name }}">
        </div>
    </div>

    <div class="item-right">
        <div class="product-title">
            <h1 class="item-name">{{ $item->name }}</h1>
            <p class="brand-name">{{ $item->brand_name }}</p>
            <div class="price">
                <span class="price-number"><span class="price-mark">¥</span>{{ number_format($item->price) }}</span>
                <span class="price-tax">(税込)</span>
            </div>
        </div>

        <div class="social-actions">
            @auth
                <div class="action-item">
                    <button class="like-button {{ $isLiked ? 'active' : '' }}" data-item-id="{{ $item->id }}">
                        <span class="like-icon">☆</span>
                        <span class="count">{{ $likeCount }}</span>
                    </button>
                </div>
            @else
                <div class="action-item">
                    <button onclick="requireLogin()" class="like-button">
                        <span class="like-icon">☆</span>
                        <span class="count">{{ $likeCount }}</span>
                    </button>
                </div>
            @endauth
            <div class="action-item">
                <span class="comment-icon">💬</span>
                <span class="count">{{ $commentCount }}</span>
            </div>
        </div>

        @auth
            <a href="{{ route('purchase.show', $item->id) }}" class="purchase-button">
                購入手続きへ
            </a>
        @else
            <button class="purchase-button disabled" disabled>
                購入手続きへ
            </button>
        @endauth

        <div class="product-description">
            <h2>商品説明</h2>
            <div class="description-content">
                {!! nl2br(e($item->description)) !!}
            </div>
        </div>

        <div class="product-info">
            <h2>商品の情報</h2>
            <div class="info-row">
                <span class="label">カテゴリー</span>
                <div class="category-tags">
                    @foreach($item->categories as $category)
                        <span class="category-tag">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>
            <div class="info-row">
                <span class="label">商品の状態</span>
                <span class="value">{{ $item->condition }}</span>
            </div>
        </div>

        <div class="comments-section">
            <h2>コメント({{ $commentCount }})</h2>
            <div class="comments-list">
                @foreach($item->comments as $comment)
                    <div class="comment">
                        <div class="comment-user">
                            <img src="{{ $comment->user->profile_img_url ?? asset('images/default-avatar.png') }}" alt="ユーザーアイコン" class="user-avatar">
                            <span class="user-name">{{ $comment->user->name }}</span>
                        </div>
                        <p class="comment-text">{{ $comment->content }}</p>
                    </div>
                @endforeach
            </div>

            @auth
                <div class="comment-form">
                    <h3>商品へのコメント</h3>
                    <form action="{{ route('comments.store') }}" method="POST" novalidate>
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <div class="form-group">
                            <textarea
                                name="content"
                                class="@error('content') is-invalid @enderror"
                                required
                            >{{ old('content') }}</textarea>
                            @error('content')
                                <div class="error-message">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="comment-submit">コメントを送信する</button>
                    </form>
                </div>
            @else
                <div class="comment-form">
                    <h3>商品へのコメント</h3>
                    <textarea
                        class="disabled"
                        placeholder="コメントを入力してください"
                        disabled
                    ></textarea>
                    <button class="comment-submit disabled" disabled>
                        コメントを送信する
                    </button>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.itemId;

            fetch(`/items/${itemId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.action === 'liked') {
                    this.classList.add('active');
                } else {
                    this.classList.remove('active');
                }
                this.querySelector('.count').textContent = data.likeCount;
            })
            .catch(error => console.error('Error:', error));
        });
    });
});

function requireLogin() {
    window.location.href = '{{ route('login') }}';
}
</script>
@endsection
