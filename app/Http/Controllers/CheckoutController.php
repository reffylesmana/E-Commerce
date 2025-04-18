<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Shipping;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melanjutkan ke checkout.');
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('carts.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }

        $shipping = 20000;
        $tax = $subtotal * 0.11;
        $total = $subtotal + $shipping + $tax;

        $addresses = Address::where('user_id', Auth::id())->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();

        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total', 'addresses'));
    }

    public function getToken(Request $request)
    {
        try {
            $params = [
                'transaction_details' => [
                    'order_id' => $request->order_id,
                    'gross_amount' => (int) $request->gross_amount,
                ],
                'customer_details' => $request->customer_details,
                'enabled_payments' => $this->getEnabledPayments($request->payment_method),
            ];

            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snap_token' => $snapToken,
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

    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'postal_code' => 'required|string|max:10',
            'shipping_method' => 'required|string',
            'gross_amount' => 'required|numeric',
            'order_id' => 'required|string|unique:orders,order_number',
        ]);
        DB::beginTransaction();

        try {
            $address = null;
            $shippingAddress = '';

            if ($request->has('address_id') && $request->address_id) {
                $address = Address::where('id', $request->address_id)->where('user_id', Auth::id())->first();

                if ($address) {
                    $shippingAddress = $address->full_address . ', ' . $address->district . ', ' . $address->city . ', ' . $address->province . ', ' . $address->postal_code;
                }
            } else {
                $address = Address::create([
                    'user_id' => Auth::id(),
                    'name' => 'Alamat Pengiriman',
                    'recipient_name' => $request->name,
                    'phone' => $request->phone,
                    'province' => '',
                    'city' => $request->city,
                    'district' => '',
                    'postal_code' => $request->postal_code,
                    'full_address' => $request->address,
                    'is_default' => !Address::where('user_id', Auth::id())->exists(),
                    'address_type' => 'other',
                ]);

                $shippingAddress = $request->address . ', ' . $request->city . ', ' . $request->postal_code;
            }

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'invoice_number' => 'INV-' . time() . '-' . Auth::id(),
                'total_amount' => $request->gross_amount,
                'shipping_cost' => 20000,
                'tax_amount' => ($request->gross_amount - 20000) * 0.11,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            $order = Order::create([
                'user_id' => Auth::id(),
                'transaction_id' => $transaction->id,
                'address_id' => $address->id,
                'order_number' => $request->order_id,
                'total_amount' => $request->gross_amount,
                'shipping_address' => $shippingAddress,
                'shipping_method' => $request->shipping_method,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'subtotal' => $cartItem->product->price * $cartItem->quantity,
                    'user_id' => $cartItem->product->user_id,
                ]);

                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            Payment::create([
                'transaction_id' => $transaction->id,
                'payment_id' => $request->transaction_id ?? 'PENDING-' . time(),
                'payment_method' => $request->payment_method,
                'amount' => $request->gross_amount,
                'status' => $request->transaction_id ? 'success' : 'pending',
            ]);

            Shipping::create([
                'order_id' => $order->id,
                'address_id' => $address->id,
                'tracking_number' => null,
                'shipping_method' => $request->shipping_method,
                'status' => 'pending',
                'shipping_address' => $shippingAddress,
                'notes' => $request->notes,
            ]);

            if ($request->transaction_id) {
                $transaction->status = 'paid';
                $transaction->save();
            }

            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat. Terima kasih telah berbelanja di TechnoShop!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Debug log (sementara untuk pengembangan)
            dd('Error Debug:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('checkout.index')
                ->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
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
                return null;
        }
    }
}
