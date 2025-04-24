@extends('layouts.app')

@section('title', 'Pengiriman')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 animate-fade-in">
    <!-- Breadcrumbs -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="/" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                    <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Beranda
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Pengiriman</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-gradient-to-br from-indigo-50 via-white to-indigo-100 rounded-xl shadow-xl p-8">
        <h1 class="text-4xl font-bold text-indigo-800 mb-8 text-center">Kebijakan Pengiriman & Pengembalian</h1>
        
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-start gap-4">
                    <div class="bg-indigo-100 p-2 rounded-full text-indigo-600 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                            <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1v-1a1 1 0 011-1h2a1 1 0 011 1v1a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1V5a1 1 0 00-1-1H3z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold mb-2 text-gray-800">Metode Pengiriman</h2>
                        <p class="text-gray-600">Kami bekerja sama dengan berbagai jasa pengiriman terpercaya termasuk JNE, J&T, SiCepat, dan Pos Indonesia.</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-start gap-4">
                    <div class="bg-indigo-100 p-2 rounded-full text-indigo-600 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 4a1 1 0 000 2 1 1 0 011 1v1H7a1 1 0 000 2h1v3a1 1 0 102 0v-1a1 1 0 011-1h1a1 1 0 100-2h-1a1 1 0 01-1-1V7a3 3 0 016 0v4a1 1 0 01-1 1h-3a1 1 0 100 2h3a3 3 0 01-3 3V7a1 1 0 012 0v4a1 1 0 002 0V7a3 3 0 00-3-3H7z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold mb-2 text-gray-800">Biaya Pengiriman</h2>
                        <p class="text-gray-600">Biaya pengiriman dihitung otomatis berdasarkan berat produk dan lokasi pengiriman.</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-start gap-4">
                    <div class="bg-indigo-100 p-2 rounded-full text-indigo-600 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold mb-2 text-gray-800">Kebijakan Pengembalian</h2>
                        <p class="text-gray-600">Produk dapat dikembalikan dalam waktu 14 hari setelah diterima jika terdapat kerusakan atau kecacatan produk.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection