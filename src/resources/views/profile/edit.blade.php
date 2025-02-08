@extends('layouts.app')

@section('title', "プロフィール設定")

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">
@endsection

@section('content')
<div class="profile-edit">
    <h1 class="profile-edit__title">プロフィール設定</h1>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate class="profile-edit__form">
        @csrf
        @method('PUT')
        <input type="hidden" name="prefix" value="">
        <div class="profile-edit__image-section">
            <div class="profile-edit__image">
                <img id="preview" src="{{ $user->profile_img_url ? asset('storage/' . $user->profile_img_url) : asset('images/default-avatar.png') }}" alt="" class="profile-edit__image-preview">
            </div>
            <div class="profile-edit__image-upload">
                <input type="file" name="profile_image" id="profileImage" accept="image/*" class="profile-edit__image-input">
                <label for="profileImage" class="profile-edit__image-button">画像を選択する</label>
            </div>
            @error('profile_image')
                <span class="profile-edit__error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="profile-edit__form-group">
            <label for="name" class="profile-edit__label">ユーザー名</label>
            <input type="text"
                id="name"
                name="name"
                value="{{ old('name', $user->name) }}"
                required
                class="profile-edit__input">
            @error('name')
                <span class="profile-edit__error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="profile-edit__form-group">
            <label for="postal_code" class="profile-edit__label">郵便番号</label>
            <input type="text"
                id="postal_code"
                name="postal_code"
                value="{{ old('postal_code', $user->address->postal_code ?? '') }}"
                pattern="\d{3}-\d{4}"
                placeholder="123-4567"
                required
                class="profile-edit__input">
            @error('postal_code')
                <span class="profile-edit__error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="profile-edit__form-group">
            <label for="address" class="profile-edit__label">住所</label>
            <input type="text"
                id="address"
                name="address"
                value="{{ old('address', $user->address->address ?? '') }}"
                required
                class="profile-edit__input">
            @error('address')
                <span class="profile-edit__error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="profile-edit__form-group">
            <label for="building" class="profile-edit__label">建物名</label>
            <input type="text"
                id="building"
                name="building"
                value="{{ old('building', $user->address->building ?? '') }}"
                class="profile-edit__input">
            @error('building')
                <span class="profile-edit__error-message">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="profile-edit__submit-button">更新する</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('profileImage').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
