<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model for Order with reference to User
**/

class Order extends Model
{
    const UPDATED_AT = null;
    use HasFactory;

    protected $fillable = [
        'user_id', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

