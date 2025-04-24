@extends('layouts.seller')

@section('title', $blog->title . ' - TechnoShop Seller')
@section('description', $blog->excerpt)

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center mb-6">
        <div>
            <div class="flex items-center mt-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $blog->is_published ? 'Dipublikasikan pada ' . $blog->published_at->format('d M Y H:i') : 'Draft' }}
                </span>
                <span class="mx-2 text-gray-500 dark:text-gray-400">•</span>
                <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full {{ $blog->is_published ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : 'text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-100' }}">
                    {{ $blog->is_published ? 'Dipublikasikan' : 'Draft' }}
                </span>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('seller.blogs.edit', $blog) }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                Edit Artikel
            </a>
            <a href="{{ route('seller.blogs.index') }}" class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg active:bg-gray-100 hover:bg-gray-100 focus:outline-none focus:shadow-outline-gray">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <!-- Featured Image -->
        @if($blog->featured_image)
        <div class="w-full h-80 overflow-hidden">
            <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
        </div>
        @endif

        <!-- Blog Content -->
        <div class="p-6">
            <!-- Excerpt -->
            <div class="mb-6 text-lg font-medium text-gray-700 dark:text-gray-300 border-l-4 border-blue-500 pl-4 py-2 bg-gray-50 dark:bg-gray-700 rounded">
                {{ $blog->excerpt }}
            </div>

            <!-- Content -->
            <div class="prose prose-lg max-w-none dark:prose-invert">
                {!! $blog->content !!}
            </div>
        </div>
    </div>

    <!-- Preview Section -->
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Preview di Halaman Blog</h2>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden shadow-md">
                    @if($blog->featured_image)
                    <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                    <div class="p-6">
                        <h3 class="font-semibold text-lg mb-2 dark:text-white">{{ $blog->title }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($blog->excerpt, 100) }}</p>
                        <a href="#" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
