@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center">Your Cart</h1>
    <div class="lg:flex lg:gap-8">
        <div class="lg:w-2/3">
            @forelse($cartItems as $item)
                <div class="flex items-center justify-between border-b border-gray-200 py-4">
                    <div class="flex items-center">
                        <img src="{{ $item->product->image ?? asset('images/placeholder.jpg') }}" alt="{{ $item->product->name }}" class="w-20 h-20 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-semibold text-lg">{{ $item->product->name }}</h3>
                            <p class="text-gray-600">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="bg-gray-200 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </button>
                            <span class="mx-2 w-8 text-center">{{ $item->quantity }}</span>
                            <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="bg-gray-200 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </button>
                        </form>
                        <form action="{{ route('cart.remove', $item) }}" method="POST" class="ml-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Your cart is empty.</p>
            @endforelse
        </div>
        <div class="lg:w-1/3 mt-8 lg:mt-0">
            <div class="bg-gray-50 p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                <div class="flex justify-between mb-2">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Discount</span>
                    <span>Rp {{ number_format($discount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-semibold text-lg mt-4">
                    <span>Total</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                {{-- {{ route('cart.applyCoupon') }} --}}
                <form action="" method="POST" class="mt-6">
                    @csrf
                    <input type="text" name="coupon_code" placeholder="Enter coupon code" class="w-full p-2 border border-gray-300 rounded mb-2">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-300">
                        Apply Coupon
                    </button>
                </form>
                <a href="" class="block w-full mt-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-lg hover:from-blue-700 hover:to-purple-700 transition duration-300 text-center">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    </div>
</div>
@endsection