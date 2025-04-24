<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'photo'];

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_category');
    }

    public function photos()
    {
        return $this->hasMany(CategoryPhoto::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'discount_category');
    }

}
