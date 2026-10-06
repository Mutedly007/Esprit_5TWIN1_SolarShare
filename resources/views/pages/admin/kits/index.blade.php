{{-- resources/views/pages/admin/kits/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'SolarShare Admin — Packs Solaires Clé-en-main')

@section('content')

    <!-- Page Breadcrumb -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    Gestion des Packs Solaires Clé-en-main
                </h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Packs Solaires (Module 3)</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-5 align-self-center text-end">
                <a href="{{ route('energy-kits.create') }}" class="btn btn-primary rounded-pill px-3 py-2 fw-bold" style="background: linear-gradient(135deg, #5f76e8, #7b8ff5); border: none;">
                    + Nouveau Pack
                </a>
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
                        <a href="{{ route('admin.energy-kits.index') }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="text-dark mb-1 font-weight-medium">{{ $stats['total'] }}</h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Packs</h6>
                                </div>
                                <div class="ms-auto">
                                    <span class="text-muted opacity-7"><i data-feather="package" class="text-muted"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card border-end">
                    <div class="card-body">
                        <a href="{{ route('admin.energy-kits.index', ['status' => 'active']) }}" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="text-success mb-1 font-weight-medium">
                                        {{ $stats['active'] }} 
                                        @if($stats['inactive'] > 0)
                                            <span class="badge bg-secondary fs-6 text-white align-middle ms-1" title="{{ $stats['inactive'] }} pack(s) inactif(s)">{{ $stats['inactive'] }} inactif</span>
                                        @endif
                                    </h2>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Packs Actifs</h6>
                                </div>
                                <div class="ms-auto">
                                    <span class="text-muted opacity-7"><i data-feather="check-circle" class="text-success"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card border-end">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="text-warning mb-1 font-weight-medium">{{ $stats['avg_discount'] }}%</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Remise Moyenne</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-muted opacity-7"><i data-feather="percent" class="text-warning"></i></span>
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
                                <h2 class="text-primary mb-1 font-weight-medium">{{ $stats['total_bundled_items'] }}</h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Articles Packagés</h6>
                            </div>
                            <div class="ms-auto">
                                <span class="text-muted opacity-7"><i data-feather="layers" class="text-primary"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Row -->
        <div class="card mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.energy-kits.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="q" id="admin_kit_search" class="form-control" placeholder="Rechercher par titre ou description de pack (ex: Vanlife)..." value="{{ $search }}" autocomplete="off">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Tous statuts ({{ $stats['total'] }})</option>
                            <option value="active" {{ $status === 'active' ? 'selected' : '' }}>🟢 Actifs ({{ $stats['active'] }})</option>
                            <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>⚪ Inactifs ({{ $stats['inactive'] }})</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="target_use" class="form-select">
                            <option value="">Tous les usages</option>
                            <option value="vanlife" {{ $targetUse === 'vanlife' ? 'selected' : '' }}>🚐 Vanlife & Voyage</option>
                            <option value="camping" {{ $targetUse === 'camping' ? 'selected' : '' }}>⛺ Camping</option>
                            <option value="chantier" {{ $targetUse === 'chantier' ? 'selected' : '' }}>🔨 Chantier</option>
                            <option value="urgence" {{ $targetUse === 'urgence' ? 'selected' : '' }}>🚨 Secours</option>
                            <option value="evenement" {{ $targetUse === 'evenement' ? 'selected' : '' }}>🎉 Événementiel</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        @if($search || $targetUse || $status)
                            <a href="{{ route('admin.energy-kits.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Packs d'énergie renouvelable configurés</h4>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="admin_kits_table">
                        <thead class="table-light">
                            <tr>
                                <th>Pack</th>
                                <th>Usage Cible</th>
                                <th>Remise</th>
                                <th>Équipements Inclus</th>
                                <th>Tarif Brut / Remisé</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kits as $kit)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 44px; height: 44px; border-radius: 8px; background: #0F172A; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                                @if($kit->image_url)
                                                    <img src="{{ $kit->image_url }}" alt="{{ $kit->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <span style="font-size: 1.2rem;">📦</span>
                                                @endif
                                            </div>
                                            <div>
                                                <strong class="text-dark d-block">{{ $kit->title }}</strong>
                                                <small class="text-muted">Slug : {{ $kit->slug }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge rounded-pill" style="{{ $kit->target_use_badge_style }}; font-size: 0.78rem;">
                                            {{ $kit->target_use_label }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($kit->discount_percentage > 0)
                                            <span class="badge bg-warning text-dark fw-bold">
                                                -{{ rtrim(rtrim(number_format($kit->discount_percentage, 1), '0'), '.') }}%
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted">0%</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge bg-secondary rounded-pill me-1">{{ $kit->equipment_count }} équipement(s)</span>
                                        <div class="small text-muted mt-1">
                                            @foreach($kit->equipment->take(2) as $eq)
                                                <span>{{ $eq->pivot->quantity }}x {{ Str::limit($eq->name, 18) }}, </span>
                                            @endforeach
                                            @if($kit->equipment->count() > 2)
                                                <span>...</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <div>
                                            <strong class="text-dark">{{ number_format($kit->discounted_daily_rate, 2) }} TND/j</strong>
                                        </div>
                                        @if($kit->discount_percentage > 0)
                                            <small class="text-muted text-decoration-line-through">{{ number_format($kit->raw_daily_rate, 2) }} TND</small>
                                        @endif
                                    </td>

                                    <td>
                                        <form action="{{ route('admin.energy-kits.toggle', $kit) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm rounded-pill {{ $kit->is_active ? 'btn-success' : 'btn-secondary' }}" style="font-size: 0.75rem;">
                                                {{ $kit->is_active ? 'Actif' : 'Inactif' }}
                                            </button>
                                        </form>
                                    </td>

                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('energy-kits.show', $kit) }}" class="btn btn-sm btn-outline-dark" target="_blank" title="Voir sur le site public">
                                                👁️
                                            </a>
                                            <a href="{{ route('energy-kits.edit', $kit) }}" class="btn btn-sm btn-outline-primary" title="Modifier le pack">
                                                ✏️
                                            </a>
                                            <form action="{{ route('energy-kits.destroy', $kit) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce pack ?');">
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
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        Aucun pack solaire configuré pour le moment.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $kits->links() }}
                </div>
            </div>
        </div>

    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('admin_kit_search');
    const table = document.getElementById('admin_kits_table');
    
    if (searchInput && table) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const rows = table.querySelectorAll('tbody tr');
            let matchCount = 0;
            
            rows.forEach(function(row) {
                // If it's a message row like "Aucun pack...", skip
                if (row.querySelector('td[colspan]')) return;
                
                const text = row.innerText.toLowerCase();
                if (query === '' || text.indexOf(query) !== -1) {
                    row.style.display = '';
                    matchCount++;
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>
@endpush
@endsection
