<style>
    .sidebar {
        background-color: #ffffff;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
    }

    .sidebar a {
        color: #000;
        transition: all 0.2s ease;
    }

    .sidebar a:hover {
        background-color: #e3f2fd;
        color: #1D4ED8;
        transform: translateX(3px);
    }

    .sidebar iconify-icon {
        color: #000;
        transition: color 0.2s ease;
    }

    .sidebar a:hover iconify-icon {
        color: #1D4ED8;
    }

    .sidebar a.active {
        color: #1D4ED8;
    }

    .sidebar a.active iconify-icon {
        color: #1D4ED8;
    }

    #transaksi-dropdown {
        border-left: 2px solid #1D4ED8;
    }

    .dropdown-item {
        color: #000;
        transition: padding 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f0f4f8;
        padding-left: 1.25rem;
    }

    .rotate-180 {
        transform: rotate(180deg);
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar .official-feature {
        border-left: 2px solid #10b981;
    }

    /* Style untuk menu yang disabled */
    .disabled-menu {
        opacity: 0.5;
        cursor: not-allowed !important;
        pointer-events: auto; /* Tetap bisa diklik untuk menampilkan alert */
    }

    .disabled-menu:hover {
        background-color: transparent !important;
        color: #000 !important;
        transform: none !important;
    }

    .disabled-menu:hover iconify-icon {
        color: #000 !important;
    }
</style>
<aside id="sidebar" class="sidebar bg-white shadow-lg z-50">
    <div class="p-4 h-full flex flex-col overflow-y-auto max-h-screen">
        @php
            $user = Auth::user()->load('store');
            $store = $user->store;
            $storeApproved = $store && $store->is_approved && $store->is_active;
            $isOfficial = $store && $store->is_official;
        @endphp

        <!-- Logo Section -->
        <div class="flex items-center gap-2 mb-8 p-2">
            <a href="/" class="flex items-center gap-2 transition-all">
                <img src="{{ asset('img/logo.png') }}" alt="TeknoShop Logo" class="w-8 h-8 transition-all">
                <span class="text-lg font-bold nav-text text-gray-800">TeknoShop</span>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="space-y-2 flex-1">
            <!-- Dashboard - Selalu aktif -->
            <a href="{{ route('seller.dashboard') }}"
                class="flex items-center gap-3 p-3 hover:bg-blue-50 rounded-lg transition-all duration-300 
               {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
                <iconify-icon icon="heroicons:home" class="text-2xl text-blue-600"></iconify-icon>
                <span class="nav-text">Dashboard</span>
            </a>

            <!-- My Store - Selalu aktif -->
            <a href="{{ route('seller.store.index') }}"
                class="flex items-center gap-3 p-3 hover:bg-blue-50 rounded-lg transition-all duration-300
               {{ request()->routeIs('seller.store.*') ? 'active' : '' }}">
                <iconify-icon icon="heroicons:building-storefront" class="text-2xl text-blue-600"></iconify-icon>
                <span class="nav-text">My Store</span>
            </a>

            <!-- Products -->
            <a href="{{ $storeApproved ? route('seller.products.index') : 'javascript:void(0)' }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all duration-300 {{ $storeApproved ? 'hover:bg-blue-50' : 'disabled-menu' }}"
                @if(!$storeApproved) onclick="showApprovalAlert()" @endif>
                <iconify-icon icon="heroicons:shopping-cart" class="text-2xl text-blue-600"></iconify-icon>
                <span class="nav-text">Products</span>
            </a>

            <!-- Transaksi Dropdown -->
            <div class="relative" x-data="{ open: {{ request()->routeIs('seller.transactions.*') ? 'true' : 'false' }} }">
                <div class="flex items-center gap-3 p-3 rounded-lg cursor-pointer transition-all duration-300 {{ $storeApproved ? 'hover:bg-blue-50' : 'disabled-menu' }}"
                    id="transaksi-trigger" @if(!$storeApproved) onclick="showApprovalAlert()" @endif>
                    <iconify-icon icon="heroicons:currency-dollar" class="text-2xl text-blue-600"></iconify-icon>
                    <span class="nav-text">Transaksi</span>
                    <iconify-icon id="transaksi-chevron" icon="heroicons:chevron-down"
                        class="text-xl ml-auto transition-transform duration-200 text-blue-600 
                        {{ request()->routeIs('seller.transactions.*') ? 'rotate-180' : '' }}"></iconify-icon>
                </div>

                <!-- Dropdown Menu -->
                <div id="transaksi-dropdown"
                    class="transition-all duration-300 max-h-0 overflow-hidden {{ request()->routeIs('seller.transactions.*') ? 'open' : 'hidden' }}">
                    <div class="bg-white shadow-lg rounded-lg mt-1">
                        <div class="py-1">
                            <a href="{{ $storeApproved ? route('seller.transactions.cart.cart') : 'javascript:void(0)' }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm dropdown-item transition-all duration-300 {{ $storeApproved ? 'hover:bg-blue-50' : 'disabled-menu' }}"
                                @if(!$storeApproved) onclick="showApprovalAlert()" @endif>
                                <iconify-icon icon="heroicons:shopping-cart" class="text-lg text-blue-600"></iconify-icon>
                                Keranjang Belanja
                            </a>
                            <a href="{{ $storeApproved ? route('seller.transactions.orders.orders') : 'javascript:void(0)' }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm dropdown-item transition-all duration-300 {{ $storeApproved ? 'hover:bg-blue-50' : 'disabled-menu' }}"
                                @if(!$storeApproved) onclick="showApprovalAlert()" @endif>
                                <iconify-icon icon="heroicons:shopping-bag" class="text-lg text-blue-600"></iconify-icon>
                                Pemesanan
                            </a>
                            <a href="{{ $storeApproved ? route('seller.transactions.payments.payments') : 'javascript:void(0)' }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm dropdown-item transition-all duration-300 {{ $storeApproved ? 'hover:bg-blue-50' : 'disabled-menu' }}"
                                @if(!$storeApproved) onclick="showApprovalAlert()" @endif>
                                <iconify-icon icon="heroicons:credit-card" class="text-lg text-blue-600"></iconify-icon>
                                Pembayaran
                            </a>
                            <a href="{{ $storeApproved ? route('seller.transactions.shipping.shipping') : 'javascript:void(0)' }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm dropdown-item transition-all duration-300 {{ $storeApproved ? 'hover:bg-blue-50' : 'disabled-menu' }}"
                                @if(!$storeApproved) onclick="showApprovalAlert()" @endif>
                                <iconify-icon icon="heroicons:truck" class="text-lg text-blue-600"></iconify-icon>
                                Pengiriman
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ulasan -->
            <a href="{{ $storeApproved ? route('seller.reviews.index') : 'javascript:void(0)' }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all duration-300 {{ $storeApproved ? 'hover:bg-blue-50' : 'disabled-menu' }}"
                @if(!$storeApproved) onclick="showApprovalAlert()" @endif>
                <iconify-icon icon="heroicons:chat-bubble-left-right" class="text-2xl text-blue-600"></iconify-icon>
                <span class="nav-text">Ulasan & Testimoni</span>
            </a>

            <!-- Official Store Features -->
            @if ($isOfficial && $storeApproved)
                <!-- Banner Management -->
                @if ($store->canManageBanners())
                    <div class="relative group">
                        <a href="{{ route('seller.banners.index') }}"
                            class="flex items-center gap-3 p-3 hover:bg-blue-50 rounded-lg official-feature {{ request()->routeIs('seller.banners.*') ? 'active' : '' }}">
                            <iconify-icon icon="heroicons:photo" class="text-2xl text-blue-600"></iconify-icon>
                            <span class="nav-text">Kelola Banner</span>
                        </a>
                    </div>
                @endif

                <!-- Blog Management -->
                @if ($store->canManageBlogs())
                    <div class="relative group">
                        <a href="{{ route('seller.blogs.index') }}"
                            class="flex items-center gap-3 p-3 hover:bg-blue-50 rounded-lg official-feature {{ request()->routeIs('seller.blogs.*') ? 'active' : '' }}">
                            <iconify-icon icon="heroicons:document-text" class="text-2xl text-blue-600"></iconify-icon>
                            <span class="nav-text">Kelola Blog</span>
                        </a>
                    </div>
                @endif

                <!-- Discount Management -->
                @if ($storeApproved && $store->canManageDiscounts())
                    <div class="relative group">
                        <a href="{{ route('seller.discounts.index') }}"
                            class="flex items-center gap-3 p-3 hover:bg-blue-50 rounded-lg official-feature {{ request()->routeIs('seller.discounts.*') ? 'active' : '' }}">
                            <iconify-icon icon="heroicons:tag" class="text-2xl text-blue-600"></iconify-icon>
                            <span class="nav-text">Kelola Diskon</span>
                        </a>
                    </div>
                @endif
            @endif

            <!-- Laporan -->
            <a href="{{ $storeApproved ? route('seller.reports') : 'javascript:void(0)' }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-all duration-300 {{ $storeApproved ? 'hover:bg-blue-50' : 'disabled-menu' }}"
                @if(!$storeApproved) onclick="showApprovalAlert()" @endif>
                <iconify-icon icon="heroicons:document-chart-bar" class="text-2xl text-blue-600"></iconify-icon>
                <span class="nav-text">Laporan Transaksi</span>
            </a>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle dropdown only if store is approved
            @if($storeApproved)
                let isDropdownOpen = {{ request()->routeIs('seller.transactions.*') ? 'true' : 'false' }};

                // Menangani klik pada dropdown
                $('#transaksi-trigger').click(function(event) {
                    event.stopPropagation();
                    isDropdownOpen = !isDropdownOpen;
                    $('#transaksi-dropdown').toggleClass('hidden');
                    $('#transaksi-chevron').toggleClass('rotate-180');

                    // Menyesuaikan tinggi dropdown
                    if (isDropdownOpen) {
                        $('#transaksi-dropdown').css('max-height', $('#transaksi-dropdown')[0].scrollHeight + 'px');
                    } else {
                        $('#transaksi-dropdown').css('max-height', '0');
                    }
                });

                // Menjaga dropdown tetap terbuka saat di dalam submenu yang aktif
                if (window.location.pathname.includes('/transactions')) {
                    $('#transaksi-dropdown').removeClass('hidden');
                    $('#transaksi-chevron').addClass('rotate-180');
                    isDropdownOpen = true;
                    $('#transaksi-dropdown').css('max-height', $('#transaksi-dropdown')[0].scrollHeight + 'px');
                }

                // Menangani klik pada dropdown item agar tidak menutup
                $('.dropdown-item').click(function(event) {
                    event.stopPropagation();
                });

                // Menutup dropdown jika klik di luar dropdown
                $(document).click(function(event) {
                    if (!$(event.target).closest('#transaksi-trigger').length && !$(event.target).closest('#transaksi-dropdown').length) {
                        // Don't close if we're on a transaction page
                        if (!window.location.pathname.includes('/transactions')) {
                            isDropdownOpen = false;
                            $('#transaksi-dropdown').css('max-height', '0');
                            $('#transaksi-chevron').removeClass('rotate-180');
                        }
                    }
                });
            @endif
        });

        // Approval Alert
        function showApprovalAlert() {
            Swal.fire({
                title: 'Akses Dibatasi!',
                text: 'Silakan lengkapi pengaturan toko dan tunggu persetujuan admin untuk mengakses fitur ini',
                icon: 'warning',
                confirmButtonText: 'Ke Halaman Toko',
                confirmButtonColor: '#6366f1',
                showCancelButton: true,
                cancelButtonText: 'Tutup'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('seller.store.index') }}";
                }
            });
        }

        // Toggle Sidebar ```javascript
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('sidebar-toggle-icon');

            sidebar.classList.toggle('collapsed');
            toggleIcon.setAttribute('icon',
                sidebar.classList.contains('collapsed') ?
                'heroicons:chevron-double-right' :
                'heroicons:chevron-double-left'
            );
        }
    </script>
</aside>

