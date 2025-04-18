<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredOrders extends Command
{
    protected $signature = 'orders:cancel-expired';
    protected $description = 'Cancel orders that have not been paid within 24 hours';

    public function handle()
    {
        $this->info('Checking for expired orders...');
        
        $expiredOrders = Order::where('status', 'pending')
            ->where('payment_expires_at', '<', now())
            ->get();
            
        $count = $expiredOrders->count();
        $this->info("Found {$count} expired orders to cancel.");
        
        foreach ($expiredOrders as $order) {
            DB::beginTransaction();
            try {
                // Update order status
                $order->status = 'cancelled';
                $order->save();
                
                // Update transaction status
                $transaction = Transaction::find($order->transaction_id);
                if ($transaction) {
                    $transaction->status = 'cancelled';
                    $transaction->save();
                }
                
                // Return stock to products
                foreach ($order->orderItems as $item) {
                    $product = $item->product;
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
                
                DB::commit();
                $this->info("Order #{$order->order_number} has been cancelled.");
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Failed to cancel order #{$order->order_number}: {$e->getMessage()}");
                Log::error("Failed to cancel order #{$order->order_number}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        
        $this->info('Expired orders cancellation completed.');
        return 0;
    }
}