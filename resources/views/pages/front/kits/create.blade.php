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

                    <form id="kit-create-form" action="{{ route('energy-kits.store') }}" method="POST" enctype="multipart/form-data" novalidate>
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
                            <div class="invalid-feedback">@error('title'){{ $message }}@else Le titre du pack solaire est obligatoire. @enderror</div>
                        </div>

                        <!-- Target Use & Discount -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="target_use" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                    Usage Cible <span class="text-danger">*</span>
                                </label>
                                <select name="target_use" id="target_use" class="form-select rounded-3 py-2 @error('target_use') is-invalid @enderror" required>
                                    <option value="" disabled {{ old('target_use') ? '' : 'selected' }}>-- Choisir un usage cible --</option>
                                    <option value="vanlife" {{ old('target_use') === 'vanlife' ? 'selected' : '' }}>🚐 Vanlife & Voyage</option>
                                    <option value="camping" {{ old('target_use') === 'camping' ? 'selected' : '' }}>⛺ Camping & Plein Air</option>
                                    <option value="chantier" {{ old('target_use') === 'chantier' ? 'selected' : '' }}>🔨 Chantier & Travaux</option>
                                    <option value="urgence" {{ old('target_use') === 'urgence' ? 'selected' : '' }}>🚨 Secours & Urgence</option>
                                    <option value="evenement" {{ old('target_use') === 'evenement' ? 'selected' : '' }}>🎉 Événementiel & Fêtes</option>
                                </select>
                                <div class="invalid-feedback">@error('target_use'){{ $message }}@else Veuillez sélectionner un usage cible. @enderror</div>
                            </div>

                            <div class="col-md-6">
                                <label for="discount_percentage" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                    Pourcentage de Remise (%) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group has-validation">
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
                                    <div class="invalid-feedback">@error('discount_percentage'){{ $message }}@else Le pourcentage de remise doit être compris entre 0 et 90%. @enderror</div>
                                </div>
                                <span class="form-text text-muted small">Remise appliquée sur le total des tarifs journaliers individuels.</span>
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

                            @if($equipments->isEmpty())
                                <div class="alert alert-warning rounded-3 p-3 mb-3 border-0 d-flex align-items-start gap-3" style="background: #FFFBEB; color: #92400E; border-left: 4px solid #F59E0B !important;">
                                    <span style="font-size: 1.3rem; line-height: 1;">⚠️</span>
                                    <div>
                                        <strong class="d-block mb-1">Aucun équipement disponible dans votre catalogue :</strong>
                                        <p class="mb-2 small">
                                            En tant que prêteur, vous ne pouvez composer un pack solaire qu'à partir de <strong>vos propres équipements approuvés</strong>. Vous n'avez actuellement aucun matériel validé.
                                        </p>
                                        <a href="{{ route('equipment.create') }}" class="btn btn-sm btn-dark rounded-pill px-3 fw-bold">
                                            + Ajouter un équipement
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <div id="equipment-rows-container">
                                <!-- Row 0 -->
                                <div class="row g-2 align-items-end mb-3 equipment-row p-3 rounded-3 bg-white border">
                                    <div class="col-md-5">
                                        <label class="form-label fw-bold small text-dark mb-1">Équipement <span class="text-danger">*</span></label>
                                        <select name="items[0][equipment_id]" class="form-select rounded-3 py-2 equipment-select {{ $errors->has('items.0.equipment_id') || $errors->has('items.*.equipment_id') ? 'is-invalid' : '' }}" required {{ $equipments->isEmpty() ? 'disabled' : '' }}>
                                            <option value="" disabled {{ old('items.0.equipment_id') ? '' : 'selected' }}>
                                                {{ $equipments->isEmpty() ? '-- Aucun équipement approuvé dans votre catalogue --' : '-- Choisir un équipement --' }}
                                            </option>
                                            @foreach($equipments as $eq)
                                                <option value="{{ $eq->id }}" {{ old('items.0.equipment_id') == $eq->id ? 'selected' : '' }}>
                                                    {{ $eq->name }} ({{ $eq->category?->name }}) — {{ number_format($eq->daily_rate, 2) }} TND/j {{ auth()->user()->isAdmin() && $eq->user ? '— Par: ' . $eq->user->name : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            @if($errors->has('items.0.equipment_id'))
                                                {{ $errors->first('items.0.equipment_id') }}
                                            @else
                                                Veuillez sélectionner un équipement pour ce pack.
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-4">
                                        <label class="form-label fw-bold small text-dark mb-1">Quantité <span class="text-danger">*</span></label>
                                        <input type="number" name="items[0][quantity]" class="form-control rounded-3 py-2 equipment-qty {{ $errors->has('items.0.quantity') || $errors->has('items.*.quantity') ? 'is-invalid' : '' }}" min="1" max="20" value="{{ old('items.0.quantity', 1) }}" required>
                                        <div class="invalid-feedback">
                                            @if($errors->has('items.0.quantity'))
                                                {{ $errors->first('items.0.quantity') }}
                                            @else
                                                La quantité doit être entre 1 et 20.
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-6">
                                        <label class="form-label fw-bold small text-dark mb-1">Options / Câbles</label>
                                        <input type="text" name="items[0][options]" class="form-control rounded-3 py-2" placeholder="Ex: Câble MC4 5m inclus" value="{{ old('items.0.options') }}">
                                    </div>

                                    <div class="col-md-1 col-2 text-end">
                                        <button type="button" class="btn btn-outline-danger rounded-3 py-2 w-100" onclick="removeEquipmentRow(this)" title="Supprimer la ligne">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @if($errors->has('items') || $errors->has('items.*'))
                                <div class="alert alert-danger small mt-2 mb-0 py-2 px-3 rounded-3">
                                    @if($errors->has('items'))
                                        <div>{{ $errors->first('items') }}</div>
                                    @endif
                                    @foreach($errors->get('items.*') as $errArr)
                                        @foreach($errArr as $m)
                                            <div>{{ $m }}</div>
                                        @endforeach
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Active Toggle -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-dark" for="is_active">
                                Publier ce pack immédiatement sur le catalogue public
                            </label>
                        </div>

                        <!-- Validation Errors Alert Box (Client-side & Server-side) -->
                        <div id="js-validation-alert" class="alert alert-danger rounded-3 p-3 mb-4 shadow-sm border-0" style="{{ $errors->any() ? '' : 'display: none;' }} background: #FEE2E2; color: #991B1B;">
                            <div class="fw-bold d-flex align-items-center gap-2 mb-2">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.176.176 0 0 1-.066.017H1.146a.176.176 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044 10.413A1 1 0 1 0 7.02 12.43a1 1 0 0 0 1.962-.001zm-.002-3.212l.25-4.286H6.77l.25 4.286h1.96z"/></svg>
                                <span>Impossible d'enregistrer le pack : veuillez corriger les erreurs de saisie</span>
                            </div>
                            <ul id="js-validation-list" class="mb-0 ps-3 small">
                                @if($errors->any())
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('energy-kits.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                Annuler
                            </a>
                            <button type="submit" id="btn-submit-kit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold flex-grow-1" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520; font-size: 1rem; box-shadow: 0 4px 14px rgba(255, 107, 61, 0.35);">
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
                <label class="form-label fw-bold small text-dark mb-1">Équipement <span class="text-danger">*</span></label>
                <select name="items[${rowIndex}][equipment_id]" class="form-select rounded-3 py-2 equipment-select" required>
                    ${selectOptions}
                </select>
                <div class="invalid-feedback">Veuillez sélectionner un équipement pour ce pack.</div>
            </div>
            <div class="col-md-2 col-4">
                <label class="form-label fw-bold small text-dark mb-1">Quantité <span class="text-danger">*</span></label>
                <input type="number" name="items[${rowIndex}][quantity]" class="form-control rounded-3 py-2 equipment-qty" min="1" max="20" value="1" required>
                <div class="invalid-feedback">La quantité doit être entre 1 et 20.</div>
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

    // Client-side instant validation on submit click
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('kit-create-form');
        const alertBox = document.getElementById('js-validation-alert');
        const alertList = document.getElementById('js-validation-list');

        if (!form) return;

        // Auto-remove is-invalid class as soon as user types or selects
        form.addEventListener('input', function(e) {
            if (e.target.classList.contains('is-invalid')) {
                e.target.classList.remove('is-invalid');
            }
        });
        form.addEventListener('change', function(e) {
            if (e.target.classList.contains('is-invalid')) {
                e.target.classList.remove('is-invalid');
            }
        });

        form.addEventListener('submit', function(e) {
            const errors = [];
            let firstInvalid = null;

            // 1. Titre
            const titleInput = document.getElementById('title');
            if (!titleInput.value || !titleInput.value.trim()) {
                titleInput.classList.add('is-invalid');
                errors.push("Le titre du pack solaire est obligatoire.");
                if (!firstInvalid) firstInvalid = titleInput;
            } else {
                titleInput.classList.remove('is-invalid');
            }

            // 2. Usage Cible
            const targetUseSelect = document.getElementById('target_use');
            if (!targetUseSelect.value) {
                targetUseSelect.classList.add('is-invalid');
                errors.push("Veuillez sélectionner un usage cible (ex: Vanlife, Camping...).");
                if (!firstInvalid) firstInvalid = targetUseSelect;
            } else {
                targetUseSelect.classList.remove('is-invalid');
            }

            // 3. Remise (%)
            const discountInput = document.getElementById('discount_percentage');
            const discountVal = parseFloat(discountInput.value);
            if (isNaN(discountVal) || discountVal < 0 || discountVal > 90) {
                discountInput.classList.add('is-invalid');
                errors.push("Le pourcentage de remise doit être un nombre compris entre 0 et 90%.");
                if (!firstInvalid) firstInvalid = discountInput;
            } else {
                discountInput.classList.remove('is-invalid');
            }

            // 4. Équipements inclus
            const rows = document.querySelectorAll('.equipment-row');
            if (rows.length === 0) {
                errors.push("Vous devez sélectionner au moins un équipement pour composer ce pack.");
            }

            let equipmentMissing = false;
            let quantityInvalid = false;

            rows.forEach(function(row) {
                const eqSelect = row.querySelector('.equipment-select') || row.querySelector('select');
                const qtyInput = row.querySelector('.equipment-qty') || row.querySelector('input[type="number"]');

                if (eqSelect) {
                    if (!eqSelect.value || eqSelect.value === '') {
                        eqSelect.classList.add('is-invalid');
                        equipmentMissing = true;
                        if (!firstInvalid) firstInvalid = eqSelect;
                    } else {
                        eqSelect.classList.remove('is-invalid');
                    }
                }

                if (qtyInput) {
                    const qVal = parseInt(qtyInput.value, 10);
                    if (isNaN(qVal) || qVal < 1 || qVal > 20) {
                        qtyInput.classList.add('is-invalid');
                        quantityInvalid = true;
                        if (!firstInvalid) firstInvalid = qtyInput;
                    } else {
                        qtyInput.classList.remove('is-invalid');
                    }
                }
            });

            if (equipmentMissing) {
                errors.push("Veuillez choisir un équipement valide pour chaque ligne du pack.");
            }
            if (quantityInvalid) {
                errors.push("La quantité pour chaque équipement doit être comprise entre 1 et 20.");
            }

            // If any error exists: block submit, display error message list and smooth-scroll
            if (errors.length > 0) {
                e.preventDefault();
                e.stopPropagation();

                alertList.innerHTML = '';
                errors.forEach(function(err) {
                    const li = document.createElement('li');
                    li.textContent = err;
                    alertList.appendChild(li);
                });
                alertBox.style.display = 'block';

                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                } else {
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                alertBox.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection
