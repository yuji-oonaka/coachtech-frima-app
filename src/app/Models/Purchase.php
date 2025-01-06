<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method',
        'shipping_postal_code',
        'shipping_prefecture',
        'shipping_city',
        'shipping_street',
        'shipping_building',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getFullShippingAddressAttribute()
    {
        return "{$this->shipping_prefecture}{$this->shipping_city}{$this->shipping_street}{$this->shipping_building}";
    }
}
