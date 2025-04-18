<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Get cart items with product details
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();
            
        // Calculate cart totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }
        
        $shipping = $cartItems->count() > 0 ? 20000 : 0;
        $tax = $subtotal * 0.11;
        $total = $subtotal + $shipping + $tax;
        
        return view('carts', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    /**
     * Add a product to the cart
     */
    public function add(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        // Get the product ID and quantity from the request
        $productId = $request->product_id;
        $quantity = $request->quantity;
    
        // Check if the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk menambahkan produk ke keranjang');
        }
    
        // Retrieve the product from the database
        $product = Product::findOrFail($productId);
    
        // Check if the requested quantity is available in stock
        if ($product->stock < $quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi');
        }
    
        // Check if the product is already in the user's cart
        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('product_id', $productId)
                        ->first();
    
        if ($cartItem) {
            // If the product is already in the cart, update the quantity
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // If the product is not in the cart, create a new cart item
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }
    
        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    /**
     * Update the quantity of a cart item
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::findOrFail($id);

        // Check if the cart item belongs to the authenticated user
        if ($cartItem->user_id !== Auth::id()) {
            return redirect()->route('carts')->with('error', 'Anda tidak memiliki akses untuk mengubah item ini');
        }

        // Check if the requested quantity is available in stock
        if ($cartItem->product->stock < $request->quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi');
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return back()->with('success', 'Jumlah produk berhasil diperbarui');
    }

    /**
     * Remove a cart item
     */
    public function remove($id)
    {
        $cartItem = Cart::findOrFail($id);

        // Check if the cart item belongs to the authenticated user
        if ($cartItem->user_id !== Auth::id()) {
            return redirect()->route('carts')->with('error', 'Anda tidak memiliki akses untuk menghapus item ini');
        }

        $cartItem->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    /**
     * Clear all items from the cart
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Keranjang belanja berhasil dikosongkan');
    }
}

