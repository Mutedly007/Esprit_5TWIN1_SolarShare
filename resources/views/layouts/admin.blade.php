{{--
  Master Layout for SolarShare Back-Office (Admin Dashboard)
  Template Source: FreeDash Bootstrap 5 Admin Template
  Template Link: https://adminmart.com/product/freedash-bootstrap-5-admin-template/
  Author: AdminMart (https://adminmart.com)
--}}
<!DOCTYPE html>
<html dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SolarShare Admin — Tableau de Bord')</title>

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin-assets/assets/images/favicon.png') }}">

    @if(app()->getLocale() === 'ar')
        <!-- Google Font for Arabic in Admin -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
        <style>
            body, h1, h2, h3, h4, h5, h6, .navbar, .sidebar-nav, button, input, select, textarea {
                font-family: 'Cairo', sans-serif !important;
            }
        </style>
    @endif

    <!-- Vendor CSS -->
    <link href="{{ asset('admin-assets/assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-assets/assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />

    <!-- Custom CSS -->
    <link href="{{ asset('admin-assets/dist/css/style.min.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Rubik', sans-serif;
            background-color: #f7f9fc;
        }
        .page-wrapper {
            background-color: #f7f9fc;
        }
        .card {
            border: 0 !important;
            border-radius: 14px !important;
            box-shadow: 0 0 35px 0 rgba(154, 161, 171, 0.12) !important;
            margin-bottom: 25px !important;
            background: #ffffff !important;
        }
        .card-body {
            padding: 1.5rem !important;
        }
        .page-breadcrumb {
            padding: 25px 30px 10px 30px !important;
        }
        .container-fluid {
            padding: 15px 30px 30px 30px !important;
        }
        .solar-badge {
            background: linear-gradient(135deg, #5f76e8, #7b8ff5);
            color: #fff;
            font-weight: 700;
        }
        /* Chartist bar style matching FreeDash */
        .net-income .ct-series-a .ct-bar {
            stroke: #5f76e8 !important;
            stroke-width: 14px !important;
        }
        /* Custom scrollbar */
        .scroll-sidebar {
            height: calc(100% - 20px);
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <!-- Main wrapper -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

        <!-- Topbar Header Partial -->
        @include('partials.admin.header')

        <!-- Left Sidebar Partial -->
        @include('partials.admin.sidebar')

        <!-- Page wrapper -->
        <div class="page-wrapper">
            <!-- Content -->
            @yield('content')

            <!-- Footer Partial -->
            @include('partials.admin.footer')
        </div>

    </div>

    <!-- All Jquery & Plugins -->
    <script src="{{ asset('admin-assets/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin-assets/dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('admin-assets/dist/js/feather.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('admin-assets/dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('admin-assets/dist/js/custom.min.js') }}"></script>

    <!-- Charts Plugins -->
    <script src="{{ asset('admin-assets/assets/extra-libs/c3/d3.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/extra-libs/c3/c3.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/libs/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <script src="{{ asset('admin-assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('admin-assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('admin-assets/dist/js/pages/dashboards/dashboard1.js') }}"></script>

    @stack('scripts')
</body>

</html>
