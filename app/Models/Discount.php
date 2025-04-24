<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Discount extends Model
{
    use SoftDeletes; 
    protected $fillable = ['name', 'code', 'type', 'value', 'start_date', 'end_date', 'max_uses', 'used_count', 'is_active', 'store_id'];

    protected $dates = ['start_date', 'end_date'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'discount_category');
    }

    public function products()
    {
        return Product::where('store_id', $this->store_id)->whereHas('categories', function ($query) {
            $query->whereIn('categories.id', $this->categories->pluck('id'));
        });
    }

    public function isApplicable(Product $product)
    {
        return $product->store_id === $this->store_id && $this->categories->contains($product->category_id);
    }

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'max_uses' => 'integer',
    ];
}
