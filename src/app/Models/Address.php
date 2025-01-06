<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'postal_code',
        'address',
        'building'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // フルアドレスを取得するアクセサ
    public function getFullAddressAttribute()
    {
        return "{$this->prefecture}{$this->city}{$this->street}{$this->building}";
    }
}
