{{-- resources/views/pages/front/hubs/show.blade.php --}}
@extends('layouts.solartec')

@section('title', $hub->name . ' — Point Relais SolarShare')
@section('meta_description', 'Horaires d\'ouverture, adresse et modalités de dépôt/retrait pour le point relais ' . $hub->name . ' à ' . $hub->city . '.')

@section('content')
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0; min-height: 80vh;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hubs.index') }}" style="color: #0F5C6B; text-decoration: none;">Points Relais</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">{{ $hub->name }}</li>
            </ol>
        </nav>

        @php
            $isOpen = $hub->isOpenToday();
            $todayFrench = \App\Models\CollectionHub::getCurrentFrenchDay();
            $todaySlot = $hub->getTodaySlot();
        @endphp

        <!-- Main Hub Header Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
            <div style="height: 8px; background: linear-gradient(90deg, #0F5C6B, #38BDF8, #FFB020);"></div>
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="badge rounded-pill fw-bold px-3 py-1 text-uppercase" 
                                  style="background: rgba(15, 92, 107, 0.12); color: #0F5C6B; font-size: 0.75rem; letter-spacing: 0.05em;">
                                📍 {{ $hub->city }} — {{ $hub->postal_code }}
                            </span>
                            @if($isOpen)
                                <span class="badge rounded-pill bg-success-subtle text-success fw-bold px-3 py-1" style="font-size: 0.75rem;">
                                    ● Ouvert aujourd'hui ({{ $todaySlot ? $todaySlot->formatted_hours : '' }})
                                </span>
                            @else
                                <span class="badge rounded-pill bg-light text-muted fw-bold px-3 py-1 border" style="font-size: 0.75rem;">
                                    ○ Fermé aujourd'hui
                                </span>
                            @endif
                            @if(auth()->check() && auth()->user()->isAdmin())
                                <a href="{{ route('admin.hubs.show', $hub) }}" class="badge rounded-pill bg-dark text-white fw-bold px-3 py-1 text-decoration-none ms-1" style="font-size: 0.75rem;">
                                    ⚙️ Gérer dans l'Admin
                                </a>
                            @endif
                        </div>

                        <h1 class="h3 fw-bold mb-3" style="color: #10202B;">{{ $hub->name }}</h1>

                        <div class="row g-3 small text-muted">
                            <div class="col-sm-6">
                                <strong class="text-dark d-block"><i class="bi bi-geo-alt-fill text-primary me-1"></i> Adresse physique :</strong>
                                <span>{{ $hub->address }}, {{ $hub->postal_code }} {{ $hub->city }}</span>
                            </div>
                            <div class="col-sm-6">
                                <strong class="text-dark d-block"><i class="bi bi-telephone-fill text-primary me-1"></i> Contact & Permanence :</strong>
                                <span>{{ $hub->contact_phone }}</span>
                            </div>
                        </div>

                        @if($hub->description)
                            <div class="mt-4 p-3 rounded-3" style="background: rgba(15, 92, 107, 0.04); border-left: 4px solid #0F5C6B;">
                                <strong class="text-dark small d-block mb-1">Consignes d'accès & description :</strong>
                                <p class="small text-muted mb-0" style="line-height: 1.6;">{{ $hub->description }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-4 text-lg-end">
                        <div class="p-4 rounded-4 bg-light text-start text-lg-end">
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Capacité du Point Relais</small>
                            <h3 class="fw-bold mb-1" style="color: #0F5C6B;">{{ $hub->max_capacity }} casiers</h3>
                            <p class="small text-muted mb-3">Emplacements sécurisés pour panneaux et batteries</p>
                            <a href="{{ route('marketplace') }}" class="btn btn-primary rounded-pill px-4 py-2 w-100 fw-bold" style="background: linear-gradient(135deg, #0F5C6B, #38BDF8); border: none; font-size: 0.88rem;">
                                Parcourir les équipements →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left: Weekly Schedule Table (Operating Slots) -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="h5 fw-bold mb-0" style="color: #10202B;">
                                <i class="bi bi-calendar-week text-primary me-2"></i> Plages Horaires de la Semaine
                            </h4>
                            <span class="badge bg-light text-muted border small">7 jours / 7</span>
                        </div>
                        <p class="small text-muted mb-4">
                            Les échanges (check-in / check-out) s'effectuent exclusivement pendant ces plages d'ouverture :
                        </p>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="small text-muted text-uppercase" style="font-size: 0.75rem;">
                                        <th>Jour</th>
                                        <th>Horaires</th>
                                        <th>Modalités</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allDays = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
                                    @endphp
                                    @foreach($allDays as $d)
                                        @php
                                            $slot = $hub->operatingSlots->where('day_of_week', $d)->first();
                                            $isCurrentDay = (strtolower($d) === strtolower($todayFrench));
                                        @endphp
                                        <tr style="{{ $isCurrentDay ? 'background: rgba(255, 176, 32, 0.12); font-weight: 600;' : '' }}">
                                            <td class="py-3">
                                                <span class="text-capitalize {{ $isCurrentDay ? 'text-dark fw-bold' : 'text-dark' }}">
                                                    {{ $d }}
                                                </span>
                                                @if($isCurrentDay)
                                                    <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Aujourd'hui</span>
                                                @endif
                                            </td>
                                            <td class="py-3">
                                                @if($slot && $slot->is_active)
                                                    <span class="badge bg-info-subtle text-info fw-bold px-2 py-1">
                                                        <i class="bi bi-clock me-1"></i> {{ $slot->formatted_hours }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">Fermé</span>
                                                @endif
                                            </td>
                                            <td class="py-3 small text-muted">
                                                {{ $slot && $slot->notes ? $slot->notes : ($slot ? 'Retrait & Dépôt standard' : '—') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: How it Works & Nearby Hubs -->
            <div class="col-lg-5">
                <!-- How Relay Hubs Work -->
                <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                    <div class="card-body p-4">
                        <h4 class="h5 fw-bold mb-3" style="color: #10202B;">
                            <i class="bi bi-shield-check text-success me-2"></i> Comment se déroule l'échange ?
                        </h4>

                        <div class="d-flex flex-column gap-3 small text-muted">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                     style="width: 28px; height: 28px; background: #0F5C6B; flex-shrink: 0; font-size: 0.8rem;">1</div>
                                <div>
                                    <strong class="text-dark d-block">Réservation en ligne</strong>
                                    L'emprunteur réserve le matériel et choisit ce point relais pour le retrait.
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                     style="width: 28px; height: 28px; background: #0F5C6B; flex-shrink: 0; font-size: 0.8rem;">2</div>
                                <div>
                                    <strong class="text-dark d-block">Dépôt par le propriétaire</strong>
                                    Le propriétaire dépose le matériel dans son casier sécurisé avec un code unique.
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                     style="width: 28px; height: 28px; background: #0F5C6B; flex-shrink: 0; font-size: 0.8rem;">3</div>
                                <div>
                                    <strong class="text-dark d-block">Retrait sans contact</strong>
                                    L'emprunteur récupère son pack solaire en renseignant son code PIN temporaire.
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                     style="width: 28px; height: 28px; background: #0F5C6B; flex-shrink: 0; font-size: 0.8rem;">4</div>
                                <div>
                                    <strong class="text-dark d-block">État des lieux numérique</strong>
                                    Photos et vérification de la batterie enregistrées directement sur la plateforme.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nearby Hubs -->
                @if($nearbyHubs->isNotEmpty())
                    <div class="card border-0 shadow-sm rounded-4 bg-white">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3 small text-uppercase text-muted" style="letter-spacing: 0.05em;">
                                Autres Hubs à {{ $hub->city }}
                            </h5>
                            <div class="list-group list-group-flush">
                                @foreach($nearbyHubs as $nearby)
                                    <a href="{{ route('hubs.show', $nearby) }}" class="list-group-item list-group-item-action px-0 py-2 border-0 d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="text-dark d-block small">{{ $nearby->name }}</strong>
                                            <small class="text-muted">{{ $nearby->address }}</small>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted small"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-4 text-center">
            <a href="{{ route('hubs.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                ← Retour à l'annuaire des points relais
            </a>
        </div>

    </div>
</section>
@endsection
