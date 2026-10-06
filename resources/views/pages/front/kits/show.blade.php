{{-- resources/views/pages/front/kits/show.blade.php --}}
@extends('layouts.solartec')

@section('title', $energyKit->title . ' — Packs Solaires Clé-en-main')
@section('meta_description', Str::limit(strip_tags($energyKit->description), 150))

@section('content')
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('energy-kits.index') }}" style="color: #0F5C6B; text-decoration: none;">Packs Solaires</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">{{ $energyKit->title }}</li>
            </ol>
        </nav>

        <!-- Top Pack Banner -->
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 mb-4 bg-white">
            <div class="row g-4 align-items-center">
                
                <div class="col-lg-5">
                    <div style="height: 280px; background: #0F172A; border-radius: 16px; overflow: hidden; position: relative; display: flex; align-items: center; justify-content: center;">
                        @if($energyKit->image_url)
                            <img src="{{ $energyKit->image_url }}" alt="{{ $energyKit->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="font-size: 5rem; color: #FFB020;">📦</div>
                        @endif

                        <span style="position: absolute; top: 16px; left: 16px; {{ $energyKit->target_use_badge_style }}; font-size: 0.82rem; font-weight: 700; padding: 0.35rem 0.85rem; border-radius: 9999px;">
                            {{ $energyKit->target_use_label }}
                        </span>

                        @if($energyKit->discount_percentage > 0)
                            <span style="position: absolute; top: 16px; right: 16px; background: linear-gradient(135deg, #FFB020, #FF6B3D); color: #071520; font-size: 0.85rem; font-weight: 800; padding: 0.4rem 0.9rem; border-radius: 9999px; box-shadow: 0 4px 12px rgba(255, 107, 61, 0.4);">
                                -{{ rtrim(rtrim(number_format($energyKit->discount_percentage, 1), '0'), '.') }}% Remise Pack
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                        <span class="badge rounded-pill text-uppercase fw-bold px-3 py-1" style="background: rgba(15, 92, 107, 0.12); color: #0F5C6B; font-size: 0.75rem;">
                            Pack Combiné ({{ $energyKit->equipment->count() }} Équipements)
                        </span>
                        
                        @auth
                            @if(auth()->user()->isAdmin() || auth()->id() === $energyKit->user_id)
                                <div class="d-flex align-items-center gap-2">
                                    <form action="{{ route('energy-kits.toggle', $energyKit) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm rounded-pill px-3 fw-bold {{ $energyKit->is_active ? 'btn-success text-white' : 'btn-secondary text-white' }}" title="Cliquer pour basculer Actif / Inactif">
                                            {{ $energyKit->is_active ? '✓ Actif (En ligne)' : '✕ Inactif (Masqué)' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('energy-kits.edit', $energyKit) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        ✏️ Modifier
                                    </a>
                                    <form action="{{ route('energy-kits.destroy', $energyKit) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce pack ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            🗑️ Supprimer
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <h1 class="h2 fw-bold text-dark mb-3">{{ $energyKit->title }}</h1>
                    
                    <p class="text-muted" style="line-height: 1.7; font-size: 0.96rem;">
                        {{ $energyKit->description ?: 'Ce pack solaire combiné rassemble tous les éléments nécessaires pour une autonomie énergétique immédiate.' }}
                    </p>

                    <!-- Combined Specs Grid -->
                    <div class="row g-2 pt-2 border-top">
                        <div class="col-sm-4 col-6">
                            <div class="p-2 rounded-3 text-center" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                <span class="d-block text-muted small fw-bold">⚡ Puissance Totale</span>
                                <strong class="fs-5 text-dark">{{ $energyKit->total_power_watts }} W</strong>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="p-2 rounded-3 text-center" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                <span class="d-block text-muted small fw-bold">🔋 Capacité Cumulée</span>
                                <strong class="fs-5 text-dark">{{ $energyKit->total_capacity_wh }} Wh</strong>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <div class="p-2 rounded-3 text-center" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                <span class="d-block text-muted small fw-bold">🛡️ Caution Totale</span>
                                <strong class="fs-5 text-dark">{{ number_format($energyKit->total_deposit, 2) }} TND</strong>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="row g-4">
            
            <!-- Left Column: Bundled Equipment Table (Relation N - N) -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    
                    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                        <div>
                            <span class="badge rounded-pill text-uppercase fw-bold px-3 py-1 mb-1" style="background: rgba(255, 176, 32, 0.15); color: #C2410C; font-size: 0.72rem;">
                                🧩 Composition du Pack (Relation N-N)
                            </span>
                            <h4 class="h5 fw-bold text-dark mb-0">Équipements Inclus dans ce Pack</h4>
                        </div>
                        
                        @auth
                            @if(auth()->user()->isAdmin() || auth()->id() === $energyKit->user_id)
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#attachEquipmentModal">
                                    + Ajouter un équipement
                                </button>
                            @endif
                        @endauth
                    </div>

                    <p class="text-muted small mb-4">
                        Ce kit combiné regroupe <strong>{{ $energyKit->equipment->count() }} équipement(s)</strong> avec options et câblages spécifiques inclus.
                    </p>

                    @if($energyKit->equipment->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">⚠️</div>
                            <p>Aucun équipement n'est encore associé à ce pack.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Équipement</th>
                                        <th>Qté</th>
                                        <th>Options & Spécifications</th>
                                        <th>Tarif Journalier</th>
                                        @auth
                                            @if(auth()->user()->isAdmin() || auth()->id() === $energyKit->user_id)
                                                <th class="text-end">Retirer</th>
                                            @endif
                                        @endauth
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($energyKit->equipment as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div style="width: 50px; height: 50px; border-radius: 10px; background: #0F172A; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                                        @if($item->image_url)
                                                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            <span style="font-size: 1.3rem;">☀️</span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <strong class="d-block text-dark">{{ $item->name }}</strong>
                                                        <span class="text-muted small">{{ $item->brand }} ── {{ $item->category?->name }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="badge bg-secondary rounded-pill px-2 py-1">
                                                    {{ $item->pivot->quantity }}x
                                                </span>
                                            </td>

                                            <td>
                                                <div class="small">
                                                    @if($item->pivot->options)
                                                        <span class="badge bg-light text-dark border d-inline-block mb-1">
                                                            📌 {{ $item->pivot->options }}
                                                        </span>
                                                    @endif
                                                    <div class="text-muted" style="font-size: 0.78rem;">
                                                        @if($item->power_watts) {{ $item->power_watts }}W @endif
                                                        @if($item->capacity_wh) | {{ $item->capacity_wh }}Wh @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <strong style="color: #0F5C6B;">{{ number_format($item->daily_rate * $item->pivot->quantity, 2) }} TND/j</strong>
                                                <span class="d-block text-muted" style="font-size: 0.72rem;">({{ number_format($item->daily_rate, 2) }} TND/u)</span>
                                            </td>

                                            @auth
                                                @if(auth()->user()->isAdmin() || auth()->id() === $energyKit->user_id)
                                                    <td class="text-end">
                                                        <form action="{{ route('energy-kits.detach', ['energyKit' => $energyKit->slug, 'equipment' => $item->id]) }}" method="POST" onsubmit="return confirm('Retirer cet équipement du pack ?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" title="Détacher cet équipement">
                                                                &times;
                                                            </button>
                                                        </form>
                                                    </td>
                                                @endif
                                            @endauth
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Right Column: Pricing & Rental Action -->
            <div class="col-lg-4">
                
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4" style="position: sticky; top: 96px;">
                    <h5 class="fw-bold mb-3" style="color: #10202B;">Tarif & Avantage Pack</h5>

                    <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, rgba(255, 176, 32, 0.12), rgba(255, 107, 61, 0.06)); border: 1px solid rgba(255, 176, 32, 0.3);">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Prix unitaire cumulé :</span>
                            <span class="text-decoration-line-through">{{ number_format($energyKit->raw_daily_rate, 2) }} TND / j</span>
                        </div>
                        
                        @if($energyKit->discount_percentage > 0)
                            <div class="d-flex justify-content-between small text-success fw-bold mb-2">
                                <span>Remise Pack ({{ rtrim(rtrim(number_format($energyKit->discount_percentage, 1), '0'), '.') }}%) :</span>
                                <span>-{{ number_format($energyKit->daily_savings, 2) }} TND / j</span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-baseline pt-2 border-top">
                            <span class="fw-bold text-dark">Tarif Pack :</span>
                            <span style="font-size: 2.2rem; font-weight: 800; color: #C2410C; line-height: 1;">
                                {{ number_format($energyKit->discounted_daily_rate, 2) }}
                            </span>
                            <span style="font-size: 0.95rem; font-weight: 700; color: #7C2D12;">TND / jour</span>
                        </div>
                    </div>

                    <!-- Availability indicator -->
                    <div class="p-3 rounded-3 mb-4" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 0.85rem;">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span>Disponibilité du pack :</span>
                            <span class="badge rounded-pill {{ $energyKit->is_all_available ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                {{ $energyKit->is_all_available ? '✓ Disponible immédiatement' : '⏳ Partiellement réservé' }}
                            </span>
                        </div>
                        <span class="text-muted small d-block">
                            Caution totale de garantie : <strong>{{ number_format($energyKit->total_deposit, 2) }} TND</strong> (restituée après restitution et état des lieux).
                        </span>
                    </div>

                    <!-- Direct Rental Button -->
                    @if($energyKit->equipment->isNotEmpty())
                        @auth
                            @if(auth()->user()->isRenter())
                                <button type="button" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mb-3 d-flex align-items-center justify-content-center gap-2" 
                                        data-bs-toggle="modal" data-bs-target="#reserveKitModal"
                                        style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520; font-size: 1.05rem; box-shadow: 0 6px 20px rgba(255, 107, 61, 0.35); cursor: pointer;">
                                    ⚡ Réserver ce Pack Solaire
                                </button>
                            @else
                                <div class="alert alert-warning border-0 rounded-4 p-3 mb-3 text-start shadow-sm" style="background: #FFFBEB; border-left: 4px solid #F59E0B !important; color: #92400E; font-size: 0.88rem;">
                                    <div class="d-flex align-items-center gap-2 fw-bold mb-1" style="font-size: 0.92rem;">
                                        <span>🔒</span>
                                        <span>Réservation non autorisée</span>
                                    </div>
                                    <p class="mb-0" style="line-height: 1.45;">
                                        Votre compte a le rôle <strong>Prêteur (Lend)</strong>. Seuls les utilisateurs avec le rôle <strong>Emprunteur (Rent)</strong> ou <strong>Les deux (Both)</strong> peuvent réserver ce pack solaire.
                                    </p>
                                </div>
                                <button type="button" class="btn btn-secondary w-100 rounded-pill py-3 fw-bold mb-3 d-flex align-items-center justify-content-center gap-2" disabled style="opacity: 0.7; cursor: not-allowed; background: #94A3B8; border: none;">
                                    ⚡ Réservation réservée aux emprunteurs
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mb-3 d-flex align-items-center justify-content-center gap-2" 
                               style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520; font-size: 1.05rem; box-shadow: 0 6px 20px rgba(255, 107, 61, 0.35); text-decoration: none;">
                                ⚡ Connectez-vous pour Réserver
                            </a>
                        @endauth
                    @else
                        <button type="button" class="btn btn-secondary w-100 rounded-pill py-3 fw-bold mb-3" disabled>
                            Pack en cours de configuration
                        </button>
                    @endif

                    @auth
                        @php
                            $userReservations = $energyKit->reservations()->where('user_id', auth()->id())->latest()->get();
                        @endphp
                        @if($userReservations->isNotEmpty())
                            <div class="mb-3 p-3 rounded-3" style="background: #F0FDF4; border: 1px solid #BBF7D0;">
                                <div class="fw-bold mb-2 text-success" style="font-size: 0.85rem;">
                                    ✓ Vos réservations pour ce pack ({{ $userReservations->count() }}) :
                                </div>
                                @foreach($userReservations as $res)
                                    <div class="d-flex align-items-center justify-content-between py-1 border-bottom border-success-subtle" style="font-size: 0.8rem;">
                                        <span>Du {{ $res->start_date->format('d/m/Y') }} au {{ $res->end_date->format('d/m/Y') }} ({{ $res->days_count }}j)</span>
                                        <span class="badge bg-success">{{ number_format($res->total_price, 2) }} TND</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endauth

                    <a href="{{ route('energy-kits.index') }}" class="btn btn-outline-secondary w-100 rounded-pill py-2 text-center" style="font-size: 0.9rem;">
                        &larr; Retour à la liste des packs
                    </a>

                    <!-- Reassurance Items -->
                    <div class="mt-4 pt-3 border-top" style="font-size: 0.82rem; color: #64748B;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span>📦</span>
                            <span>Ensemble complet prêt à brancher immédiatement</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span>🔍</span>
                            <span>État des lieux numérique complet (Check-in & Check-out)</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span>🛡️</span>
                            <span>Contrat de prêt sécurisé et caution garantie</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

<!-- Modal: Attach Equipment to Pack -->
@auth
@if(auth()->user()->isAdmin() || auth()->id() === $energyKit->user_id)
<div class="modal fade" id="attachEquipmentModal" tabindex="-1" aria-labelledby="attachModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('energy-kits.attach', $energyKit->slug) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark" id="attachModalLabel">Ajouter un équipement au pack</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label for="modal_equipment_id" class="form-label fw-bold small text-dark">Choisir l'équipement</label>
                        <select name="equipment_id" id="modal_equipment_id" class="form-select rounded-3" required>
                            <option value="" disabled selected>-- Sélectionnez un équipement --</option>
                            @foreach(\App\Models\Equipment::where('status', 'approved')->orderBy('name')->get() as $eq)
                                <option value="{{ $eq->id }}">{{ $eq->name }} ({{ $eq->category?->name }}) — {{ number_format($eq->daily_rate, 2) }} TND/j</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="modal_quantity" class="form-label fw-bold small text-dark">Quantité</label>
                        <input type="number" name="quantity" id="modal_quantity" class="form-control rounded-3" min="1" max="20" value="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal_options" class="form-label fw-bold small text-dark">Option / Câblage inclus (facultatif)</label>
                        <input type="text" name="options" id="modal_options" class="form-control rounded-3" placeholder="Ex: Câble MC4 5m inclus, Trépied fourni">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520;">
                        + Ajouter au pack
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endauth

<!-- Modal Réservation de Pack Solaire -->
@auth
<div class="modal fade" id="reserveKitModal" tabindex="-1" aria-labelledby="reserveKitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <form action="{{ route('energy-kits.reserve', $energyKit) }}" method="POST">
                @csrf
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #071520, #0F5C6B); border-radius: 16px 16px 0 0; padding: 1.25rem 1.5rem;">
                    <h5 class="modal-title fw-bold" id="reserveKitModalLabel" style="font-size: 1.15rem;">
                        ⚡ Réserver : {{ $energyKit->title }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center justify-content-between" style="border-radius: 10px; font-size: 0.88rem;">
                        <span>Tarif négocié ({{ $energyKit->discount_percentage }}% de remise) :</span>
                        <strong class="text-primary">{{ number_format($energyKit->discounted_daily_rate, 2) }} TND / jour</strong>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="reserve_start_date" class="form-label fw-semibold" style="font-size: 0.88rem;">Date de début *</label>
                            <input type="date" name="start_date" id="reserve_start_date" class="form-control rounded-3" 
                                   min="{{ date('Y-m-d') }}" value="{{ old('start_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="reserve_end_date" class="form-label fw-semibold" style="font-size: 0.88rem;">Date de fin *</label>
                            <input type="date" name="end_date" id="reserve_end_date" class="form-control rounded-3" 
                                   min="{{ date('Y-m-d') }}" value="{{ old('end_date', date('Y-m-d', strtotime('+3 days'))) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reserve_notes" class="form-label fw-semibold" style="font-size: 0.88rem;">Remarques ou besoins spécifiques (Optionnel)</label>
                        <textarea name="notes" id="reserve_notes" rows="2" class="form-control rounded-3" placeholder="Ex: Livraison sur chantier, câblage additionnel souhaité..."></textarea>
                    </div>

                    <!-- Live Cost Preview -->
                    <div class="p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.88rem;">
                            <span class="text-muted">Durée de la location :</span>
                            <strong id="calc_days">4 jour(s)</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.88rem;">
                            <span class="text-muted">Total location pack :</span>
                            <strong class="text-success" id="calc_total">0.00 TND</strong>
                        </div>
                        <div class="d-flex justify-content-between pt-2 border-top" style="font-size: 0.82rem;">
                            <span class="text-muted">Caution de garantie (restituée) :</span>
                            <strong>{{ number_format($energyKit->total_deposit, 2) }} TND</strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520;">
                        ✓ Confirmer la réservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startInput = document.getElementById('reserve_start_date');
    const endInput = document.getElementById('reserve_end_date');
    const calcDays = document.getElementById('calc_days');
    const calcTotal = document.getElementById('calc_total');
    const dailyRate = {{ (float) $energyKit->discounted_daily_rate }};

    function updateTotals() {
        if (!startInput || !endInput || !calcDays || !calcTotal) return;
        const start = new Date(startInput.value);
        const end = new Date(endInput.value);
        if (isNaN(start) || isNaN(end) || end < start) {
            calcDays.textContent = 'Dates invalides';
            calcTotal.textContent = '0.00 TND';
            return;
        }
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        const total = (diffDays * dailyRate).toFixed(2);
        calcDays.textContent = diffDays + ' jour(s)';
        calcTotal.textContent = total + ' TND';
    }

    if (startInput && endInput) {
        startInput.addEventListener('change', function() {
            endInput.min = this.value;
            if (endInput.value < this.value) endInput.value = this.value;
            updateTotals();
        });
        endInput.addEventListener('change', updateTotals);
        updateTotals();
    }
});
</script>
@endauth

@endsection
