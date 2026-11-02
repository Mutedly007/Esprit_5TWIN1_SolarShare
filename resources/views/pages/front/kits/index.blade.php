{{-- resources/views/pages/front/kits/index.blade.php --}}
@extends('layouts.solartec')

@section('title', __('messages.kits.page_title'))
@section('meta_description', __('messages.kits.meta_description'))

@section('content')
@php $isRtl = app()->getLocale() === 'ar'; @endphp
<section style="background: #F8FAFC; padding: 2.5rem 0 5rem 0; min-height: 80vh;">
    <div class="container">

        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-size: 0.88rem;">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color: #0F5C6B; text-decoration: none;">{{ __('messages.nav.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('marketplace') }}" style="color: #0F5C6B; text-decoration: none;">{{ __('messages.kits.marketplace_crumb') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #64748B;">{{ __('messages.nav.packs') }}</li>
            </ol>
        </nav>

        <!-- Header Card -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 p-4 p-md-5 rounded-4 bg-white shadow-sm border">
            <div style="max-width: 650px;">
                <span class="badge rounded-pill text-uppercase fw-bold px-3 py-2 mb-2" style="background: rgba(255, 176, 32, 0.15); color: #C2410C; font-size: 0.75rem; letter-spacing: 0.05em;">
                    📦 {{ __('messages.kits.header_badge') }}
                </span>
                <h1 class="h3 fw-bold mb-2" style="color: #10202B;">{{ __('messages.kits.title') }}</h1>
                <p class="text-muted mb-0 small" style="line-height: 1.6;">
                    {!! __('messages.kits.header_desc') !!}
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @auth
                    @if(auth()->user()->isLender())
                        <a href="{{ route('energy-kits.create') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520; font-size: 0.92rem; box-shadow: 0 4px 14px rgba(255, 107, 61, 0.3);">
                            + {{ __('messages.kits.compose_btn') }}
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520; font-size: 0.92rem;">
                        + {{ __('messages.kits.login_to_compose') }}
                    </a>
                @endauth
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('energy-kits.index') }}" 
               class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ empty($targetUse) ? 'btn-dark text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
                {{ __('messages.kits.filter_all') }} ({{ $counts['all'] }})
            </a>
            <a href="{{ route('energy-kits.index', ['target_use' => 'vanlife']) }}" 
               class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $targetUse === 'vanlife' ? 'btn-info text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
                🚐 {{ __('messages.kits.filter_vanlife') }} ({{ $counts['vanlife'] }})
            </a>
            <a href="{{ route('energy-kits.index', ['target_use' => 'camping']) }}" 
               class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $targetUse === 'camping' ? 'btn-success text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
                ⛺ {{ __('messages.kits.filter_camping') }} ({{ $counts['camping'] }})
            </a>
            <a href="{{ route('energy-kits.index', ['target_use' => 'chantier']) }}" 
               class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $targetUse === 'chantier' ? 'btn-warning text-dark' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
                🔨 {{ __('messages.kits.filter_chantier') }} ({{ $counts['chantier'] }})
            </a>
            <a href="{{ route('energy-kits.index', ['target_use' => 'urgence']) }}" 
               class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $targetUse === 'urgence' ? 'btn-danger text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
                🚨 {{ __('messages.kits.filter_urgence') }} ({{ $counts['urgence'] }})
            </a>
            <a href="{{ route('energy-kits.index', ['target_use' => 'evenement']) }}" 
               class="btn rounded-pill px-3 py-2 text-decoration-none fw-semibold {{ $targetUse === 'evenement' ? 'btn-primary text-white' : 'btn-white text-muted border bg-white' }}" style="font-size: 0.85rem;">
                🎉 {{ __('messages.kits.filter_evenement') }} ({{ $counts['evenement'] }})
            </a>
        </div>

        @if($kits->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
                <div style="font-size: 3.5rem; margin-bottom: 1rem;">📦</div>
                <h4 class="fw-bold text-dark mb-2">{{ __('messages.kits.empty_title') }}</h4>
                <p class="text-muted mx-auto mb-4" style="max-width: 480px;">
                    {{ __('messages.kits.empty_desc') }}
                </p>
                <div>
                    @auth
                        @if(auth()->user()->isLender())
                            <a href="{{ route('energy-kits.create') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; color: #071520;">
                                + {{ __('messages.kits.create_btn') }}
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        @else
            <!-- Packs Grid -->
            <div class="row g-4">
                @foreach($kits as $kit)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden d-flex flex-column" style="transition: transform 0.2s, box-shadow 0.2s;">
                            
                            <!-- Visual Banner -->
                            <div style="height: 200px; background: #0F172A; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                @if($kit->image_url)
                                    <img src="{{ $kit->image_url }}" alt="{{ $kit->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="font-size: 4rem; color: #FFB020;">📦</div>
                                @endif

                                <!-- Target Use Badge -->
                                <span style="position: absolute; top: 14px; inset-inline-start: 14px; {{ $kit->target_use_badge_style }}; font-size: 0.78rem; font-weight: 700; padding: 0.35rem 0.8rem; border-radius: 9999px;">
                                    {{ $kit->target_use_label }}
                                </span>

                                <!-- Discount Badge -->
                                @if($kit->discount_percentage > 0)
                                    <span style="position: absolute; top: 14px; inset-inline-end: 14px; background: linear-gradient(135deg, #FFB020, #FF6B3D); color: #071520; font-size: 0.8rem; font-weight: 800; padding: 0.35rem 0.8rem; border-radius: 9999px; box-shadow: 0 4px 12px rgba(255, 107, 61, 0.4);">
                                        -{{ rtrim(rtrim(number_format($kit->discount_percentage, 1), '0'), '.') }}% {{ __('messages.kits.discount_badge') }}
                                    </span>
                                @endif

                                @if(! $kit->is_active)
                                    <span style="position: absolute; bottom: 12px; inset-inline-start: 14px; background: rgba(15, 23, 42, 0.88); color: #FCA5A5; font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 9999px; border: 1px solid rgba(248, 113, 113, 0.4); backdrop-filter: blur(4px);">
                                        ✕ {{ __('messages.kits.inactive_badge') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Body -->
                            <div class="p-4 flex-grow-1 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
                                        <span>⚡ {{ $kit->equipment_count }} {{ __('messages.kits.bundled_count') }}</span>
                                        @if($kit->total_power_watts > 0)
                                            <strong>{{ $kit->total_power_watts }} W</strong>
                                        @endif
                                    </div>

                                    <h5 class="fw-bold mb-2" style="color: #10202B;">
                                        <a href="{{ route('energy-kits.show', $kit) }}" class="text-decoration-none text-dark">
                                            {{ $kit->title }}
                                        </a>
                                    </h5>

                                    <p class="text-muted small mb-3" style="line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $kit->description ?: __('messages.kits.default_desc') }}
                                    </p>

                                    <!-- Bundled items preview chips -->
                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                        @foreach($kit->equipment->take(3) as $item)
                                            <span class="badge bg-light text-dark border small" style="font-size: 0.72rem; font-weight: 600;">
                                                {{ $item->pivot->quantity }}x {{ Str::limit($item->name, 22) }}
                                            </span>
                                        @endforeach
                                        @if($kit->equipment->count() > 3)
                                            <span class="badge bg-light text-muted border small" style="font-size: 0.72rem;">
                                                +{{ $kit->equipment->count() - 3 }} {{ __('messages.kits.more_items') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Pricing Block -->
                                <div class="pt-3 border-top d-flex align-items-baseline justify-content-between">
                                    <div>
                                        @if($kit->discount_percentage > 0)
                                            <span class="d-block text-muted text-decoration-line-through small" style="font-size: 0.8rem;">
                                                {{ number_format($kit->raw_daily_rate, 2) }} {{ __('messages.kits.tnd_per_day_short') }}
                                            </span>
                                        @endif
                                        <div class="d-flex align-items-baseline gap-1">
                                            <strong style="color: #C2410C; font-size: 1.4rem; font-weight: 800; line-height: 1;">
                                                {{ number_format($kit->discounted_daily_rate, 2) }}
                                            </strong>
                                            <span class="text-muted small" style="font-size: 0.82rem;">{{ __('messages.kits.tnd_per_day') }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-1 align-items-center">
                                        @auth
                                            @if((auth()->user()->isAdmin() || auth()->id() === $kit->user_id) && ! $kit->is_active)
                                                <form action="{{ route('energy-kits.toggle', $kit) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-2 py-1" style="font-size: 0.75rem;" title="{{ __('messages.kits.reactivate_title') }}">
                                                        ✓ {{ __('messages.kits.reactivate') }}
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                        <a href="{{ route('energy-kits.show', $kit) }}" class="btn btn-outline-dark rounded-pill btn-sm px-3 py-2 fw-semibold">
                                            {{ __('messages.kits.discover') }} {!! $isRtl ? '&larr;' : '&rarr;' !!}
                                        </a>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $kits->links() }}
            </div>
        @endif

    </div>
</section>
@endsection
