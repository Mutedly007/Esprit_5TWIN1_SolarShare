{{-- resources/views/lender/rentals/index.blade.php --}}
@extends('layouts.lender')

@section('title', 'Gestion des Prêts & Contrats — Espace Prêteur')

@push('styles')
<style>
    /* =========================================================
       SolarShare - Premium Rentals & Loans Table Styles
       ========================================================= */
    :root {
        --ss-teal-rgb: 15, 92, 107;
        --ss-amber-rgb: 255, 176, 32;
        --ss-orange-rgb: 255, 107, 61;
    }

    .rentals-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .rentals-kpi-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 1.25rem 1.4rem;
        box-shadow: 0 2px 8px -2px rgba(16, 32, 43, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .rentals-kpi-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: transparent;
        transition: background 0.2s ease;
    }

    .rentals-kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px -4px rgba(16, 32, 43, 0.08);
    }

    .rentals-kpi-card.kpi-total:hover::before { background: #0F5C6B; }
    .rentals-kpi-card.kpi-pending:hover::before { background: #F59E0B; }
    .rentals-kpi-card.kpi-active:hover::before { background: #10B981; }
    .rentals-kpi-card.kpi-revenue:hover::before { background: #FF6B3D; }

    .rentals-kpi-info .kpi-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748B;
        margin-bottom: 0.25rem;
        display: block;
    }

    .rentals-kpi-info .kpi-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0F172A;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .rentals-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Filter & Search Toolbar */
    .table-toolbar-wrap {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 3px 0 rgba(16, 32, 43, 0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .filter-tabs-strip {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .filter-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #475569;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .filter-tab-btn:hover {
        background: #F1F5F9;
        color: #0F172A;
        border-color: #CBD5E1;
    }

    .filter-tab-btn.is-active {
        background: #0F5C6B;
        color: #FFFFFF;
        border-color: #0F5C6B;
        box-shadow: 0 2px 8px rgba(15, 92, 107, 0.25);
    }

    .filter-tab-btn .tab-count {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.1rem 0.45rem;
        border-radius: 9999px;
        background: rgba(0, 0, 0, 0.06);
    }

    .filter-tab-btn.is-active .tab-count {
        background: rgba(255, 255, 255, 0.25);
        color: #FFFFFF;
    }

    .filter-tab-btn.tab-pending.is-active {
        background: #D97706;
        border-color: #D97706;
        box-shadow: 0 2px 8px rgba(217, 119, 6, 0.25);
    }

    .filter-tab-btn.tab-confirmed.is-active {
        background: #0284C7;
        border-color: #0284C7;
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
    }

    .filter-tab-btn.tab-active.is-active {
        background: #16A34A;
        border-color: #16A34A;
        box-shadow: 0 2px 8px rgba(22, 163, 74, 0.25);
    }

    .filter-tab-btn.tab-completed.is-active {
        background: #475569;
        border-color: #475569;
    }

    .search-filter-box {
        position: relative;
        min-width: 260px;
        flex: 1;
        max-width: 380px;
    }

    .search-filter-input {
        width: 100%;
        padding: 0.5rem 0.85rem 0.5rem 2.3rem;
        border-radius: 9999px;
        border: 1px solid #E2E8F0;
        background: #F8FAFC;
        font-size: 0.84rem;
        color: #0F172A;
        outline: none;
        transition: all 0.15s ease;
    }

    .search-filter-input:focus {
        background: #FFFFFF;
        border-color: #0F5C6B;
        box-shadow: 0 0 0 3px rgba(15, 92, 107, 0.12);
    }

    .search-icon-inside {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        pointer-events: none;
    }

    /* Main Rentals Table Card */
    .rentals-card-wrap {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 24px -4px rgba(16, 32, 43, 0.05), 0 1px 2px rgba(16, 32, 43, 0.02);
    }

    .rentals-card-header {
        padding: 1.1rem 1.4rem;
        border-bottom: 1px solid #E2E8F0;
        background: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .rentals-card-title {
        font-size: 0.96rem;
        font-weight: 700;
        color: #0F172A;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .rentals-card-badge {
        font-size: 0.72rem;
        font-weight: 700;
        background: rgba(15, 92, 107, 0.08);
        color: #0F5C6B;
        padding: 0.2rem 0.6rem;
        border-radius: 9999px;
    }

    /* Table Scrollable Container */
    .rentals-table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .rentals-table-container::-webkit-scrollbar {
        height: 6px;
    }
    .rentals-table-container::-webkit-scrollbar-track {
        background: #F8FAFC;
    }
    .rentals-table-container::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 4px;
    }
    .rentals-table-container::-webkit-scrollbar-thumb:hover {
        background: #94A3B8;
    }

    .rentals-custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.88rem;
        text-align: left;
    }

    .rentals-custom-table thead th {
        background: #F8FAFC;
        padding: 0.95rem 1.25rem;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #475569;
        border-bottom: 1px solid #E2E8F0;
        white-space: nowrap;
    }

    .rentals-custom-table tbody td {
        padding: 1.15rem 1.25rem;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
        color: #0F172A;
        background: #FFFFFF;
        transition: background 0.15s ease;
    }

    .rentals-custom-table tbody tr:hover td {
        background: #F8FAFC;
    }

    .rentals-custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Column Specific Styles */
    /* Ref Contrat */
    .contract-ref-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.82rem;
        font-weight: 700;
        color: #0F5C6B;
        background: rgba(15, 92, 107, 0.08);
        border: 1px solid rgba(15, 92, 107, 0.15);
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        white-space: nowrap;
    }

    .contract-date-sub {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.74rem;
        color: #64748B;
        margin-top: 0.3rem;
        white-space: nowrap;
    }

    /* Locataire / Client */
    .locataire-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 190px;
    }

    .locataire-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0F5C6B 0%, #1E3A8A 100%);
        color: #FFFFFF;
        font-size: 0.8rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(15, 92, 107, 0.2);
    }

    .locataire-info .locataire-name {
        display: block;
        font-weight: 700;
        color: #0F172A;
        font-size: 0.88rem;
        line-height: 1.25;
    }

    .locataire-info .locataire-email {
        display: block;
        font-size: 0.76rem;
        color: #64748B;
        margin-top: 0.1rem;
    }

    /* Matériel Concerné */
    .equipment-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 220px;
    }

    .equipment-thumb {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #F1F5F9;
        border: 1px solid #E2E8F0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .equipment-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .equipment-info {
        min-width: 0;
    }

    .equipment-name {
        display: block;
        font-weight: 700;
        color: #0F172A;
        font-size: 0.88rem;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 180px;
        text-decoration: none;
    }

    .equipment-name:hover {
        color: #0F5C6B;
    }

    .equipment-category-pill {
        display: inline-block;
        font-size: 0.72rem;
        font-weight: 500;
        color: #475569;
        background: #F1F5F9;
        padding: 0.12rem 0.45rem;
        border-radius: 4px;
        margin-top: 0.2rem;
    }

    /* Période & Durée */
    .period-cell {
        min-width: 175px;
    }

    .period-dates {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.84rem;
        font-weight: 700;
        color: #0F172A;
        white-space: nowrap;
    }

    .period-arrow {
        color: #94A3B8;
        font-weight: 400;
    }

    .period-duration-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.74rem;
        font-weight: 600;
        color: #0F5C6B;
        background: rgba(15, 92, 107, 0.08);
        padding: 0.15rem 0.5rem;
        border-radius: 9999px;
        margin-top: 0.3rem;
        white-space: nowrap;
    }

    /* Montant & Caution */
    .amount-cell {
        min-width: 140px;
    }

    .amount-total {
        font-size: 1rem;
        font-weight: 800;
        color: #C2410C;
        letter-spacing: -0.01em;
        white-space: nowrap;
    }

    .amount-currency {
        font-size: 0.78rem;
        font-weight: 700;
        color: #EA580C;
    }

    .amount-deposit {
        display: block;
        font-size: 0.72rem;
        color: #64748B;
        margin-top: 0.2rem;
        white-space: nowrap;
    }

    /* Status Pills */
    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.76rem;
        font-weight: 700;
        padding: 0.32rem 0.7rem;
        border-radius: 9999px;
        white-space: nowrap;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }

    .status-pending {
        background: #FEF3C7;
        color: #92400E;
        border: 1px solid #FDE68A;
    }
    .status-pending .status-dot {
        background: #F59E0B;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
    }

    .status-confirmed {
        background: #E0F2FE;
        color: #0369A1;
        border: 1px solid #BAE6FD;
    }
    .status-confirmed .status-dot {
        background: #0284C7;
    }

    .status-active {
        background: #DCFCE7;
        color: #166534;
        border: 1px solid #BBF7D0;
    }
    .status-active .status-dot {
        background: #16A34A;
        box-shadow: 0 0 0 2px rgba(22, 163, 74, 0.2);
    }

    .status-completed {
        background: #F1F5F9;
        color: #475569;
        border: 1px solid #E2E8F0;
    }
    .status-completed .status-dot {
        background: #64748B;
    }

    .status-cancelled {
        background: #FEE2E2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }
    .status-cancelled .status-dot {
        background: #EF4444;
    }

    /* États des Lieux Micro-Badges */
    .inspections-cell {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
        min-width: 155px;
    }

    .inspection-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.18rem 0.55rem;
        border-radius: 6px;
        white-space: nowrap;
        width: fit-content;
    }

    .inspection-badge-validated {
        background: #ECFDF5;
        color: #065F46;
        border: 1px solid #A7F3D0;
    }

    .inspection-badge-draft {
        background: #FFFBEB;
        color: #B45309;
        border: 1px solid #FDE68A;
    }

    .inspection-badge-pending {
        background: #F8FAFC;
        color: #94A3B8;
        border: 1px dashed #CBD5E1;
    }

    /* Actions Column & Buttons */
    .actions-cell-wrap {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.35rem;
        white-space: nowrap;
        min-width: 170px;
    }

    .btn-action-custom {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 0.42rem 0.75rem;
        border-radius: 9999px;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all 0.15s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        line-height: 1;
    }

    .btn-action-custom:hover {
        transform: translateY(-1px);
    }

    .btn-action-view {
        background: #FFFFFF;
        color: #334155;
        border-color: #E2E8F0;
        box-shadow: 0 1px 2px rgba(16, 32, 43, 0.04);
    }
    .btn-action-view:hover {
        background: #F8FAFC;
        color: #0F172A;
        border-color: #CBD5E1;
    }

    .btn-action-confirm {
        background: #10B981;
        color: #FFFFFF;
        border-color: #059669;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25);
    }
    .btn-action-confirm:hover {
        background: #059669;
        color: #FFFFFF;
    }

    .btn-action-checkin {
        background: #FFB020;
        color: #071520;
        border-color: #F59E0B;
        box-shadow: 0 2px 6px rgba(255, 176, 32, 0.25);
    }
    .btn-action-checkin:hover {
        background: #F59E0B;
        color: #071520;
    }

    .btn-action-checkout {
        background: #0F5C6B;
        color: #FFFFFF;
        border-color: #083E49;
        box-shadow: 0 2px 6px rgba(15, 92, 107, 0.25);
    }
    .btn-action-checkout:hover {
        background: #083E49;
        color: #FFFFFF;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .rentals-kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .rentals-kpi-grid {
            grid-template-columns: 1fr;
        }
        .table-toolbar-wrap {
            flex-direction: column;
            align-items: stretch;
        }
        .search-filter-box {
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="lender-page-content">

    <!-- Hero Greeting Bar -->
    <div class="lender-hero mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="sidebar-space-badge">
                    <span class="sidebar-pulse-dot"></span>
                    Espace Prêteur
                </span>
                <span style="font-size: 0.8rem; color: var(--ss-slate); font-weight: 500;">
                    Module 2 : Cycle de Location & Contrats
                </span>
            </div>
            <h1 class="hero-title" style="margin-bottom: 0.35rem;">Prêts & Contrats de Location</h1>
            <p class="hero-subtitle mb-0">
                Gérez en toute sérénité les demandes de location reçues, validez les réservations et réalisez les états des lieux numériques de départ et de retour.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('equipment.create') }}" class="btn-solar-primary" id="btn-add-equipment-top">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
                <span>Ajouter du matériel</span>
            </a>
            <a href="{{ route('marketplace') }}" class="btn-solar-secondary" target="_blank">
                <span>Catalogue Public ↗</span>
            </a>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="rentals-kpi-grid">
        <!-- Total Requests -->
        <div class="rentals-kpi-card kpi-total">
            <div class="rentals-kpi-info">
                <span class="kpi-label">Total Réservations</span>
                <span class="kpi-value">{{ $stats['total'] }}</span>
            </div>
            <div class="rentals-kpi-icon" style="background: rgba(15, 92, 107, 0.08); color: #0F5C6B;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </div>
        </div>

        <!-- Pending Confirmation -->
        <div class="rentals-kpi-card kpi-pending">
            <div class="rentals-kpi-info">
                <span class="kpi-label">À Confirmer</span>
                <span class="kpi-value" style="color: #D97706;">{{ $stats['pending'] }}</span>
            </div>
            <div class="rentals-kpi-icon" style="background: #FEF3C7; color: #D97706;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
        </div>

        <!-- Active Rentals -->
        <div class="rentals-kpi-card kpi-active">
            <div class="rentals-kpi-info">
                <span class="kpi-label">Prêts en cours</span>
                <span class="kpi-value" style="color: #16A34A;">{{ $stats['active'] }}</span>
            </div>
            <div class="rentals-kpi-icon" style="background: #DCFCE7; color: #16A34A;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
            </div>
        </div>

        <!-- Revenue -->
        <div class="rentals-kpi-card kpi-revenue">
            <div class="rentals-kpi-info">
                <span class="kpi-label">Revenus Estimés</span>
                <span class="kpi-value" style="color: #C2410C;">
                    {{ number_format($stats['revenue'], 2) }}
                    <span style="font-size: 0.9rem; font-weight: 700; color: #EA580C;">TND</span>
                </span>
            </div>
            <div class="rentals-kpi-icon" style="background: rgba(255, 107, 61, 0.1); color: #FF6B3D;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="table-toolbar-wrap">
        <div class="filter-tabs-strip">
            <a href="{{ route('lender.rentals.index', array_filter(['search' => request('search')])) }}" 
               class="filter-tab-btn {{ empty($status) ? 'is-active' : '' }}">
                <span>Toutes</span>
                <span class="tab-count">{{ $stats['total'] }}</span>
            </a>
            <a href="{{ route('lender.rentals.index', array_filter(['status' => 'pending', 'search' => request('search')])) }}" 
               class="filter-tab-btn tab-pending {{ $status === 'pending' ? 'is-active' : '' }}">
                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #F59E0B;"></span>
                <span>En attente</span>
                <span class="tab-count">{{ $stats['pending'] }}</span>
            </a>
            <a href="{{ route('lender.rentals.index', array_filter(['status' => 'confirmed', 'search' => request('search')])) }}" 
               class="filter-tab-btn tab-confirmed {{ $status === 'confirmed' ? 'is-active' : '' }}">
                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #0284C7;"></span>
                <span>Confirmées</span>
                <span class="tab-count">{{ $stats['confirmed'] }}</span>
            </a>
            <a href="{{ route('lender.rentals.index', array_filter(['status' => 'active', 'search' => request('search')])) }}" 
               class="filter-tab-btn tab-active {{ $status === 'active' ? 'is-active' : '' }}">
                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #16A34A;"></span>
                <span>En cours</span>
                <span class="tab-count">{{ $stats['active'] }}</span>
            </a>
            <a href="{{ route('lender.rentals.index', array_filter(['status' => 'completed', 'search' => request('search')])) }}" 
               class="filter-tab-btn tab-completed {{ $status === 'completed' ? 'is-active' : '' }}">
                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #64748B;"></span>
                <span>Terminées</span>
                <span class="tab-count">{{ $stats['completed'] }}</span>
            </a>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('lender.rentals.index') }}" class="search-filter-box">
            @if(!empty($status))
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <svg class="search-icon-inside" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Rechercher (contrat, client, matériel)..." 
                   class="search-filter-input">
        </form>
    </div>

    <!-- Rentals Table Card -->
    <div class="rentals-card-wrap">
        <div class="rentals-card-header">
            <div class="d-flex align-items-center gap-2">
                <h3 class="rentals-card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: #0F5C6B;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Contrats de Location Reçus</span>
                </h3>
                <span class="rentals-card-badge">{{ $rentals->total() }} au total</span>
            </div>

            @if(request('search'))
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size: 0.8rem; color: #64748B;">
                        Résultats pour : <strong style="color: #0F172A;">« {{ request('search') }} »</strong>
                    </span>
                    <a href="{{ route('lender.rentals.index', array_filter(['status' => $status])) }}" 
                       class="text-decoration-none small fw-bold" style="color: #C2410C;">
                        Effacer ✕
                    </a>
                </div>
            @endif
        </div>

        @if($rentals->isEmpty())
            <div class="p-5 text-center bg-white">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: #F8FAFC; border: 1px dashed #CBD5E1; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; color: #94A3B8;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="9" y1="15" x2="15" y2="15"></line>
                    </svg>
                </div>
                <h5 class="fw-bold text-dark mb-1">Aucune réservation trouvée</h5>
                <p class="text-muted small mx-auto mb-3" style="max-width: 440px;">
                    @if(request('search') || !empty($status))
                        Aucun contrat ne correspond aux critères sélectionnés. Vous pouvez réinitialiser les filtres pour revoir tous vos prêts.
                    @else
                        Vous n'avez pas encore reçu de demande de location pour vos équipements. Dès qu'un membre loue votre matériel solaire, sa réservation apparaîtra ici.
                    @endif
                </p>
                @if(request('search') || !empty($status))
                    <a href="{{ route('lender.rentals.index') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1">
                        Réinitialiser tous les filtres
                    </a>
                @endif
            </div>
        @else
            <div class="rentals-table-container">
                <table class="rentals-custom-table">
                    <thead>
                        <tr>
                            <th style="width: 140px;">Réf. Contrat</th>
                            <th style="min-width: 200px;">Locataire</th>
                            <th style="min-width: 230px;">Matériel Concerné</th>
                            <th style="min-width: 175px;">Période & Durée</th>
                            <th style="min-width: 140px;">Montant</th>
                            <th style="min-width: 130px;">Statut</th>
                            <th style="min-width: 165px;">États des Lieux</th>
                            <th style="text-align: right; min-width: 170px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rentals as $rental)
                            @php
                                $checkIn = $rental->checkInReport;
                                $checkOut = $rental->checkOutReport;
                            @endphp
                            <tr>
                                <!-- Réf Contrat & Date -->
                                <td>
                                    <span class="contract-ref-pill">
                                        #LOC-{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span class="contract-date-sub">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        {{ $rental->created_at->format('d/m/Y') }}
                                    </span>
                                </td>

                                <!-- Locataire -->
                                <td>
                                    <div class="locataire-cell">
                                        <div class="locataire-avatar" title="{{ $rental->user->name }}">
                                            {{ strtoupper(substr($rental->user->name, 0, 2)) }}
                                        </div>
                                        <div class="locataire-info">
                                            <span class="locataire-name">{{ $rental->user->name }}</span>
                                            <span class="locataire-email">{{ $rental->user->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Matériel Concerné -->
                                <td>
                                    <div class="equipment-cell">
                                        <div class="equipment-thumb">
                                            @if($rental->equipment?->image_url)
                                                <img src="{{ $rental->equipment->image_url }}" alt="{{ $rental->equipment->name }}">
                                            @else
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="5"></circle>
                                                    <line x1="12" y1="1" x2="12" y2="3"></line>
                                                    <line x1="12" y1="21" x2="12" y2="23"></line>
                                                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                                    <line x1="1" y1="12" x2="3" y2="12"></line>
                                                    <line x1="21" y1="12" x2="23" y2="12"></line>
                                                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                                                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="equipment-info">
                                            <a href="{{ route('marketplace.show', $rental->equipment) }}" 
                                               target="_blank" 
                                               class="equipment-name" 
                                               title="{{ $rental->equipment->name }}">
                                                {{ $rental->equipment->name }}
                                            </a>
                                            <span class="equipment-category-pill">
                                                {{ $rental->equipment->category?->name ?? 'Équipement Solaire' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Période & Durée -->
                                <td>
                                    <div class="period-cell">
                                        <div class="period-dates">
                                            <span>{{ $rental->start_date->format('d/m/Y') }}</span>
                                            <span class="period-arrow">→</span>
                                            <span>{{ $rental->end_date->format('d/m/Y') }}</span>
                                        </div>
                                        <span class="period-duration-tag">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            {{ $rental->duration_days }} jour{{ $rental->duration_days > 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Montant & Caution -->
                                <td>
                                    <div class="amount-cell">
                                        <span class="amount-total">{{ number_format($rental->total_price, 2) }}</span>
                                        <span class="amount-currency">TND</span>
                                        <span class="amount-deposit">
                                            Caution : <strong style="color: #475569;">{{ number_format($rental->equipment->deposit_amount, 2) }} TND</strong>
                                        </span>
                                    </div>
                                </td>

                                <!-- Statut -->
                                <td>
                                    @if($rental->status === 'pending')
                                        <span class="status-pill status-pending">
                                            <span class="status-dot"></span>
                                            En attente
                                        </span>
                                    @elseif($rental->status === 'confirmed')
                                        <span class="status-pill status-confirmed">
                                            <span class="status-dot"></span>
                                            Confirmée
                                        </span>
                                    @elseif($rental->status === 'active')
                                        <span class="status-pill status-active">
                                            <span class="status-dot"></span>
                                            En cours
                                        </span>
                                    @elseif($rental->status === 'completed')
                                        <span class="status-pill status-completed">
                                            <span class="status-dot"></span>
                                            Terminée
                                        </span>
                                    @elseif($rental->status === 'cancelled')
                                        <span class="status-pill status-cancelled">
                                            <span class="status-dot"></span>
                                            Annulée
                                        </span>
                                    @else
                                        <span class="status-pill status-completed">
                                            {{ $rental->status_label }}
                                        </span>
                                    @endif
                                </td>

                                <!-- États des Lieux -->
                                <td>
                                    <div class="inspections-cell">
                                        {{-- Check-in (Départ) --}}
                                        @if($checkIn && $checkIn->is_validated)
                                            <span class="inspection-badge inspection-badge-validated" title="État des lieux de départ validé">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                                Départ : Validé
                                            </span>
                                        @elseif($checkIn)
                                            <span class="inspection-badge inspection-badge-draft" title="État des lieux de départ en brouillon">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg>
                                                Départ : Brouillon
                                            </span>
                                        @else
                                            <span class="inspection-badge inspection-badge-pending" title="État des lieux de départ non commencé">
                                                Départ : À faire
                                            </span>
                                        @endif

                                        {{-- Check-out (Retour) --}}
                                        @if($checkOut && $checkOut->is_validated)
                                            <span class="inspection-badge inspection-badge-validated" title="État des lieux de retour validé">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>
                                                Retour : Validé
                                            </span>
                                        @elseif($checkOut)
                                            <span class="inspection-badge inspection-badge-draft" title="État des lieux de retour en brouillon">
                                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg>
                                                Retour : Brouillon
                                            </span>
                                        @else
                                            <span class="inspection-badge inspection-badge-pending" title="État des lieux de retour non commencé">
                                                Retour : À faire
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td style="text-align: right;">
                                    <div class="actions-cell-wrap">
                                        {{-- Valider (si en attente) --}}
                                        @if($rental->status === 'pending')
                                            <form action="{{ route('rentals.confirm', $rental) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer cette demande de location ?');">
                                                @csrf
                                                <button type="submit" class="btn-action-custom btn-action-confirm" title="Confirmer la réservation">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    </svg>
                                                    <span>Valider</span>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Check-in (Départ) --}}
                                        @if($rental->canDoCheckIn())
                                            <a href="{{ route('inspections.create', ['rental' => $rental->id, 'type' => 'check_in']) }}" 
                                               class="btn-action-custom btn-action-checkin" 
                                               title="Remplir le constat de départ">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                                </svg>
                                                <span>Check-in</span>
                                            </a>
                                        {{-- Check-out (Retour) --}}
                                        @elseif($rental->canDoCheckOut())
                                            <a href="{{ route('inspections.create', ['rental' => $rental->id, 'type' => 'check_out']) }}" 
                                               class="btn-action-custom btn-action-checkout" 
                                               title="Remplir le constat de retour">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="23 4 23 10 17 10"></polyline>
                                                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                                                </svg>
                                                <span>Check-out</span>
                                            </a>
                                        @endif

                                        {{-- Voir le contrat / Détails --}}
                                        <a href="{{ route('rentals.show', $rental) }}" 
                                           class="btn-action-custom btn-action-view" 
                                           title="Consulter le contrat de location">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span>Détails</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($rentals->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $rentals->links() }}
        </div>
    @endif

</div>
@endsection
