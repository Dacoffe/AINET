<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item_Ordered extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'discount',
        'subtotal'
    ];

    public function order():HasOne
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function product():HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
