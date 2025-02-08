@extends('layouts.app')

@section('title', "{$item->name} - 商品詳細")

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
    <div class="item-detail">
        <div class="item-detail__left">
            <div class="item-detail__image">
                @if($item->status === '売却済み')
                    <div class="item-detail__sold-label">Sold</div>
                @endif
                <img class="item-detail__image-content" src="{{ $item->img_url }}" alt="{{ $item->name }}">
            </div>
        </div>

        <div class="item-detail__right">
            <div class="item-detail__title">
                <h1 class="item-detail__name">{{ $item->name }}</h1>
                <p class="item-detail__brand">{{ $item->brand_name }}</p>
                <div class="item-detail__price">
                    <span class="item-detail__price-number"><span class="item-detail__price-mark">¥</span>{{ number_format($item->price) }}</span>
                    <span class="item-detail__price-tax">(税込)</span>
                </div>
            </div>

            <div class="item-detail__social-actions">
                @auth
                    <div class="item-detail__action-item">
                        <button class="item-detail__like-button {{ $isLiked ? 'item-detail__like-button--active' : '' }}" data-item-id="{{ $item->id }}">
                            <span class="item-detail__like-icon">☆</span>
                            <span class="item-detail__count">{{ $likeCount }}</span>
                        </button>
                    </div>
                @else
                    <div class="item-detail__action-item">
                        <button onclick="requireLogin()" class="item-detail__like-button">
                            <span class="item-detail__like-icon">☆</span>
                            <span class="item-detail__count">{{ $likeCount }}</span>
                        </button>
                    </div>
                @endauth
                <div class="item-detail__action-item">
                    <span class="item-detail__comment-icon" data-scroll-target="comments">💬</span>
                    <span class="item-detail__count">{{ $commentCount }}</span>
                </div>
            </div>

            @auth
                @if($item->user_id === Auth::id())
                    <button class="item-detail__purchase-button item-detail__purchase-button--unavailable" disabled>
                        自分が出品した商品です
                    </button>
                @elseif($item->status === '売却済み')
                    <button class="item-detail__purchase-button item-detail__purchase-button--sold-out" disabled>
                        売り切れました
                    </button>
                @else
                    <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}" class="item-detail__purchase-button">
                        購入手続きへ
                    </a>
                @endif
            @else
                @if($item->status === '売却済み')
                    <button class="item-detail__purchase-button item-detail__purchase-button--sold-out" disabled>
                        売り切れました
                    </button>
                @else
                    <a href="{{ route('login') }}" class="item-detail__purchase-button">
                        購入手続きへ
                    </a>
                @endif
            @endauth
            <div class="item-detail__description">
                <h2 class="item-detail__section-title">商品説明</h2>
                <div class="item-detail__description-content">
                    {!! nl2br(e($item->description)) !!}
                </div>
            </div>

            <div class="item-detail__info">
                <h2 class="item-detail__section-title">商品の情報</h2>
                <div class="item-detail__info-row">
                    <span class="item-detail__label">カテゴリー</span>
                    <div class="item-detail__category-tags">
                        @foreach($item->categories as $category)
                            <span class="item-detail__category-tag">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="item-detail__info-row">
                    <span class="item-detail__label">商品の状態</span>
                    <span class="item-detail__value">{{ $item->condition }}</span>
                </div>
            </div>

            <div class="item-detail__comments">
                <h2 class="item-detail__section-title">コメント({{ $commentCount }})</h2>
                <div class="item-detail__comments-list">
                    @foreach($item->comments as $comment)
                        <div class="item-detail__comment">
                            <div class="item-detail__comment-user">
                                <img src="{{ $comment->user->profile_img_url ?? asset('images/default-avatar.png') }}" alt="" class="item-detail__user-avatar">
                                <span class="item-detail__user-name">{{ $comment->user->name }}</span>
                            </div>
                            <p class="item-detail__comment-text">{!! nl2br(e($comment->content)) !!}</p>
                        </div>
                    @endforeach
                </div>

                @auth
                    <div class="item-detail__comment-form">
                        <h3 class="item-detail__form-title">商品へのコメント</h3>
                        <form action="{{ route('comments.store', ['item_id' => $item->id]) }}" method="POST" novalidate>
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <div class="item-detail__form-group">
                                <textarea
                                    name="content"
                                    class="item-detail__comment-input @error('content') item-detail__comment-input--invalid @enderror"
                                    required
                                >{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="item-detail__error-message">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="item-detail__comment-submit">コメントを送信する</button>
                        </form>
                    </div>
                @else
                    <div class="item-detail__comment-form">
                        <h3 class="item-detail__form-title">商品へのコメント</h3>
                        <form action="{{ route('login') }}" method="GET">
                            <textarea
                                name="content"
                                class="item-detail__comment-input"
                            >{{ old('content') }}</textarea>
                            <button type="submit" class="item-detail__comment-submit">
                                コメントを送信する
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentIcon = document.querySelector('.item-detail__comment-icon');
    const commentsSection = document.querySelector('.item-detail__comments');

    if (commentIcon && commentsSection) {
        commentIcon.addEventListener('click', function(e) {
            e.preventDefault();
            commentsSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    }
});

    document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.querySelectorAll('.item-detail__like-button').forEach(button => {
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
                    this.classList.add('item-detail__like-button--active');
                } else {
                    this.classList.remove('item-detail__like-button--active');
                }
                this.querySelector('.item-detail__count').textContent = data.likeCount;
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
