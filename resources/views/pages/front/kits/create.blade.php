{{-- resources/views/pages/front/kits/create.blade.php --}}
@extends('layouts.solartec')

@section('title', 'Composer un Pack Solaire — SolarShare')
@section('meta_description', 'Créez un nouveau pack solaire combiné en associant des équipements avec réduction tarifaire.')

@section('content')
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('energy-kits.index') }}" style="color: #0F5C6B; text-decoration: none;">Packs Solaires</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">Nouveau Pack</li>
            </ol>
        </nav>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-9">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    
                    <div class="mb-4">
                        <span class="badge rounded-pill text-uppercase fw-bold px-3 py-1 mb-2" style="background: rgba(255, 176, 32, 0.15); color: #C2410C; font-size: 0.75rem;">
                            📦 Module 3 : Packs Clé-en-main
                        </span>
                        <h1 class="h3 fw-bold text-dark mb-1">Composer un Pack Solaire Combiné</h1>
                        <p class="text-muted small mb-0">
                            Associez plusieurs équipements (batteries, panneaux, adaptateurs) pour créer une offre tout-en-un avec réduction attractive.
                        </p>
                    </div>

                    <form action="{{ route('energy-kits.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                Titre du Pack Solaire <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   class="form-control rounded-3 py-2 @error('title') is-invalid @enderror" 
                                   value="{{ old('title') }}" 
                                   placeholder="Ex : Pack Vanlife Autonomie Totale, Pack Télétravail Nomade..." 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Target Use & Discount -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="target_use" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                    Usage Cible <span class="text-danger">*</span>
                                </label>
                                <select name="target_use" id="target_use" class="form-select rounded-3 py-2 @error('target_use') is-invalid @enderror" required>
                                    <option value="vanlife" {{ old('target_use') === 'vanlife' ? 'selected' : '' }}>🚐 Vanlife & Voyage</option>
                                    <option value="camping" {{ old('target_use') === 'camping' ? 'selected' : '' }}>⛺ Camping & Plein Air</option>
                                    <option value="chantier" {{ old('target_use') === 'chantier' ? 'selected' : '' }}>🔨 Chantier & Travaux</option>
                                    <option value="urgence" {{ old('target_use') === 'urgence' ? 'selected' : '' }}>🚨 Secours & Urgence</option>
                                    <option value="evenement" {{ old('target_use') === 'evenement' ? 'selected' : '' }}>🎉 Événementiel & Fêtes</option>
                                </select>
                                @error('target_use')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="discount_percentage" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                    Pourcentage de Remise (%) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           name="discount_percentage" 
                                           id="discount_percentage" 
                                           class="form-control rounded-start py-2 @error('discount_percentage') is-invalid @enderror" 
                                           min="0" 
                                           max="90" 
                                           step="1" 
                                           value="{{ old('discount_percentage', 15) }}" 
                                           required>
                                    <span class="input-group-text rounded-end bg-light fw-bold">%</span>
                                </div>
                                <span class="form-text text-muted small">Remise appliquée sur le total des tarifs journaliers individuels.</span>
                                @error('discount_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                Description du Pack
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3" 
                                      class="form-control rounded-3 @error('description') is-invalid @enderror" 
                                      placeholder="Détaillez pour qui est conçu ce pack, son autonomie estimée, ses atouts...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                Image d'illustration du Pack <span class="text-muted fw-normal">(optionnel)</span>
                            </label>
                            <input type="file" name="image" id="image" class="form-control rounded-3 @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bundled Equipments (N-N with quantity & options) -->
                        <div class="mb-4 p-4 rounded-3 border" style="background: #FAFBFD;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-0">Équipements Inclus dans le Pack <span class="text-danger">*</span></h5>
                                    <small class="text-muted">Sélectionnez les équipements à combiner, leurs quantités et options.</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addEquipmentRow()">
                                    + Ajouter un équipement
                                </button>
                            </div>

                            <div id="equipment-rows-container">
                                <!-- Row 0 -->
                                <div class="row g-2 align-items-end mb-3 equipment-row p-3 rounded-3 bg-white border">
                                    <div class="col-md-5">
                                        <label class="form-label fw-bold small text-dark mb-1">Équipement</label>
                                        <select name="items[0][equipment_id]" class="form-select rounded-3 py-2" required>
                                            <option value="" disabled selected>-- Choisir un équipement --</option>
                                            @foreach($equipments as $eq)
                                                <option value="{{ $eq->id }}">
                                                    {{ $eq->name }} ({{ $eq->category?->name }}) — {{ number_format($eq->daily_rate, 2) }} TND/j
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 col-4">
                                        <label class="form-label fw-bold small text-dark mb-1">Quantité</label>
                                        <input type="number" name="items[0][quantity]" class="form-control rounded-3 py-2" min="1" max="20" value="1" required>
                                    </div>

                                    <div class="col-md-4 col-6">
                                        <label class="form-label fw-bold small text-dark mb-1">Options / Câbles</label>
                                        <input type="text" name="items[0][options]" class="form-control rounded-3 py-2" placeholder="Ex: Câble MC4 5m inclus">
                                    </div>

                                    <div class="col-md-1 col-2 text-end">
                                        <button type="button" class="btn btn-outline-danger rounded-3 py-2 w-100" onclick="removeEquipmentRow(this)" title="Supprimer la ligne">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @error('items')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Active Toggle -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                            <label class="form-check-label fw-semibold text-dark" for="is_active">
                                Publier ce pack immédiatement sur le catalogue public
                            </label>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('energy-kits.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold flex-grow-1" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520; font-size: 1rem; box-shadow: 0 4px 14px rgba(255, 107, 61, 0.35);">
                                📦 Enregistrer et Créer le Pack Solaire
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</section>

@push('scripts')
<script>
    let rowIndex = 1;

    function addEquipmentRow() {
        const container = document.getElementById('equipment-rows-container');
        const firstRow = container.querySelector('.equipment-row');
        const selectOptions = firstRow.querySelector('select').innerHTML;

        const newRow = document.createElement('div');
        newRow.className = 'row g-2 align-items-end mb-3 equipment-row p-3 rounded-3 bg-white border';
        newRow.innerHTML = `
            <div class="col-md-5">
                <label class="form-label fw-bold small text-dark mb-1">Équipement</label>
                <select name="items[${rowIndex}][equipment_id]" class="form-select rounded-3 py-2" required>
                    ${selectOptions}
                </select>
            </div>
            <div class="col-md-2 col-4">
                <label class="form-label fw-bold small text-dark mb-1">Quantité</label>
                <input type="number" name="items[${rowIndex}][quantity]" class="form-control rounded-3 py-2" min="1" max="20" value="1" required>
            </div>
            <div class="col-md-4 col-6">
                <label class="form-label fw-bold small text-dark mb-1">Options / Câbles</label>
                <input type="text" name="items[${rowIndex}][options]" class="form-control rounded-3 py-2" placeholder="Ex: Câble MC4 5m inclus">
            </div>
            <div class="col-md-1 col-2 text-end">
                <button type="button" class="btn btn-outline-danger rounded-3 py-2 w-100" onclick="removeEquipmentRow(this)" title="Supprimer la ligne">
                    &times;
                </button>
            </div>
        `;
        container.appendChild(newRow);
        rowIndex++;
    }

    function removeEquipmentRow(btn) {
        const container = document.getElementById('equipment-rows-container');
        if (container.querySelectorAll('.equipment-row').length > 1) {
            btn.closest('.equipment-row').remove();
        } else {
            alert('Le pack doit contenir au moins un équipement.');
        }
    }
</script>
@endpush
@endsection
