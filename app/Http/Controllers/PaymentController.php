<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction; // Assuming Transaction is the model for the transactions table
use App\Models\Payment; // Assuming Payment is the model for the payments table
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $seller = Auth::user();
    
        // Ambil payment yang transaksinya punya order dengan item dari produk seller ini
        $query = Payment::whereHas('transaction.order.orderItems.product', function ($q) use ($seller) {
            $q->where('user_id', $seller->id);
        });
    
        // Filter optional
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
    
        $payments = $query->with('transaction.order.orderItems.product')->orderBy('created_at', 'desc')->paginate(10);
    
        // Hitung total pembayaran dan jumlah status
        $totalPayments = $payments->sum('amount');
        $successCount = $payments->where('status', 'success')->count();
        $pendingCount = $payments->where('status', 'pending')->count();
    
        return view('seller.transactions.payments.payments', compact(
            'payments', 'totalPayments', 'successCount', 'pendingCount'
        ));
    }
    
    
    /**
     * Display the specified payment.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\View\View
     */
    public function show(Payment $payment)
    {
        $seller = Auth::user();

        // Check if the payment belongs to the seller's transaction
        $transaction = $payment->transaction; // Assuming there's a relationship defined in the Payment model

        return view('seller.transactions.payments.detail-payment', compact('payment'));
    }
}