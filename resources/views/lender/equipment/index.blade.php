{{-- resources/views/lender/equipment/index.blade.php --}}
@extends('layouts.lender')

@section('title', 'Mes Équipements — SolarShare')

@section('content')
<!-- Header & Actions -->
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; color: var(--ss-slate); margin-bottom: 0.25rem;">
            <a href="{{ route('lender.dashboard') }}" style="color: var(--ss-teal); text-decoration: none;">Dashboard</a>
            <span>/</span>
            <span>Mes Équipements</span>
        </div>
        <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--ss-dark); letter-spacing: -0.02em;">
            Mes Équipements Prêtés
        </h1>
        <p style="font-size: 0.92rem; color: var(--ss-slate);">
            Consultez, organisez par catégorie et gérez les matériels que vous mettez à disposition sur SolarShare.
        </p>
    </div>

    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <a href="{{ route('equipment.create') }}" class="btn-solar-primary" id="btn-add-equipment">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Equipment
        </a>
    </div>
</div>

<!-- Filter & Search Toolbar -->
<div class="solar-card" style="padding: 1rem 1.25rem; margin-bottom: 2rem;">
    <form method="GET" action="{{ route('equipment.index') }}" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.75rem; flex: 1; min-width: 260px;">
            <div style="position: relative; flex: 1; max-width: 380px;">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Rechercher par nom, marque..."
                    class="form-control"
                    style="padding-left: 2.2rem; font-size: 0.9rem;"
                >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%);">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>

            <select name="category" class="form-control" style="max-width: 240px; font-size: 0.9rem;" onchange="this.form.submit()">
                <option value="">Toutes les catégories</option>
                @foreach($allCategories as $cat)
                    <option value="{{ $cat->id }}" {{ $selectedCategory == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn-solar-secondary" style="padding: 0.65rem 1rem; font-size: 0.88rem;">
                Filtrer
            </button>

            @if($search || $selectedCategory)
                <a href="{{ route('equipment.index') }}" class="btn-solar-secondary" style="color: #64748B; font-size: 0.82rem; padding: 0.65rem 0.85rem;">
                    Réinitialiser
                </a>
            @endif
        </div>

        <!-- View Switcher -->
        <div style="display: flex; align-items: center; gap: 0.4rem; background: #F1F5F9; padding: 0.25rem; border-radius: 8px;">
            <button type="button" id="view-mode-categories-btn" onclick="switchView('categories')" class="btn-solar-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.82rem; border: none; background: #FFFFFF; font-weight: 700; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                📁 Par Catégorie
            </button>
            <button type="button" id="view-mode-table-btn" onclick="switchView('table')" class="btn-solar-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.82rem; border: none; background: transparent; color: var(--ss-slate);">
                📋 Tableau
            </button>
        </div>
    </form>
</div>

@if($equipment->isEmpty())
    <!-- Empty State -->
    <div class="solar-card" style="text-align: center; padding: 4rem 1.5rem;">
        <div style="font-size: 3.2rem; margin-bottom: 0.8rem;">☀️</div>
        <h3 style="font-size: 1.3rem; font-weight: 800; color: var(--ss-dark); margin-bottom: 0.4rem;">
            Aucun équipement trouvé
        </h3>
        <p style="color: var(--ss-slate); max-width: 460px; margin: 0 auto 1.5rem auto; font-size: 0.92rem;">
            @if($search || $selectedCategory)
                Aucun de vos équipements ne correspond aux critères de recherche actuels.
            @else
                Vous n'avez pas encore ajouté d'équipement à votre inventaire de prêteur.
            @endif
        </p>
        <div style="display: flex; justify-content: center; gap: 0.75rem;">
            @if($search || $selectedCategory)
                <a href="{{ route('equipment.index') }}" class="btn-solar-secondary">
                    Afficher tous mes équipements
                </a>
            @endif
            <a href="{{ route('equipment.create') }}" class="btn-solar-primary">
                + Ajouter un équipement
            </a>
        </div>
    </div>
@else
    <!-- 1. CATEGORY-SECTIONS VIEW (Section 6: Organized visually by category) -->
    <div id="view-categories-container">
        @php
            // Filter categories that have at least 1 equipment matching current query
            $activeCategoryIds = $equipment->pluck('category_id')->unique()->toArray();
        @endphp

        @foreach($categoriesWithEquipment as $category)
            @php
                $catEquipments = $equipment->where('category_id', $category->id);
            @endphp

            @if($catEquipments->isNotEmpty())
                <div style="margin-bottom: 2.75rem;">
                    <!-- Category Section Heading -->
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid var(--ss-border); padding-bottom: 0.75rem; margin-bottom: 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.65rem;">
                            <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--ss-dark); letter-spacing: -0.01em;">
                                {{ $category->name }}
                            </h2>
                            <span class="category-pill" style="font-weight: 700;">
                                {{ $catEquipments->count() }} élément(s)
                            </span>
                        </div>
                    </div>

                    <!-- Cards for this Category -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.25rem;">
                        @foreach($catEquipments as $item)
                            <div class="solar-card hover-lift" style="display: flex; flex-direction: column; justify-content: space-between; border-top: 3px solid var(--ss-teal); overflow: hidden; padding-top: {{ $item->image_url ? '0' : '1.5rem' }};">
                                @if($item->image_url)
                                    <div style="height: 180px; margin: 0 -1.5rem 1.25rem -1.5rem; overflow: hidden; background: #0F172A; position: relative;">
                                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                @endif
                                <div>
                                    <!-- Status & Availability Row -->
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                                        <span class="status-badge-pending" title="Statut : Non traité (Admin en attente)">
                                            🟡 {{ $item->status_label }}
                                        </span>
                                        @if($item->is_available)
                                            <span class="badge-available">Disponible</span>
                                        @else
                                            <span class="badge-unavailable">Indisponible</span>
                                        @endif
                                    </div>

                                    <!-- Title & Brand -->
                                    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--ss-dark); margin-bottom: 0.25rem;">
                                        <a href="{{ route('equipment.show', $item) }}" style="text-decoration: none; color: inherit;">
                                            {{ $item->name }}
                                        </a>
                                    </h3>
                                    <p style="font-size: 0.88rem; color: var(--ss-slate); margin-bottom: 0.75rem;">
                                        Marque : <strong style="color: var(--ss-dark);">{{ $item->brand }}</strong>
                                    </p>

                                    @if($item->description)
                                        <p style="font-size: 0.84rem; color: #475569; line-height: 1.45; margin-bottom: 0.85rem;">
                                            {{ Str::limit($item->description, 90) }}
                                        </p>
                                    @endif

                                    <!-- Specs Pills -->
                                    <div style="display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 1rem;">
                                        @if($item->power_watts)
                                            <span style="font-size: 0.78rem; background: #F8FAFC; border: 1px solid var(--ss-border); padding: 0.2rem 0.55rem; border-radius: 6px; font-weight: 600;">
                                                ⚡ {{ $item->power_watts }} W
                                            </span>
                                        @endif
                                        @if($item->capacity_wh)
                                            <span style="font-size: 0.78rem; background: #F8FAFC; border: 1px solid var(--ss-border); padding: 0.2rem 0.55rem; border-radius: 6px; font-weight: 600;">
                                                🔋 {{ $item->capacity_wh }} Wh
                                            </span>
                                        @endif
                                        @if($item->deposit_amount)
                                            <span style="font-size: 0.78rem; background: #F8FAFC; border: 1px solid var(--ss-border); padding: 0.2rem 0.55rem; border-radius: 6px; color: var(--ss-slate);">
                                                Caution : {{ number_format($item->deposit_amount, 2) }} DT
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Card Footer: Rate & Actions -->
                                <div style="border-top: 1px solid var(--ss-border-light); padding-top: 0.85rem; margin-top: 0.6rem; display: flex; align-items: center; justify-content: space-between;">
                                    <div>
                                        <span style="font-size: 1.25rem; font-weight: 800; color: var(--ss-teal);">
                                            {{ number_format($item->daily_rate, 2) }} DT
                                        </span>
                                        <span style="font-size: 0.75rem; color: var(--ss-slate);">/ jour</span>
                                    </div>

                                    <div style="display: flex; gap: 0.4rem;">
                                        <a href="{{ route('equipment.show', $item) }}" class="btn-solar-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.82rem;" title="Voir">
                                            View
                                        </a>
                                        <a href="{{ route('equipment.edit', $item) }}" class="btn-solar-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.82rem;" title="Modifier">
                                            Edit
                                        </a>
                                        <form action="{{ route('equipment.destroy', $item) }}" method="POST" style="display: inline;" onsubmit="return confirm('Confirmez-vous la suppression de « {{ addslashes($item->name) }} » ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-solar-danger" style="padding: 0.35rem 0.65rem;">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- 2. TABLE VIEW (Section 5: Clean Table) -->
    <div id="view-table-container" style="display: none;">
        <div class="solar-table-wrap">
            <table class="solar-table">
                <thead>
                    <tr>
                        <th>Equipment</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th style="text-align: right;">Price/day</th>
                        <th>Availability</th>
                        <th>Status</th>
                        <th style="text-align: right; width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipment as $item)
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
                                        <span style="font-size: 0.75rem; color: var(--ss-slate);">
                                            Caution : {{ number_format($item->deposit_amount, 2) }} DT
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="category-pill">
                                    <span>{{ $item->category->name }}</span>
                                </span>
                            </td>
                            <td style="font-weight: 600; color: var(--ss-dark);">
                                {{ $item->brand }}
                            </td>
                            <td style="text-align: right; font-weight: 800; color: var(--ss-teal); font-size: 1rem;">
                                {{ number_format($item->daily_rate, 2) }} DT
                            </td>
                            <td>
                                @if($item->is_available)
                                    <span class="badge-available">Available</span>
                                @else
                                    <span class="badge-unavailable">Unavailable</span>
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
                                    <form action="{{ route('equipment.destroy', $item) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer « {{ addslashes($item->name) }} » ?');">
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
    </div>
@endif

<script>
    function switchView(mode) {
        const catContainer = document.getElementById('view-categories-container');
        const tableContainer = document.getElementById('view-table-container');
        const catBtn = document.getElementById('view-mode-categories-btn');
        const tableBtn = document.getElementById('view-mode-table-btn');

        if (!catContainer || !tableContainer) return;

        if (mode === 'categories') {
            catContainer.style.display = 'block';
            tableContainer.style.display = 'none';
            catBtn.style.background = '#FFFFFF';
            catBtn.style.fontWeight = '700';
            catBtn.style.boxShadow = '0 1px 3px rgba(0,0,0,0.06)';
            catBtn.style.color = 'var(--ss-dark)';

            tableBtn.style.background = 'transparent';
            tableBtn.style.fontWeight = '500';
            tableBtn.style.boxShadow = 'none';
            tableBtn.style.color = 'var(--ss-slate)';
        } else {
            catContainer.style.display = 'none';
            tableContainer.style.display = 'block';
            tableBtn.style.background = '#FFFFFF';
            tableBtn.style.fontWeight = '700';
            tableBtn.style.boxShadow = '0 1px 3px rgba(0,0,0,0.06)';
            tableBtn.style.color = 'var(--ss-dark)';

            catBtn.style.background = 'transparent';
            catBtn.style.fontWeight = '500';
            catBtn.style.boxShadow = 'none';
            catBtn.style.color = 'var(--ss-slate)';
        }
    }
</script>
@endsection
