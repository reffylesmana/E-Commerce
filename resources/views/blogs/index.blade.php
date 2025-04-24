@extends('layouts.app')

@section('title', 'Blog - TechnoShop')
@section('description', 'Baca artikel terbaru tentang teknologi dan produk di TechnoShop')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 py-12">
    <div class="container mx-auto px-6 sm:px-10">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-white mb-4">TechnoShop Blog</h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Temukan artikel terbaru tentang teknologi, tips, dan tren terkini di dunia digital</p>
        </div>

        <!-- Featured Article -->
        @if($featuredBlog)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-12" data-aos="fade-up">
            <div class="md:flex">
                <div class="md:w-1/2">
                    @if($featuredBlog->featured_image)
                    <img src="{{ Storage::url($featuredBlog->featured_image) }}" alt="{{ $featuredBlog->title }}" class="w-full h-64 md:h-full object-cover">
                    @else
                    <div class="w-full h-64 md:h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                </div>
                <div class="md:w-1/2 p-6 md:p-8 flex flex-col justify-center">
                    <div class="flex items-center mb-4">
                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-3 py-1 rounded-full">Featured</span>
                        <span class="ml-3 text-sm text-gray-500 dark:text-gray-400">{{ $featuredBlog->published_at->format('d M Y') }}</span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white mb-4">{{ $featuredBlog->title }}</h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">{{ $featuredBlog->excerpt }}</p>
                    <a href="{{ route('blogs.show', $featuredBlog->slug) }}" class="inline-flex items-center text-blue-600 dark:text-blue-400 font-semibold hover:underline">
                        Baca Selengkapnya
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Blog Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($blogs as $blog)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <a href="{{ route('blogs.show', $blog->slug) }}" class="block h-full flex flex-col">
                    <div class="relative aspect-w-16 aspect-h-9 overflow-hidden">
                        @if($blog->featured_image)
                        <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-48 object-cover transition duration-300 hover:scale-110">
                        @else
                        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition duration-300"></div>
                    </div>
                    <div class="p-6 flex-grow flex flex-col">
                        <div class="flex items-center mb-3">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $blog->published_at->format('d M Y') }}</span>
                            <span class="mx-2 text-gray-300 dark:text-gray-600">•</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $blog->store->name }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3 line-clamp-2 hover:text-blue-600 dark:hover:text-blue-400 transition duration-300">{{ $blog->title }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3 flex-grow">{{ $blog->excerpt }}</p>
                        <div class="flex items-center text-blue-600 dark:text-blue-400 font-semibold hover:underline mt-auto">
                            Baca Selengkapnya
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">Belum ada artikel</h3>
                <p class="text-gray-600 dark:text-gray-300">Artikel akan segera hadir. Kunjungi kembali nanti.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $blogs->links() }}
        </div>
    </div>
</div>
@endsection
