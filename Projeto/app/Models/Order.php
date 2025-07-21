<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    //
    protected $fillable = [
    'member_id', 'status', 'date', 'total_items',
    'shipping_cost', 'total', 'nif', 'delivery_address',
    'pdf_receipt', 'cancel_reason', 'custom','status',
];

    public function user():HasOne
    {
        return $this->hasOne(User::class, 'id', 'member_id');
    }

    public function operation():HasMany
    {
        return $this->hasMany(Operation::class, 'order_id', 'id');
    }

    public function products():BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'items_orders')->withPivot(['quantity', 'unit_price', 'discount', 'subtotal']);
    }
     public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
