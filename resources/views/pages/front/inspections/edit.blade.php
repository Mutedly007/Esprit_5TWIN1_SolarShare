{{-- resources/views/pages/front/inspections/edit.blade.php --}}
@extends('layouts.solartec')

@section('title', 'Modifier l\'État des Lieux — SolarShare')
@section('meta_description', 'Mise à jour des informations de l\'état des lieux numérique.')

@section('content')
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rentals.index') }}" style="color: #0F5C6B; text-decoration: none;">Mes Réservations</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rentals.show', $rental) }}" style="color: #0F5C6B; text-decoration: none;">#LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('inspections.show', $inspection) }}" style="color: #0F5C6B; text-decoration: none;">Rapport #{{ $inspection->id }}</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">Modifier</li>
            </ol>
        </nav>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-8">
                
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    
                    <div class="mb-4">
                        <span class="badge rounded-pill text-uppercase fw-bold px-3 py-1 mb-2" style="{{ $inspection->type === 'check_in' ? 'background:#FEF3C7; color:#92400E;' : 'background:#DCFCE7; color:#166534;' }} font-size: 0.78rem;">
                            {{ $inspection->type === 'check_in' ? '🛫 Check-in au départ' : '🛬 Check-out au retour' }}
                        </span>
                        <h1 class="h3 fw-bold text-dark mb-1">Mettre à jour l'état des lieux</h1>
                        <p class="text-muted small mb-0">
                            Modifiez les observations, l'état physique ou le niveau de charge pour l'équipement <strong>{{ $rental->equipment->name }}</strong>.
                        </p>
                    </div>

                    <form action="{{ route('inspections.update', $inspection) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- 1. Battery Level -->
                        <div class="mb-4 p-3 rounded-3 border" style="background: #FAFBFD;">
                            <label for="battery_level_percent" class="form-label fw-bold text-dark d-flex justify-content-between align-items-center mb-1">
                                <span>🔋 Niveau de batterie / charge</span>
                                <span class="badge rounded-pill fs-6 px-3 py-1" id="battery-badge" style="background: #10B981; color: #fff;">
                                    <span id="battery-value">{{ $inspection->battery_level_percent ?? 100 }}</span>%
                                </span>
                            </label>

                            <input type="range" 
                                   class="form-range" 
                                   min="0" 
                                   max="100" 
                                   step="5" 
                                   name="battery_level_percent" 
                                   id="battery_level_percent" 
                                   value="{{ old('battery_level_percent', $inspection->battery_level_percent ?? 100) }}" 
                                   oninput="updateBatteryDisplay(this.value)">

                            <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                                <span>0%</span>
                                <span>50%</span>
                                <span>100%</span>
                            </div>
                        </div>

                        <!-- 2. Physical Condition -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2">
                                🛠️ État physique général <span class="text-danger">*</span>
                            </label>
                            
                            <div class="row g-2">
                                @php
                                    $conditions = [
                                        'excellent' => ['label' => 'Excellent état', 'desc' => 'Comme neuf', 'icon' => '✨'],
                                        'good' => ['label' => 'Bon état', 'desc' => 'Usage normal', 'icon' => '👍'],
                                        'fair' => ['label' => 'État moyen', 'desc' => 'Traces d\'usure', 'icon' => '⚠️'],
                                        'damaged' => ['label' => 'Endommagé', 'desc' => 'Défauts ou impacts', 'icon' => '❌'],
                                    ];
                                    $currentCond = old('physical_condition', $inspection->physical_condition);
                                @endphp

                                @foreach($conditions as $key => $cond)
                                    <div class="col-sm-6">
                                        <label class="p-3 rounded-3 border d-flex align-items-center gap-3 w-100 cursor-pointer" style="cursor: pointer;">
                                            <input type="radio" 
                                                   name="physical_condition" 
                                                   value="{{ $key }}" 
                                                   class="form-check-input mt-0" 
                                                   {{ $currentCond === $key ? 'checked' : '' }} 
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
                        </div>

                        <!-- 3. Cables and Accessories -->
                        <div class="mb-4 p-3 rounded-3 border" style="background: #FAFBFD;">
                            <label class="form-label fw-bold text-dark mb-2">
                                🔌 Câbles et accessoires
                            </label>
                            
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_all_cables" id="cables_yes" value="1" {{ old('has_all_cables', $inspection->has_all_cables ? '1' : '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold text-success" for="cables_yes">
                                        ✅ Tous les câbles sont présents
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="has_all_cables" id="cables_no" value="0" {{ old('has_all_cables', $inspection->has_all_cables ? '1' : '0') === '0' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold text-danger" for="cables_no">
                                        ❌ Câble(s) manquant(s)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                📝 Remarques & Observations
                            </label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3" 
                                      class="form-control rounded-3">{{ old('notes', $inspection->notes) }}</textarea>
                        </div>

                        <!-- 5. Additional Photos -->
                        <div class="mb-4">
                            <label for="photos" class="form-label fw-bold text-dark" style="font-size: 0.9rem;">
                                📸 Ajouter des photos supplémentaires
                            </label>
                            <input type="file" name="photos[]" id="photos" class="form-control rounded-3" multiple accept="image/*">
                            
                            @if($inspection->photos && count($inspection->photos))
                                <div class="mt-3">
                                    <span class="d-block small text-muted mb-2">Photos actuellement enregistrées :</span>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach($inspection->photo_urls as $url)
                                            <a href="{{ $url }}" target="_blank">
                                                <img src="{{ $url }}" alt="Photo inspection" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid #E2E8F0;">
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('inspections.show', $inspection) }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold flex-grow-1" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520;">
                                💾 Mettre à jour l'état des lieux
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
</script>
@endpush
@endsection
