<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings_shipping_costs extends Model
{
    protected $fillable = [
        'shipping_cost',
        'min_value_threshold',
        'max_value_threshold'
    ];
}
