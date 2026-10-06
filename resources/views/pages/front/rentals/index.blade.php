{{-- resources/views/pages/front/rentals/index.blade.php --}}
@extends('layouts.solartec')

@section('title', 'Mes Réservations & Prêts — SolarShare')
@section('meta_description', 'Suivi de vos réservations et contrats de location de matériel solaire et écologique.')

@section('content')
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0; min-height: 80vh;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('marketplace') }}" style="color: #0F5C6B; text-decoration: none;">Marketplace</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">Mes Réservations</li>
            </ol>
        </nav>

        <!-- Header Bar -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 p-4 rounded-4 bg-white shadow-sm border">
            <div>
                <span class="badge rounded-pill text-uppercase fw-bold px-3 py-2 mb-2" style="background: rgba(255, 176, 32, 0.15); color: #C2410C; font-size: 0.75rem; letter-spacing: 0.05em;">
                    ⚡ Cycle de Location & Prêt
                </span>
                <h1 class="h3 fw-bold mb-1" style="color: #10202B;">Mes Réservations de Matériel</h1>
                <p class="text-muted mb-0 small">
                    Gérez vos contrats de location, suivez les dates et effectuez les états des lieux numériques avant et après utilisation.
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('marketplace') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2" style="font-size: 0.9rem;">
                    🔍 Explorer le Catalogue
                </a>
                @if(auth()->user()->isRenter())
                    <a href="{{ route('rentals.create') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520; font-size: 0.9rem; box-shadow: 0 4px 14px rgba(255, 107, 61, 0.3);">
                        + Nouvelle Réservation
                    </a>
                @endif
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('rentals.index') }}" 
               class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ empty($status) ? 'btn-dark text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
                Toutes ({{ $counts['all'] }})
            </a>
            <a href="{{ route('rentals.index', ['status' => 'pending']) }}" 
               class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $status === 'pending' ? 'btn-warning text-dark' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
                ⏳ En attente ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('rentals.index', ['status' => 'confirmed']) }}" 
               class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $status === 'confirmed' ? 'btn-info text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
                ✅ Confirmées ({{ $counts['confirmed'] }})
            </a>
            <a href="{{ route('rentals.index', ['status' => 'active']) }}" 
               class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $status === 'active' ? 'btn-primary text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
                ⚡ En cours ({{ $counts['active'] }})
            </a>
            <a href="{{ route('rentals.index', ['status' => 'completed']) }}" 
               class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $status === 'completed' ? 'btn-success text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
                🎉 Terminées ({{ $counts['completed'] }})
            </a>
            <a href="{{ route('rentals.index', ['status' => 'cancelled']) }}" 
               class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $status === 'cancelled' ? 'btn-danger text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
                ❌ Annulées ({{ $counts['cancelled'] }})
            </a>
        </div>

        @if($rentals->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
                <div style="font-size: 3.5rem; margin-bottom: 1rem;">📋</div>
                <h4 class="fw-bold text-dark mb-2">Aucune réservation trouvée</h4>
                <p class="text-muted mx-auto mb-4" style="max-width: 480px;">
                    Vous n'avez pas encore de réservation dans cette catégorie. Découvrez nos équipements solaires disponibles et réservez dès aujourd'hui !
                </p>
                <div>
                    <a href="{{ route('marketplace') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520;">
                        ⚡ Parcourir les équipements
                    </a>
                </div>
            </div>
        @else
            <!-- Rentals Grid -->
            <div class="row g-4">
                @foreach($rentals as $rental)
                    @php
                        $badgeStyle = match($rental->status) {
                            'pending' => 'background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D;',
                            'confirmed' => 'background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD;',
                            'active' => 'background: #DCFCE7; color: #166534; border: 1px solid #BBF7D0;',
                            'completed' => 'background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0;',
                            'cancelled' => 'background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA;',
                            default => 'background: #F1F5F9; color: #475569;',
                        };
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden d-flex flex-column" style="transition: transform 0.2s, box-shadow 0.2s;">
                            
                            <!-- Header with Equipment & Status -->
                            <div class="p-3 border-bottom d-flex align-items-center justify-content-between" style="background: #FAFBFD;">
                                <span style="font-size: 0.78rem; font-weight: 700; color: #64748B;">
                                    #LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="badge rounded-pill fw-bold" style="{{ $badgeStyle }} font-size: 0.76rem; padding: 0.35rem 0.75rem;">
                                    {{ $rental->status_label }}
                                </span>
                            </div>

                            <!-- Body -->
                            <div class="p-4 flex-grow-1">
                                <div class="d-flex gap-3 mb-3">
                                    <div style="width: 70px; height: 70px; border-radius: 12px; background: #0F172A; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                        @if($rental->equipment?->image_url)
                                            <img src="{{ $rental->equipment->image_url }}" alt="{{ $rental->equipment->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <span style="font-size: 1.8rem;">☀️</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <span class="d-block text-muted small fw-bold text-uppercase" style="letter-spacing: 0.04em;">
                                            {{ $rental->equipment?->category?->name ?? 'Énergie' }}
                                        </span>
                                        <h5 class="fw-bold mb-1 text-truncate" style="color: #10202B;" title="{{ $rental->equipment?->name }}">
                                            {{ $rental->equipment?->name ?? 'Équipement supprimé' }}
                                        </h5>
                                        <span class="text-muted small">
                                            Par <strong>{{ $rental->equipment?->user?->name ?? 'Prêteur' }}</strong>
                                        </span>
                                    </div>
                                </div>

                                <!-- Date & Price Info -->
                                <div class="p-3 rounded-3 mb-3" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 0.88rem;">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">📅 Période :</span>
                                        <strong>{{ $rental->start_date->format('d/m/Y') }} → {{ $rental->end_date->format('d/m/Y') }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">⏱️ Durée :</span>
                                        <strong>{{ $rental->duration_days }} jour(s)</strong>
                                    </div>
                                    <div class="d-flex justify-content-between pt-2 border-top">
                                        <span class="text-muted">💰 Montant total :</span>
                                        <strong style="color: #C2410C; font-size: 1.05rem;">{{ number_format($rental->total_price, 2) }} TND</strong>
                                    </div>
                                </div>

                                <!-- Inspection Reports status indicators -->
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between small text-muted mb-1">
                                        <span>États des lieux (Check-in / Check-out)</span>
                                        <span>
                                            {{ $rental->inspectionReports->count() }} / 2
                                        </span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        @php
                                            $checkIn = $rental->checkInReport;
                                            $checkOut = $rental->checkOutReport;
                                        @endphp
                                        <div class="flex-grow-1 p-2 rounded-2 text-center" style="font-size: 0.75rem; font-weight: 600; {{ $checkIn && $checkIn->is_validated ? 'background:#DCFCE7; color:#166534;' : ($checkIn ? 'background:#FEF3C7; color:#92400E;' : 'background:#F1F5F9; color:#94A3B8;') }}">
                                            Check-in : {{ $checkIn && $checkIn->is_validated ? 'Validé' : ($checkIn ? 'Brouillon' : 'En attente') }}
                                        </div>
                                        <div class="flex-grow-1 p-2 rounded-2 text-center" style="font-size: 0.75rem; font-weight: 600; {{ $checkOut && $checkOut->is_validated ? 'background:#DCFCE7; color:#166534;' : ($checkOut ? 'background:#FEF3C7; color:#92400E;' : 'background:#F1F5F9; color:#94A3B8;') }}">
                                            Check-out : {{ $checkOut && $checkOut->is_validated ? 'Validé' : ($checkOut ? 'Brouillon' : 'En attente') }}
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Card Footer Action -->
                            <div class="p-3 bg-white border-top d-flex gap-2 justify-content-between align-items-center">
                                <a href="{{ route('rentals.show', $rental) }}" class="btn btn-outline-dark rounded-pill flex-grow-1 btn-sm fw-semibold">
                                    Détails & Contrat &rarr;
                                </a>

                                @if($rental->canBeEdited())
                                    <a href="{{ route('rentals.edit', $rental) }}" class="btn btn-outline-secondary rounded-pill btn-sm" title="Modifier les dates">
                                        ✏️ Dates
                                    </a>
                                @endif

                                @if($rental->canDoCheckIn())
                                    <a href="{{ route('inspections.create', ['rental' => $rental->id, 'type' => 'check_in']) }}" class="btn btn-warning rounded-pill btn-sm fw-bold" style="background:#FFB020; border:none; color:#071520;">
                                        ⚡ Check-in
                                    </a>
                                @elseif($rental->canDoCheckOut())
                                    <a href="{{ route('inspections.create', ['rental' => $rental->id, 'type' => 'check_out']) }}" class="btn btn-success rounded-pill btn-sm fw-bold" style="background:#10B981; border:none; color:#ffffff;">
                                        🔄 Check-out
                                    </a>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $rentals->links() }}
            </div>
        @endif

    </div>
</section>
@endsection
