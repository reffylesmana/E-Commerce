@extends('layouts.seller')

@section('title', 'Kelola Banner - TechnoShop Seller')
@section('description', 'Kelola banner untuk ditampilkan di halaman utama TechnoShop')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="page-header">
        <div class="mb-4"></div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
        </svg>
        <span class="sr-only">Success</span>
        <div class="ml-3 text-sm font-medium">
            {{ session('success') }}
        </div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-3" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
    @endif

    <!-- Actions Panel -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div class="w-full md:w-auto">
            <a href="{{ route('seller.banners.create') }}" class="flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Banner Baru
            </a>
        </div>
    </div>

    <!-- Banner List -->
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Urutan</th>
                        <th class="px-4 py-3">Banner</th>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Link</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Periode</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800" id="banner-list">
                    @forelse($banners as $banner)
                    <tr class="text-gray-700 dark:text-gray-400" data-id="{{ $banner->id }}">
                        <td class="px-4 py-3 handle cursor-move">
                            <div class="flex items-center">
                                <span class="text-gray-500 mr-2">{{ $banner->order }}</span>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                </svg>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div class="relative w-32 h-16 mr-3 rounded-lg md:block">
                                    <img class="object-cover w-full h-full rounded-lg" src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}" loading="lazy">
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $banner->title }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($banner->link_url)
                                <a href="{{ $banner->link_url }}" target="_blank" class="text-blue-600 hover:underline">{{ Str::limit($banner->link_url, 30) }}</a>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $banner->is_active ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : 'text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-100' }}">
                                {{ $banner->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div>
                                @if($banner->start_date)
                                    <p>Mulai: {{ $banner->start_date->format('d M Y') }}</p>
                                @else
                                    <p>Mulai: -</p>
                                @endif
                                
                                @if($banner->end_date)
                                    <p>Berakhir: {{ $banner->end_date->format('d M Y') }}</p>
                                @else
                                    <p>Berakhir: -</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end space-x-4 text-sm">
                                <a href="{{ route('seller.banners.edit', $banner) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('seller.banners.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td colspan="7" class="px-4 py-3 text-sm text-center">
                            Belum ada banner yang dibuat. <a href="{{ route('seller.banners.create') }}" class="text-blue-600 hover:underline">Buat banner baru</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Banner Preview Section -->
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Preview Banner</h2>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
            <div class="relative rounded-xl overflow-hidden shadow-lg">
                <div id="banner-preview" class="relative">
                    <div class="banner-container overflow-hidden">
                        <div class="banner-wrapper flex transition-transform duration-500 ease-in-out h-64">
                            @forelse($banners->where('is_active', true)->sortBy('order') as $banner)
                            <div class="banner-item min-w-full">
                                <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}"
                                    class="w-full h-full object-cover">
                            </div>
                            @empty
                            <div class="banner-item min-w-full flex items-center justify-center bg-gray-200 dark:bg-gray-700">
                                <p class="text-gray-600 dark:text-gray-300">Belum ada banner aktif</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    <button id="banner-prev"
                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/70 rounded-full p-3 shadow-md hover:bg-white transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button id="banner-next"
                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/70 rounded-full p-3 shadow-md hover:bg-white transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Indicators -->
                    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                        @foreach($banners->where('is_active', true)->sortBy('order') as $index => $banner)
                        <button
                            class="banner-indicator w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100 transition-opacity duration-300 {{ $index === 0 ? 'banner-indicator-active opacity-100' : '' }}"></button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Sortable for banner reordering
        const bannerList = document.getElementById('banner-list');
        if (bannerList) {
            new Sortable(bannerList, {
                handle: '.handle',
                animation: 150,
                onEnd: function(evt) {
                    updateBannerOrder();
                }
            });
        }

        // Function to update banner order
        function updateBannerOrder() {
            const banners = [];
            document.querySelectorAll('#banner-list tr').forEach((row, index) => {
                const id = row.getAttribute('data-id');
                if (id) {
                    banners.push({
                        id: id,
                        order: index
                    });
                    
                    // Update the displayed order number
                    const orderSpan = row.querySelector('.handle span');
                    if (orderSpan) {
                        orderSpan.textContent = index;
                    }
                }
            });

            // Send the updated order to the server
            fetch('{{ route("seller.banners.update-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ banners: banners })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                    toast.textContent = 'Urutan banner berhasil diperbarui';
                    document.body.appendChild(toast);
                    
                    // Remove toast after 3 seconds
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error updating banner order:', error);
            });
        }

        // Banner Preview Carousel
        const bannerWrapper = document.querySelector('.banner-wrapper');
        const bannerItems = document.querySelectorAll('.banner-item');
        const bannerPrev = document.getElementById('banner-prev');
        const bannerNext = document.getElementById('banner-next');
        const bannerIndicators = document.querySelectorAll('.banner-indicator');

        if (bannerWrapper && bannerItems.length > 0) {
            let currentBannerIndex = 0;
            const totalBanners = bannerItems.length;

            // Function to update banner position
            function updateBannerPosition() {
                bannerWrapper.style.transform = `translateX(-${currentBannerIndex * 100}%)`;

                // Update indicators
                bannerIndicators.forEach((indicator, index) => {
                    if (index === currentBannerIndex) {
                        indicator.classList.add('opacity-100', 'banner-indicator-active');
                        indicator.classList.remove('opacity-50');
                    } else {
                        indicator.classList.remove('opacity-100', 'banner-indicator-active');
                        indicator.classList.add('opacity-50');
                    }
                });
            }

            // Previous button
            if (bannerPrev) {
                bannerPrev.addEventListener('click', function() {
                    currentBannerIndex = (currentBannerIndex - 1 + totalBanners) % totalBanners;
                    updateBannerPosition();
                });
            }

            // Next button
            if (bannerNext) {
                bannerNext.addEventListener('click', function() {
                    currentBannerIndex = (currentBannerIndex + 1) % totalBanners;
                    updateBannerPosition();
                });
            }

            // Indicator buttons
            bannerIndicators.forEach((indicator, index) => {
                indicator.addEventListener('click', function() {
                    currentBannerIndex = index;
                    updateBannerPosition();
                });
            });

            // Auto-rotate banners every 5 seconds
            if (totalBanners > 1) {
                setInterval(function() {
                    currentBannerIndex = (currentBannerIndex + 1) % totalBanners;
                    updateBannerPosition();
                }, 5000);
            }
        }
    });
</script>
@endsection
