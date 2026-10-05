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

        <!-- Controls Bar -->
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
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
                    {{ $equipment->total() }} équipement(s) répertorié(s)
                </span>
            </div>

            <!-- Sort & Availability Form -->
            <form method="GET" action="{{ route('marketplace') }}" style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <select name="sort" class="form-select form-select-sm" style="width: auto; border-radius: 10px; font-size: 0.88rem; font-weight: 600; padding: 0.45rem 2rem 0.45rem 0.85rem;" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Plus récents</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix : Moins cher</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix : Plus cher</option>
                </select>

                @if(request('search') || request('category') || request('sort'))
                    <a href="{{ route('marketplace') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size: 0.82rem;">
                        Réinitialiser
                    </a>
                @endif
            </form>
        </div>

        <!-- Equipment Cards Grid -->
        @if($equipment->count() > 0)
            <div class="row g-4">
                @foreach($equipment as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden equipment-card" style="transition: transform 0.2s ease, box-shadow 0.2s ease; background: #FFFFFF;">
                            
                            <!-- Image Container -->
                            <div style="height: 220px; position: relative; overflow: hidden; background: #0F172A;">
                                @if($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0F5C6B, #083E49); color: #FFB020; font-size: 3rem;">
                                        ☀️
                                    </div>
                                @endif

                                <!-- Category Badge -->
                                <span style="position: absolute; top: 12px; left: 12px; background: rgba(16, 32, 43, 0.85); backdrop-filter: blur(8px); color: #FFFFFF; font-size: 0.74rem; font-weight: 700; padding: 0.3rem 0.75rem; border-radius: 9999px;">
                                    {{ $item->category?->name ?? 'Énergie' }}
                                </span>

                                <!-- Availability Badge -->
                                <span style="position: absolute; top: 12px; right: 12px; background: {{ $item->is_available ? '#DCFCE7' : '#FEE2E2' }}; color: {{ $item->is_available ? '#166534' : '#991B1B' }}; font-size: 0.74rem; font-weight: 700; padding: 0.3rem 0.65rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.3rem;">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $item->is_available ? '#16A34A' : '#EF4444' }};"></span>
                                    {{ $item->is_available ? 'Disponible' : 'Réservé' }}
                                </span>
                            </div>

                            <!-- Card Body -->
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Brand -->
                                    <div style="font-size: 0.78rem; font-weight: 700; color: #0F5C6B; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.3rem;">
                                        {{ $item->brand ?? 'Matériel certifié' }}
                                    </div>

                                    <!-- Name -->
                                    <h5 class="card-title fw-bold text-dark mb-2" style="font-size: 1.15rem; line-height: 1.35;">
                                        {{ $item->name }}
                                    </h5>

                                    <!-- Description snippet -->
                                    <p class="text-muted small mb-3" style="line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $item->description ?: 'Aucune description spécifique fournie pour cet équipement.' }}
                                    </p>

                                    <!-- Specs Pills -->
                                    <div style="display: flex; gap: 0.4rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
                                        @if($item->power_watts)
                                            <span style="display: inline-flex; align-items: center; gap: 0.3rem; background: #F1F5F9; color: #1E293B; font-size: 0.76rem; font-weight: 700; padding: 0.25rem 0.55rem; border-radius: 8px;">
                                                ⚡ {{ $item->power_watts }} W
                                            </span>
                                        @endif
                                        @if($item->capacity_wh)
                                            <span style="display: inline-flex; align-items: center; gap: 0.3rem; background: #F1F5F9; color: #1E293B; font-size: 0.76rem; font-weight: 700; padding: 0.25rem 0.55rem; border-radius: 8px;">
                                                🔋 {{ $item->capacity_wh }} Wh
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Pricing & Action -->
                                <div style="border-top: 1px solid #F1F5F9; padding-top: 1rem; display: flex; align-items: center; justify-content: space-between;">
                                    <div>
                                        <div style="display: flex; align-items: baseline; gap: 0.25rem;">
                                            <span style="font-size: 1.35rem; font-weight: 800; color: #10202B;">
                                                {{ number_format($item->daily_rate, 2) }}
                                            </span>
                                            <span style="font-size: 0.8rem; color: #64748B; font-weight: 600;">TND / jour</span>
                                        </div>
                                        <span style="display: block; font-size: 0.75rem; color: #94A3B8;">
                                            Caution : {{ number_format($item->deposit_amount, 2) }} TND
                                        </span>
                                    </div>

                                    <a href="{{ route('marketplace.show', $item) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2" style="font-weight: 700; font-size: 0.85rem; border-color: #0F5C6B; color: #0F5C6B;">
                                        Détails &rarr;
                                    </a>
                                </div>

                            </div>

                            <!-- Card Footer: Owner Info -->
                            <div style="background: #FAFCFE; border-top: 1px solid #F1F5F9; padding: 0.65rem 1.25rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; color: #64748B;">
                                <div style="display: flex; align-items: center; gap: 0.45rem;">
                                    <span style="width: 22px; height: 22px; border-radius: 50%; background: linear-gradient(135deg, #FFB020, #FF6B3D); color: #FFFFFF; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700;">
                                        {{ strtoupper(substr($item->user?->name ?? 'P', 0, 1)) }}
                                    </span>
                                    <span>{{ $item->user?->name ?? 'Prêteur partenaire' }}</span>
                                </div>
                                <span style="font-size: 0.72rem; color: #94A3B8;">
                                    {{ $item->created_at?->diffForHumans() }}
                                </span>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $equipment->links() }}
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
</style>
@endpush
