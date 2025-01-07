<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index()
    {
        return view('carts.index', [
            'title' => 'Keranjang',
            'carts' => Cart::with(['product.photos'])->where('user_id', 2)->orderBy('created_at', 'DESC')->get(),
        ]);
    }

    public function store(Request $request, Product $product)
    {
        Cart::create([
            'user_id' => 2,
            'product_id' => $product->id,
        ]);

        return redirect('/carts')->with('success', "Produk $product->name berhasil ditambahkan ke keranjang");
    }

}
