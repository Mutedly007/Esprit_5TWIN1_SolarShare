{{-- Left Sidebar Partial - SolarShare Admin (Faithful to FreeDash original) --}}
<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" data-sidebarbg="skin6">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav pt-3">
            <ul id="sidebarnav">
                <!-- Main Dashboard link matching FreeDash pill style -->
                <li class="sidebar-item my-1">
                    <a class="sidebar-link active text-white d-flex align-items-center" href="{{ route('admin.dashboard') }}" 
                        style="background: linear-gradient(135deg, #5f76e8, #7b8ff5) !important; border-radius: 8px !important; margin: 0 12px; padding: 10px 16px; box-shadow: 0 4px 15px rgba(95, 118, 232, 0.35);" 
                        aria-expanded="false">
                        <i data-feather="home" class="feather-icon text-white me-2" style="width: 18px; height: 18px;"></i>
                        <span class="hide-menu fw-bold">Dashboard</span>
                    </a>
                </li>

                <!-- Section: APPLICATIONS (Modules Métier) -->
                <li class="nav-small-cap pt-3 px-3">
                    <span class="hide-menu text-uppercase text-muted fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Applications</span>
                </li>

                <!-- Module 1: Équipements -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="#equipments" aria-expanded="false">
                        <i data-feather="sun" class="feather-icon text-muted"></i>
                        <span class="hide-menu">Équipements</span>
                    </a>
                </li>

                <!-- Module 2: Locations & Cycle de Location -->
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.rentals.*') ? 'active' : '' }}" href="{{ route('admin.rentals.index') }}" aria-expanded="false">
                        <i data-feather="clipboard" class="feather-icon text-muted"></i>
                        <span class="hide-menu">Locations & États</span>
                    </a>
                </li>

                <!-- Module 3: Packs Solaires (Kits Combinés N-N) -->
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('admin.energy-kits.*') ? 'active' : '' }}" href="{{ route('admin.energy-kits.index') }}" aria-expanded="false">
                        <i data-feather="package" class="feather-icon text-muted"></i>
                        <span class="hide-menu">Packs Solaires</span>
                    </a>
                </li>

                <!-- Section: COMPONENTS -->
                <li class="nav-small-cap pt-3 px-3">
                    <span class="hide-menu text-uppercase text-muted fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Components</span>
                </li>

                <!-- Module 4: Hubs Relais -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="#hubs" aria-expanded="false">
                        <i data-feather="map-pin" class="feather-icon text-muted"></i>
                        <span class="hide-menu">Hubs Relais</span>
                    </a>
                </li>

                <!-- Administration: Utilisateurs -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="#users" aria-expanded="false">
                        <i data-feather="users" class="feather-icon text-muted"></i>
                        <span class="hide-menu">Utilisateurs</span>
                    </a>
                </li>

                <!-- Section: AUTHENTICATION -->
                <li class="nav-small-cap pt-3 px-3">
                    <span class="hide-menu text-uppercase text-muted fw-bold" style="font-size: 0.68rem; letter-spacing: 0.08em;">Authentication</span>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('home') }}" aria-expanded="false">
                        <i data-feather="globe" class="feather-icon text-muted"></i>
                        <span class="hide-menu">Site public</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <form method="POST" action="{{ route('logout') }}" id="sidebar-logout-form">
                        @csrf
                        <a class="sidebar-link text-danger" href="javascript:void(0)" onclick="document.getElementById('sidebar-logout-form').submit();" aria-expanded="false">
                            <i data-feather="log-out" class="feather-icon text-danger"></i>
                            <span class="hide-menu text-danger">Déconnexion</span>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
