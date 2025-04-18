@extends('layouts.app')

@section('title', 'Akun Saya - TechnoShop')
@section('description', 'Kelola akun dan pesanan Anda di TechnoShop')

@push('styles')
    <style>
        .glass-effect {
            backdrop-filter: blur(12px) saturate(180%);
            background-color: rgba(239, 246, 255, 0.7);
            /* blue-50 with opacity */
        }

        .dark .glass-effect {
            background-color: rgba(30, 58, 138, 0.1);
            /* dark blue with opacity */
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>
@endpush

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
        <div class="container mx-auto px-4">
            <!-- Profile Section -->
            <div class="max-w-6xl mx-auto">
                @auth
                    <div class="glass-effect rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/30">
                        <div class="p-8 sm:p-10">
                            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8">
                                <div class="relative group">
                                    @if(auth()->user()->image)
                                        <div class="w-32 h-32 rounded-2xl overflow-hidden shadow-lg transform transition-all duration-300 group-hover:scale-105 group-hover:rotate-3">
                                            <img src="{{ asset('storage/images/' . auth()->user()->image) }}" 
                                                 alt="Profile Picture" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg transform transition-all duration-300 group-hover:scale-105 group-hover:rotate-3">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="absolute -bottom-3 -right-3 w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center text-white shadow-lg transform transition-all duration-300 group-hover:scale-110">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="text-center sm:text-left flex-1">
                                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ auth()->user()->name }}
                                    </h1>
                                    <p class="text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>

                                    <div class="mt-6 grid grid-cols-2 gap-4 sm:flex sm:flex-wrap sm:gap-6">
                                        <div
                                            class="p-4 rounded-2xl bg-blue-50/30 dark:bg-blue-900/30 border border-blue-100/50 dark:border-blue-800/50">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Pesanan</p>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $orderCount ?? 0 }}
                                            </p>
                                        </div>
                                        <div
                                            class="p-4 rounded-2xl bg-blue-50/30 dark:bg-blue-900/30 border border-blue-100/50 dark:border-blue-800/50">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Belanja</p>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp
                                                {{ number_format($totalSpent ?? 0, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap justify-center sm:justify-start gap-4 mt-8">
                                        <a href="{{ route('profile.edit.buyer') }}"
                                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-xl hover:from-blue-600 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            Edit Profil
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center px-6 py-3 bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200 rounded-xl hover:bg-blue-200 dark:hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 border border-blue-200 dark:border-blue-700">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                    </path>
                                                </svg>
                                                Logout
                                            </button>
                                        </form>
                                        <button onclick="toggleDarkMode()"
                                            class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 border border-gray-200 dark:border-gray-700">
                                            <svg class="w-5 h-5 mr-2 dark:hidden" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                                                </path>
                                            </svg>
                                            <svg class="w-5 h-5 mr-2 hidden dark:block" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707">
                                                </path>
                                            </svg>
                                            <span class="dark:hidden">Mode Gelap</span>
                                            <span class="hidden dark:block">Mode Terang</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div
                        class="glass-effect rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/30 p-10 text-center">
                        <div
                            class="w-24 h-24 mx-auto rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mb-8 float-animation">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Selamat Datang!</h2>
                        <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">Silakan login untuk mengakses
                            dashboard akun Anda, melacak pesanan, dan mengelola profil Anda.</p>
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <a href="{{ route('login') }}"
                                class="px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                Login ke Akun Anda
                            </a>
                            <a href="{{ route('register') }}"
                                class="px-8 py-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 border border-gray-200 dark:border-gray-600">
                                Buat Akun Baru
                            </a>
                        </div>
                    </div>
                @endauth

                @auth
                    <!-- Order Navigation Tabs -->
                    <div class="mt-10">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pesanan Saya</h2>
                        </div>

                        <div class="glass-effect rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/30 overflow-hidden">
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 p-4">
                                <!-- Semua Pesanan -->
                                <a href="{{ route('orders.all') }}" class="relative flex flex-col items-center p-4 rounded-xl hover:bg-blue-100/50 dark:hover:bg-blue-900/30 transition-colors">
                                    <div class="w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-2">
                                        <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Semua Pesanan</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $orderCount ?? 0 }}</span>
                                </a>
                        
                                <!-- Belum Bayar -->
                                <a href="{{ route('orders.unpaid') }}" class="relative flex flex-col items-center p-4 rounded-xl hover:bg-yellow-100/50 dark:hover:bg-yellow-900/30 transition-colors">
                                    <div class="w-14 h-14 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center mb-2">
                                        <svg class="w-7 h-7 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Belum Bayar</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $unpaidCount ?? 0 }}</span>
                                    @if ($unpaidCount ?? 0 > 0)
                                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $unpaidCount }}</span>
                                    @endif
                                </a>
                        
                                <!-- Dikemas -->
                                <a href="{{ route('orders.processing') }}" class="relative flex flex-col items-center p-4 rounded-xl hover:bg-teal-100/50 dark:hover:bg-teal-900/30 transition-colors">
                                    <div class="w-14 h-14 rounded-full bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center mb-2">
                                        <svg class="w-7 h-7 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Dikemas</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $processingCount ?? 0 }}</span>
                                    @if ($processingCount ?? 0 > 0)
                                        <span class="absolute -top-1 -right-1 bg-teal-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $processingCount }}</span>
                                    @endif
                                </a>
                        
                                <!-- Dikirim -->
                                <a href="{{ route('orders.shipped') }}" class="relative flex flex-col items-center p-4 rounded-xl hover:bg-purple-100/50 dark:hover:bg-purple-900/30 transition-colors">
                                    <div class="w-14 h-14 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-2">
                                        <svg class="w-7 h-7 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Dikirim</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $shippedCount ?? 0 }}</span>
                                    @if ($shippedCount ?? 0 > 0)
                                        <span class="absolute -top-1 -right-1 bg-purple-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $shippedCount }}</span>
                                    @endif
                                </a>
                        
                                <!-- Selesai -->
                                <a href="{{ route('orders.completed') }}" class="relative flex flex-col items-center p-4 rounded-xl hover:bg-green-100/50 dark:hover:bg-green-900/30 transition-colors">
                                    <div class="w-14 h-14 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mb-2">
                                        <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Selesai</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $completedCount ?? 0 }}</span>
                                </a>
                        
                                <!-- Dibatalkan -->
                                <a href="{{ route('orders.cancelled') }}" class="relative flex flex-col items-center p-4 rounded-xl hover:bg-red-100/50 dark:hover:bg-red-900/30 transition-colors">
                                    <div class="w-14 h-14 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-2">
                                        <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Dibatalkan</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $canceledCount ?? 0 }}</span>
                                    @if ($canceledCount ?? 0 > 0)
                                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $canceledCount }}</span>
                                    @endif
                                </a>
                        
                                <!-- Penilaian -->
                                <a href="{{ route('reviews.index') }}" class="relative flex flex-col items-center p-4 rounded-xl hover:bg-pink-100/50 dark:hover:bg-pink-900/30 transition-colors">
                                    <div class="w-14 h-14 rounded-full bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center mb-2">
                                        <svg class="w-7 h-7 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Penilaian</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $reviewCount ?? 0 }}</span>
                                    @if ($reviewNeededCount ?? 0 > 0)
                                        <span class="absolute -top-1 -right-1 bg-pink-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $reviewNeededCount }}</span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="mt-10">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pesanan Terbaru</h2>
                            <a href="{{ route('orders.all') }}"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                Lihat Semua
                            </a>
                        </div>

                        <div
                            class="glass-effect rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/30 overflow-hidden">
                            @if (count($recentOrders ?? []) > 0)
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="bg-blue-50/50 dark:bg-blue-900/20">
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    No. Pesanan</th>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Tanggal</th>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Total</th>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Status</th>
                                                <th
                                                    class="px-6 py-4 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach ($recentOrders as $order)
                                                <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors">
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $order->order_number }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                        {{ $order->created_at->format('d M Y') }}</td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if ($order->status == 'pending')
                                                            <span
                                                                class="px-2.5 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Belum
                                                                Bayar</span>
                                                        @elseif($order->status == 'processing')
                                                            <span
                                                                class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Dikemas</span>
                                                        @elseif($order->status == 'shipped')
                                                            <span
                                                                class="px-2.5 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Dikirim</span>
                                                        @elseif($order->status == 'completed')
                                                            <span
                                                                class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Selesai</span>
                                                        @elseif($order->status == 'cancelled')
                                                            <span
                                                                class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Dibatalkan</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('orders.show', $order->id) }}"
                                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                            Lihat Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-10 text-center">
                                    <div
                                        class="w-20 h-20 mx-auto rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Pesanan</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-6">Anda belum melakukan pemesanan. Mulai
                                        belanja untuk melihat pesanan Anda di sini.</p>
                                    <a href="{{ route('products.index') }}"
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors duration-300 shadow-lg hover:shadow-xl">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                        Mulai Belanja
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endauth
                @auth
                    <!-- Wishlist Section -->
                    <div class="mt-10">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Wishlist Saya</h2>
                            <a href="{{ route('products.index') }}"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                                Lihat Semua Produk
                            </a>
                        </div>

                        <div
                            class="glass-effect rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/30 overflow-hidden">
                            @if ($wishlists->isEmpty())
                                <div class="p-10 text-center">
                                    <div
                                        class="w-20 h-20 mx-auto rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Wishlist Kosong</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-6">Tambahkan produk favorit Anda ke wishlist
                                        untuk melihatnya di sini</p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="bg-blue-50/50 dark:bg-blue-900/20">
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Produk</th>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Harga</th>
                                                <th
                                                    class="px-6 py-4 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Kategori</th>
                                                <th
                                                    class="px-6 py-4 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach ($wishlists as $wishlist)
                                                <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors">
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-16 w-16">
                                                                <img class="h-16 w-16 rounded-lg object-cover"
                                                                    src="{{ asset('storage/' . $wishlist->product->photos->first()->photo) }}"
                                                                    alt="{{ $wishlist->product->name }}">
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                                    {{ $wishlist->product->name }}
                                                                </div>
                                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                    {{ $wishlist->product->store->name }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                        Rp {{ number_format($wishlist->product->price, 0, ',', '.') }}
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                                        {{ $wishlist->product->category->name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <div class="flex items-center justify-end space-x-4">
                                                            <a href="{{ route('show', $wishlist->product->slug) }}"
                                                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                                Lihat Produk
                                                            </a>
                                                            <form
                                                                action="{{ route('wishlist.remove', $wishlist->product->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                                                    onclick="return confirm('Hapus dari wishlist?')">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                                    {{ $wishlists->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection
