<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function getToken(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'order_number' => 'required|string',
                'total_amount' => 'required|numeric',
                'name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'shipping_address' => 'required|string',
                'payment_method' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Format the amount to ensure it's an integer (Midtrans requires amounts in lowest currency unit)
            $amount = (int) ($request->total_amount * 100);

            // Build transaction parameters for Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $request->order_number,
                    'gross_amount' => $amount,
                ],
                'customer_details' => [
                    'first_name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'billing_address' => [
                        'address' => $request->shipping_address,
                    ],
                    'shipping_address' => [
                        'address' => $request->shipping_address,
                    ],
                ],
                'item_details' => $request->items ?? [],
            ];
            
            // Add enabled payments if specified
            if ($request->payment_method) {
                $params['enabled_payments'] = $this->getEnabledPayments($request->payment_method);
            }
            
            // Generate Snap Token
            $snapToken = Snap::getSnapToken($params);
            
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_number' => $request->order_number
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating payment token',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        try {
            $notificationBody = json_decode($request->getContent(), true);
            
            // Verify the signature (optional but recommended)
            $signatureKey = $notificationBody['signature_key'] ?? '';
            $orderId = $notificationBody['order_id'] ?? '';
            $statusCode = $notificationBody['status_code'] ?? '';
            $grossAmount = $notificationBody['gross_amount'] ?? '';
            
            $mySignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . Config::$serverKey);
            
            if ($signatureKey !== $mySignatureKey) {
                return response()->json(['error' => 'Invalid signature'], 403);
            }
            
            // Find the order by order_number
            $order = Order::where('order_number', $orderId)->first();
            
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }
            
            // Update order status based on transaction status
            $transactionStatus = $notificationBody['transaction_status'] ?? '';
            $fraudStatus = $notificationBody['fraud_status'] ?? '';
            
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    // Set order status to 'processing' if payment needs review
                    $order->status = 'processing';
                } else if ($fraudStatus == 'accept') {
                    // Set order status to 'processing' if payment is accepted
                    $order->status = 'processing';
                }
            } else if ($transactionStatus == 'settlement') {
                // Set order status to 'processing' if payment is settled
                $order->status = 'processing';
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                // Set order status to 'cancelled' if payment is cancelled, denied, or expired
                $order->status = 'cancelled';
            } else if ($transactionStatus == 'pending') {
                // Payment is pending, keep order status as 'pending'
                $order->status = 'pending';
            }
            
            $order->save();
            
            // Update transaction status if transaction exists
            if ($order->transaction_id) {
                $transaction = Transaction::find($order->transaction_id);
                if ($transaction) {
                    $transaction->status = $order->status;
                    $transaction->save();
                }
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function getEnabledPayments($paymentMethod)
    {
        switch ($paymentMethod) {
            case 'bank_transfer':
                return ['bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va'];
            case 'credit_card':
                return ['credit_card'];
            case 'e_wallet':
                return ['gopay', 'shopeepay', 'qris'];
            case 'qris':
                return ['qris'];
            default:
                return null; // All available payment methods
        }
    }
}

