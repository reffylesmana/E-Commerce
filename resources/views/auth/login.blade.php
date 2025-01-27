<x-guest-layout>
    <div class="container position-relative d-flex flex-column justify-content-center align-items-center vh-100">
        <!-- Welcome Text -->
        <div class="welcome-text mb-4 text-center">
            <h1 class="text-3xl font-bold">
                TeknoShop
            </h1>
            <h2 class="text-sm mt-2">
                Your Tech, Your Future!
            </h2>
        </div>

        <!-- Card -->
        <div class="card shadow-lg border-0" style="width: 900px; height: 550px; border-radius: 20px;">
            <div class="row g-0 h-100">
                <!-- Bagian Gambar -->
                <div class="col-md-7 d-flex justify-content-center align-items-center">
                    <img src="{{ asset('img/login.png') }}" alt="TeknoShop Logo" class="img-fluid rounded-start"
                        alt="Illustration of people working in a command center"
                        style="object-fit: contain; width: 100%; height: 100%; border-top-left-radius: 20px; border-bottom-left-radius: 20px;" />
                </div>

                <!-- Bagian Form -->
                <div class="col-md-5 bg-blue-500 p-5 d-flex flex-column justify-content-center"
                    style="border-top-right-radius: 20px; border-bottom-right-radius: 20px;">
                    <h2 class="text-white text-center mb-4"
                        style="font-weight: bold; font-size: 1.5rem; position: relative;">
                        Log In
                    </h2>

                    <!-- Breeze Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <!-- Username -->
                        <div class="mb-6">
                            <label for="username" class="block text-white font-semibold mb-2">Username</label>
                            <input type="text" id="username" name="username"
                                class="form-input w-full p-2 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-blue-500 @error('username') border-red-500 @enderror"
                                placeholder="Username" value="{{ old('username') }}" required>
                            @error('username')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    
                        <!-- Password -->
                        <div class="mb-6">
                            <label for="password" class="block text-white font-semibold mb-2">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    class="form-input w-full p-2 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-blue-500"
                                    placeholder="Password Anda" required>
                                <button type="button" class="absolute right-3 top-3 text-gray-500" onclick="togglePasswordVisibility('password')">
                                    <i id="icon-password" class="fa fa-eye-slash"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    
                        <!-- Remember Me -->
                        <div class="flex items-center mb-6">
                            <input type="checkbox" name="remember" id="remember" class="form-checkbox h-5 w-5 text-blue-500">
                            <label for="remember" class="ml-2 text-white text-sm">Ingat Saya</label>
                        </div>
                    
                        <!-- Register Link -->
                        <div class="text-center mb-6">
                            <span class="text-white text-sm">Tidak memiliki akun? 
                                <a href="{{ route('register') }}" class="text-warning hover:underline">Daftar disini</a>
                            </span>
                        </div>
                    
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-warning w-full py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-400 focus:outline-none">
                            Log In
                        </button>
                    </form>
                    

                </div>
            </div>
        </div>

    <!-- JavaScript -->
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
