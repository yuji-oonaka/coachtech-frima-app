<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

// 商品一覧画面（トップ画面）
Route::get('/', [ItemController::class, 'listItems'])->name('items.index');

// 商品検索
Route::get('/item/search', [ItemController::class, 'searchItems'])->name('items.search');

// 商品詳細画面
Route::get('/item/{item_id}', [ItemController::class, 'displayItemDetails'])->name('items.show');


// 認証が必要なルート
Route::middleware(['auth'])->group(function () {
    // 商品一覧画面（トップ画面）_マイリスト

    // 商品購入画面
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'showPurchaseForm'])->name('purchase.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'processPurchase'])->name('purchase.process');

    // 送付先住所変更画面
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');

    // 商品出品画面
    Route::get('/sell', [ItemController::class, 'showSellForm'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'createListing'])->name('items.store');

    // プロフィール関連
    Route::prefix('mypage')->group(function () {
        Route::get('/', [ProfileController::class, 'showProfile'])->name('profile.show');
        Route::get('/profile', [ProfileController::class, 'showEditForm'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::get('/purchases', [ProfileController::class, 'showPurchases'])->name('profile.purchases');
        Route::get('/listings', [ProfileController::class, 'showListings'])->name('profile.listings');
    });

    // いいね関連
    Route::post('/items/{item_id}/like', [LikeController::class, 'toggleLike'])->name('items.like');

    // コメント関連
    Route::post('/items/{item_id}/comment', [CommentController::class, 'postComment'])->name('comments.store');
    Route::get('/items/{item_id}/comments', [CommentController::class, 'listComments'])->name('comments.list');
});
