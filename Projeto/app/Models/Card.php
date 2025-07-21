<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;


class Card extends Model
{
    protected $fillable = [
        'id',
        'card_number',
        'balance'
    ];

    public function operation():HasMany
    {
        return $this->hasMany(Operation::class, 'card_id', 'id');
    }

       public function user():HasOne
    {
        return $this->hasOne(User::class);
    }
    public function transactions()
    {
        return $this->hasMany(CardTransaction::class);
    }

    public function refund($amount, $orderId)
    {
        DB::transaction(function () use ($amount, $orderId) {
            $this->increment('balance', $amount);

            $this->transactions()->create([
                'amount' => $amount,
                'type' => 'refund',
                'description' => 'Order refund #'.$orderId
            ]);
        });
    }
}
