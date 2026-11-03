{{-- resources/views/pages/front/kits/show.blade.php --}}
@extends('layouts.solartec')

@section('title', $energyKit->title . ' — ' . __('messages.kits.show_title_suffix'))
@section('meta_description', Str::limit(strip_tags($energyKit->description), 150))

@section('content')
@php $isRtl = app()->getLocale() === 'ar'; @endphp
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">{{ __('messages.nav.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('energy-kits.index') }}" style="color: #0F5C6B; text-decoration: none;">{{ __('messages.nav.packs') }}</a></li>
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

                        <span style="position: absolute; top: 16px; inset-inline-start: 16px; {{ $energyKit->target_use_badge_style }}; font-size: 0.82rem; font-weight: 700; padding: 0.35rem 0.85rem; border-radius: 9999px;">
                            {{ $energyKit->target_use_label }}
                        </span>

                        @if($energyKit->discount_percentage > 0)
                            <span style="position: absolute; top: 16px; inset-inline-end: 16px; background: linear-gradient(135deg, #FFB020, #FF6B3D); color: #071520; font-size: 0.85rem; font-weight: 800; padding: 0.4rem 0.9rem; border-radius: 9999px; box-shadow: 0 4px 12px rgba(255, 107, 61, 0.4);">
                                -{{ rtrim(rtrim(number_format($energyKit->discount_percentage, 1), '0'), '.') }}% {{ __('messages.kits.discount_badge') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                        <span class="badge rounded-pill text-uppercase fw-bold px-3 py-1" style="background: rgba(15, 92, 107, 0.12); color: #0F5C6B; font-size: 0.75rem;">
                            {{ __('messages.kits.combined_pack_badge', ['count' => $energyKit->equipment->count()]) }}
                        </span>
                        
                        @auth
                            @if(auth()->user()->isAdmin() || auth()->id() === $energyKit->user_id)
                                <div class="d-flex align-items-center gap-2">
                                    <form action="{{ route('energy-kits.toggle', $energyKit) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm rounded-pill px-3 fw-bold {{ $energyKit->is_active ? 'btn-success text-white' : 'btn-secondary text-white' }}" title="{{ __('messages.kits.toggle_title') }}">
                                            {{ $energyKit->is_active ? __('messages.kits.active_online') : __('messages.kits.inactive_hidden') }}
                                        </button>
                                    </form>
                                    <a href="{{ route('energy-kits.edit', $energyKit) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        ✏️ {{ __('messages.common.edit') }}
                                    </a>
                                    <form action="{{ route('energy-kits.destroy', $energyKit) }}" method="POST" class="d-inline" onsubmit="return confirm(@js(__('messages.kits.confirm_delete_pack')));">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            🗑️ {{ __('messages.common.delete') }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <h1 class="h2 fw-bold text-dark mb-3">{{ $energyKit->title }}</h1>
                    
                    <p class="text-muted" style="line-height: 1.7; font-size: 0.96rem;">
                        {{ $energyKit->description ?: __('messages.kits.show_default_desc') }}
                    </p>

                    <!-- Combined Specs Grid -->
                    <div class="row g-2 pt-2 border-top">
                        <div class="col-sm-4 col-6">
                            <div class="p-2 rounded-3 text-center" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                <span class="d-block text-muted small fw-bold">⚡ {{ __('messages.kits.total_power') }}</span>
                                <strong class="fs-5 text-dark">{{ $energyKit->total_power_watts }} W</strong>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="p-2 rounded-3 text-center" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                <span class="d-block text-muted small fw-bold">🔋 {{ __('messages.kits.total_capacity') }}</span>
                                <strong class="fs-5 text-dark">{{ $energyKit->total_capacity_wh }} Wh</strong>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <div class="p-2 rounded-3 text-center" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                                <span class="d-block text-muted small fw-bold">🛡️ {{ __('messages.kits.total_deposit') }}</span>
                                <strong class="fs-5 text-dark">{{ number_format($energyKit->total_deposit, 2) }} {{ __('messages.kits.currency') }}</strong>
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
                                🧩 {{ __('messages.kits.composition_badge') }}
                            </span>
                            <h4 class="h5 fw-bold text-dark mb-0">{{ __('messages.kits.included_title') }}</h4>
                        </div>
                        
                        @auth
                            @if(auth()->user()->isAdmin() || auth()->id() === $energyKit->user_id)
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#attachEquipmentModal">
                                    + {{ __('messages.kits.add_equipment_btn') }}
                                </button>
                            @endif
                        @endauth
                    </div>

                    <p class="text-muted small mb-4">
                        {!! __('messages.kits.composition_intro', ['count' => $energyKit->equipment->count()]) !!}
                    </p>

                    @if($energyKit->equipment->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">⚠️</div>
                            <p>{{ __('messages.kits.no_equipment_in_pack') }}</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('messages.kits.col_equipment') }}</th>
                                        <th>{{ __('messages.kits.col_qty') }}</th>
                                        <th>{{ __('messages.kits.col_options') }}</th>
                                        <th>{{ __('messages.kits.col_daily_rate') }}</th>
                                        @auth
                                            @if(auth()->user()->isAdmin() || auth()->id() === $energyKit->user_id)
                                                <th style="text-align: end;">{{ __('messages.kits.col_remove') }}</th>
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
                                                <strong style="color: #0F5C6B;">{{ number_format($item->daily_rate * $item->pivot->quantity, 2) }} {{ __('messages.kits.tnd_per_day_short') }}</strong>
                                                <span class="d-block text-muted" style="font-size: 0.72rem;">({{ number_format($item->daily_rate, 2) }} {{ __('messages.kits.tnd_per_unit') }})</span>
                                            </td>

                                            @auth
                                                @if(auth()->user()->isAdmin() || auth()->id() === $energyKit->user_id)
                                                    <td style="text-align: end;">
                                                        <form action="{{ route('energy-kits.detach', ['energyKit' => $energyKit->slug, 'equipment' => $item->id]) }}" method="POST" onsubmit="return confirm(@js(__('messages.kits.confirm_detach_eq')));">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" title="{{ __('messages.kits.detach_title') }}">
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

                    <!-- Lender & Admin: Received Reservations Table for this Pack -->
                    @auth
                        @if(auth()->id() === $energyKit->user_id || auth()->user()->isAdmin())
                            @php
                                $receivedKitReservations = $energyKit->reservations()->with('user')->latest()->get();
                            @endphp
                            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mt-4" style="border-inline-start: 4px solid #FF6B3D !important;">
                                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="font-size: 1.2rem;">📥</span>
                                            <h5 class="fw-bold mb-0 text-dark">{{ __('messages.kits.received_res_title') }}</h5>
                                            <span class="badge rounded-pill bg-light text-dark border">
                                                {{ __('messages.kits.total_count', ['count' => $receivedKitReservations->count()]) }}
                                            </span>
                                        </div>
                                        <p class="text-muted small mb-0 mt-1">
                                            {{ __('messages.kits.received_res_desc', ['discount' => rtrim(rtrim(number_format($energyKit->discount_percentage, 1), '0'), '.')]) }}
                                        </p>
                                    </div>
                                    <a href="{{ route('lender.rentals.index', ['tab' => 'packs']) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1 fw-bold text-decoration-none">
                                        {{ __('messages.nav.lender_space') }} {!! $isRtl ? '&larr;' : '&rarr;' !!}
                                    </a>
                                </div>

                                @if($receivedKitReservations->isEmpty())
                                    <div class="p-4 text-center rounded-3" style="background: #F8FAFC; border: 1px dashed #CBD5E1;">
                                        <p class="text-muted small mb-0">
                                            {{ __('messages.kits.received_res_empty') }}
                                        </p>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('messages.kits.col_ref') }}</th>
                                                    <th>{{ __('messages.kits.col_renter') }}</th>
                                                    <th>{{ __('messages.kits.col_period') }}</th>
                                                    <th>{{ __('messages.kits.col_discounted_rate') }}</th>
                                                    <th>{{ __('messages.common.status') }}</th>
                                                    <th style="text-align: end;">{{ __('messages.kits.col_action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($receivedKitReservations as $rRes)
                                                    <tr>
                                                        <td>
                                                            <span class="fw-bold" style="color: #C2410C;">
                                                                #PACK-{{ str_pad($rRes->id, 5, '0', STR_PAD_LEFT) }}
                                                            </span>
                                                            <span class="d-block text-muted" style="font-size: 0.72rem;">
                                                                {{ $rRes->created_at->format('d/m/Y') }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="fw-bold text-dark">{{ $rRes->user->name }}</div>
                                                            <div class="text-muted" style="font-size: 0.78rem;">{{ $rRes->user->email }}</div>
                                                            @if($rRes->notes)
                                                                <div class="text-muted small mt-1" style="font-style: italic;">
                                                                    💬 « {{ Str::limit($rRes->notes, 40) }} »
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div>{{ $rRes->start_date->format('d/m/Y') }} {!! $isRtl ? '&larr;' : '&rarr;' !!} {{ $rRes->end_date->format('d/m/Y') }}</div>
                                                            <span class="badge bg-light text-dark border">{{ $rRes->days_count }} {{ __('messages.kits.days_unit') }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="fw-bold" style="color: #C2410C; font-size: 1rem;">
                                                                {{ number_format($rRes->total_price, 2) }} {{ __('messages.kits.currency') }}
                                                            </div>
                                                            <span class="d-block text-muted" style="font-size: 0.75rem;">
                                                                {{ number_format($rRes->daily_rate, 2) }} {{ __('messages.kits.tnd_per_day_short') }} · {{ __('messages.kits.deposit_short') }} {{ number_format($rRes->deposit_amount, 2) }} {{ __('messages.kits.currency') }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            {!! $rRes->status_badge !!}
                                                        </td>
                                                        <td style="text-align: end;">
                                                            @if($rRes->status === 'pending')
                                                                <form method="POST" action="{{ route('lender.kit-reservations.status', $rRes) }}" class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status" value="confirmed">
                                                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 py-1 fw-bold">
                                                                        {{ __('messages.kits.btn_confirm') }}
                                                                    </button>
                                                                </form>
                                                            @elseif($rRes->status === 'confirmed')
                                                                <form method="POST" action="{{ route('lender.kit-reservations.status', $rRes) }}" class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status" value="active">
                                                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold" style="background:#0284C7; border-color:#0284C7;">
                                                                        {{ __('messages.kits.btn_active') }}
                                                                    </button>
                                                                </form>
                                                            @elseif($rRes->status === 'active')
                                                                <form method="POST" action="{{ route('lender.kit-reservations.status', $rRes) }}" class="d-inline">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status" value="completed">
                                                                    <button type="submit" class="btn btn-sm btn-secondary rounded-pill px-3 py-1 fw-bold">
                                                                        {{ __('messages.kits.btn_returned') }}
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span class="text-muted small">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endauth

                </div>
            </div>

            <!-- Right Column: Pricing & Rental Action -->
            <div class="col-lg-4">
                
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4" style="position: sticky; top: 96px;">
                    <h5 class="fw-bold mb-3" style="color: #10202B;">{{ __('messages.kits.pricing_title') }}</h5>

                    <div class="p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, rgba(255, 176, 32, 0.12), rgba(255, 107, 61, 0.06)); border: 1px solid rgba(255, 176, 32, 0.3);">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>{{ __('messages.kits.raw_unit_price') }}</span>
                            <span class="text-decoration-line-through">{{ number_format($energyKit->raw_daily_rate, 2) }} {{ __('messages.kits.tnd_per_day_short') }}</span>
                        </div>
                        
                        @if($energyKit->discount_percentage > 0)
                            <div class="d-flex justify-content-between small text-success fw-bold mb-2">
                                <span>{{ __('messages.kits.pack_discount_label', ['discount' => rtrim(rtrim(number_format($energyKit->discount_percentage, 1), '0'), '.')]) }}</span>
                                <span>-{{ number_format($energyKit->daily_savings, 2) }} {{ __('messages.kits.tnd_per_day_short') }}</span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-baseline pt-2 border-top">
                            <span class="fw-bold text-dark">{{ __('messages.kits.pack_rate_label') }}</span>
                            <span style="font-size: 2.2rem; font-weight: 800; color: #C2410C; line-height: 1;">
                                {{ number_format($energyKit->discounted_daily_rate, 2) }}
                            </span>
                            <span style="font-size: 0.95rem; font-weight: 700; color: #7C2D12;">{{ __('messages.kits.tnd_per_day') }}</span>
                        </div>
                    </div>

                    <!-- Availability indicator -->
                    <div class="p-3 rounded-3 mb-4" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 0.85rem;">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span>{{ __('messages.kits.availability_label') }}</span>
                            <span class="badge rounded-pill {{ $energyKit->is_all_available ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                {{ $energyKit->is_all_available ? __('messages.kits.avail_now') : __('messages.kits.avail_partial') }}
                            </span>
                        </div>
                        <span class="text-muted small d-block">
                            {!! __('messages.kits.deposit_notice', ['amount' => number_format($energyKit->total_deposit, 2)]) !!}
                        </span>
                    </div>

                    <!-- Direct Rental Button -->
                    @if($energyKit->equipment->isNotEmpty())
                        @auth
                            @if(auth()->user()->isRenter())
                                <button type="button" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mb-3 d-flex align-items-center justify-content-center gap-2" 
                                        data-bs-toggle="modal" data-bs-target="#reserveKitModal"
                                        style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520; font-size: 1.05rem; box-shadow: 0 6px 20px rgba(255, 107, 61, 0.35); cursor: pointer;">
                                    ⚡ {{ __('messages.kits.reserve_this_pack') }}
                                </button>
                            @else
                                <div class="alert alert-warning border-0 rounded-4 p-3 mb-3 text-start shadow-sm" style="background: #FFFBEB; border-inline-start: 4px solid #F59E0B !important; color: #92400E; font-size: 0.88rem;">
                                    <div class="d-flex align-items-center gap-2 fw-bold mb-1" style="font-size: 0.92rem;">
                                        <span>🔒</span>
                                        <span>{{ __('messages.kits.booking_not_allowed_title') }}</span>
                                    </div>
                                    <p class="mb-0" style="line-height: 1.45;">
                                        {!! __('messages.kits.booking_not_allowed_desc') !!}
                                    </p>
                                </div>
                                <button type="button" class="btn btn-secondary w-100 rounded-pill py-3 fw-bold mb-3 d-flex align-items-center justify-content-center gap-2" disabled style="opacity: 0.7; cursor: not-allowed; background: #94A3B8; border: none;">
                                    ⚡ {{ __('messages.kits.booking_renters_only') }}
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mb-3 d-flex align-items-center justify-content-center gap-2" 
                               style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520; font-size: 1.05rem; box-shadow: 0 6px 20px rgba(255, 107, 61, 0.35); text-decoration: none;">
                                ⚡ {{ __('messages.kits.login_to_reserve') }}
                            </a>
                        @endauth
                    @else
                        <button type="button" class="btn btn-secondary w-100 rounded-pill py-3 fw-bold mb-3" disabled>
                            {{ __('messages.kits.pack_configuring') }}
                        </button>
                    @endif

                    @auth
                        {{-- Incoming reservations for pack owner or admin --}}
                        @if(auth()->id() === $energyKit->user_id || auth()->user()->isAdmin())
                            @php
                                $sidebarReceivedReservations = $energyKit->reservations()->with('user')->latest()->get();
                            @endphp
                            <div class="mb-3 p-3 rounded-3" style="background: #FFFBEB; border: 1.5px solid #FCD34D;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">
                                        📥 {{ __('messages.kits.sidebar_received', ['count' => $sidebarReceivedReservations->count()]) }}
                                    </div>
                                    @if($sidebarReceivedReservations->where('status', 'pending')->count() > 0)
                                        <span class="badge bg-warning text-dark">{{ __('messages.kits.pending_count', ['count' => $sidebarReceivedReservations->where('status', 'pending')->count()]) }}</span>
                                    @endif
                                </div>
                                @if($sidebarReceivedReservations->isNotEmpty())
                                    @foreach($sidebarReceivedReservations->take(3) as $rRes)
                                        <div class="py-2 border-bottom border-warning-subtle" style="font-size: 0.8rem;">
                                            <div class="d-flex justify-content-between fw-bold text-dark">
                                                <span>{{ $rRes->user->name }}</span>
                                                <span style="color: #C2410C;">{{ number_format($rRes->total_price, 2) }} {{ __('messages.kits.currency') }}</span>
                                            </div>
                                            <div class="text-muted d-flex justify-content-between align-items-center mt-1">
                                                <span>{{ $rRes->start_date->format('d/m/y') }} {!! $isRtl ? '&larr;' : '&rarr;' !!} {{ $rRes->end_date->format('d/m/y') }} ({{ $rRes->days_count }}{{ __('messages.kits.days_short') }})</span>
                                                {!! $rRes->status_badge !!}
                                            </div>
                                        </div>
                                    @endforeach
                                    <a href="{{ route('lender.rentals.index', ['tab' => 'packs']) }}" class="btn btn-sm btn-dark w-100 mt-2 rounded-pill fw-bold" style="font-size: 0.78rem;">
                                        {{ __('messages.kits.manage_in_lender') }} {!! $isRtl ? '&larr;' : '&rarr;' !!}
                                    </a>
                                @else
                                    <span class="text-muted small">{{ __('messages.kits.no_res_yet') }}</span>
                                @endif
                            </div>
                        @endif

                        {{-- Outgoing reservations made by the current user --}}
                        @php
                            $userReservations = $energyKit->reservations()->where('user_id', auth()->id())->latest()->get();
                        @endphp
                        @if($userReservations->isNotEmpty())
                            <div class="mb-3 p-3 rounded-3" style="background: #F0FDF4; border: 1px solid #BBF7D0;">
                                <div class="fw-bold mb-2 text-success" style="font-size: 0.85rem;">
                                    {{ __('messages.kits.your_reservations', ['count' => $userReservations->count()]) }}
                                </div>
                                @foreach($userReservations as $res)
                                    <div class="d-flex align-items-center justify-content-between py-1 border-bottom border-success-subtle" style="font-size: 0.8rem;">
                                        <span>{{ __('messages.kits.from_to_days', ['start' => $res->start_date->format('d/m/Y'), 'end' => $res->end_date->format('d/m/Y'), 'days' => $res->days_count]) }}</span>
                                        <span class="badge bg-success">{{ number_format($res->total_price, 2) }} {{ __('messages.kits.currency') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endauth

                    <button type="button" onclick="window.openPackBot && window.openPackBot()" class="btn w-100 rounded-pill py-2 mb-2 fw-bold text-center" style="background: rgba(15, 92, 107, 0.08); border: 1.5px solid #0F5C6B; color: #0F5C6B; font-size: 0.86rem;">
                        🤖 {{ __('messages.pack_ai.open_btn_show') }}
                    </button>

                    <a href="{{ route('energy-kits.index') }}" class="btn btn-outline-secondary w-100 rounded-pill py-2 text-center" style="font-size: 0.9rem;">
                        {!! $isRtl ? '&rarr;' : '&larr;' !!} {{ __('messages.kits.back_to_packs') }}
                    </a>

                    <!-- Reassurance Items -->
                    <div class="mt-4 pt-3 border-top" style="font-size: 0.82rem; color: #64748B;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span>📦</span>
                            <span>{{ __('messages.kits.reassurance_1') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span>🔍</span>
                            <span>{{ __('messages.kits.reassurance_2') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span>🛡️</span>
                            <span>{{ __('messages.kits.reassurance_3') }}</span>
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
                    <h5 class="modal-title fw-bold text-dark" id="attachModalLabel">{{ __('messages.kits.modal_attach_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.common.close') }}"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label for="modal_equipment_id" class="form-label fw-bold small text-dark">{{ __('messages.kits.modal_choose_eq') }}</label>
                        <select name="equipment_id" id="modal_equipment_id" class="form-select rounded-3" required>
                            <option value="" disabled selected>{{ __('messages.kits.modal_select_placeholder') }}</option>
                            @foreach(\App\Models\Equipment::where('status', 'approved')->orderBy('name')->get() as $eq)
                                <option value="{{ $eq->id }}">{{ $eq->name }} ({{ $eq->category?->name }}) — {{ number_format($eq->daily_rate, 2) }} {{ __('messages.kits.tnd_per_day_short') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="modal_quantity" class="form-label fw-bold small text-dark">{{ __('messages.kits.modal_qty_label') }}</label>
                        <input type="number" name="quantity" id="modal_quantity" class="form-control rounded-3" min="1" max="20" value="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="modal_options" class="form-label fw-bold small text-dark">{{ __('messages.kits.modal_options_label') }}</label>
                        <input type="text" name="options" id="modal_options" class="form-control rounded-3" placeholder="{{ __('messages.kits.modal_options_placeholder') }}">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520;">
                        {{ __('messages.kits.modal_add_to_pack') }}
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
                        ⚡ {{ __('messages.kits.modal_reserve_title', ['title' => $energyKit->title]) }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('messages.common.close') }}"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center justify-content-between" style="border-radius: 10px; font-size: 0.88rem;">
                        <span>{{ __('messages.kits.modal_negotiated_rate', ['discount' => $energyKit->discount_percentage]) }}</span>
                        <strong class="text-primary">{{ number_format($energyKit->discounted_daily_rate, 2) }} {{ __('messages.kits.tnd_per_day') }}</strong>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="reserve_start_date" class="form-label fw-semibold" style="font-size: 0.88rem;">{{ __('messages.kits.start_date') }}</label>
                            <input type="date" name="start_date" id="reserve_start_date" class="form-control rounded-3" 
                                   min="{{ date('Y-m-d') }}" value="{{ old('start_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="reserve_end_date" class="form-label fw-semibold" style="font-size: 0.88rem;">{{ __('messages.kits.end_date') }}</label>
                            <input type="date" name="end_date" id="reserve_end_date" class="form-control rounded-3" 
                                   min="{{ date('Y-m-d') }}" value="{{ old('end_date', date('Y-m-d', strtotime('+3 days'))) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reserve_notes" class="form-label fw-semibold" style="font-size: 0.88rem;">{{ __('messages.kits.reserve_notes_label') }}</label>
                        <textarea name="notes" id="reserve_notes" rows="2" class="form-control rounded-3" placeholder="{{ __('messages.kits.reserve_notes_placeholder') }}"></textarea>
                    </div>

                    <!-- Live Cost Preview -->
                    <div class="p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.88rem;">
                            <span class="text-muted">{{ __('messages.kits.rental_duration') }}</span>
                            <strong id="calc_days">4 {{ __('messages.kits.days_unit') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.88rem;">
                            <span class="text-muted">{{ __('messages.kits.total_pack_rental') }}</span>
                            <strong class="text-success" id="calc_total">0.00 {{ __('messages.kits.currency') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between pt-2 border-top" style="font-size: 0.82rem;">
                            <span class="text-muted">{{ __('messages.kits.guarantee_deposit') }}</span>
                            <strong>{{ number_format($energyKit->total_deposit, 2) }} {{ __('messages.kits.currency') }}</strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520;">
                        {{ __('messages.kits.confirm_booking') }}
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
    const invalidDatesLabel = @json(__('messages.kits.invalid_dates'));
    const daysUnitLabel = @json(__('messages.kits.days_unit'));
    const currencyLabel = @json(__('messages.kits.currency'));

    function updateTotals() {
        if (!startInput || !endInput || !calcDays || !calcTotal) return;
        const start = new Date(startInput.value);
        const end = new Date(endInput.value);
        if (isNaN(start) || isNaN(end) || end < start) {
            calcDays.textContent = invalidDatesLabel;
            calcTotal.textContent = '0.00 ' + currencyLabel;
            return;
        }
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        const total = (diffDays * dailyRate).toFixed(2);
        calcDays.textContent = diffDays + ' ' + daysUnitLabel;
        calcTotal.textContent = total + ' ' + currencyLabel;
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
