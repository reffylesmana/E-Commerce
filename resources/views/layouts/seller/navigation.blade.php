<header class="bg-white shadow-sm">
    <div class="flex items-center justify-between p-4">
        <!-- Left Section -->
        <div class="flex items-center gap-4">
            <h1 class="text-xl font-semibold">@yield('title', 'Dashboard')</h1>
        </div>

        <!-- Right Section -->
        <div class="flex items-center gap-4">
            <!-- Notification -->
            <div class="relative">
                <button onclick="toggleDropdown('notificationDropdown')" 
                        class="p-2 hover:bg-gray-100 rounded-full relative">
                    <iconify-icon icon="heroicons:bell" class="text-2xl"></iconify-icon>
                    <span class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-4 h-4 text-xs">3</span>
                </button>
                <div id="notificationDropdown" class="dropdown-menu absolute bg-white shadow-lg rounded-lg mt-2 py-2 w-48">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">New order received</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">System alert</a>
                </div>
            </div>

            <!-- User Profile -->
            <div class="relative dropdown-parent">
                <button onclick="toggleDropdown('profileDropdown')" 
                        class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-lg">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" 
                             alt="User" 
                             class="w-8 h-8 rounded-full object-cover">
                    @else
                        @php
                            $name = auth()->user()->name ?? 'User';
                            $initials = collect(explode(' ', $name))->map(function ($word) {
                                return strtoupper(substr($word, 0, 1));
                            })->take(2)->join('');
                        @endphp
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                            {{ $initials }}
                        </div>
                    @endif
                    <span class="nav-text">{{ $name }}</span>
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