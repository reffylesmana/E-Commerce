<x-guest-layout>
    <style>
        .tech-gradient {
            background: linear-gradient(135deg, #0EA5E9 0%, #2563EB 100%);
        }
        .glass-effect {
            backdrop-filter: blur(12px) saturate(180%);
            background-color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .hover-scale {
            transition: transform 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.02);
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .input-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border-color: rgba(255, 255, 255, 0.8);
            border: 2px solid #2563eb;
            border-radius: 0.75rem;
            color: #1e3a8a;
            transition: all 0.3s ease;
        }
        .input-group input:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.8);
            border: 2px solid #1e3a8a;
            background: rgba(255, 255, 255, 0.3);
        }
        .input-group label {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #1e3a8a;
            transition: all 0.3s ease;
            pointer-events: none;
        }
        .input-group input:focus ~ label,
        .input-group input:not(:placeholder-shown) ~ label {
            top: -0.5rem;
            left: 0.5rem;
            font-size: 0.75rem;
            background: transparent;
            padding: 0 0.5rem;
            border-radius: 0.25rem;
            color: #1e3a8a;
        }
    </style>

    <div class="min-h-screen tech-gradient relative overflow-hidden flex items-center justify-center">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute w-96 h-96 bg-blue-400 rounded-full blur-3xl opacity-20 -top-20 -left-20 floating"></div>
            <div class="absolute w-96 h-96 bg-indigo-400 rounded-full blur-3xl opacity-20 -bottom-20 -right-20 floating" style="animation-delay: -1.5s"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-white mb-2 tracking-tight">
                        TeknoShop
                    </h1>
                    <p class="text-blue-100 text-lg">Your Tech, Your Future!</p>
                </div>

                <div class="bg-white backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden hover-scale">
                    <div class="grid md:grid-cols-2 gap-0">
                        <div class="relative p-6 hidden md:block">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/30 to-transparent"></div>
                            <img src="{{ asset('img/login.png') }}" alt="TeknoShop Logo" 
                                class="w-full h-full object-cover rounded-2xl floating">
                        </div>

                        <div class="p-8 sm:p-12">
                            <h2 class="text-3xl font-bold text-black mb-8 text-center">Welcome</h2>

                            <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-6">
                                @csrf
                                
                                <div class="input-group">
                                    <input type="text" id="username" name="username" placeholder=" " 
                                        value="{{ old('name') }}" required>
                                    <label for="username">Name</label>
                                </div>

                                <div class="input-group">
                                    <input type="password" id="password" name="password" placeholder=" " required>
                                    <label for="password">Password</label>
                                    <button type="button" 
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-800 hover:text-blue-600"
                                        onclick="togglePasswordVisibility('password')">
                                        <i id="icon-password" class="fa fa-eye-slash"></i>
                                    </button>
                                </div>

                                <div class="flex items-center justify-between">
                                    <label class="flex items-center space-x-2 text-sm text-blue-800 cursor-pointer">
                                        <input type="checkbox" name="remember" class="form-checkbox rounded border-blue-300 text-blue-500">
                                        <span>Remember me</span>
                                    </label>
                                    <a href="#" class="text-sm text-blue-800 hover:text-blue-600">Forgot password?</a>
                                </div>

                                <button type="submit" 
                                    class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
                                    Sign In
                                </button>

                                <p class="text-center text-blue-800">
                                    Don't have an account? 
                                    <a href="{{ route('register') }}" 
                                        class="text-blue-600 hover:text-blue-800 font-medium">
                                        Create one
                                    </a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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

        // Tambahkan script untuk menangani error dan menampilkan SweetAlert
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('loginForm');
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;
                let errorMessage = '';

                if (!username && !password) {
                    errorMessage = 'Please enter both username and password.';
                } else if (!username) {
                    errorMessage = 'Please enter your username.';
                } else if (!password) {
                    errorMessage = 'Please enter your password.';
                }

                if (errorMessage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Jika tidak ada error, submit form
                    form.submit();
                }
            });
        });

        // Tampilkan SweetAlert jika ada error dari server
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Authentication Failed',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
</x-guest-layout>