<x-guest-layout>
    <div class="bg-gradient-to-b from-white to-blue-500 min-h-screen flex justify-center items-center">
        <div class="w-full max-w-3xl bg-white p-8 rounded-lg shadow-lg">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-black">Daftar Akun</h2>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nama Lengkap -->
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="text-indigo-600 text-lg" />
                        <x-text-input id="name" class="block mt-1 w-full py-2 border-indigo-500 focus:ring-indigo-500" type="text"
                            name="name" :value="old('name')" required autofocus placeholder="Nama Lengkap Anda" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-indigo-600 text-lg" />
                        <x-text-input id="email" class="block mt-1 w-full py-2 border-indigo-500 focus:ring-indigo-500" type="email"
                            name="email" :value="old('email')" required placeholder="Email Anda" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <!-- Username -->
                    <div>
                        <x-input-label for="username" :value="__('Username')" class="text-indigo-600 text-lg" />
                        <x-text-input id="username" class="block mt-1 w-full py-2 border-indigo-500 focus:ring-indigo-500" type="text"
                            name="username" :value="old('username')" required placeholder="Username Anda" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    <!-- Role -->
                    <div>
                        <x-input-label for="role" :value="__('Register as')" class="text-indigo-600 text-lg" />
                        <select id="role" name="role" class="block mt-1 w-full py-2 border-indigo-500 focus:ring-indigo-500">
                            <option selected>Pilih Role</option>
                            <option value="seller" {{ old('role') == 'seller' ? 'selected' : '' }}>Seller</option>
                            <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-indigo-600 text-lg" />
                        <div class="relative">
                            <x-text-input id="password" class="block mt-1 w-full py-2 border-indigo-500 focus:ring-indigo-500" type="password"
                                name="password" required autocomplete="new-password" placeholder="Password Anda" />
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600"
                                onclick="togglePasswordVisibility('password')">
                                <i id="icon-password" class="fa fa-eye-slash"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-indigo-600 text-lg" />
                        <div class="relative">
                            <x-text-input id="password_confirmation" class="block mt-1 w-full py-2 border-indigo-500 focus:ring-indigo-500"
                                type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Konfirmasi Password" />
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600"
                                onclick="togglePasswordVisibility('password_confirmation')">
                                <i id="icon-password_confirmation" class="fa fa-eye-slash"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                @if (session('success'))
                    <script>
                        Swal.fire({
                            title: 'Pendaftaran Berhasil!',
                            text: 'Silakan login.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            willClose: () => {
                                window.location.href = '{{ route('login') }}'; // Redirect to login page
                            }
                        });
                    </script>
                @endif

                <div class="flex flex-col justify-center items-center mt-6">
                    <x-primary-button class="mb-3 bg-orange-600 text-white">
                        {{ __('Register') }}
                    </x-primary-button>
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Login disini</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById('icon-' + inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
</x-guest-layout>
