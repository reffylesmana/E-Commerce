@extends('layouts.app')

@section('title', 'Edit Profil - E-Commerce')
@section('description', 'Edit informasi profil Anda di E-Commerce')

@push('styles')
    <style>
        .glass-effect {
            backdrop-filter: blur(12px) saturate(180%);
            background-color: rgba(239, 246, 255, 0.7);
        }

        .dark .glass-effect {
            background-color: rgba(30, 58, 138, 0.1);
        }

        .profile-preview {
            transition: all 0.3s ease;
        }

        .profile-preview:hover .edit-overlay {
            opacity: 1;
        }
    </style>
@endpush

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb Updated -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('account.index') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            Akun
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400">Edit Profil</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="max-w-4xl mx-auto">
                <div class="glass-effect rounded-2xl shadow-xl p-8 transition-all duration-300 hover:shadow-2xl">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Profil</h2>
                        <div class="relative profile-preview group">
                            @if (auth()->user()->image)
                                <div
                                    class="w-20 h-20 rounded-full overflow-hidden border-4 border-white dark:border-gray-800 shadow-lg">
                                    <img src="{{ asset('storage/images/' . auth()->user()->image) }}" alt="Current Profile"
                                        class="w-full h-full object-cover">
                                </div>
                            @else
                                <div
                                    class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div
                                class="edit-overlay absolute inset-0 bg-black/50 rounded-full flex items-center justify-center opacity-0 transition-opacity">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <!-- Name Input -->
                        <div class="mb-6">
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                            <div class="relative">
                                <input type="text" id="name" name="name"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white transition-all duration-300"
                                    value="{{ old('name', auth()->user()->name) }}" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error class="mt-2 text-red-500 text-sm" :messages="$errors->get('name')" />
                        </div>

                        <!-- Email Input -->
                        <div class="mb-6">
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Email</label>
                            <div class="relative">
                                <input type="email" id="email" name="email"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white transition-all duration-300"
                                    value="{{ old('email', auth()->user()->email) }}" required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                        </path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error class="mt-2 text-red-500 text-sm" :messages="$errors->get('email')" />
                        </div>

                        <!-- Profile Photo Upload -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Foto
                                Profil</label>
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <input type="file" id="image" name="image"
                                        class="opacity-0 absolute inset-0 w-full h-full cursor-pointer" accept="image/*"
                                        onchange="previewImage(event)">
                                    <label for="image"
                                        class="cursor-pointer px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-300 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Pilih Foto
                                    </label>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Format: JPEG, PNG (Maks. 2MB)</span>
                            </div>
                            <x-input-error class="mt-2 text-red-500 text-sm" :messages="$errors->get('image')" />
                        </div>

                        <!-- Password Input -->
                        <div class="mb-8">
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Password
                                Baru</label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white transition-all duration-300"
                                    placeholder="••••••••">
                                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    onclick="togglePasswordVisibility()">
                                    <svg id="eye-icon" class="h-5 w-5 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path id="eye-open" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4.5C6.75 4.5 2.25 9 2.25 12s4.5 7.5 9.75 7.5 9.75-4.5 9.75-7.5S17.25 4.5 12 4.5zM12 10.5a1.5 1.5 0 100 3 1.5 1.5 0 000-3z">
                                        </path>
                                        <path id="eye-closed" class="hidden" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <x-input-error class="mt-2 text-red-500 text-sm" :messages="$errors->get('password')" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-4 border-t pt-6 dark:border-gray-700">
                            <a href="{{ route('account.index') }}"
                                class="px-6 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-300 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                    </path>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.querySelector('.profile-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-white dark:border-gray-800 shadow-lg">
                            <img src="${e.target.result}" 
                                 alt="Preview" 
                                 class="w-full h-full object-cover">
                            <div class="edit-overlay absolute inset-0 bg-black/50 rounded-full flex items-center justify-center opacity-0 transition-opacity">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin
                                    ="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = "password";
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
@endsection
