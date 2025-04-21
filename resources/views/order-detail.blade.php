@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number . ' - TechnoShop')
@section('description', 'Detail pesanan dan status pengiriman Anda di TechnoShop')

@push('styles')
    <style>
        .progress-step {
            position: relative;
        }

        .progress-step::after {
            content: '';
            position: absolute;
            top: 24px;
            left: 50%;
            width: 100%;
            height: 2px;
            background-color: #e5e7eb;
            z-index: 0;
        }

        .progress-step:last-child::after {
            display: none;
        }

        .progress-step.active .step-circle {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .progress-step.active::after {
            background-color: #3b82f6;
        }

        .progress-step.completed .step-circle {
            background-color: #10b981;
            border-color: #10b981;
            color: white;
        }

        .progress-step.completed::after {
            background-color: #10b981;
        }

        .dark .progress-step::after {
            background-color: #374151;
        }

        .dark .progress-step.active::after {
            background-color: #3b82f6;
        }

        .dark .progress-step.completed::after {
            background-color: #10b981;
        }

        .order-item {
            transition: all 0.3s ease;
        }

        .order-item:hover {
            background-color: rgba(243, 244, 246, 0.5);
        }

        .dark .order-item:hover {
            background-color: rgba(55, 65, 81, 0.5);
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse-slow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
@endpush

@section('content')
    <div
        class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen py-8">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}"
                            class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('account.index') }}"
                                class="ml-1 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 md:ml-2">Akun
                                Saya</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('orders.all') }}"
                                class="ml-1 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 md:ml-2">Pesanan
                                Saya</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500 dark:text-gray-400 md:ml-2">Detail Pesanan</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="max-w-6xl mx-auto">
                <!-- Order Header -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-6 border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Pesanan
                                    #{{ $order->order_number }}</h1>
                                <p class="text-gray-500 dark:text-gray-400">Dibuat pada
                                    {{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                @if ($order->status == 'pending')
                                    <span
                                        class="px-3 py-1.5 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Belum
                                        Bayar</span>
                                @elseif($order->status == 'processing')
                                    <span
                                        class="px-3 py-1.5 text-sm font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Dikemas</span>
                                @elseif($order->status == 'shipped')
                                    <span
                                        class="px-3 py-1.5 text-sm font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Dikirim</span>
                                @elseif($order->status == 'completed')
                                    <span
                                        class="px-3 py-1.5 text-sm font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Selesai</span>
                                @elseif($order->status == 'cancelled')
                                    <span
                                        class="px-3 py-1.5 text-sm font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Dibatalkan</span>
                                @endif
                            </div>

                        </div>

                        <!-- Order Progress Tracker -->
                        <div class="mb-8">
                            <div class="flex flex-wrap justify-between">
                                <div
                                    class="progress-step {{ $order->status != 'cancelled' ? 'completed' : '' }} w-1/4 text-center">
                                    <div
                                        class="step-circle w-12 h-12 mx-auto rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 relative z-10">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="text-xs mt-2 font-medium text-gray-700 dark:text-gray-300">Pesanan Dibuat
                                    </div>
                                </div>

                                <div
                                    class="progress-step {{ in_array($order->status, ['processing', 'shipped', 'completed']) ? 'completed' : ($order->status == 'pending' ? 'active' : '') }} w-1/4 text-center">
                                    <div
                                        class="step-circle w-12 h-12 mx-auto rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 relative z-10">
                                        @if (in_array($order->status, ['processing', 'shipped', 'completed']))
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="text-xs mt-2 font-medium text-gray-700 dark:text-gray-300">Dikemas</div>
                                </div>

                                <div
                                    class="progress-step {{ in_array($order->status, ['shipped', 'completed']) ? 'completed' : ($order->status == 'processing' ? 'active' : '') }} w-1/4 text-center">
                                    <div
                                        class="step-circle w-12 h-12 mx-auto rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 relative z-10">
                                        @if (in_array($order->status, ['shipped', 'completed']))
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="text-xs mt-2 font-medium text-gray-700 dark:text-gray-300">Dikirim</div>
                                </div>

                                <div
                                    class="progress-step {{ $order->status == 'completed' ? 'completed' : ($order->status == 'shipped' ? 'active' : '') }} w-1/4 text-center">
                                    <div
                                        class="step-circle w-12 h-12 mx-auto rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center text-gray-700 dark:text-gray-300 relative z-10">
                                        @if ($order->status == 'completed')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="text-xs mt-2 font-medium text-gray-700 dark:text-gray-300">Beri Penilaian
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Order Items and Status -->
                    <div class="lg:col-span-2">
                        <!-- Order Status Content -->
                        @if ($order->status == 'pending')
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-6 border border-gray-100 dark:border-gray-700">
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Status Pembayaran
                                    </h2>

                                    <div
                                        class="p-4 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800/30 mb-6">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <svg class="w-5 h-5 text-yellow-500" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                                    Menunggu Pembayaran</h3>
                                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                                    <p>Silakan selesaikan pembayaran sebelum batas waktu berakhir.</p>

                                                    @php
                                                        $now = \Carbon\Carbon::now();
                                                        $expiryTime = $order->payment_expires_at
                                                            ? \Carbon\Carbon::parse($order->payment_expires_at)
                                                            : null;
                                                        $isExpired = $expiryTime ? $now->gt($expiryTime) : true;
                                                        $remainingTime =
                                                            $expiryTime && !$isExpired
                                                                ? $now->diffInSeconds($expiryTime)
                                                                : 0;
                                                    @endphp

                                                    @if (!$isExpired && $remainingTime > 0)
                                                        <div class="mt-3 mb-4">
                                                            <div class="flex items-center justify-between mb-1">
                                                                <span
                                                                    class="text-xs text-yellow-700 dark:text-yellow-300">Batas
                                                                    Waktu: {{ $expiryTime->format('d M Y, H:i') }}</span>
                                                                <span
                                                                    class="text-xs font-medium text-yellow-700 dark:text-yellow-300"
                                                                    id="countdown-timer">--:--:--</span>
                                                            </div>
                                                            <div
                                                                class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                                @php
                                                                    $percentage = min(
                                                                        100,
                                                                        max(0, ($remainingTime / 86400) * 100),
                                                                    );
                                                                @endphp
                                                                <div class="bg-yellow-500 h-2 rounded-full"
                                                                    style="width: {{ $percentage }}%"></div>
                                                            </div>
                                                        </div>

                                                        <script>
                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                let remainingSeconds = {{ $remainingTime }};
                                                                const countdownEl = document.getElementById('countdown-timer');

                                                                function updateCountdown() {
                                                                    if (remainingSeconds <= 0) {
                                                                        countdownEl.innerHTML = 'Waktu habis';
                                                                        countdownEl.classList.add('text-red-600', 'dark:text-red-400');
                                                                        return;
                                                                    }

                                                                    const hours = Math.floor(remainingSeconds / 3600);
                                                                    const minutes = Math.floor((remainingSeconds % 3600) / 60);
                                                                    const seconds = remainingSeconds % 60;

                                                                    countdownEl.innerHTML =
                                                                        hours.toString().padStart(2, '0') + ':' +
                                                                        minutes.toString().padStart(2, '0') + ':' +
                                                                        seconds.toString().padStart(2, '0') + ':' +
                                                                        seconds.toString().padStart(2, '0');

                                                                    remainingSeconds--;
                                                                }

                                                                updateCountdown();
                                                                const countdownInterval = setInterval(function() {
                                                                    updateCountdown();

                                                                    if (remainingSeconds <= 0) {
                                                                        clearInterval(countdownInterval);
                                                                    }
                                                                }, 1000);
                                                            });
                                                        </script>

                                                        <div class="mt-4">
                                                            <button
                                                                class="pay-button inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-lg transition-all duration-300 shadow-md hover:shadow-lg"
                                                                data-order-id="{{ $order->id }}"
                                                                data-route="{{ route('orders.payment-token', $order->id) }}"
                                                                data-success-url="{{ route('orders.show', $order->id) }}?payment_success=true"
                                                                data-pending-url="{{ route('orders.show', $order->id) }}?payment_pending=true">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-5 mr-2" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                                </svg>
                                                                Bayar Sekarang
                                                            </button>
                                                        </div>
                                                    @else
                                                        <p class="mt-2 text-red-600 dark:text-red-400 font-medium">Waktu
                                                            pembayaran telah habis. Silakan buat pesanan baru.</p>
                                                        <div class="mt-4">
                                                            <a href="{{ route('products.index') }}"
                                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-300">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-5 mr-2" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                                </svg>
                                                                Belanja Lagi
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($order->status == 'processing')
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-6 border border-gray-100 dark:border-gray-700">
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Status Pesanan
                                    </h2>

                                    <div
                                        class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/30 mb-6">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <svg class="w-5 h-5 text-blue-500" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Pesanan
                                                    Sedang Dikemas</h3>
                                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                                    <p>Pesanan Anda sedang dikemas oleh penjual. Anda akan mendapatkan
                                                        notifikasi ketika pesanan dikirim.</p>

                                                    <div class="mt-4 flex items-center">
                                                        <div
                                                            class="relative w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                                                            <div
                                                                class="absolute top-0 left-0 h-full bg-blue-500 w-1/2 animate-pulse-slow">
                                                            </div>
                                                        </div>
                                                        <span
                                                            class="ml-3 text-xs text-gray-500 dark:text-gray-400">50%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($order->status == 'shipped')
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-6 border border-gray-100 dark:border-gray-700">
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Status Pesanan
                                    </h2>

                                    <div
                                        class="p-4 rounded-xl bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800/30 mb-6">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <svg class="w-5 h-5 text-purple-500" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-purple-800 dark:text-purple-200">
                                                    Pesanan Dalam Pengiriman</h3>
                                                <div class="mt-2 text-sm text-purple-700 dark:text-purple-300">
                                                    <p>Pesanan Anda sedang dalam perjalanan. Nomor resi: <span
                                                            class="font-medium">{{ $order->shipping->tracking_number ?? 'Belum tersedia' }}</span>
                                                    </p>

                                                    <div class="mt-4 flex items-center">
                                                        <div
                                                            class="relative w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                                                            <div
                                                                class="absolute top-0 left-0 h-full bg-purple-500 w-3/4 animate-pulse-slow">
                                                            </div>
                                                        </div>
                                                        <span
                                                            class="ml-3 text-xs text-gray-500 dark:text-gray-400">75%</span>
                                                    </div>

                                                    <div class="mt-4">
                                                        <form method="POST"
                                                            action="{{ route('shipments.mark-delivered', $order->shipping->id) }}">
                                                            @csrf
                                                            <button type="submit"
                                                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-5 mr-2" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                Pesanan Diterima
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Lacak Pengiriman
                                    </h3>

                                    <div class="relative pb-12">
                                        <!-- Timeline track -->
                                        <div class="absolute h-full w-0.5 bg-gray-200 dark:bg-gray-700 left-6 top-0"></div>

                                        <!-- Timeline items -->
                                        <div class="relative flex items-start mb-6">
                                            <div
                                                class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 z-10">
                                                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <h4 class="text-sm font-medium text-gray-800 dark:text-white">Pesanan
                                                    Dikirim</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $order->shipping->shipped_at ? $order->shipping->shipped_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }}
                                                </p>
                                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Pesanan Anda telah
                                                    dikirim oleh penjual.</p>
                                            </div>
                                        </div>

                                        <div class="relative flex items-start mb-6">
                                            <div
                                                class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 z-10">
                                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <h4 class="text-sm font-medium text-gray-800 dark:text-white">Pesanan
                                                    Dikemas</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $order->updated_at->format('d M Y, H:i') }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Pesanan Anda telah
                                                    dikemas oleh penjual.</p>
                                            </div>
                                        </div>

                                        <div class="relative flex items-start">
                                            <div
                                                class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 z-10">
                                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <h4 class="text-sm font-medium text-gray-800 dark:text-white">Pembayaran
                                                    Berhasil</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $order->transaction->updated_at->format('d M Y, H:i') }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Pembayaran Anda
                                                    telah berhasil diverifikasi.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($order->status == 'completed')
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-6 border border-gray-100 dark:border-gray-700">
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Status Pesanan
                                    </h2>

                                    <div
                                        class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30 mb-6">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <svg class="w-5 h-5 text-green-500" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Pesanan
                                                    Selesai</h3>
                                                <div class="mt-2 text-sm text-green-700 dark:text-green-300">
                                                    <p>Pesanan Anda telah selesai. Terima kasih telah berbelanja di
                                                        TechnoShop!</p>

                                                    <div class="mt-4 flex items-center">
                                                        <div
                                                            class="relative w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full overflow-hidden">
                                                            <div class="absolute top-0 left-0 h-full bg-green-500 w-full">
                                                            </div>
                                                        </div>
                                                        <span
                                                            class="ml-3 text-xs text-gray-500 dark:text-gray-400">100%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Beri Penilaian
                                    </h3>

                                    <div class="space-y-6">
                                        @foreach ($order->orderItems as $item)
                                            <div
                                                class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                <div class="flex items-center gap-4 mb-4">
                                                    <div
                                                        class="flex-shrink-0 w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                                                        @if ($item->product && $item->product->photos->count() > 0)
                                                            <img src="{{ asset('storage/' . $item->product->photos->first()->photo) }}"
                                                                alt="{{ $item->product->name }}"
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            <div
                                                                class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700">
                                                                <svg class="w-8 h-8 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="text-sm font-medium text-gray-800 dark:text-white">
                                                            {{ $item->product->name }}</h4>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $item->quantity }} x Rp
                                                            {{ number_format($item->price, 0, ',', '.') }}</p>
                                                    </div>
                                                </div>

                                                @php
                                                    $review = \App\Models\Review::where('product_id', $item->product_id)
                                                        ->where('user_id', auth()->id())
                                                        ->first();
                                                @endphp

                                                @if ($review)
                                                    <div class="bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg">
                                                        <div class="flex items-center mb-2">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= $review->rating)
                                                                    <svg class="w-5 h-5 text-yellow-400"
                                                                        fill="currentColor" viewBox="0 0 20 20">
                                                                        <path
                                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                                        </path>
                                                                    </svg>
                                                                @else
                                                                    <svg class="w-5 h-5 text-gray-300 dark:text-gray-600"
                                                                        fill="currentColor" viewBox="0 0 20 20">
                                                                        <path
                                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                                        </path>
                                                                    </svg>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                                            {{ $review->comment }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                                            {{ $review->created_at->format('d M Y') }}</p>
                                                    </div>
                                                @else
                                                    <form action="{{ route('reviews.store') }}" method="POST"
                                                        class="space-y-4">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $item->product_id }}">
                                                        <input type="hidden" name="order_id"
                                                            value="{{ $order->id }}">

                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Beri
                                                                Rating</label>
                                                            <div class="flex items-center">
                                                                <div class="flex space-x-1">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <label
                                                                            for="star-{{ $item->id }}-{{ $i }}"
                                                                            class="cursor-pointer">
                                                                            <input type="radio"
                                                                                id="star-{{ $item->id }}-{{ $i }}"
                                                                                name="star"
                                                                                value="{{ $i }}"
                                                                                class="sr-only peer">
                                                                            <svg class="w-8 h-8 text-gray-300 dark:text-gray-600 hover:text-yellow-400 peer-checked:text-yellow-400"
                                                                                fill="currentColor" viewBox="0 0 20 20">
                                                                                <path
                                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                                                </path>
                                                                            </svg>
                                                                        </label>
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label for="text-{{ $item->id }}"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ulasan
                                                                Anda</label>
                                                            <textarea id="text-{{ $item->id }}" name="text" rows="3"
                                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                                                placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
                                                        </div>

                                                        <button type="submit"
                                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                            Kirim Ulasan
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @elseif($order->status == 'cancelled')
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-6 border border-gray-100 dark:border-gray-700">
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Status Pesanan
                                    </h2>

                                    <div
                                        class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 mb-6">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <svg class="w-5 h-5 text-red-500" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Pesanan
                                                    Dibatalkan</h3>
                                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                                    <p>Pesanan ini telah dibatalkan karena pembayaran tidak diselesaikan
                                                        dalam waktu 24 jam.</p>

                                                    <div class="mt-4">
                                                        <a href="{{ route('products.index') }}"
                                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-lg transition-all duration-300 shadow-md hover:shadow-lg">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                            </svg>
                                                            Belanja Lagi
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Order Items -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-6 border border-gray-100 dark:border-gray-700">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Produk yang Dibeli
                                </h2>

                                <div class="space-y-4">
                                    @foreach ($order->orderItems as $item)
                                        <div class="order-item flex items-center gap-4 p-3 rounded-xl">
                                            <div
                                                class="flex-shrink-0 w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                                                @if ($item->product && $item->product->photos->count() > 0)
                                                    <img src="{{ asset('storage/' . $item->product->photos->first()->photo) }}"
                                                        alt="{{ $item->product->name }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div
                                                        class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-800 dark:text-white">
                                                    {{ $item->product->name }}</h4>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->quantity }}
                                                    x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            </div>
                                            <div class="text-sm font-medium text-gray-800 dark:text-white">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden sticky top-6 border border-gray-100 dark:border-gray-700">
                            <div class="p-6">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Ringkasan Pesanan</h2>

                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                        <span class="text-gray-800 dark:text-white font-medium">
                                            Rp {{ number_format($order->orderItems->sum('subtotal'), 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Pengiriman</span>
                                        <span class="text-gray-800 dark:text-white font-medium">
                                            Rp {{ number_format($order->transaction->shipping_cost ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Pajak (11%)</span>
                                        <span class="text-gray-800 dark:text-white font-medium">
                                            Rp {{ number_format($order->transaction->tax_amount ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div
                                        class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-3 flex justify-between">
                                        <span class="text-lg font-semibold text-gray-800 dark:text-white">Total</span>
                                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                                    <h3 class="text-base font-semibold text-gray-800 dark:text-white mb-3">Informasi
                                        Pengiriman</h3>

                                    <div class="space-y-2 text-sm">
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Alamat Pengiriman:</span>
                                            <p class="text-gray-800 dark:text-white mt-1">{{ $order->shipping_address }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Metode Pengiriman:</span>
                                            <p class="text-gray-800 dark:text-white mt-1">
                                                {{ ucfirst($order->shipping_method ?? 'Reguler') }}</p>
                                        </div>
                                        @if ($order->shipping && $order->shipping->tracking_number)
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-400">Nomor Resi:</span>
                                                <p class="text-gray-800 dark:text-white mt-1">
                                                    {{ $order->shipping->tracking_number }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                                    <h3 class="text-base font-semibold text-gray-800 dark:text-white mb-3">Informasi
                                        Pembayaran</h3>

                                    <div class="space-y-2 text-sm">

                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Status Pembayaran:</span>
                                            <p
                                                class="mt-1 {{ $order->status == 'pending' ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400' }} font-medium">
                                                {{ $order->status == 'pending' ? 'Menunggu Pembayaran' : 'Lunas' }}
                                            </p>
                                        </div>
                                        @if ($payment)
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-400">Metode Pembayaran:</span>
                                                <p class="text-gray-800 dark:text-white mt-1">
                                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? 'Tidak tersedia')) }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if ($order->notes)
                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                                        <h3 class="text-base font-semibold text-gray-800 dark:text-white mb-3">Catatan</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $order->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
@endsection
