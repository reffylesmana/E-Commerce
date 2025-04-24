@extends('layouts.app')

@section('title', $blog->title . ' - TechnoShop')
@section('description', $blog->excerpt)

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 py-12">
    <div class="container mx-auto px-6 sm:px-10">
        <!-- Breadcrumbs -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('blogs.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Blogs</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ Str::limit($blog->title, 40) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Article -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden" data-aos="fade-up">
            <!-- Article Header -->
            <div class="p-6 md:p-10">
                <div class="flex items-center mb-4">
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $blog->published_at->format('d M Y') }}</span>
                    <span class="mx-2 text-gray-300 dark:text-gray-600">•</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $blog->store->name }}</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-white mb-6">{{ $blog->title }}</h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 border-l-4 border-blue-500 pl-4 py-2 bg-gray-50 dark:bg-gray-700">{{ $blog->excerpt }}</p>
            </div>

            <!-- Featured Image -->
            @if($blog->featured_image)
            <div class="w-full">
                <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-auto object-cover">
            </div>
            @endif

            <!-- Article Content -->
            <div class="p-6 md:p-10">
                <div class="prose prose-lg max-w-none dark:prose-invert">
                    {!! $blog->content !!}
                </div>
            </div>
        </div>

        <!-- Store Info -->
        <div class="mt-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 md:p-8" data-aos="fade-up">
            <div class="flex flex-col md:flex-row items-center">
                <div class="mb-4 md:mb-0 md:mr-6">
                    @if($blog->store->logo)
                    <img src="{{ Storage::url($blog->store->logo) }}" alt="{{ $blog->store->name }}" class="w-24 h-24 rounded-full object-cover">
                    @else
                    <div class="w-24 h-24 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-300">{{ substr($blog->store->name, 0, 1) }}</span>
                    </div>
                    @endif
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">{{ $blog->store->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ $blog->store->description ?? 'Official Store di TechnoShop' }}</p>
                    <a href="{{ route('store', $blog->store->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Kunjungi Toko
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Articles -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Artikel Terkait</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($relatedBlogs as $relatedBlog)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ route('blogs.show', $relatedBlog->slug) }}" class="block h-full flex flex-col">
                        <div class="relative aspect-w-16 aspect-h-9 overflow-hidden">
                            @if($relatedBlog->featured_image)
                            <img src="{{ Storage::url($relatedBlog->featured_image) }}" alt="{{ $relatedBlog->title }}" class="w-full h-48 object-cover transition duration-300 hover:scale-110">
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
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $relatedBlog->published_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3 line-clamp-2 hover:text-blue-600 dark:hover:text-blue-400 transition duration-300">{{ $relatedBlog->title }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3 flex-grow">{{ $relatedBlog->excerpt }}</p>
                            <div class="flex items-center text-blue-600 dark:text-blue-400 font-semibold hover:underline mt-auto">
                                Baca Selengkapnya
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
