<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Mendapatkan isi body dari request callback
        $notificationBody = json_decode($request->getContent(), true);
        
        // Log the callback for debugging
        Log::info('Midtrans callback received', ['data' => $notificationBody]);
        
        // Ambil order_id dan transaction_id dari callback
        $orderId = $notificationBody['order_id'];
        $status = $notificationBody['transaction_status'];
        $transactionId = $notificationBody['transaction_id'];
        
        // Cek apakah ada payment yang terkait dengan transaction_id
        $payment = Payment::where('payment_id', $transactionId)
            ->orWhere(function($query) use ($orderId) {
                $query->whereHas('transaction.orders', function($q) use ($orderId) {
                    $q->where('order_number', $orderId);
                });
            })
            ->first();
            
        // Jika payment tidak ditemukan
        if (!$payment) {
            Log::error('Payment not found', ['transaction_id' => $transactionId, 'order_id' => $orderId]);
            return response()->json(['message' => 'Payment not found'], 404);
        }
        
        // Mendapatkan transaction yang terkait dengan payment
        $transaction = Transaction::find($payment->transaction_id);
        if (!$transaction) {
            Log::error('Transaction not found', ['transaction_id' => $transactionId]);
            return response()->json(['message' => 'Transaction not found'], 404);
        }
        
        // Mendapatkan order yang terkait dengan transaction
        $order = Order::where('transaction_id', $transaction->id)->first();
        if (!$order) {
            Log::error('Order not found', ['transaction_id' => $transaction->id]);
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        // Log order status before processing
        Log::info('Order Status before processing', ['order_status' => $order->status]);
        
        // Mulai transaksi database
        DB::beginTransaction();
        try {
            // Update status payment sesuai dengan status yang diterima dari Midtrans
            switch ($status) {
                case 'capture':
                case 'settlement':
                    $payment->status = 'success';
                    $payment->payment_id = $transactionId;
                    $payment->payment_method = $notificationBody['payment_type'] ?? $payment->payment_method;
                    
                    // Update status transaksi menjadi paid
                    $transaction->status = 'paid';
                    
                    // Update status order menjadi processing (sedang dikemas)
                    $order->status = 'processing';
                    
                    // Update status shipping (jika ada) menjadi processing
                    $shipping = Shipping::where('order_id', $order->id)->first();
                    if ($shipping) {
                        $shipping->status = 'processing';
                        $shipping->save();
                    }
                    break;
                    
                case 'deny':
                case 'cancel':
                case 'expire':
                    $payment->status = 'failed';
                    $payment->payment_id = $transactionId;
                    $payment->payment_method = $notificationBody['payment_type'] ?? $payment->payment_method;
                    
                    // Update status transaksi menjadi cancelled
                    $transaction->status = 'cancelled';
                    
                    // Update status order menjadi cancelled
                    $order->status = 'cancelled';
                    
                    // Kembalikan stok produk jika pembayaran gagal
                    foreach ($order->orderItems as $item) {
                        $product = $item->product;
                        if ($product) {
                            $product->increment('stock', $item->quantity);
                        }
                    }
                    break;
                    
                case 'pending':
                    $payment->status = 'pending';
                    $payment->payment_id = $transactionId;
                    $payment->payment_method = $notificationBody['payment_type'] ?? $payment->payment_method;
                    break;
            }
            
            // Log order status after update
            Log::info('Order Status after processing', ['order_status' => $order->status]);

            // Simpan data yang sudah diupdate
            $payment->payment_details = $notificationBody;
            $payment->save();
            $transaction->save();
            $order->save();
            
            // Commit transaksi jika semua update berhasil
            DB::commit();
            
            Log::info('Payment callback processed successfully', [
                'order_id' => $order->id,
                'status' => $status
            ]);
            
            return response()->json(['message' => 'Notification processed']);
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();
            
            // Log error jika terjadi kesalahan
            Log::error('Error processing payment callback', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'data' => $notificationBody
            ]);
            
            return response()->json(['message' => 'Error processing notification: ' . $e->getMessage()], 500);
        }
    }
}
