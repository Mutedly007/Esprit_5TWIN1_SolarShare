{{-- resources/views/pages/admin/hubs/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'SolarShare Admin — Ajouter un Hub de Retrait')

@section('content')

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    Ajouter un Point Relais / Hub Partenaire
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.hubs.index') }}">Hubs Relais</a></li>
                        <li class="breadcrumb-item text-muted active" aria-current="page">Nouveau Hub</li>
                    </ol>
                </nav>
            </div>
            <div class="col-5 align-self-center text-end">
                <a href="{{ route('admin.hubs.index') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-bold">
                    ← Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0" style="border-radius: 14px;">
                    <div class="card-body p-4">
                        <h4 class="card-title fw-bold text-dark mb-1">Informations du Point Relais</h4>
                        <p class="text-muted small mb-4">Remplissez les informations géographiques et opérationnelles du hub de collecte de matériel solaire.</p>

                        <form method="POST" action="{{ route('admin.hubs.store') }}">
                            @csrf

                            <div class="row g-3">
                                <!-- Nom du Hub -->
                                <div class="col-md-8">
                                    <label for="name" class="form-label fw-bold text-dark">Nom du Point Relais <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                        class="form-control @error('name') is-invalid @enderror" 
                                        placeholder="Ex: Casier Solaire Connecté — La Marsa Plage">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Capacité Maximale -->
                                <div class="col-md-4">
                                    <label for="max_capacity" class="form-label fw-bold text-dark">Capacité Max. (Équipements) <span class="text-danger">*</span></label>
                                    <input type="number" name="max_capacity" id="max_capacity" value="{{ old('max_capacity', 25) }}" min="1" max="500" 
                                        class="form-control @error('max_capacity') is-invalid @enderror">
                                    @error('max_capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Ville -->
                                <div class="col-md-4">
                                    <label for="city" class="form-label fw-bold text-dark">Gouvernorat / Ville <span class="text-danger">*</span></label>
                                    <input type="text" name="city" id="city" value="{{ old('city') }}" 
                                        class="form-control @error('city') is-invalid @enderror" 
                                        placeholder="Ex: Tunis, Ariana, Sousse...">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Code Postal -->
                                <div class="col-md-4">
                                    <label for="postal_code" class="form-label fw-bold text-dark">Code Postal <span class="text-danger">*</span></label>
                                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" 
                                        class="form-control @error('postal_code') is-invalid @enderror" 
                                        placeholder="Ex: 2070">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Téléphone de Contact -->
                                <div class="col-md-4">
                                    <label for="contact_phone" class="form-label fw-bold text-dark">Téléphone de Contact <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}" 
                                        class="form-control @error('contact_phone') is-invalid @enderror" 
                                        placeholder="Ex: +216 71 234 567">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Adresse Complète -->
                                <div class="col-12">
                                    <label for="address" class="form-label fw-bold text-dark">Adresse Physique Détaillée <span class="text-danger">*</span></label>
                                    <input type="text" name="address" id="address" value="{{ old('address') }}" 
                                        class="form-control @error('address') is-invalid @enderror" 
                                        placeholder="Numéro, rue, repère ou nom de l'établissement partenaire">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description / Consignes -->
                                <div class="col-12">
                                    <label for="description" class="form-label fw-bold text-dark">Description & Consignes de Retrait</label>
                                    <textarea name="description" id="description" rows="4" 
                                        class="form-control @error('description') is-invalid @enderror" 
                                        placeholder="Précisez les modalités d'accès, types de casiers, consignes pour les utilisateurs (code SMS, badge, accueil sur place)...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Statut Actif -->
                                <div class="col-12">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold text-dark ms-2" for="is_active">
                                            Rendre ce point relais actif immédiatement (visible sur le site public)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Submit / Cancel -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.hubs.index') }}" class="btn btn-light rounded-pill px-4">Annuler</a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: linear-gradient(135deg, #5f76e8, #7b8ff5); border: none;">
                                    Créer le Point Relais →
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
