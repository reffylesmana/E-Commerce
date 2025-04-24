<header class="bg-white shadow-sm">
    <div class="flex items-center justify-between p-4">
        <!-- Left Section -->
        <div class="flex items-center gap-4">
            <h1 class="text-xl font-semibold text-blue-800">@yield('title', 'Dashboard')</h1>
        </div>

        <!-- Right Section -->
        <div class="flex items-center gap-4">
            
            <!-- Notification -->
            <div class="relative dropdown-parent">
                <button type="button" onclick="toggleDropdown('notificationDropdown')"
                    class="p-2 hover:bg-blue-50 rounded-full relative transition-all duration-300">
                    <iconify-icon icon="heroicons:bell" class="text-2xl text-blue-600"></iconify-icon>
                    @php
                        $unreadCount = auth()->user()->unreadNotifications->count();
                    @endphp
                    @if ($unreadCount > 0)
                        <span
                            class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center shadow-sm">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </button>
                <div id="notificationDropdown"
                    class="dropdown-menu absolute bg-white shadow-lg rounded-lg mt-2 py-2 w-80 z-50 right-0 hidden transition-all duration-300">
                    <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-medium text-gray-700">Notifikasi</h3>
                        @if ($unreadCount > 0)
                            <form action="{{ route('seller.notifications.mark-all-read') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-xs text-blue-600 hover:text-blue-800 transition-colors duration-300">
                                    Tandai semua dibaca
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('notifications.index') }}"
                            class="text-xs text-blue-600 hover:text-blue-800 transition-colors duration-300">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="max-h-80 overflow-y-auto">
                        @forelse(auth()->user()->notifications->take(5) as $notification)
                            <a href="{{ $notification->data['url'] ?? '#' }}"
                                class="block px-4 py-3 hover:bg-blue-50 border-b border-gray-100 {{ $notification->read_at ? '' : 'bg-blue-50' }} transition-colors duration-300"
                                @if (!$notification->read_at) onclick="markAsRead('{{ $notification->id }}', this)" @endif>
                                <div class="flex">
                                    <!-- Icon berdasarkan jenis notifikasi -->
                                    @if (isset($notification->data['type']))
                                        @if ($notification->data['type'] == 'order')
                                            <div class="flex-shrink-0 bg-blue-100 rounded-full p-2 mr-3">
                                                <iconify-icon icon="heroicons:shopping-bag"
                                                    class="text-blue-600"></iconify-icon>
                                            </div>
                                        @elseif($notification->data['type'] == 'product')
                                            <div class="flex-shrink-0 bg-green-100 rounded-full p-2 mr-3">
                                                <iconify-icon icon="heroicons:cube"
                                                    class="text-green-600"></iconify-icon>
                                            </div>
                                        @elseif($notification->data['type'] == 'store')
                                            <div class="flex-shrink-0 bg-purple-100 rounded-full p-2 mr-3">
                                                <iconify-icon icon="heroicons:building-storefront"
                                                    class="text-purple-600"></iconify-icon>
                                            </div>
                                        @endif
                                    @else
                                        <div class="flex-shrink-0 bg-gray-100 rounded-full p-2 mr-3">
                                            <iconify-icon icon="heroicons:bell" class="text-gray-600"></iconify-icon>
                                        </div>
                                    @endif

                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $notification->data['message'] ?? '' }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="px-4 py-6 text-center text-gray-500">
                                <iconify-icon icon="heroicons:bell-slash" class="text-4xl mx-auto mb-2"></iconify-icon>
                                <p>Tidak ada notifikasi</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>


            <!-- User Profile -->
            <div class="relative dropdown-parent">
                <button type="button" onclick="toggleDropdown('profileDropdown')"
                    class="flex items-center gap-2 p-2 hover:bg-blue-50 rounded-lg transition-all duration-300">
                    @if (auth()->user()->image)
                        <img src="{{ asset('storage/images/' . auth()->user()->image) }}" alt="Profile Picture"
                            class="w-8 h-8 rounded-full object-cover">
                    @else
                        @php
                            $username = auth()->user()->username ?? 'User';
                            $initials = collect(explode(' ', $username))
                                ->map(function ($word) {
                                    return strtoupper(substr($word, 0, 1));
                                })
                                ->take(2)
                                ->join('');
                        @endphp
                        <div
                            class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                            {{ $initials }}
                        </div>
                    @endif
                    <span class="nav-text">{{ auth()->user()->username }}</span>
                </button>

                <div id="profileDropdown"
                    class="dropdown-menu absolute bg-white shadow-lg rounded-lg mt-2 py-2 w-48 right-0 z-50 hidden transition-all duration-300">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-blue-50">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-blue-50">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    /* Style untuk Header */
    .header {
        background-color: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header h1 {
        color: #1e3a8a;
        /* Warna biru tua */
    }




    .notification-count {
        background-color: #dc2626;
        /* Warna merah untuk notifikasi */
    }
</style>

<script>
    // Fungsi untuk toggle dropdown
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);

        // Tutup semua dropdown lain
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu.id !== dropdownId) {
                menu.classList.add('hidden');
            }
        });

        // Toggle dropdown yang diklik
        dropdown.classList.toggle('hidden');
    }

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function(event) {
        const isDropdownButton = event.target.closest('[onclick^="toggleDropdown"]');
        const isInsideDropdown = event.target.closest('.dropdown-menu');
        const isLogoutButton = event.target.closest('#logout-form button');

        // Jangan lakukan apa-apa jika mengklik tombol logout
        if (isLogoutButton) {
            return;
        }

        // Jika klik bukan pada dropdown button dan bukan di dalam dropdown
        if (!isDropdownButton && !isInsideDropdown) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
</script>
