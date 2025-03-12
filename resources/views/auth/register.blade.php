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
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .input-group input, .input-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(37, 99, 235, 0.3);
            border-radius: 0.75rem;
            color: #1e3a8a;
            transition: all 0.3s ease;
        }
        .input-group input:focus, .input-group select:focus {
            outline: none;
            border-color: #2563eb;
            background: rgba(255, 255, 255, 0.2);
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
        .input-group input:not(:placeholder-shown) ~ label,
        .input-group select:focus ~ label,
        .input-group select:not(:placeholder-shown) ~ label {
            top: -0.5rem;
            left: 0.5rem;
            font-size: 0.75rem;
            background: transparent;
            padding: 0 0.5rem;
            border-radius: 0.25rem;
            color: #2563eb;
        }
    </style>

    <div class="min-h-screen tech-gradient flex justify-center items-center p-4">
        <div class="w-full max-w-4xl glass-effect p-8 rounded-2xl shadow-2xl hover-scale">
            <div class="text-center mb-8">
                <h2 class="text-4xl font-bold text-black">Create Your Account</h2>
                <p class="text-blue-700 mt-2">Join TeknoShop and start your tech journey!</p>
            </div>
            <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="input-group">
                        <input id="name" type="text" name="name" :value="old('name')" required autofocus placeholder=" ">
                        <label for="name">Name</label>
                    </div>
                    <div class="input-group">
                        <input id="email" type="email" name="email" :value="old('email')" required placeholder=" ">
                        <label for="email">Email</label>
                    </div>
                    <div class="input-group">
                        <input id="username" type="text" name="username" :value="old('username')" required placeholder=" ">
                        <label for="username">Username</label>
                    </div>
                    <div class="input-group">
                        <select id="role" name="role" required>
                            <option value="" disabled selected>Pilih peran Anda</option>
                            <option value="seller" {{ old('role') == 'seller' ? 'selected' : '' }}>Seller</option>
                            <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                        </select>
                        <label for="role">Register as</label>
                    </div>
                    <div class="input-group">
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder=" ">
                        <label for="password">Password</label>
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-800 hover:text-blue-600"
                            onclick="togglePasswordVisibility('password')">
                            <i id="icon-password" class="fa fa-eye-slash"></i>
                        </button>
                    </div>
                    <div class="input-group">
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder=" ">
                        <label for="password_confirmation">Confirm Password</label>
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-800 hover:text-blue-600"
                            onclick="togglePasswordVisibility('password_confirmation')">
                            <i id="icon-password_confirmation" class="fa fa-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <div class="flex flex-col items-center mt-8">
                    <button type="submit" class="w-full md:w-auto px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-medium transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg">
                        Register
                    </button>
                    <p class="mt-4 text-black">
                        Already have an account? <a href="{{ route('login') }}" class="text-orange-300 hover:text-orange-400 font-medium">Login here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('registerForm');
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                // Perform client-side validation here
                // If validation passes, submit the form
                form.submit();
            });
        });

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '{!! implode("<br>", $errors->all()) !!}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Registration Successful!',
                text: 'Silahkan Login',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}';
                }
            });
        @endif
    </script>
</x-guest-layout>