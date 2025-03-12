@extends('layouts.app')

@section('title', 'Your Account')

@push('styles')
<style>
.glass-effect {
    backdrop-filter: blur(12px) saturate(180%);
    background-color: rgba(239, 246, 255, 0.7); /* blue-50 with opacity */
}
.dark .glass-effect {
    background-color: rgba(30, 58, 138, 0.1); /* dark blue with opacity */
}
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="container mx-auto px-4">
        <!-- Profile Section -->
        <div class="max-w-4xl mx-auto">
            @auth
                <div class="glass-effect rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/30">
                    <div class="p-8 sm:p-10">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8">
                            <div class="relative group">
                              <div class="w-32 h-32 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg transform transition-all duration-300 group-hover:scale-105 group-hover:rotate-3">
                                    {{ substr(auth()->user()->username, 0, 1) }}
                                </div>
                                <div class="absolute -bottom-3 -right-3 w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center text-white shadow-lg transform transition-all duration-300 group-hover:scale-110">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-center sm:text-left flex-1">
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ auth()->user()->username }}</h1>
                                <p class="text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                
                                <div class="mt-6 grid grid-cols-2 gap-4 sm:flex sm:flex-wrap sm:gap-6">
                                  <div class="p-4 rounded-2xl bg-blue-50/30 dark:bg-blue-900/30 border border-blue-100/50 dark:border-blue-800/50">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Orders</p>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->orders_count ?? 0 }}</p>
                                    </div>
                                    <div class="p-4 rounded-2xl bg-blue-50/30 dark:bg-blue-900/30 border border-blue-100/50 dark:border-blue-800/50">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Spent</p>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format(auth()->user()->total_spent ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap justify-center sm:justify-start gap-4 mt-8">
                                  <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-xl hover:from-blue-600 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200 rounded-xl hover:bg-blue-200 dark:hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 border border-blue-200 dark:border-blue-700">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="glass-effect rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/30 p-10 text-center">
                    <div class="w-24 h-24 mx-auto rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mb-8 float-animation">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Welcome Back!</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">Sign in to access your account dashboard, track orders, and manage your profile.</p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            Login to Your Account
                        </a>
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 border border-gray-200 dark:border-gray-600">
                            Create New Account
                        </a>
                    </div>
                </div>
            @endauth

            @auth
                <!-- Recent Orders -->
                <div class="mt-10">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Recent Orders</h2>
                        <a href="#" class="inline-flex items-center text-blue-500 hover:text-blue-600 text-sm font-medium">
                            View All
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="glass-effect rounded-3xl shadow-xl border border-white/20 dark:border-gray-700/30 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order ID</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                {{-- <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse(auth()->user()->orders()->latest()->take(5)->get() as $order)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">#{{ $order->id }}</div>
                                                <div class="text-xs text-gray-500">{{ $order->reference }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $order->created_at->format('M d, Y') }}
                                                <div class="text-xs">{{ $order->created_at->format('h:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                                                <div class="text-xs text-gray-500">{{ $order->items_count }} items</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($order->status === 'completed') bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-400
                                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800/20 dark:text-yellow-400
                                                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-800/20 dark:text-blue-400
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-800/20 dark:text-gray-400
                                                    @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="#" class="text-blue-500 hover:text-blue-600 transition-colors duration-200">View Details</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <div class="float-animation">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                    </svg>
                                                </div>
                                                <p class="mt-4 text-gray-500 dark:text-gray-400 font-medium">No orders found</p>
                                                <p class="mt-2 text-sm text-gray-400 dark:text-gray-500">Start shopping to see your orders here</p>
                                                <a href="{{ route('products.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-blue-500 hover:text-blue-600">
                                                    Browse Products
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody> --}}
                            </table>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection