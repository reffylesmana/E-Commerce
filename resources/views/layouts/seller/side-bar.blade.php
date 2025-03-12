<aside id="sidebar" class="sidebar bg-white shadow-lg z-50">
    <div class="p-4 h-full flex flex-col">
        @php
            $user = Auth::user()->load('store');
            $store = $user->store;
            $storeApproved = $store && $store->is_approved && $store->is_active;
        @endphp

        <!-- Logo Section -->
        <div class="flex items-center gap-2 mb-8 p-2">
            <a href="/" class="flex items-center gap-2 transition-all">
                <img src="{{ asset('img/logo.png') }}" alt="TeknoShop Logo" class="w-8 h-8 transition-all">
                <span class="text-lg font-bold nav-text text-gray-800">
                    TeknoShop
                </span>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="space-y-2 flex-1">
            <!-- Dashboard -->
            <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-3 p-3 hover:bg-gray-100 rounded-lg">
                <iconify-icon icon="heroicons:home" class="text-2xl"></iconify-icon>
                <span class="nav-text">Dashboard</span>
            </a>

            <!-- My Store -->
            <a href="{{ route('seller.store.index') }}" class="flex items-center gap-3 p-3 hover:bg-gray-100 rounded-lg">
                <iconify-icon icon="heroicons:building-storefront" class="text-2xl"></iconify-icon>
                <span class="nav-text">My Store</span>
            </a>

            <!-- Products -->
            <div class="relative group"
                 @if(!$storeApproved) onclick="showApprovalAlert()" @endif>
                <a href="{{ $storeApproved ? route('seller.products.index') : 'javascript:void(0)' }}" 
                   class="flex items-center gap-3 p-3 {{ $storeApproved ? 'hover:bg-gray-100' : 'opacity-50 cursor-not-allowed' }} rounded-lg">
                    <iconify-icon icon="heroicons:shopping-cart" class="text-2xl"></iconify-icon>
                    <span class="nav-text">Products</span>
                </a>
            </div>

            <!-- Analytics -->
            <div class="relative group"
                 @if(!$storeApproved) onclick="showApprovalAlert()" @endif>
                <a href="#" 
                   class="flex items-center gap-3 p-3 {{ $storeApproved ? 'hover:bg-gray-100' : 'opacity-50 cursor-not-allowed' }} rounded-lg">
                    <iconify-icon icon="heroicons:chart-bar" class="text-2xl"></iconify-icon>
                    <span class="nav-text">Analytics</span>
                </a>
            </div>
        </nav>

        <!-- Collapse Button -->
        <button onclick="toggleSidebar()" class="mt-auto p-2 hover:bg-gray-100 rounded-lg">
            <iconify-icon id="sidebar-toggle-icon" icon="heroicons:chevron-double-left" class="text-2xl"></iconify-icon>
        </button>
    </div>

    <script>
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
    </script>
</aside>