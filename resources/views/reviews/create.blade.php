@extends('layouts.app')

@section('title', 'Beri Penilaian - TechnoShop')
@section('description', 'Berikan penilaian dan ulasan untuk produk yang telah Anda beli di TechnoShop')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-6 sm:py-8">
    <div class="container mx-auto px-4 max-w-4xl">
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
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('orders.completed') }}" class="ml-1 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 md:ml-2">Pesanan Selesai</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500 dark:text-gray-400 md:ml-2">Beri Penilaian</span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Beri Penilaian</h1>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden mb-6">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pesanan #{{ $order->order_number }}</h2>
                
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Tanggal Pembelian: {{ $order->created_at->format('d M Y') }}</div>
                </div>

                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    
                    @foreach($order->orderItems as $item)
                    <div class="border-b border-gray-200 dark:border-gray-700 py-4 mb-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-20 h-20 rounded-md overflow-hidden">
                                @if($item->product->photos->count() > 0)
                                <img src="{{ asset('storage/' . $item->product->photos->first()->photo) }}"
                                alt="Foto Produk" class="object-cover w-full h-full">
                                @else
                                    <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-base font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->variant ?? 'Standar' }} • {{ $item->quantity }} item</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <input type="hidden" name="reviews[{{ $loop->index }}][product_id]" value="{{ $item->product_id }}">
                        <input type="hidden" name="reviews[{{ $loop->index }}][order_item_id]" value="{{ $item->id }}">
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                <button type="button" 
                                        class="rating-star text-2xl focus:outline-none" 
                                        data-index="{{ $loop->index }}" 
                                        data-value="{{ $i }}">
                                    <span class="text-gray-300 dark:text-gray-600 hover:text-yellow-400">★</span>
                                </button>
                                @endfor
                                <input type="hidden" name="reviews[{{ $loop->index }}][rating]" id="rating-input-{{ $loop->index }}" value="0" required>
                            </div>
                            @error("reviews.{$loop->index}.rating")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-4">
                            <label for="review-{{ $loop->index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ulasan</label>
                            <textarea id="review-{{ $loop->index }}" 
                                      name="reviews[{{ $loop->index }}][comment]" 
                                      rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Bagaimana pengalaman Anda dengan produk ini?"
                                      required></textarea>
                            @error("reviews.{$loop->index}.comment")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-4">
                            <label for="images-{{ $loop->index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Produk (Opsional)</label>
                            <div class="flex items-center">
                                <label class="flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Unggah Foto
                                    <input id="images-{{ $loop->index }}" name="reviews[{{ $loop->index }}][images][]" type="file" class="sr-only" accept="image/*" multiple>
                                </label>
                                <span class="ml-3 text-sm text-gray-500 dark:text-gray-400" id="file-count-{{ $loop->index }}">Tidak ada file yang dipilih</span>
                            </div>
                            <div class="mt-2 flex flex-wrap gap-2" id="image-preview-{{ $loop->index }}"></div>
                            @error("reviews.{$loop->index}.images")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="flex justify-end mt-6">
                        <a href="{{ route('orders.completed') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Kirim Penilaian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Rating stars functionality
    const ratingStars = document.querySelectorAll('.rating-star');
    ratingStars.forEach(star => {
        star.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            const value = this.getAttribute('data-value');
            const inputField = document.getElementById(`rating-input-${index}`);
            inputField.value = value;
            
            // Update stars appearance
            const stars = document.querySelectorAll(`.rating-star[data-index="${index}"]`);
            stars.forEach(s => {
                const starValue = s.getAttribute('data-value');
                const starSpan = s.querySelector('span');
                if (starValue <= value) {
                    starSpan.classList.remove('text-gray-300', 'dark:text-gray-600');
                    starSpan.classList.add('text-yellow-400');
                } else {
                    starSpan.classList.add('text-gray-300', 'dark:text-gray-600');
                    starSpan.classList.remove('text-yellow-400');
                }
            });
        });
    });
    
    // Image upload preview
    document.querySelectorAll('input[type="file"]').forEach(input => {
        const index = input.id.split('-')[1];
        
        input.addEventListener('change', function() {
            const fileCount = document.getElementById(`file-count-${index}`);
            const imagePreview = document.getElementById(`image-preview-${index}`);
            
            // Update file count text
            if (this.files.length === 0) {
                fileCount.textContent = 'Tidak ada file yang dipilih';
            } else if (this.files.length === 1) {
                fileCount.textContent = '1 file dipilih';
            } else {
                fileCount.textContent = `${this.files.length} file dipilih`;
            }
            
            // Clear previous previews
            imagePreview.innerHTML = '';
            
            // Create preview for each file
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'relative w-16 h-16 rounded overflow-hidden';
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
                        <button type="button" class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">×</button>
                    `;
                    imagePreview.appendChild(preview);
                    
                    // Add remove functionality
                    const removeButton = preview.querySelector('button');
                    removeButton.addEventListener('click', function() {
                        preview.remove();
                        // Note: This doesn't actually remove the file from the input
                        // For that, you'd need a more complex solution with a custom file list
                    });
                };
                reader.readAsDataURL(file);
            });
        });
    });
});
</script>
@endsection
