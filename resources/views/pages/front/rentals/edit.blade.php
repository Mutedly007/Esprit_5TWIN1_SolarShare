{{-- resources/views/pages/front/rentals/edit.blade.php --}}
@extends('layouts.solartec')

@section('title', 'Modifier les Dates de Réservation — SolarShare')
@section('meta_description', 'Modification des dates de réservation du matériel et recalcul du tarif.')

@section('content')
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rentals.index') }}" style="color: #0F5C6B; text-decoration: none;">Mes Réservations</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rentals.show', $rental) }}" style="color: #0F5C6B; text-decoration: none;">#LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">Modifier les dates</li>
            </ol>
        </nav>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    
                    <div class="mb-4">
                        <span class="badge rounded-pill text-uppercase fw-bold px-3 py-2 mb-2" style="background: rgba(255, 176, 32, 0.15); color: #C2410C; font-size: 0.75rem; letter-spacing: 0.05em;">
                            ✏️ Modification de Contrat
                        </span>
                        <h1 class="h3 fw-bold text-dark mb-1">Modifier les dates de réservation</h1>
                        <p class="text-muted small">
                            Ajustez votre période de location. Le montant total sera automatiquement recalculé sur la base de {{ number_format($rental->equipment->daily_rate, 2) }} TND / jour.
                        </p>
                    </div>

                    <!-- Equipment Summary Box -->
                    <div class="p-3 rounded-3 border d-flex align-items-center gap-3 mb-4" style="background: #F8FAFC;">
                        <div style="width: 60px; height: 60px; border-radius: 10px; background: #0F172A; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                            @if($rental->equipment?->image_url)
                                <img src="{{ $rental->equipment->image_url }}" alt="{{ $rental->equipment->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="font-size: 1.5rem;">☀️</span>
                            @endif
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="badge bg-light text-dark border rounded-pill small" style="font-size: 0.75rem;">
                                    {{ $rental->equipment->category?->name ?? 'Énergie' }}
                                </span>
                                <strong style="color: #C2410C;">{{ number_format($rental->equipment->daily_rate, 2) }} TND / jour</strong>
                            </div>
                            <strong class="d-block text-dark text-truncate mt-1">{{ $rental->equipment->name }}</strong>
                            <small class="text-muted">Proposé par {{ $rental->equipment->user?->name ?? 'Membre' }}</small>
                        </div>
                    </div>

                    <form action="{{ route('rentals.update', $rental) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label for="start_date" class="form-label fw-bold" style="color: #10202B; font-size: 0.9rem;">
                                    📅 Nouvelle date de début <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="start_date" 
                                       id="start_date" 
                                       class="form-control rounded-3 py-2 @error('start_date') is-invalid @enderror" 
                                       value="{{ old('start_date', $rental->start_date->format('Y-m-d')) }}" 
                                       min="{{ now()->format('Y-m-d') }}" 
                                       required 
                                       onchange="recalculatePrice()">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <label for="end_date" class="form-label fw-bold" style="color: #10202B; font-size: 0.9rem;">
                                    📅 Nouvelle date de fin <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="end_date" 
                                       id="end_date" 
                                       class="form-control rounded-3 py-2 @error('end_date') is-invalid @enderror" 
                                       value="{{ old('end_date', $rental->end_date->format('Y-m-d')) }}" 
                                       min="{{ now()->format('Y-m-d') }}" 
                                       required 
                                       onchange="recalculatePrice()">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Price Preview -->
                        <div class="p-3 rounded-3 mb-4" style="background: linear-gradient(135deg, rgba(255, 176, 32, 0.12), rgba(255, 107, 61, 0.06)); border: 1px solid rgba(255, 176, 32, 0.3);">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted small">Nouvelle durée :</span>
                                <span class="fw-bold text-dark"><span id="preview-days">{{ $rental->duration_days }}</span> jour(s)</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-baseline pt-2 border-top">
                                <span class="fw-bold text-dark">Nouveau Montant Total :</span>
                                <span style="font-size: 1.6rem; font-weight: 800; color: #C2410C; line-height: 1;">
                                    <span id="preview-total-price">{{ number_format($rental->total_price, 2) }}</span> <span style="font-size: 0.9rem; font-weight: 700;">TND</span>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="renter_notes" class="form-label fw-bold" style="color: #10202B; font-size: 0.9rem;">
                                Note / Justification du changement de date
                            </label>
                            <textarea name="renter_notes" 
                                      id="renter_notes" 
                                      rows="3" 
                                      class="form-control rounded-3 @error('renter_notes') is-invalid @enderror" 
                                      placeholder="Raison du changement de dates...">{{ old('renter_notes', $rental->renter_notes) }}</textarea>
                            @error('renter_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('rentals.show', $rental) }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold flex-grow-1" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520;">
                                💾 Enregistrer les nouvelles dates
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
    const currentRate = {{ (float) $rental->equipment->daily_rate }};

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
</script>
@endpush
@endsection
