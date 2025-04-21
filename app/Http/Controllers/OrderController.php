<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;


class OrderController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index()
    {
        // Batalkan pesan yang expired
        Order::expired()->update(['status' => 'cancelled']);

        // Get all orders for the authenticated user
        $orders = Order::where('user_id', Auth::id())
            ->with(['transaction', 'orderItems.product.photos'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders', compact('orders'));
    }

    public function allOrders()
    {
        // Batalkan pesan yang expired
        Order::expired()->update(['status' => 'cancelled']);

        // Get all orders for the authenticated user
        $orders = Order::where('user_id', Auth::id())
            ->with(['transaction', 'orderItems.product.photos'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.all', compact('orders'));
    }

    public function show(Order $order)
    {
        // Verify the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Anda tidak memiliki akses untuk melihat pesanan ini.');
        }

        // Load relationships
        $order->load(['transaction', 'orderItems.product.photos', 'shipping']);

        // Get payment details
        $payment = $order->transaction->payment ?? null; // Use null coalescing to avoid errors if payment is not found

        // Calculate remaining time for payment
        $remainingTime = null;
        if ($order->status === 'pending' && $order->payment_expires_at) {
            $now = Carbon::now();
            $expiryTime = Carbon::parse($order->payment_expires_at);

            if ($now->lt($expiryTime)) {
                $remainingTime = $now->diffInSeconds($expiryTime);
            }
        }

        // Check if payment is still valid (not expired)
        $paymentValid = $order->status === 'pending' && $order->payment_expires_at && $order->payment_expires_at > now();

        return view('order-detail', compact('order', 'payment', 'paymentValid', 'remainingTime'));
    }

    /**
     * Get payment token for an existing order
     */
    public function getPaymentToken(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if order is still pending
        if ($order->status !== 'pending') {
            return response()->json(['error' => 'Order is no longer pending'], 400);
        }

        // Check if payment has expired
        $now = Carbon::now();
        $paymentExpired = true;

        if ($order->payment_expires_at) {
            $expiryTime = Carbon::parse($order->payment_expires_at);
            $paymentExpired = $now->gt($expiryTime);
        }

        // If we have a valid token and payment hasn't expired, return the existing token
        if ($order->snap_token && !$paymentExpired) {
            // Calculate remaining time in seconds
            $remainingTime = $now->diffInSeconds($expiryTime);

            return response()->json([
                'snap_token' => $order->snap_token,
                'remaining_time' => $remainingTime,
            ]);
        }

        try {
            // Generate a new token with Midtrans expiry settings
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $order->total_amount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone ?? '',
                    'billing_address' => [
                        'address' => $order->shipping_address ?? '',
                    ],
                ],
                'expiry' => [
                    'unit' => 'day',
                    'duration' => 1, // 24 hours expiry - Midtrans will handle this
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            // Update the order with the new token and payment creation time
            $order->snap_token = $snapToken;
            $order->payment_created_at = now();
            $order->payment_expires_at = now()->addDay(); // Set to 24 hours from now
            $order->save();

            return response()->json([
                'snap_token' => $snapToken,
                'remaining_time' => 86400, // 24 hours in seconds
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function unpaid()
    {
        // Batalkan pesan yang expired
        Order::expired()->update(['status' => 'cancelled']);
        // Get unpaid orders for the authenticated user
        $orders = Order::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->with(['transaction', 'orderItems.product.photos'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.unpaid', compact('orders'));
    }

    public function processing()
    {
        // Batalkan pesan yang expired
        Order::expired()->update(['status' => 'cancelled']);
        // Get orders being processed for the authenticated user
        $orders = Order::where('user_id', Auth::id())
            ->where('status', 'processing')
            ->with(['transaction', 'orderItems.product.photos'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.processing', compact('orders'));
    }

    public function shipped()
    {
        // Get shipped orders for the authenticated user
        $orders = Order::where('user_id', Auth::id())
            ->where('status', 'shipped')
            ->with(['transaction', 'orderItems.product.photos', 'shipping'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.shipped', compact('orders'));
    }

    public function completed()
    {
        // Batalkan pesan yang expired
        Order::expired()->update(['status' => 'cancelled']);
        // Get completed orders for the authenticated user
        $orders = Order::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->with(['transaction', 'orderItems.product.photos'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.completed', compact('orders'));
    }

    public function cancelled()
{
    // Get canceled orders for the authenticated user
    $orders = Order::where('user_id', Auth::id())
        ->where('status', 'cancelled')
        ->with(['transaction', 'orderItems.product.photos'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('orders.cancelled', compact('orders'));
}

/**
     * Mark an order as delivered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsDelivered(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Pastikan order milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }
        
        // Pastikan status order adalah shipped
        if ($order->status !== 'shipped') {
            return redirect()->back()->with('error', 'Hanya pesanan dengan status Dikirim yang dapat ditandai sebagai Diterima.');
        }
        
        // Update status order menjadi completed
        $order->status = 'completed';
        $order->save();
        
        // Update status shipping menjadi delivered dan set delivered_at
        if ($order->shipping) {
            $order->shipping->status = 'delivered';
            $order->shipping->delivered_at = Carbon::now();
            $order->shipping->save();
        }
        
        // Redirect dengan pesan sukses
        return redirect()->route('orders.completed')->with('success', 'Pesanan berhasil ditandai sebagai Diterima.');
    }
}
