@extends('layouts.seller')

@section('title', 'Notificiation - Seller Dashboard')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Notifikasi Seller</h1>
            <div class="flex space-x-2">
                <form action="{{ route('seller.notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Tandai Semua Dibaca
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="border-b border-gray-200 pb-4 mb-4 last:border-0">
                    <a href="{{ $notification->data['url'] ?? '#' }}" 
                       class="block p-4 hover:bg-gray-50 rounded-lg {{ $notification->read_at ? '' : 'bg-blue-50' }} transition">
                        <div class="flex items-start">
                            <!-- Icon -->
                            <div class="flex-shrink-0 bg-purple-100 rounded-full p-3 mr-4">
                                <iconify-icon icon="heroicons:bell" class="text-purple-600 text-xl"></iconify-icon>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1">
                                <p class="text-lg font-medium text-gray-900">
                                    {{ $notification->data['title'] ?? 'Notifikasi Seller' }}
                                </p>
                                <p class="text-gray-600 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-sm text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                    @if(!$notification->read_at)
                                        <span class="text-xs bg-blue-500 text-white px-2 py-1 rounded-full">
                                            Baru
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="text-center py-12">
                    <iconify-icon icon="heroicons:bell-slash" class="mx-auto text-4xl text-gray-400 mb-4"></iconify-icon>
                    <p class="text-gray-500 text-lg">Tidak ada notifikasi</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection