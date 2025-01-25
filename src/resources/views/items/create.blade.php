@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
<div class="create-product">
    <h1 class="create-product__title">商品の出品</h1>

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="create-product__form" novalidate>
        @csrf
        <div class="create-product__image-section">
            <label class="create-product__section-title">商品画像</label>
            <div class="create-product__image-upload">
                <input type="file" name="item_image" id="itemImage" accept="image/jpeg,image/png" class="create-product__image-input" required>
                <label for="itemImage" class="create-product__image-button">画像を選択する</label>
                <img id="preview" src="" class="create-product__image-preview create-product__image-preview--hidden">
            </div>
            @error('item_image')
                <span class="create-product__error">{{ $message }}</span>
            @enderror
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
            <input type="hidden" name="selected_category" id="selectedCategory" required>
            @error('selected_category')
                <span class="create-product__error">{{ $message }}</span>
            @enderror
        </div>

        <div class="create-product__condition-section">
            <label class="create-product__section-title">商品の状態</label>
            <div class="condition-select-wrapper">
                <div class="condition-select" id="conditionSelect">
                    <div class="selected-option">選択してください</div>
                    <div class="select-arrow">▼</div>
                </div>
                <div class="condition-options" style="display: none;">
                    @foreach($conditions as $condition)
                        <div class="condition-option" data-value="{{ $condition }}">
                            <span class="checkmark">✓</span>
                            <span class="option-text">{{ $condition }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <input type="hidden" name="condition" id="conditionInput" value="" required>
            @error('condition')
                <span class="create-product__error">{{ $message }}</span>
            @enderror
        </div>

        <div class="create-product__divider">
            <h2 class="create-product__divider-title">商品名と説明</h2>
        </div>

        <div class="create-product__name-section">
            <label class="create-product__section-title">商品名</label>
            <input type="text" name="name" required class="create-product__input" value="{{ old('name') }}" maxlength="255">
            @error('name')
                <span class="create-product__error">{{ $message }}</span>
            @enderror
        </div>

        <div class="create-product__description-section">
            <label class="create-product__section-title">商品の説明</label>
            <textarea name="description" required class="create-product__textarea" maxlength="255">{{ old('description') }}</textarea>
            @error('description')
                <span class="create-product__error">{{ $message }}</span>
            @enderror
        </div>

        <div class="create-product__price-section">
            <label class="create-product__section-title">販売価格</label>
            <div class="create-product__price-input">
                <span class="create-product__currency">￥</span>
                <input type="number" name="price" required min="0" class="create-product__input" value="{{ old('price') }}">
            </div>
            @error('price')
                <span class="create-product__error">{{ $message }}</span>
            @enderror
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
document.addEventListener('DOMContentLoaded', function() {
    const conditionSelect = document.getElementById('conditionSelect');
    const conditionOptions = document.querySelector('.condition-options');
    const selectedOption = conditionSelect.querySelector('.selected-option');
    const options = document.querySelectorAll('.condition-option');
    const conditionInput = document.getElementById('conditionInput');

    conditionSelect.addEventListener('click', function() {
        conditionOptions.style.display = conditionOptions.style.display === 'none' ? 'block' : 'none';
    });

    options.forEach(option => {
        option.addEventListener('click', function() {
            const value = this.getAttribute('data-value');

            options.forEach(opt => {
                opt.classList.remove('selected');
                opt.querySelector('.checkmark').style.display = 'none';
            });

            this.classList.add('selected');
            this.querySelector('.checkmark').style.display = 'inline-block';

            selectedOption.textContent = value;
            conditionInput.value = value;

            conditionOptions.style.display = 'none';
        });
    });

    // クリック外での閉じる処理
    document.addEventListener('click', function(e) {
        if (!conditionSelect.contains(e.target) && !conditionOptions.contains(e.target)) {
            conditionOptions.style.display = 'none';
        }
    });
});
</script>
@endsection
