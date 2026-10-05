{{-- resources/views/lender/categories/show.blade.php --}}
@extends('layouts.lender')

@section('title', $category->name . ' — Catégorie — SolarShare')

@section('content')
<div style="margin-bottom: 2rem;">
    <!-- Breadcrumb -->
    <div style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; color: var(--ss-slate); margin-bottom: 0.75rem;">
        <a href="{{ route('lender.dashboard') }}" style="color: var(--ss-teal); text-decoration: none;">Dashboard</a>
        <span>/</span>
        <a href="{{ route('categories.index') }}" style="color: var(--ss-teal); text-decoration: none;">Catégories</a>
        <span>/</span>
        <span>{{ $category->name }}</span>
    </div>

    <!-- Category Header Card -->
    <div class="solar-card" style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem; border-left: 6px solid var(--ss-amber);">
        <div>
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.35rem;">
                <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--ss-dark); letter-spacing: -0.02em;">
                    {{ $category->name }}
                </h1>
                <code style="background: #F1F5F9; color: var(--ss-teal-dark); padding: 0.15rem 0.5rem; border-radius: 6px; font-size: 0.8rem; font-family: monospace;">
                    {{ $category->slug }}
                </code>
            </div>
            <p style="color: var(--ss-slate); font-size: 0.95rem; max-width: 680px; margin-bottom: 0.6rem;">
                {{ $category->description ?: 'Aucune description disponible pour cette catégorie.' }}
            </p>
            <span class="category-pill" style="font-weight: 700;">
                {{ $category->equipment->count() }} équipement(s) prêté(s) par vous
            </span>
        </div>

        <div style="display: flex; align-items: center; gap: 0.6rem;">
            <a href="{{ route('equipment.create', ['category_id' => $category->id]) }}" class="btn-solar-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Equipment
            </a>
            <a href="{{ route('categories.edit', $category) }}" class="btn-solar-secondary">
                Edit
            </a>
            <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer définitivement la catégorie {{ addslashes($category->name) }} ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-solar-danger" style="padding: 0.65rem 0.95rem;">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Equipment in this category -->
<div style="margin-top: 2rem;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
        <div>
            <h2 style="font-size: 1.3rem; font-weight: 800; color: var(--ss-dark);">
                Vos équipements dans « {{ $category->name }} »
            </h2>
            <p style="font-size: 0.85rem; color: var(--ss-slate);">
                Seul votre matériel enregistré dans cette catégorie est affiché ici.
            </p>
        </div>
    </div>

    @if($category->equipment->isEmpty())
        <div class="solar-card" style="text-align: center; padding: 3rem 1.5rem;">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">⚡</div>
            <p style="font-weight: 700; color: var(--ss-dark); font-size: 1.05rem;">Aucun équipement dans cette catégorie</p>
            <p style="color: var(--ss-slate); font-size: 0.88rem; margin: 0.35rem auto 1.25rem auto; max-width: 440px;">
                Vous n'avez pas encore enregistré de matériel sous cette catégorie.
            </p>
            <a href="{{ route('equipment.create', ['category_id' => $category->id]) }}" class="btn-solar-primary">
                + Ajouter un équipement ici
            </a>
        </div>
    @else
        <div class="solar-table-wrap">
            <table class="solar-table">
                <thead>
                    <tr>
                        <th>Équipement</th>
                        <th>Marque</th>
                        <th>Puissance / Capacité</th>
                        <th style="text-align: right;">Tarif / jour</th>
                        <th>Disponibilité</th>
                        <th>Statut</th>
                        <th style="text-align: right; width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category->equipment as $item)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if($item->image_url)
                                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="width: 44px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid var(--ss-border);">
                                    @else
                                        <div style="width: 44px; height: 44px; border-radius: 8px; background: #F1F5F9; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: var(--ss-slate-light);">
                                            ⚡
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('equipment.show', $item) }}" style="font-weight: 700; color: var(--ss-dark); text-decoration: none; font-size: 0.95rem; display: block;">
                                            {{ $item->name }}
                                        </a>
                                        <span style="font-size: 0.78rem; color: var(--ss-slate);">
                                            Ajouté le {{ $item->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td style="font-weight: 600; color: var(--ss-dark);">
                                {{ $item->brand }}
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.35rem; flex-wrap: wrap;">
                                    @if($item->power_watts)
                                        <span style="font-size: 0.78rem; background: #F8FAFC; border: 1px solid var(--ss-border); padding: 0.15rem 0.45rem; border-radius: 4px;">
                                            ⚡ {{ $item->power_watts }} W
                                        </span>
                                    @endif
                                    @if($item->capacity_wh)
                                        <span style="font-size: 0.78rem; background: #F8FAFC; border: 1px solid var(--ss-border); padding: 0.15rem 0.45rem; border-radius: 4px;">
                                            🔋 {{ $item->capacity_wh }} Wh
                                        </span>
                                    @endif
                                    @if(!$item->power_watts && !$item->capacity_wh)
                                        <span style="color: var(--ss-slate); font-size: 0.82rem;">—</span>
                                    @endif
                                </div>
                            </td>
                            <td style="text-align: right; font-weight: 800; color: var(--ss-teal); font-size: 1rem;">
                                {{ number_format($item->daily_rate, 2) }} DT
                            </td>
                            <td>
                                @if($item->is_available)
                                    <span class="badge-available">Disponible</span>
                                @else
                                    <span class="badge-unavailable">Indisponible</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge-pending">
                                    🟡 {{ $item->status_label }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; align-items: center; gap: 0.4rem;">
                                    <a href="{{ route('equipment.show', $item) }}" class="btn-solar-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;">
                                        View
                                    </a>
                                    <a href="{{ route('equipment.edit', $item) }}" class="btn-solar-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;">
                                        Edit
                                    </a>
                                    <form action="{{ route('equipment.destroy', $item) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer cet équipement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-solar-danger">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
