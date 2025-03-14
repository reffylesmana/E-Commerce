@extends('layouts.seller')
@section('title', 'Pengaturan Toko')
@section('content')

<style>
    /* Improved Floating Label CSS */
    input.peer, textarea.peer {
        transition: border-color 0.2s ease-in-out;
    }
    
    input.peer::placeholder, textarea.peer::placeholder {
        color: transparent;
    }
    
    /* This ensures the label stays at the top when there's content */
    input.peer:not(:placeholder-shown) + label,
    textarea.peer:not(:placeholder-shown) + label {
        transform: translateY(-4px) scale(0.75);
        top: 4px;
        color: #6366f1; /* indigo-500 */
    }
    
    /* Focus styles */
    input.peer:focus + label,
    textarea.peer:focus + label {
        transform: translateY(-4px) scale(0.75);
        top: 4px;
        color: #6366f1; /* indigo-500 */
    }
    
    /* TomSelect Custom Styles */
    .ts-control {
        @apply border-gray-300 rounded-lg shadow-sm transition-all duration-150;
        padding: 0.5rem;
    }

    .ts-dropdown {
        @apply rounded-lg shadow-lg border border-gray-200 mt-1;
        max-height: 200px;
    }

    .ts-item {
        @apply bg-indigo-100 text-indigo-800 rounded-md px-2 py-1 m-1 text-sm;
    }

    .ts-item .remove {
        @apply text-indigo-500 hover:text-indigo-700;
    }
    
    /* Animation for modal */
    @keyframes modalAppear {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    
    .animate-modal-appear {
        animation: modalAppear 0.3s ease-out forwards;
    }

    @media (max-width: 640px) {
        .sm\:w-11\/12 {
            width: 91.666667% !important;
        }
    }
</style>

<div class="container mx-auto px-4 py-8 max-w-7xl">
    <h1 class="text-3xl font-bold mb-8 text-gray-800 relative">
        My Store
        <span class="absolute top-10 left-0 w-36 h-1 bg-indigo-500 rounded-full"></span>
    </h1>
    
    @if ($store)
        <div class="bg-white rounded-xl shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
            <div class="md:flex">
                <div class="md:flex-shrink-0 relative group">
                    @if ($store->logo)
                        <img class="h-64 w-full object-cover md:w-64 transition-transform duration-500 group-hover:scale-105" 
                            src="{{ Storage::url($store->logo) }}" alt="{{ $store->name }}">
                    @else
                        <div class="h-64 w-full md:w-64 bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                            <svg class="h-24 w-24 text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4 md:pb-6">
                        <button onclick="openEditModal()" class="bg-white/90 hover:bg-white text-indigo-600 font-medium py-2 px-4 rounded-full text-sm shadow-lg transform transition duration-300 hover:scale-105">
                            Change Logo
                        </button>
                    </div>
                </div>
                <div class="p-8 md:p-10 flex-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                </svg>
                                Your Store
                            </div>
                            <h2 class="mt-2 text-2xl md:text-3xl leading-8 font-bold text-gray-900">{{ $store->name }}</h2>
                        </div>
                        <button onclick="openEditModal()"
                            class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-lg flex items-center self-start">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Edit Store
                        </button>
                    </div>
                    
                    <p class="mt-4 text-gray-600 leading-relaxed">{{ $store->description }}</p>

                    <div class="mt-6 flex flex-wrap gap-2">
                        @if (!$store->is_approved)
                            <span class="px-3 py-1.5 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Pending Approval
                            </span>
                        @elseif($store->is_active)
                            <span class="px-3 py-1.5 text-xs font-semibold text-green-800 bg-green-100 rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Active
                            </span>
                        @else
                            <span class="px-3 py-1.5 text-xs font-semibold text-red-800 bg-red-100 rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Inactive
                            </span>
                        @endif

                        @if ($store->is_official)
                            <span class="px-3 py-1.5 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Official
                            </span>
                        @else
                            <span class="px-3 py-1.5 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                </svg>
                                Standard
                            </span>
                        @endif
                    </div>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Store Information</h3>
                            <div class="space-y-2">
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-gray-700">Created: <span class="font-medium">{{ $store->created_at->format('M d, Y') }}</span></span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-gray-700">Last Updated: <span class="font-medium">{{ $store->updated_at->format('M d, Y') }}</span></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Categories</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($store->categories as $category)
                                    <span class="px-2.5 py-1 text-xs font-medium bg-indigo-100 text-indigo-800 rounded-full">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-xl p-8 text-center max-w-2xl mx-auto">
            <div class="relative w-32 h-32 mx-auto mb-6 bg-indigo-100 rounded-full flex items-center justify-center">
                <svg class="w-16 h-16 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold mb-4 text-gray-800">You don't have a store yet</h2>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">Create your store to start selling products on our platform and reach customers worldwide.</p>
            <button onclick="openCreateModal()"
                class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-3 px-8 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 hover:shadow-xl flex items-center mx-auto">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Create Store
            </button>
        </div>
    @endif
</div>

<!-- Modal -->
<div id="storeModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop with blur effect -->
        <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity"></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-11/12 animate-modal-appear">
            <!-- Close Button -->
            <div class="absolute top-4 right-4 z-10">
                <button onclick="closeModal()" class="p-2 hover:bg-gray-100 rounded-full transition duration-200 group">
                    <svg class="w-6 h-6 text-gray-600 group-hover:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="storeForm"
                action="{{ $store ? route('seller.store.update', $store->id) : route('seller.store.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if ($store)
                    @method('PUT')
                @endif

                <div class="bg-white px-6 pt-8 pb-6 sm:p-8">
                    <h3 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                        <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg mr-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="{{ $store ? 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' : 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' }}" />
                            </svg>
                        </span>
                        {{ $store ? 'Edit Store' : 'Create Store' }}
                    </h3>
                    <div class="h-1 w-16 bg-indigo-500 rounded-full mb-6"></div>

                    <div class="space-y-6">
                        <!-- Nama Toko - Improved Floating Label -->
                        <div class="relative">
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $store->name ?? '') }}"
                                class="w-full h-14 py-4 px-4 pt-6 pb-2 border-0 ring-1 ring-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all peer"
                                placeholder=" " required>
                            <label for="name" 
                                class="absolute text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:top-1/2 peer-focus:top-4 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-indigo-500">
                                Store Name
                            </label>
                        </div>

                        <!-- Deskripsi - Improved Floating Label -->
                        <div class="relative">
                            <textarea name="description" id="description" rows="3"
                                class="w-full py-4 px-4 pt-6 pb-2 border-0 ring-1 ring-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all peer resize-none"
                                placeholder=" " required>{{ old('description', $store->description ?? '') }}</textarea>
                            <label for="description"
                                class="absolute text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-placeholder-shown:top-6 peer-focus:top-4 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-indigo-500">
                                Description
                            </label>
                        </div>

                        <!-- Dropdown Kategori -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1 ml-1">Categories</label>
                            <select name="categories[]" id="categories" multiple
                                class="w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all duration-150">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $store && $store->categories->contains($category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Upload Logo -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1 ml-1">Store Logo</label>
                            <div class="mt-1 relative h-48">
                                <label for="logo" id="upload-label"
                                    class="w-full h-full flex items-center justify-center border-2 border-dashed border-gray-300 rounded-xl hover:border-indigo-500 transition-colors cursor-pointer bg-gray-50 group">
                                    <div class="text-center space-y-2 transition-all duration-300 transform group-hover:scale-110">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-sm text-gray-500 group-hover:text-indigo-600 transition-colors">Click to upload</p>
                                    </div>
                                </label>

                                <input type="file" id="logo" name="logo" accept="image/*"
                                    class="hidden">

                                <div id="logo-preview"
                                    class="absolute inset-0 rounded-xl overflow-hidden bg-gray-50 hidden flex items-center justify-center border">
                                    <img class="object-contain max-h-full p-2" src="/placeholder.svg" alt="Preview">
                                    <button type="button"
                                        class="absolute top-2 right-2 p-1.5 bg-white/90 hover:bg-white rounded-full shadow-md hover:shadow-lg transition-all duration-200 remove-preview group">
                                        <svg class="w-5 h-5 text-red-500 group-hover:text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 ml-1">PNG, JPG, GIF (Max 1MB)</p>
                            <div id="logo-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 sm:flex sm:flex-row-reverse rounded-b-2xl">
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-all duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="{{ $store ? 'M5 13l4 4L19 7' : 'M12 6v6m0 0v6m0-6h6m-6 0H6' }}" />
                        </svg>
                        {{ $store ? 'Update Store' : 'Create Store' }}
                    </button>
                    <button type="button" onclick="closeModal()"
                        class="mt-3 sm:mt-0 sm:mr-4 w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-all duration-150">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi Modal
    const openCreateModal = () => {
        const modal = document.getElementById('storeModal');
        if(modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    const openEditModal = () => {
        const modal = document.getElementById('storeModal');
        if(modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    const closeModal = () => {
        const modal = document.getElementById('storeModal');
        if(modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    // Inisialisasi File Upload
    const initFileUpload = () => {
        const fileInput = document.getElementById('logo');
        const preview = document.getElementById('logo-preview');
        const uploadLabel = document.getElementById('upload-label');
        const errorDiv = document.getElementById('logo-error');
        const previewImg = preview?.querySelector('img');

        // Handle jika elemen tidak ditemukan
        if(!fileInput || !preview || !uploadLabel || !errorDiv || !previewImg) return;

        @if($store && $store->logo)
            preview.classList.remove('hidden');
            uploadLabel.classList.add('hidden');
            previewImg.src = "{{ Storage::url($store->logo) }}";
        @endif

        const isValidFile = (file) => {
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 1024 * 1024;

            if(!validTypes.includes(file.type)) {
                errorDiv.textContent = 'Only JPG, PNG, or GIF files are allowed';
                return false;
            }

            if(file.size > maxSize) {
                errorDiv.textContent = 'File size must be less than 1MB';
                return false;
            }

            return true;
        };

        const handleFile = (file) => {
            errorDiv.classList.add('hidden');
            if(!isValidFile(file)) {
                errorDiv.classList.remove('hidden');
                fileInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
                uploadLabel.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        };

        fileInput.addEventListener('change', (e) => {
            if(e.target.files.length) handleFile(e.target.files[0]);
        });

        preview.addEventListener('click', (e) => {
            if(e.target.closest('.remove-preview')) {
                fileInput.value = '';
                preview.classList.add('hidden');
                uploadLabel.classList.remove('hidden');
                previewImg.src = '';
                errorDiv.classList.add('hidden');
            }
        });
    };

    // Inisialisasi TomSelect
    const initTomSelect = () => {
        const categoriesEl = document.getElementById('categories');
        if(categoriesEl) {
            new TomSelect(categoriesEl, {
                plugins: ['remove_button'],
                create: false,
                maxItems: 5,
                placeholder: 'Select categories...',
                render: {
                    item: (data, escape) => `<div class="ts-item">${escape(data.text)}</div>`,
                    option: (data, escape) => `<div class="px-4 py-2 hover:bg-indigo-50">${escape(data.text)}</div>`
                }
            });
        }
    };

    // Event Listeners
    document.addEventListener('DOMContentLoaded', () => {
        // Inisialisasi komponen
        initFileUpload();
        initTomSelect();

        // Handle keyboard escape
        document.addEventListener('keydown', (e) => {
            if(e.key === 'Escape') closeModal();
        });

        // Click outside to close
        const modal = document.getElementById('storeModal');
        if(modal) {
            modal.addEventListener('click', (e) => {
                if(e.target === modal) closeModal();
            });
        }
    });

    // Ekspos fungsi ke global scope
    window.openCreateModal = openCreateModal;
    window.openEditModal = openEditModal;
    window.closeModal = closeModal;
</script>

@endsection