<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'alamat', 'logo', 'user_id', 'created_by', 'is_approved', 'is_official', 'is_active', 'is_banned', 'banned_until', 'violation_count', 'last_violation_reason', 'last_violation_at'];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_official' => 'boolean',
        'is_active' => 'boolean',
        'banned_until' => 'datetime',
        'is_banned' => 'boolean',
        'last_violation_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'store_category')->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($store) {
            if (empty($store->slug)) {
                $store->slug = Str::slug($store->name);
            }
        });
    }

    public function canManageBanners()
    {
        return $this->is_official;
    }

    public function canManageBlogs()
    {
        return $this->is_official;
    }

    public function canManageDiscounts()
    {
        return $this->is_official;
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }
}
