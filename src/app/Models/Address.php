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
}
