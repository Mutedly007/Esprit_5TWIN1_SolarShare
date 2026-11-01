{{-- resources/views/pages/admin/users/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'SolarShare Admin — Profil Utilisateur : ' . $user->name)

@section('content')

    <!-- Page Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-md-7 col-12">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-0">
                        {{ $user->name }}
                    </h3>
                    <span class="badge rounded-pill text-white px-3 py-1 font-12" style="background: linear-gradient(135deg, #5f76e8, #7b8ff5);">
                        Utilisateur #{{ $user->id }}
                    </span>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">{{ $user->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-5 col-12 text-md-end mt-3 mt-md-0">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold">
                    &larr; Retour à la liste
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

        <div class="row g-4">
            
            <!-- Left Column: User Profile Card & Role Modification -->
            <div class="col-lg-4">
                
                <!-- Profile Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4 text-center">
                        <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #FFB020, #FF6B3D); color: #fff; font-size: 2rem; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(255, 107, 61, 0.35); margin-bottom: 1rem;">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            @else
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            @endif
                        </div>

                        <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
                        <span class="text-muted d-block small mb-2">{{ $user->email }}</span>

                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <span class="badge rounded-pill px-3 py-1 text-white fw-bold" style="background: linear-gradient(135deg, #0F5C6B, #071520); font-size: 0.8rem;">
                                {{ $user->solar_role_label }}
                            </span>
                            @if($user->isAdmin())
                                <span class="badge rounded-pill bg-danger text-white px-2 py-1 small fw-bold">Admin</span>
                            @endif
                        </div>

                        <ul class="list-group list-group-flush text-start small border-top pt-2">
                            <li class="list-group-item px-0 d-flex justify-content-between text-muted">
                                <span>Méthode de connexion :</span>
                                <strong class="text-dark">
                                    @if($user->provider === 'google')
                                        🔴 Google
                                    @elseif($user->provider === 'github')
                                        🐙 GitHub
                                    @else
                                        📧 Email & Mot de passe
                                    @endif
                                </strong>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between text-muted">
                                <span>Membre depuis :</span>
                                <strong class="text-dark">{{ $user->created_at->format('d/m/Y à H:i') }}</strong>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between text-muted">
                                <span>Dernière mise à jour :</span>
                                <strong class="text-dark">{{ $user->updated_at->diffForHumans() }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Role Modification Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="card-title fw-bold text-dark text-uppercase small mb-3" style="letter-spacing: 0.05em;">
                            Modifier le Rôle de l'utilisateur
                        </h6>

                        <form action="{{ route('admin.users.update-role', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="solar_role" class="form-label text-muted small fw-bold">Rôle SolarShare</label>
                                <select name="solar_role" id="solar_role" class="form-select">
                                    <option value="both" {{ $user->solar_role === 'both' ? 'selected' : '' }}>🔄 Mixte (Lend & Rent)</option>
                                    <option value="lend" {{ $user->solar_role === 'lend' ? 'selected' : '' }}>☀️ Prêteur (Lend uniquement)</option>
                                    <option value="rent" {{ $user->solar_role === 'rent' ? 'selected' : '' }}>⚡ Emprunteur (Rent uniquement)</option>
                                    <option value="admin" {{ ($user->solar_role === 'admin' || $user->role === 'admin') ? 'selected' : '' }}>🛡️ Administrateur</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold" style="background: #5f76e8; border: none;">
                                Enregistrer les modifications
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Right Column: Activities, Equipment & Rentals -->
            <div class="col-lg-8">
                
                <!-- Activity KPI Summary -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 text-center bg-white shadow-sm border-0">
                            <span class="text-muted small d-block">Équipements Déposés</span>
                            <strong class="text-primary d-block" style="font-size: 1.6rem; font-weight: 800;">
                                {{ $user->equipment_count }}
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 text-center bg-white shadow-sm border-0">
                            <span class="text-muted small d-block">Réservations Empruntées</span>
                            <strong class="text-dark d-block" style="font-size: 1.6rem; font-weight: 800;">
                                {{ $user->rentals_count }}
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 text-center bg-white shadow-sm border-0">
                            <span class="text-muted small d-block">Prêts Reçus (Prêteur)</span>
                            <strong class="text-success d-block" style="font-size: 1.6rem; font-weight: 800;">
                                {{ $lentRentalsCount }}
                            </strong>
                        </div>
                    </div>
                </div>

                <!-- Card: Equipment published by this user -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <h5 class="fw-bold text-dark mb-0">
                                <i data-feather="sun" class="me-2 text-warning" style="width: 18px; height: 18px;"></i>
                                Matériel déposé par cet utilisateur ({{ $user->equipment->count() }})
                            </h5>
                        </div>

                        @if($user->equipment->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.88rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Équipement</th>
                                            <th>Catégorie</th>
                                            <th>Tarif</th>
                                            <th class="text-center">Statut</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->equipment as $eq)
                                            <tr>
                                                <td class="fw-bold text-dark">
                                                    {{ $eq->name }}
                                                    <span class="text-muted small d-block">{{ $eq->brand }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge rounded-pill bg-light text-muted border">
                                                        {{ $eq->category?->name ?? 'Général' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong>{{ number_format($eq->daily_rate, 2) }} TND/j</strong>
                                                </td>
                                                <td class="text-center">
                                                    @if($eq->status === 'approved')
                                                        <span class="badge bg-success text-white">Validé</span>
                                                    @elseif($eq->status === 'rejected')
                                                        <span class="badge bg-danger text-white">Refusé</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">En attente</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.equipment.show', $eq) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1">
                                                        <i data-feather="eye" style="width: 12px; height: 12px;"></i> Fiche
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
                                <p class="mb-0 small">Cet utilisateur n'a pas encore mis d'équipement en location.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Card: Rentals made by this user -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <h5 class="fw-bold text-dark mb-0">
                                <i data-feather="clipboard" class="me-2 text-info" style="width: 18px; height: 18px;"></i>
                                Réservations en tant qu'emprunteur ({{ $user->rentals->count() }})
                            </h5>
                        </div>

                        @if($user->rentals->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.88rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th># Contrat</th>
                                            <th>Équipement loué</th>
                                            <th>Période</th>
                                            <th>Montant</th>
                                            <th>Statut</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->rentals as $rental)
                                            <tr>
                                                <td class="fw-bold text-dark">
                                                    #LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}
                                                </td>
                                                <td>
                                                    <strong>{{ $rental->equipment?->name ?? 'Équipement supprimé' }}</strong>
                                                </td>
                                                <td>
                                                    Du {{ $rental->start_date->format('d/m/Y') }}<br>
                                                    au {{ $rental->end_date->format('d/m/Y') }}
                                                </td>
                                                <td class="fw-bold">
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
                                                    <a href="{{ route('rentals.show', $rental) }}" class="btn btn-sm btn-outline-dark rounded-pill px-2 py-1" target="_blank">
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
                                <i data-feather="clipboard" class="mb-2" style="width: 32px; height: 32px; opacity: 0.4;"></i>
                                <p class="mb-0 small">Cet utilisateur n'a pas encore effectué de réservation.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
