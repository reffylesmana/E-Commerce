<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Order;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipping::with('order');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan metode pengiriman
        if ($request->filled('shipping_method')) {
            $query->where('shipping_method', $request->shipping_method);
        }

        // Pencarian berdasarkan nomor resi
        if ($request->filled('search')) {
            $query->where('tracking_number', 'like', '%' . $request->search . '%');
        }

        $shippings = $query->latest()->paginate(10);

        // Hitung jumlah berdasarkan status
        $pendingCount = Shipping::where('status', 'pending')->count();
        $shippedCount = Shipping::where('status', 'shipped')->count();
        $deliveredCount = Shipping::where('status', 'delivered')->count();

        return view('seller.transactions.shipping.shipping', compact('shippings', 'pendingCount', 'shippedCount', 'deliveredCount'));
    }

    public function show($id)
    {
        $shipping = Shipping::with('order')->findOrFail($id);
        return view('seller.transactions.shipping.show', compact('shipping'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
            'status' => 'required|in:pending,shipped,in_transit,delivered,failed',
        ]);

        $shipping = Shipping::findOrFail($id);
        $shipping->tracking_number = $request->tracking_number;
        $shipping->status = $request->status;
        $shipping->shipped_at = now();
        $shipping->save();

        return redirect()->back()->with('success', 'Pengiriman berhasil diperbarui.');
    }
}
