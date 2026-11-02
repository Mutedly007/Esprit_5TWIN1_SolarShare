{{-- Topbar Header Partial - SolarShare Admin (Faithful to FreeDash original) --}}
<header class="topbar" data-navbarbg="skin6">
    <nav class="navbar top-navbar navbar-expand-lg">
        <div class="navbar-header" data-logobg="skin6">
            <!-- Sidebar toggle on mobile -->
            <a class="nav-toggler waves-effect waves-light d-block d-lg-none" href="javascript:void(0)">
                <i class="ti-menu ti-close"></i>
            </a>

            <!-- Logo -->
            <div class="navbar-brand">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none">
                    <!-- FreeDash styled leaf/sun modern logo -->
                    <svg width="30" height="30" viewBox="0 0 36 36" fill="none" class="me-2">
                        <path d="M6 18C6 11.3726 11.3726 6 18 6C24.6274 6 30 11.3726 30 18C30 24.6274 24.6274 30 18 30C11.3726 30 6 24.6274 6 18Z" fill="url(#brandGrad)" />
                        <path d="M12 18C12 14.6863 14.6863 12 18 12C21.3137 12 24 14.6863 24 18C24 21.3137 21.3137 24 18 24C14.6863 24 12 21.3137 12 18Z" fill="#ffffff" opacity="0.9" />
                        <circle cx="18" cy="18" r="3.5" fill="#5f76e8" />
                        <defs>
                            <linearGradient id="brandGrad" x1="6" y1="6" x2="30" y2="30" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#5f76e8" />
                                <stop offset="1" stop-color="#01caf1" />
                            </linearGradient>
                        </defs>
                    </svg>
                    <span class="fs-4 fw-bold text-dark" style="letter-spacing: -0.02em;">Dash<span style="color: #5f76e8;">board</span></span>
                </a>
            </div>

            <!-- Mobile toggle -->
            <a class="topbartoggler d-block d-lg-none waves-effect waves-light" href="javascript:void(0)"
                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="ti-more"></i>
            </a>
        </div>

        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <!-- Left Header Items (Notification, Settings, Language - exactly as in FreeDash mockup) -->
            <ul class="navbar-nav float-left me-auto ms-3 ps-1 align-items-center">
                <!-- Notifications -->
                <li class="nav-item me-2">
                    <a class="nav-link text-muted px-2 d-flex align-items-center" href="javascript:void(0)" title="Notifications">
                        <div style="position: relative; display: inline-flex; align-items: center; justify-content: center;">
                            <i data-feather="bell" class="feather-sm"></i>
                            <span class="badge rounded-pill bg-primary" style="position: absolute; top: -6px; right: -8px; font-size: 0.62rem; padding: 0.18em 0.42em; background-color: #5f76e8 !important; line-height: 1;">
                                5
                            </span>
                        </div>
                    </a>
                </li>

                <!-- Settings -->
                <li class="nav-item me-2">
                    <a class="nav-link text-muted px-2" href="javascript:void(0)" title="Paramètres">
                        <i data-feather="settings" class="feather-sm"></i>
                    </a>
                </li>

                <!-- Language Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted small px-2 d-flex align-items-center" href="javascript:void(0)" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="fw-bold">FR</span>
                        <i data-feather="chevron-down" class="feather-xs ms-1"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-animate-up py-2 shadow-sm border-0">
                        <a class="dropdown-item small" href="javascript:void(0)">FR — Français</a>
                        <a class="dropdown-item small" href="javascript:void(0)">EN — English</a>
                    </div>
                </li>
            </ul>

            <!-- Right Header Items (Search & User Profile) -->
            <ul class="navbar-nav float-end align-items-center">
                <!-- Search Box matching mockup -->
                <li class="nav-item d-none d-md-block me-3">
                    <div class="position-relative">
                        <input class="form-control bg-white border-0 shadow-none" type="search" placeholder="Search..." aria-label="Search" style="background: #ffffff !important; border-radius: 20px; box-shadow: 0 0 25px 0 rgba(154, 161, 171, 0.18) !important; padding: 0.45rem 2.4rem 0.45rem 1.1rem; font-size: 0.88rem; width: 220px; height: 38px;">
                        <i data-feather="search" class="svg-icon" style="position: absolute; right: 12px; top: 11px; color: #a1aab2; width: 16px; height: 16px;"></i>
                    </div>
                </li>

                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-decoration-none" href="javascript:void(0)" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <!-- Rounded Avatar with initial / gradient -->
                        <span class="rounded-circle text-white d-inline-flex align-items-center justify-content-center me-2 shadow-sm overflow-hidden" style="width: 36px; height: 36px; background: linear-gradient(135deg, #5f76e8, #7b8ff5); font-weight: 700; font-size: 0.85rem;">
                            @if(auth()->user()->hasAvatar())
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </span>
                        <span class="d-none d-lg-inline-block text-dark font-14">
                            Hello, <strong class="text-dark">{{ auth()->user()->name }}</strong>
                            <i data-feather="chevron-down" class="svg-icon font-12 ms-1"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up py-2 shadow-sm border-0" style="min-width: 210px; border-radius: 12px;">
                        <div class="px-3 py-2 border-bottom mb-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <h6 class="mb-0 text-dark fw-bold">{{ auth()->user()->name }}</h6>
                                <span class="badge rounded-pill text-white ms-2" style="font-size: 0.68rem; background-color: #ff4f70; padding: 0.2em 0.55em;">Admin</span>
                            </div>
                            <small class="text-muted d-block">{{ auth()->user()->email }}</small>
                        </div>
                        <a class="dropdown-item py-2 font-14 text-dark fw-semibold" href="{{ route('profile.edit') }}">
                            <i data-feather="user" class="svg-icon me-2 ms-1"></i> Mon Profil
                        </a>
                        <a class="dropdown-item py-2 font-14 text-primary" href="{{ route('home') }}">
                            <i data-feather="external-link" class="svg-icon me-2 ms-1"></i> Voir le site public
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 font-14 text-danger">
                                <i data-feather="power" class="svg-icon me-2 ms-1"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
