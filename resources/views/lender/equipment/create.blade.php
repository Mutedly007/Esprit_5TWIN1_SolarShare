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

                <!-- Equipment Image Upload & AI Vision Analyzer -->
                <div class="form-group" style="grid-column: span 12;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.4rem; flex-wrap: wrap; gap: 0.5rem;">
                        <label for="image" class="form-label" style="margin-bottom: 0;">
                            Photo de l'équipement <span class="req">*</span>
                        </label>
                        <span style="display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.78rem; font-weight: 700; color: #4F46E5; background: #EEF2FF; padding: 0.25rem 0.65rem; border-radius: var(--ss-radius-pill); border: 1px solid #C7D2FE;">
                            <span>✨</span> Remplissage intelligent par Gemini Vision
                        </span>
                    </div>

                    <div style="background: #F8FAFC; border: 1.5px dashed var(--ss-border); border-radius: var(--ss-radius-md); padding: 1.25rem; transition: all 0.3s ease;">
                        <div style="display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap;">
                            <div id="image-preview-box" style="width: 120px; height: 120px; border-radius: 12px; border: 1px solid var(--ss-border); display: flex; align-items: center; justify-content: center; overflow: hidden; background: #FFFFFF; flex-shrink: 0; box-shadow: var(--ss-shadow-sm);">
                                <span id="image-preview-placeholder" style="font-size: 2.4rem; color: #94A3B8;">📸</span>
                                <img id="image-preview-img" src="" alt="Aperçu" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            
                            <div style="flex: 1; min-width: 260px;">
                                <input
                                    type="file"
                                    name="image"
                                    id="image"
                                    accept="image/png,image/jpeg,image/webp,image/jpg"
                                    class="form-control @error('image') is-invalid @enderror"
                                    onchange="handleEquipmentImageSelection(this)"
                                >
                                <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; margin-top: 0.5rem; flex-wrap: wrap;">
                                    <span class="form-hint" style="margin: 0;">
                                        JPG, PNG, WebP (max 10 Mo). Dès sélection, Gemini Vision analyse le matériel, son état et pré-remplit les champs.
                                    </span>
                                    <button
                                        type="button"
                                        id="btn-trigger-ai"
                                        onclick="triggerAiAnalysis()"
                                        style="display: none; align-items: center; gap: 0.4rem; font-size: 0.82rem; font-weight: 700; padding: 0.4rem 0.85rem; border-radius: var(--ss-radius-md); border: 1px solid #6366F1; background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%); color: #FFFFFF; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 6px rgba(99, 102, 241, 0.25);"
                                    >
                                        <span>✨</span> Ré-analyser avec l'IA
                                    </button>
                                </div>
                                @error('image')
                                    <span class="form-text-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- AI Analysis Status Card -->
                        <div id="ai-status-card" style="display: none; margin-top: 1rem; border-radius: 10px; padding: 1rem; font-size: 0.88rem; transition: all 0.3s ease;">
                            <!-- Dynamically populated via JS -->
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

<style>
    @keyframes aiSpin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .field-ai-highlight {
        animation: aiFieldGlow 2s ease-out;
        border-color: #10B981 !important;
        background-color: #F0FDF4 !important;
        transition: all 0.4s ease;
    }
    @keyframes aiFieldGlow {
        0% { box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.4); }
        100% { box-shadow: none; }
    }
</style>

<script>
    function handleEquipmentImageSelection(input) {
        const previewImg = document.getElementById('image-preview-img');
        const placeholder = document.getElementById('image-preview-placeholder');
        const triggerBtn = document.getElementById('btn-trigger-ai');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);

            if (triggerBtn) {
                triggerBtn.style.display = 'inline-flex';
            }

            // Automatically launch AI analysis upon photo selection
            triggerAiAnalysis();
        } else {
            previewImg.style.display = 'none';
            placeholder.style.display = 'block';
            if (triggerBtn) triggerBtn.style.display = 'none';
            const statusCard = document.getElementById('ai-status-card');
            if (statusCard) statusCard.style.display = 'none';
        }
    }

    async function triggerAiAnalysis() {
        const fileInput = document.getElementById('image');
        const statusCard = document.getElementById('ai-status-card');

        if (!fileInput.files || !fileInput.files[0]) {
            alert('Veuillez d\'abord sélectionner une photo.');
            return;
        }

        const file = fileInput.files[0];
        statusCard.style.display = 'block';
        statusCard.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.85rem; color: #3730A3; background: #EEF2FF; border: 1.5px solid #C7D2FE; border-radius: 10px; padding: 1rem;">
                <div style="width: 24px; height: 24px; border: 3px solid #C7D2FE; border-top-color: #4F46E5; border-radius: 50%; animation: aiSpin 0.7s linear infinite; flex-shrink: 0;"></div>
                <div>
                    <strong style="font-size: 0.95rem; color: #312E81;">Analyse visuelle Gemini Vision en cours...</strong>
                    <div style="font-size: 0.82rem; color: #4338CA; margin-top: 0.2rem;">
                        Inspection de l'image, reconnaissance de la marque, vérification de la netteté et détection des dommages éventuels...
                    </div>
                </div>
            </div>
        `;

        const formData = new FormData();
        formData.append('image', file);

        try {
            const token = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';
            const response = await fetch("{{ route('equipment.analyze-photo') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Impossible d\'analyser la photo.');
            }

            const data = result.data;

            // Scenario 1: Irrelevant photo (Not renewable energy equipment)
            if (data.is_valid_equipment === false) {
                statusCard.innerHTML = `
                    <div style="background: #FEF2F2; border: 1.5px solid #FCA5A5; color: #991B1B; border-radius: 10px; padding: 1rem;">
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <span style="font-size: 1.4rem;">⚠️</span>
                            <div>
                                <strong style="font-size: 0.95rem; color: #991B1B;">Photo non reconnue ou hors sujet</strong>
                                <p style="margin: 0.35rem 0 0 0; font-size: 0.86rem; color: #B91C1C;">
                                    ${data.relevance_explanation || "L'image ne semble pas être un équipement d'énergie renouvelable (panneau solaire, batterie, onduleur, etc.)."}
                                </p>
                                ${data.quality_warning ? `<div style="margin-top: 0.4rem; font-size: 0.82rem; color: #DC2626;"><strong>Conseil qualité :</strong> ${data.quality_warning}</div>` : ''}
                                <div style="margin-top: 0.6rem; font-size: 0.82rem; color: #7F1D1D; font-weight: 600;">
                                    👉 Veuillez téléverser une photo claire de votre matériel pour activer le pré-remplissage automatique.
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                return;
            }

            // Scenario 2: Valid equipment
            // Pre-fill form fields
            fillField('name', data.name);
            fillField('brand', data.brand);
            if (data.matched_category_id) {
                fillField('category_id', data.matched_category_id);
            }
            if (data.power_watts) {
                fillField('power_watts', data.power_watts);
            }
            if (data.capacity_wh) {
                fillField('capacity_wh', data.capacity_wh);
            }
            if (data.daily_rate) {
                fillField('daily_rate', Number(data.daily_rate).toFixed(2));
            }
            if (data.deposit_amount) {
                fillField('deposit_amount', Number(data.deposit_amount).toFixed(2));
            }
            if (data.description) {
                fillField('description', data.description);
            }

            // Render success report card
            const damageBadge = data.damage_detected
                ? `<span style="background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 9999px; font-size: 0.78rem;">⚠️ Dommages / usure détectés</span>`
                : `<span style="background: #DCFCE7; color: #166534; border: 1px solid #86EFAC; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 9999px; font-size: 0.78rem;">🟢 Aucun dommage visible</span>`;

            const qualityBadge = data.is_blurry_or_low_quality
                ? `<span style="background: #FFFBEB; color: #B45309; border: 1px solid #FCD34D; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 9999px; font-size: 0.78rem;">⚠️ Photo floue / sombre</span>`
                : `<span style="background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 9999px; font-size: 0.78rem;">✅ Photo nette</span>`;

            statusCard.innerHTML = `
                <div style="background: #F0FDF4; border: 1.5px solid #86EFAC; border-radius: 10px; padding: 1.1rem; color: #166534;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.45rem; font-weight: 800; font-size: 0.96rem; color: #15803D;">
                            <span>✨</span> Analyse Gemini Vision réussie
                        </div>
                        <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                            ${qualityBadge}
                            ${damageBadge}
                        </div>
                    </div>

                    ${data.quality_warning ? `
                        <div style="background: #FFFBEB; border: 1px solid #FCD34D; border-radius: 6px; padding: 0.5rem 0.75rem; color: #92400E; font-size: 0.82rem; margin-bottom: 0.75rem;">
                            <strong>Avis netteté :</strong> ${data.quality_warning}
                        </div>
                    ` : ''}

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 0.6rem; font-size: 0.85rem; margin-bottom: 0.85rem; background: #FFFFFF; border: 1px solid #BBF7D0; border-radius: 8px; padding: 0.85rem;">
                        <div><span style="color: #64748B;">Équipement :</span> <strong>${data.identified_type || data.name}</strong></div>
                        <div><span style="color: #64748B;">Marque :</span> <strong>${data.brand}</strong></div>
                        <div><span style="color: #64748B;">Puissance estimée :</span> <strong>${data.power_watts ? data.power_watts + ' W' : 'Non spécifiée'}</strong></div>
                        <div><span style="color: #64748B;">Capacité batterie :</span> <strong>${data.capacity_wh ? data.capacity_wh + ' Wh' : 'N/A'}</strong></div>
                    </div>

                    <!-- Inspection visuelle & calcul caution -->
                    <div style="background: #FFFFFF; border: 1px solid ${data.damage_detected ? '#FCD34D' : '#BBF7D0'}; border-radius: 8px; padding: 0.85rem; margin-bottom: 0.75rem;">
                        <div style="display: flex; align-items: flex-start; gap: 0.6rem;">
                            <span style="font-size: 1.25rem;">${data.damage_detected ? '🔍' : '🛡️'}</span>
                            <div style="font-size: 0.85rem;">
                                <strong style="color: ${data.damage_detected ? '#B45309' : '#15803D'};">
                                    ${data.damage_detected ? 'Détection de défauts / usure :' : 'État général constaté :'}
                                </strong>
                                <span style="color: #334155;"> ${data.damage_assessment}</span>
                                <div style="margin-top: 0.4rem; padding-top: 0.4rem; border-top: 1px dashed #E2E8F0; color: #475569; font-size: 0.82rem;">
                                    <strong>Caution suggérée (${data.deposit_amount} DT) :</strong> ${data.deposit_recommendation_reason}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; flex-wrap: wrap; font-size: 0.82rem; color: #15803D; font-weight: 600;">
                        <span>✓ Tous les champs du formulaire ont été pré-remplis automatiquement. Vous restez libre de les ajuster avant validation.</span>
                    </div>
                </div>
            `;

        } catch (err) {
            statusCard.innerHTML = `
                <div style="background: #FEF2F2; border: 1.5px solid #FCA5A5; color: #991B1B; border-radius: 10px; padding: 0.9rem; font-size: 0.88rem;">
                    <strong>Erreur lors de l'analyse :</strong> ${err.message}
                </div>
            `;
        }
    }

    function fillField(id, value) {
        const el = document.getElementById(id);
        if (el && value !== null && value !== undefined) {
            el.value = value;
            el.classList.add('field-ai-highlight');
            setTimeout(() => {
                el.classList.remove('field-ai-highlight');
            }, 2500);
        }
    }
</script>
@endsection
