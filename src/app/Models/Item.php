<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand_name',
        'img_url',
        'description',
        'price',
        'condition',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy($user)
    {
        if ($user) {
            return Like::where('user_id', $user->id)
                    ->where('item_id', $this->id)
                    ->exists();
        }
        return false;
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}

