{{-- resources/views/pages/admin/users/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'SolarShare Admin — Gestion des Utilisateurs')

@section('content')

    <!-- Page Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-md-7 col-12">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    Gestion des Utilisateurs
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">Utilisateurs</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-5 col-12 text-md-end mt-3 mt-md-0">
                <span class="badge rounded-pill text-white px-3 py-2 fw-semibold" style="background: linear-gradient(135deg, #5f76e8, #7b8ff5); font-size: 0.85rem;">
                    {{ $stats['total'] }} Comptes Enregistrés
                </span>
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px;">
                <i data-feather="alert-circle" class="me-2" style="width: 18px; height: 18px;"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        <!-- KPI Row -->
        <div class="row">
            <!-- Total Users -->
            <div class="col-sm-6 col-lg-3">
                <div class="card border-end">
                    <div class="card-body">
                        <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="text-dark mb-1 font-weight-medium">{{ $stats['total'] }}</h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Membres</h6>
                                </div>
                                <div class="ms-auto">
                                    <span class="text-primary opacity-7"><i data-feather="users" style="width: 28px; height: 28px;"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lenders (Lend + Both) -->
            <div class="col-sm-6 col-lg-3">
                <div class="card border-end">
                    <div class="card-body">
                        <a href="{{ route('admin.users.index', ['solar_role' => 'lend']) }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="text-warning mb-1 font-weight-medium">{{ $stats['lenders'] }}</h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Prêteurs (Lend & Both)</h6>
                                </div>
                                <div class="ms-auto">
                                    <span class="text-warning opacity-7"><i data-feather="sun" style="width: 28px; height: 28px;"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Renters (Rent + Both) -->
            <div class="col-sm-6 col-lg-3">
                <div class="card border-end">
                    <div class="card-body">
                        <a href="{{ route('admin.users.index', ['solar_role' => 'rent']) }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="text-info mb-1 font-weight-medium">{{ $stats['renters'] }}</h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Emprunteurs (Rent & Both)</h6>
                                </div>
                                <div class="ms-auto">
                                    <span class="text-info opacity-7"><i data-feather="zap" style="width: 28px; height: 28px;"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Admins -->
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.users.index', ['solar_role' => 'admin']) }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="text-danger mb-1 font-weight-medium">{{ $stats['admins'] }}</h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Administrateurs</h6>
                                </div>
                                <div class="ms-auto">
                                    <span class="text-danger opacity-7"><i data-feather="shield" style="width: 28px; height: 28px;"></i></span>
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
                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
                    
                    <!-- Search input -->
                    <div class="col-lg-4 col-md-6">
                        <label for="search" class="form-label text-muted small fw-bold mb-1">Recherche</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i data-feather="search" style="width: 14px; height: 14px;"></i>
                            </span>
                            <input type="text" name="search" id="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Nom ou adresse email..." value="{{ $search }}">
                        </div>
                    </div>

                    <!-- Role filter -->
                    <div class="col-lg-3 col-md-6">
                        <label for="solar_role" class="form-label text-muted small fw-bold mb-1">Rôle SolarShare</label>
                        <select name="solar_role" id="solar_role" class="form-select">
                            <option value="">Tous les rôles</option>
                            <option value="both" {{ $solarRole === 'both' ? 'selected' : '' }}>🔄 Mixte (Lend & Rent)</option>
                            <option value="lend" {{ $solarRole === 'lend' ? 'selected' : '' }}>☀️ Prêteur (Lend uniquement)</option>
                            <option value="rent" {{ $solarRole === 'rent' ? 'selected' : '' }}>⚡ Emprunteur (Rent uniquement)</option>
                            <option value="admin" {{ $solarRole === 'admin' ? 'selected' : '' }}>🛡️ Administrateur</option>
                        </select>
                    </div>

                    <!-- Provider filter -->
                    <div class="col-lg-2 col-md-4">
                        <label for="provider" class="form-label text-muted small fw-bold mb-1">Connexion</label>
                        <select name="provider" id="provider" class="form-select">
                            <option value="">Toutes</option>
                            <option value="email" {{ $provider === 'email' ? 'selected' : '' }}>📧 Email</option>
                            <option value="google" {{ $provider === 'google' ? 'selected' : '' }}>🔴 Google</option>
                            <option value="github" {{ $provider === 'github' ? 'selected' : '' }}>🐙 GitHub</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div class="col-lg-2 col-md-4">
                        <label for="sort" class="form-label text-muted small fw-bold mb-1">Trier par</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Plus récents</option>
                            <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                            <option value="equipment_desc" {{ $sort === 'equipment_desc' ? 'selected' : '' }}>Matériel déposé (↓)</option>
                            <option value="rentals_desc" {{ $sort === 'rentals_desc' ? 'selected' : '' }}>Réservations (↓)</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Plus anciens</option>
                        </select>
                    </div>

                    <!-- Submit & Reset Buttons -->
                    <div class="col-lg-1 col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100" title="Filtrer" style="background: #5f76e8; border: none;">
                            <i data-feather="filter" style="width: 14px; height: 14px;"></i>
                        </button>
                        @if($search || $solarRole || $provider || $sort !== 'latest')
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Réinitialiser">
                                <i data-feather="x" style="width: 14px; height: 14px;"></i>
                            </a>
                        @endif
                    </div>

                </form>
            </div>
        </div>

        <!-- Users Table Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B;">
                            <tr>
                                <th class="ps-4 py-3">Utilisateur</th>
                                <th class="py-3">Email & Auth</th>
                                <th class="py-3">Rôle Plateforme</th>
                                <th class="py-3 text-center">Matériel Déposé</th>
                                <th class="py-3 text-center">Réservations</th>
                                <th class="py-3">Inscrit le</th>
                                <th class="pe-4 py-3 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.88rem;">
                            @forelse($users as $user)
                                <tr style="cursor: pointer;" onclick="if (!event.target.closest('a, button, form, select')) { window.location='{{ route('admin.users.show', $user) }}'; }">
                                    
                                    <!-- User Avatar & Name -->
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div style="width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #FFB020, #FF6B3D); color: #fff; font-size: 1.05rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                                @if($user->avatar)
                                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                                @else
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.users.show', $user) }}" class="text-dark fw-bold text-decoration-none hover-primary d-block" style="font-size: 0.94rem;">
                                                    {{ $user->name }}
                                                </a>
                                                @if($user->id === auth()->id())
                                                    <span class="badge bg-primary text-white" style="font-size: 0.68rem; padding: 0.15rem 0.45rem;">Vous</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Email & Provider -->
                                    <td class="py-3">
                                        <span class="d-block text-dark fw-semibold" style="font-size: 0.88rem;">
                                            {{ $user->email }}
                                        </span>
                                        <span class="badge rounded-pill text-muted border bg-light small" style="font-size: 0.7rem;">
                                            @if($user->provider === 'google')
                                                🔴 Google OAuth
                                            @elseif($user->provider === 'github')
                                                🐙 GitHub OAuth
                                            @else
                                                📧 Email Direct
                                            @endif
                                        </span>
                                    </td>

                                    <!-- Solar Role -->
                                    <td class="py-3" style="white-space: nowrap;">
                                        @if($user->isAdmin())
                                            <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-1 fw-bold">
                                                🛡️ Administrateur
                                            </span>
                                        @elseif($user->solar_role === 'both')
                                            <span class="badge rounded-pill px-3 py-1 fw-bold text-white" style="background: linear-gradient(135deg, #0F5C6B, #071520);">
                                                🔄 Mixte (Lend & Rent)
                                            </span>
                                        @elseif($user->solar_role === 'lend')
                                            <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-1 fw-bold">
                                                ☀️ Prêteur (Lend)
                                            </span>
                                        @elseif($user->solar_role === 'rent')
                                            <span class="badge rounded-pill bg-info-subtle text-info px-3 py-1 fw-bold">
                                                ⚡ Emprunteur (Rent)
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary text-white px-3 py-1">
                                                Non configuré
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Equipment Count -->
                                    <td class="py-3 text-center">
                                        @if($user->equipment_count > 0)
                                            <a href="{{ route('admin.equipment.index', ['search' => $user->email]) }}" class="badge rounded-pill bg-light text-primary border px-3 py-1 fw-bold text-decoration-none">
                                                ☀️ {{ $user->equipment_count }} équipement(s)
                                            </a>
                                        @else
                                            <span class="text-muted small">0</span>
                                        @endif
                                    </td>

                                    <!-- Rentals Count -->
                                    <td class="py-3 text-center">
                                        @if($user->rentals_count > 0)
                                            <a href="{{ route('admin.rentals.index', ['q' => $user->email]) }}" class="badge rounded-pill bg-light text-dark border px-3 py-1 fw-bold text-decoration-none">
                                                📋 {{ $user->rentals_count }} location(s)
                                            </a>
                                        @else
                                            <span class="text-muted small">0</span>
                                        @endif
                                    </td>

                                    <!-- Created At -->
                                    <td class="py-3 text-muted small" style="white-space: nowrap;">
                                        {{ $user->created_at->format('d/m/Y') }}<br>
                                        <span class="text-muted" style="font-size: 0.75rem;">{{ $user->created_at->diffForHumans() }}</span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="pe-4 py-3 text-end" style="white-space: nowrap;">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            
                                            <!-- View Details Button -->
                                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1" title="Voir profil & activités">
                                                <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                                <span>Détails</span>
                                            </a>

                                            <!-- Role Modification Dropdown -->
                                            <div class="dropdown d-inline">
                                                <button class="btn btn-sm btn-light rounded-circle p-2 text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Changer le rôle">
                                                    <i data-feather="more-vertical" style="width: 14px; height: 14px;"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2" style="border-radius: 12px; min-width: 220px;">
                                                    <li class="dropdown-header text-uppercase small fw-bold text-muted pb-1">Changer le rôle SolarShare</li>
                                                    
                                                    <li>
                                                        <form action="{{ route('admin.users.update-role', $user) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="solar_role" value="both">
                                                            <button type="submit" class="dropdown-item py-2 rounded d-flex align-items-center gap-2 {{ $user->solar_role === 'both' ? 'active fw-bold' : '' }}">
                                                                <span>🔄</span> <span>Mixte (Lend & Rent)</span>
                                                            </button>
                                                        </form>
                                                    </li>

                                                    <li>
                                                        <form action="{{ route('admin.users.update-role', $user) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="solar_role" value="lend">
                                                            <button type="submit" class="dropdown-item py-2 rounded d-flex align-items-center gap-2 {{ $user->solar_role === 'lend' ? 'active fw-bold' : '' }}">
                                                                <span>☀️</span> <span>Prêteur (Lend)</span>
                                                            </button>
                                                        </form>
                                                    </li>

                                                    <li>
                                                        <form action="{{ route('admin.users.update-role', $user) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="solar_role" value="rent">
                                                            <button type="submit" class="dropdown-item py-2 rounded d-flex align-items-center gap-2 {{ $user->solar_role === 'rent' ? 'active fw-bold' : '' }}">
                                                                <span>⚡</span> <span>Emprunteur (Rent)</span>
                                                            </button>
                                                        </form>
                                                    </li>

                                                    <li>
                                                        <form action="{{ route('admin.users.update-role', $user) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="solar_role" value="admin">
                                                            <button type="submit" class="dropdown-item py-2 rounded d-flex align-items-center gap-2 text-danger {{ $user->isAdmin() ? 'active fw-bold' : '' }}">
                                                                <span>🛡️</span> <span>Promouvoir Admin</span>
                                                            </button>
                                                        </form>
                                                    </li>

                                                    @if($user->id !== auth()->id())
                                                        <li><hr class="dropdown-divider my-2"></li>
                                                        <li>
                                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cet utilisateur et toutes ses données associées ?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger py-2 rounded d-flex align-items-center gap-2">
                                                                    <i data-feather="trash-2" style="width: 14px; height: 14px;"></i> Supprimer le compte
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <div class="py-4">
                                            <i data-feather="users" class="text-muted mb-3" style="width: 48px; height: 48px; opacity: 0.35;"></i>
                                            <h5 class="fw-bold text-dark">Aucun utilisateur trouvé</h5>
                                            <p class="text-muted small mb-3">
                                                Aucun compte ne correspond à ces critères de recherche.
                                            </p>
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">
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
                @if($users->hasPages())
                    <div class="p-3 border-top d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs
                        </span>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection
