<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Stock_Adjustment extends Model
{

    public function product():HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function user():HasOne
    {
        return $this->hasOne(User::class, 'id', 'registered_by_user_id');
    }
}
