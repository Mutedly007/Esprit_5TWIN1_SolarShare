{{-- resources/views/pages/front/rentals/show.blade.php --}}
@extends('layouts.solartec')

@section('title', 'Contrat de Location #LOC-' . str_pad($rental->id, 5, '0', STR_PAD_LEFT) . ' — SolarShare')
@section('meta_description', 'Contrat de prêt et suivi de cycle de location avec états des lieux numériques.')

@section('content')
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rentals.index') }}" style="color: #0F5C6B; text-decoration: none;">Mes Réservations</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">#LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</li>
            </ol>
        </nav>

        @php
            $isLender = $rental->isLender(auth()->user());
            $isRenter = $rental->isRenter(auth()->user());
            $isAdmin = auth()->user()->isAdmin();

            $badgeStyle = match($rental->status) {
                'pending' => 'background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D;',
                'confirmed' => 'background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD;',
                'active' => 'background: #DCFCE7; color: #166534; border: 1px solid #BBF7D0;',
                'completed' => 'background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0;',
                'cancelled' => 'background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA;',
                default => 'background: #F1F5F9; color: #475569;',
            };

            $checkIn = $rental->checkInReport;
            $checkOut = $rental->checkOutReport;
        @endphp

        <!-- Top Header Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge rounded-pill text-uppercase fw-bold px-3 py-1" style="{{ $badgeStyle }} font-size: 0.8rem;">
                            ● {{ $rental->status_label }}
                        </span>
                        <span style="color: #64748B; font-size: 0.85rem; font-weight: 600;">
                            Créée le {{ $rental->created_at->format('d/m/Y à H:i') }}
                        </span>
                    </div>
                    <h1 class="h3 fw-bold text-dark mb-0">
                        Contrat de Location <span style="color: #C2410C;">#LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </h1>
                </div>

                <!-- Action Toolbar -->
                <div class="d-flex flex-wrap gap-2">
                    @if($rental->canBeEdited())
                        <a href="{{ route('rentals.edit', $rental) }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 btn-sm fw-semibold">
                            ✏️ Modifier les dates
                        </a>
                    @endif

                    @if($rental->status === 'pending' && ($isLender || $isAdmin))
                        <form action="{{ route('rentals.confirm', $rental) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success rounded-pill px-4 py-2 btn-sm fw-bold shadow-sm" style="background:#16A34A; border:none;" onclick="return confirm('Confirmer cette réservation ?');">
                                ✅ Valider la réservation
                            </button>
                        </form>
                    @endif

                    @if($rental->canBeCancelled())
                        <!-- Cancel Modal Trigger -->
                        <button type="button" class="btn btn-outline-danger rounded-pill px-3 py-2 btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            ❌ Annuler la réservation
                        </button>
                    @endif

                    @if($isLender)
                        <a href="{{ route('lender.rentals.index') }}" class="btn btn-outline-dark rounded-pill px-3 py-2 btn-sm">
                            &larr; Espace Prêteur
                        </a>
                    @else
                        <a href="{{ route('rentals.index') }}" class="btn btn-outline-dark rounded-pill px-3 py-2 btn-sm">
                            &larr; Mes Réservations
                        </a>
                    @endif
                </div>
            </div>

            <!-- Lifecycle Stepper / Timeline -->
            @if($rental->status !== 'cancelled')
                <div class="mt-4 pt-4 border-top">
                    <div class="d-flex justify-content-between position-relative" style="max-width: 800px; margin: 0 auto;">
                        
                        <!-- Step 1: Pending -->
                        <div class="text-center" style="flex: 1; position: relative;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; margin: 0 auto 0.5rem auto; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; background: #16A34A; color: #fff; box-shadow: 0 0 0 4px #DCFCE7;">
                                ✓
                            </div>
                            <span class="d-block fw-bold text-dark" style="font-size: 0.8rem;">1. Réservation</span>
                            <span class="text-muted" style="font-size: 0.72rem;">Demandée</span>
                        </div>

                        <!-- Step 2: Confirmed -->
                        @php $step2Done = in_array($rental->status, ['confirmed', 'active', 'completed']); @endphp
                        <div class="text-center" style="flex: 1; position: relative;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; margin: 0 auto 0.5rem auto; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; {{ $step2Done ? 'background: #16A34A; color: #fff; box-shadow: 0 0 0 4px #DCFCE7;' : 'background: #E2E8F0; color: #64748B;' }}">
                                {{ $step2Done ? '✓' : '2' }}
                            </div>
                            <span class="d-block fw-bold {{ $step2Done ? 'text-dark' : 'text-muted' }}" style="font-size: 0.8rem;">2. Validation</span>
                            <span class="text-muted" style="font-size: 0.72rem;">Prêteur</span>
                        </div>

                        <!-- Step 3: Check-in (Départ) -->
                        @php $step3Done = ($checkIn && $checkIn->is_validated); @endphp
                        <div class="text-center" style="flex: 1; position: relative;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; margin: 0 auto 0.5rem auto; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; {{ $step3Done ? 'background: #16A34A; color: #fff; box-shadow: 0 0 0 4px #DCFCE7;' : ($rental->status === 'confirmed' ? 'background: #FFB020; color: #071520; box-shadow: 0 0 0 4px #FEF3C7;' : 'background: #E2E8F0; color: #64748B;') }}">
                                {{ $step3Done ? '✓' : '3' }}
                            </div>
                            <span class="d-block fw-bold {{ $step3Done || $rental->status === 'confirmed' ? 'text-dark' : 'text-muted' }}" style="font-size: 0.8rem;">3. Check-in</span>
                            <span class="text-muted" style="font-size: 0.72rem;">Départ / Retrait</span>
                        </div>

                        <!-- Step 4: Check-out (Retour) -->
                        @php $step4Done = ($checkOut && $checkOut->is_validated); @endphp
                        <div class="text-center" style="flex: 1; position: relative;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; margin: 0 auto 0.5rem auto; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; {{ $step4Done ? 'background: #16A34A; color: #fff; box-shadow: 0 0 0 4px #DCFCE7;' : ($rental->status === 'active' ? 'background: #FFB020; color: #071520; box-shadow: 0 0 0 4px #FEF3C7;' : 'background: #E2E8F0; color: #64748B;') }}">
                                {{ $step4Done ? '✓' : '4' }}
                            </div>
                            <span class="d-block fw-bold {{ $step4Done || $rental->status === 'active' ? 'text-dark' : 'text-muted' }}" style="font-size: 0.8rem;">4. Check-out</span>
                            <span class="text-muted" style="font-size: 0.72rem;">Retour & Fin</span>
                        </div>

                    </div>
                </div>
            @else
                <div class="alert alert-danger rounded-3 mt-4 mb-0" role="alert">
                    <strong>Réservation annulée :</strong> {{ $rental->cancellation_reason ?: 'Aucune raison spécifiée.' }}
                </div>
            @endif

        </div>

        <div class="row g-4">
            
            <!-- Left Column: Equipment & Inspection Reports -->
            <div class="col-lg-7">
                
                <!-- Inspection Reports Box (1 - N Relationship) -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <span class="badge rounded-pill text-uppercase fw-bold px-3 py-1 mb-1" style="background: rgba(15, 92, 107, 0.12); color: #0F5C6B; font-size: 0.72rem;">
                                📝 Suivi du Matériel
                            </span>
                            <h4 class="h5 fw-bold text-dark mb-0">États des Lieux Numériques</h4>
                        </div>
                        <span class="badge bg-light text-muted border rounded-pill">
                            1 Location ── 2 Rapports
                        </span>
                    </div>

                    <p class="text-muted small mb-4">
                        Conformément au cycle de location SolarShare, chaque prêt comporte 2 états des lieux horodatés : 
                        un <strong>Check-in au départ</strong> et un <strong>Check-out au retour</strong>.
                    </p>

                    <div class="row g-3">
                        
                        <!-- Report 1: Check-in -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-4 border h-100 d-flex flex-column justify-content-between" style="background: {{ $checkIn && $checkIn->is_validated ? '#F0FDF4' : '#FFFFFF' }}; border-color: {{ $checkIn && $checkIn->is_validated ? '#BBF7D0' : '#E2E8F0' }} !important;">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold" style="color: #0F5C6B; font-size: 0.9rem;">
                                            🛫 Check-in (Départ)
                                        </span>
                                        @if($checkIn && $checkIn->is_validated)
                                            <span class="badge rounded-pill" style="background:#DCFCE7; color:#166534; font-size:0.7rem;">Validé</span>
                                        @elseif($checkIn)
                                            <span class="badge rounded-pill" style="background:#FEF3C7; color:#92400E; font-size:0.7rem;">Brouillon</span>
                                        @else
                                            <span class="badge rounded-pill" style="background:#F1F5F9; color:#94A3B8; font-size:0.7rem;">À faire</span>
                                        @endif
                                    </div>

                                    @if($checkIn)
                                        <div class="small text-muted mb-3" style="line-height: 1.6;">
                                            <div>🔋 Batterie : <strong>{{ $checkIn->battery_level_percent !== null ? $checkIn->battery_level_percent . '%' : 'N/A' }}</strong></div>
                                            <div>🏷️ État : <strong>{{ $checkIn->condition_label }}</strong></div>
                                            <div>🔌 Câbles : <strong>{{ $checkIn->has_all_cables ? 'Complets' : 'Manquants' }}</strong></div>
                                            @if($checkIn->photos && count($checkIn->photos))
                                                <div>📸 Photos : <strong>{{ count($checkIn->photos) }} photo(s)</strong></div>
                                            @endif
                                        </div>
                                    @else
                                        <p class="small text-muted mb-3">
                                            À réaliser au moment où le matériel est remis à l'emprunteur pour constater son bon fonctionnement initial.
                                        </p>
                                    @endif
                                </div>

                                <div>
                                    @if($checkIn)
                                        <a href="{{ route('inspections.show', $checkIn) }}" class="btn btn-outline-dark rounded-pill w-100 btn-sm fw-semibold">
                                            Consulter l'état des lieux &rarr;
                                        </a>
                                    @elseif($rental->canDoCheckIn())
                                        <a href="{{ route('inspections.create', ['rental' => $rental->id, 'type' => 'check_in']) }}" class="btn btn-primary rounded-pill w-100 btn-sm fw-bold" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520;">
                                            ⚡ Remplir le Check-in
                                        </a>
                                    @else
                                        <button class="btn btn-light border rounded-pill w-100 btn-sm text-muted" disabled>
                                            Disponible après validation
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Report 2: Check-out -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-4 border h-100 d-flex flex-column justify-content-between" style="background: {{ $checkOut && $checkOut->is_validated ? '#F0FDF4' : '#FFFFFF' }}; border-color: {{ $checkOut && $checkOut->is_validated ? '#BBF7D0' : '#E2E8F0' }} !important;">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold" style="color: #0F5C6B; font-size: 0.9rem;">
                                            🛬 Check-out (Retour)
                                        </span>
                                        @if($checkOut && $checkOut->is_validated)
                                            <span class="badge rounded-pill" style="background:#DCFCE7; color:#166534; font-size:0.7rem;">Validé</span>
                                        @elseif($checkOut)
                                            <span class="badge rounded-pill" style="background:#FEF3C7; color:#92400E; font-size:0.7rem;">Brouillon</span>
                                        @else
                                            <span class="badge rounded-pill" style="background:#F1F5F9; color:#94A3B8; font-size:0.7rem;">À faire</span>
                                        @endif
                                    </div>

                                    @if($checkOut)
                                        <div class="small text-muted mb-3" style="line-height: 1.6;">
                                            <div>🔋 Batterie : <strong>{{ $checkOut->battery_level_percent !== null ? $checkOut->battery_level_percent . '%' : 'N/A' }}</strong></div>
                                            <div>🏷️ État : <strong>{{ $checkOut->condition_label }}</strong></div>
                                            <div>🔌 Câbles : <strong>{{ $checkOut->has_all_cables ? 'Complets' : 'Manquants' }}</strong></div>
                                            @if($checkOut->photos && count($checkOut->photos))
                                                <div>📸 Photos : <strong>{{ count($checkOut->photos) }} photo(s)</strong></div>
                                            @endif
                                        </div>
                                    @else
                                        <p class="small text-muted mb-3">
                                            À réaliser lors de la restitution pour vérifier l'intégrité du matériel et libérer la caution.
                                        </p>
                                    @endif
                                </div>

                                <div>
                                    @if($checkOut)
                                        <a href="{{ route('inspections.show', $checkOut) }}" class="btn btn-outline-dark rounded-pill w-100 btn-sm fw-semibold">
                                            Consulter l'état des lieux &rarr;
                                        </a>
                                    @elseif($rental->canDoCheckOut())
                                        <a href="{{ route('inspections.create', ['rental' => $rental->id, 'type' => 'check_out']) }}" class="btn btn-success rounded-pill w-100 btn-sm fw-bold" style="background: #10B981; border: none; color: #fff;">
                                            🔄 Remplir le Check-out
                                        </a>
                                    @else
                                        <button class="btn btn-light border rounded-pill w-100 btn-sm text-muted" disabled>
                                            Disponible après Check-in
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Comparison Action if both exist -->
                    @if($checkIn && $checkOut)
                        <div class="mt-3 pt-3 border-top text-center">
                            <a href="{{ route('inspections.show', $checkOut) }}#comparison" class="btn btn-outline-primary rounded-pill btn-sm fw-bold">
                                🔍 Voir la comparaison complète Départ vs Retour
                            </a>
                        </div>
                    @endif

                </div>

                <!-- Equipment Details Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold mb-3" style="color: #10202B;">Matériel Objet du Contrat</h5>
                    
                    <div class="d-flex flex-wrap gap-4 align-items-center mb-3">
                        <div style="width: 110px; height: 110px; border-radius: 14px; background: #0F172A; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                            @if($rental->equipment?->image_url)
                                <img src="{{ $rental->equipment->image_url }}" alt="{{ $rental->equipment->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span style="font-size: 2.5rem;">☀️</span>
                            @endif
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <span class="badge bg-light text-muted border rounded-pill mb-1">
                                {{ $rental->equipment?->category?->name ?? 'Catégorie générale' }}
                            </span>
                            <h4 class="h5 fw-bold text-dark mb-1">{{ $rental->equipment?->name }}</h4>
                            <p class="text-muted small mb-2">Marque : <strong>{{ $rental->equipment?->brand ?: 'Non spécifiée' }}</strong></p>
                            
                            <div class="d-flex flex-wrap gap-2">
                                @if($rental->equipment?->power_watts)
                                    <span class="badge bg-light text-dark border">⚡ {{ $rental->equipment->power_watts }} Watts</span>
                                @endif
                                @if($rental->equipment?->capacity_wh)
                                    <span class="badge bg-light text-dark border">🔋 {{ $rental->equipment->capacity_wh }} Wh</span>
                                @endif
                                <a href="{{ route('marketplace.show', $rental->equipment) }}" class="badge bg-dark text-white text-decoration-none" target="_blank">
                                    Fiche Catalogue ↗
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($rental->renter_notes)
                        <div class="p-3 rounded-3 mt-2" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                            <span class="d-block text-muted small fw-bold mb-1">Notes du locataire :</span>
                            <div class="small text-dark" style="white-space: pre-line;">{{ $rental->renter_notes }}</div>
                        </div>
                    @endif

                </div>

            </div>

            <!-- Right Column: Parties, Dates & Financials -->
            <div class="col-lg-5">
                
                <!-- Financial Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold mb-3" style="color: #10202B;">Modalités Financières</h5>

                    <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, rgba(255, 176, 32, 0.12), rgba(255, 107, 61, 0.06)); border: 1px solid rgba(255, 176, 32, 0.3);">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Période du prêt :</span>
                            <strong class="text-dark">{{ $rental->start_date->format('d/m/Y') }} → {{ $rental->end_date->format('d/m/Y') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Durée totale :</span>
                            <strong class="text-dark">{{ $rental->duration_days }} jour(s)</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Tarif journalier :</span>
                            <strong class="text-dark">{{ number_format($rental->equipment->daily_rate, 2) }} TND / jour</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-baseline pt-2 border-top">
                            <span class="fw-bold text-dark">Montant Total :</span>
                            <span style="font-size: 1.8rem; font-weight: 800; color: #C2410C; line-height: 1;">
                                {{ number_format($rental->total_price, 2) }} <span style="font-size: 0.9rem; font-weight: 700;">TND</span>
                            </span>
                        </div>
                    </div>

                    <div class="p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 0.85rem;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted">Caution de garantie :</span>
                            <strong class="text-dark">{{ number_format($rental->equipment->deposit_amount, 2) }} TND</strong>
                        </div>
                        <span class="text-muted small d-block">
                            État caution : 
                            @if($rental->status === 'completed')
                                <strong class="text-success">Restituée intégralement après check-out</strong>
                            @elseif($rental->status === 'cancelled')
                                <strong class="text-muted">Non applicable (annulée)</strong>
                            @else
                                <strong class="text-warning">Garantie active (en attente du check-out)</strong>
                            @endif
                        </span>
                    </div>

                </div>

                <!-- Parties Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold mb-3" style="color: #10202B;">Parties au Contrat</h5>

                    <!-- Lender -->
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #FFB020, #FF6B3D); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0;">
                            {{ strtoupper(substr($rental->equipment->user?->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="badge bg-warning text-dark rounded-pill" style="font-size: 0.68rem;">Prêteur (Propriétaire)</span>
                                @if($isLender) <span class="badge bg-light text-muted border small">C'est vous</span> @endif
                            </div>
                            <strong class="d-block text-dark text-truncate mt-1">{{ $rental->equipment->user?->name ?? 'Membre SolarShare' }}</strong>
                            <span class="text-muted small text-truncate d-block">{{ $rental->equipment->user?->email }}</span>
                        </div>
                    </div>

                    <!-- Renter -->
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #0F5C6B, #083E49); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0;">
                            {{ strtoupper(substr($rental->user?->name ?? 'E', 0, 1)) }}
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="badge bg-info text-white rounded-pill" style="font-size: 0.68rem;">Emprunteur (Locataire)</span>
                                @if($isRenter) <span class="badge bg-light text-muted border small">C'est vous</span> @endif
                            </div>
                            <strong class="d-block text-dark text-truncate mt-1">{{ $rental->user?->name ?? 'Membre SolarShare' }}</strong>
                            <span class="text-muted small text-truncate d-block">{{ $rental->user?->email }}</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

<!-- Cancel Modal -->
@if($rental->canBeCancelled())
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('rentals.cancel', $rental) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="cancelModalLabel">Annuler la réservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body py-3">
                    <p class="text-muted small">
                        Êtes-vous sûr de vouloir annuler la réservation <strong>#LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</strong> ? 
                        Cette action mettra fin au contrat de location.
                    </p>
                    <div class="mb-3">
                        <label for="cancellation_reason" class="form-label fw-bold small text-dark">Motif d'annulation</label>
                        <textarea name="cancellation_reason" id="cancellation_reason" rows="3" class="form-control rounded-3" placeholder="Ex: Empêchement personnel, changement de dates..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Retour</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Confirmer l'annulation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
