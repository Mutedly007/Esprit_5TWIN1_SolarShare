{{-- resources/views/pages/front/hubs/index.blade.php --}}
@extends('layouts.solartec')

@section('title', 'Points Relais & Hubs Solaires — SolarShare')
@section('meta_description', 'Trouvez les points relais de quartier et casiers solaires sécurisés pour retirer ou déposer du matériel en toute sérénité sans partager votre adresse.')

@section('content')
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0; min-height: 80vh;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">Accueil</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">Points Relais & Casiers Solaires</li>
            </ol>
        </nav>

        <!-- Header Card -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 p-4 p-md-5 rounded-4 bg-white shadow-sm border">
            <div style="max-width: 680px;">
                <span class="badge rounded-pill text-uppercase fw-bold px-3 py-2 mb-2" style="background: rgba(15, 92, 107, 0.12); color: #0F5C6B; font-size: 0.75rem; letter-spacing: 0.05em;">
                    📍 Réseau Éco-Logistique de Quartier
                </span>
                <h1 class="h3 fw-bold mb-2" style="color: #10202B;">Points Relais & Casiers Solaires</h1>
                <p class="text-muted mb-0 small" style="line-height: 1.6;">
                    Échangez vos batteries nomades et panneaux solaires en toute sécurité sans divulguer votre domicile. Nos partenaires de quartier et casiers connectés sécurisés garantissent des retraits et dépôts rapides sur des plages horaires flexibles.
                </p>
            </div>
            <div class="d-flex flex-column align-items-md-end gap-2">
                <div class="d-flex gap-2">
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small">
                        <strong>{{ $stats['total_hubs'] }}</strong> Hubs Actifs
                    </span>
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small">
                        <strong>{{ $stats['cities_count'] }}</strong> Villes Couvertes
                    </span>
                </div>
                <small class="text-muted">Capacité globale de <strong>{{ $stats['total_capacity'] }}</strong> emplacements</small>
                @if(auth()->check() && auth()->user()->isAdmin())
                    <div class="d-flex gap-2 mt-1">
                        <a href="{{ route('admin.hubs.create') }}" class="btn btn-sm btn-primary rounded-pill px-3 py-2 fw-bold" style="background: linear-gradient(135deg, #0F5C6B, #38BDF8); border: none;">
                            + Nouveau Hub Relais
                        </a>
                        <a href="{{ route('admin.hubs.index') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-2 fw-semibold">
                            ⚙️ Espace Admin
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3 p-md-4">
                <form method="GET" action="{{ route('hubs.index') }}" class="row g-3 align-items-center">
                    <!-- Search Input -->
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0 ps-0" placeholder="Rechercher par nom, adresse, quartier...">
                        </div>
                    </div>

                    <!-- City Filter Dropdown -->
                    <div class="col-md-3">
                        <select name="city" class="form-select">
                            <option value="">Toutes les villes</option>
                            @foreach($cities as $c)
                                <option value="{{ $c }}" {{ $city === $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Only Open Today Checkbox -->
                    <div class="col-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="open_today" value="1" id="open_today" {{ $onlyOpenToday ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold text-dark" for="open_today">
                                Ouvert aujourd'hui
                            </label>
                        </div>
                    </div>

                    <!-- Submit & Reset Buttons -->
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-dark w-100 rounded-pill fw-semibold" style="font-size: 0.88rem;">Filtrer</button>
                        @if($search || $city || $onlyOpenToday)
                            <a href="{{ route('hubs.index') }}" class="btn btn-outline-secondary rounded-pill" title="Réinitialiser">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Hubs Grid -->
        @if($hubs->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 text-center py-5 my-4">
                <div class="card-body">
                    <i class="bi bi-geo-alt text-muted" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold text-dark mt-3">Aucun point relais trouvé</h5>
                    <p class="text-muted small">Aucun point relais ne correspond à vos critères de recherche. Essayez d'élargir la ville ou de réinitialiser les filtres.</p>
                    <a href="{{ route('hubs.index') }}" class="btn btn-outline-primary rounded-pill px-4 btn-sm">Réinitialiser les filtres</a>
                </div>
            </div>
        @else
            <div class="row g-4 mb-4">
                @foreach($hubs as $hub)
                    @php
                        $isOpen = $hub->isOpenToday();
                        $todaySlot = $hub->getTodaySlot();
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative d-flex flex-column" 
                             style="transition: transform 0.2s, box-shadow 0.2s; background: #fff;">
                            
                            <!-- Top Colored Strip -->
                            <div style="height: 6px; background: linear-gradient(90deg, #0F5C6B, #38BDF8);"></div>

                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <!-- City and Status Badges -->
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge rounded-pill fw-bold px-3 py-1 text-uppercase" 
                                          style="background: rgba(15, 92, 107, 0.1); color: #0F5C6B; font-size: 0.72rem; letter-spacing: 0.05em;">
                                        📍 {{ $hub->city }} ({{ $hub->postal_code }})
                                    </span>
                                    
                                    @if($isOpen)
                                        <span class="badge rounded-pill bg-success-subtle text-success fw-bold px-3 py-1" style="font-size: 0.72rem;">
                                            ● Ouvert aujourd'hui
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-light text-muted fw-bold px-3 py-1 border" style="font-size: 0.72rem;">
                                            ○ Fermé aujourd'hui
                                        </span>
                                    @endif
                                </div>

                                <!-- Hub Name -->
                                <h5 class="fw-bold mb-2">
                                    <a href="{{ route('hubs.show', $hub) }}" class="text-decoration-none" style="color: #10202B;">
                                        {{ $hub->name }}
                                    </a>
                                </h5>

                                <!-- Address & Phone -->
                                <div class="small text-muted mb-3">
                                    <div class="d-flex align-items-start gap-2 mb-1">
                                        <i class="bi bi-geo-alt-fill text-primary" style="flex-shrink: 0; margin-top: 2px;"></i>
                                        <span>{{ $hub->address }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-telephone-fill text-primary" style="flex-shrink: 0;"></i>
                                        <span>{{ $hub->contact_phone }}</span>
                                    </div>
                                </div>

                                <!-- Description excerpt -->
                                @if($hub->description)
                                    <p class="small text-muted mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                                        {{ $hub->description }}
                                    </p>
                                @endif

                                <!-- Today's schedule banner -->
                                <div class="p-2 px-3 rounded-3 mb-3 small d-flex align-items-center justify-content-between" 
                                     style="background: {{ $isOpen ? 'rgba(16, 185, 129, 0.08)' : 'rgba(241, 245, 249, 0.8)' }}; border: 1px solid {{ $isOpen ? 'rgba(16, 185, 129, 0.2)' : 'rgba(226, 232, 240, 0.8)' }};">
                                    <span class="text-muted fw-semibold">
                                        <i class="bi bi-clock me-1 text-primary"></i> Aujourd'hui :
                                    </span>
                                    <strong style="color: {{ $isOpen ? '#059669' : '#64748B' }};">
                                        {{ $isOpen && $todaySlot ? $todaySlot->formatted_hours : 'Pas de permanence' }}
                                    </strong>
                                </div>

                                <!-- Open days tags -->
                                <div class="mb-4 mt-auto">
                                    <small class="text-muted fw-semibold d-block mb-1" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em;">Jours d'ouverture :</small>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($hub->operatingSlots as $slot)
                                            <span class="badge rounded-pill {{ strtolower($slot->day_of_week) === strtolower(\App\Models\CollectionHub::getCurrentFrenchDay()) ? 'bg-warning text-dark fw-bold' : 'bg-light text-muted border' }}" style="font-size: 0.68rem;">
                                                {{ ucfirst(substr($slot->day_of_week, 0, 3)) }}.
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Footer CTA Button -->
                                <a href="{{ route('hubs.show', $hub) }}" class="btn btn-outline-dark rounded-pill w-100 fw-bold py-2 mt-auto" style="font-size: 0.85rem;">
                                    Consulter les horaires & détails →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $hubs->links() }}
            </div>
        @endif

    </div>
</section>
@endsection
