<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class LandingPageController extends Controller
{
    // Fungsi untuk menampilkan landing page
    public function show()
    {
        // Ambil semua produk dan kategori dari database
        $products = Product::latest()->take(8)->get(); 
        $categories = Category::all(); 

        // Kirim data produk dan kategori ke tampilan landing page
        return view('index', compact('products', 'categories'));
    }
}
