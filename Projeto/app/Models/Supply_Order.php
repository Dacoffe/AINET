<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply_Order extends Model
{
    protected $table = 'supply_orders'; 

    protected $fillable = [
        'product_id',
        'registered_by_user_id',
        'status',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }
}
