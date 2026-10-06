{{-- resources/views/pages/front/inspections/create.blade.php --}}
@extends('layouts.solartec')

@section('title', 'État des Lieux : ' . ($type === 'check_in' ? 'Check-in (Départ)' : 'Check-out (Retour)') . ' — SolarShare')
@section('meta_description', 'Remplissage et validation de l\'état des lieux numérique au retrait et à la restitution du matériel.')

@section('content')
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rentals.index') }}" style="color: #0F5C6B; text-decoration: none;">Mes Réservations</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rentals.show', $rental) }}" style="color: #0F5C6B; text-decoration: none;">#LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">
                    État des lieux ({{ $type === 'check_in' ? 'Départ' : 'Retour' }})
                </li>
            </ol>
        </nav>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-8">
                
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge rounded-pill text-uppercase fw-bold px-3 py-1" style="{{ $type === 'check_in' ? 'background:#FEF3C7; color:#92400E;' : 'background:#DCFCE7; color:#166534;' }} font-size: 0.78rem;">
                                {{ $type === 'check_in' ? '🛫 Check-in au départ' : '🛬 Check-out au retour' }}
                            </span>
                            <span class="text-muted small">Contrat #LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        
                        <h1 class="h3 fw-bold text-dark mb-1">
                            {{ $type === 'check_in' ? 'État des lieux de retrait (Check-in)' : 'État des lieux de restitution (Check-out)' }}
                        </h1>
                        <p class="text-muted small mb-0">
                            {{ $type === 'check_in' 
                                ? 'Vérifiez minutieusement l\'état du matériel solaire au moment de la remise au locataire.' 
                                : 'Constatez l\'état de l\'équipement lors de son retour pour libérer la caution.' }}
                        </p>
                    </div>

                    <!-- Equipment Quick Banner -->
                    <div class="p-3 rounded-3 border d-flex align-items-center gap-3 mb-4" style="background: #F8FAFC;">
                        <div style="width: 55px; height: 55px; border-radius: 10px; background: #0F172A; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                            @if($rental->equipment?->image_url)
                                <img src="{{ $rental->equipment->image_url }}" alt="{{ $rental->equipment->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="font-size: 1.4rem;">☀️</span>
                            @endif
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <strong class="d-block text-dark text-truncate">{{ $rental->equipment->name }}</strong>
                            <span class="text-muted small">
                                Prêteur : <strong>{{ $rental->equipment->user?->name }}</strong> ── Locataire : <strong>{{ $rental->user->name }}</strong>
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('inspections.store', $rental) }}" method="POST" enctype="multipart/form-data" id="inspection-form">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">

                        <!-- 1. Battery Level -->
                        <div class="mb-4 p-3 rounded-3 border" style="background: #FAFBFD;">
                            <label for="battery_level_percent" class="form-label fw-bold text-dark d-flex justify-content-between align-items-center mb-1">
                                <span>🔋 Niveau de batterie / charge actuelle</span>
                                <span class="badge rounded-pill fs-6 px-3 py-1" id="battery-badge" style="background: #10B981; color: #fff;">
                                    <span id="battery-value">100</span>%
                                </span>
                            </label>
                            <span class="d-block text-muted small mb-3">
                                Indiquez le pourcentage de charge constaté lors de l'inspection (laissez à 100% si l'équipement n'a pas de batterie).
                            </span>

                            <input type="range" 
                                   class="form-range" 
                                   min="0" 
                                   max="100" 
                                   step="5" 
                                   name="battery_level_percent" 
                                   id="battery_level_percent" 
                                   value="{{ old('battery_level_percent', 100) }}" 
                                   oninput="updateBatteryDisplay(this.value)">

                            <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                                <span>0% (Vide)</span>
                                <span>25%</span>
                                <span>50%</span>
                                <span>75%</span>
                                <span>100% (Pleine charge)</span>
                            </div>
                        </div>

                        <!-- 2. Physical Condition -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2">
                                🛠️ État physique général du matériel <span class="text-danger">*</span>
                            </label>
                            
                            <div class="row g-2">
                                @php
                                    $conditions = [
                                        'excellent' => ['label' => 'Excellent état', 'desc' => 'Comme neuf, aucune rayure', 'icon' => '✨', 'color' => '#16A34A'],
                                        'good' => ['label' => 'Bon état', 'desc' => 'Traces mineures d\'usage normal', 'icon' => '👍', 'color' => '#0F5C6B'],
                                        'fair' => ['label' => 'État moyen', 'desc' => 'Micro-rayures, usure visible', 'icon' => '⚠️', 'color' => '#D97706'],
                                        'damaged' => ['label' => 'Endommagé', 'desc' => 'Défauts, fissures ou impacts', 'icon' => '❌', 'color' => '#DC2626'],
                                    ];
                                    $selectedCondition = old('physical_condition', 'excellent');
                                @endphp

                                @foreach($conditions as $key => $cond)
                                    <div class="col-sm-6">
                                        <label class="p-3 rounded-3 border d-flex align-items-center gap-3 w-100 cursor-pointer condition-tile" style="cursor: pointer; transition: all 0.15s ease;">
                                            <input type="radio" 
                                                   name="physical_condition" 
                                                   value="{{ $key }}" 
                                                   class="form-check-input mt-0" 
                                                   {{ $selectedCondition === $key ? 'checked' : '' }} 
                                                   required>
                                            <div>
                                                <strong class="d-block text-dark" style="font-size: 0.92rem;">
                                                    {{ $cond['icon'] }} {{ $cond['label'] }}
                                                </strong>
                                                <small class="text-muted d-block" style="font-size: 0.78rem;">{{ $cond['desc'] }}</small>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('physical_condition')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 3. Cables and Accessories -->
                        <div class="mb-4 p-3 rounded-3 border" style="background: #FAFBFD;">
                            <label class="form-label fw-bold text-dark mb-2">
                                🔌 Câbles, adaptateurs et accessoires d'origine <span class="text-danger">*</span>
                            </label>
                            
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_all_cables" id="cables_yes" value="1" {{ old('has_all_cables', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold text-success" for="cables_yes">
                                        ✅ Tous les câbles sont présents et conformes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_all_cables" id="cables_no" value="0" {{ old('has_all_cables') === '0' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold text-danger" for="cables_no">
                                        ❌ Câble(s) ou accessoire(s) manquant(s)
                                    </label>
                                </div>
                            </div>
                            @error('has_all_cables')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 4. Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                📝 Remarques & Constatations détaillées <span class="text-muted fw-normal">(optionnel)</span>
                            </label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3" 
                                      class="form-control rounded-3 @error('notes') is-invalid @enderror" 
                                      placeholder="Exemple : Écran intact, câble solaire MC4 d'origine fourni, test de charge réussi...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 5. Photo Uploads -->
                        <div class="mb-4">
                            <label for="photos" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                📸 Photos de constatation <span class="text-muted fw-normal">(recommandé pour la caution)</span>
                            </label>
                            <input type="file" 
                                   name="photos[]" 
                                   id="photos" 
                                   class="form-control rounded-3 @error('photos.*') is-invalid @enderror" 
                                   multiple 
                                   accept="image/*">
                            <span class="form-text text-muted small">
                                Vous pouvez sélectionner plusieurs photos (formats JPG, PNG, WEBP — max 5 Mo par photo).
                            </span>
                            @error('photos.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sign & Validate Options -->
                        <div class="p-3 rounded-3 mb-4 border" style="background: #FEF3C7; border-color: #FCD34D !important;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="validate_now" id="validate_now" value="1" checked>
                                <label class="form-check-label fw-bold text-dark" for="validate_now">
                                    ✍️ Signer et valider numériquement cet état des lieux immédiatement
                                </label>
                            </div>
                            <span class="d-block text-muted small mt-1 ps-4">
                                @if($type === 'check_in')
                                    La validation activera le contrat de prêt (statut <strong>En cours</strong>).
                                @else
                                    La validation clôturera le prêt (statut <strong>Terminé</strong>) et débloquera la caution.
                                @endif
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-wrap gap-2 justify-content-between">
                            <a href="{{ route('rentals.show', $rental) }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold flex-grow-1" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520; font-size: 1rem; box-shadow: 0 4px 14px rgba(255, 107, 61, 0.35);">
                                📋 Enregistrer l'état des lieux
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
    function updateBatteryDisplay(val) {
        document.getElementById('battery-value').textContent = val;
        const badge = document.getElementById('battery-badge');
        if (val > 60) {
            badge.style.background = '#10B981';
        } else if (val > 25) {
            badge.style.background = '#F59E0B';
        } else {
            badge.style.background = '#EF4444';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('battery_level_percent');
        if (input) {
            updateBatteryDisplay(input.value);
        }
    });
</script>
@endpush
@endsection
