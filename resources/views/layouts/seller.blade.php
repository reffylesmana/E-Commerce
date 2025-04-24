<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('TechnoShop', 'Seller Dashboard') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="TechnoShop">

    <!-- Iconify CDN -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- DataTables Styling -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <!-- Select2 Dropdown -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

    <!-- Tom Select (Alternative Select) -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    

    <!-- Styles -->
    @vite('resources/css/app.css')

    <!-- Scripts -->
    @vite('resources/js/app.js')
    @php
        $user = auth()->user();
        $store = $user->store ?? null;
        $storeApproved = $store && $store->is_approved;
    @endphp
    <style>
        /* Custom CSS for Seller Menu */
        :root {
            --primary-color: #1e3a8a;
            --hover-color: #3b82f6;
            --notification-bg: #f0f9ff;
        }

        .header {
            background-color: var(--primary-color);
            color: white;
        }

        .header h1 {
            color: white;
        }

        .header .dropdown-menu {
            background-color: white;
            border: 1px solid var(--primary-color);
        }

        .header .dropdown-menu a {
            color: var(--primary-color);
        }

        .header .dropdown-menu a:hover {
            background-color: var(--hover-color);
            color: white;
        }

        .sidebar {
            background-color: var(--primary-color);
        }

        .sidebar a {
            color: white;
        }

        .sidebar a:hover {
            background-color: var(--hover-color);
        }

        .sidebar .nav-text {
            transition: color 0.3s ease;
        }

        .sidebar .nav-text:hover {
            color: var(--hover-color);
        }


        .dropdown-menu {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="h-full">
    <div class="flex">
        <!-- Sidebar -->
        @include('layouts.seller.side-bar')

        <!-- Main Content -->
        <div class="main-content flex-1 min-h-screen">
            <!-- Navigation -->
            @include('layouts.seller.navigation')

            <!-- Page Content -->
            <main class="">
                @yield('content')
            </main>
        </div>
    </div>


    <!-- JavaScript Libraries -->
    <!-- jQuery (Required oleh DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables -->
    <!-- Versi 1.10.25 (Legacy) -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- DataTables Versi 1.11.5 (Disarankan) -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- Select2 (Untuk dropdown yang lebih baik) -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 (Untuk notifikasi modern) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.8/dist/sweetalert2.all.min.js"></script>

    <!-- Tom Select (Alternatif select box) -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>

        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('sidebar-toggle-icon');

            sidebar.classList.toggle('sidebar-collapsed');

            // Update icon berdasarkan state sidebar
            if (sidebar.classList.contains('sidebar-collapsed')) {
                toggleIcon.setAttribute('icon', 'heroicons:chevron-double-right');
            } else {
                toggleIcon.setAttribute('icon', 'heroicons:chevron-double-left');
            }
        }

        // Dropdown Management
        function toggleDropdown(menuId) {
            const menu = document.getElementById(menuId);
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }

        // Close dropdowns on outside click
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('.dropdown-menu');
            dropdowns.forEach(dropdown => {
                if (!event.target.closest('.dropdown-parent') &&
                    !event.target.closest('.dropdown-menu')) {
                    dropdown.style.display = 'none';
                }
            });
        });

        // Dark Mode Toggle
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>
</body>

</html>
