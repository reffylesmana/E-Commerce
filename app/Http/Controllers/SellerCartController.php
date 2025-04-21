<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SellerCartController extends Controller
{
    /**
     * Display the seller cart analytics page
     */
    public function index(Request $request)
    {
        $seller = Auth::user();
    
        // Get date range filter
        $dateRange = $request->input('date_range', 'today');
        $search = $request->input('search');
    
        // Set date range based on filter
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();
    
        switch ($dateRange) {
            case 'yesterday':
                $startDate = Carbon::yesterday()->startOfDay();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
            case 'last_7_days':
                $startDate = Carbon::now()->subDays(7)->startOfDay();
                break;
            case 'last_30_days':
                $startDate = Carbon::now()->subDays(30)->startOfDay();
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
        }
    
        // Get cart items for seller's products
        $query = Cart::whereHas('product', function ($query) use ($seller, $search) {
            $query->where('user_id', $seller->id); // Only fetch cart items with products from the seller
    
            if ($search) {
                $query->where('name', 'like', "%{$search}%");
            }
        })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['product', 'user']);
    
        $cartItems = $query->paginate(10);
    
        // Get cart analytics
        $totalItems = Cart::whereHas('product', function ($query) use ($seller) {
            $query->where('user_id', $seller->id);
        })->sum('quantity');
    
        // Get most popular product in cart
        $popularProduct = Product::where('user_id', $seller->id)
            ->withCount(['carts']) 
            ->orderBy('carts_count', 'desc')
            ->first();
    
        // Calculate cart conversion rate (orders / cart additions)
        $cartAdditions = Cart::whereHas('product', function ($query) use ($seller) {
            $query->where('user_id', $seller->id);
        })->count();
    
        $completedOrders = Order::whereHas('orderItems.product', function ($query) use ($seller) {
            $query->where('user_id', $seller->id);
        })->where('status', 'completed')->count();        
    
        $conversionRate = $cartAdditions > 0 ? ($completedOrders / $cartAdditions) * 100 : 0;
    
        // Get abandoned carts (older than 24 hours with no purchase)
        $abandonedCarts = [];
        $cutoffDate = Carbon::now()->subHours(24);
    
        $abandonedCartUsers = Cart::whereHas('product', function ($query) use ($seller) {
            $query->where('user_id', $seller->id);
        })
            ->where('updated_at', '<', $cutoffDate)
            ->select('user_id')
            ->distinct()
            ->get();
    
        foreach ($abandonedCartUsers as $cartUser) {
            $user = User::find($cartUser->user_id);
            if (!$user) {
                continue;
            } // Skip if user not found
    
            $userCartItems = Cart::where('user_id', $user->id)
                ->whereHas('product', function ($query) use ($seller) {
                    $query->where('user_id', $seller->id);
                })
                ->with('product')
                ->get();
    
            $totalQuantity = $userCartItems->sum('quantity');
            $totalValue = 0;
    
            foreach ($userCartItems as $item) {
                $totalValue += $item->product->price * $item->quantity;
            }
    
            $lastUpdated = $userCartItems->max('updated_at');
    
            $abandonedCarts[] = [
                'user' => $user,
                'user_id' => $user->id,
                'total_quantity' => $totalQuantity,
                'total_value' => $totalValue,
                'last_updated' => $lastUpdated,
            ];
        }
    
        // Return the seller cart view, not the buyer cart view
        return view('seller.transactions.cart.cart', compact('cartItems', 'totalItems', 'popularProduct', 'conversionRate', 'abandonedCarts', 'dateRange'));
    }
    

    /**
     * Send a reminder to a user with abandoned cart
     */
    public function sendReminder(User $user)
    {
        $seller = Auth::user();

        // Get cart items for this user and seller
        $cartItems = Cart::whereHas('product', function ($query) use ($seller) {
            $query->where('user_id', $seller); // Filter produk berdasarkan seller ID
        })
            ->with(['product', 'user'])
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Tidak ada item di keranjang untuk pengguna ini');
        }

        // Calculate cart total
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        try {
            // Send email reminder
            Mail::send(
                'emails.cart-reminder',
                [
                    'user' => $user,
                    'cartItems' => $cartItems,
                    'total' => $total,
                    'seller' => $seller,
                ],
                function ($message) use ($user, $seller) {
                    $message->to($user->email);
                    $message->subject('Anda memiliki item di keranjang belanja - ' . $seller->store->name);
                },
            );

            return back()->with('success', 'Pengingat berhasil dikirim ke ' . $user->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim pengingat: ' . $e->getMessage());
        }
    }
}
