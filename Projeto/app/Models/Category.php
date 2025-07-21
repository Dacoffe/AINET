<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'image',
    ];
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists("categories/{$this->image}")) {
            return asset("storage/categories/{$this->image}");
        } else {
            return asset("storage/categories/category_no_image.png");
        }
    }


    public function products():HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}
