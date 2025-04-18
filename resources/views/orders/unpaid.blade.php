@extends('layouts.app')

@section('title', 'Pesanan Belum Bayar - TechnoShop')
@section('description', 'Lihat dan bayar pesanan yang belum dibayar di TechnoShop')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-6 sm:py-8">
  <div class="container mx-auto px-4">
      <!-- Breadcrumb -->
      <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                  <a href="{{ route('home') }}" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                      <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                          <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                      </svg>
                      Beranda
                  </a>
              </li>
              <li>
                  <div class="flex items-center">
                      <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                      </svg>
                      <a href="{{ route('account.index') }}" class="ml-1 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 md:ml-2">Akun Saya</a>
                  </div>
              </li>
              <li aria-current="page">
                  <div class="flex items-center">
                      <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                      </svg>
                      <span class="ml-1 text-gray-500 dark:text-gray-400 md:ml-2">Belum Bayar</span>
                  </div>
              </li>
          </ol>
      </nav>

      <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Pesanan Belum Bayar</h1>

      <!-- Order Status Tabs -->
      <div class="flex overflow-x-auto mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-1">
        <a href="{{ route('orders.all') }}" 
           class="px-4 py-2 text-sm font-medium whitespace-nowrap 
                  @if(Route::is('orders.all')) text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 rounded-lg
                  @else text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 @endif">
            Semua Pesanan
        </a>
        
        <a href="{{ route('orders.unpaid') }}" 
           class="px-4 py-2 text-sm font-medium whitespace-nowrap 
                  @if(Route::is('orders.unpaid')) text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 rounded-lg
                  @else text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 @endif">
            Belum Bayar
        </a>
        
        <a href="{{ route('orders.processing') }}" 
           class="px-4 py-2 text-sm font-medium whitespace-nowrap 
                  @if(Route::is('orders.processing')) text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 rounded-lg
                  @else text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 @endif">
            Dikemas
        </a>
        
        <a href="{{ route('orders.shipped') }}" 
           class="px-4 py-2 text-sm font-medium whitespace-nowrap 
                  @if(Route::is('orders.shipped')) text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 rounded-lg
                  @else text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 @endif">
            Dikirim
        </a>
        
        <a href="{{ route('orders.completed') }}" 
           class="px-4 py-2 text-sm font-medium whitespace-nowrap 
                  @if(Route::is('orders.completed')) text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 rounded-lg
                  @else text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 @endif">
            Selesai
        </a>
        
        <a href="{{ route('orders.cancelled') }}" 
           class="px-4 py-2 text-sm font-medium whitespace-nowrap 
                  @if(Route::is('orders.cancelled')) text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 rounded-lg
                  @else text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 @endif">
            Dibatalkan
        </a>
    </div>

      <!-- Unpaid Orders -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
          @if(count($orders) > 0)
              @foreach($orders as $order)
                  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                      <div class="p-6">
                          <div class="flex justify-between items-center mb-4">
                              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pesanan #{{ $order->order_number }}</h3>
                              <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Belum Bayar</span>
                          </div>
                          
                          <div class="space-y-3 text-sm">
                              <div class="flex justify-between">
                                  <span class="text-gray-500 dark:text-gray-400">Tanggal Pesanan:</span>
                                  <span class="text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y, H:i') }}</span>
                              </div>
                              <div class="flex justify-between">
                                  <span class="text-gray-500 dark:text-gray-400">Total:</span>
                                  <span class="text-gray-900 dark:text-white font-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                              </div>
                              <div class="flex justify-between">
                                  <span class="text-gray-500 dark:text-gray-400">Batas Pembayaran:</span>
                                  <span class="text-gray-900 dark:text-white">24 jam setelah checkout</span>
                              </div>
                          </div>
                          
                          <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                              <div class="flex justify-between items-center">
                                  <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                      Lihat Detail
                                  </a>
                                  <button 
                                      class="pay-button inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-300"
                                      data-order-id="{{ $order->id }}"
                                      data-route="{{ route('orders.payment-token', $order->id) }}"
                                      data-success-url="{{ route('orders.show', $order->id) }}?payment_success=true"
                                      data-pending-url="{{ route('orders.show', $order->id) }}?payment_pending=true"
                                  >
                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                      </svg>
                                      Bayar Sekarang
                                  </button>
                              </div>
                          </div>
                      </div>
                  </div>
              @endforeach
          @else
              <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white dark:bg-gray-800 rounded-xl shadow-md p-10 text-center">
                  <div class="w-20 h-20 mx-auto rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-4">
                      <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Tidak Ada Pesanan yang Belum Dibayar</h3>
                  <p class="text-gray-500 dark:text-gray-400 mb-6">Semua pesanan Anda telah dibayar. Terima kasih atas pembayaran Anda.</p>
                  <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors duration-300 shadow-lg hover:shadow-xl">
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                      </svg>
                      Belanja Lagi
                  </a>
              </div>
          @endif
      </div>
      
      @if(count($orders) > 0)
          <div class="mt-6">
              {{ $orders->links() }}
          </div>
      @endif
  </div>
</div>
@endsection

