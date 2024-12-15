<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemCategory extends Pivot
{
    protected $table = 'item_category';

    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'category_id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

