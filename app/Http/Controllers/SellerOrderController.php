<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class SellerOrderController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = Auth::user()->id;

        $query = Order::whereHas('items', function($query) use ($sellerId) {
                $query->where('user_id', $sellerId);
            })
            ->with(['user', 'items'])
            ->latest();

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Pencarian
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%'.$request->search.'%');
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('seller.transactions.orders.orders', compact('orders'));
    }
    

    // Show the order details
    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);
        return view('seller.transactions.orders.show', compact('order'));
    }

    // Update the status of an order
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Validate the request
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'notes' => 'nullable|string|max:255',
        ]);

        // Update the order status
        $order->status = $request->status;
        $order->notes = $request->notes;
        $order->save();

        // Redirect back to the order list with success message
        return redirect()->route('seller.transactions.orders.orders')->with('success', 'Status pemesanan berhasil diperbarui');
    }
}




