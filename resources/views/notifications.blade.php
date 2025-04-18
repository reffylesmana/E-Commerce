@extends('layouts.app')

@section('title', 'Notifikasi - TechnoShop')
@section('description', 'Lihat semua notifikasi Anda di TechnoShop')

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
                        <span class="ml-1 text-gray-500 dark:text-gray-400 md:ml-2">Notifikasi</span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Notifikasi</h1>

        <!-- Notifications List -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Semua Notifikasi</h2>
                
                @if(count($notifications) > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        Tandai semua sebagai dibaca
                    </button>
                </form>
                @endif
            </div>
            
            @if(count($notifications) > 0)
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($notifications as $notification)
                        <div class="p-6 {{ $notification->read_at ? 'bg-white dark:bg-gray-800' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                            <div class="flex">
                                @if(isset($notification->data['type']) && $notification->data['type'] == 'order')
                                    <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900/30 rounded-full p-3 mr-4">
                                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                @elseif(isset($notification->data['type']) && $notification->data['type'] == 'shipping')
                                    <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/30 rounded-full p-3 mr-4">
                                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                @elseif(isset($notification->data['type']) && $notification->data['type'] == 'promo')
                                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/30 rounded-full p-3 mr-4">
                                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-full p-3 mr-4">
                                        <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                                {{ $notification->data['title'] ?? 'Notifikasi' }}
                                            </h3>
                                            <p class="mt-1 text-gray-600 dark:text-gray-300">
                                                {{ $notification->data['message'] ?? '' }}
                                            </p>
                                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        
                                        <div class="flex space-x-2">
                                            @if(!$notification->read_at)
                                                <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                        Tandai dibaca
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    @if(isset($notification->data['action_url']) && $notification->data['action_url'] !== '#')
                                        <div class="mt-3">
                                            <a href="{{ $notification->data['action_url'] }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                {{ $notification->data['action_text'] ?? 'Lihat Detail' }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="mt-4 text-lg">Anda belum memiliki notifikasi</p>
                    <p class="mt-2">Notifikasi tentang pesanan, pengiriman, dan promosi akan muncul di sini</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
