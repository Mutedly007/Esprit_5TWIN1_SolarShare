{{-- resources/views/lender/rentals/index.blade.php --}}
@extends('layouts.lender')

@section('title', 'Gestion des Prêts & Réservations — Espace Prêteur')

@section('content')
<div class="lender-page-content">

    <!-- Hero Greeting Bar -->
    <div class="lender-hero">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="sidebar-space-badge">
                    <span class="sidebar-pulse-dot"></span>
                    Espace Prêteur
                </span>
                <span style="font-size: 0.8rem; color: var(--ss-slate);">Module 2 : Cycle de Location</span>
            </div>
            <h1 class="hero-title">Réservations & Prêts de Matériel</h1>
            <p class="hero-subtitle mb-0">
                Gérez les demandes de location pour vos équipements solaires, validez les réservations et réalisez les états des lieux numériques.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('equipment.create') }}" class="btn-solar-primary" id="btn-add-equipment-top">
                <span>⚡ Ajouter du matériel</span>
            </a>
            <a href="{{ route('marketplace') }}" class="btn-solar-secondary" target="_blank">
                <span>Catalogue Public ↗</span>
            </a>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="kpi-grid mb-4" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem;">
        
        <!-- Total Requests -->
        <div class="kpi-card" style="background:#fff; border:1px solid var(--ss-border); border-radius:var(--ss-radius-md); padding:1.25rem; box-shadow:var(--ss-shadow-sm);">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Demandes</span>
            <div class="d-flex align-items-baseline justify-content-between">
                <strong class="fs-3 text-dark">{{ $stats['total'] }}</strong>
                <span style="font-size: 1.5rem;">📋</span>
            </div>
        </div>

        <!-- Pending Confirmation -->
        <div class="kpi-card" style="background:#fff; border:1px solid {{ $stats['pending'] > 0 ? '#FCD34D' : 'var(--ss-border)' }}; border-radius:var(--ss-radius-md); padding:1.25rem; box-shadow:var(--ss-shadow-sm);">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">À Valider</span>
            <div class="d-flex align-items-baseline justify-content-between">
                <strong class="fs-3" style="color: #D97706;">{{ $stats['pending'] }}</strong>
                <span style="font-size: 1.5rem;">⏳</span>
            </div>
        </div>

        <!-- Active Rentals -->
        <div class="kpi-card" style="background:#fff; border:1px solid var(--ss-border); border-radius:var(--ss-radius-md); padding:1.25rem; box-shadow:var(--ss-shadow-sm);">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Prêts en cours</span>
            <div class="d-flex align-items-baseline justify-content-between">
                <strong class="fs-3" style="color: #16A34A;">{{ $stats['active'] }}</strong>
                <span style="font-size: 1.5rem;">⚡</span>
            </div>
        </div>

        <!-- Revenue -->
        <div class="kpi-card" style="background:#fff; border:1px solid var(--ss-border); border-radius:var(--ss-radius-md); padding:1.25rem; box-shadow:var(--ss-shadow-sm);">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Revenus Cumulés</span>
            <div class="d-flex align-items-baseline justify-content-between">
                <strong class="fs-3" style="color: #C2410C;">{{ number_format($stats['revenue'], 2) }} <span style="font-size: 0.9rem;">TND</span></strong>
                <span style="font-size: 1.5rem;">💰</span>
            </div>
        </div>

    </div>

    <!-- Filter Buttons -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('lender.rentals.index') }}" 
           class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ empty($status) ? 'btn-dark text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
            Toutes ({{ $stats['total'] }})
        </a>
        <a href="{{ route('lender.rentals.index', ['status' => 'pending']) }}" 
           class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $status === 'pending' ? 'btn-warning text-dark' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
            ⏳ En attente ({{ $stats['pending'] }})
        </a>
        <a href="{{ route('lender.rentals.index', ['status' => 'confirmed']) }}" 
           class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $status === 'confirmed' ? 'btn-info text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
            ✅ Confirmées ({{ $stats['confirmed'] }})
        </a>
        <a href="{{ route('lender.rentals.index', ['status' => 'active']) }}" 
           class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $status === 'active' ? 'btn-primary text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
            ⚡ En cours ({{ $stats['active'] }})
        </a>
        <a href="{{ route('lender.rentals.index', ['status' => 'completed']) }}" 
           class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $status === 'completed' ? 'btn-success text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
            🎉 Terminées ({{ $stats['completed'] }})
        </a>
    </div>

    <!-- Rentals Table -->
    <div class="solar-table-wrap">
        @if($rentals->isEmpty())
            <div class="p-5 text-center bg-white">
                <div style="font-size: 3rem; margin-bottom: 0.75rem;">📋</div>
                <h5 class="fw-bold text-dark">Aucune réservation reçue</h5>
                <p class="text-muted small mx-auto mb-0" style="max-width: 420px;">
                    Vous n'avez pas encore de demande pour vos équipements dans cette vue. Dès qu'un emprunteur réservera votre matériel, elle s'affichera ici pour validation.
                </p>
            </div>
        @else
            <table class="solar-table">
                <thead>
                    <tr>
                        <th>Réf. Contrat</th>
                        <th>Locataire</th>
                        <th>Matériel Concerné</th>
                        <th>Période & Durée</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>États des Lieux</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
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
                            $checkIn = $rental->checkInReport;
                            $checkOut = $rental->checkOutReport;
                        @endphp
                        <tr>
                            <td>
                                <strong style="color: #0F5C6B; font-size: 0.88rem;">
                                    #LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}
                                </strong>
                                <span class="d-block text-muted" style="font-size: 0.75rem;">
                                    {{ $rental->created_at->format('d/m/Y') }}
                                </span>
                            </td>

                            <td>
                                <strong class="text-dark d-block">{{ $rental->user->name }}</strong>
                                <span class="text-muted small">{{ $rental->user->email }}</span>
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 38px; height: 38px; border-radius: 8px; background: #0F172A; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                        @if($rental->equipment?->image_url)
                                            <img src="{{ $rental->equipment->image_url }}" alt="{{ $rental->equipment->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <span style="font-size: 1rem;">☀️</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <strong class="text-dark d-block text-truncate" style="max-width: 180px;">{{ $rental->equipment->name }}</strong>
                                        <span class="text-muted small">{{ $rental->equipment->category?->name }}</span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div><strong>{{ $rental->start_date->format('d/m/Y') }}</strong> → <strong>{{ $rental->end_date->format('d/m/Y') }}</strong></div>
                                <span class="text-muted small">{{ $rental->duration_days }} jour(s)</span>
                            </td>

                            <td>
                                <strong style="color: #C2410C;">{{ number_format($rental->total_price, 2) }} TND</strong>
                                <span class="d-block text-muted" style="font-size: 0.75rem;">Caution : {{ number_format($rental->equipment->deposit_amount, 2) }} TND</span>
                            </td>

                            <td>
                                <span class="badge rounded-pill fw-bold" style="{{ $badgeStyle }} font-size: 0.76rem; padding: 0.35rem 0.65rem;">
                                    {{ $rental->status_label }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex gap-1 flex-column" style="font-size: 0.75rem;">
                                    <span class="{{ $checkIn && $checkIn->is_validated ? 'text-success fw-bold' : ($checkIn ? 'text-warning fw-bold' : 'text-muted') }}">
                                        Départ : {{ $checkIn && $checkIn->is_validated ? '✓ Validé' : ($checkIn ? '⏳ Brouillon' : '— À faire') }}
                                    </span>
                                    <span class="{{ $checkOut && $checkOut->is_validated ? 'text-success fw-bold' : ($checkOut ? 'text-warning fw-bold' : 'text-muted') }}">
                                        Retour : {{ $checkOut && $checkOut->is_validated ? '✓ Validé' : ($checkOut ? '⏳ Brouillon' : '— À faire') }}
                                    </span>
                                </div>
                            </td>

                            <td style="text-align: right;">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('rentals.show', $rental) }}" class="btn btn-sm btn-outline-dark rounded-pill px-2 py-1" title="Voir le contrat de location">
                                        👁️ Détails
                                    </a>

                                    @if($rental->status === 'pending')
                                        <form action="{{ route('rentals.confirm', $rental) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-2 py-1 fw-bold" title="Confirmer la réservation">
                                                ✓ Valider
                                            </button>
                                        </form>
                                    @endif

                                    @if($rental->canDoCheckIn())
                                        <a href="{{ route('inspections.create', ['rental' => $rental->id, 'type' => 'check_in']) }}" class="btn btn-sm btn-warning rounded-pill px-2 py-1 fw-bold" style="background:#FFB020; border:none; color:#071520;" title="Remplir le check-in de départ">
                                            ⚡ Check-in
                                        </a>
                                    @elseif($rental->canDoCheckOut())
                                        <a href="{{ route('inspections.create', ['rental' => $rental->id, 'type' => 'check_out']) }}" class="btn btn-sm btn-success rounded-pill px-2 py-1 fw-bold" style="background:#10B981; border:none; color:#fff;" title="Remplir le check-out de retour">
                                            🔄 Check-out
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $rentals->links() }}
    </div>

</div>
@endsection
