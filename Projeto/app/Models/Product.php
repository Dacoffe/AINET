<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'description',
        'photo',
        'discount_min_qty',
        'discount',
        'stock_lower_limit',
        'stock_upper_limit',
        'category_id',
    ];
    
    public function getImageUrlAttribute()
    {
        if ($this->photo && Storage::disk('public')->exists("products/{$this->photo}")) {
            return asset("storage/products/{$this->photo}");
        } else {
            return asset("storage/products/product_no_image.png");
        }
    }

    public function items_order(): HasMany
    {
        return $this->hasMany(Order::class, 'product_id', 'id');
    }

    public function category():HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function stock_adjustment():HasMany
    {
        return $this->hasMany(Stock_Adjustment::class, 'product_id', 'id');
    }

    public function supply_order():HasMany
    {
        return $this->hasMany(Supply_Order::class, 'product_id', 'id');
    }
    public function orders()
    {
       return $this->belongsToMany(Order::class)
           ->withPivot('quantity', 'unit_price', 'discount', 'subtotal')
           ->withTimestamps();
    }
}
