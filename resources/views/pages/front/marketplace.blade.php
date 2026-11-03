{{-- resources/views/pages/front/marketplace.blade.php --}}
@extends('layouts.solartec')

@section('title', __('messages.marketplace.page_title'))
@section('meta_description', __('messages.marketplace.meta_description'))

@section('content')

<!-- ==================== MARKETPLACE HERO & SEARCH ==================== -->
<section style="background: linear-gradient(135deg, #083E49 0%, #0F5C6B 60%, #10202B 100%); color: #FFFFFF; padding: 4rem 0 3.5rem 0; position: relative; overflow: hidden;">
    <!-- Decorative Sun Ray Glow -->
    <div style="position: absolute; inset-inline-end: -50px; top: -50px; width: 350px; height: 350px; background: radial-gradient(circle, rgba(255, 176, 32, 0.25) 0%, rgba(255, 107, 61, 0) 70%); border-radius: 50%; pointer-events: none;"></div>

    <div class="container" style="position: relative; z-index: 2;">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge badge-solar mb-3 text-uppercase px-3 py-2" style="background: #FFB020; color: #10202B; font-weight: 700; border-radius: 9999px;">
                    ☀️ {{ __('messages.marketplace.hero_badge') }}
                </span>
                <h1 class="display-5 text-white fw-bold mb-3" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
                    {{ __('messages.marketplace.hero_title') }}
                </h1>
                <p class="fs-5 text-white-50 mb-4" style="max-width: 680px; margin: 0 auto;">
                    {{ __('messages.marketplace.hero_subtitle') }}
                </p>

                <!-- Search Form -->
                <form method="GET" action="{{ route('marketplace') }}" style="max-width: 640px; margin: 0 auto;">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div style="display: flex; gap: 0.5rem; background: #FFFFFF; padding: 0.45rem; border-radius: 9999px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);">
                        <div style="display: flex; align-items: center; flex: 1; padding-inline-start: 1rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="{{ __('messages.marketplace.hero_search_placeholder') }}"
                                style="width: 100%; border: none; outline: none; padding: 0.6rem 0.8rem; font-size: 0.95rem; color: #10202B; background: transparent;"
                            >
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2" style="background: linear-gradient(135deg, #FFB020, #FF6B3D); border: none; font-weight: 700; color: #071520;">
                            {{ __('messages.marketplace.search_btn') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ==================== CATEGORIES TABS ==================== -->
<section style="background: #FFFFFF; border-bottom: 1px solid rgba(16, 32, 43, 0.08); padding: 1.25rem 0;">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 0.75rem; overflow-x: auto; padding-bottom: 0.25rem; scrollbar-width: none;">
            
            <!-- All Categories -->
            <a href="{{ route('marketplace', array_merge(request()->except(['category', 'page']))) }}"
               class="badge-category-filter {{ !request('category') ? 'is-active' : '' }}">
                <span>{{ __('messages.marketplace.all_equipment') }}</span>
                <span class="count-pill">{{ $totalCount }}</span>
            </a>

            <!-- Dynamic Categories -->
            @foreach($categories as $cat)
                <a href="{{ route('marketplace', array_merge(request()->except(['page']), ['category' => $cat->slug])) }}"
                   class="badge-category-filter {{ request('category') == $cat->slug ? 'is-active' : '' }}">
                    <span>{{ $cat->localized_name }}</span>
                    <span class="count-pill">{{ $cat->equipment_count }}</span>
                </a>
            @endforeach

        </div>
    </div>
</section>

<!-- ==================== MARKETPLACE CONTENT & GRID ==================== -->
<section style="background: #F8FAFC; padding: 3rem 0 5rem 0; min-height: 55vh;">
    <div class="container">

        <!-- Controls Bar -->
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem;">
            <div>
                <h2 style="font-size: 1.35rem; font-weight: 800; color: #10202B; margin-bottom: 0.2rem;">
                    @if($selectedCategory)
                        {{ $selectedCategory->localized_name }}
                    @elseif(request('search'))
                        {{ __('messages.marketplace.search_results_for', ['query' => request('search')]) }}
                    @else
                        {{ __('messages.marketplace.available_catalog_heading') }}
                    @endif
                </h2>
                <span style="font-size: 0.88rem; color: #64748B;">
                    {{ __('messages.marketplace.listed_count', ['count' => $equipment->total()]) }}
                </span>
            </div>

            <!-- Sort & Availability Form -->
            <form method="GET" action="{{ route('marketplace') }}" style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <select name="sort" class="form-select form-select-sm" style="width: auto; border-radius: 10px; font-size: 0.88rem; font-weight: 600; padding-block: 0.45rem; padding-inline-start: 0.85rem; padding-inline-end: 2rem;" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('messages.marketplace.sort_latest') }}</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('messages.marketplace.sort_price_asc') }}</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('messages.marketplace.sort_price_desc') }}</option>
                </select>

                @if(request('search') || request('category') || request('sort'))
                    <a href="{{ route('marketplace') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size: 0.82rem;">
                        {{ __('messages.marketplace.reset_btn') }}
                    </a>
                @endif
            </form>
        </div>

        <!-- Equipment Cards Grid -->
        @if($equipment->count() > 0)
            <div class="row g-4">
                @foreach($equipment as $item)
                    <div class="col-md-6 col-lg-4">
                        <x-equipment-card :equipment="$item" />
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $equipment->links('partials.pagination') }}
            </div>

        @else
            <!-- Empty State -->
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4" style="background: #FFFFFF;">
                <div style="font-size: 3.5rem; margin-bottom: 1rem;">☀️</div>
                <h3 class="fw-bold mb-2">{{ __('messages.marketplace.empty_title') }}</h3>
                <p class="text-muted" style="max-width: 480px; margin: 0 auto 1.5rem auto;">
                    {{ __('messages.marketplace.empty_subtitle') }}
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('marketplace') }}" class="btn btn-outline-primary rounded-pill px-4">
                        {{ __('messages.marketplace.view_full_catalog') }}
                    </a>
                    @auth
                        <a href="{{ route('equipment.create') }}" class="btn btn-primary rounded-pill px-4">
                            + {{ __('messages.marketplace.list_equipment_btn') }}
                        </a>
                    @endauth
                </div>
            </div>
        @endif

    </div>
</section>

@endsection

@push('styles')
<style>
    .badge-category-filter {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #F1F5F9;
        color: #1E293B;
        font-size: 0.88rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        text-decoration: none;
        white-space: nowrap;
        border: 1.5px solid transparent;
        transition: all 0.2s ease;
    }
    .badge-category-filter:hover {
        background: #E2E8F0;
        color: #0F5C6B;
    }
    .badge-category-filter.is-active {
        background: #0F5C6B;
        color: #FFFFFF;
        border-color: #0F5C6B;
        box-shadow: 0 4px 12px rgba(15, 92, 107, 0.25);
    }
    .badge-category-filter .count-pill {
        background: rgba(0, 0, 0, 0.08);
        font-size: 0.74rem;
        font-weight: 700;
        padding: 0.15rem 0.45rem;
        border-radius: 9999px;
    }
    .badge-category-filter.is-active .count-pill {
        background: rgba(255, 255, 255, 0.25);
        color: #FFFFFF;
    }
    .equipment-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px -6px rgba(16, 32, 43, 0.12) !important;
    }
    .equipment-card:hover img {
        transform: scale(1.05);
    }

    /* ==================== CUSTOM SOLARSHARE PAGINATION ==================== */
    nav[role="navigation"] svg {
        max-width: 18px !important;
        max-height: 18px !important;
    }
    .ss-pagination-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.25rem;
        margin-top: 3.5rem;
        margin-bottom: 1rem;
    }
    .ss-pagination-info {
        font-size: 0.88rem;
        color: #64748B;
        font-weight: 500;
    }
    .ss-pagination-info span {
        font-weight: 700;
        color: #10202B;
    }
    .ss-pagination-list {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        list-style: none;
        padding: 0;
        margin: 0;
        flex-wrap: wrap;
        justify-content: center;
    }
    .ss-page-item {
        display: inline-flex;
    }
    .ss-page-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1.15rem;
        border-radius: 9999px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        color: #10202B;
        font-size: 0.88rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 1px 3px rgba(16, 32, 43, 0.05);
    }
    .ss-page-btn svg {
        width: 16px !important;
        height: 16px !important;
        max-width: 16px !important;
        max-height: 16px !important;
        flex-shrink: 0;
    }
    .ss-page-item:not(.is-disabled) .ss-page-btn:hover {
        background: #0F5C6B;
        border-color: #0F5C6B;
        color: #FFFFFF;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px -2px rgba(15, 92, 107, 0.3);
    }
    .ss-page-item.is-disabled .ss-page-btn {
        background: #F8FAFC;
        border-color: #E2E8F0;
        color: #94A3B8;
        cursor: not-allowed;
        opacity: 0.65;
    }
    .ss-page-link {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        color: #10202B;
        font-size: 0.92rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 1px 3px rgba(16, 32, 43, 0.04);
    }
    .ss-page-item:not(.is-active) .ss-page-link:hover {
        background: #F1F5F9;
        border-color: #CBD5E1;
        color: #0F5C6B;
        transform: translateY(-2px);
    }
    .ss-page-item.is-active .ss-page-link {
        background: linear-gradient(135deg, #0F5C6B, #083E49);
        border-color: #0F5C6B;
        color: #FFFFFF;
        box-shadow: 0 6px 18px -2px rgba(15, 92, 107, 0.35);
    }
    .ss-page-dots {
        width: 36px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #94A3B8;
        font-weight: 800;
        letter-spacing: 0.1em;
    }
</style>
@endpush
