{{-- resources/views/lender/categories/create.blade.php --}}
@extends('layouts.lender')

@section('title', 'Créer une Catégorie — SolarShare')

@section('content')
<div style="max-width: 720px; margin: 0 auto;">
    <!-- Breadcrumb -->
    <div style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; color: var(--ss-slate); margin-bottom: 0.5rem;">
        <a href="{{ route('lender.dashboard') }}" style="color: var(--ss-teal); text-decoration: none;">Dashboard</a>
        <span>/</span>
        <a href="{{ route('categories.index') }}" style="color: var(--ss-teal); text-decoration: none;">Catégories</a>
        <span>/</span>
        <span>Nouvelle</span>
    </div>

    <!-- Header -->
    <div style="margin-bottom: 1.75rem;">
        <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--ss-dark); letter-spacing: -0.02em;">
            Ajouter une Catégorie
        </h1>
        <p style="font-size: 0.92rem; color: var(--ss-slate);">
            Définissez une nouvelle catégorie de matériel solaire ou renouvelable.
        </p>
    </div>

    <!-- Form Card -->
    <div class="solar-card">
        <form action="{{ route('categories.store') }}" method="POST" novalidate>
            @csrf

            <div class="form-grid">
                <!-- Name -->
                <div class="form-group" style="grid-column: span 12;">
                    <label for="name" class="form-label">
                        Nom de la catégorie <span class="req">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Ex : Panneaux solaires portables"
                        required
                        autofocus
                    >
                    @error('name')
                        <span class="form-text-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Slug -->
                <div class="form-group" style="grid-column: span 12;">
                    <label for="slug" class="form-label">
                        Slug (identifiant URL) <span style="font-size: 0.8rem; font-weight: normal; color: var(--ss-slate);">(Optionnel)</span>
                    </label>
                    <input
                        type="text"
                        name="slug"
                        id="slug"
                        class="form-control @error('slug') is-invalid @enderror"
                        value="{{ old('slug') }}"
                        placeholder="ex: panneaux-solaires-portables"
                    >
                    <span class="form-hint">Laissez vide pour générer automatiquement à partir du nom.</span>
                    @error('slug')
                        <span class="form-text-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group" style="grid-column: span 12;">
                    <label for="description" class="form-label">
                        Description
                    </label>
                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Brève description de cette catégorie de matériel..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <span class="form-text-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Action buttons -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; margin-top: 1.75rem; padding-top: 1.25rem; border-top: 1px solid var(--ss-border-light);">
                <a href="{{ route('categories.index') }}" class="btn-solar-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn-solar-primary" id="btn-submit-category">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Enregistrer la catégorie
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function () {
                if (!slugInput.dataset.touched) {
                    slugInput.value = nameInput.value
                        .toLowerCase()
                        .trim()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/[\s_-]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                }
            });

            slugInput.addEventListener('input', function () {
                slugInput.dataset.touched = "true";
            });
        }
    });
</script>
@endsection
