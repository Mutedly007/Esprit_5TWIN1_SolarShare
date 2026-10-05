{{-- resources/views/lender/dashboard.blade.php --}}
@extends('layouts.lender')

@section('title', 'Tableau de bord Prêteur — SolarShare')

@section('content')
<!-- Hero Welcome Section -->
<section class="lender-hero">
    <div>
        <div style="display: inline-flex; align-items: center; gap: 0.4rem; background: rgba(255, 176, 32, 0.15); color: #B45309; padding: 0.3rem 0.75rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; margin-bottom: 0.6rem;">
            <span>☀️</span>
            <span>Espace Partage d'Énergie Solaire</span>
        </div>
        <h1 class="hero-title">Bonjour, {{ auth()->user()->name }} 👋</h1>
        <p class="hero-subtitle">
            Gérez et partagez vos équipements d'énergie renouvelable avec la communauté SolarShare.
        </p>
    </div>
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <a href="{{ route('equipment.create') }}" class="btn-solar-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Equipment
        </a>
        <a href="{{ route('categories.create') }}" class="btn-solar-secondary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"></path>
            </svg>
            Add Category
        </a>
    </div>
</section>

<!-- KPI Metrics Grid -->
<div class="kpi-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 2.25rem;">
    <!-- Total Equipment -->
    <div class="solar-card hover-lift" style="display: flex; flex-direction: column; justify-content: space-between; padding: 1.35rem 1.4rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
            <span style="font-size: 0.78rem; font-weight: 700; color: var(--ss-slate); text-transform: uppercase; letter-spacing: 0.05em;">
                Mes Équipements
            </span>
            <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(15, 92, 107, 0.1); color: var(--ss-teal); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                ⚡
            </div>
        </div>
        <div>
            <div style="font-size: 2.1rem; font-weight: 800; font-family: 'Sora', sans-serif; color: var(--ss-dark); line-height: 1; margin-bottom: 0.35rem;">
                {{ $totalCount }}
            </div>
            <span style="font-size: 0.8rem; color: var(--ss-slate); font-weight: 500;">
                matériel(s) enregistré(s)
            </span>
        </div>
    </div>

    <!-- Pending / Non traité -->
    <div class="solar-card hover-lift" style="display: flex; flex-direction: column; justify-content: space-between; padding: 1.35rem 1.4rem; border-color: #FCD34D; background: linear-gradient(180deg, #FFFFFF 0%, #FFFDF5 100%);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
            <span style="font-size: 0.78rem; font-weight: 700; color: #92400E; text-transform: uppercase; letter-spacing: 0.05em;">
                Statut : Non traité
            </span>
            <div style="width: 40px; height: 40px; border-radius: 12px; background: #FEF3C7; color: #D97706; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                🟡
            </div>
        </div>
        <div>
            <div style="font-size: 2.1rem; font-weight: 800; font-family: 'Sora', sans-serif; color: #92400E; line-height: 1; margin-bottom: 0.35rem;">
                {{ $pendingCount }}
            </div>
            <span style="font-size: 0.8rem; color: #B45309; font-weight: 500;">
                en attente de validation
            </span>
        </div>
    </div>

    <!-- Available -->
    <div class="solar-card hover-lift" style="display: flex; flex-direction: column; justify-content: space-between; padding: 1.35rem 1.4rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
            <span style="font-size: 0.78rem; font-weight: 700; color: var(--ss-slate); text-transform: uppercase; letter-spacing: 0.05em;">
                Disponibles au prêt
            </span>
            <div style="width: 40px; height: 40px; border-radius: 12px; background: #DCFCE7; color: #16A34A; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                🟢
            </div>
        </div>
        <div>
            <div style="font-size: 2.1rem; font-weight: 800; font-family: 'Sora', sans-serif; color: var(--ss-dark); line-height: 1; margin-bottom: 0.35rem;">
                {{ $availableCount }}
            </div>
            <span style="font-size: 0.8rem; color: #166534; font-weight: 500;">
                actif(s) pour emprunt
            </span>
        </div>
    </div>

    <!-- Categories -->
    <div class="solar-card hover-lift" style="display: flex; flex-direction: column; justify-content: space-between; padding: 1.35rem 1.4rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem;">
            <span style="font-size: 0.78rem; font-weight: 700; color: var(--ss-slate); text-transform: uppercase; letter-spacing: 0.05em;">
                Catégories
            </span>
            <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(255, 176, 32, 0.15); color: #B45309; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                🏷️
            </div>
        </div>
        <div>
            <div style="display: flex; align-items: baseline; justify-content: space-between;">
                <span style="font-size: 2.1rem; font-weight: 800; font-family: 'Sora', sans-serif; color: var(--ss-dark); line-height: 1;">
                    {{ $categoryCount }}
                </span>
                <a href="{{ route('categories.index') }}" style="font-size: 0.8rem; font-weight: 700; color: var(--ss-teal); text-decoration: none;">
                    gérer &rarr;
                </a>
            </div>
            <span style="font-size: 0.8rem; color: var(--ss-slate); font-weight: 500;">
                catégories disponibles
            </span>
        </div>
    </div>
</div>

<!-- Main Section: My Equipment -->
<div style="margin-bottom: 2.5rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--ss-dark); letter-spacing: -0.01em;">
                Mes Équipements Récents
            </h2>
            <p style="font-size: 0.88rem; color: var(--ss-slate);">
                Équipements enregistrés par votre compte prêteur
            </p>
        </div>
        <div style="display: flex; gap: 0.6rem;">
            <a href="{{ route('equipment.index') }}" class="btn-solar-secondary" style="padding: 0.45rem 0.95rem; font-size: 0.85rem;">
                Voir tout le catalogue ({{ $totalCount }}) &rarr;
            </a>
        </div>
    </div>

    @if($equipment->isEmpty())
        <div class="solar-card" style="text-align: center; padding: 3.5rem 1.5rem;">
            <div style="font-size: 3rem; margin-bottom: 0.8rem;">☀️</div>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--ss-dark); margin-bottom: 0.4rem;">
                Vous n'avez pas encore ajouté d'équipement
            </h3>
            <p style="color: var(--ss-slate); max-width: 480px; margin: 0 auto 1.5rem auto; font-size: 0.92rem;">
                Partagez vos panneaux solaires, batteries, éoliennes ou onduleurs avec des utilisateurs engagés dans la transition énergétique.
            </p>
            <a href="{{ route('equipment.create') }}" class="btn-solar-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Ajouter mon premier équipement
            </a>
        </div>
    @else
        <!-- Equipment Cards Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.25rem;">
            @foreach($equipment as $item)
                <div class="solar-card hover-lift" style="display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--ss-amber); overflow: hidden; padding-top: {{ $item->image_url ? '0' : '1.5rem' }};">
                    @if($item->image_url)
                        <div style="height: 180px; margin: 0 -1.5rem 1.25rem -1.5rem; overflow: hidden; background: #0F172A; position: relative;">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @endif
                    <div>
                        <!-- Header with Category & Status -->
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                            <span class="category-pill">
                                <span>{{ $item->category->name }}</span>
                            </span>
                            <span class="status-badge-pending" title="En attente de revue administrateur">
                                🟡 {{ $item->status_label }}
                            </span>
                        </div>

                        <!-- Title and Brand -->
                        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--ss-dark); margin-bottom: 0.25rem;">
                            <a href="{{ route('equipment.show', $item) }}" style="text-decoration: none; color: inherit;">
                                {{ $item->name }}
                            </a>
                        </h3>
                        <p style="font-size: 0.85rem; color: var(--ss-slate); margin-bottom: 0.8rem; font-weight: 500;">
                            Marque : <strong style="color: var(--ss-dark);">{{ $item->brand }}</strong>
                        </p>

                        <!-- Key Specs Pills -->
                        <div style="display: flex; flex-wrap: wrap; gap: 0.45rem; margin-bottom: 1rem;">
                            @if($item->power_watts)
                                <span style="font-size: 0.78rem; background: #F8FAFC; border: 1px solid var(--ss-border); padding: 0.2rem 0.55rem; border-radius: 6px; color: var(--ss-dark); font-weight: 600;">
                                    ⚡ {{ $item->power_watts }} W
                                </span>
                            @endif
                            @if($item->capacity_wh)
                                <span style="font-size: 0.78rem; background: #F8FAFC; border: 1px solid var(--ss-border); padding: 0.2rem 0.55rem; border-radius: 6px; color: var(--ss-dark); font-weight: 600;">
                                    🔋 {{ $item->capacity_wh }} Wh
                                </span>
                            @endif
                            @if($item->is_available)
                                <span class="badge-available">Disponible</span>
                            @else
                                <span class="badge-unavailable">Indisponible</span>
                            @endif
                        </div>
                    </div>

                    <!-- Footer: Price & Actions -->
                    <div style="border-top: 1px solid var(--ss-border-light); padding-top: 0.85rem; margin-top: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <span style="font-size: 1.25rem; font-weight: 800; color: var(--ss-teal);">
                                {{ number_format($item->daily_rate, 2) }} DT
                            </span>
                            <span style="font-size: 0.75rem; color: var(--ss-slate);">/ jour</span>
                        </div>

                        <div style="display: flex; gap: 0.4rem;">
                            <a href="{{ route('equipment.show', $item) }}" class="btn-solar-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.82rem;" title="Voir les détails">
                                View
                            </a>
                            <a href="{{ route('equipment.edit', $item) }}" class="btn-solar-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.82rem;" title="Modifier">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Categories Quick Overview -->
<div class="solar-card" style="margin-top: 2rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
        <div>
            <h2 style="font-size: 1.2rem; font-weight: 800; color: var(--ss-dark);">
                Catégories de Matériel
            </h2>
            <p style="font-size: 0.85rem; color: var(--ss-slate);">
                Types d'équipements renouvelables disponibles sur SolarShare
            </p>
        </div>
        <a href="{{ route('categories.index') }}" class="btn-solar-secondary" style="padding: 0.45rem 0.85rem; font-size: 0.82rem;">
            Toutes les catégories &rarr;
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem;">
        @foreach($categories as $cat)
            <div style="background: #F8FAFC; border: 1px solid var(--ss-border); border-radius: var(--ss-radius-md); padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <strong style="display: block; font-size: 0.95rem; color: var(--ss-dark);">{{ $cat->name }}</strong>
                    <span style="font-size: 0.8rem; color: var(--ss-slate);">{{ $cat->equipment_count }} équipement(s) prêté(s)</span>
                </div>
                <a href="{{ route('categories.show', $cat) }}" style="color: var(--ss-teal); font-weight: 700; text-decoration: none; font-size: 0.85rem;">
                    &rarr;
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
