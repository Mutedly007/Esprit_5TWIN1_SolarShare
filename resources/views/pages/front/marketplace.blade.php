{{-- resources/views/pages/front/marketplace.blade.php --}}
@extends('layouts.solartec')

@section('title', 'Marketplace d\'Équipements — SolarShare')
@section('meta_description', 'Découvrez et louez des panneaux solaires, batteries de stockage et micro-éoliennes partagés par la communauté SolarShare.')

@section('content')

<!-- ==================== MARKETPLACE HERO & SEARCH ==================== -->
<section style="background: linear-gradient(135deg, #083E49 0%, #0F5C6B 60%, #10202B 100%); color: #FFFFFF; padding: 4rem 0 3.5rem 0; position: relative; overflow: hidden;">
    <!-- Decorative Sun Ray Glow -->
    <div style="position: absolute; right: -50px; top: -50px; width: 350px; height: 350px; background: radial-gradient(circle, rgba(255, 176, 32, 0.25) 0%, rgba(255, 107, 61, 0) 70%); border-radius: 50%; pointer-events: none;"></div>

    <div class="container" style="position: relative; z-index: 2;">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge badge-solar mb-3 text-uppercase px-3 py-2" style="background: #FFB020; color: #10202B; font-weight: 700; border-radius: 9999px;">
                    ☀️ Marketplace Énergies Renouvelables
                </span>
                <h1 class="display-5 text-white fw-bold mb-3" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                    Louez du Matériel Solaire & Écologique
                </h1>
                <p class="fs-5 text-white-50 mb-4" style="max-width: 680px; margin: 0 auto;">
                    Parcourez les équipements d'énergie renouvelable mis à disposition par la communauté SolarShare pour vos voyages, travaux ou urgences.
                </p>

                <!-- Search Form -->
                <form method="GET" action="{{ route('marketplace') }}" style="max-width: 640px; margin: 0 auto;">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div style="display: flex; gap: 0.5rem; background: #FFFFFF; padding: 0.45rem; border-radius: 9999px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);">
                        <div style="display: flex; align-items: center; flex: 1; padding-left: 1rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Rechercher par nom, marque (EcoFlow, Bluetti, Renogy...)..."
                                style="width: 100%; border: none; outline: none; padding: 0.6rem 0.8rem; font-size: 0.95rem; color: #10202B; background: transparent;"
                            >
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; font-weight: 700; color: #071520;">
                            Rechercher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CATEGORIES TABS ==================== -->
<section style="background: #FFFFFF; border-bottom: 1px solid rgba(16, 32, 43, 0.08); padding: 1.25rem 0;">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 0.75rem; overflow-x: auto; padding-bottom: 0.25rem; scrollbar-width: none;">
            
            <!-- All Categories -->
            <a href="{{ route('marketplace', array_merge(request()->except(['category', 'page']))) }}"
               class="badge-category-filter {{ !request('category') ? 'is-active' : '' }}">
                <span>Tous les matériels</span>
                <span class="count-pill">{{ $totalCount }}</span>
            </a>

            <!-- Dynamic Categories -->
            @foreach($categories as $cat)
                <a href="{{ route('marketplace', array_merge(request()->except(['page']), ['category' => $cat->slug])) }}"
                   class="badge-category-filter {{ request('category') == $cat->slug ? 'is-active' : '' }}">
                    <span>{{ $cat->name }}</span>
                    <span class="count-pill">{{ $cat->equipment_count }}</span>
                </a>
            @endforeach

        </div>
    </div>
</section>

<!-- ==================== MARKETPLACE CONTENT & GRID ==================== -->
<section style="background: #F8FAFC; padding: 3rem 0 5rem 0; min-height: 55vh;">
    <div class="container">

        <!-- Advanced Filter & Controls Card -->
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 bg-white mb-4">
            <form method="GET" action="{{ route('marketplace') }}" id="marketplace-filter-form">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <div class="row g-2 align-items-center">
                    <!-- Search Input -->
                    <div class="col-12 col-md-5 col-lg-5">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   class="form-control bg-light border-start-0 py-2" 
                                   placeholder="Rechercher par nom, marque, description..." 
                                   value="{{ request('search') }}"
                                   aria-label="Recherche">
                            @if(request('search'))
                                <a href="{{ route('marketplace', request()->except('search', 'page')) }}" 
                                   class="input-group-text bg-light border-start-0 text-muted" 
                                   title="Effacer la recherche">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Sorting Dropdown -->
                    <div class="col-6 col-md-3 col-lg-3">
                        <select name="sort" class="form-select bg-light rounded-pill py-2" onchange="this.form.submit()">
                            <option value="latest" {{ ($sort ?? 'latest') === 'latest' ? 'selected' : '' }}>✨ Plus récents</option>
                            <option value="price_asc" {{ ($sort ?? '') === 'price_asc' ? 'selected' : '' }}>💶 Prix : Moins cher</option>
                            <option value="price_desc" {{ ($sort ?? '') === 'price_desc' ? 'selected' : '' }}>💶 Prix : Plus cher</option>
                            <option value="power_desc" {{ ($sort ?? '') === 'power_desc' ? 'selected' : '' }}>⚡ Puissance max (Watts)</option>
                            <option value="capacity_desc" {{ ($sort ?? '') === 'capacity_desc' ? 'selected' : '' }}>🔋 Capacité max (Wh)</option>
                        </select>
                    </div>

                    <!-- Buttons: Toggle Advanced Filters + Submit -->
                    <div class="col-6 col-md-4 col-lg-4 d-flex gap-2">
                        <button type="button" 
                                class="btn {{ $activeFiltersCount > 0 ? 'btn-outline-primary border-primary' : 'btn-outline-secondary' }} rounded-pill px-3 py-2 flex-grow-1 d-flex align-items-center justify-content-center gap-2" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#marketplaceFiltersCollapse" 
                                aria-expanded="{{ $activeFiltersCount > 0 ? 'true' : 'false' }}" 
                                aria-controls="marketplaceFiltersCollapse">
                            <i class="bi bi-sliders2"></i>
                            <span>Filtres avancés</span>
                            @if($activeFiltersCount > 0)
                                <span class="badge rounded-pill bg-primary text-white" style="font-size: 0.72rem;">{{ $activeFiltersCount }}</span>
                            @endif
                        </button>

                        <button type="submit" class="btn btn-primary rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-1" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520;">
                            <i class="bi bi-funnel-fill"></i>
                            <span class="d-none d-sm-inline">Filtrer</span>
                        </button>
                    </div>
                </div>

                <!-- Collapsible Advanced Filters Section -->
                <div class="collapse {{ $activeFiltersCount > 0 ? 'show' : '' }} mt-3 pt-3 border-top" id="marketplaceFiltersCollapse">
                    <div class="row g-3">
                        <!-- Brand Select -->
                        <div class="col-12 col-md-3">
                            <label class="form-label small fw-bold text-dark mb-1">
                                <i class="bi bi-tag-fill me-1 text-primary"></i>Marque du matériel
                            </label>
                            <select name="brand" class="form-select form-select-sm rounded-3">
                                <option value="">Toutes les marques</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b }}" {{ request('brand') === $b ? 'selected' : '' }}>
                                        {{ $b }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="col-12 col-md-3">
                            <label class="form-label small fw-bold text-dark mb-1">
                                <i class="bi bi-cash-stack me-1 text-success"></i>Tarif journalier (TND / j)
                            </label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.5" min="0" name="min_price" class="form-control rounded-start-3" placeholder="Min" value="{{ request('min_price') }}">
                                <span class="input-group-text bg-light text-muted">à</span>
                                <input type="number" step="0.5" min="0" name="max_price" class="form-control rounded-end-3" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <!-- Minimum Power -->
                        <div class="col-12 col-md-2">
                            <label class="form-label small fw-bold text-dark mb-1">
                                <i class="bi bi-lightning-charge-fill me-1 text-warning"></i>Puissance min.
                            </label>
                            <select name="min_power" class="form-select form-select-sm rounded-3">
                                <option value="">Indifférent</option>
                                <option value="100" {{ (int)request('min_power') === 100 ? 'selected' : '' }}>&ge; 100 W</option>
                                <option value="200" {{ (int)request('min_power') === 200 ? 'selected' : '' }}>&ge; 200 W</option>
                                <option value="400" {{ (int)request('min_power') === 400 ? 'selected' : '' }}>&ge; 400 W</option>
                                <option value="800" {{ (int)request('min_power') === 800 ? 'selected' : '' }}>&ge; 800 W</option>
                            </select>
                        </div>

                        <!-- Minimum Capacity -->
                        <div class="col-12 col-md-2">
                            <label class="form-label small fw-bold text-dark mb-1">
                                <i class="bi bi-battery-charging me-1 text-info"></i>Capacité min.
                            </label>
                            <select name="min_capacity" class="form-select form-select-sm rounded-3">
                                <option value="">Indifférent</option>
                                <option value="250" {{ (int)request('min_capacity') === 250 ? 'selected' : '' }}>&ge; 250 Wh</option>
                                <option value="500" {{ (int)request('min_capacity') === 500 ? 'selected' : '' }}>&ge; 500 Wh</option>
                                <option value="1000" {{ (int)request('min_capacity') === 1000 ? 'selected' : '' }}>&ge; 1000 Wh</option>
                                <option value="2000" {{ (int)request('min_capacity') === 2000 ? 'selected' : '' }}>&ge; 2000 Wh</option>
                            </select>
                        </div>

                        <!-- Availability & Max Deposit -->
                        <div class="col-12 col-md-2 d-flex flex-column justify-content-end gap-1">
                            <div class="form-check form-switch mb-1">
                                <input class="form-check-input" type="checkbox" name="available" id="filter-marketplace-available" value="1" {{ request('available') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label small fw-semibold text-dark" for="filter-marketplace-available">
                                    ⚡ Disponible de suite
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Action Bar inside Collapse -->
                    <div class="d-flex justify-content-between align-items-center gap-2 mt-3 pt-2 border-top">
                        <div>
                            @if($activeFiltersCount > 0)
                                <a href="{{ route('marketplace', request('category') ? ['category' => request('category')] : []) }}" 
                                   class="btn btn-sm btn-link text-muted text-decoration-none small p-0">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Réinitialiser les filtres
                                </a>
                            @endif
                        </div>
                        <div>
                            <button type="submit" class="btn btn-sm btn-dark rounded-pill px-4 py-1 fw-semibold">
                                Appliquer les filtres
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Active Filter Pills & Results Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h2 style="font-size: 1.35rem; font-weight: 800; color: #10202B; margin-bottom: 0.2rem;">
                    @if($selectedCategory)
                        {{ $selectedCategory->name }}
                    @elseif(request('search'))
                        Résultats pour &laquo; {{ request('search') }} &raquo;
                    @else
                        Catalogue d'Équipements Disponibles
                    @endif
                </h2>
                <span style="font-size: 0.88rem; color: #64748B;">
                    <strong>{{ $equipment->total() }}</strong> équipement(s) répertorié(s)
                </span>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                @if(request('search'))
                    <a href="{{ route('marketplace', request()->except('search', 'page')) }}" class="badge rounded-pill bg-white text-dark border px-3 py-2 text-decoration-none small shadow-xs">
                        Recherche : "{{ Str::limit(request('search'), 20) }}" <i class="bi bi-x ms-1"></i>
                    </a>
                @endif

                @if(request('brand'))
                    <a href="{{ route('marketplace', request()->except('brand', 'page')) }}" class="badge rounded-pill bg-white text-dark border px-3 py-2 text-decoration-none small shadow-xs">
                        Marque : {{ request('brand') }} <i class="bi bi-x ms-1"></i>
                    </a>
                @endif

                @if(request('min_price') || request('max_price'))
                    <a href="{{ route('marketplace', request()->except('min_price', 'max_price', 'page')) }}" class="badge rounded-pill bg-white text-dark border px-3 py-2 text-decoration-none small shadow-xs">
                        Prix : {{ request('min_price') ?? 0 }} à {{ request('max_price') ? request('max_price') . ' TND' : 'illimité' }} <i class="bi bi-x ms-1"></i>
                    </a>
                @endif

                @if(request('min_power'))
                    <a href="{{ route('marketplace', request()->except('min_power', 'page')) }}" class="badge rounded-pill bg-white text-dark border px-3 py-2 text-decoration-none small shadow-xs">
                        Puissance &ge; {{ request('min_power') }} W <i class="bi bi-x ms-1"></i>
                    </a>
                @endif

                @if(request('min_capacity'))
                    <a href="{{ route('marketplace', request()->except('min_capacity', 'page')) }}" class="badge rounded-pill bg-white text-dark border px-3 py-2 text-decoration-none small shadow-xs">
                        Capacité &ge; {{ request('min_capacity') }} Wh <i class="bi bi-x ms-1"></i>
                    </a>
                @endif

                @if(request('available') == '1')
                    <a href="{{ route('marketplace', request()->except('available', 'page')) }}" class="badge rounded-pill bg-white text-dark border px-3 py-2 text-decoration-none small shadow-xs">
                        Disponible de suite <i class="bi bi-x ms-1"></i>
                    </a>
                @endif

                @if($activeFiltersCount > 0)
                    <a href="{{ route('marketplace', request('category') ? ['category' => request('category')] : []) }}" class="btn btn-sm btn-link text-danger text-decoration-none p-0 ms-1 small fw-semibold">
                        Tout effacer
                    </a>
                @endif
            </div>
        </div>

        <!-- Equipment Cards Grid -->
        @if($equipment->count() > 0)
            <div class="row g-4">
                @foreach($equipment as $item)
                    <div class="col-md-6 col-lg-4">
                        <x-equipment-card :equipment="$item" />
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $equipment->links('partials.pagination') }}
            </div>

        @else
            <!-- Empty State -->
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4" style="background: #FFFFFF;">
                <div style="font-size: 3.5rem; margin-bottom: 1rem;">☀️</div>
                <h3 class="fw-bold mb-2">Aucun équipement ne correspond à votre recherche</h3>
                <p class="text-muted" style="max-width: 480px; margin: 0 auto 1.5rem auto;">
                    Essayez de réinitialiser vos critères de recherche ou explorez une autre catégorie d'énergie propre.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('marketplace') }}" class="btn btn-outline-primary rounded-pill px-4">
                        Voir tout le catalogue
                    </a>
                    @auth
                        <a href="{{ route('equipment.create') }}" class="btn btn-primary rounded-pill px-4">
                            + Déposer un équipement
                        </a>
                    @endauth
                </div>
            </div>
        @endif

    </div>
</section>

@endsection

@push('styles')
<style>
    .badge-category-filter {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #F1F5F9;
        color: #1E293B;
        font-size: 0.88rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        text-decoration: none;
        white-space: nowrap;
        border: 1.5px solid transparent;
        transition: all 0.2s ease;
    }
    .badge-category-filter:hover {
        background: #E2E8F0;
        color: #0F5C6B;
    }
    .badge-category-filter.is-active {
        background: #0F5C6B;
        color: #FFFFFF;
        border-color: #0F5C6B;
        box-shadow: 0 4px 12px rgba(15, 92, 107, 0.25);
    }
    .badge-category-filter .count-pill {
        background: rgba(0, 0, 0, 0.08);
        font-size: 0.74rem;
        font-weight: 700;
        padding: 0.15rem 0.45rem;
        border-radius: 9999px;
    }
    .badge-category-filter.is-active .count-pill {
        background: rgba(255, 255, 255, 0.25);
        color: #FFFFFF;
    }
    .equipment-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px -6px rgba(16, 32, 43, 0.12) !important;
    }
    .equipment-card:hover img {
        transform: scale(1.05);
    }

    /* ==================== CUSTOM SOLARSHARE PAGINATION ==================== */
    nav[role="navigation"] svg {
        max-width: 18px !important;
        max-height: 18px !important;
    }
    .ss-pagination-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.25rem;
        margin-top: 3.5rem;
        margin-bottom: 1rem;
    }
    .ss-pagination-info {
        font-size: 0.88rem;
        color: #64748B;
        font-weight: 500;
    }
    .ss-pagination-info span {
        font-weight: 700;
        color: #10202B;
    }
    .ss-pagination-list {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        list-style: none;
        padding: 0;
        margin: 0;
        flex-wrap: wrap;
        justify-content: center;
    }
    .ss-page-item {
        display: inline-flex;
    }
    .ss-page-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1.15rem;
        border-radius: 9999px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        color: #10202B;
        font-size: 0.88rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 1px 3px rgba(16, 32, 43, 0.05);
    }
    .ss-page-btn svg {
        width: 16px !important;
        height: 16px !important;
        max-width: 16px !important;
        max-height: 16px !important;
        flex-shrink: 0;
    }
    .ss-page-item:not(.is-disabled) .ss-page-btn:hover {
        background: #0F5C6B;
        border-color: #0F5C6B;
        color: #FFFFFF;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px -2px rgba(15, 92, 107, 0.3);
    }
    .ss-page-item.is-disabled .ss-page-btn {
        background: #F8FAFC;
        border-color: #E2E8F0;
        color: #94A3B8;
        cursor: not-allowed;
        opacity: 0.65;
    }
    .ss-page-link {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        color: #10202B;
        font-size: 0.92rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 1px 3px rgba(16, 32, 43, 0.04);
    }
    .ss-page-item:not(.is-active) .ss-page-link:hover {
        background: #F1F5F9;
        border-color: #CBD5E1;
        color: #0F5C6B;
        transform: translateY(-2px);
    }
    .ss-page-item.is-active .ss-page-link {
        background: linear-gradient(135deg, #0F5C6B, #083E49);
        border-color: #0F5C6B;
        color: #FFFFFF;
        box-shadow: 0 6px 18px -2px rgba(15, 92, 107, 0.35);
    }
    .ss-page-dots {
        width: 36px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #94A3B8;
        font-weight: 800;
        letter-spacing: 0.1em;
    }
</style>
@endpush
