{{-- resources/views/pages/admin/equipment/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'SolarShare Admin — Inventaire & Gestion des Équipements')

@section('content')

    <!-- Page Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-md-7 col-12">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    Inventaire & Gestion des Équipements
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">Équipements (Module 1)</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-5 col-12 text-md-end mt-3 mt-md-0">
                <a href="{{ route('marketplace') }}" target="_blank" class="btn btn-outline-info rounded-pill px-3 py-2 fw-bold me-2">
                    <i data-feather="external-link" style="width: 14px; height: 14px;" class="me-1"></i> Voir Marketplace
                </a>
            </div>
        </div>
    </div>

    <!-- Container fluid -->
    <div class="container-fluid">
        
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <i data-feather="check-circle" class="me-2" style="width: 18px; height: 18px;"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        <!-- KPI Row -->
        <div class="row">
            <!-- Total Equipments -->
            <div class="col-sm-6 col-lg-3">
                <div class="card border-end">
                    <div class="card-body">
                        <a href="{{ route('admin.equipment.index') }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="text-dark mb-1 font-weight-medium">{{ $stats['total'] }}</h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Équipements</h6>
                                </div>
                                <div class="ms-auto">
                                    <span class="text-primary opacity-7"><i data-feather="sun" style="width: 28px; height: 28px;"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pending Review -->
            <div class="col-sm-6 col-lg-3">
                <div class="card border-end">
                    <div class="card-body">
                        <a href="{{ route('admin.equipment.index', ['status' => 'pending']) }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="text-warning mb-1 font-weight-medium">{{ $stats['pending'] }}</h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">En attente (Non traité)</h6>
                                </div>
                                <div class="ms-auto">
                                    <span class="text-warning opacity-7"><i data-feather="clock" style="width: 28px; height: 28px;"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Approved -->
            <div class="col-sm-6 col-lg-3">
                <div class="card border-end">
                    <div class="card-body">
                        <a href="{{ route('admin.equipment.index', ['status' => 'approved']) }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="text-success mb-1 font-weight-medium">{{ $stats['approved'] }}</h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Validés (Approuvés)</h6>
                                </div>
                                <div class="ms-auto">
                                    <span class="text-success opacity-7"><i data-feather="check-circle" style="width: 28px; height: 28px;"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Available -->
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.equipment.index', ['availability' => '1']) }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="text-info mb-1 font-weight-medium">{{ $stats['available'] }}</h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Disponibles au prêt</h6>
                                </div>
                                <div class="ms-auto">
                                    <span class="text-info opacity-7"><i data-feather="zap" style="width: 28px; height: 28px;"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.equipment.index') }}" class="row g-3 align-items-end">
                    
                    <!-- Search input -->
                    <div class="col-lg-3 col-md-6">
                        <label for="search" class="form-label text-muted small fw-bold mb-1">Recherche</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i data-feather="search" style="width: 14px; height: 14px;"></i>
                            </span>
                            <input type="text" name="search" id="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Nom, marque, prêteur..." value="{{ $search }}">
                        </div>
                    </div>

                    <!-- Category filter -->
                    <div class="col-lg-2 col-md-6">
                        <label for="category_id" class="form-label text-muted small fw-bold mb-1">Catégorie</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->equipment_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status filter -->
                    <div class="col-lg-2 col-md-4">
                        <label for="status" class="form-label text-muted small fw-bold mb-1">Statut d'approbation</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>⏳ Non traité (En attente)</option>
                            <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>✅ Validé</option>
                            <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>❌ Refusé</option>
                        </select>
                    </div>

                    <!-- Availability filter -->
                    <div class="col-lg-2 col-md-4">
                        <label for="availability" class="form-label text-muted small fw-bold mb-1">Disponibilité</label>
                        <select name="availability" id="availability" class="form-select">
                            <option value="">Toutes</option>
                            <option value="1" {{ $availability === '1' ? 'selected' : '' }}>🟢 Disponible</option>
                            <option value="0" {{ $availability === '0' ? 'selected' : '' }}>🔴 Indisponible / Réservé</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="col-lg-2 col-md-4">
                        <label for="sort" class="form-label text-muted small fw-bold mb-1">Trier par</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Plus récents</option>
                            <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                            <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                        </select>
                    </div>

                    <!-- Submit & Reset Buttons -->
                    <div class="col-lg-1 col-md-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100" title="Filtrer" style="background: #5f76e8; border: none;">
                            <i data-feather="filter" style="width: 14px; height: 14px;"></i>
                        </button>
                        @if($search || $categoryId || $status || $availability !== null || $sort !== 'latest')
                            <a href="{{ route('admin.equipment.index') }}" class="btn btn-outline-secondary" title="Réinitialiser">
                                <i data-feather="x" style="width: 14px; height: 14px;"></i>
                            </a>
                        @endif
                    </div>

                </form>
            </div>
        </div>

        <!-- Equipment Table Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B;">
                            <tr>
                                <th class="ps-4 py-3">Équipement</th>
                                <th class="py-3">Propriétaire (Prêteur)</th>
                                <th class="py-3">Catégorie</th>
                                <th class="py-3">Tarif & Caution</th>
                                <th class="py-3 text-center">Disponibilité</th>
                                <th class="py-3 text-center">Statut</th>
                                <th class="py-3 text-center">Locations</th>
                                <th class="pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.88rem;">
                            @forelse($equipment as $item)
                                <tr style="cursor: pointer;" onclick="if (!event.target.closest('a, button, form')) { window.location='{{ route('admin.equipment.show', $item) }}'; }">
                                    
                                    <!-- Equipment Thumbnail & Info -->
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div style="width: 52px; height: 52px; border-radius: 10px; background: #0F172A; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                                @if($item->image_url)
                                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <span style="font-size: 1.4rem;">☀️</span>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.equipment.show', $item) }}" class="text-dark fw-bold text-decoration-none hover-primary d-block" style="font-size: 0.94rem;">
                                                    {{ $item->name }}
                                                </a>
                                                <span class="text-muted small">
                                                    {{ $item->brand }} 
                                                    @if($item->power_watts) • {{ $item->power_watts }}W @endif
                                                    @if($item->capacity_wh) • {{ $item->capacity_wh }}Wh @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Owner / Lender -->
                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #FFB020, #FF6B3D); color: #fff; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                {{ strtoupper(substr($item->user?->name ?? 'P', 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong class="d-block text-dark" style="font-size: 0.86rem;">
                                                    {{ $item->user?->name ?? 'Prêteur inconnu' }}
                                                </strong>
                                                <span class="text-muted small" style="font-size: 0.76rem;">
                                                    {{ $item->user?->email }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Category -->
                                    <td class="py-3">
                                        <span class="badge rounded-pill px-3 py-1" style="background: rgba(95, 118, 232, 0.1); color: #5f76e8; font-weight: 600;">
                                            {{ $item->category?->name ?? 'Non classé' }}
                                        </span>
                                    </td>

                                    <!-- Daily rate & Deposit -->
                                    <td class="py-3" style="white-space: nowrap;">
                                        <strong class="text-dark d-block">
                                            {{ number_format($item->daily_rate, 2) }} TND <span class="text-muted small fw-normal">/ jour</span>
                                        </strong>
                                        <span class="text-muted small" style="font-size: 0.78rem;">
                                            Caution : {{ number_format($item->deposit_amount, 2) }} TND
                                        </span>
                                    </td>

                                    <!-- Availability -->
                                    <td class="py-3 text-center">
                                        <form action="{{ route('admin.equipment.toggle-availability', $item) }}" method="POST" class="d-inline">
                                            @csrf
                                            @if($item->is_available)
                                                <button type="submit" class="badge rounded-pill border-0 px-3 py-1 text-white bg-success" style="cursor: pointer;" title="Cliquer pour marquer indisponible">
                                                    ✓ Disponible
                                                </button>
                                            @else
                                                <button type="submit" class="badge rounded-pill border-0 px-3 py-1 text-white bg-secondary" style="cursor: pointer;" title="Cliquer pour rendre disponible">
                                                    ✕ Indisponible
                                                </button>
                                            @endif
                                        </form>
                                    </td>

                                    <!-- Approval Status -->
                                    <td class="py-3 text-center">
                                        @if($item->status === 'approved')
                                            <span class="badge rounded-pill bg-success-subtle text-success px-3 py-1 fw-bold">
                                                Validé
                                            </span>
                                        @elseif($item->status === 'rejected')
                                            <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-1 fw-bold">
                                                Refusé
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-1 fw-bold">
                                                ⏳ Non traité
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Total rentals -->
                                    <td class="py-3 text-center">
                                        <span class="badge rounded-pill bg-light text-dark px-2 py-1 fw-semibold border">
                                            {{ $item->rentals_count }} location(s)
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="pe-4 py-3 text-end">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            
                                            <!-- View Details Button -->
                                            <a href="{{ route('admin.equipment.show', $item) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1" title="Voir la fiche détaillée">
                                                <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                                <span>Détails</span>
                                            </a>

                                            <!-- Quick Validation Dropdown -->
                                            <div class="dropdown d-inline">
                                                <button class="btn btn-sm btn-light rounded-circle p-2 text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions rapides">
                                                    <i data-feather="more-vertical" style="width: 14px; height: 14px;"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius: 10px;">
                                                    @if($item->status !== 'approved')
                                                        <li>
                                                            <form action="{{ route('admin.equipment.approve', $item) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-success fw-semibold d-flex align-items-center gap-2">
                                                                    <i data-feather="check" style="width: 14px; height: 14px;"></i> Valider l'équipement
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if($item->status !== 'rejected')
                                                        <li>
                                                            <form action="{{ route('admin.equipment.reject', $item) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-danger fw-semibold d-flex align-items-center gap-2">
                                                                    <i data-feather="x" style="width: 14px; height: 14px;"></i> Refuser l'équipement
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('marketplace.show', $item) }}" target="_blank">
                                                            <i data-feather="external-link" style="width: 14px; height: 14px;"></i> Voir page publique
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.equipment.destroy', $item) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cet équipement ? Cette action est irréversible.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                                                <i data-feather="trash-2" style="width: 14px; height: 14px;"></i> Supprimer
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <div class="py-4">
                                            <i data-feather="sun" class="text-muted mb-3" style="width: 48px; height: 48px; opacity: 0.35;"></i>
                                            <h5 class="fw-bold text-dark">Aucun équipement trouvé</h5>
                                            <p class="text-muted small mb-3">
                                                Aucun matériel ne correspond aux critères de recherche actuels.
                                            </p>
                                            <a href="{{ route('admin.equipment.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                                                Réinitialiser les filtres
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($equipment->hasPages())
                    <div class="p-3 border-top d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            Affichage de {{ $equipment->firstItem() }} à {{ $equipment->lastItem() }} sur {{ $equipment->total() }} équipements
                        </span>
                        <div>
                            {{ $equipment->links() }}
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection
