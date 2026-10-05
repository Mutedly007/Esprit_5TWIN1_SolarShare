{{-- resources/views/pages/front/marketplace_show.blade.php --}}
@extends('layouts.solartec')

@section('title', $equipment->name . ' — SolarShare')
@section('meta_description', Str::limit(strip_tags($equipment->description), 150))

@section('content')
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0;">
    <div class="container">
        
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('marketplace') }}" style="color: #0F5C6B; text-decoration: none;">Marketplace</a></li>
                @if($equipment->category)
                    <li class="breadcrumb-item"><a href="{{ route('marketplace', ['category' => $equipment->category->slug]) }}" style="color: #0F5C6B; text-decoration: none;">{{ $equipment->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">{{ $equipment->name }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            
            <!-- Left Column: Image & Specs -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
                    <div style="height: 420px; background: #0F172A; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                        @if($equipment->image_url)
                            <img src="{{ $equipment->image_url }}" alt="{{ $equipment->name }}" style="width: 100%; height: 100%; object-fit: contain;">
                        @else
                            <div style="font-size: 5rem; color: #FFB020;">☀️</div>
                        @endif

                        <span style="position: absolute; top: 18px; left: 18px; background: rgba(16, 32, 43, 0.85); backdrop-filter: blur(8px); color: #FFFFFF; font-size: 0.82rem; font-weight: 700; padding: 0.4rem 0.9rem; border-radius: 9999px;">
                            {{ $equipment->category?->name ?? 'Énergie' }}
                        </span>

                        <span style="position: absolute; top: 18px; right: 18px; background: {{ $equipment->is_available ? '#DCFCE7' : '#FEE2E2' }}; color: {{ $equipment->is_available ? '#166534' : '#991B1B' }}; font-size: 0.82rem; font-weight: 700; padding: 0.4rem 0.9rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.35rem;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $equipment->is_available ? '#16A34A' : '#EF4444' }};"></span>
                            {{ $equipment->is_available ? 'Disponible immédiatement' : 'Actuellement réservé' }}
                        </span>
                    </div>

                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3" style="color: #10202B;">Description du Matériel</h4>
                        <div style="color: #334155; line-height: 1.7; font-size: 0.98rem; white-space: pre-line;">
                            {{ $equipment->description ?: 'Aucune description spécifique fournie pour cet équipement.' }}
                        </div>
                    </div>
                </div>

                <!-- Technical Specs Box -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h5 class="fw-bold mb-3" style="color: #10202B;">Spécifications Techniques</h5>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                <span class="d-block text-muted small fw-bold text-uppercase">Marque / Fabricant</span>
                                <strong class="fs-5 text-dark">{{ $equipment->brand ?: 'Non spécifiée' }}</strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                <span class="d-block text-muted small fw-bold text-uppercase">Catégorie</span>
                                <strong class="fs-5 text-dark">{{ $equipment->category?->name ?? 'Général' }}</strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                <span class="d-block text-muted small fw-bold text-uppercase">Puissance Nominale</span>
                                <strong class="fs-5 text-dark">{{ $equipment->power_watts ? $equipment->power_watts . ' Watts' : 'Non précisé' }}</strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                <span class="d-block text-muted small fw-bold text-uppercase">Capacité Batterie</span>
                                <strong class="fs-5 text-dark">{{ $equipment->capacity_wh ? $equipment->capacity_wh . ' Wh' : 'Non précisé' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Price Card & Actions -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4" style="position: sticky; top: 96px;">
                    <div style="font-size: 0.82rem; font-weight: 700; color: #0F5C6B; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.4rem;">
                        {{ $equipment->brand }}
                    </div>
                    <h1 class="h3 fw-bold text-dark mb-3">{{ $equipment->name }}</h1>

                    <!-- Price Block -->
                    <div class="p-3 rounded-3 mb-4" style="background: linear-gradient(135deg, rgba(255, 176, 32, 0.12), rgba(255, 107, 61, 0.06)); border: 1px solid rgba(255, 176, 32, 0.3);">
                        <div style="display: flex; align-items: baseline; gap: 0.35rem;">
                            <span style="font-size: 2.2rem; font-weight: 800; color: #C2410C; line-height: 1;">
                                {{ number_format($equipment->daily_rate, 2) }}
                            </span>
                            <span style="font-size: 1rem; color: #7C2D12; font-weight: 700;">TND / jour</span>
                        </div>
                        <span class="d-block mt-2 text-muted small">
                            Montant de la caution requise : <strong>{{ number_format($equipment->deposit_amount, 2) }} TND</strong> (bloquée et restituée après inspection).
                        </span>
                    </div>

                    <!-- Owner Profile -->
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-4" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #FFB020, #FF6B3D); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 800; flex-shrink: 0;">
                            {{ strtoupper(substr($equipment->user?->name ?? 'P', 0, 1)) }}
                        </div>
                        <div>
                            <span class="d-block text-muted small fw-bold">Mis à disposition par</span>
                            <strong class="text-dark d-block">{{ $equipment->user?->name ?? 'Membre SolarShare' }}</strong>
                        </div>
                    </div>

                    <!-- Rental Action Button -->
                    @if($equipment->is_available)
                        <button type="button" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mb-3" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520; font-size: 1.05rem; box-shadow: 0 6px 20px rgba(255, 107, 61, 0.35);" onclick="alert('Module de réservation en cours de finalisation !')">
                            ⚡ Louer cet équipement
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary w-100 rounded-pill py-3 fw-bold mb-3" disabled>
                            Actuellement indisponible
                        </button>
                    @endif

                    <a href="{{ route('marketplace') }}" class="btn btn-outline-secondary w-100 rounded-pill py-2 text-center" style="font-size: 0.9rem;">
                        &larr; Retour au catalogue
                    </a>

                    <!-- Reassurance Items -->
                    <div class="mt-4 pt-3 border-top" style="font-size: 0.82rem; color: #64748B;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span>🛡️</span>
                            <span>Contrat de prêt sécurisé et caution garantie</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span>🔍</span>
                            <span>État des lieux numérique avant et après le prêt</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span>🤝</span>
                            <span>Partage d'énergie éco-responsable et local</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- Related Equipment -->
        @if($related->count() > 0)
            <div class="mt-5 pt-4 border-top">
                <h3 class="fw-bold mb-4" style="color: #10202B;">Autres équipements dans la même catégorie</h3>
                <div class="row g-4">
                    @foreach($related as $rel)
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                                <div style="height: 160px; background: #0F172A; overflow: hidden;">
                                    @if($rel->image_url)
                                        <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #FFB020; font-size: 2rem;">☀️</div>
                                    @endif
                                </div>
                                <div class="card-body p-3">
                                    <span class="text-primary small fw-bold text-uppercase">{{ $rel->brand }}</span>
                                    <h6 class="fw-bold text-dark mb-2">{{ $rel->name }}</h6>
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                        <strong class="text-dark">{{ number_format($rel->daily_rate, 2) }} TND/j</strong>
                                        <a href="{{ route('marketplace.show', $rel) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Voir</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>
@endsection
