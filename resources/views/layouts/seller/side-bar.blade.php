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
            <a href="{{ route('seller.store.index') }}"
                class="flex items-center gap-3 p-3 hover:bg-gray-100 rounded-lg">
                <iconify-icon icon="heroicons:building-storefront" class="text-2xl"></iconify-icon>
                <span class="nav-text">My Store</span>
            </a>

            <!-- Products -->
            <div class="relative group" @if (!$storeApproved) onclick="showApprovalAlert()" @endif>
                <a href="{{ $storeApproved ? route('seller.products.index') : 'javascript:void(0)' }}"
                    class="flex items-center gap-3 {{ $storeApproved ? 'hover:bg-gray-100' : 'opacity-50 cursor-not-allowed' }} rounded-lg p-3">
                    <iconify-icon icon="heroicons:shopping-cart" class="text-2xl"></iconify-icon>
                    <span class="nav-text">Products</span>
                </a>
            </div>

            <!-- Transaksi Dropdown -->
            <div class="relative">
                <div class="flex items-center gap-3 p-3 hover:bg-gray-100 rounded-lg cursor-pointer"
                    id="transaksi-trigger">
                    <iconify-icon icon="heroicons:currency-dollar" class="text-2xl"></iconify-icon>
                    <span class="nav-text">Transaksi</span>
                    <iconify-icon id="transaksi-chevron" icon="heroicons:chevron-down"
                        class="text-xl ml-auto transition-transform duration-200"></iconify-icon>
                </div>

                <!-- Dropdown Menu -->
                <div id="transaksi-dropdown" class="hidden transition-all duration-300 overflow-hidden max-h-0 " onclick="showApprovalAlert()">
                    <div class="bg-white shadow-lg rounded-lg mt-1">
                        <div class="py-1">
                            <a href="{{ $storeApproved ? route('seller.transactions.orders.orders') : 'javascript:void(0)' }}"
                                class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-sm dropdown-item {{ $storeApproved ? 'hover:bg-gray-100' : 'opacity-50 cursor-not-allowed' }} ">
                                <iconify-icon icon="heroicons:shopping-bag" class="text-lg"></iconify-icon>
                                Pemesanan
                            </a>
                            <a href="{{ $storeApproved ? route('seller.transactions.payments.payments') : 'javascript:void(0)' }}"
                                class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-sm dropdown-item {{ $storeApproved ? 'hover:bg-gray-100' : 'opacity-50 cursor-not-allowed' }} ">
                                <iconify-icon icon="heroicons:credit-card" class="text-lg"></iconify-icon>
                                Pembayaran
                            </a>
                            <a href="{{ $storeApproved ? route('seller.transactions.shipping.shipping') : 'javascript:void(0)' }}"
                                class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-sm dropdown-item {{ $storeApproved ? 'hover:bg-gray-100' : 'opacity-50 cursor-not-allowed' }} ">
                                <iconify-icon icon="heroicons:truck" class="text-lg"></iconify-icon>
                                Pengiriman
                            </a>
                            <a href="{{ $storeApproved ? route('seller.transactions.cart.cart') : 'javascript:void(0)' }}"
                                class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-sm dropdown-item {{ $storeApproved ? 'hover:bg-gray-100' : 'opacity-50 cursor-not-allowed' }} ">
                                <iconify-icon icon="heroicons:shopping-cart" class="text-lg"></iconify-icon>
                                Keranjang Belanja
                            </a>
                            <a href="{{ $storeApproved ? route('seller.transactions.reports.reports') : 'javascript:void(0)' }}"
                                class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-sm dropdown-item {{ $storeApproved ? 'hover:bg-gray-100' : 'opacity-50 cursor-not-allowed' }} ">
                                <iconify-icon icon="heroicons:document-text" class="text-lg"></iconify-icon>
                                Laporan Transaksi
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ulasan -->
            <div class="relative group" @if (!$storeApproved) onclick="showApprovalAlert()" @endif>
                <a href="#" class="flex items-center gap-3 p-3 hover:bg-gray-100 rounded-lg {{ $storeApproved ? 'hover:bg-gray-100' : 'opacity-50 cursor-not-allowed' }} ">
                    <iconify-icon icon="heroicons:chat-bubble-left-right" class="text-2xl"></iconify-icon>
                    <span class="nav-text">Ulasan & Testimoni</span>
                </a>
            </div>
        </nav>
    </div>

    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }

        #transaksi-dropdown {
            min-width: 220px;
        }

        .sidebar.collapsed .nav-text {
            display: none;
        }

        .sidebar.collapsed #transaksi-dropdown {
            left: 0;
            /* Ubah posisi dropdown agar tetap dalam sidebar */
            top: 0;
            margin-left: 0;
            /* Hapus margin untuk menghindari keluar dari sidebar */
        }

        @media (max-width: 640px) {
            #transaksi-dropdown {
                width: 100%;
                /* Pastikan dropdown memenuhi lebar sidebar */
                left: 0;
                /* Ubah posisi ke kiri */
                right: 0;
                /* Pastikan dropdown tidak keluar dari sidebar */
            }

            #transaksi-chevron {
                margin-left: auto;
            }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let isDropdownOpen = false;

            $('#transaksi-trigger').click(function(event) {
                event.stopPropagation();
                isDropdownOpen = !isDropdownOpen;
                $('#transaksi-dropdown').toggleClass('hidden');
                $('#transaksi-chevron').toggleClass('rotate-180');

                if (isDropdownOpen) {
                    $('#transaksi-dropdown').css('max-height', $('#transaksi-dropdown')[0].scrollHeight +
                        'px');
                } else {
                    $('#transaksi-dropdown').css('max-height', '0');
                }
            });

            $('.dropdown-item').click(function(event) {
                event.stopPropagation();
            });

            $(document).click(function(event) {
                if (!$(event.target).closest('#transaksi-trigger').length) {
                    isDropdownOpen = false;
                    $('#transaksi-dropdown').css('max-height', '0');
                    $('#transaksi-chevron').removeClass('rotate-180');
                }
            });
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

        // Toggle Sidebar
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
