<section class="max-w-4xl mx-auto py-8">
    <header class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-8" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 mb-6 md:mb-0">
                    <div class="text-center">
                        <!-- Container untuk preview gambar atau singkatan nama -->
                        <div id="image-preview-container" class="w-32 h-32 rounded-full object-cover mx-auto mb-4 border-4 border-indigo-500 dark:border-indigo-400 flex items-center justify-center bg-gray-200 dark:bg-gray-700">
                            @if ($user->image)
                                <!-- Jika ada gambar, tampilkan gambar -->
                                <img id="image-preview" src="{{ asset('storage/images/' . $user->image) }}" class="w-full h-full rounded-full object-cover">
                            @else
                                <!-- Jika tidak ada gambar, tampilkan singkatan nama -->
                                <span id="initials" class="text-4xl font-bold text-gray-800 dark:text-gray-200">
                                    {{ generateInitials($user->username) }}
                                </span>
                            @endif
                        </div>

                        <!-- Tombol untuk mengubah foto -->
                        <label for="image" class="cursor-pointer bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out">
                            {{ __('Change Photo') }}
                        </label>
                        <input id="image" name="image" type="file" class="hidden" onchange="previewImage(event)">
                    </div>
                    <x-input-error class="mt-2 text-center" :messages="$errors->get('image')" />
                </div>
                <div class="md:w-2/3 md:pl-8">
                    <div class="mb-6">
                        <x-input-label for="name" :value="__('Name')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-2">
                                <p class="text-sm text-gray-800 dark:text-gray-200">
                                    {{ __('Your email address is unverified.') }}

                                    <button form="send-verification" class="underline text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                        {{ __('Click here to re-send the verification email.') }}
                                    </button>
                                </p>

                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <x-primary-button class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out">
                {{ __('Save Changes') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 dark:text-green-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<!-- Script untuk preview gambar -->
<script>
    function previewImage(event) {
        const input = event.target;
        const previewContainer = document.getElementById('image-preview-container');
        const initials = document.getElementById('initials');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                // Hapus singkatan nama jika ada
                if (initials) {
                    initials.remove();
                }

                // Buat elemen gambar jika belum ada
                let img = document.getElementById('image-preview');
                if (!img) {
                    img = document.createElement('img');
                    img.id = 'image-preview';
                    img.className = 'w-full h-full rounded-full object-cover';
                    previewContainer.appendChild(img);
                }

                // Set sumber gambar
                img.src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            // Jika tidak ada file yang dipilih, kembalikan ke keadaan semula
            if (initials) {
                initials.style.display = 'block';
            }
            const img = document.getElementById('image-preview');
            if (img) {
                img.remove();
            }
        }
    }
</script>