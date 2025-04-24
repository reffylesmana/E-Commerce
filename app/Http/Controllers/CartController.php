<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melihat keranjang belanja Anda.');
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        // Hitung subtotal
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Ambil diskon dari session
        $discount = Session::get('discount');
        $discountValue = 0;

        // Hitung nilai diskon
        if ($discount) {
            if ($discount['type'] === 'percentage') {
                $discountValue = $subtotal * ($discount['value'] / 100);
            } else {
                $discountValue = $discount['value'];
            }
        }

        $shipping = $cartItems->count() > 0 ? 20000 : 0;
        $tax = ($subtotal - $discountValue) * 0.11;
        $total = $subtotal - $discountValue + $shipping + $tax;

        return view('carts', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total', 'discount', 'discountValue'));
    }

    /**
     * Apply discount code
     */
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $discount = Discount::where('code', $request->code)->where('is_active', true)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();

        if (!$discount) {
            return back()->with('error', 'Kode voucher tidak valid atau telah kadaluarsa');
        }

        if ($discount->max_uses > 0 && $discount->used_count >= $discount->max_uses) {
            return back()->with('error', 'Voucher telah mencapai batas penggunaan');
        }

        Session::put('discount', [
            'id' => $discount->id,
            'name' => $discount->name,
            'code' => $discount->code,
            'type' => $discount->type,
            'value' => $discount->value,
        ]);

        return back()->with('success', 'Voucher berhasil digunakan');
    }

    /**
     * Remove discount from session
     */
    public function removeDiscount()
    {
        Session::forget('discount');
        return back()->with('success', 'Voucher berhasil dihapus');
    }

    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $productId = $request->product_id;
        $quantity = $request->quantity;
        $user = Auth::user();
    
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk menambahkan produk ke keranjang');
        }
    
        $product = Product::with('seller')->findOrFail($productId);
    
        if ($product->stock < $quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi');
        }
    
        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('product_id', $productId)
                        ->first();
    
        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }
    
        // Hanya kirim notifikasi ke seller produk
        if ($product->seller && $product->seller->role === 'seller') {
            $message = 'User ' . $user->name . ' telah menambahkan ' . $quantity . ' produk ' . $product->name . ' ke keranjang.';
            
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => 'App\Notifications\ProductAddedToCart', // Tetap menggunakan format type
                'notifiable_id' => $product->seller->id, // Seller yang menerima notifikasi
                'notifiable_type' => 'App\Models\User',
                'data' => json_encode([
                    'message' => $message,
                    'product_id' => $product->id,
                    'buyer_id' => $user->id,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    
        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    /**
     * Update quantity of product in cart
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::findOrFail($id);

        if ($cartItem->user_id !== Auth::id()) {
            return redirect()->route('carts')->with('error', 'Anda tidak memiliki akses untuk mengubah item ini');
        }

        if ($cartItem->product->stock < $request->quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi');
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return back()->with('success', 'Jumlah produk berhasil diperbarui');
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cartItem = Cart::findOrFail($id);

        if ($cartItem->user_id !== Auth::id()) {
            return redirect()->route('carts')->with('error', 'Anda tidak memiliki akses untuk menghapus item ini');
        }

        $cartItem->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Keranjang belanja berhasil dikosongkan');
    }
}
