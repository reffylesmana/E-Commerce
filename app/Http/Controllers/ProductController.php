<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter berdasarkan kategori jika ada
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter pencarian berdasarkan nama produk
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter urutan produk
        if ($request->has('sort') && $request->sort != '') {
            switch ($request->sort) {
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'cheapest':
                    $query->orderBy('price', 'ASC');
                    break;
                case 'expensive':
                    $query->orderBy('price', 'DESC');
                    break;
                case 'sold':
                    $query->orderBy('sold', 'desc');
                    break;
                default:
                    break;
            }
        }

        return view('products.index', [
            'title' => 'Data Produk',
            'categories' => Category::all(),
            'products' => $query->with(['photos'])->get(),
            'carts' => Cart::with(['product.photos'])->where('user_id', 2)->orderBy('created_at', 'DESC')->limit(5)->get(),
        ]);
    }

    public function show(Product $product)
    {
        return view('products.show', [
            'title' => $product->name,
            'categories' => Category::all(),
            'product' => $product->load('photos', 'category'),
            'productSuggets' => Product::with(['photos'])->where('category_id', $product->category_id)->where('id', '!=', $product->id)->get(),
            'carts' => Cart::with(['product.photos'])->where('user_id', 2)->orderBy('created_at', 'DESC')->limit(5)->get(),
        ]);
    }

}
