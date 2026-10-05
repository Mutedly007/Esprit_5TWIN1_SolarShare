@extends('layouts.solartec')

@section('title', 'Complete Your SolarShare Profile — Choose Your Role')
@section('meta_description', 'Tell SolarShare how you plan to use clean energy sharing: rent gear from neighbors, lend your own equipment, or both.')

@section('content')
<section class="ss-auth-section">
  <!-- Ambient Solar Glow Orbs in Background -->
  <div class="ss-auth-orb ss-auth-orb-amber" aria-hidden="true"></div>
  <div class="ss-auth-orb ss-auth-orb-teal" aria-hidden="true"></div>

  <div class="ss-container ss-auth-container">

    <!-- Top Bar / Trust Badge (no navigation until onboarding is complete) -->
    <div class="ss-auth-topbar" style="justify-content: center;">
      <div class="ss-auth-trust-badge">
        <span class="ss-pulse-dot"></span>
        <span>100% Verified Neighborhood Sharing</span>
      </div>
    </div>

    <!-- Main Split Glass Card -->
    <div class="ss-auth-card">

      <!-- Left Column: Visual Brand -->
      <div class="ss-auth-showcase" style="display:flex; align-items:center; justify-content:center;">
        <div class="ss-auth-showcase-content" style="text-align:left;">

          <div class="ss-auth-badge">
            <svg class="ss-auth-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
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
            <span>You're in, {{ $user->name }}!</span>
          </div>

          <h1 class="ss-auth-showcase-title">
            One last step.<br>
            <span class="ss-text-gradient">Pick your role.</span>
          </h1>

          <p class="ss-auth-showcase-desc">
            Help us tailor your SolarShare experience. You can always change this later from your account settings.
          </p>

          <div class="ss-auth-socials" style="margin-top: 28px;">
            <div class="ss-auth-oauth-btn" style="cursor: default; justify-content: center;" aria-label="Signed in with {{ ucfirst($user->provider) }}">
              @if ($user->provider === 'github')
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                </svg>
              @else
                <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
                  <path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.6l3.1-3.1C17.3 1.8 14.8 1 12 1 7.5 1 3.7 3.6 1.9 7.3l3.7 2.9C6.5 7.4 9 5 12 5z"/>
                  <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.7-.2-2.3H12v4.5h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5.1 3.7-8.8z"/>
                  <path fill="#FBBC05" d="M5.6 14.8c-.2-.7-.4-1.5-.4-2.3s.1-1.6.4-2.3L1.9 7.3C.7 9.7 0 12.3 0 15.2c0 2.8.7 5.5 1.9 7.8l3.7-2.9z"/>
                  <path fill="#34A853" d="M12 23.5c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3 0-5.5-2-6.4-4.8L1.9 17C3.7 20.7 7.5 23.5 12 23.5z"/>
                </svg>
              @endif
              <span>Signed in with {{ ucfirst($user->provider) }}</span>
            </div>
          </div>

        </div>
      </div>

      <!-- Right Column: Role Selection Form -->
      <div class="ss-auth-hub">

        <div class="ss-auth-header">
          <h2 class="ss-auth-title">How will you use SolarShare?</h2>
          <p class="ss-auth-subtitle">Choose the role that fits you best today</p>
        </div>

        @if ($errors->any())
          <div style="color: #FF6B3D; font-size: 0.9rem; margin-bottom: 16px;">
            {{ $errors->first() }}
          </div>
        @endif

        <form class="ss-auth-form" method="POST" action="{{ route('onboarding.role.store') }}">
          @csrf

          <!-- Role Selector Pills -->
          <div class="ss-form-group">
            <label class="ss-form-label">I'm joining to:</label>
            <div class="ss-role-selector" role="radiogroup" aria-label="Account primary role">
              <label class="ss-role-pill">
                <input type="radio" name="solar_role" value="rent" {{ old('solar_role', 'rent') === 'rent' ? 'checked' : '' }}>
                <span class="ss-role-btn">
                  <span class="ss-role-icon">⚡</span>
                  <span>Rent gear</span>
                </span>
              </label>
              <label class="ss-role-pill">
                <input type="radio" name="solar_role" value="lend" {{ old('solar_role') === 'lend' ? 'checked' : '' }}>
                <span class="ss-role-btn">
                  <span class="ss-role-icon">☀️</span>
                  <span>Lend &amp; earn</span>
                </span>
              </label>
              <label class="ss-role-pill">
                <input type="radio" name="solar_role" value="both" {{ old('solar_role') === 'both' ? 'checked' : '' }}>
                <span class="ss-role-btn">
                  <span class="ss-role-icon">🔁</span>
                  <span>Both</span>
                </span>
              </label>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="ss-btn ss-btn-primary ss-btn-submit">
            <span class="ss-btn-text">Continue to SolarShare</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </button>

        </form>

        <!-- Sign-out escape hatch -->
        <div class="ss-auth-switch-prompt">
          <span>Not you?</span>
          <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="ss-switch-link">Sign out</button>
          </form>
        </div>

      </div>

    </div>

  </div>
</section>
@endsection
