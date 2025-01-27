<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Keania+One&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Rajdhani:wght@300;400;500;600;700&family=Prosto+One&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/jqvmap/jqvmap.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/dist/css/adminlte.min.css') }}">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/daterangepicker/daterangepicker.css') }}">

    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3.2.0/plugins/summernote/summernote-bs4.min.css') }}">

    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">

    {{-- Sweetalert --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- My Style -->
    <style>
        * {
            font-family: Poppins;
        }

        .container .row {
            margin-left: 1rem;
            margin-right: 1rem;
        }

        /* Side-Bar */
        .brand-text {
            font-family: Keania One;
        }

        .side-bar {
            font-weight: 400;
            color: black;
            border-radius: 30px;
            transition: all .3s ease-in;
        }

        .side-bar:hover {
            background-color: #D16A03;
            border-radius: 30px;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.419);
        }

        .side-bar:hover i,
        .side-bar:hover p {
            color: white;
        }

        .side-bar.aktif {
            background-color: #D16A03;
            border-radius: 30px;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.419);
        }

        .side-bar.aktif .nav-link i,
        .side-bar.aktif .nav-link p {
            color: white;
        }

        /* Content */
        .title-page {
            font-family: Rajdhani;
            font-size: 30px;
            font-weight: 700;
            margin-top: 5.5rem;
        }

        .card-stats {
            border-radius: 10px;
            box-shadow: 0px 5px 5px rgba(128, 128, 128, 0.459);
            font-size: 1.5rem;
            font-weight: 700;
        }

        .card-sertifikat {
            background-color: #D9D9D9;
            box-shadow: 5px 5px 5px rgba(128, 128, 128, 0.664);
            border-radius: 10px;
            padding: .8rem;
        }

        .card-sertifikat .card-body img {
            margin-left: -3rem;
            margin-top: -1rem;
        }

        .card-view {
            background-color: #D9D9D9;
            border-radius: 30px;
            font-size: 1rem;
            transition: all .3s ease;
        }

        .card-view:hover {
            color: white;
            background-color: #D16A03;
            box-shadow: 0px 0px 20px black;
        }

        .main-footer {
            position: fixed;
            bottom: 0;
            right: 0;
            left: 0;
            margin-top: 1rem;
        }

        /* Tambahkan di dalam <style> di bagian <head> atau file CSS terpisah */
        .table td {
            white-space: nowrap; /* Mencegah teks berpindah ke baris baru */
            overflow: hidden; /* Menyembunyikan teks yang melampaui ukuran kolom */
            text-overflow: ellipsis; /* Menampilkan ... ketika teks terlalu panjang */
        }

        .table th {
            white-space: nowrap; /* Sama untuk header agar konsisten */
        }


        /* Sticky footer */
        html,
        body {
            height: 100%;
            margin: 0;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            flex-grow: 1;
            padding-bottom: 8rem;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        @include('layouts.seller.navigation')

        <!-- Main Sidebar Container -->
        @include('layouts.seller.side-bar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper flex-grow-1">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2024 <a href="">GuruMagang.id</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>TEFA IFSU</b>
            </div>
        </footer>

    </div>
    <!-- ./wrapper -->

    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-3.2.0/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 5 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Summernote -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('AdminLTE-3.2.0/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLTE-3.2.0/dist/js/adminlte.js') }}"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
</body>

</html>
