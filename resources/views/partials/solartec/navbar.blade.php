{{-- resources/views/partials/solartec/navbar.blade.php --}}
<style>
  /* ==========================================================================
     SolarShare Explorer Dropdown Styles
     ========================================================================== */
  .ss-nav-item-dropdown {
    position: relative;
    display: inline-flex;
    align-items: center;
  }

  .ss-nav-dropdown-toggle {
    background: transparent;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-family: inherit;
    font-size: 0.925rem;
    font-weight: 600;
    color: var(--ss-ink-muted, #64748B);
    padding: 0.4rem 0;
    transition: color 0.15s ease;
    line-height: inherit;
  }

  .ss-nav-dropdown-toggle:hover,
  .ss-nav-item-dropdown:hover .ss-nav-dropdown-toggle {
    color: var(--ss-ink, #10202B);
  }

  .ss-nav-dropdown-toggle.is-active {
    color: #0F5C6B;
    font-weight: 700;
  }

  .ss-dropdown-chevron {
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .ss-nav-item-dropdown:hover .ss-dropdown-chevron,
  .ss-nav-dropdown-toggle[aria-expanded="true"] .ss-dropdown-chevron {
    transform: rotate(180deg);
  }

  .ss-nav-dropdown-menu {
    position: absolute;
    top: calc(100% + 14px);
    left: 50%;
    transform: translateX(-50%) translateY(8px);
    min-width: 340px;
    background: #FFFFFF;
    border: 1px solid rgba(16, 32, 43, 0.08);
    border-radius: 18px;
    box-shadow: 0 20px 45px -12px rgba(16, 32, 43, 0.2), 0 0 1px 1px rgba(16, 32, 43, 0.04);
    padding: 0.65rem;
    z-index: 1050;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1), transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.2s;
  }

  /* Invisible hover bridge */
  .ss-nav-dropdown-menu::before {
    content: '';
    position: absolute;
    top: -16px;
    left: 0;
    right: 0;
    height: 16px;
  }

  .ss-nav-item-dropdown:hover .ss-nav-dropdown-menu,
  .ss-nav-dropdown-menu.is-open {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateX(-50%) translateY(0);
  }

  .ss-nav-dropdown-header {
    padding: 0.4rem 0.75rem 0.5rem;
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #94A3B8;
    border-bottom: 1px solid rgba(16, 32, 43, 0.06);
    margin-bottom: 0.35rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .ss-dropdown-card {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.65rem 0.8rem;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.15s ease;
  }

  .ss-dropdown-card:hover {
    background: #F8FAFC;
    transform: translateX(2px);
  }

  .ss-dropdown-card.is-active {
    background: rgba(15, 92, 107, 0.06);
  }

  .ss-dropdown-card-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .icon-equipment {
    background: #FEF3C7;
    color: #D97706;
  }

  .icon-kits {
    background: rgba(15, 92, 107, 0.1);
    color: #0F5C6B;
  }

  .icon-hubs {
    background: #DCFCE7;
    color: #16A34A;
  }

  .ss-dropdown-card-content {
    flex: 1;
    min-width: 0;
  }

  .ss-dropdown-card-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
  }

  .ss-dropdown-card-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: #0F172A;
  }

  .ss-dropdown-card-desc {
    display: block;
    font-size: 0.75rem;
    color: #64748B;
    margin-top: 0.15rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .ss-dropdown-pill {
    font-size: 0.68rem;
    font-weight: 700;
    padding: 0.12rem 0.45rem;
    border-radius: 9999px;
    background: #F1F5F9;
    color: #475569;
  }

  .pill-kits {
    background: #FEF3C7;
    color: #B45309;
  }

  .pill-hubs {
    background: #DCFCE7;
    color: #15803D;
  }

  /* Mobile menu styles */
  .ss-mobile-sublink {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.65rem 0.9rem;
    border-radius: 10px;
    color: #1E293B;
    font-weight: 600;
    font-size: 0.92rem;
    text-decoration: none;
    background: #F8FAFC;
    transition: all 0.15s ease;
  }

  .ss-mobile-sublink:hover,
  .ss-mobile-sublink.is-active {
    background: rgba(15, 92, 107, 0.08);
    color: #0F5C6B;
  }

  /* Language Switcher Component */
  .ss-lang-menu {
    position: relative;
    display: inline-flex;
    align-items: center;
  }

  .ss-lang-btn {
    display: inline-flex !important;
    align-items: center;
    gap: 0.4rem;
    padding: 0.42rem 0.8rem;
    border-radius: 9999px;
    border: 1.5px solid rgba(16, 32, 43, 0.12);
    background: #ffffff;
    color: #10202B;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(16, 32, 43, 0.06);
    transition: all 0.15s ease;
    text-decoration: none;
    line-height: 1;
  }

  .ss-lang-btn:hover {
    background: #F8FAFC;
    border-color: #0F5C6B;
    color: #0F5C6B;
    box-shadow: 0 3px 8px rgba(15, 92, 107, 0.12);
  }

  .ss-lang-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    min-width: 155px;
    background: #ffffff;
    border: 1px solid rgba(16, 32, 43, 0.1);
    border-radius: 14px;
    box-shadow: 0 16px 36px -8px rgba(16, 32, 43, 0.22);
    padding: 0.4rem;
    z-index: 1100;
  }

  .ss-lang-dropdown::before {
    content: '';
    position: absolute;
    top: -8px;
    left: 0;
    right: 0;
    height: 8px;
  }

  .ss-lang-menu:hover .ss-lang-dropdown,
  .ss-lang-dropdown.is-open {
    display: block !important;
  }

  [dir="rtl"] .ss-lang-dropdown {
    left: 0;
    right: auto;
  }
  [dir="ltr"] .ss-lang-dropdown,
  html:not([dir="rtl"]) .ss-lang-dropdown {
    right: 0;
    left: auto;
  }

  .ss-lang-option {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.85rem;
    color: #10202B;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.15s ease, color 0.15s ease;
  }

  .ss-lang-option:hover {
    background: #F1F5F9;
    color: #0F5C6B;
  }

  .ss-lang-option.is-active {
    background: rgba(15, 92, 107, 0.08);
    color: #0F5C6B;
    font-weight: 700;
  }

  /* User Menu Dropdown */
  .ss-user-menu {
    position: relative;
    display: inline-flex;
    align-items: center;
  }

  .ss-user-btn {
    display: inline-flex !important;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0.65rem;
    border-radius: 9999px;
    border: 1px solid rgba(16, 32, 43, 0.1);
    background: #ffffff;
    color: #10202B;
    cursor: pointer;
    font-family: inherit;
    font-size: 0.88rem;
    font-weight: 600;
    line-height: 1;
    transition: all 0.15s ease;
  }

  .ss-user-btn:hover {
    background: #F8FAFC;
    border-color: #0F5C6B;
    color: #0F5C6B;
  }

  .ss-user-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    min-width: 220px;
    background: #ffffff;
    border: 1px solid rgba(16, 32, 43, 0.1);
    border-radius: 14px;
    box-shadow: 0 18px 40px -12px rgba(16, 32, 43, 0.25);
    padding: 0.5rem;
    z-index: 1100;
  }

  .ss-user-dropdown::before {
    content: '';
    position: absolute;
    top: -10px;
    left: 0;
    right: 0;
    height: 10px;
  }

  .ss-user-menu:hover .ss-user-dropdown,
  .ss-user-dropdown.is-open {
    display: block !important;
  }

  [dir="rtl"] .ss-user-dropdown {
    left: 0;
    right: auto;
  }
  [dir="ltr"] .ss-user-dropdown,
  html:not([dir="rtl"]) .ss-user-dropdown {
    right: 0;
    left: auto;
  }

  .ss-user-dropdown-item {
    transition: background 0.15s ease, color 0.15s ease;
  }

  .ss-user-dropdown-item:hover {
    background: #F1F5F9 !important;
  }
</style>

<header id="ss-navbar" class="ss-navbar">
  <div class="ss-container">
    <nav class="ss-nav-container" aria-label="Main navigation">

      <!-- Logo: Sun Mark + Wordmark -->
      <a href="{{ url('/') }}" class="ss-brand" aria-label="SolarShare home">
        <svg class="ss-brand-mark" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <circle cx="18" cy="18" r="7" fill="#FFB020" />
          <path d="M18 2V7M18 29V34M2 18H7M29 18H34M6.68 6.68L10.22 10.22M25.78 25.78L29.32 29.32M6.68 29.32L10.22 25.78M25.78 10.22L29.32 6.68" stroke="#FF6B3D" stroke-width="2.6" stroke-linecap="round" />
          <circle cx="18" cy="18" r="13.5" stroke="#0F5C6B" stroke-width="1.8" stroke-dasharray="3 4" />
        </svg>
        Solar<span>Share</span>
      </a>

      <!-- Desktop Navigation Links -->
      <ul class="ss-nav-menu" role="menubar" style="margin: 0 auto;">
        <li role="none"><a href="{{ url('/#how-it-works') }}" class="ss-nav-link" role="menuitem">{{ __('messages.nav.how_it_works') }}</a></li>
        
        <!-- Dropdown: Explorer (Equipment, Packs Solaires, Points Relais) -->
        @php
          $isExploreActive = request()->routeIs('marketplace*', 'energy-kits*', 'hubs*');
        @endphp
        <li class="ss-nav-item-dropdown" role="none">
          <button type="button" 
                  class="ss-nav-link ss-nav-dropdown-toggle {{ $isExploreActive ? 'is-active' : '' }}" 
                  id="ss-explore-dropdown-btn" 
                  aria-haspopup="true" 
                  aria-expanded="false" 
                  role="menuitem">
            <span>{{ __('messages.nav.explorer') }}</span>
            <svg class="ss-dropdown-chevron" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </button>
          
          <div class="ss-nav-dropdown-menu" id="ss-explore-dropdown-menu" role="menu" aria-labelledby="ss-explore-dropdown-btn">
            <div class="ss-nav-dropdown-header">
              <span>{{ __('messages.nav.explorer') }}</span>
            </div>

            <!-- 1. Équipements / Marketplace -->
            <a href="{{ route('marketplace') }}" class="ss-dropdown-card {{ request()->routeIs('marketplace*') ? 'is-active' : '' }}" role="menuitem">
              <div class="ss-dropdown-card-icon icon-equipment">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
              </div>
              <div class="ss-dropdown-card-content">
                <div class="ss-dropdown-card-title-row">
                  <strong class="ss-dropdown-card-title">{{ __('messages.nav.equipment') }}</strong>
                  <span class="ss-dropdown-pill">{{ __('messages.nav.explore_equip_pill') }}</span>
                </div>
                <span class="ss-dropdown-card-desc">{{ __('messages.nav.explore_equip_desc') }}</span>
              </div>
            </a>

            <!-- 2. Packs Solaires Clé-en-main -->
            <a href="{{ route('energy-kits.index') }}" class="ss-dropdown-card {{ request()->routeIs('energy-kits.*') ? 'is-active' : '' }}" role="menuitem">
              <div class="ss-dropdown-card-icon icon-kits">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                  <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                  <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
              </div>
              <div class="ss-dropdown-card-content">
                <div class="ss-dropdown-card-title-row">
                  <strong class="ss-dropdown-card-title">{{ __('messages.nav.packs') }}</strong>
                  <span class="ss-dropdown-pill pill-kits">{{ __('messages.nav.explore_kits_pill') }}</span>
                </div>
                <span class="ss-dropdown-card-desc">{{ __('messages.nav.explore_kits_desc') }}</span>
              </div>
            </a>

            <!-- 3. Points Relais & Hubs -->
            <a href="{{ route('hubs.index') }}" class="ss-dropdown-card {{ request()->routeIs('hubs.*') ? 'is-active' : '' }}" role="menuitem">
              <div class="ss-dropdown-card-icon icon-hubs">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
              </div>
              <div class="ss-dropdown-card-content">
                <div class="ss-dropdown-card-title-row">
                  <strong class="ss-dropdown-card-title">{{ __('messages.nav.hubs') }}</strong>
                  <span class="ss-dropdown-pill pill-hubs">{{ __('messages.nav.explore_hubs_pill') }}</span>
                </div>
                <span class="ss-dropdown-card-desc">{{ __('messages.nav.explore_hubs_desc') }}</span>
              </div>
            </a>
          </div>
        </li>

        <li role="none"><a href="{{ url('/#impact') }}" class="ss-nav-link" role="menuitem">{{ __('messages.nav.impact') }}</a></li>
        <li role="none"><a href="{{ url('/#community') }}" class="ss-nav-link" role="menuitem">{{ __('messages.nav.community') }}</a></li>
        <li role="none"><a href="{{ url('/#faq') }}" class="ss-nav-link" role="menuitem">{{ __('messages.nav.faq') }}</a></li>
        <!-- Sun dot glider indicator -->
        <div id="ss-nav-sun-indicator" class="ss-nav-sun-indicator" aria-hidden="true"></div>
      </ul>

      <!-- Right Action Buttons -->
      <div class="ss-nav-actions">
        <!-- Language Switcher Dropdown -->
        <div class="ss-lang-menu">
          <button type="button" id="ss-lang-menu-btn" class="ss-lang-btn" aria-haspopup="true" aria-expanded="false" title="{{ __('messages.nav.language') }}">
            <span>
              @if(app()->getLocale() === 'fr') 🇫🇷 FR
              @elseif(app()->getLocale() === 'ar') 🇹🇳 العربية
              @else 🇬🇧 EN
              @endif
            </span>
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
          </button>
          <div id="ss-lang-dropdown" class="ss-lang-dropdown" role="menu">
            <a href="{{ route('lang.switch', 'fr') }}" class="ss-lang-option {{ app()->getLocale() === 'fr' ? 'is-active' : '' }}" role="menuitem">
              <span>🇫🇷 Français</span>
              @if(app()->getLocale() === 'fr') <i class="fas fa-check text-success" style="font-size:0.75rem;"></i> @endif
            </a>
            <a href="{{ route('lang.switch', 'en') }}" class="ss-lang-option {{ app()->getLocale() === 'en' ? 'is-active' : '' }}" role="menuitem">
              <span>🇬🇧 English</span>
              @if(app()->getLocale() === 'en') <i class="fas fa-check text-success" style="font-size:0.75rem;"></i> @endif
            </a>
            <a href="{{ route('lang.switch', 'ar') }}" class="ss-lang-option {{ app()->getLocale() === 'ar' ? 'is-active' : '' }}" role="menuitem">
              <span>🇹🇳 العربية</span>
              @if(app()->getLocale() === 'ar') <i class="fas fa-check text-success" style="font-size:0.75rem;"></i> @endif
            </a>
          </div>
        </div>

        @auth
          <!-- Logged-in user menu -->
          <div class="ss-user-menu">
            <button type="button" id="ss-user-menu-btn" class="ss-user-btn" aria-haspopup="true" aria-expanded="false">
              <span class="ss-user-avatar" style="width:30px; height:30px; border-radius:50%; background:linear-gradient(135deg,#FFB020,#FF6B3D); color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:0.78rem; font-weight:700; overflow:hidden;">
                @if(auth()->user()->hasAvatar())
                  <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width:100%; height:100%; object-fit:cover;">
                @else
                  {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
              </span>
              <span class="ss-user-name">{{ auth()->user()->name }}</span>
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <div id="ss-user-dropdown" class="ss-user-dropdown" role="menu">
              <div style="padding:0.6rem 0.8rem; border-bottom:1px solid rgba(16,32,43,0.06); margin-bottom:0.4rem;">
                <div style="display:flex; align-items:center; justify-content:space-between;">
                  <strong style="display:block; font-size:0.9rem; color:#10202B;">{{ auth()->user()->name }}</strong>
                  @if(auth()->user()->isAdmin())
                    <span style="background:#EF4444; color:#fff; font-size:0.68rem; font-weight:700; padding:0.15rem 0.45rem; border-radius:20px;">Admin</span>
                  @endif
                </div>
                <span style="font-size:0.78rem; color:#64748B;">{{ auth()->user()->email }}</span>
              </div>
              <a href="{{ route('profile.edit') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#0F5C6B; text-decoration:none; font-weight:700;">
                👤 {{ __('messages.nav.profile') }}
              </a>
              @if(auth()->user()->isRenter())
                <a href="{{ route('rentals.index') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#10202B; text-decoration:none; font-weight:600;">
                  ⚡ {{ __('messages.nav.my_rentals') }}
                </a>
              @endif
              @if(auth()->user()->isLender())
                <a href="{{ route('lender.dashboard') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#10202B; text-decoration:none;">
                  {{ __('messages.nav.dashboard') }}
                </a>
                <a href="{{ route('lender.rentals.index') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#10202B; text-decoration:none;">
                  {{ __('messages.nav.received_rentals') }}
                </a>
                <a href="{{ route('equipment.index') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#10202B; text-decoration:none;">
                  {{ __('messages.nav.my_equipment') }}
                </a>
                <a href="{{ route('categories.index') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#10202B; text-decoration:none;">
                  {{ __('messages.nav.categories') }}
                </a>
              @endif
              <a href="{{ route('energy-kits.index') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#10202B; text-decoration:none;">
                📦 {{ __('messages.nav.packs') }}
              </a>
              <a href="{{ route('hubs.index') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#10202B; text-decoration:none;">
                📍 {{ __('messages.nav.hubs') }}
              </a>
              <a href="{{ url('/') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#64748B; text-decoration:none; border-top:1px solid rgba(16,32,43,0.06); margin-top:0.3rem;">
                {{ __('messages.nav.home') }}
              </a>
              @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#10202B; text-decoration:none;">
                  {{ __('messages.nav.admin_dashboard') }}
                </a>
              @endif
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="ss-user-dropdown-item" style="display:block; width:100%; text-align:start; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#C2410C; background:none; border:none; cursor:pointer;">{{ __('messages.nav.logout') }}</button>
              </form>
            </div>
          </div>
        @endauth
        @guest
          <a href="{{ route('login') }}" class="ss-btn ss-btn-ghost">{{ __('messages.nav.login') }}</a>
          <a href="{{ route('register') }}" class="ss-btn ss-btn-primary">{{ __('messages.nav.register') }}</a>
        @endguest

        <!-- Mobile Hamburger Toggle -->
        <button id="ss-hamburger" class="ss-hamburger" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="ss-mobile-menu">
          <span class="ss-hamburger-line"></span>
          <span class="ss-hamburger-line"></span>
          <span class="ss-hamburger-line"></span>
        </button>
      </div>

    </nav>
  </div>

  <!-- Fullscreen Mobile Menu -->
  <div id="ss-mobile-menu" class="ss-mobile-menu" aria-hidden="true">
    <ul class="ss-mobile-nav-list">
      <li class="ss-mobile-nav-item"><a href="{{ url('/#how-it-works') }}" class="ss-mobile-nav-link">{{ __('messages.nav.how_it_works') }}</a></li>
      
      <!-- Explorer Section on Mobile -->
      <li class="ss-mobile-nav-item" style="padding: 0.35rem 0;">
        <span style="display: block; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #94A3B8; margin-bottom: 0.4rem; padding-left: 0.5rem;">
          {{ __('messages.nav.explorer') }}
        </span>
        <div style="display: flex; flex-direction: column; gap: 0.35rem;">
          <a href="{{ route('marketplace') }}" class="ss-mobile-sublink {{ request()->routeIs('marketplace*') ? 'is-active' : '' }}">
            <span style="font-size: 1.1rem;">☀️</span>
            <span>{{ __('messages.nav.equipment') }}</span>
          </a>
          <a href="{{ route('energy-kits.index') }}" class="ss-mobile-sublink {{ request()->routeIs('energy-kits.*') ? 'is-active' : '' }}">
            <span style="font-size: 1.1rem;">📦</span>
            <span>{{ __('messages.nav.packs') }}</span>
          </a>
          <a href="{{ route('hubs.index') }}" class="ss-mobile-sublink {{ request()->routeIs('hubs.*') ? 'is-active' : '' }}">
            <span style="font-size: 1.1rem;">📍</span>
            <span>{{ __('messages.nav.hubs') }}</span>
          </a>
        </div>
      </li>

      <li class="ss-mobile-nav-item"><a href="{{ url('/#impact') }}" class="ss-mobile-nav-link">{{ __('messages.nav.impact') }}</a></li>
      <li class="ss-mobile-nav-item"><a href="{{ url('/#community') }}" class="ss-mobile-nav-link">{{ __('messages.nav.community') }}</a></li>
      <li class="ss-mobile-nav-item"><a href="{{ url('/#faq') }}" class="ss-mobile-nav-link">{{ __('messages.nav.faq') }}</a></li>
    </ul>

    <!-- Mobile Language Switcher -->
    <div style="display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.75rem 0; border-top:1px solid rgba(16,32,43,0.06); border-bottom:1px solid rgba(16,32,43,0.06);">
      <span style="font-size:0.78rem; color:#94A3B8; font-weight:700;">🌐 {{ __('messages.nav.language') }}:</span>
      <a href="{{ route('lang.switch', 'fr') }}" class="ss-btn ss-btn-sm {{ app()->getLocale() === 'fr' ? 'ss-btn-primary' : 'ss-btn-secondary' }}" style="padding:0.35rem 0.65rem; font-size:0.82rem; border-radius:14px; text-decoration:none;">🇫🇷 FR</a>
      <a href="{{ route('lang.switch', 'en') }}" class="ss-btn ss-btn-sm {{ app()->getLocale() === 'en' ? 'ss-btn-primary' : 'ss-btn-secondary' }}" style="padding:0.35rem 0.65rem; font-size:0.82rem; border-radius:14px; text-decoration:none;">🇬🇧 EN</a>
      <a href="{{ route('lang.switch', 'ar') }}" class="ss-btn ss-btn-sm {{ app()->getLocale() === 'ar' ? 'ss-btn-primary' : 'ss-btn-secondary' }}" style="padding:0.35rem 0.65rem; font-size:0.82rem; border-radius:14px; text-decoration:none;">🇹🇳 العربية</a>
    </div>

    <div style="display: flex; flex-direction: column; gap: 1rem;">
      @auth
        <div style="display:flex; align-items:center; justify-content:center; gap:0.6rem;">
          @if(auth()->user()->hasAvatar())
            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width:32px; height:32px; border-radius:50%; object-fit:cover;">
          @endif
          <span style="text-align:center; font-size:0.85rem; color:#64748B;">{{ __('messages.nav.signed_in_as') }} <strong>{{ auth()->user()->name }}</strong></span>
        </div>
        <a href="{{ route('profile.edit') }}" class="ss-btn ss-btn-secondary w-100 ss-mobile-nav-link" style="text-align:center; text-decoration:none; font-weight:700;">👤 {{ __('messages.nav.profile') }}</a>
        @if(auth()->user()->isRenter())
          <a href="{{ route('rentals.index') }}" class="ss-btn ss-btn-primary w-100 ss-mobile-nav-link" style="text-align:center; text-decoration:none;">⚡ {{ __('messages.nav.my_rentals') }}</a>
        @endif
        @if(auth()->user()->isLender())
          <a href="{{ route('lender.dashboard') }}" class="ss-btn ss-btn-secondary w-100 ss-mobile-nav-link" style="text-align:center; text-decoration:none;">{{ __('messages.nav.lender_space') }}</a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="ss-btn ss-btn-secondary w-100">{{ __('messages.nav.logout') }}</button>
        </form>
      @endauth
      @guest
        <a href="{{ route('login') }}" class="ss-btn ss-btn-secondary w-100 ss-mobile-nav-link">{{ __('messages.nav.login') }}</a>
        <a href="{{ route('register') }}" class="ss-btn ss-btn-primary w-100 ss-mobile-nav-link">{{ __('messages.nav.register') }}</a>
      @endguest
    </div>
  </div>
</header>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // 1. Explorer dropdown toggle
    const exploreBtn = document.getElementById('ss-explore-dropdown-btn');
    const exploreMenu = document.getElementById('ss-explore-dropdown-menu');
    if (exploreBtn && exploreMenu) {
      exploreBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const isOpen = exploreMenu.classList.toggle('is-open');
        exploreBtn.setAttribute('aria-expanded', String(isOpen));
      });
      document.addEventListener('click', function(e) {
        if (!exploreMenu.contains(e.target) && !exploreBtn.contains(e.target)) {
          exploreMenu.classList.remove('is-open');
          exploreBtn.setAttribute('aria-expanded', 'false');
        }
      });
    }

    // 2. Language dropdown toggle
    const langBtn = document.getElementById('ss-lang-menu-btn');
    const langMenu = document.getElementById('ss-lang-dropdown');
    if (langBtn && langMenu) {
      langBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const isOpen = langMenu.classList.toggle('is-open');
        langBtn.setAttribute('aria-expanded', String(isOpen));
      });
      document.addEventListener('click', function(e) {
        if (!langMenu.contains(e.target) && !langBtn.contains(e.target)) {
          langMenu.classList.remove('is-open');
          langBtn.setAttribute('aria-expanded', 'false');
        }
      });
    }

    // 3. User menu dropdown toggle
    const userBtn = document.getElementById('ss-user-menu-btn');
    const userMenu = document.getElementById('ss-user-dropdown');
    if (userBtn && userMenu) {
      userBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const isOpen = userMenu.classList.toggle('is-open');
        userBtn.setAttribute('aria-expanded', String(isOpen));
      });
      document.addEventListener('click', function(e) {
        if (!userMenu.contains(e.target) && !userBtn.contains(e.target)) {
          userMenu.classList.remove('is-open');
          userBtn.setAttribute('aria-expanded', 'false');
        }
      });
    }
  });
</script>
