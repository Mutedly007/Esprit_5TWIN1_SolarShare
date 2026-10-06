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
                <!-- Add New Slot Card -->
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 14px;">
                    <div class="card-body">
                        <h5 class="fw-bold text-dark mb-1">
                            <i data-feather="plus-circle" class="text-primary me-2" style="width: 18px; height: 18px;"></i>
                            Ajouter une Plage Horaire de Permanence
                        </h5>
                        <p class="text-muted small mb-3">Définissez les jours et heures auxquels les emprunteurs peuvent déposer ou retirer du matériel solaire dans ce point relais.</p>

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
@endsection
