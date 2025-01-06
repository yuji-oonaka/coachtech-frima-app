<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemCategory extends Pivot
{
    use SoftDeletes;

    protected $table = 'item_category';

    public $timestamps = true;

    protected $fillable = [
        'item_id',
        'category_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
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
