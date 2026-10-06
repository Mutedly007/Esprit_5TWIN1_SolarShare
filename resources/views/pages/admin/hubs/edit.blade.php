{{-- resources/views/pages/admin/hubs/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'SolarShare Admin — Modifier le Hub Relais')

@section('content')

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    Modifier le Point Relais : {{ $hub->name }}
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.hubs.index') }}">Hubs Relais</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.hubs.show', $hub) }}">{{ $hub->name }}</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">Modification</li>
                    </ol>
                </nav>
            </div>
            <div class="col-5 align-self-center text-end">
                <a href="{{ route('admin.hubs.show', $hub) }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-bold">
                    ← Annuler et voir la fiche
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0" style="border-radius: 14px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h4 class="card-title fw-bold text-dark mb-1">Édition du Hub #{{ $hub->id }}</h4>
                                <p class="text-muted small mb-0">Modifiez les informations générales de cet emplacement de relais.</p>
                            </div>
                            <span class="badge {{ $hub->is_active ? 'bg-success' : 'bg-danger' }} px-3 py-2 rounded-pill">
                                {{ $hub->is_active ? 'Hub Actif' : 'Hub Inactif' }}
                            </span>
                        </div>

                        <form method="POST" action="{{ route('admin.hubs.update', $hub) }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <!-- Nom du Hub -->
                                <div class="col-md-8">
                                    <label for="name" class="form-label fw-bold text-dark">Nom du Point Relais <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $hub->name) }}" 
                                        class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Capacité Maximale -->
                                <div class="col-md-4">
                                    <label for="max_capacity" class="form-label fw-bold text-dark">Capacité Max. (Équipements) <span class="text-danger">*</span></label>
                                    <input type="number" name="max_capacity" id="max_capacity" value="{{ old('max_capacity', $hub->max_capacity) }}" min="1" max="500" 
                                        class="form-control @error('max_capacity') is-invalid @enderror">
                                    @error('max_capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Ville -->
                                <div class="col-md-4">
                                    <label for="city" class="form-label fw-bold text-dark">Gouvernorat / Ville <span class="text-danger">*</span></label>
                                    <input type="text" name="city" id="city" value="{{ old('city', $hub->city) }}" 
                                        class="form-control @error('city') is-invalid @enderror">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Code Postal -->
                                <div class="col-md-4">
                                    <label for="postal_code" class="form-label fw-bold text-dark">Code Postal <span class="text-danger">*</span></label>
                                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $hub->postal_code) }}" 
                                        class="form-control @error('postal_code') is-invalid @enderror">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Téléphone de Contact -->
                                <div class="col-md-4">
                                    <label for="contact_phone" class="form-label fw-bold text-dark">Téléphone de Contact <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $hub->contact_phone) }}" 
                                        class="form-control @error('contact_phone') is-invalid @enderror">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Adresse Complète -->
                                <div class="col-12">
                                    <label for="address" class="form-label fw-bold text-dark">Adresse Physique Détaillée <span class="text-danger">*</span></label>
                                    <input type="text" name="address" id="address" value="{{ old('address', $hub->address) }}" 
                                        class="form-control @error('address') is-invalid @enderror">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description / Consignes -->
                                <div class="col-12">
                                    <label for="description" class="form-label fw-bold text-dark">Description & Consignes de Retrait</label>
                                    <textarea name="description" id="description" rows="4" 
                                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $hub->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Statut Actif -->
                                <div class="col-12">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $hub->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold text-dark ms-2" for="is_active">
                                            Point relais actif et disponible pour les usagers
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Submit / Cancel -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.hubs.show', $hub) }}" class="btn btn-light rounded-pill px-4">Annuler</a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: linear-gradient(135deg, #5f76e8, #7b8ff5); border: none;">
                                    Enregistrer les modifications →
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
