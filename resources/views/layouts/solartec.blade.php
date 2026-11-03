{{--
  Master Layout for SolarShare Front-Office
  Template Source: Solartec - Renewable Energy Website Template
  Template Link: https://htmlcodex.com/renewable-energy-website-template
  Author: HTML Codex (https://htmlcodex.com)
  Distributed By: ThemeWagon (https://themewagon.com)
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SolarShare — Location & Partage d\'Équipements Énergies Renouvelables')</title>
    <meta name="description" content="@yield('meta_description', 'Plateforme collaborative de location et partage de panneaux solaires portables, batteries nomades et micro-équipements éoliens entre particuliers.')">

    <!-- Favicon -->
    <link href="{{ asset('solartec/img/favicon.ico') }}" rel="icon">

    @if(app()->getLocale() === 'ar')
        <!-- Google Font for Arabic -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
        <style>
            body, h1, h2, h3, h4, h5, h6, .ss-btn, .nav-link, button, input, select, textarea {
                font-family: 'Cairo', 'DM Sans', sans-serif !important;
            }
            .breadcrumb-item + .breadcrumb-item::before {
                float: right !important;
                padding-left: 0.5rem !important;
                padding-right: 0 !important;
            }
            /* Logical RTL adaptations for Bootstrap 5 LTR utility classes */
            [dir="rtl"] .me-1 { margin-right: 0 !important; margin-left: 0.25rem !important; }
            [dir="rtl"] .me-2 { margin-right: 0 !important; margin-left: 0.5rem !important; }
            [dir="rtl"] .me-3 { margin-right: 0 !important; margin-left: 1rem !important; }
            [dir="rtl"] .me-4 { margin-right: 0 !important; margin-left: 1.5rem !important; }
            [dir="rtl"] .ms-1 { margin-left: 0 !important; margin-right: 0.25rem !important; }
            [dir="rtl"] .ms-2 { margin-left: 0 !important; margin-right: 0.5rem !important; }
            [dir="rtl"] .ms-3 { margin-left: 0 !important; margin-right: 1rem !important; }
            [dir="rtl"] .ps-3 { padding-left: 0 !important; padding-right: 1rem !important; }
            [dir="rtl"] .end-0 { right: auto !important; left: 0 !important; }
            [dir="rtl"] .start-0 { left: auto !important; right: 0 !important; }
            [dir="rtl"] .back-to-top { right: auto !important; left: 30px !important; }
            [dir="rtl"] .header-carousel .owl-dots { right: auto !important; left: 45px !important; }
            [dir="rtl"] .form-select {
                background-position: left 0.75rem center !important;
                padding-left: 2.25rem !important;
                padding-right: 0.85rem !important;
            }
        </style>
    @endif

    <!-- Core Design System & Assets (Same unified navbar, footer, buttons, and animations) -->
    @vite(['resources/js/front/landing.js'])

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Solartec Libraries Stylesheet -->
    <link href="{{ asset('solartec/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('solartec/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('solartec/lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">

    <!-- Solartec Specific Components Stylesheet -->
    <link href="{{ asset('solartec/css/style.css') }}" rel="stylesheet">

    <!-- Custom Adjustments to Harmonize Solartec Sections with SolarShare Design System -->
    <style>
        body {
            background-color: var(--ss-sky-bottom, #FFF8F0);
            color: var(--ss-ink, #10202B);
            font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Sora', 'DM Sans', sans-serif;
            color: var(--ss-ink, #10202B);
        }
        .header-carousel .owl-carousel-item {
            position: relative;
            min-height: 540px;
        }
        .owl-carousel-inner {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            background: rgba(16, 32, 43, 0.65);
        }
        .equipment-card {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
            border-radius: 16px;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid rgba(16, 32, 43, 0.08);
        }
        .equipment-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -15px rgba(16, 32, 43, 0.15) !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--ss-sun-amber, #FFB020), var(--ss-ember-coral, #FF6B3D)) !important;
            border: none !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 8px 20px -6px rgba(255, 112, 67, 0.4) !important;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #f5a616, #e85d2e) !important;
            box-shadow: 0 12px 24px -6px rgba(255, 112, 67, 0.5) !important;
            transform: translateY(-2px);
        }
        .text-primary {
            color: var(--ss-deep-teal, #0F5C6B) !important;
        }
        .bg-primary {
            background-color: var(--ss-deep-teal, #0F5C6B) !important;
        }
        .badge-solar {
            background: var(--ss-sun-amber, #FFB020);
            color: var(--ss-ink, #10202B);
            font-weight: 700;
        }
        .ss-navbar {
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border-bottom: 1px solid rgba(16, 32, 43, 0.08) !important;
            box-shadow: 0 4px 20px rgba(16, 32, 43, 0.04) !important;
            padding: 0.75rem 0 !important;
            margin-bottom: 0 !important;
        }
        main {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Separated Navbar Partial (Exact same design across all pages) -->
    @includeUnless(request()->routeIs('onboarding.*'), 'partials.solartec.navbar')

    <!-- Flash messages -->
    @if (session('success'))
    <div class="container mt-4">
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if (session('error'))
    <div class="container mt-4">
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center rounded-3 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if (session('warning'))
    <div class="container mt-4">
        <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center rounded-3 shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
            <div>{{ session('warning') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if (isset($errors) && $errors->any())
    <div class="container mt-4">
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ __('messages.common.fix_errors') }}</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.common.close') }}"></button>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Separated Footer Partial (Exact same design with template attribution) -->
    @includeUnless(request()->routeIs('onboarding.*'), 'partials.solartec.footer')

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top" aria-label="{{ __('messages.common.back_to_top') }}">
        <i class="bi bi-arrow-up"></i>
    </a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="{{ asset('solartec/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('solartec/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('solartec/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('solartec/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('solartec/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('solartec/lib/isotope/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('solartec/lib/lightbox/js/lightbox.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('solartec/js/main.js') }}"></script>

    @stack('scripts')
</body>
</html>
