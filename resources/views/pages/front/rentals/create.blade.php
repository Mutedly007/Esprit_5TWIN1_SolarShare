{{-- resources/views/pages/front/rentals/create.blade.php --}}
@extends('layouts.solartec')

@section('title', 'Réserver un Équipement — SolarShare')
@section('meta_description', 'Formulaire de réservation de matériel solaire avec calcul automatique du coût et état des lieux.')

@section('content')
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('marketplace') }}" style="color: #0F5C6B; text-decoration: none;">Marketplace</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rentals.index') }}" style="color: #0F5C6B; text-decoration: none;">Mes Réservations</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">Nouvelle Réservation</li>
            </ol>
        </nav>

        <div class="row g-4 justify-content-center">
            
            <!-- Left Column: Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    
                    <div class="mb-4">
                        <span class="badge rounded-pill text-uppercase fw-bold px-3 py-2 mb-2" style="background: rgba(255, 176, 32, 0.15); color: #C2410C; font-size: 0.75rem; letter-spacing: 0.05em;">
                            ⚡ Contrat de Prêt & Location
                        </span>
                        <h1 class="h3 fw-bold text-dark mb-1">Réserver un équipement</h1>
                        <p class="text-muted small">
                            Choisissez votre période d'utilisation. Le tarif est calculé automatiquement à la journée.
                        </p>
                    </div>

                    <form action="{{ route('rentals.store') }}" method="POST" id="rental-form">
                        @csrf

                        <!-- Equipment Selection -->
                        <div class="mb-4">
                            <label for="equipment_id" class="form-label fw-bold" style="color: #10202B; font-size: 0.9rem;">
                                Équipement à louer <span class="text-danger">*</span>
                            </label>
                            
                            @if($selectedEquipment)
                                <input type="hidden" name="equipment_id" id="equipment_id" value="{{ $selectedEquipment->id }}">
                                
                                <div class="p-3 rounded-3 border d-flex align-items-center gap-3" style="background: #F8FAFC;">
                                    <div style="width: 60px; height: 60px; border-radius: 10px; background: #0F172A; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                        @if($selectedEquipment->image_url)
                                            <img src="{{ $selectedEquipment->image_url }}" alt="{{ $selectedEquipment->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <span style="font-size: 1.5rem;">☀️</span>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="badge bg-light text-dark border rounded-pill small" style="font-size: 0.75rem;">
                                                {{ $selectedEquipment->category?->name ?? 'Énergie' }}
                                            </span>
                                            <strong style="color: #C2410C;">{{ number_format($selectedEquipment->daily_rate, 2) }} TND / jour</strong>
                                        </div>
                                        <strong class="d-block text-dark text-truncate mt-1">{{ $selectedEquipment->name }}</strong>
                                        <small class="text-muted">Proposé par {{ $selectedEquipment->user?->name ?? 'Membre' }}</small>
                                    </div>
                                </div>
                            @else
                                <select name="equipment_id" id="equipment_id" class="form-select rounded-3 py-2 @error('equipment_id') is-invalid @enderror" required onchange="handleEquipmentChange(this)">
                                    <option value="" disabled selected>-- Sélectionnez un équipement --</option>
                                    @foreach($availableEquipment as $eq)
                                        <option value="{{ $eq->id }}" 
                                                data-rate="{{ $eq->daily_rate }}" 
                                                data-deposit="{{ $eq->deposit_amount }}"
                                                data-name="{{ $eq->name }}"
                                                data-brand="{{ $eq->brand }}"
                                                data-category="{{ $eq->category?->name }}"
                                                data-power="{{ $eq->power_watts }}"
                                                data-capacity="{{ $eq->capacity_wh }}"
                                                {{ old('equipment_id') == $eq->id ? 'selected' : '' }}>
                                            {{ $eq->name }} ({{ $eq->category?->name }}) — {{ number_format($eq->daily_rate, 2) }} TND/j
                                        </option>
                                    @endforeach
                                </select>
                                @error('equipment_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        <!-- Date Selection Row -->
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label for="start_date" class="form-label fw-bold" style="color: #10202B; font-size: 0.9rem;">
                                    📅 Date de début (Retrait) <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="start_date" 
                                       id="start_date" 
                                       class="form-control rounded-3 py-2 @error('start_date') is-invalid @enderror" 
                                       value="{{ old('start_date', now()->format('Y-m-d')) }}" 
                                       min="{{ now()->format('Y-m-d') }}" 
                                       required 
                                       onchange="recalculatePrice()">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <span class="form-text text-muted small">Date de réalisation du Check-in.</span>
                            </div>

                            <div class="col-sm-6">
                                <label for="end_date" class="form-label fw-bold" style="color: #10202B; font-size: 0.9rem;">
                                    📅 Date de fin (Restitution) <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="end_date" 
                                       id="end_date" 
                                       class="form-control rounded-3 py-2 @error('end_date') is-invalid @enderror" 
                                       value="{{ old('end_date', now()->addDays(2)->format('Y-m-d')) }}" 
                                       min="{{ now()->format('Y-m-d') }}" 
                                       required 
                                       onchange="recalculatePrice()">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <span class="form-text text-muted small">Date de réalisation du Check-out.</span>
                            </div>
                        </div>

                        <!-- Notes / Use Case -->
                        <div class="mb-4">
                            <label for="renter_notes" class="form-label fw-bold" style="color: #10202B; font-size: 0.9rem;">
                                Remarques / Projet d'utilisation <span class="text-muted fw-normal">(optionnel)</span>
                            </label>
                            <textarea name="renter_notes" 
                                      id="renter_notes" 
                                      rows="3" 
                                      class="form-control rounded-3 @error('renter_notes') is-invalid @enderror" 
                                      placeholder="Exemple : Utilisation pour un week-end camping vanlife dans le sud, besoin du câble allume-cigare...">{{ old('renter_notes') }}</textarea>
                            @error('renter_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520; font-size: 1.05rem; box-shadow: 0 6px 20px rgba(255, 107, 61, 0.35);">
                            ⚡ Confirmer et Envoyer la Demande de Réservation
                        </button>

                    </form>
                </div>
            </div>

            <!-- Right Column: Live Price & Cycle Summary -->
            <div class="col-lg-5">
                
                <!-- Price Calculator Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4" style="position: sticky; top: 96px;">
                    <h5 class="fw-bold mb-3" style="color: #10202B;">Récapitulatif Financier</h5>

                    <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, rgba(255, 176, 32, 0.12), rgba(255, 107, 61, 0.06)); border: 1px solid rgba(255, 176, 32, 0.3);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Tarif journalier :</span>
                            <span class="fw-bold text-dark"><span id="preview-daily-rate">{{ $selectedEquipment ? number_format($selectedEquipment->daily_rate, 2) : '0.00' }}</span> TND / jour</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Nombre de jours calculé :</span>
                            <span class="fw-bold text-dark"><span id="preview-days">1</span> jour(s)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-baseline pt-2 border-top">
                            <span class="fw-bold text-dark">Total Location :</span>
                            <span style="font-size: 1.8rem; font-weight: 800; color: #C2410C; line-height: 1;">
                                <span id="preview-total-price">{{ $selectedEquipment ? number_format($selectedEquipment->daily_rate * 3, 2) : '0.00' }}</span> <span style="font-size: 0.9rem; font-weight: 700;">TND</span>
                            </span>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 mb-4" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 0.85rem;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted">Caution de garantie :</span>
                            <strong><span id="preview-deposit">{{ $selectedEquipment ? number_format($selectedEquipment->deposit_amount, 2) : '0.00' }}</span> TND</strong>
                        </div>
                        <span class="text-muted small d-block" style="line-height: 1.4;">
                            * La caution n'est pas encaissée. Elle est garantie et restituée intégralement après validation de l'état des lieux de retour (Check-out).
                        </span>
                    </div>

                    <!-- Steps Reminder -->
                    <h6 class="fw-bold text-dark mb-3" style="font-size: 0.9rem;">Cycle de Location en 4 étapes :</h6>
                    <div style="font-size: 0.82rem; color: #475569; display: flex; flex-direction: column; gap: 0.75rem;">
                        <div class="d-flex align-items-start gap-2">
                            <span style="background: #FEF3C7; color: #92400E; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; font-size: 0.75rem;">1</span>
                            <div><strong>Demande de réservation :</strong> Validation par le propriétaire prêteur.</div>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <span style="background: #E0F2FE; color: #0369A1; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; font-size: 0.75rem;">2</span>
                            <div><strong>Check-in au départ :</strong> État des lieux numérique (batterie, câbles, photos).</div>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <span style="background: #DCFCE7; color: #166534; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; font-size: 0.75rem;">3</span>
                            <div><strong>Utilisation :</strong> Énergie solaire propre à votre disposition.</div>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <span style="background: #F1F5F9; color: #334155; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; font-size: 0.75rem;">4</span>
                            <div><strong>Check-out au retour :</strong> Constat de restitution et libération de caution.</div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

@push('scripts')
<script>
    let currentRate = {{ $selectedEquipment ? (float) $selectedEquipment->daily_rate : 0 }};
    let currentDeposit = {{ $selectedEquipment ? (float) $selectedEquipment->deposit_amount : 0 }};

    function handleEquipmentChange(select) {
        const option = select.options[select.selectedIndex];
        if (option) {
            currentRate = parseFloat(option.getAttribute('data-rate')) || 0;
            currentDeposit = parseFloat(option.getAttribute('data-deposit')) || 0;
            document.getElementById('preview-daily-rate').textContent = currentRate.toFixed(2);
            document.getElementById('preview-deposit').textContent = currentDeposit.toFixed(2);
            recalculatePrice();
        }
    }

    function recalculatePrice() {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');

        if (!startInput.value || !endInput.value) return;

        const start = new Date(startInput.value);
        const end = new Date(endInput.value);

        if (end < start) {
            endInput.value = startInput.value;
        }

        const validStart = new Date(startInput.value);
        const validEnd = new Date(endInput.value);

        const diffTime = Math.abs(validEnd - validStart);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

        const total = diffDays * currentRate;

        document.getElementById('preview-days').textContent = diffDays;
        document.getElementById('preview-total-price').textContent = total.toFixed(2);
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        recalculatePrice();
    });
</script>
@endpush
@endsection
