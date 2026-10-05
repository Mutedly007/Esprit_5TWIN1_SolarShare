{{-- resources/views/lender/equipment/show.blade.php --}}
@extends('layouts.lender')

@section('title', $equipment->name . ' — Équipement — SolarShare')

@section('content')
<div style="max-width: 920px; margin: 0 auto;">
    <!-- Breadcrumb -->
    <div style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; color: var(--ss-slate); margin-bottom: 0.75rem;">
        <a href="{{ route('lender.dashboard') }}" style="color: var(--ss-teal); text-decoration: none;">Dashboard</a>
        <span>/</span>
        <a href="{{ route('equipment.index') }}" style="color: var(--ss-teal); text-decoration: none;">Mes Équipements</a>
        <span>/</span>
        <span>{{ $equipment->name }}</span>
    </div>

    <!-- Header Card -->
    <div class="solar-card" style="margin-bottom: 1.5rem; border-top: 4px solid var(--ss-amber);">
        <div style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.4rem;">
                    <a href="{{ route('categories.show', $equipment->category) }}" class="category-pill" style="text-decoration: none;">
                        <span>{{ $equipment->category->name }}</span>
                    </a>
                    <span class="status-badge-pending" title="Statut : Non traité (Admin en attente)">
                        🟡 {{ $equipment->status_label }}
                    </span>
                    @if($equipment->is_available)
                        <span class="badge-available">Disponible au prêt</span>
                    @else
                        <span class="badge-unavailable">Indisponible</span>
                    @endif
                </div>

                <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--ss-dark); letter-spacing: -0.02em;">
                    {{ $equipment->name }}
                </h1>
                <p style="font-size: 0.95rem; color: var(--ss-slate); font-weight: 500;">
                    Marque / Fabricant : <strong style="color: var(--ss-dark);">{{ $equipment->brand }}</strong>
                </p>
            </div>

            <!-- Actions -->
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <a href="{{ route('equipment.edit', $equipment) }}" class="btn-solar-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('equipment.destroy', $equipment) }}" method="POST" style="display: inline;" onsubmit="return confirm('Confirmez-vous la suppression de « {{ addslashes($equipment->name) }} » ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-solar-danger" style="padding: 0.65rem 0.95rem;">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        @if($equipment->image_url)
            <!-- Equipment Photo Showcase -->
            <div style="margin-bottom: 1.25rem; border-radius: 12px; overflow: hidden; max-height: 440px; background: #0F172A; display: flex; align-items: center; justify-content: center; border: 1px solid var(--ss-border);">
                <img src="{{ $equipment->image_url }}" alt="{{ $equipment->name }}" style="max-height: 440px; width: 100%; object-fit: contain;">
            </div>
        @endif

        <!-- Price Banner -->
        <div style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%); border-radius: var(--ss-radius-md); padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div>
                <span style="display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--ss-slate); letter-spacing: 0.04em;">
                    Tarif de location
                </span>
                <span style="font-size: 1.85rem; font-weight: 800; color: var(--ss-teal);">
                    {{ number_format($equipment->daily_rate, 2) }} DT
                </span>
                <span style="font-size: 0.88rem; color: var(--ss-slate);">/ jour</span>
            </div>

            <div style="text-align: right;">
                <span style="display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--ss-slate); letter-spacing: 0.04em;">
                    Dépôt de garantie (Caution)
                </span>
                <span style="font-size: 1.4rem; font-weight: 800; color: var(--ss-dark);">
                    {{ number_format($equipment->deposit_amount, 2) }} DT
                </span>
            </div>
        </div>
    </div>

    <!-- Technical Specifications & Description -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Specs Card -->
        <div class="solar-card">
            <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--ss-dark); margin-bottom: 1rem; border-bottom: 1px solid var(--ss-border-light); padding-bottom: 0.5rem;">
                Spécifications Techniques
            </h2>
            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.92rem;">
                    <span style="color: var(--ss-slate);">Catégorie</span>
                    <a href="{{ route('categories.show', $equipment->category) }}" style="font-weight: 700; color: var(--ss-teal); text-decoration: none;">
                        {{ $equipment->category->name }}
                    </a>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.92rem;">
                    <span style="color: var(--ss-slate);">Marque</span>
                    <span style="font-weight: 700; color: var(--ss-dark);">{{ $equipment->brand }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.92rem;">
                    <span style="color: var(--ss-slate);">Puissance (Watts)</span>
                    <span style="font-weight: 700; color: var(--ss-dark);">
                        {{ $equipment->power_watts ? $equipment->power_watts . ' W' : 'Non spécifié' }}
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.92rem;">
                    <span style="color: var(--ss-slate);">Capacité (Wh)</span>
                    <span style="font-weight: 700; color: var(--ss-dark);">
                        {{ $equipment->capacity_wh ? $equipment->capacity_wh . ' Wh' : 'Non spécifié' }}
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.92rem;">
                    <span style="color: var(--ss-slate);">Statut de modération</span>
                    <span class="status-badge-pending">🟡 {{ $equipment->status_label }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.92rem;">
                    <span style="color: var(--ss-slate);">Date d'ajout</span>
                    <span style="color: var(--ss-dark);">{{ $equipment->created_at->format('d/m/Y à H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Description Card -->
        <div class="solar-card">
            <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--ss-dark); margin-bottom: 1rem; border-bottom: 1px solid var(--ss-border-light); padding-bottom: 0.5rem;">
                Description & Accessoires
            </h2>
            @if($equipment->description)
                <p style="font-size: 0.92rem; color: #334155; line-height: 1.6; white-space: pre-line;">
                    {{ $equipment->description }}
                </p>
            @else
                <p style="font-size: 0.9rem; color: var(--ss-slate); font-style: italic;">
                    Aucune description détaillée fournie.
                </p>
            @endif
        </div>
    </div>

    <!-- Return button -->
    <div style="text-align: center;">
        <a href="{{ route('equipment.index') }}" class="btn-solar-secondary">
            &larr; Revenir à la liste des équipements
        </a>
    </div>
</div>
@endsection
