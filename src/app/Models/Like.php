<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'created_at',
        'updated_at'
    ];

    /**
     * ユーザーとのリレーション
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 商品とのリレーション
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * いいねの重複チェック
     */
    public static function isLikedBy($userId, $itemId)
    {
        return static::where('user_id', $userId)
            ->where('item_id', $itemId)
            ->exists();
    }
}
