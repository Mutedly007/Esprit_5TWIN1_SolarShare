{{-- resources/views/pages/admin/hubs/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'SolarShare Admin — Hubs de Retrait Locaux & Créneaux')

@section('content')
<div class="page-wrapper">
    
    <!-- Page Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    Gestion des Hubs de Retrait & Créneaux (Module 4)
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Hubs Relais & Plages Horaires</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center text-end">
                <a href="{{ route('hubs.index') }}" target="_blank" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-bold me-2">
                    <i data-feather="external-link" style="width: 16px; height: 16px;"></i> Vue Publique
                </a>
                <a href="{{ route('admin.hubs.create') }}" class="btn btn-primary rounded-pill px-3 py-2 fw-bold" style="background: linear-gradient(135deg, #5f76e8, #7b8ff5); border: none;">
                    + Nouveau Hub Relais
                </a>
            </div>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        
        {{-- Flash Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <i data-feather="check-circle" class="me-2" style="width: 18px; height: 18px;"></i>
                <strong>Succès !</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- KPI Row -->
        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="card border-end">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="text-dark mb-1 font-weight-medium">{{ $stats['total_hubs'] }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Hubs Relais</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-primary opacity-7"><i data-feather="map-pin" style="width: 32px; height: 32px;"></i></span>
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
                                <h2 class="text-success mb-1 font-weight-medium">{{ $stats['active_hubs'] }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Hubs Opérationnels</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-success opacity-7"><i data-feather="check-circle" style="width: 32px; height: 32px;"></i></span>
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
                                <h2 class="text-warning mb-1 font-weight-medium">{{ $stats['total_capacity'] }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Capacité Casier / Matériel</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-warning opacity-7"><i data-feather="box" style="width: 32px; height: 32px;"></i></span>
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
                                <h2 class="text-info mb-1 font-weight-medium">{{ $stats['total_slots'] }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Créneaux Configurés</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-info opacity-7"><i data-feather="clock" style="width: 32px; height: 32px;"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.hubs.index') }}" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i data-feather="search" style="width: 16px; height: 16px;"></i></span>
                            <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0 ps-0" placeholder="Rechercher nom, adresse, ville...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="city" class="form-select">
                            <option value="">Toutes les villes</option>
                            @foreach($cities as $c)
                                <option value="{{ $c }}" {{ $city === $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Actifs uniquement</option>
                            <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactifs</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100 rounded-pill">Filtrer</button>
                        @if($search || $city || $status !== null)
                            <a href="{{ route('admin.hubs.index') }}" class="btn btn-outline-secondary rounded-pill" title="Réinitialiser">
                                <i data-feather="rotate-ccw" style="width: 14px; height: 14px;"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Hubs Table Card -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="card-title mb-0">Répertoire des Points Relais Partenaires</h4>
                    <span class="badge bg-light text-dark">{{ $hubs->total() }} Hub(s) trouvé(s)</span>
                </div>

                @if($hubs->isEmpty())
                    <div class="text-center py-5">
                        <i data-feather="inbox" class="text-muted mb-2" style="width: 48px; height: 48px;"></i>
                        <h5 class="text-muted">Aucun point relais ne correspond à vos critères.</h5>
                        <a href="{{ route('admin.hubs.create') }}" class="btn btn-sm btn-primary rounded-pill mt-2">+ Créer le premier Hub</a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nom du Hub Relais</th>
                                    <th>Ville & Adresse</th>
                                    <th>Contact</th>
                                    <th>Capacité</th>
                                    <th>Plages Horaires</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hubs as $hub)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.hubs.show', $hub) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $hub->name }}
                                            </a>
                                            @if($hub->description)
                                                <small class="text-muted d-block text-truncate" style="max-width: 250px;">
                                                    {{ $hub->description }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary fw-bold">{{ $hub->city }}</span>
                                            <small class="d-block text-muted">{{ $hub->postal_code }} — {{ $hub->address }}</small>
                                        </td>
                                        <td>
                                            <span class="text-nowrap"><i data-feather="phone" style="width: 13px; height: 13px;"></i> {{ $hub->contact_phone }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $hub->max_capacity }} équipements
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.hubs.show', $hub) }}" class="badge bg-info text-white text-decoration-none" title="Gérer les créneaux">
                                                <i data-feather="calendar" style="width: 12px; height: 12px;"></i> {{ $hub->operating_slots_count }} créneau(x)
                                            </a>
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.hubs.toggle', $hub) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm rounded-pill {{ $hub->is_active ? 'btn-success' : 'btn-outline-danger' }}" style="font-size: 0.75rem;" title="Cliquer pour changer le statut">
                                                    {{ $hub->is_active ? 'Actif' : 'Inactif' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <a href="{{ route('admin.hubs.show', $hub) }}" class="btn btn-sm btn-outline-info rounded-pill me-1" title="Voir les créneaux & détails">
                                                <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                            </a>
                                            <a href="{{ route('admin.hubs.edit', $hub) }}" class="btn btn-sm btn-outline-primary rounded-pill me-1" title="Modifier le hub">
                                                <i data-feather="edit-2" style="width: 14px; height: 14px;"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteHubModal-{{ $hub->id }}" title="Supprimer">
                                                <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                            </button>

                                            <!-- Delete Modal Confirmation -->
                                            <div class="modal fade" id="deleteHubModal-{{ $hub->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content text-start">
                                                        <div class="modal-header border-0 pb-0">
                                                            <h5 class="modal-title fw-bold text-danger">Confirmer la suppression</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-muted">
                                                            Êtes-vous sûr de vouloir supprimer définitivement le point relais <strong>« {{ $hub->name }} »</strong> ?
                                                            <div class="alert alert-warning mt-3 mb-0" style="font-size: 0.85rem;">
                                                                <i data-feather="alert-triangle" style="width: 14px; height: 14px;"></i>
                                                                Attention : Tous les <strong>{{ $hub->operating_slots_count }} créneau(x) horaires</strong> associés seront également supprimés en cascade.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0">
                                                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Annuler</button>
                                                            <form method="POST" action="{{ route('admin.hubs.destroy', $hub) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger rounded-pill">Supprimer définitivement</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $hubs->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
