<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Operation extends Model
{
    protected $fillable = [
        'type',
        'value',
        'payment_type',
        'payment_reference',
    ];

    public function order():HasOne
    {
        return $this->hasOne(Order::class, 'order_id', 'id');
    }

    public function card():HasOne
    {
        return $this->hasOne(Card::class, 'card_id', 'id');
    }
}
