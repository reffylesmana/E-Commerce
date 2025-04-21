@extends('layouts.app')

@section('title', 'Penilaian Produk - TechnoShop')
@section('description', 'Lihat dan kelola penilaian produk Anda di TechnoShop')

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
                      <span class="ml-1 text-gray-500 dark:text-gray-400 md:ml-2">Penilaian Produk</span>
                  </div>
              </li>
          </ol>
      </nav>

      <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Penilaian Produk</h1>


      <!-- My Reviews Section -->
      <div>
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Ulasan Saya</h2>
          
          @if(count($reviews) > 0)
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                  @foreach($reviews as $review)
                      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 transition-transform hover:scale-105">
                          <div class="flex items-center mb-4">
                              <div class="flex-shrink-0">
                                  <img src="{{ asset('storage/' . $review->product->photos->first()->photo) }}" alt="{{ $review->product->name }}" class="w-16 h-16 rounded-lg object-cover">
                              </div>
                              <div class="ml-4">
                                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $review->product->name }}</h3>
                                  <div class="flex items-center mt-1">
                                      @for($i = 1; $i <= 5; $i++)
                                          @if($i <= $review->star)
                                              <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                              </svg>
                                          @else
                                              <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                              </svg>
                                          @endif
                                      @endfor
                                  </div>
                              </div>
                          </div>
                          <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3">{{ $review->text }}</p>
                          <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                              {{ $review->created_at->format('d M Y') }}
                          </div>
                          <div class="mt-4 flex justify-end">
                              <a href="#" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                                  Edit Ulasan
                              </a>
                          </div>
                      </div>
                  @endforeach
              </div>
              
              <div class="mt-6">
                  {{ $reviews->links() }}
              </div>
          @else
              <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 text-center">
                  <div class="w-16 h-16 mx-auto rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-4">
                      <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                  </div>
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Ulasan</h4>
                  <p class="text-gray-500 dark:text-gray-400">Anda belum memberikan ulasan untuk produk yang Anda beli.</p>
              </div>
          @endif
      </div>
  </div>
</div>
@endsection
