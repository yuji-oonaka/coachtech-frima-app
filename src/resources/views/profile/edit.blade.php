@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">
@endsection

@section('content')
<div class="profile-edit-container">
    <h1 class="page-title">プロフィール設定</h1>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')
        <div class="profile-image-section">
            <div class="profile-image">
                <img id="preview" src="{{ $user->profile_img_url ?? asset('images/default-avatar.png') }}" alt="">
            </div>
            <div class="image-upload">
                <input type="file" name="profile_image" id="profileImage" accept="image/*" class="hidden">
                <label for="profileImage" class="select-image-button">画像を選択する</label>
            </div>
            @error('profile_image')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text"
                id="name"
                name="name"
                value="{{ old('name', $user->name) }}"
                required>
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text"
                id="postal_code"
                name="postal_code"
                value="{{ old('postal_code', $user->address->postal_code ?? '') }}"
                pattern="\d{3}-\d{4}"
                placeholder="123-4567"
                required>
            @error('postal_code')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text"
                id="address"
                name="address"
                value="{{ old('address', $user->address->address ?? '') }}"
                required>
            @error('address')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text"
                id="building"
                name="building"
                value="{{ old('building', $user->address->building ?? '') }}">
            @error('building')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="update-button">更新する</button>
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
