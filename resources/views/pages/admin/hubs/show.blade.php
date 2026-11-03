{{-- resources/views/pages/admin/hubs/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'SolarShare Admin — Fiche Hub Relais & Créneaux')

@section('content')

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    {{ $hub->name }}
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.hubs.index') }}">Hubs Relais</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">{{ $hub->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-5 align-self-center text-end">
                <a href="{{ route('hubs.show', $hub) }}" target="_blank" class="btn btn-outline-info rounded-pill px-3 py-2 fw-bold me-2">
                    <i data-feather="external-link" style="width: 15px; height: 15px;"></i> Voir page publique
                </a>
                <a href="{{ route('admin.hubs.edit', $hub) }}" class="btn btn-primary rounded-pill px-3 py-2 fw-bold" style="background: linear-gradient(135deg, #5f76e8, #7b8ff5); border: none;">
                    <i data-feather="edit-2" style="width: 15px; height: 15px;"></i> Modifier le Hub
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <i data-feather="check-circle" class="me-2" style="width: 18px; height: 18px;"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <i data-feather="alert-circle" class="me-2" style="width: 18px; height: 18px;"></i>
                <strong>Des erreurs ont été détectées :</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Left Column: Hub Summary Card -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0" style="border-radius: 14px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge {{ $hub->is_active ? 'bg-success' : 'bg-danger' }} px-3 py-1 rounded-pill">
                                {{ $hub->is_active ? '● Opérationnel' : '● Suspendu' }}
                            </span>
                            <form method="POST" action="{{ route('admin.hubs.toggle', $hub) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill" style="font-size: 0.75rem;">
                                    Changer statut
                                </button>
                            </form>
                        </div>

                        <h4 class="fw-bold text-dark mb-2">{{ $hub->name }}</h4>
                        <div class="mb-3">
                            <span class="badge bg-primary-subtle text-primary fw-bold fs-6">{{ $hub->city }}</span>
                            <span class="text-muted small ms-2">CP: {{ $hub->postal_code }}</span>
                        </div>

                        <div class="list-group list-group-flush small">
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between">
                                <span class="text-muted"><i data-feather="map-pin" style="width: 14px; height: 14px;" class="me-1"></i> Adresse</span>
                                <span class="fw-bold text-dark text-end" style="max-width: 60%;">{{ $hub->address }}</span>
                            </div>
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between">
                                <span class="text-muted"><i data-feather="phone" style="width: 14px; height: 14px;" class="me-1"></i> Contact</span>
                                <span class="fw-bold text-dark">{{ $hub->contact_phone }}</span>
                            </div>
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between">
                                <span class="text-muted"><i data-feather="box" style="width: 14px; height: 14px;" class="me-1"></i> Capacité de casiers</span>
                                <span class="fw-bold text-primary">{{ $hub->max_capacity }} équipements</span>
                            </div>
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between">
                                <span class="text-muted"><i data-feather="clock" style="width: 14px; height: 14px;" class="me-1"></i> Total Créneaux</span>
                                <span class="fw-bold text-dark">{{ $hub->operatingSlots->count() }} plage(s)</span>
                            </div>
                        </div>

                        @if($hub->description)
                            <div class="mt-3 p-3 bg-light rounded-3 small">
                                <strong class="d-block text-dark mb-1">Consignes & Description :</strong>
                                <p class="text-muted mb-0">{{ $hub->description }}</p>
                            </div>
                        @endif

                        <div class="mt-4 pt-3 border-top d-flex gap-2">
                            <a href="{{ route('admin.hubs.edit', $hub) }}" class="btn btn-sm btn-outline-primary rounded-pill w-100 fw-bold">
                                <i data-feather="edit" style="width: 14px; height: 14px;"></i> Modifier
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#deleteHubModal">
                                <i data-feather="trash-2" style="width: 14px; height: 14px;"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Operating Slots (1-N Relation CRUD) -->
            <div class="col-lg-8">
                <!-- Add Slots Section: AI Schedule Assistant + Manual Form Tabs -->
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-0 px-4">
                        <ul class="nav nav-pills" id="slotManagementTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold rounded-pill px-3 py-2" id="ai-tab" data-bs-toggle="pill" data-bs-target="#ai-slot-pane" type="button" role="tab" style="font-size: 0.85rem;">
                                    <i data-feather="cpu" class="me-1" style="width: 15px; height: 15px;"></i>
                                    ✨ Assistant IA d'Horaires (Gemini)
                                </button>
                            </li>
                            <li class="nav-item ms-2" role="presentation">
                                <button class="nav-link fw-bold rounded-pill px-3 py-2 text-muted" id="manual-tab" data-bs-toggle="pill" data-bs-target="#manual-slot-pane" type="button" role="tab" style="font-size: 0.85rem;">
                                    <i data-feather="plus-circle" class="me-1" style="width: 15px; height: 15px;"></i>
                                    Ajout Manuel Unitaire
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content" id="slotManagementTabContent">
                            <!-- TAB 1: AI Schedule Assistant -->
                            <div class="tab-pane fade show active" id="ai-slot-pane" role="tabpanel">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h5 class="fw-bold text-dark mb-0">
                                        <i data-feather="zap" class="text-warning me-2" style="width: 18px; height: 18px;"></i>
                                        Assistant IA : Générateur de Plages Horaires
                                    </h5>
                                    <span class="badge rounded-pill text-white px-3 py-1" style="background: linear-gradient(135deg, #10B981, #059669); font-size: 0.72rem;">
                                        Gemini IA ● Prévisualisation sécurisée
                                    </span>
                                </div>
                                <p class="text-muted small mb-3">
                                    Décrivez les horaires d'ouverture avec vos propres mots. L'IA va structurer les créneaux jour par jour, séparer automatiquement les pauses déjeuners, et vérifier les chevauchements avant enregistrement.
                                </p>

                                <div class="mb-3">
                                    <label for="ai_schedule_input" class="form-label small fw-bold text-dark">
                                        Décrivez les horaires d'ouverture <span class="text-danger">*</span>
                                    </label>
                                    <textarea id="ai_schedule_input" rows="3" class="form-control" style="border-radius: 10px;" 
                                        placeholder="Exemple : Open Monday to Friday 9am to 6pm, Saturday morning only until 1pm, closed Sunday. Lunch break 12:30 to 14:00 on weekdays.">Ouvert du lundi au vendredi de 9h à 18h avec pause déjeuner de 12h30 à 14h, samedi matin jusqu'à 13h, fermé le dimanche.</textarea>
                                </div>

                                <!-- Quick Prompt Examples -->
                                <div class="mb-3 d-flex flex-wrap align-items-center gap-1">
                                    <span class="text-muted small me-1">Exemples rapides :</span>
                                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-2 py-0 text-muted small prompt-shortcut" 
                                        data-prompt="Ouvert du lundi au vendredi de 9h à 18h avec pause déjeuner de 12h30 à 14h, samedi matin jusqu'à 13h, fermé le dimanche.">
                                        💡 Semaine 9h-18h (pause midi) + Samedi matin
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-2 py-0 text-muted small prompt-shortcut" 
                                        data-prompt="Ouvert en continu du lundi au samedi de 08:30 à 19:30, fermé le dimanche.">
                                        💡 Continu Lundi-Samedi 8h30-19h30
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-2 py-0 text-muted small prompt-shortcut" 
                                        data-prompt="Lundi, mercredi et vendredi matin de 8h à 12h. Mardi et jeudi après-midi de 14h à 18h. Samedi et dimanche fermé.">
                                        💡 Créneaux alternés matin / après-midi
                                    </button>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" id="btn-generate-ai" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: linear-gradient(135deg, #5f76e8, #7b8ff5); border: none;">
                                        <i data-feather="cpu" class="me-1" style="width: 15px; height: 15px;"></i>
                                        Générer et Prévisualiser avec l'IA
                                    </button>
                                    <span class="text-muted small">
                                        <i data-feather="info" style="width: 13px; height: 13px;"></i> Rien n'est enregistré dans la base avant confirmation.
                                    </span>
                                </div>

                                <!-- Loading Indicator -->
                                <div id="ai-loading" class="mt-3 p-3 bg-light rounded-3 text-center d-none">
                                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                    <span class="text-dark small fw-bold">L'IA Gemini analyse votre texte et calcule les créneaux non-chevauchants...</span>
                                </div>

                                <!-- Error Box -->
                                <div id="ai-error" class="alert alert-danger alert-dismissible fade show mt-3 d-none border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                                    <i data-feather="alert-triangle" class="me-1" style="width: 16px; height: 16px;"></i>
                                    <span id="ai-error-text"></span>
                                    <button type="button" class="btn-close" onclick="document.getElementById('ai-error').classList.add('d-none')"></button>
                                </div>

                                <!-- AI Interactive Preview Container -->
                                <div id="ai-preview-box" class="mt-4 pt-3 border-top d-none">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0">
                                                <i data-feather="check-square" class="text-success me-1" style="width: 16px; height: 16px;"></i>
                                                Proposition de l'IA (<span id="ai-count-badge">0</span> créneaux détectés)
                                            </h6>
                                            <small class="text-muted">Vous pouvez ajuster les horaires, modifier les notes ou retirer des lignes avant de valider.</small>
                                        </div>
                                        <div id="ai-warnings-badge" class="d-none">
                                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">
                                                ⚠️ Chevauchements détectés (ajustez ou supprimez)
                                            </span>
                                        </div>
                                    </div>

                                    <form id="ai-apply-form" method="POST" action="{{ route('admin.hubs.ai-apply-slots', $hub) }}">
                                        @csrf
                                        <div class="table-responsive bg-light p-2 rounded-3 mb-3">
                                            <table class="table table-sm table-bordered bg-white align-middle mb-0" id="ai-preview-table">
                                                <thead class="table-light small">
                                                    <tr>
                                                        <th style="width: 22%;">Jour</th>
                                                        <th style="width: 18%;">Ouverture</th>
                                                        <th style="width: 18%;">Fermeture</th>
                                                        <th>Notes & Contexte</th>
                                                        <th style="width: 15%;">Contrôle</th>
                                                        <th style="width: 5%;" class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="ai-preview-rows" class="small">
                                                    <!-- Dynamic rows will be inserted here via JS -->
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between">
                                            <button type="button" id="btn-add-preview-row" class="btn btn-sm btn-outline-secondary rounded-pill">
                                                + Ajouter une ligne manuelle au lot
                                            </button>

                                            <div class="d-flex gap-2">
                                                <button type="button" id="btn-cancel-preview" class="btn btn-sm btn-light border rounded-pill px-3">
                                                    Annuler
                                                </button>
                                                <button type="submit" id="btn-confirm-ai-slots" class="btn btn-sm btn-success rounded-pill px-4 fw-bold">
                                                    <i data-feather="check" class="me-1" style="width: 14px; height: 14px;"></i>
                                                    Confirmer et Enregistrer ces Créneaux
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- TAB 2: Classic Manual Slot Form -->
                            <div class="tab-pane fade" id="manual-slot-pane" role="tabpanel">
                                <h5 class="fw-bold text-dark mb-1">
                                    <i data-feather="plus-circle" class="text-primary me-2" style="width: 18px; height: 18px;"></i>
                                    Ajouter une Plage Horaire Unitaire
                                </h5>
                                <p class="text-muted small mb-3">Formulaire classique pour ajouter un seul créneau horaire à ce point relais.</p>

                                <form method="POST" action="{{ route('admin.hubs.slots.store', $hub) }}" class="row g-3 align-items-end">
                                    @csrf
                                    <input type="hidden" name="collection_hub_id" value="{{ $hub->id }}">

                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold text-dark mb-1">Jour de la semaine <span class="text-danger">*</span></label>
                                        <select name="day_of_week" class="form-select form-select-sm @error('day_of_week') is-invalid @enderror" required>
                                            <option value="">Choisir un jour...</option>
                                            @foreach($daysOfWeek as $d)
                                                <option value="{{ $d }}" {{ old('day_of_week') === $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
                                            @endforeach
                                        </select>
                                        @error('day_of_week')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label small fw-bold text-dark mb-1">Ouverture <span class="text-danger">*</span></label>
                                        <input type="time" name="open_time" value="{{ old('open_time', '08:30') }}" 
                                            class="form-control form-control-sm @error('open_time') is-invalid @enderror" required>
                                        @error('open_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label small fw-bold text-dark mb-1">Fermeture <span class="text-danger">*</span></label>
                                        <input type="time" name="close_time" value="{{ old('close_time', '18:30') }}" 
                                            class="form-control form-control-sm @error('close_time') is-invalid @enderror" required>
                                        @error('close_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold text-dark mb-1">Type / Remarques</label>
                                        <input type="text" name="notes" value="{{ old('notes') }}" 
                                            class="form-control form-control-sm @error('notes') is-invalid @enderror" 
                                            placeholder="Ex: Casiers 24/7, Permanence...">
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill w-100 fw-bold" style="background: linear-gradient(135deg, #5f76e8, #7b8ff5); border: none;">
                                            + Ajouter
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Existing Slots Table -->
                <div class="card shadow-sm border-0" style="border-radius: 14px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold text-dark mb-0">
                                <i data-feather="calendar" class="text-info me-2" style="width: 18px; height: 18px;"></i>
                                Plages Horaires Actuelles ({{ $hub->operatingSlots->count() }})
                            </h5>
                            <span class="badge bg-light text-muted">Relation 1 - N avec {{ $hub->name }}</span>
                        </div>

                        @if($hub->operatingSlots->isEmpty())
                            <div class="text-center py-4 bg-light rounded-3">
                                <i data-feather="clock" class="text-muted mb-2" style="width: 32px; height: 32px;"></i>
                                <p class="text-muted small mb-0">Aucun créneau d'ouverture configuré pour ce point relais pour l'instant.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light small">
                                        <tr>
                                            <th>Jour</th>
                                            <th>Horaires d'Ouverture</th>
                                            <th>Remarques / Modalités</th>
                                            <th>Statut</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($hub->operatingSlots as $slot)
                                            <tr>
                                                <td class="fw-bold text-dark">
                                                    {{ $slot->formatted_day }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-info-subtle text-info fw-bold">
                                                        <i data-feather="clock" style="width: 12px; height: 12px;" class="me-1"></i>
                                                        {{ $slot->formatted_hours }}
                                                    </span>
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $slot->notes ?: 'Permanence normale' }}
                                                </td>
                                                <td>
                                                    <form method="POST" action="{{ route('admin.hubs.slots.toggle', $slot) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm rounded-pill {{ $slot->is_active ? 'btn-success' : 'btn-outline-secondary' }}" style="font-size: 0.7rem;">
                                                            {{ $slot->is_active ? 'Actif' : 'Suspendu' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <!-- Edit Modal Trigger -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill me-1" data-bs-toggle="modal" data-bs-target="#editSlotModal-{{ $slot->id }}" title="Modifier ce créneau">
                                                        <i data-feather="edit-2" style="width: 12px; height: 12px;"></i>
                                                    </button>
                                                    
                                                    <!-- Delete Slot Form -->
                                                    <form method="POST" action="{{ route('admin.hubs.slots.destroy', $slot) }}" class="d-inline" onsubmit="return confirm('Supprimer ce créneau horaire ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" title="Supprimer">
                                                            <i data-feather="trash-2" style="width: 12px; height: 12px;"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Edit Slot Modal -->
                                                    <div class="modal fade" id="editSlotModal-{{ $slot->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered text-start">
                                                            <div class="modal-content">
                                                                <form method="POST" action="{{ route('admin.hubs.slots.update', $slot) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="hidden" name="collection_hub_id" value="{{ $hub->id }}">

                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title fw-bold">Modifier le Créneau ({{ $slot->formatted_day }})</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label class="form-label small fw-bold">Jour de la semaine</label>
                                                                            <select name="day_of_week" class="form-select form-select-sm" required>
                                                                                @foreach($daysOfWeek as $d)
                                                                                    <option value="{{ $d }}" {{ $slot->day_of_week === $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="row g-2 mb-3">
                                                                            <div class="col-6">
                                                                                <label class="form-label small fw-bold">Heure d'ouverture</label>
                                                                                <input type="time" name="open_time" value="{{ $slot->open_time }}" class="form-control form-control-sm" required>
                                                                            </div>
                                                                            <div class="col-6">
                                                                                <label class="form-label small fw-bold">Heure de fermeture</label>
                                                                                <input type="time" name="close_time" value="{{ $slot->close_time }}" class="form-control form-control-sm" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label class="form-label small fw-bold">Remarques / Modalités</label>
                                                                            <input type="text" name="notes" value="{{ $slot->notes }}" class="form-control form-control-sm">
                                                                        </div>
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input" type="checkbox" name="is_active" id="slot_active_{{ $slot->id }}" value="1" {{ $slot->is_active ? 'checked' : '' }}>
                                                                            <label class="form-check-label small fw-bold" for="slot_active_{{ $slot->id }}">Créneau actif</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-light btn-sm rounded-pill" data-bs-dismiss="modal">Annuler</button>
                                                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill fw-bold">Enregistrer</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

<!-- Delete Hub Modal Confirmation -->
<div class="modal fade" id="deleteHubModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">Confirmer la suppression du Hub</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-muted">
                Êtes-vous sûr de vouloir supprimer définitivement le point relais <strong>« {{ $hub->name }} »</strong> ?
                <div class="alert alert-warning mt-3 mb-0" style="font-size: 0.85rem;">
                    <i data-feather="alert-triangle" style="width: 14px; height: 14px;"></i>
                    Tous les <strong>{{ $hub->operatingSlots->count() }} créneaux horaires</strong> rattachés à ce hub seront immédiatement supprimés.
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
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const aiInput = document.getElementById('ai_schedule_input');
    const btnGenerate = document.getElementById('btn-generate-ai');
    const loadingBox = document.getElementById('ai-loading');
    const errorBox = document.getElementById('ai-error');
    const errorText = document.getElementById('ai-error-text');
    const previewBox = document.getElementById('ai-preview-box');
    const previewRows = document.getElementById('ai-preview-rows');
    const countBadge = document.getElementById('ai-count-badge');
    const warningsBadge = document.getElementById('ai-warnings-badge');
    const btnAddRow = document.getElementById('btn-add-preview-row');
    const btnCancel = document.getElementById('btn-cancel-preview');

    const daysList = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

    // 1. Shortcut prompt pills
    document.querySelectorAll('.prompt-shortcut').forEach(function (pill) {
        pill.addEventListener('click', function () {
            aiInput.value = this.getAttribute('data-prompt');
            aiInput.focus();
        });
    });

    // 2. Click on Generate Button
    if (btnGenerate) {
        btnGenerate.addEventListener('click', function () {
            const promptText = (aiInput.value || '').trim();
            if (promptText.length < 5) {
                showError('Veuillez décrire plus en détail vos horaires d\'ouverture (minimum 5 caractères).');
                return;
            }

            hideError();
            loadingBox.classList.remove('d-none');
            previewBox.classList.add('d-none');
            btnGenerate.disabled = true;
            btnGenerate.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Analyse en cours...';

            fetch("{{ route('admin.hubs.ai-generate-slots', $hub) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ description: promptText })
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || 'Erreur lors de la génération des créneaux.');
                }
                return data;
            })
            .then(data => {
                loadingBox.classList.add('d-none');
                btnGenerate.disabled = false;
                btnGenerate.innerHTML = '<i data-feather="cpu" class="me-1" style="width: 15px; height: 15px;"></i> Regénérer avec l\'IA';

                if (!data.slots || data.slots.length === 0) {
                    showError('Aucune plage horaire n\'a été détectée. Veuillez reformuler votre description.');
                    return;
                }

                renderPreview(data.slots, data.has_warnings);
            })
            .catch(err => {
                loadingBox.classList.add('d-none');
                btnGenerate.disabled = false;
                btnGenerate.innerHTML = '<i data-feather="cpu" class="me-1" style="width: 15px; height: 15px;"></i> Générer et Prévisualiser avec l\'IA';
                showError(err.message || 'Une erreur est survenue lors de la communication avec l\'IA Gemini.');
            });
        });
    }

    // Helper: render preview table
    function renderPreview(slots, hasWarnings) {
        previewRows.innerHTML = '';
        countBadge.textContent = slots.length;

        if (hasWarnings) {
            warningsBadge.classList.remove('d-none');
        } else {
            warningsBadge.classList.add('d-none');
        }

        slots.forEach((slot, index) => {
            appendRowToTable(slot, index);
        });

        previewBox.classList.remove('d-none');
        if (window.feather) {
            feather.replace();
        }
        previewBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Helper: append a row
    function appendRowToTable(slot, index) {
        const tr = document.createElement('tr');
        if (!slot.is_valid && slot.warning) {
            tr.classList.add('table-warning');
        }

        let dayOptions = '';
        daysList.forEach(d => {
            const isSelected = (slot.day_of_week === d) ? 'selected' : '';
            const cap = d.charAt(0).toUpperCase() + d.slice(1);
            dayOptions += `<option value="${d}" ${isSelected}>${cap}</option>`;
        });

        const statusBadge = slot.is_valid
            ? `<span class="badge bg-success-subtle text-success fw-bold px-2 py-1"><i data-feather="check" style="width: 12px; height: 12px;"></i> Prêt</span>`
            : `<span class="badge bg-warning-subtle text-dark fw-bold px-2 py-1" title="${slot.warning || ''}">⚠️ ${slot.warning || 'Ajustement requis'}</span>`;

        tr.innerHTML = `
            <td>
                <select name="slots[${index}][day_of_week]" class="form-select form-select-sm" required>
                    ${dayOptions}
                </select>
            </td>
            <td>
                <input type="time" name="slots[${index}][open_time]" value="${slot.open_time}" class="form-control form-control-sm" required>
            </td>
            <td>
                <input type="time" name="slots[${index}][close_time]" value="${slot.close_time}" class="form-control form-control-sm" required>
            </td>
            <td>
                <input type="text" name="slots[${index}][notes]" value="${slot.notes || 'Généré par IA'}" class="form-control form-control-sm" placeholder="Remarque">
            </td>
            <td>
                ${statusBadge}
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle remove-ai-row" title="Retirer ce créneau">
                    <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                </button>
            </td>
        `;

        // Delete row event
        tr.querySelector('.remove-ai-row').addEventListener('click', function () {
            tr.remove();
            reindexRows();
        });

        previewRows.appendChild(tr);
    }

    // Helper: Re-index row input names after removal or addition
    function reindexRows() {
        const rows = previewRows.querySelectorAll('tr');
        countBadge.textContent = rows.length;
        rows.forEach((row, i) => {
            const daySelect = row.querySelector('select');
            const timeInputs = row.querySelectorAll('input[type="time"]');
            const noteInput = row.querySelector('input[type="text"]');

            if (daySelect) daySelect.name = `slots[${i}][day_of_week]`;
            if (timeInputs[0]) timeInputs[0].name = `slots[${i}][open_time]`;
            if (timeInputs[1]) timeInputs[1].name = `slots[${i}][close_time]`;
            if (noteInput) noteInput.name = `slots[${i}][notes]`;
        });

        if (rows.length === 0) {
            previewBox.classList.add('d-none');
        }
    }

    // 3. Add manual row to preview batch
    if (btnAddRow) {
        btnAddRow.addEventListener('click', function () {
            const newIndex = previewRows.querySelectorAll('tr').length;
            appendRowToTable({
                day_of_week: 'lundi',
                open_time: '08:30',
                close_time: '18:30',
                notes: 'Ajout manuel au lot',
                is_valid: true,
                warning: null
            }, newIndex);

            if (window.feather) {
                feather.replace();
            }
            countBadge.textContent = newIndex + 1;
        });
    }

    // 4. Cancel preview
    if (btnCancel) {
        btnCancel.addEventListener('click', function () {
            previewBox.classList.add('d-none');
            previewRows.innerHTML = '';
        });
    }

    function showError(msg) {
        errorText.textContent = msg;
        errorBox.classList.remove('d-none');
        if (window.feather) feather.replace();
    }

    function hideError() {
        errorBox.classList.add('d-none');
    }
});
</script>
@endpush
@endsection
