<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->get();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $discount = 0; // Implement your discount logic here
        $total = $subtotal - $discount;

        return view('carts', compact('cartItems', 'subtotal', 'discount', 'total'));
    }

    public function update(Request $request, Cart $item)
    {
        $item->update(['quantity' => $request->quantity]);
        return back();
    }

    public function remove(Cart $item)
    {
        $item->delete();
        return back();
    }

    public function applyCoupon(Request $request)
    {
        // Implement your coupon logic here
        return back();
    }
}