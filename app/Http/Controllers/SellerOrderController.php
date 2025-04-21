<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SellerOrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $seller = Auth::user();

        $query = Order::whereHas('orderItems.product', function ($q) use ($seller) {
            $q->where('user_id', $seller->id);
        });

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get orders with pagination
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get counts for stats
        $totalOrders = Order::whereHas('orderItems.product', function ($q) use ($seller) {
            $q->where('user_id', $seller->id);
        })->count();

        $pendingOrders = Order::whereHas('orderItems.product', function ($q) use ($seller) {
            $q->where('user_id', $seller->id);
        })->where('status', 'pending')->count();

        $processingOrders = Order::whereHas('orderItems.product', function ($q) use ($seller) {
            $q->where('user_id', $seller->id);
        })->where('status', 'processing')->count();

        $completedOrders = Order::whereHas('orderItems.product', function ($q) use ($seller) {
            $q->where('user_id', $seller->id);
        })->where('status', 'completed')->count();

        return view('seller.transactions.orders.orders', compact('orders', 'totalOrders', 'pendingOrders', 'processingOrders', 'completedOrders'));
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        $seller = Auth::user();

        $order->load('orderItems.product');

        $belongsToSeller = $order->orderItems->contains(function ($item) use ($seller) {
            return $item->product->user_id === $seller->id;
        });
        
        if (!$belongsToSeller) {
            abort(403, 'Unauthorized action.');
        }
        

        // Eager load relationships
        $order->load(['user', 'orderItems.product.Photos', 'shipping']);

        return view('seller.transactions.orders.detail-order', compact('order'));
    }

}
