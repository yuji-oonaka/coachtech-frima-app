@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
<div class="create-product">
    @if ($errors->any())
        <div class="create-product__alert create-product__alert--danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h1 class="create-product__title">商品の出品</h1>

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="create-product__form">
        @csrf
        <div class="create-product__image-section">
            <label class="create-product__section-title">商品画像</label>
            <div class="create-product__image-upload">
                <input type="file" name="item_image" id="itemImage" accept="image/*" class="create-product__image-input">
                <label for="itemImage" class="create-product__image-button">画像を選択する</label>
                <img id="preview" src="" class="create-product__image-preview create-product__image-preview--hidden">
            </div>
        </div>

        <div class="create-product__divider">
            <h2 class="create-product__divider-title">商品の詳細</h2>
        </div>

        <div class="create-product__category-section">
            <label class="create-product__section-title">カテゴリー</label>
            <div class="create-product__category-list">
                @foreach($categories as $category)
                    <label class="create-product__category-item" data-category-id="{{ $category->id }}">
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>
            <input type="hidden" name="selected_category" id="selectedCategory">
        </div>

        <div class="create-product__condition-section">
            <label class="create-product__section-title">商品の状態</label>
            <select name="condition" required class="create-product__select">
                <option value="" disabled selected>選択してください</option>
                @foreach($conditions as $condition)
                    <option value="{{ $condition }}">{{ $condition }}</option>
                @endforeach
            </select>
        </div>

        <div class="create-product__divider">
            <h2 class="create-product__divider-title">商品名と説明</h2>
        </div>

        <div class="create-product__name-section">
            <label class="create-product__section-title">商品名</label>
            <input type="text" name="name" required class="create-product__input">
        </div>

        <div class="create-product__description-section">
            <label class="create-product__section-title">商品の説明</label>
            <textarea name="description" required class="create-product__textarea"></textarea>
        </div>

        <div class="create-product__price-section">
            <label class="create-product__section-title">販売価格</label>
            <div class="create-product__price-input">
                <span class="create-product__currency">￥</span>
                <input type="number" name="price" required min="0" class="create-product__input">
            </div>
        </div>

        <button type="submit" class="create-product__submit">出品する</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('itemImage').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            preview.src = e.target.result;
            preview.classList.remove('create-product__image-preview--hidden');
            document.querySelector('.create-product__image-button').classList.add('create-product__image-button--hidden');
        }
        reader.readAsDataURL(file);
    }
});
document.querySelectorAll('.create-product__category-item').forEach(item => {
    item.addEventListener('click', function() {
        this.classList.toggle('create-product__category-item--selected');
        const selectedCategories = [];
        document.querySelectorAll('.create-product__category-item--selected').forEach(selected => {
            selectedCategories.push(selected.dataset.categoryId);
        });
        document.getElementById('selectedCategory').value = selectedCategories.join(',');
    });
});
</script>
@endsection
