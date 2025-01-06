@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
<div class="create-container">
    <h1 class="page-title">商品の出品</h1>

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 商品画像エリア -->
        <div class="exhibited-products-image">
            <label class="section-title">商品画像</label>
            <div class="image-upload-area">
                <input type="file" name="item_image" id="itemImage" accept="image/*" class="hidden">
                <label for="itemImage" class="select-image-button">画像を選択する</label>
                <img id="preview" src="" class="hidden">
            </div>
        </div>

        <div class="product-details-divider">
            <h2 class="product-details-title"> 商品の詳細</h2>
        </div>

        <!-- カテゴリーエリア -->
        <div class="exhibited-product-category-area">
            <label class="section-title">カテゴリー</label>
            <div class="category-items">
                @foreach($categories as $category)
                    <label class="category-item" data-category-id="{{ $category->id }}">
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>
            <input type="hidden" name="selected_category" id="selectedCategory">
        </div>

        <!-- 商品の状態 -->
        <div class="exhibited-product-status">
            <label class="section-title">商品の状態</label>
            <select name="condition" required>
                <option value="" disabled selected>選択してください</option>
                <option value="新品">新品</option>
                <option value="未使用">未使用</option>
                <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                <option value="傷や汚れあり">傷や汚れあり</option>
                <option value="全体的に状態が悪い">全体的に状態が悪い</option>
            </select>
        </div>

        <div class="product-details-divider">
            <h2 class="product-details-title">商品名と説明</h2>
        </div>

        <!-- 商品名 -->
        <div class="product-name">
            <label class="section-title">商品名</label>
            <input type="text" name="name" required>
        </div>

        <!-- 商品の説明 -->
        <div class="product-description">
            <label class="section-title">商品の説明</label>
            <textarea name="description" required></textarea>
        </div>

        <!-- 販売価格 -->
        <div class="product-price">
            <label class="section-title">販売価格</label>
            <div class="price-input">
                <span class="currency">￥</span>
                <input type="number" name="price" required min="0">
            </div>
        </div>

        <button type="submit" class="submit-button">出品する</button>
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
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
});
document.querySelectorAll('.category-item').forEach(item => {
    item.addEventListener('click', function(e) {
        const checkbox = this.querySelector('input[type="checkbox"]');
        this.classList.toggle('selected');
        checkbox.checked = !checkbox.checked;
        e.preventDefault();
    });
});

</script>
@endsection
