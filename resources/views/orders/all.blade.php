@extends('layouts.app')

@section('title', 'Semua Pesanan - TechnoShop')
@section('description', 'Lihat dan kelola semua pesanan Anda di TechnoShop')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-6 sm:py-8">
  <div class="container mx-auto px-4">
      <!-- Breadcrumb -->
      <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
          <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                  <a href="{{ route('home') }}" class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                      <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                          <path d="M10.707 2.293a1 1 0 0-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
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
                      <span class="ml-1 text-gray-500 dark:text-gray-400 md:ml-2">Semua Pesanan</span>
                  </div>
              </li>
          </ol>
      </nav>

      <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Semua Pesanan</h1>

      <!-- Order Statistics Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <!-- Total Orders Card -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
              <div class="p-5">
                  <div class="flex items-center">
                      <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                          <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                          </svg>
                      </div>
                      <div class="ml-4">
                          <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pesanan</h2>
                          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $orders->total() }}</p>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Pending Orders Card -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
              <div class="p-5">
                  <div class="flex items-center">
                      <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-lg">
                          <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                      </div>
                      <div class="ml-4">
                          <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">Belum Bayar</h2>
                          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $orders->where('status', 'pending')->count() }}</p>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Processing Orders Card -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
              <div class="p-5">
                  <div class="flex items-center">
                      <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg">
                          <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                          </svg>
                      </div>
                      <div class="ml-4">
                          <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">Dalam Proses</h2>
                          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $orders->whereIn('status', ['processing', 'shipped'])->count() }}</p>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Completed Orders Card -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
              <div class="p-5">
                  <div class="flex items-center">
                      <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                          <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                          </svg>
                      </div>
                      <div class="ml-4">
                          <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">Selesai</h2>
                          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $orders->where('status', 'selesai')->count() }}</p>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <!-- Orders Table -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden mb-6">
          @if($orders->count() > 0)
              <div class="overflow-x-auto">
                  <table class="w-full">
                      <thead>
                          <tr class="bg-gray-50 dark:bg-gray-700/50">
                              <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 dark:text-gray-300">No. Pesanan</th>
                              <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</th>
                              <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Total</th>
                              <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Status</th>
                              <th class="px-6 py-4 text-right text-sm font-medium text-gray-700 dark:text-gray-300">Aksi</th>
                          </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                          @foreach($orders as $order)
                              <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</td>
                                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $order->created_at->format('d M Y') }}</td>
                                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                  <td class="px-6 py-4 whitespace-nowrap">
                                      @if($order->status == 'pending')
                                          <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Belum Bayar</span>
                                      @elseif($order->status == 'processing')
                                          <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Dikemas</span>
                                      @elseif($order->status == 'shipped')
                                          <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Dikirim</span>
                                      @elseif($order->status == 'completed')
                                          <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Selesai</span>
                                      @elseif($order->status == 'cancelled')
                                          <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Dibatalkan</span>
                                      @endif
                                  </td>
                                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                      <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mr-4">
                                          Lihat Detail
                                      </a>
                                      
                                      @if($order->status == 'pending' && $order->payment_expires_at && $order->payment_expires_at > now())
                                          <a href="{{ route('orders.show', $order->id) }}#pay" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                              Bayar
                                          </a>
                                      @endif
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
              
              <div class="px-6 py-4">
                  {{ $orders->links() }}
              </div>
          @else
              <div class="p-10 text-center">
                  <div class="w-20 h-20 mx-auto rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-4">
                      <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                      </svg>
                  </div>
                  <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Pesanan</h3>
                  <p class="text-gray-500 dark:text-gray-400 mb-6">Anda belum melakukan pemesanan. Mulai belanja untuk melihat pesanan Anda di sini.</p>
                  <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors duration-300 shadow-lg hover:shadow-xl">
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                      </svg>
                      Mulai Belanja
                  </a>
              </div>
          @endif
      </div>
  </div>
</div>
@endsection

