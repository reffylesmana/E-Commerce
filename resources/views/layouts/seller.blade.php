<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'TailAdmin Dashboard') }}</title>
    <meta name="description" content="TailAdmin Dashboard Template">

    <!-- Iconify CDN -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Styles -->
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <!-- Scripts -->
    @vite('resources/js/app.js')
    @php
    $user = auth()->user();
    $store = $user->store ?? null;
    $storeApproved = $store && $store->is_approved;
@endphp
    <style>
        /* Custom CSS */

        * {
            transition: all 0.15 s ease-in-out;
        }

        :root {
            --sidebar-width-expanded: 16rem;
            --sidebar-width-collapsed: 5rem;
            --transition-duration: 0.3s;
        }

        .sidebar {
            width: var(--sidebar-width-expanded);
            transition: all var(--transition-duration) ease;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar-collapsed {
            width: var(--sidebar-width-collapsed);
        }

        .main-content {
            margin-left: var(--sidebar-width-expanded);
            transition: margin var(--transition-duration) ease;
        }

        .sidebar-collapsed+.main-content {
            margin-left: var(--sidebar-width-collapsed);
        }

        .nav-text {
            opacity: 1;
            transition: opacity var(--transition-duration) ease;
        }

        /* Di dalam tag style di main layout */
        .sidebar-collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .sidebar:not(.sidebar-collapsed) .nav-text {
            opacity: 1;
            width: auto;
        }

        .sidebar-collapsed img {
            margin-left: 0.25rem;
            transform: scale(0.9);
        }

        .dropdown-menu {
            display: none;
            right: 0;
            min-width: 12rem;
        }

        .dark-mode {
            background-color: #1a202c;
            color: #fff;
        }

        .dark-mode .sidebar {
            background-color: #2d3748;
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


    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- TomSelect -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
        // Di dalam template (bagian script)
        @if (Session::has('swal'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: "{{ Session::get('swal.icon', 'success') }}",
                    title: "{{ Session::get('swal.title') }}",
                    text: "{{ Session::get('swal.text') }}",
                    confirmButtonText: "{{ Session::get('swal.button', 'OK') }}",
                    confirmButtonColor: "#6366f1",
                    timer: {{ Session::get('swal.timer', 'null') }}, // dalam milidetik
                    timerProgressBar: true,
                });
            });
        @endif
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
