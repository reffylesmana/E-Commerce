<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ShippingController extends Controller
{
    /**
     * Display a listing of the shippings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $seller = Auth::user();
    
        // Ambil shipping yang produknya milik seller
        $query = Shipping::whereHas('order.orderItems.product', function ($query) use ($seller) {
            $query->where('user_id', $seller->id);
        });
    
        // Filter tambahan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        if ($request->filled('shipping_method')) {
            $query->where('shipping_method', $request->shipping_method);
        }
    
        if ($request->filled('search')) {
            $query->where('no_resi', 'like', '%' . $request->search . '%');
        }
    
        $shippings = $query->with(['order.orderItems.product', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        // Hitung statistik berdasarkan produk milik seller
        $getCountByStatus = function ($status) use ($seller) {
            return Shipping::where('status', $status)
                ->whereHas('order.orderItems.product', function ($q) use ($seller) {
                    $q->where('user_id', $seller->id);
                })->count();
        };
    
        return view('seller.transactions.shipping.shipping', [
            'shippings' => $shippings,
            'pendingCount' => $getCountByStatus('pending'),
            'shippedCount' => $getCountByStatus('shipped'),
            'deliveredCount' => $getCountByStatus('delivered'),
        ]);
    }
    

    /**
     * Display the specified shipping.
     *
     * @param  \App\Models\Shipping  $shipping
     * @return \Illuminate\View\View
     */
    public function show(Shipping $shipping)
    {
        $seller = Auth::user();
    
        // Load relasi order + order items + product
        $shipping->load('order.orderItems.product', 'order.user');
    
        // Pastikan shipping mengandung produk yang dimiliki seller
        $ownsProduct = $shipping->order->orderItems->contains(function ($item) use ($seller) {
            return $item->product->user_id === $seller->id;
        });
    
        if (!$ownsProduct) {
            abort(403, 'Anda tidak memiliki akses ke pengiriman ini.');
        }
    
        return view('seller.transactions.shipping.detail-shipping', compact('shipping'));
    }
    
    /**
     * Send the shipment (update status to shipped).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shipping  $shipping
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request, Shipping $shipping)
    {
        $seller = Auth::user();
        
        // Load relasi order + order items + product
        $shipping->load('order.orderItems.product', 'order');
        
        // Pastikan shipping mengandung produk yang dimiliki seller
        $ownsProduct = $shipping->order->orderItems->contains(function ($item) use ($seller) {
            return $item->product->user_id === $seller->id;
        });
        
        if (!$ownsProduct) {
            abort(403, 'Anda tidak memiliki akses ke pengiriman ini.');
        }
        
        // Pastikan order dalam status processing
        if ($shipping->order->status !== 'processing') {
            return redirect()->back()->with('error', 'Hanya pesanan dengan status Processing yang dapat dikirim.');
        }
        
        // Validate the request
        $request->validate([
            'no_resi' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:255',
        ]);
        
        // Generate tracking number if not provided
        if (empty($request->no_resi)) {
            $no_resi = $this->generateTrackingNumber($shipping);
        } else {
            $no_resi = $request->no_resi;
        }
        
        // Update the shipping
        $shipping->no_resi = $no_resi;
        $shipping->status = 'shipped';
        $shipping->shipped_at = Carbon::now();
        
        // Calculate estimated arrival date
        $shipping->estimated_arrival = $this->calculateEstimatedArrival($shipping->shipping_method);
        
        // Add notes if provided
        if ($request->filled('notes')) {
            $shipping->notes = $request->notes;
        }
        
        // Update the order status
        $shipping->order->status = 'shipped';
        $shipping->order->save();
        
        $shipping->save();
        
        return redirect()->route('seller.transactions.shipping.show', $shipping)
            ->with('success', 'Pengiriman berhasil dikirim.');
    }
    
    private function calculateEstimatedArrival($shippingMethod)
    {
        // Define estimated delivery days based on shipping method
        $deliveryTimes = [
            'jne' => 3, // 3 days for JNE
            'jnt' => 2, // 2 days for J&T
            'pos' => 5, // 5 days for POS Indonesia
            'sicepat' => 3, // 3 days for SiCepat
            'anteraja' => 4, // 4 days for AnterAja
        ];
    
        // Get the current date
        $currentDate = Carbon::now();
    
        // Calculate estimated arrival date
        $daysToAdd = $deliveryTimes[$shippingMethod] ?? 3; // Default to 3 days if method not found
        return $currentDate->addDays($daysToAdd);
    }

    /**
     * Generate a tracking number with a specific pattern.
     *
     * @param  \App\Models\Shipping  $shipping
     * @return string
     */
    private function generateTrackingNumber(Shipping $shipping)
    {
        // Get courier code (first 3 letters of shipping method)
        $courierCode = strtoupper(substr($shipping->shipping_method, 0, 3));
        
        // Get date in format YYMMDD
        $date = Carbon::now()->format('ymd');
        
        // Get last 4 digits of order number
        $orderNumber = $shipping->order->order_number;
        $lastFourDigits = substr($orderNumber, -4);
        
        // Generate 8 random alphanumeric characters
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomPart = '';
        for ($i = 0; $i < 8; $i++) {
            $randomPart .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        // Combine all parts to create the tracking number
        return $courierCode . $date . $lastFourDigits . $randomPart;
    }
}
