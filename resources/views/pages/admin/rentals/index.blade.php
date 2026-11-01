{{-- resources/views/pages/admin/rentals/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'SolarShare Admin — Gestion des Locations & États des Lieux')

@section('content')

    <!-- Page Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    Gestion des Locations & Contrats
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Locations (Module 2)</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center">
                <div class="customize-input float-end">
                    <span class="badge rounded-pill text-white px-3 py-2" style="background: linear-gradient(135deg, #5f76e8, #7b8ff5); font-size: 0.82rem;">
                        Cycle de Location & États des lieux
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        
        <!-- KPI Row -->
        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="card border-end">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $stats['total'] }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Contrats</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-muted opacity-7"><i data-feather="clipboard" class="text-muted"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card border-end">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="text-warning mb-1 font-weight-medium">{{ $stats['pending'] }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">En attente</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-muted opacity-7"><i data-feather="clock" class="text-warning"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card border-end">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="text-primary mb-1 font-weight-medium">{{ $stats['active'] }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Prêts en cours</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-muted opacity-7"><i data-feather="activity" class="text-primary"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="text-success mb-1 font-weight-medium">{{ number_format($stats['total_volume'], 2) }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Volume TND</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-muted opacity-7"><i data-feather="dollar-sign" class="text-success"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search Row -->
        <div class="card mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.rentals.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="q" class="form-control" placeholder="Rechercher par équipement, locataire ou email..." value="{{ $search }}">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>⏳ En attente</option>
                            <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>✅ Confirmée</option>
                            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>⚡ En cours</option>
                            <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>🎉 Terminée</option>
                            <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>❌ Annulée</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        @if($search || $status)
                            <a href="{{ route('admin.rentals.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Rentals Table Card -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Toutes les réservations de la plateforme</h4>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Réf. Contrat</th>
                                <th>Équipement</th>
                                <th>Prêteur</th>
                                <th>Locataire</th>
                                <th>Dates & Durée</th>
                                <th>Montant Total</th>
                                <th>Statut</th>
                                <th>États des Lieux</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rentals as $rental)
                                @php
                                    $badgeClass = match($rental->status) {
                                        'pending' => 'bg-warning text-dark',
                                        'confirmed' => 'bg-info text-white',
                                        'active' => 'bg-primary text-white',
                                        'completed' => 'bg-success text-white',
                                        'cancelled' => 'bg-danger text-white',
                                        default => 'bg-secondary text-white',
                                    };
                                    $checkIn = $rental->checkInReport;
                                    $checkOut = $rental->checkOutReport;
                                @endphp
                                <tr>
                                    <td>
                                        <strong class="text-primary">#LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</strong>
                                        <span class="d-block text-muted small">{{ $rental->created_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $rental->equipment?->name ?? 'N/A' }}</strong>
                                        <span class="d-block text-muted small">{{ $rental->equipment?->category?->name }}</span>
                                    </td>
                                    <td>{{ $rental->equipment?->user?->name ?? 'N/A' }}</td>
                                    <td>
                                        <strong>{{ $rental->user->name }}</strong>
                                        <span class="d-block text-muted small">{{ $rental->user->email }}</span>
                                    </td>
                                    <td>
                                        <div>{{ $rental->start_date->format('d/m/Y') }} → {{ $rental->end_date->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $rental->duration_days }} jour(s)</small>
                                    </td>
                                    <td>
                                        <strong class="text-dark">{{ number_format($rental->total_price, 2) }} TND</strong>
                                    </td>
                                    <td>
                                        <span class="badge {{ $badgeClass }} rounded-pill px-2 py-1">
                                            {{ $rental->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>Départ : <span class="{{ $checkIn && $checkIn->is_validated ? 'text-success fw-bold' : ($checkIn ? 'text-warning' : 'text-muted') }}">{{ $checkIn && $checkIn->is_validated ? '✓ Fait' : ($checkIn ? 'Brouillon' : 'Non') }}</span></div>
                                            <div>Retour : <span class="{{ $checkOut && $checkOut->is_validated ? 'text-success fw-bold' : ($checkOut ? 'text-warning' : 'text-muted') }}">{{ $checkOut && $checkOut->is_validated ? '✓ Fait' : ($checkOut ? 'Brouillon' : 'Non') }}</span></div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('rentals.show', $rental) }}" class="btn btn-sm btn-outline-dark" target="_blank" title="Consulter le contrat">
                                                👁️
                                            </a>
                                            @if($rental->status === 'pending')
                                                <form action="{{ route('rentals.confirm', $rental) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Valider la réservation">
                                                        ✓
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('rentals.destroy', $rental) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer définitivement cette location ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">
                                        Aucune location enregistrée.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $rentals->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
