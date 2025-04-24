@extends('layouts.app')

@section('title', 'Edit Penilaian - TechnoShop')
@section('description', 'Edit penilaian produk Anda di TechnoShop')

@section('content')
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-6 sm:py-8">
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
                            <a href="{{ route('reviews.index') }}"
                                class="ml-1 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 md:ml-2">Penilaian
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
                            <span class="ml-1 text-gray-500 dark:text-gray-400 md:ml-2">Edit Penilaian</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="flex-shrink-0 w-16 h-16 rounded-md overflow-hidden">
                            @if ($review->product->photos->count() > 0)
                                <img src="{{ asset('storage/' . $review->product->photos->first()->photo) }}"
                                    alt="{{ $review->product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                                Edit Penilaian: {{ $review->product->name }}
                            </h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Pesanan #{{ $review->order->order_number }} • {{ $review->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                                <div class="flex items-center space-x-1" id="rating-stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button type="button" class="rating-star text-3xl focus:outline-none"
                                            data-value="{{ $i }}">
                                            <span
                                                class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }} hover:text-yellow-400">★</span>
                                        </button>
                                    @endfor
                                    <input type="hidden" name="rating" id="rating-input" value="{{ $review->rating }}"
                                        required>
                                </div>
                                @error('rating')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="comment"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ulasan</label>
                                <textarea id="comment" name="comment" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                    placeholder="Bagaimana pengalaman Anda dengan produk ini?" required>{{ $review->comment }}</textarea>
                                @error('comment')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Produk
                                    Saat Ini</label>
                                <div class="flex flex-wrap gap-3" id="current-images">
                                    @if (is_array($review->images) && count($review->images) > 0)
                                        @foreach ($review->images as $image)
                                            <div class="relative w-24 h-24 rounded overflow-hidden group">
                                                <img src="{{ asset('storage/reviews/' . $image) }}" alt="Review Image"
                                                    class="w-full h-full object-cover">
                                                <button type="button"
                                                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm opacity-0 group-hover:opacity-100 transition-opacity"
                                                    onclick="removeImage('{{ $image }}', this)">
                                                    ×
                                                </button>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada foto</p>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label for="images"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tambah Foto Baru
                                    (Opsional)</label>
                                <div class="flex items-center">
                                    <label
                                        class="flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Unggah Foto
                                        <input id="images" name="images[]" type="file" class="sr-only"
                                            accept="image/*" multiple>
                                    </label>
                                    <span class="ml-3 text-sm text-gray-500 dark:text-gray-400" id="file-count">Tidak ada
                                        file yang dipilih</span>
                                </div>
                                <div class="mt-3 flex flex-wrap gap-3" id="image-preview"></div>
                                @error('images.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-between pt-4">
                                <a href="{{ route('reviews.index') }}"
                                    class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Kembali
                                </a>
                                <button type="submit"
                                    class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="remove-image-form" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="image" id="image-to-remove">
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Rating stars functionality
            const ratingStars = document.querySelectorAll('.rating-star');
            ratingStars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    document.getElementById('rating-input').value = value;
                    updateRatingStars(value);
                });
            });

            // Image upload preview
            const imagesInput = document.getElementById('images');
            if (imagesInput) {
                imagesInput.addEventListener('change', function() {
                    const fileCount = document.getElementById('file-count');
                    const imagePreview = document.getElementById('image-preview');

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
                            preview.className = 'relative w-24 h-24 rounded overflow-hidden';
                            preview.innerHTML = `
                                <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
                                <button type="button" class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm">×</button>
                            `;
                            imagePreview.appendChild(preview);

                            // Add remove functionality
                            const removeButton = preview.querySelector('button');
                            removeButton.addEventListener('click', function() {
                                preview.remove();
                                // Note: This doesn't actually remove the file from the input
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                });
            }
        });

        function updateRatingStars(rating) {
            const stars = document.querySelectorAll('.rating-star');
            stars.forEach(star => {
                const value = parseInt(star.getAttribute('data-value'));
                const starSpan = star.querySelector('span');
                if (value <= rating) {
                    starSpan.classList.remove('text-gray-300', 'dark:text-gray-600');
                    starSpan.classList.add('text-yellow-400');
                } else {
                    starSpan.classList.add('text-gray-300', 'dark:text-gray-600');
                    starSpan.classList.remove('text-yellow-400');
                }
            });
        }

        function removeImage(imageName, button) {
            if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
                fetch(`/reviews/{{ $review->id }}/remove-image`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            image: imageName
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the image element from DOM
                            button.closest('.relative').remove();

                            // If no images left, show message
                            const currentImagesContainer = document.getElementById('current-images');
                            if (currentImagesContainer.children.length === 0) {
                                currentImagesContainer.innerHTML =
                                    '<p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada foto</p>';
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        function removeImage(imageName, button) {
        // Tambahkan hidden input untuk menghapus saat submit
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'removed_images[]';
        input.value = imageName;
        document.querySelector('form').appendChild(input);

        // Hapus elemen gambar dari tampilan
        const imageDiv = button.closest('.relative');
        imageDiv.remove();
    }

    </script>
@endsection
