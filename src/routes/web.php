<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ItemController;

// 商品一覧表示（トップページ）
Route::get('/', [ItemController::class, 'listItems'])->name('items.index');

// 商品検索
Route::get('/items/search', [ItemController::class, 'searchItems'])->name('items.search');

// 認証が必要なルート
Route::middleware(['auth'])->group(function () {
    // マイリスト表示
    Route::get('/items/mylist', [ItemController::class, 'listMyListItems'])->name('items.mylist');
});
