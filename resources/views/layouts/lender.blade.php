{{-- resources/views/layouts/lender.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Prêteur — SolarShare')</title>

    <!-- Favicon -->
    <link href="{{ asset('solartec/img/favicon.ico') }}" rel="icon">

    <!-- Core Design System & Assets (Same unified navbar, typography and branding as landing page) -->
    @vite(['resources/js/front/landing.js'])

    <!-- Icon Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Sora:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Design System CSS for SolarShare Lender Space -->
    <style>
        :root {
            --ss-amber: #FFB020;
            --ss-orange: #FF6B3D;
            --ss-teal: #0F5C6B;
            --ss-teal-dark: #083E49;
            --ss-dark: #10202B;
            --ss-slate: #64748B;
            --ss-slate-light: #94A3B8;
            --ss-bg: #F8FAFC;
            --ss-card-bg: #FFFFFF;
            --ss-border: #E2E8F0;
            --ss-border-light: #F1F5F9;
            --ss-shadow-sm: 0 1px 3px 0 rgba(16, 32, 43, 0.05);
            --ss-shadow-md: 0 4px 20px -2px rgba(16, 32, 43, 0.06), 0 2px 6px -1px rgba(16, 32, 43, 0.03);
            --ss-shadow-lg: 0 10px 30px -4px rgba(16, 32, 43, 0.08);
            --ss-radius-sm: 8px;
            --ss-radius-md: 14px;
            --ss-radius-lg: 20px;
            --ss-radius-pill: 9999px;
        }

        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--ss-bg);
            color: var(--ss-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* Harmonize Navbar with Landing Page exact aesthetic */
        .ss-navbar {
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border-bottom: 1px solid rgba(16, 32, 43, 0.08) !important;
            box-shadow: 0 4px 20px rgba(16, 32, 43, 0.04) !important;
            padding: 0.75rem 0 !important;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Container */
        .lender-container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Main Content */
        .lender-main {
            flex: 1;
            padding: 2rem 0 3.5rem 0;
        }

        /* 2-Column Lender Layout */
        .lender-layout {
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 2rem;
            align-items: start;
        }

        /* Sticky Sidebar Styling */
        .lender-sidebar {
            position: sticky;
            top: 86px;
            z-index: 30;
        }

        .lender-sidebar-inner {
            background: #FFFFFF;
            border: 1px solid var(--ss-border);
            border-radius: var(--ss-radius-lg);
            padding: 1.5rem 1.25rem;
            box-shadow: 0 4px 20px -2px rgba(16, 32, 43, 0.05);
            display: flex;
            flex-direction: column;
            gap: 1.35rem;
        }

        .sidebar-user-card {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding-bottom: 1.15rem;
            border-bottom: 1px solid var(--ss-border-light);
        }

        .sidebar-user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--ss-amber) 0%, var(--ss-orange) 100%);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(255, 107, 61, 0.25);
            flex-shrink: 0;
        }

        .sidebar-user-meta {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--ss-dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-space-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.72rem;
            font-weight: 700;
            color: #92400E;
            background: #FEF3C7;
            padding: 0.18rem 0.55rem;
            border-radius: var(--ss-radius-pill);
            width: fit-content;
        }

        .sidebar-pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #F59E0B;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
            animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        .sidebar-cta-section {
            width: 100%;
        }

        .sidebar-btn-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            background: linear-gradient(135deg, var(--ss-amber) 0%, var(--ss-orange) 100%);
            color: #071520;
            font-weight: 700;
            font-size: 0.94rem;
            padding: 0.78rem 1rem;
            border-radius: var(--ss-radius-md);
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(255, 107, 61, 0.32);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            border: none;
            cursor: pointer;
        }

        .sidebar-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 107, 61, 0.45);
            color: #071520;
        }

        .sidebar-nav-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .sidebar-nav-heading {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--ss-slate-light);
            padding: 0 0.5rem;
            margin-bottom: 0.25rem;
        }

        .sidebar-nav-list {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .sidebar-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: 12px;
            color: var(--ss-slate);
            font-size: 0.92rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
            position: relative;
        }

        .sidebar-nav-link:hover {
            background: #F8FAFC;
            color: var(--ss-dark);
            transform: translateX(3px);
        }

        .sidebar-nav-link.is-active {
            background: linear-gradient(90deg, rgba(255, 176, 32, 0.14) 0%, rgba(255, 107, 61, 0.05) 100%);
            color: #C2410C;
            font-weight: 700;
        }

        .sidebar-nav-link.is-active .sidebar-nav-icon {
            color: #FF6B3D;
        }

        .sidebar-nav-link.is-active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            bottom: 6px;
            width: 3.5px;
            background: linear-gradient(180deg, var(--ss-amber) 0%, var(--ss-orange) 100%);
            border-radius: 4px;
        }

        .sidebar-nav-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ss-slate-light);
            transition: color 0.15s ease;
            flex-shrink: 0;
        }

        .sidebar-nav-link:hover .sidebar-nav-icon {
            color: var(--ss-teal);
        }

        .sidebar-nav-title {
            flex: 1;
        }

        .sidebar-nav-badge {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.15rem 0.55rem;
            border-radius: var(--ss-radius-pill);
            background: #F1F5F9;
            color: var(--ss-slate);
            transition: all 0.15s ease;
        }

        .sidebar-nav-link.is-active .sidebar-nav-badge {
            background: #FFEDD5;
            color: #9A3412;
        }

        .sidebar-btn-outline {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            width: 100%;
            padding: 0.65rem 0.85rem;
            border-radius: 12px;
            background: #FFFFFF;
            border: 1.5px dashed var(--ss-border);
            color: var(--ss-teal);
            font-size: 0.86rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .sidebar-btn-outline:hover {
            border-color: var(--ss-teal);
            background: rgba(15, 92, 107, 0.04);
            color: var(--ss-teal-dark);
            transform: translateY(-1px);
        }

        .sidebar-status-card {
            background: #F8FAFC;
            border: 1px solid var(--ss-border-light);
            border-radius: 14px;
            padding: 1rem;
            margin-top: 0.5rem;
        }

        .sidebar-link-public {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--ss-teal);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .sidebar-link-public:hover {
            color: var(--ss-orange);
            text-decoration: underline;
        }

        .lender-content-area {
            min-width: 0;
        }

        /* Mobile / Tablet Responsive Adjustments */
        @media (max-width: 991px) {
            .lender-layout {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .lender-sidebar {
                position: static;
                top: auto;
            }
            .sidebar-status-card {
                display: none;
            }
            .sidebar-nav-list {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            .sidebar-nav-link {
                flex: 1 1 calc(33.333% - 0.5rem);
                justify-content: center;
                text-align: center;
                padding: 0.6rem 0.5rem;
            }
            .sidebar-nav-link.is-active::before {
                display: none;
            }
        }

        @media (max-width: 1250px) {
            .kpi-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        @media (max-width: 600px) {
            .kpi-grid {
                grid-template-columns: 1fr !important;
            }
            .sidebar-nav-link {
                flex: 1 1 100%;
                justify-content: flex-start;
            }
        }

        /* Hero / Greeting Bar */
        .lender-hero {
            background: linear-gradient(135deg, #FFFFFF 0%, #F1F5F9 100%);
            border: 1px solid var(--ss-border);
            border-radius: var(--ss-radius-lg);
            padding: 2rem 2.25rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1.5rem;
            box-shadow: var(--ss-shadow-sm);
        }

        .hero-title {
            font-family: 'Sora', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--ss-dark);
            letter-spacing: -0.02em;
            margin-bottom: 0.35rem;
        }

        .hero-subtitle {
            font-size: 0.95rem;
            color: var(--ss-slate);
        }

        /* Buttons */
        .btn-solar-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--ss-amber) 0%, var(--ss-orange) 100%);
            color: #071520;
            font-weight: 700;
            font-size: 0.92rem;
            padding: 0.65rem 1.25rem;
            border-radius: var(--ss-radius-md);
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(255, 176, 32, 0.35);
            transition: all 0.2s ease;
        }

        .btn-solar-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(255, 176, 32, 0.45);
            color: #071520;
        }

        .btn-solar-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: #FFFFFF;
            color: var(--ss-teal);
            font-weight: 600;
            font-size: 0.92rem;
            padding: 0.65rem 1.2rem;
            border-radius: var(--ss-radius-md);
            text-decoration: none;
            border: 1px solid var(--ss-border);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-solar-secondary:hover {
            background: rgba(15, 92, 107, 0.05);
            border-color: var(--ss-teal);
            color: var(--ss-teal-dark);
        }

        .btn-solar-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            background: #FEE2E2;
            color: #991B1B;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.45rem 0.85rem;
            border-radius: var(--ss-radius-sm);
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .btn-solar-danger:hover {
            background: #FCA5A5;
        }

        /* Cards */
        .solar-card {
            background: var(--ss-card-bg);
            border: 1px solid var(--ss-border);
            border-radius: var(--ss-radius-md);
            box-shadow: var(--ss-shadow-sm);
            padding: 1.5rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .solar-card.hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: var(--ss-shadow-md);
        }

        /* Status & Availability Badges */
        .status-badge-pending {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FCD34D;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 0.25rem 0.65rem;
            border-radius: var(--ss-radius-pill);
            letter-spacing: 0.02em;
        }

        .badge-available {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: #DCFCE7;
            color: #166534;
            border: 1px solid #BBF7D0;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 0.25rem 0.65rem;
            border-radius: var(--ss-radius-pill);
        }

        .badge-unavailable {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FECACA;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 0.25rem 0.65rem;
            border-radius: var(--ss-radius-pill);
        }

        .category-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: #F1F5F9;
            color: var(--ss-dark);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.25rem 0.65rem;
            border-radius: var(--ss-radius-pill);
        }

        /* Tables */
        .solar-table-wrap {
            background: #FFFFFF;
            border: 1px solid var(--ss-border);
            border-radius: var(--ss-radius-md);
            overflow: hidden;
            box-shadow: var(--ss-shadow-sm);
        }

        .solar-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.92rem;
        }

        .solar-table th {
            background: #F8FAFC;
            padding: 0.95rem 1.25rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--ss-slate);
            border-bottom: 1px solid var(--ss-border);
        }

        .solar-table td {
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid var(--ss-border-light);
            vertical-align: middle;
            color: var(--ss-dark);
        }

        .solar-table tr:last-child td {
            border-bottom: none;
        }

        .solar-table tr:hover td {
            background: #FAFCFE;
        }

        /* Flash Alerts */
        .alert-box {
            padding: 1rem 1.25rem;
            border-radius: var(--ss-radius-md);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.92rem;
            animation: fadeIn 0.2s ease-out;
        }

        .alert-success {
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .alert-error {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        /* Forms */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 1.25rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .form-label {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--ss-dark);
        }

        .form-label span.req {
            color: #EF4444;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-family: inherit;
            color: var(--ss-dark);
            background: #FFFFFF;
            border: 1.5px solid var(--ss-border);
            border-radius: var(--ss-radius-md);
            transition: all 0.15s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--ss-teal);
            box-shadow: 0 0 0 3px rgba(15, 92, 107, 0.12);
        }

        .form-control.is-invalid {
            border-color: #EF4444 !important;
            background-color: #FEF2F2;
        }

        .form-control.is-invalid:focus {
            border-color: #DC2626 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2) !important;
        }

        .form-text-error {
            display: block;
            margin-top: 0.35rem;
            font-size: 0.82rem;
            color: #DC2626;
            font-weight: 600;
        }

        .form-hint {
            font-size: 0.8rem;
            color: var(--ss-slate);
        }

        /* Footer */
        .lender-footer {
            background: #FFFFFF;
            border-top: 1px solid var(--ss-border);
            padding: 1.75rem 0;
            color: var(--ss-slate);
            font-size: 0.85rem;
            text-align: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .lender-hero {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Unified Landing Page Header (Exact same header across landing & lender spaces) -->
    @include('partials.solartec.navbar')

    <!-- Main Container -->
    <main class="lender-main">
        <div class="lender-container">
            <div class="lender-layout">
                <!-- Lender Sidebar with that Add Equipment button & navigation -->
                <aside class="lender-sidebar" aria-label="Menu latéral Espace Prêteur">
                    @include('lender.partials.sidebar')
                </aside>

                <!-- Lender Content Area -->
                <div class="lender-content-area">
                    <!-- Flash Notifications -->
                    @if(session('success'))
                        <div class="alert-box alert-success" role="alert">
                            <div style="display:flex; align-items:center; gap:0.6rem;">
                                <span style="font-size:1.1rem;">✅</span>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; font-size:1.1rem; color:#065F46;">&times;</button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert-box alert-error" role="alert">
                            <div style="display:flex; align-items:center; gap:0.6rem;">
                                <span style="font-size:1.1rem;">⚠️</span>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; font-size:1.1rem; color:#991B1B;">&times;</button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert-box alert-error" role="alert">
                            <div>
                                <strong style="display:block; margin-bottom:0.3rem;">Veuillez corriger les erreurs ci-dessous :</strong>
                                <ul style="margin-left:1.2rem; font-size:0.88rem;">
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Page Content -->
                    @yield('content')
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Partial -->
    @include('lender.partials.footer')

    @stack('scripts')
</body>
</html>
