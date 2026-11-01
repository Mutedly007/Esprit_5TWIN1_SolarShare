{{-- resources/views/pages/admin/equipment/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'SolarShare Admin — Fiche Détail : ' . $equipment->name)

@section('content')

    <!-- Page Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-md-7 col-12">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-0">
                        {{ $equipment->name }}
                    </h3>
                    <span class="badge rounded-pill text-white px-3 py-1 font-12" style="background: linear-gradient(135deg, #5f76e8, #7b8ff5);">
                        #EQP-{{ str_pad($equipment->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.equipment.index') }}">Équipements</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">{{ $equipment->name }}</li>
                    </ol>
                </nav>
            </div>

            <!-- Top Actions -->
            <div class="col-md-5 col-12 text-md-end mt-3 mt-md-0 d-flex flex-wrap justify-content-md-end gap-2">
                <a href="{{ route('admin.equipment.index') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold">
                    &larr; Retour à l'inventaire
                </a>
                <a href="{{ route('marketplace.show', $equipment) }}" target="_blank" class="btn btn-outline-info rounded-pill px-3 py-2 fw-semibold">
                    <i data-feather="external-link" style="width: 14px; height: 14px;" class="me-1"></i> Fiche publique
                </a>
            </div>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px;">
                <i data-feather="check-circle" class="me-2" style="width: 18px; height: 18px;"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        <!-- Quick Status & Moderation Action Banner -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(95, 118, 232, 0.1); display: flex; align-items: center; justify-content: center; color: #5f76e8;">
                        <i data-feather="shield" style="width: 22px; height: 22px;"></i>
                    </div>
                    <div>
                        <strong class="text-dark d-block" style="font-size: 1.05rem;">Statut d'approbation & Disponibilité</strong>
                        <span class="text-muted small">
                            Modérez ce matériel pour autoriser ou restreindre sa visibilité et sa réservation sur la plateforme SolarShare.
                        </span>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <!-- Approve Button -->
                    @if($equipment->status !== 'approved')
                        <form action="{{ route('admin.equipment.approve', $equipment) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1 shadow-sm">
                                <i data-feather="check-circle" style="width: 15px; height: 15px;"></i>
                                <span>Valider l'équipement</span>
                            </button>
                        </form>
                    @endif

                    <!-- Reject Button -->
                    @if($equipment->status !== 'rejected')
                        <form action="{{ route('admin.equipment.reject', $equipment) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1" onsubmit="return confirm('Marquer cet équipement comme refusé ?');">
                                <i data-feather="x-circle" style="width: 15px; height: 15px;"></i>
                                <span>Refuser</span>
                            </button>
                        </form>
                    @endif

                    <!-- Toggle Availability -->
                    <form action="{{ route('admin.equipment.toggle-availability', $equipment) }}" method="POST" class="d-inline">
                        @csrf
                        @if($equipment->is_available)
                            <button type="submit" class="btn btn-outline-warning rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1">
                                <i data-feather="eye-off" style="width: 15px; height: 15px;"></i>
                                <span>Rendre indisponible</span>
                            </button>
                        @else
                            <button type="submit" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1">
                                <i data-feather="eye" style="width: 15px; height: 15px;"></i>
                                <span>Rendre disponible</span>
                            </button>
                        @endif
                    </form>

                    <!-- Delete Button -->
                    <form action="{{ route('admin.equipment.destroy', $equipment) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer définitivement cet équipement ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-light text-danger rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1" title="Supprimer définitivement">
                            <i data-feather="trash-2" style="width: 15px; height: 15px;"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row g-4">
            
            <!-- Left Column: Image, Price, Owner & Category -->
            <div class="col-lg-4">
                
                <!-- Card 1: Equipment Visual & Badges -->
                <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                    <div style="height: 240px; background: #0F172A; display: flex; align-items: center; justify-content: center; position: relative;">
                        @if($equipment->image_url)
                            <img src="{{ $equipment->image_url }}" alt="{{ $equipment->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="text-center text-muted">
                                <span style="font-size: 3.5rem;">☀️</span>
                                <span class="d-block text-white small mt-2">Aucune photo fournie</span>
                            </div>
                        @endif

                        <!-- Floating Status Badges -->
                        <div style="position: absolute; top: 12px; left: 12px;">
                            <span class="badge rounded-pill px-3 py-2 text-white fw-bold shadow-sm" style="background: rgba(16, 32, 43, 0.85); backdrop-filter: blur(8px); font-size: 0.8rem;">
                                {{ $equipment->category?->name ?? 'Général' }}
                            </span>
                        </div>

                        <div style="position: absolute; top: 12px; right: 12px; display: flex; flex-direction: column; gap: 6px; align-items: flex-end;">
                            @if($equipment->status === 'approved')
                                <span class="badge rounded-pill bg-success text-white px-3 py-1 fw-bold shadow-sm">
                                    ✓ Validé
                                </span>
                            @elseif($equipment->status === 'rejected')
                                <span class="badge rounded-pill bg-danger text-white px-3 py-1 fw-bold shadow-sm">
                                    ✕ Refusé
                                </span>
                            @else
                                <span class="badge rounded-pill bg-warning text-dark px-3 py-1 fw-bold shadow-sm">
                                    ⏳ Non traité
                                </span>
                            @endif

                            @if($equipment->is_available)
                                <span class="badge rounded-pill bg-info text-white px-3 py-1 fw-bold shadow-sm">
                                    ● Disponible
                                </span>
                            @else
                                <span class="badge rounded-pill bg-secondary text-white px-3 py-1 fw-bold shadow-sm">
                                    ● Indisponible
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="p-3 rounded-3 text-center mb-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                            <span class="text-muted small fw-bold d-block text-uppercase" style="letter-spacing: 0.05em;">Tarif de Location</span>
                            <div class="d-flex align-items-baseline justify-content-center gap-1 my-1">
                                <span style="font-size: 2.2rem; font-weight: 800; color: #10202B; line-height: 1;">
                                    {{ number_format($equipment->daily_rate, 2) }}
                                </span>
                                <span class="text-muted fw-bold">TND / jour</span>
                            </div>
                            <span class="text-muted small">
                                Caution de garantie : <strong class="text-dark">{{ number_format($equipment->deposit_amount, 2) }} TND</strong>
                            </span>
                        </div>

                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item px-0 d-flex justify-content-between text-muted">
                                <span>Référence interne :</span>
                                <strong class="text-dark">#EQP-{{ str_pad($equipment->id, 5, '0', STR_PAD_LEFT) }}</strong>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between text-muted">
                                <span>Date de soumission :</span>
                                <strong class="text-dark">{{ $equipment->created_at->format('d/m/Y') }}</strong>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between text-muted">
                                <span>Dernière modification :</span>
                                <strong class="text-dark">{{ $equipment->updated_at->diffForHumans() }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Card 2: Propriétaire (Prêteur) Profile -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h6 class="card-title fw-bold text-dark text-uppercase small mb-3" style="letter-spacing: 0.05em;">
                            Propriétaire / Prêteur
                        </h6>

                        <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                            <div style="width: 52px; height: 52px; border-radius: 50%; background: linear-gradient(135deg, #FFB020, #FF6B3D); color: #fff; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 10px rgba(255, 107, 61, 0.3);">
                                {{ strtoupper(substr($equipment->user?->name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 1rem;">
                                    {{ $equipment->user?->name ?? 'Prêteur non identifié' }}
                                </h6>
                                <span class="text-muted small d-block">
                                    {{ $equipment->user?->email }}
                                </span>
                                <div class="mt-1">
                                    <span class="badge rounded-pill text-uppercase px-2 py-1" style="background: rgba(15, 92, 107, 0.1); color: #0F5C6B; font-size: 0.7rem; font-weight: 700;">
                                        Rôle : {{ $equipment->user?->solar_role ?? $equipment->user?->role ?? 'lend' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="small text-muted">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Membre depuis :</span>
                                <strong class="text-dark">{{ $equipment->user?->created_at?->format('M Y') ?? 'N/A' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Total matériel déposé :</span>
                                <strong class="text-dark">{{ $equipment->user?->equipment()->count() ?? 1 }} équipement(s)</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Catégorie Associée -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="card-title fw-bold text-dark text-uppercase small mb-3" style="letter-spacing: 0.05em;">
                            Catégorie
                        </h6>
                        <div class="p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">
                                ☀️ {{ $equipment->category?->name ?? 'Non classé' }}
                            </div>
                            <p class="text-muted small mb-2">
                                {{ $equipment->category?->description ?? 'Aucune description de catégorie.' }}
                            </p>
                            <span class="badge bg-light text-muted border small">
                                slug: {{ $equipment->category?->slug ?? 'n-a' }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Specs, Description, Rentals & Packs -->
            <div class="col-lg-8">
                
                <!-- Card 4: Fiche Technique & Description -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div>
                                <span class="badge text-uppercase fw-bold px-2 py-1 mb-1" style="background: rgba(95, 118, 232, 0.1); color: #5f76e8; font-size: 0.72rem;">
                                    {{ $equipment->brand }}
                                </span>
                                <h4 class="fw-bold text-dark mb-0">{{ $equipment->name }}</h4>
                            </div>
                            <span class="text-muted small">
                                ID: #{{ $equipment->id }}
                            </span>
                        </div>

                        <!-- Technical Specs Grid -->
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6 col-md-3">
                                <div class="p-3 rounded-3 text-center" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                    <span class="text-muted small d-block">Marque</span>
                                    <strong class="text-dark d-block" style="font-size: 1.05rem;">
                                        {{ $equipment->brand ?: 'Non spécifiée' }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="p-3 rounded-3 text-center" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                    <span class="text-muted small d-block">Puissance Crête</span>
                                    <strong class="text-dark d-block" style="font-size: 1.05rem;">
                                        @if($equipment->power_watts)
                                            ⚡ {{ $equipment->power_watts }} W
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </strong>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="p-3 rounded-3 text-center" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                    <span class="text-muted small d-block">Capacité Batterie</span>
                                    <strong class="text-dark d-block" style="font-size: 1.05rem;">
                                        @if($equipment->capacity_wh)
                                            🔋 {{ $equipment->capacity_wh }} Wh
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </strong>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="p-3 rounded-3 text-center" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                    <span class="text-muted small d-block">Caution de Garantie</span>
                                    <strong class="text-dark d-block" style="font-size: 1.05rem;">
                                        🛡️ {{ number_format($equipment->deposit_amount, 2) }} TND
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Description -->
                        <h6 class="fw-bold text-dark text-uppercase small mb-2" style="letter-spacing: 0.05em;">
                            Description détaillée
                        </h6>
                        <div class="p-3 rounded-3 mb-0" style="background: #F8FAFC; border: 1px solid #E2E8F0; line-height: 1.7; font-size: 0.92rem; color: #334155;">
                            {{ $equipment->description ?: 'Aucune description rédigée pour cet équipement.' }}
                        </div>

                    </div>
                </div>

                <!-- Card 5: Activité de Location (Module 2) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold text-dark mb-0">
                                <i data-feather="clipboard" class="me-2 text-primary" style="width: 18px; height: 18px;"></i>
                                Historique des Locations ({{ $rentalStats['total'] }})
                            </h5>
                            <span class="badge rounded-pill bg-light text-dark border px-3 py-1">
                                Volume total généré : {{ number_format($rentalStats['revenue'], 2) }} TND
                            </span>
                        </div>

                        <!-- Mini Stats Bar -->
                        <div class="row g-2 mb-3 text-center">
                            <div class="col-4">
                                <div class="p-2 rounded border bg-light">
                                    <span class="text-muted small d-block">En cours</span>
                                    <strong class="text-primary">{{ $rentalStats['active'] }}</strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded border bg-light">
                                    <span class="text-muted small d-block">Terminées</span>
                                    <strong class="text-success">{{ $rentalStats['completed'] }}</strong>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded border bg-light">
                                    <span class="text-muted small d-block">Total Contrats</span>
                                    <strong class="text-dark">{{ $rentalStats['total'] }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Rentals Table -->
                        @if($equipment->rentals->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th># Contrat</th>
                                            <th>Locataire</th>
                                            <th>Période</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($equipment->rentals as $rental)
                                            <tr>
                                                <td class="fw-bold text-dark">
                                                    #LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}
                                                </td>
                                                <td>
                                                    <strong class="d-block">{{ $rental->user?->name ?? 'Inconnu' }}</strong>
                                                    <span class="text-muted small">{{ $rental->user?->email }}</span>
                                                </td>
                                                <td>
                                                    Du {{ $rental->start_date->format('d/m/Y') }}<br>
                                                    au {{ $rental->end_date->format('d/m/Y') }} ({{ $rental->duration_in_days }}j)
                                                </td>
                                                <td class="fw-bold text-dark">
                                                    {{ number_format($rental->total_price, 2) }} TND
                                                </td>
                                                <td>
                                                    @if($rental->status === 'confirmed')
                                                        <span class="badge bg-info text-white">Confirmée</span>
                                                    @elseif($rental->status === 'active')
                                                        <span class="badge bg-primary text-white">En cours</span>
                                                    @elseif($rental->status === 'completed')
                                                        <span class="badge bg-success text-white">Terminée</span>
                                                    @elseif($rental->status === 'cancelled')
                                                        <span class="badge bg-danger text-white">Annulée</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">En attente</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('rentals.show', $rental) }}" class="btn btn-sm btn-outline-dark rounded-pill px-2 py-1" target="_blank" title="Consulter le contrat">
                                                        <i data-feather="file-text" style="width: 12px; height: 12px;"></i> Contrat
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted bg-light rounded-3">
                                <i data-feather="inbox" class="mb-2" style="width: 32px; height: 32px; opacity: 0.4;"></i>
                                <p class="mb-0 small">Aucune location n'a encore été enregistrée pour cet équipement.</p>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Card 6: Packs Solaires Associés (Module 3) -->
                @if($equipment->energyKits->isNotEmpty())
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-dark mb-3">
                                <i data-feather="package" class="me-2 text-primary" style="width: 18px; height: 18px;"></i>
                                Intégré dans les Packs Solaires ({{ $equipment->energyKits->count() }})
                            </h5>
                            <div class="row g-3">
                                @foreach($equipment->energyKits as $kit)
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-3 border d-flex align-items-center justify-content-between" style="background: #F8FAFC;">
                                            <div>
                                                <strong class="d-block text-dark">{{ $kit->title }}</strong>
                                                <span class="text-muted small">Remise : -{{ $kit->discount_percentage }}% • Qté : {{ $kit->pivot->quantity ?? 1 }}</span>
                                            </div>
                                            <a href="{{ route('energy-kits.show', $kit) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" target="_blank">
                                                Voir pack &rarr;
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </div>
@endsection
