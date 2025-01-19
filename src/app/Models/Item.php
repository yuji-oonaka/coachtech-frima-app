<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use HasFactory, SoftDeletes;

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

    protected $casts = [
        'price' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category')->withTimestamps();
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
        return $user ? $this->likes()->where('user_id', $user->id)->exists() : false;
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public static function getEnumValues($column)
    {
        $instance = new static;
        $table = $instance->getTable();

        $columnInfo = DB::select("SHOW COLUMNS FROM `{$table}` WHERE Field = ?", [$column]);

        if (empty($columnInfo)) {
            return [];
        }

        $type = $columnInfo[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);

        if (empty($matches)) {
            return [];
        }

        $enum = array_map(function($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        return $enum;
    }
}
