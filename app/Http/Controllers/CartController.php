<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index()
    {
        return view('carts', [
            'title' => 'Keranjang',
            'carts' => Cart::with(['product.photos'])
                ->where('user_id', Auth::id()) // Menggunakan ID pengguna yang sedang login
                ->orderBy('created_at', 'DESC')
                ->get(),
        ]);
    }

    public function store(Request $request, Product $product)
    {
        // Validasi jika diperlukan
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Cek apakah produk sudah ada di keranjang pengguna
        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            // Jika sudah ada, update jumlah
            $cart->increment('quantity', $request->quantity);
        } else {
            // Jika belum ada, tambahkan produk ke keranjang
            Cart::create([
                'user_id' => Auth::id(), // Menggunakan ID pengguna yang sedang login
                'product_id' => $product->id,
                'quantity' => $request->quantity, // Tambahkan kuantitas berdasarkan input
            ]);
        }

        return redirect('/carts')->with('success', "Produk $product->name berhasil ditambahkan ke keranjang");
    }
}