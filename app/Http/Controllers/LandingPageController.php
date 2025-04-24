<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Blog;

class LandingPageController extends Controller
{

    public function show()
    {
        $products = Product::latest()->take(8)->get();
    
        // Tambahkan deskripsi singkat (12 kata)
        $products->map(function ($product) {
            $words = explode(' ', strip_tags($product->description));
            $short = implode(' ', array_slice($words, 0, 12));
            $product->short_description = $short . (count($words) > 12 ? '...' : '');
            return $product;
        });
    
        $categories = Category::all();
        $reviews = Review::with(['user'])->latest()->get();
        $banners = Banner::active()->ordered()->get();
        $blogs = Blog::where('is_published', 1)
        ->whereNotNull('published_at')
        ->orderByDesc('published_at')
        ->take(3)
        ->get();
    
        return view('index', compact('products', 'categories', 'reviews', 'banners', 'blogs'));
    }
    
}
