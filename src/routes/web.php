<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;

// 商品一覧表示（トップページ）
Route::get('/', [ItemController::class, 'listItems'])->name('items.index');

// 検索機能は認証不要なので、先に定義
Route::get('/items/search', [ItemController::class, 'searchItems'])->name('items.search');

Route::get('/items/mylist', [ItemController::class, 'listMyListItems'])->name('items.mylist');
// 認証が必要なルート
Route::middleware(['auth'])->group(function () {
    // マイリスト関連

    // マイページ関連
    Route::get('/mypage', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/mypage/profile', [ProfileController::class, 'showEditForm'])->name('profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // 商品出品関連
    Route::get('/items/create', [ItemController::class, 'showSellForm'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');

    // 購入関連
    Route::get('/purchase/{id}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{id}', [PurchaseController::class, 'store'])->name('purchase.store');

    // いいね関連
    Route::post('/items/{id}/like', [LikeController::class, 'toggleLike'])->name('items.like');

    // コメント関連
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/address/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/address/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/profile/address/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/address/update', [ProfileController::class, 'update'])->name('profile.update');
});

// 商品詳細は認証不要なので、最後に定義
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');
