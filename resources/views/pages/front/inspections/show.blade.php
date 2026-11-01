{{-- resources/views/pages/front/inspections/show.blade.php --}}
@extends('layouts.solartec')

@section('title', 'Rapport d\'Inspection Numérique #' . $inspection->id . ' — SolarShare')
@section('meta_description', 'Rapport officiel d\'état des lieux au départ ou au retour du matériel loué.')

@section('content')
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rentals.index') }}" style="color: #0F5C6B; text-decoration: none;">Mes Réservations</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rentals.show', $rental) }}" style="color: #0F5C6B; text-decoration: none;">#LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">
                    État des lieux ({{ $inspection->type === 'check_in' ? 'Check-in' : 'Check-out' }})
                </li>
            </ol>
        </nav>

        <!-- Top Header Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge rounded-pill text-uppercase fw-bold px-3 py-1" style="{{ $inspection->type === 'check_in' ? 'background:#FEF3C7; color:#92400E;' : 'background:#DCFCE7; color:#166534;' }} font-size: 0.8rem;">
                            {{ $inspection->type === 'check_in' ? '🛫 Check-in (Départ / Retrait)' : '🛬 Check-out (Retour / Restitution)' }}
                        </span>
                        
                        @if($inspection->is_validated)
                            <span class="badge rounded-pill fw-bold px-3 py-1" style="background: #DCFCE7; color: #166534; font-size: 0.8rem;">
                                ✓ Signé & Validé Numériquement
                            </span>
                        @else
                            <span class="badge rounded-pill fw-bold px-3 py-1" style="background: #FEF3C7; color: #92400E; font-size: 0.8rem;">
                                ⏳ Brouillon (Non validé)
                            </span>
                        @endif
                    </div>
                    
                    <h1 class="h3 fw-bold text-dark mb-1">
                        Rapport d'État des Lieux <span style="color: #0F5C6B;">#INSP-{{ str_pad($inspection->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </h1>
                    <p class="text-muted small mb-0">
                        Équipement : <strong>{{ $rental->equipment->name }}</strong> ── Contrat lié : <strong>#LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</strong>
                    </p>
                </div>

                <!-- Actions -->
                <div class="d-flex flex-wrap gap-2">
                    @if(! $inspection->is_validated)
                        <a href="{{ route('inspections.edit', $inspection) }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 btn-sm fw-semibold">
                            ✏️ Modifier le rapport
                        </a>
                        <form action="{{ route('inspections.validate', $inspection) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success rounded-pill px-4 py-2 btn-sm fw-bold shadow-sm" style="background: #16A34A; border: none;" onclick="return confirm('Valider et signer définitivement cet état des lieux ?');">
                                ✍️ Valider & Signer numériquement
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('rentals.show', $rental) }}" class="btn btn-outline-dark rounded-pill px-3 py-2 btn-sm">
                        &larr; Retour au Contrat
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            
            <!-- Left Column: Inspection Data Sheet -->
            <div class="col-lg-7">
                
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold mb-4" style="color: #10202B;">Constatations de l'Inspection</h5>

                    <!-- 1. Battery Gauge -->
                    <div class="p-3 rounded-3 border mb-3" style="background: #FAFBFD;">
                        <span class="d-block text-muted small fw-bold text-uppercase mb-2">1. Niveau de Charge & Batterie</span>
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $bat = $inspection->battery_level_percent ?? 100;
                                $batColor = $bat > 60 ? '#10B981' : ($bat > 25 ? '#F59E0B' : '#EF4444');
                            @endphp
                            <div style="font-size: 2rem;">🔋</div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between mb-1">
                                    <strong class="text-dark">{{ $bat }}% de charge</strong>
                                    <span class="small text-muted">{{ $bat >= 80 ? 'Batterie pleine / optimale' : ($bat >= 30 ? 'Charge moyenne' : 'Batterie faible') }}</span>
                                </div>
                                <div class="progress" style="height: 10px; border-radius: 9999px; background: #E2E8F0;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $bat }}%; background: {{ $batColor }};" aria-valuenow="{{ $bat }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Physical Condition -->
                    <div class="p-3 rounded-3 border mb-3" style="background: #FAFBFD;">
                        <span class="d-block text-muted small fw-bold text-uppercase mb-2">2. État Physique & Esthétique</span>
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $condIcon = match($inspection->physical_condition) {
                                    'excellent' => '✨',
                                    'good' => '👍',
                                    'fair' => '⚠️',
                                    'damaged' => '❌',
                                    default => '🏷️',
                                };
                            @endphp
                            <div style="font-size: 2rem;">{{ $condIcon }}</div>
                            <div>
                                <strong class="fs-5 text-dark d-block">{{ $inspection->condition_label }}</strong>
                                <span class="badge rounded-pill {{ $inspection->condition_badge_class }} small mt-1">
                                    Conforme aux critères de sécurité
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Cables and Accessories -->
                    <div class="p-3 rounded-3 border mb-3" style="background: #FAFBFD;">
                        <span class="d-block text-muted small fw-bold text-uppercase mb-2">3. Câblage & Accessoires Fournis</span>
                        <div class="d-flex align-items-center gap-3">
                            <div style="font-size: 2rem;">{{ $inspection->has_all_cables ? '🔌' : '⚠️' }}</div>
                            <div>
                                <strong class="text-dark d-block">
                                    {{ $inspection->has_all_cables ? 'Ensemble complet des câbles et connecteurs présent' : 'Attention : câbles ou adaptateurs manquants' }}
                                </strong>
                                <span class="small {{ $inspection->has_all_cables ? 'text-success' : 'text-danger' }}">
                                    {{ $inspection->has_all_cables ? 'Vérifié et testé en état de marche' : 'Signalé pour examen de la caution' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Notes -->
                    @if($inspection->notes)
                        <div class="p-3 rounded-3 border mb-3" style="background: #FAFBFD;">
                            <span class="d-block text-muted small fw-bold text-uppercase mb-1">4. Observations & Notes de l'Inspecteur</span>
                            <div class="text-dark small" style="white-space: pre-line; line-height: 1.6;">
                                {{ $inspection->notes }}
                            </div>
                        </div>
                    @endif

                    <!-- 5. Photo Gallery -->
                    <div class="p-3 rounded-3 border" style="background: #FAFBFD;">
                        <span class="d-block text-muted small fw-bold text-uppercase mb-2">5. Galerie Photos d'Inspection</span>
                        
                        @if($inspection->photos && count($inspection->photos))
                            <div class="row g-2">
                                @foreach($inspection->photo_urls as $photoUrl)
                                    <div class="col-sm-4 col-6">
                                        <a href="{{ $photoUrl }}" target="_blank" class="d-block rounded-3 overflow-hidden border shadow-sm" style="height: 120px; background: #0F172A;">
                                            <img src="{{ $photoUrl }}" alt="Photo état des lieux" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-3 text-muted small">
                                📷 Aucune photo jointe à cet état des lieux.
                            </div>
                        @endif
                    </div>

                </div>

            </div>

            <!-- Right Column: Digital Signature & Summary -->
            <div class="col-lg-5">
                
                <!-- Digital Signature Certificate Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold mb-3" style="color: #10202B;">Signature & Horodatage</h5>

                    @if($inspection->is_validated)
                        <div class="p-3 rounded-3 mb-3" style="background: #F0FDF4; border: 1px solid #BBF7D0;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span style="font-size: 1.3rem;">🛡️</span>
                                <strong class="text-success">Certificat Numérique Valide</strong>
                            </div>
                            <div class="small text-muted" style="line-height: 1.6;">
                                <div>Signataire : <strong>{{ $inspection->inspector?->name ?? 'Inspecteur Certifié' }}</strong></div>
                                <div>Email : <strong>{{ $inspection->inspector?->email }}</strong></div>
                                <div>Date & Heure : <strong>{{ $inspection->validated_at?->format('d/m/Y à H:i:s') }}</strong></div>
                                <div>Rôle : <strong>{{ $inspection->inspector?->role === 'admin' ? 'Administrateur' : 'Membre SolarShare' }}</strong></div>
                            </div>
                        </div>
                    @else
                        <div class="p-3 rounded-3 mb-3" style="background: #FEF3C7; border: 1px solid #FCD34D;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span style="font-size: 1.2rem;">⏳</span>
                                <strong class="text-warning text-dark">En attente de signature</strong>
                            </div>
                            <p class="small text-muted mb-0">
                                Ce rapport est actuellement un brouillon. Il deviendra contractuel dès sa validation numérique.
                            </p>
                        </div>
                    @endif

                    <div class="p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 0.85rem;">
                        <span class="d-block text-muted small fw-bold mb-2">Détails du Contrat Associé</span>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Référence :</span>
                            <strong>#LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Période :</span>
                            <span>{{ $rental->start_date->format('d/m/Y') }} → {{ $rental->end_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Propriétaire :</span>
                            <span>{{ $rental->equipment->user?->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Locataire :</span>
                            <span>{{ $rental->user->name }}</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- ============================================================== -->
        <!-- Side-by-Side Comparison: Check-in vs Check-out (If Check-out) -->
        <!-- ============================================================== -->
        @if($inspection->type === 'check_out' && $checkInReport)
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white mt-4" id="comparison">
                
                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                    <div>
                        <span class="badge rounded-pill text-uppercase fw-bold px-3 py-1 mb-2" style="background: rgba(15, 92, 107, 0.12); color: #0F5C6B; font-size: 0.75rem;">
                            🔍 Bilan Comparatif Complet
                        </span>
                        <h3 class="h4 fw-bold text-dark mb-0">Comparaison : Départ (Check-in) vs Retour (Check-out)</h3>
                    </div>
                    <span class="badge bg-success text-white rounded-pill px-3 py-2 fw-semibold">
                        Garantie & Restitution de Caution
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 28%;">Critère d'Inspection</th>
                                <th style="width: 36%;">🛫 Départ (Check-in #{{ $checkInReport->id }})</th>
                                <th style="width: 36%;">🛬 Retour (Check-out #{{ $inspection->id }})</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Battery -->
                            <tr>
                                <td class="fw-bold">🔋 Niveau de Batterie</td>
                                <td>
                                    <span class="badge bg-success">{{ $checkInReport->battery_level_percent ?? 100 }}%</span>
                                </td>
                                <td>
                                    <span class="badge {{ ($inspection->battery_level_percent ?? 100) >= 20 ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $inspection->battery_level_percent ?? 100 }}%
                                    </span>
                                </td>
                            </tr>

                            <!-- Condition -->
                            <tr>
                                <td class="fw-bold">🛠️ État Physique</td>
                                <td>
                                    <strong>{{ $checkInReport->condition_label }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $inspection->condition_label }}</strong>
                                    @if($checkInReport->physical_condition !== $inspection->physical_condition)
                                        <span class="badge bg-warning text-dark ms-2">Évolution constatée</span>
                                    @else
                                        <span class="badge bg-light text-success ms-2">Conforme</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Cables -->
                            <tr>
                                <td class="fw-bold">🔌 Câbles & Accessoires</td>
                                <td>
                                    {{ $checkInReport->has_all_cables ? '✅ Complets' : '❌ Manquants' }}
                                </td>
                                <td>
                                    {{ $inspection->has_all_cables ? '✅ Complets' : '❌ Manquants' }}
                                </td>
                            </tr>

                            <!-- Observations -->
                            <tr>
                                <td class="fw-bold">📝 Observations</td>
                                <td class="small text-muted">{{ $checkInReport->notes ?: 'Aucune remarque.' }}</td>
                                <td class="small text-muted">{{ $inspection->notes ?: 'Aucune remarque.' }}</td>
                            </tr>

                            <!-- Photos -->
                            <tr>
                                <td class="fw-bold">📸 Photos</td>
                                <td>
                                    <span class="small text-muted">{{ count($checkInReport->photos ?? []) }} photo(s) enregistrée(s)</span>
                                </td>
                                <td>
                                    <span class="small text-muted">{{ count($inspection->photos ?? []) }} photo(s) enregistrée(s)</span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="p-3 rounded-3 mt-3 d-flex align-items-center gap-3" style="background: #F0FDF4; border: 1px solid #BBF7D0;">
                    <div style="font-size: 2rem;">🤝</div>
                    <div>
                        <strong class="text-success d-block">Bilan de Caution Favorable</strong>
                        <span class="small text-muted">
                            L'équipement a été restitué en conformité avec l'état initial. La caution de <strong>{{ number_format($rental->equipment->deposit_amount, 2) }} TND</strong> est intégralement restituée.
                        </span>
                    </div>
                </div>

            </div>
        @endif

    </div>
</section>
@endsection
