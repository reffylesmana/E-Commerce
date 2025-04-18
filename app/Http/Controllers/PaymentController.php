<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // Ambil seller ID yang sedang login (asumsinya pakai relasi user->seller)
        $sellerId = Auth::user()->seller->id ?? null;

        // Query dasar
        $query = Payment::with(['transaction.order'])
            ->whereHas('transaction.order', function ($q) use ($sellerId) {
                $q->where('user_id', $sellerId);
            });

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter metode pembayaran
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter pencarian berdasarkan payment_id
        if ($request->filled('search')) {
            $query->where('payment_id', 'like', '%' . $request->search . '%');
        }

        // Paginate hasil
        $payments = $query->latest()->paginate(10);

        // Hitung summary
        $totalPayments = (clone $query)->where('status', 'success')->sum('amount');
        $successCount  = (clone $query)->where('status', 'success')->count();
        $pendingCount  = (clone $query)->where('status', 'pending')->count();

        return view('seller.transactions.payments.payments', compact('payments', 'totalPayments', 'successCount', 'pendingCount'));
    }

    public function show($id)
    {
        $payment = Payment::with(['transaction.order'])->findOrFail($id);

        // Validasi agar hanya seller terkait yang bisa melihat
        $sellerId = Auth::user()->seller->id ?? null;
        if ($payment->transaction->order->seller_id != $sellerId) {
            abort(403, 'Unauthorized');
        }

        return view('seller.transactions.payments.show', compact('payment'));
    }
}
