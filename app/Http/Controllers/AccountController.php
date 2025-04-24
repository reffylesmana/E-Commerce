<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get order statistics
        $orderCount = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_amount');
            
        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get order counts by status
        $unpaidCount = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
            
        $processingCount = Order::where('user_id', $user->id)
            ->where('status', 'processing')
            ->count();
            
        $shippedCount = Order::where('user_id', $user->id)
            ->where('status', 'shipped')
            ->count();

        $wishlists = Wishlist::where('user_id', $user->id)
            ->with(['product' => function($query) {
                $query->with(['photos', 'category', 'store']);
            }])
            ->paginate(10);
            
        // Get products that need reviews
        $completedOrderItems = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('status', 'completed');
        })->get();
        
        $reviewNeededCount = 0;
        foreach ($completedOrderItems as $item) {
            $hasReview = Review::where('user_id', $user->id)
                ->where('product_id', $item->product_id)
                ->exists();
                
            if (!$hasReview) {
                $reviewNeededCount++;
            }
        }
        
        return view('account', compact(
            'orderCount', 
            'totalSpent', 
            'recentOrders',
            'unpaidCount',
            'processingCount',
            'wishlists',
            'shippedCount',
            'reviewNeededCount'
        ));
    }
    
    public function allOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['transaction', 'orderItems.product.photos'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('orders.all', compact('orders'));
    }
    
    public function unpaidOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->with(['transaction', 'orderItems.product.photos'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('orders.unpaid', compact('orders'));
    }
    
    public function processingOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->where('status', 'processing')
            ->with(['transaction', 'orderItems.product.photos'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('orders.processing', compact('orders'));
    }
    
    public function shippedOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->where('status', 'shipped')
            ->with(['transaction', 'orderItems.product.photos', 'shipping'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('orders.shipped', compact('orders'));
    }
    
    public function completedOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->with(['transaction', 'orderItems.product.photos'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('orders.completed', compact('orders'));
    }
    
    public function reviews()
    {
        $user = Auth::user();
        
        // Get products that need reviews
        $pendingReviews = OrderItem::whereHas('order', function($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('status', 'completed');
        })
        ->with(['product.photos', 'order'])
        ->get()
        ->filter(function($item) use ($user) {
            return !Review::where('user_id', $user->id)
                ->where('product_id', $item->product_id)
                ->exists();
        });
        
        // Get user's reviews
        $reviews = Review::where('user_id', $user->id)
            ->with('product.photos')
            ->orderBy('created_at', 'desc')
            ->paginate(9);
            
        return view('reviews', compact('pendingReviews', 'reviews'));
    }
}

