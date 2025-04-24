@extends('layouts.seller')

@section('title', 'Tambah Banner Baru - TechnoShop Seller')
@section('description', 'Tambahkan banner baru untuk ditampilkan di halaman utama TechnoShop')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="page-header">
        <div class="mb-4"></div>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-md">
        <div class="w-full overflow-x-auto p-6">
            <form action="{{ route('seller.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Banner Image -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Banner Image <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="image-placeholder">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16  aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Klik untuk upload</span></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (Maks. 2MB)</p>
                                </div>
                                <div class="hidden" id="image-preview">
                                    <img id="preview-image" src="#" alt="Preview" class="h-64 object-contain">
                                </div>
                                <input id="image" name="image" type="file" class="hidden" accept="image/*" required />
                            </label>
                        </div>
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Banner Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Judul Banner <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500 focus:ring-opacity-40">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Link URL -->
                    <div>
                        <label for="link_url" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            URL Link (Opsional)
                        </label>
                        <input type="url" name="link_url" id="link_url" value="{{ old('link_url') }}"
                            class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500 focus:ring-opacity-40">
                        @error('link_url')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Deskripsi (Opsional)
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500 focus:ring-opacity-40">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Urutan
                        </label>
                        <input type="number" name="order" id="order" value="{{ old('order', 0) }}" min="0"
                            class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500 focus:ring-opacity-40">
                        @error('order')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Status
                        </label>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Aktif
                            </label>
                        </div>
                        @error('is_active')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Period -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Periode (Opsional)
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1">
                                    Tanggal Mulai
                                </label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                    class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500 focus:ring-opacity-40">
                            </div>
                            <div>
                                <label for="end_date" class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1">
                                    Tanggal Berakhir
                                </label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                    class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500 focus:ring-opacity-40">
                            </div>
                        </div>
                        @error('start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('end_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('seller.banners.index') }}" class="px-6 py-2 leading-5 text-gray-700 transition-colors duration-200 transform bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:bg-gray-300 mr-4">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 leading-5 text-white transition-colors duration-200 transform bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imagePlaceholder = document.getElementById('image-placeholder');
        const imagePreview = document.getElementById('image-preview');
        const previewImage = document.getElementById('preview-image');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    imagePlaceholder.classList.add('hidden');
                    imagePreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                previewImage.src = '#';
                imagePlaceholder.classList.remove('hidden');
                imagePreview.classList.add('hidden');
            }
        });
    });
</script>
@endsection
