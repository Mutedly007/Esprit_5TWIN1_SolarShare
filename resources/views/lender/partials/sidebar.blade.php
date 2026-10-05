{{-- resources/views/lender/partials/sidebar.blade.php --}}
<div class="lender-sidebar-inner">



    <!-- Navigation Section -->
    <div class="sidebar-nav-group">
        <span class="sidebar-nav-heading">Menu Principal</span>
        <nav class="sidebar-nav-list" aria-label="Navigation Espace Prêteur">
            
            <!-- Dashboard Link -->
            <a href="{{ route('lender.dashboard') }}"
               class="sidebar-nav-link {{ request()->routeIs('lender.dashboard') ? 'is-active' : '' }}"
               id="sidebar-link-dashboard">
                <span class="sidebar-nav-icon">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="9"></rect>
                        <rect x="14" y="3" width="7" height="5"></rect>
                        <rect x="14" y="12" width="7" height="9"></rect>
                        <rect x="3" y="16" width="7" height="5"></rect>
                    </svg>
                </span>
                <span class="sidebar-nav-title">Dashboard</span>
            </a>

            <!-- Equipment Link -->
            <a href="{{ route('equipment.index') }}"
               class="sidebar-nav-link {{ (request()->routeIs('equipment.*') && !request()->routeIs('equipment.create')) ? 'is-active' : '' }}"
               id="sidebar-link-equipment">
                <span class="sidebar-nav-icon">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                </span>
                <span class="sidebar-nav-title">Mes Équipements</span>
                <span class="sidebar-nav-badge" title="Nombre total de vos équipements">
                    {{ auth()->user()->equipment()->count() }}
                </span>
            </a>

            <!-- Categories Link -->
            <a href="{{ route('categories.index') }}"
               class="sidebar-nav-link {{ (request()->routeIs('categories.*') && !request()->routeIs('categories.create')) ? 'is-active' : '' }}"
               id="sidebar-link-categories">
                <span class="sidebar-nav-icon">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                    </svg>
                </span>
                <span class="sidebar-nav-title">Catégories</span>
                <span class="sidebar-nav-badge" title="Total des catégories disponibles">
                    {{ \App\Models\Category::count() }}
                </span>
            </a>

        </nav>
    </div>

    <!-- Quick Action / Add Category -->
    <div class="sidebar-nav-group">
        <span class="sidebar-nav-heading">Actions Rapides</span>
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <a href="{{ route('categories.create') }}" class="sidebar-btn-outline" id="sidebar-btn-add-category">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Add Category</span>
            </a>
        </div>
    </div>

    <!-- Platform Status -->
    <div class="sidebar-status-card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem;">
            <span style="font-size: 0.72rem; font-weight: 700; color: var(--ss-slate-light); text-transform: uppercase; letter-spacing: 0.06em;">Statut Prêt</span>
            <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.76rem; color: #16A34A; font-weight: 700;">
                <span class="sidebar-pulse-dot" style="background: #16A34A; box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.2);"></span>
                Actif
            </span>
        </div>
        <p style="font-size: 0.79rem; color: var(--ss-slate); line-height: 1.4; margin: 0;">
            Vos équipements enregistrés restent en <strong>Non traité</strong> jusqu'à validation.
        </p>
    </div>

</div>
