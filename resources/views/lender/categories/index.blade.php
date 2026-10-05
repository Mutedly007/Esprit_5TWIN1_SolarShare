{{-- resources/views/lender/categories/index.blade.php --}}
@extends('layouts.lender')

@section('title', 'Catégories — SolarShare')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.75rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <div style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; color: var(--ss-slate); margin-bottom: 0.25rem;">
            <a href="{{ route('lender.dashboard') }}" style="color: var(--ss-teal); text-decoration: none;">Dashboard</a>
            <span>/</span>
            <span>Catégories</span>
        </div>
        <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--ss-dark); letter-spacing: -0.02em;">
            Catégories d'Équipement
        </h1>
        <p style="font-size: 0.92rem; color: var(--ss-slate);">
            Gérez les types d'équipements solaires et d'énergie renouvelable disponibles sur la plateforme.
        </p>
    </div>

    <div>
        <a href="{{ route('categories.create') }}" class="btn-solar-primary" id="btn-add-category">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Category
        </a>
    </div>
</div>

<div class="solar-table-wrap">
    <table class="solar-table">
        <thead>
            <tr>
                <th style="width: 70px;">ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Description</th>
                <th>Équipements</th>
                <th style="text-align: right; width: 220px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td style="font-weight: 700; color: var(--ss-slate);">
                        #{{ $category->id }}
                    </td>
                    <td>
                        <a href="{{ route('categories.show', $category) }}" style="font-weight: 700; color: var(--ss-dark); text-decoration: none; font-size: 0.95rem;">
                            {{ $category->name }}
                        </a>
                    </td>
                    <td>
                        <code style="background: #F1F5F9; color: var(--ss-teal-dark); padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.82rem; font-family: monospace;">
                            {{ $category->slug }}
                        </code>
                    </td>
                    <td style="color: var(--ss-slate); max-width: 320px;">
                        {{ $category->description ? Str::limit($category->description, 70) : '—' }}
                    </td>
                    <td>
                        <span class="category-pill">
                            {{ $category->equipment_count ?? $category->equipment()->count() }} matériel(s)
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; align-items: center; gap: 0.45rem;">
                            <a href="{{ route('categories.show', $category) }}" class="btn-solar-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Voir">
                                View
                            </a>
                            <a href="{{ route('categories.edit', $category) }}" class="btn-solar-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Modifier">
                                Edit
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la catégorie « {{ addslashes($category->name) }} » ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-solar-danger" title="Supprimer">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem 1.5rem; color: var(--ss-slate);">
                        <div style="font-size: 2.2rem; margin-bottom: 0.5rem;">🏷️</div>
                        <p style="font-weight: 600; font-size: 1rem; color: var(--ss-dark);">Aucune catégorie trouvée</p>
                        <p style="font-size: 0.85rem; margin-top: 0.25rem;">Commencez par créer votre première catégorie d'énergie renouvelable.</p>
                        <a href="{{ route('categories.create') }}" class="btn-solar-primary" style="margin-top: 1rem; display: inline-flex;">
                            + Ajouter une catégorie
                        </a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
