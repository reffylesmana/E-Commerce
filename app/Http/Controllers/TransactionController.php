<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function orders(Request $request)
    {
        $seller = Auth::user();
        $storeId = $seller->store->id;
    
        $validated = $request->validate([
            'status' => 'nullable|in:pending,processing,shipped,completed,cancelled',
            'date' => 'nullable|date',
            'search' => 'nullable|string|max:255'
        ]);
    
        $query = Order::whereHas('orderItems.product', function($q) use ($storeId) {
            $q->where('store_id', $storeId);
        });
    
        // Filter: Status
        if ($request->filled('status')) {
            $query->where('status', $validated['status']);
        }
    
        // Filter: Tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', Carbon::parse($validated['date'])->toDateString());
        }
    
        // Filter: Pencarian
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $validated['search'] . '%');
        }
    
        $orders = $query->with('user')
            ->latest()
            ->paginate(10)
            ->appends($request->query());
    
        return view('seller.transactions.orders.orders', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function showOrder(Order $order)
    {
        $seller = Auth::user();
        $storeId = $seller->store->id;

        // Check if order belongs to seller
        $hasAccess = $order->orderItems()->whereHas('product', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['user', 'orderItems.product', 'transaction.payment', 'shipping']);

        return view('seller.transactions.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $seller = Auth::user();
        $storeId = $seller->store->id;

        // Check if order belongs to seller
        $hasAccess = $order->orderItems()->whereHas('product', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $order->status = $request->status;
        $order->notes = $request->notes ?? null;
        $order->save();

        // Update transaction status if needed
        if ($order->transaction) {
            $order->transaction->status = $request->status;
            $order->transaction->save();
        }

        return redirect()->route('seller.transactions.orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Display a listing of payments.
     */
    public function payments(Request $request)
    {
        $seller = Auth::user();
        $storeId = $seller->store->id;

        $validated = $request->validate([
            'status' => 'nullable|in:pending,success,failed',
            'payment_method' => 'nullable|string',
            'search' => 'nullable|string|max:255'
        ]);

        $query = Payment::whereHas('transaction.order.orderItems.product', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        });

        // Apply filters
        if (isset($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if ($validated['payment_method']) {
            $query->where('payment_method', $validated['payment_method']);
        }

        if ($validated['search']) {
            $query->where('payment_id', 'like', '%' . $validated['search'] . '%');
        }

        $payments = $query->with('transaction.order')->latest()->paginate(10)->appends($request->query());

        // Get payment statistics
        $totalPayments = $payments->where('status', 'success')->sum('amount');
        $successCount = $payments->where('status', 'success')->count();
        $pendingCount = $payments->where('status', 'pending')->count();

        return view('seller.transactions.payments.payments', compact('payments', 'totalPayments', 'successCount', 'pendingCount'));
    }

    /**
     * Display the specified payment.
     */
    public function showPayment(Payment $payment)
    {
        $seller = Auth::user();
        $storeId = $seller->store->id;

        // Check if payment belongs to seller
        $hasAccess = $payment->transaction->order->orderItems()->whereHas('product', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized action.');
        }

        $payment->load(['transaction.order.user', 'transaction.order.orderItems.product']);

        return view('seller.transactions.payments.show', compact('payment'));
    }

    /**
     * Display a listing of shipping records.
     */
    public function shipping(Request $request)
    {
        $seller = Auth::user();
        $storeId = $seller->store->id;
    
        $validated = $request->validate([
            'status' => 'nullable|in:pending,shipped,in_transit,delivered,failed',
            'shipping_method' => 'nullable|string',
            'search' => 'nullable|string|max:255'
        ]);
    
        $query = Shipping::whereHas('order.orderItems.product', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        });
    
        // Perbaikan 1: Gunakan isset() untuk cek keberadaan key
        if (isset($validated['status']) && $validated['status']) {
            $query->where('status', $validated['status']);
        }
    
        // Perbaikan 2: Handle parameter shipping_method
        if (isset($validated['shipping_method']) && $validated['shipping_method']) {
            $query->where('shipping_method', $validated['shipping_method']);
        }
    
        // Perbaikan 3: Handle parameter search
        if (isset($validated['search']) && $validated['search']) {
            $query->where('tracking_number', 'like', '%' . $validated['search'] . '%');
        }
    
        $shippings = $query->with('order')->latest()->paginate(10)->appends($request->query());
    
        // Hitung statistik dengan cara yang lebih aman
        $pendingCount = $shippings->where('status', 'pending')->count();
        $shippedCount = $shippings->where('status', 'shipped')->count();
        $inTransitCount = $shippings->where('status', 'in_transit')->count();
        $deliveredCount = $shippings->where('status', 'delivered')->count();
    
        return view('seller.transactions.shipping.shipping', compact('shippings', 'pendingCount', 'shippedCount', 'inTransitCount', 'deliveredCount'));
    }

    /**
     * Display the specified shipping record.
     */
    public function showShipping(Shipping $shipping)
    {
        $seller = Auth::user();
        $storeId = $seller->store->id;

        // Check if shipping belongs to seller
        $hasAccess = $shipping->order->orderItems()->whereHas('product', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized action.');
        }

        $shipping->load(['order.user', 'order.orderItems.product']);

        return view('seller.transactions.shipping.show', compact('shipping'));
    }

    /**
     * Update shipping information.
     */
    public function updateShipping(Request $request, Shipping $shipping)
    {
        // Validasi akses
        $seller = Auth::user();
        $storeId = $seller->store->id;
        
        if (!$shipping->order->orderItems()->whereHas('product', fn($q) => $q->where('store_id', $storeId))->exists()) {
            abort(403);
        }
    
        // Update data
        $shipping->update([
            'status' => 'shipped',
            'shipped_at' => now(),
            'tracking_number' => $request->tracking_number ?: null,
        ]);
    
        // Update order status
        $shipping->order()->update(['status' => 'shipped']);
    
        return back()->with('success', 'Status pengiriman berhasil diperbarui');
    }

    /**
     * Display cart items.
     */
    public function cart(Request $request)
    {
        $sellerId = Auth::id();
        $query = CartItem::with(['product', 'user'])
            ->whereHas('product', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            });

        // Filter by date range
        if ($request->has('date_range')) {
            $dateRange = $request->input('date_range');
            $this->applyDateRangeFilter($query, $dateRange);
        }

        // Search by product name
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        }

        $cartItems = $query->paginate(10);
        $totalItems = $cartItems->sum('quantity');
        $popularProduct = $this->getPopularProduct($sellerId);
        $conversionRate = $this->calculateConversionRate($sellerId);
        $abandonedCarts = $this->getAbandonedCarts();

        return view('seller.cart.index', compact('cartItems', 'totalItems', 'popularProduct', 'conversionRate', 'abandonedCarts'));
    }

    /**
     * Send a reminder to a user with abandoned cart.
     */
    public function sendCartReminder(User $user)
    {
        $seller = Auth::user();
        $storeId = $seller->store->id;

        // Check if user has items in cart from this seller
        $hasItems = $user->carts()->whereHas('product', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->exists();

        if (!$hasItems) {
            abort(403, 'Unauthorized action.');
        }

        // Send reminder email logic would go here
        // For now, we'll just redirect with a success message

        return redirect()->route('seller.transactions.cart')->with('success', 'Pengingat berhasil dikirim ke ' . $user->email);
    }

    /**
     * Display transaction reports.
     */
    public function reports(Request $request)
    {
        $seller = Auth::user();
        $storeId = $seller->store->id;

        // Default to current month if no dates provided
        $startDate = $request->has('start') ? Carbon::parse($request->start) : Carbon::now()->startOfMonth();
        $endDate = $request->has('end') ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfMonth();

        // Get report type
        $reportType = $request->has('type') ? $request->type : 'sales';

        // Get transactions for the period
        $transactions = Transaction::whereHas('order.orderItems.product', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->with(['order.user', 'payment'])
        ->latest()
        ->paginate(10);

        // Calculate sales statistics
        $totalSales = $transactions->sum('total_amount');
        $totalOrders = $transactions->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Count new customers
        $newCustomers = User::whereHas('orders.orderItems.product', function($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

        // Prepare chart data
        $chartData = [];
        $chartLabels = [];

        // Generate daily data for the selected period
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $chartLabels[] = $currentDate->format('d M');

            $dailySales = Transaction::whereHas('order.orderItems.product', function($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })
            ->whereDate('created_at', $currentDate)
            ->sum('total_amount');

            $chartData[] = $dailySales;

            $currentDate->addDay();
        }

        // Get product reports if needed
        $productReports = null;
        if ($reportType == 'products') {
            $productReports = \App\Models\Product::where('store_id', $storeId)
                ->withCount(['orderItems as sold_count' => function($query) use ($startDate, $endDate) {
                    $query->whereHas('order', function($q) use ($startDate, $endDate) {
                        $q->whereBetween ('created_at', [$startDate, $endDate]);
                    });
                }])
                ->withSum(['orderItems as revenue' => function($query) use ($startDate, $endDate) {
                    $query->whereHas('order', function($q) use ($startDate, $endDate) {
                        $q->whereBetween('created_at', [$startDate, $endDate]);
                    });
                }], 'subtotal')
                ->orderByDesc('sold_count')
                ->get();
        }

        // Get customer reports if needed
        $customerReports = null;
        if ($reportType == 'customers') {
            $customerReports = User::whereHas('orders.orderItems.product', function($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })
            ->withCount(['orders as order_count' => function($query) use ($storeId, $startDate, $endDate) {
                $query->whereHas('orderItems.product', function($q) use ($storeId) {
                    $q->where('store_id', $storeId);
                })
                ->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['transactions as total_spent' => function($query) use ($storeId, $startDate, $endDate) {
                $query->whereHas('order.orderItems.product', function($q) use ($storeId) {
                    $q->where('store_id', $storeId);
                })
                ->whereBetween('created_at', [$startDate, $endDate]);
            }], 'total_amount')
            ->with(['orders' => function($query) use ($storeId, $startDate, $endDate) {
                $query->whereHas('orderItems.product', function($q) use ($storeId) {
                    $q->where('store_id', $storeId);
                })
                ->whereBetween('created_at', [$startDate, $endDate])
                ->latest();
            }])
            ->get()
            ->map(function($user) {
                $user->last_order_date = $user->orders->first() ? $user->orders->first()->created_at : null;
                return $user;
            })
            ->filter(function($user) {
                return $user->order_count > 0;
            })
            ->sortByDesc('total_spent');
        }

        // Get payment reports if needed
        $paymentReports = null;
        if ($reportType == 'payments') {
            $totalPaymentAmount = Payment::whereHas('transaction.order.orderItems.product', function($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'success')
            ->sum('amount');

            $paymentReports = Payment::whereHas('transaction.order.orderItems.product', function($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'success')
            ->select('payment_method')
            ->selectRaw('COUNT(*) as transaction_count')
            ->selectRaw('SUM(amount) as total_amount')
            ->groupBy('payment_method')
            ->get()
            ->map(function($payment) use ($totalPaymentAmount) {
                $payment->percentage = $totalPaymentAmount > 0 ? ($payment->total_amount / $totalPaymentAmount) * 100 : 0;
                return $payment;
            });
        }

        return view('transactions.reports', compact(
            'transactions', 
            'totalSales', 
            'totalOrders', 
            'averageOrderValue', 
            'newCustomers',
            'chartData',
            'chartLabels',
            'productReports',
            'customerReports',
            'paymentReports'
        ));
    }

    /**
     * Export transaction report.
     */
    public function exportReport(Request $request)
    {
        // This would typically generate a CSV or Excel file for download
        // For now, we'll just redirect back with a message
        
        return redirect()->back()->with('success', 'Laporan berhasil diunduh.');
    }
}