<header class="bg-white shadow-sm">
    <div class="flex items-center justify-between p-4">
        <!-- Left Section -->
        <div class="flex items-center gap-4">
            <h1 class="text-xl font-semibold">@yield('title', 'Dashboard')</h1>
        </div>

        <!-- Right Section -->
        <div class="flex items-center gap-4">
            <!-- Notification -->
            <div class="relative dropdown-parent">
                <button onclick="toggleDropdown('notificationDropdown')" 
                        class="p-2 hover:bg-gray-100 rounded-full relative">
                    <iconify-icon icon="heroicons:bell" class="text-2xl"></iconify-icon>
                    @php
                        $unreadCount = auth()->user()->unreadNotifications->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </button>
                <div id="notificationDropdown" class="dropdown-menu absolute bg-white shadow-lg rounded-lg mt-2 py-2 w-80 z-50 right-0">
                    <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-medium text-gray-700">Notifikasi</h3>
                        @if($unreadCount > 0)
                            <form action="#" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">
                                    Tandai semua dibaca
                                </button>
                            </form>
                        @endif
                        <a href="#" class="text-xs text-blue-600 hover:text-blue-800">
                            Lihat Semua
                        </a>
                    </div>
                    
                    <div class="max-h-80 overflow-y-auto">
                        @forelse(auth()->user()->notifications->take(5) as $notification)
                            <a href="#" 
                               class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 {{ $notification->read_at ? '' : 'bg-blue-50' }}">
                                <div class="flex">
                                    @if(isset($notification->data['type']) && $notification->data['type'] == 'order')
                                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-2 mr-3">
                                            <iconify-icon icon="heroicons:shopping-bag" class="text-blue-600"></iconify-icon>
                                        </div>
                                    @elseif(isset($notification->data['type']) && $notification->data['type'] == 'product')
                                        <div class="flex-shrink-0 bg-green-100 rounded-full p-2 mr-3">
                                            <iconify-icon icon="heroicons:cube" class="text-green-600"></iconify-icon>
                                        </div>
                                    @elseif(isset($notification->data['type']) && $notification->data['type'] == 'store')
                                        <div class="flex-shrink-0 bg-purple-100 rounded-full p-2 mr-3">
                                            <iconify-icon icon="heroicons:building-storefront" class="text-purple-600"></iconify-icon>
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 bg-gray-100 rounded-full p-2 mr-3">
                                            <iconify-icon icon="heroicons:bell" class="text-gray-600"></iconify-icon>
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
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
                <button onclick="toggleDropdown('profileDropdown')" 
                        class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-lg">
                    @if(auth()->user()->image)
                        <img src="{{ asset('storage/images/' . auth()->user()->image) }}" 
                             alt="Profile Picture" 
                             class="w-8 h-8 rounded-full object-cover">
                    @else
                        @php
                            // Gunakan username jika name tidak ada
                            $username = auth()->user()->username ?? 'User';
                            $initials = collect(explode(' ', $username))->map(function ($word) {
                                return strtoupper(substr($word, 0, 1));
                            })->take(2)->join('');
                        @endphp
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                            {{ $initials }}
                        </div>
                    @endif
                    <span class="nav-text">{{ auth()->user()->username }}</span>
                </button>
                
                <div id="profileDropdown" class="dropdown-menu absolute bg-white shadow-lg rounded-lg mt-2 py-2 w-48">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
