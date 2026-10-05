{{-- resources/views/lender/equipment/create.blade.php --}}
@extends('layouts.lender')

@section('title', 'Ajouter un Équipement — SolarShare')

@section('content')
<div style="max-width: 860px; margin: 0 auto;">
    <!-- Breadcrumb -->
    <div style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.82rem; color: var(--ss-slate); margin-bottom: 0.5rem;">
        <a href="{{ route('lender.dashboard') }}" style="color: var(--ss-teal); text-decoration: none;">Dashboard</a>
        <span>/</span>
        <a href="{{ route('equipment.index') }}" style="color: var(--ss-teal); text-decoration: none;">Mes Équipements</a>
        <span>/</span>
        <span>Ajouter</span>
    </div>

    <!-- Header -->
    <div style="margin-bottom: 1.75rem;">
        <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--ss-dark); letter-spacing: -0.02em;">
            Ajouter un Équipement à Prêter
        </h1>
        <p style="font-size: 0.92rem; color: var(--ss-slate);">
            Proposez votre matériel d'énergie renouvelable (panneau solaire, batterie, onduleur, etc.) à la communauté SolarShare.
        </p>
    </div>

    <!-- Informational Note about 'pending' status -->
    <div style="background: #FFFBEB; border: 1px solid #FCD34D; border-radius: var(--ss-radius-md); padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.75rem;">
        <span style="font-size: 1.25rem; line-height: 1;">🟡</span>
        <div style="font-size: 0.88rem; color: #92400E;">
            <strong>Information sur le statut :</strong> Tout nouvel équipement est enregistré avec le statut initial <strong>« Non traité »</strong>. Il sera immédiatement visible et gérable dans votre espace prêteur.
        </div>
    </div>

    <!-- Form Card -->
    <div class="solar-card">
        <form action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="form-grid">
                <!-- Name -->
                <div class="form-group" style="grid-column: span 8;">
                    <label for="name" class="form-label">
                        Nom de l'équipement <span class="req">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Ex : Panneau solaire pliable 200W, EcoFlow Delta 2..."
                        required
                        autofocus
                    >
                    @error('name')
                        <span class="form-text-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Brand -->
                <div class="form-group" style="grid-column: span 4;">
                    <label for="brand" class="form-label">
                        Marque / Fabricant <span class="req">*</span>
                    </label>
                    <input
                        type="text"
                        name="brand"
                        id="brand"
                        class="form-control @error('brand') is-invalid @enderror"
                        value="{{ old('brand') }}"
                        placeholder="Ex : EcoFlow, Bluetti, Renogy..."
                        required
                    >
                    @error('brand')
                        <span class="form-text-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Category dropdown (Loaded dynamically from database) -->
                <div class="form-group" style="grid-column: span 6;">
                    <label for="category_id" class="form-label">
                        Catégorie <span class="req">*</span>
                    </label>
                    <select
                        name="category_id"
                        id="category_id"
                        class="form-control @error('category_id') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Sélectionnez une catégorie existante --</option>
                        @foreach($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ old('category_id', request('category_id')) == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="form-hint">Une catégorie existante doit obligatoirement être sélectionnée.</span>
                    @error('category_id')
                        <span class="form-text-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Daily Rate (DT) -->
                <div class="form-group" style="grid-column: span 3;">
                    <label for="daily_rate" class="form-label">
                        Tarif / jour (DT) <span class="req">*</span>
                    </label>
                    <input
                        type="number"
                        name="daily_rate"
                        id="daily_rate"
                        step="0.01"
                        min="0"
                        class="form-control @error('daily_rate') is-invalid @enderror"
                        value="{{ old('daily_rate') }}"
                        placeholder="Ex : 15.00"
                        required
                    >
                    <span class="form-hint">Prix par jour en Dinars</span>
                    @error('daily_rate')
                        <span class="form-text-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Deposit Amount (DT) -->
                <div class="form-group" style="grid-column: span 3;">
                    <label for="deposit_amount" class="form-label">
                        Caution (DT) <span class="req">*</span>
                    </label>
                    <input
                        type="number"
                        name="deposit_amount"
                        id="deposit_amount"
                        step="0.01"
                        min="0"
                        class="form-control @error('deposit_amount') is-invalid @enderror"
                        value="{{ old('deposit_amount') }}"
                        placeholder="Ex : 100.00"
                        required
                    >
                    <span class="form-hint">Dépôt de garantie en Dinars</span>
                    @error('deposit_amount')
                        <span class="form-text-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Power in Watts -->
                <div class="form-group" style="grid-column: span 6;">
                    <label for="power_watts" class="form-label">
                        Puissance nominale (Watts)
                    </label>
                    <input
                        type="number"
                        name="power_watts"
                        id="power_watts"
                        step="1"
                        min="0"
                        class="form-control @error('power_watts') is-invalid @enderror"
                        value="{{ old('power_watts') }}"
                        placeholder="Ex : 200, 400, 1000..."
                    >
                    <span class="form-hint">Optionnel : puissance crête ou continue en Watts</span>
                    @error('power_watts')
                        <span class="form-text-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Capacity in Wh -->
                <div class="form-group" style="grid-column: span 6;">
                    <label for="capacity_wh" class="form-label">
                        Capacité de stockage (Wh)
                    </label>
                    <input
                        type="number"
                        name="capacity_wh"
                        id="capacity_wh"
                        step="1"
                        min="0"
                        class="form-control @error('capacity_wh') is-invalid @enderror"
                        value="{{ old('capacity_wh') }}"
                        placeholder="Ex : 512, 1024, 2048..."
                    >
                    <span class="form-hint">Optionnel : capacité batterie en Watt-heures</span>
                    @error('capacity_wh')
                        <span class="form-text-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Equipment Image Upload -->
                <div class="form-group" style="grid-column: span 12;">
                    <label for="image" class="form-label">
                        Photo de l'équipement
                    </label>
                    <div style="display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap; background: #F8FAFC; border: 1.5px dashed var(--ss-border); border-radius: var(--ss-radius-md); padding: 1.25rem;">
                        <div id="image-preview-box" style="width: 110px; height: 110px; border-radius: 12px; border: 1px solid var(--ss-border); display: flex; align-items: center; justify-content: center; overflow: hidden; background: #FFFFFF; flex-shrink: 0;">
                            <span id="image-preview-placeholder" style="font-size: 2.2rem; color: #94A3B8;">📸</span>
                            <img id="image-preview-img" src="" alt="Aperçu" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div style="flex: 1; min-width: 240px;">
                            <input
                                type="file"
                                name="image"
                                id="image"
                                accept="image/png,image/jpeg,image/webp,image/jpg"
                                class="form-control @error('image') is-invalid @enderror"
                                onchange="previewEquipmentImage(this)"
                            >
                            <span class="form-hint" style="margin-top: 0.4rem; display: block;">
                                Formats acceptés : JPG, PNG, WebP (max 5 Mo). Ajoutez une photo claire de votre matériel.
                            </span>
                            @error('image')
                                <span class="form-text-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group" style="grid-column: span 12;">
                    <label for="description" class="form-label">
                        Description détaillée & caractéristiques
                    </label>
                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Précisez l'état, les accessoires inclus (câbles, connecteurs MC4, sac de transport...), conseils d'utilisation..."
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <span class="form-text-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Availability Checkbox -->
                <div class="form-group" style="grid-column: span 12;">
                    <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer;">
                        <input
                            type="checkbox"
                            name="is_available"
                            value="1"
                            {{ old('is_available', '1') ? 'checked' : '' }}
                            style="width: 18px; height: 18px; accent-color: var(--ss-teal);"
                        >
                        <span style="font-weight: 700; color: var(--ss-dark); font-size: 0.95rem;">
                            Rendre cet équipement disponible immédiatement au prêt
                        </span>
                    </label>
                    <span class="form-hint" style="margin-left: 1.8rem;">
                        Vous pourrez suspendre ou réactiver la disponibilité à tout moment depuis votre tableau de bord.
                    </span>
                    @error('is_available')
                        <span class="form-text-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Action buttons -->
            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; margin-top: 1.75rem; padding-top: 1.25rem; border-top: 1px solid var(--ss-border-light);">
                <a href="{{ route('equipment.index') }}" class="btn-solar-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn-solar-primary" id="btn-submit-equipment">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Enregistrer l'équipement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewEquipmentImage(input) {
        const previewImg = document.getElementById('image-preview-img');
        const placeholder = document.getElementById('image-preview-placeholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            previewImg.style.display = 'none';
            placeholder.style.display = 'block';
        }
    }
</script>
@endsection
