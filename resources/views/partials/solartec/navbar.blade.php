{{-- resources/views/partials/solartec/navbar.blade.php --}}
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

      <!-- Desktop Navigation Links (Default landing page menu) -->
      <ul class="ss-nav-menu" role="menubar" style="margin: 0 auto;">
        <li role="none"><a href="{{ url('/#how-it-works') }}" class="ss-nav-link" role="menuitem">How it works</a></li>
        <li role="none"><a href="{{ route('marketplace') }}" class="ss-nav-link {{ request()->routeIs('marketplace*') ? 'is-active' : '' }}" role="menuitem">Equipment</a></li>
        <li role="none"><a href="{{ route('energy-kits.index') }}" class="ss-nav-link {{ request()->routeIs('energy-kits.*') ? 'is-active' : '' }}" role="menuitem">Packs Solaires</a></li>
        <li role="none"><a href="{{ url('/#impact') }}" class="ss-nav-link" role="menuitem">Impact</a></li>
        <li role="none"><a href="{{ url('/#community') }}" class="ss-nav-link" role="menuitem">Community</a></li>
        <li role="none"><a href="{{ url('/#faq') }}" class="ss-nav-link" role="menuitem">FAQ</a></li>
        <!-- Sun dot glider indicator -->
        <div id="ss-nav-sun-indicator" class="ss-nav-sun-indicator" aria-hidden="true"></div>
      </ul>

      <!-- Right Action Buttons -->
      <div class="ss-nav-actions">
        @auth
          <!-- Logged-in user menu -->
          <div class="ss-user-menu" style="position: relative;">
            <button type="button" id="ss-user-menu-btn" class="ss-btn ss-btn-ghost" style="display:inline-flex; align-items:center; gap:0.5rem;" aria-haspopup="true" aria-expanded="false">
              <span class="ss-user-avatar" style="width:30px; height:30px; border-radius:50%; background:linear-gradient(135deg,#FFB020,#FF6B3D); color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:0.78rem; font-weight:700;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
              </span>
              <span class="ss-user-name">{{ auth()->user()->name }}</span>
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <div id="ss-user-dropdown" class="ss-user-dropdown" style="display:none; position:absolute; right:0; top:calc(100% + 10px); min-width:210px; background:#fff; border:1px solid rgba(16,32,43,0.08); border-radius:14px; box-shadow:0 18px 40px -12px rgba(16,32,43,0.25); padding:0.5rem; z-index:1000;">
              <div style="padding:0.6rem 0.8rem; border-bottom:1px solid rgba(16,32,43,0.06); margin-bottom:0.4rem;">
                <div style="display:flex; align-items:center; justify-content:space-between;">
                  <strong style="display:block; font-size:0.9rem; color:#10202B;">{{ auth()->user()->name }}</strong>
                  @if(auth()->user()->isAdmin())
                    <span style="background:#EF4444; color:#fff; font-size:0.68rem; font-weight:700; padding:0.15rem 0.45rem; border-radius:20px;">Admin</span>
                  @endif
                </div>
                <span style="font-size:0.78rem; color:#64748B;">{{ auth()->user()->email }}</span>
              </div>
              <a href="{{ route('lender.dashboard') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#10202B; text-decoration:none;">
                Tableau de bord
              </a>
              <a href="{{ route('equipment.index') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#10202B; text-decoration:none;">
                Mes Équipements
              </a>
              <a href="{{ route('categories.index') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#10202B; text-decoration:none;">
                Catégories
              </a>
              <a href="{{ route('energy-kits.index') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#10202B; text-decoration:none;">
                📦 Packs Solaires
              </a>
              <a href="{{ url('/') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#64748B; text-decoration:none; border-top:1px solid rgba(16,32,43,0.06); margin-top:0.3rem;">
                Site Public
              </a>
              @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="ss-user-dropdown-item" style="display:block; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#10202B; text-decoration:none;">
                  Dashboard Admin
                </a>
              @endif
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="ss-user-dropdown-item" style="display:block; width:100%; text-align:left; padding:0.55rem 0.8rem; border-radius:8px; font-size:0.88rem; color:#C2410C; background:none; border:none; cursor:pointer;">Sign out</button>
              </form>
            </div>
          </div>
        @endauth
        @guest
          <a href="{{ route('login') }}" class="ss-btn ss-btn-ghost">Log in</a>
          <a href="{{ route('register') }}" class="ss-btn ss-btn-primary">Get started</a>
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
      <li class="ss-mobile-nav-item"><a href="{{ url('/#how-it-works') }}" class="ss-mobile-nav-link">How it works</a></li>
      <li class="ss-mobile-nav-item"><a href="{{ route('marketplace') }}" class="ss-mobile-nav-link">Equipment</a></li>
      <li class="ss-mobile-nav-item"><a href="{{ route('energy-kits.index') }}" class="ss-mobile-nav-link">Packs Solaires</a></li>
      <li class="ss-mobile-nav-item"><a href="{{ url('/#impact') }}" class="ss-mobile-nav-link">Impact</a></li>
      <li class="ss-mobile-nav-item"><a href="{{ url('/#community') }}" class="ss-mobile-nav-link">Community</a></li>
      <li class="ss-mobile-nav-item"><a href="{{ url('/#faq') }}" class="ss-mobile-nav-link">FAQ</a></li>
    </ul>
    <div style="display: flex; flex-direction: column; gap: 1rem;">
      @auth
        <span style="text-align:center; font-size:0.85rem; color:#64748B;">Signed in as <strong>{{ auth()->user()->name }}</strong></span>
        <a href="{{ route('lender.dashboard') }}" class="ss-btn ss-btn-secondary w-100 ss-mobile-nav-link" style="text-align:center; text-decoration:none;">Espace Prêteur</a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="ss-btn ss-btn-secondary w-100">Sign out</button>
        </form>
      @endauth
      @guest
        <a href="{{ route('login') }}" class="ss-btn ss-btn-secondary w-100 ss-mobile-nav-link">Log in</a>
        <a href="{{ route('register') }}" class="ss-btn ss-btn-primary w-100 ss-mobile-nav-link">Get started</a>
      @endguest
    </div>
  </div>
</header>
