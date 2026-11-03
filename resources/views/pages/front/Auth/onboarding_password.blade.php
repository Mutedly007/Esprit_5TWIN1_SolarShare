{{-- resources/views/pages/front/Auth/onboarding_password.blade.php --}}
@extends('layouts.solartec')

@section('title', __('messages.auth.onboarding_pwd_title') . ' — SolarShare')
@section('meta_description', __('messages.auth.onboarding_pwd_desc'))

@section('content')
<section class="ss-auth-section">
  <!-- Ambient Solar Glow Orbs in Background -->
  <div class="ss-auth-orb ss-auth-orb-amber" aria-hidden="true"></div>
  <div class="ss-auth-orb ss-auth-orb-teal" aria-hidden="true"></div>

  <div class="ss-container ss-auth-container">

    <!-- Top Bar / Trust Badge -->
    <div class="ss-auth-topbar" style="justify-content: center;">
      <div class="ss-auth-trust-badge">
        <span class="ss-pulse-dot"></span>
        <span>{{ __('messages.common.verified_network') }}</span>
      </div>
    </div>

    <!-- Main Split Glass Card -->
    <div class="ss-auth-card">

      <!-- Left Column: Visual Brand -->
      <div class="ss-auth-showcase" style="display:flex; align-items:center; justify-content:center;">
        <div class="ss-auth-showcase-content" style="text-align:left;">

          <div class="ss-auth-badge">
            <svg class="ss-auth-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <span>{{ __('messages.profile.security_title') }}</span>
          </div>

          <h1 class="ss-auth-showcase-title">
            {{ $user->name }} !<br>
            <span class="ss-text-gradient">{{ __('messages.auth.onboarding_pwd_title') }}</span>
          </h1>

          <p class="ss-auth-showcase-desc">
            {{ __('messages.auth.onboarding_pwd_desc') }} (<strong>{{ $user->email }}</strong>).
          </p>

          <div class="ss-auth-socials" style="margin-top: 24px;">
            <div class="ss-auth-oauth-btn" style="cursor: default; justify-content: center;" aria-label="Compte {{ ucfirst($user->provider ?? 'Google') }}">
              <span class="badge rounded-pill bg-success me-2" style="font-size: 0.75rem;">✓ {{ __('messages.common.confirmed') }}</span>
              <span>{{ $user->email }}</span>
            </div>
          </div>

        </div>
      </div>

      <!-- Right Column: Set Password Form -->
      <div class="ss-auth-hub">

        <div class="ss-auth-header">
          <h2 class="ss-auth-title">{{ __('messages.auth.onboarding_pwd_title') }}</h2>
          <p class="ss-auth-subtitle">{{ __('messages.auth.onboarding_pwd_subtitle') }}</p>
        </div>

        @if(session('success'))
          <div class="alert alert-success py-2 px-3 small rounded-3 mb-3 border-0" style="background:#ECFDF5; color:#065F46;">
            {{ session('success') }}
          </div>
        @endif

        @if(session('info'))
          <div class="alert alert-info py-2 px-3 small rounded-3 mb-3 border-0" style="background:#EFF6FF; color:#1E40AF;">
            {{ session('info') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3 border-0" style="background:#FEF2F2; color:#991B1B;">
            <ul class="mb-0 ps-3">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form class="ss-auth-form" method="POST" action="{{ route('onboarding.password.store') }}">
          @csrf

          <!-- Password Input -->
          <div class="ss-form-group">
            <label for="password" class="ss-form-label">{{ __('messages.profile.new_password') }}</label>
            <div class="ss-input-wrap">
              <span class="ss-input-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
              </span>
              <input 
                type="password" 
                id="password" 
                name="password" 
                class="ss-form-input" 
                placeholder="{{ __('messages.profile.new_password') }}" 
                autocomplete="new-password"
                required
              >
              <button type="button" class="ss-pwd-toggle" aria-label="Toggle" onclick="toggleInput('password')">
                <i class="fas fa-eye text-muted" id="password-eye"></i>
              </button>
            </div>
            <div class="form-text small text-muted mt-1" style="font-size:0.75rem;">
              {{ __('messages.profile.password_rules') }}
            </div>
          </div>

          <!-- Password Confirmation Input -->
          <div class="ss-form-group">
            <label for="password_confirmation" class="ss-form-label">{{ __('messages.profile.confirm_password') }}</label>
            <div class="ss-input-wrap">
              <span class="ss-input-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                  <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
              </span>
              <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation" 
                class="ss-form-input" 
                placeholder="{{ __('messages.profile.confirm_password') }}" 
                autocomplete="new-password"
                required
              >
              <button type="button" class="ss-pwd-toggle" aria-label="Toggle" onclick="toggleInput('password_confirmation')">
                <i class="fas fa-eye text-muted" id="password_confirmation-eye"></i>
              </button>
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="ss-btn ss-btn-primary ss-btn-submit">
            <span class="ss-btn-text">{{ __('messages.auth.save_and_start') }}</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </button>
        </form>

        <!-- Skip Option -->
        <div class="ss-auth-switch-prompt mt-3 text-center">
          <form method="POST" action="{{ route('onboarding.password.skip') }}" style="display:inline;">
            @csrf
            <button type="submit" class="ss-switch-link" style="background:none; border:none; color:#64748B; font-size:0.85rem; text-decoration:underline; cursor:pointer;">
              {!! __('messages.auth.skip_for_now') !!}
            </button>
          </form>
        </div>

      </div>

    </div>

  </div>
</section>

<script>
  function toggleInput(inputId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(inputId + '-eye');
    if (input) {
      if (input.type === 'password') {
        input.type = 'text';
        if (eye) {
          eye.classList.remove('fa-eye');
          eye.classList.add('fa-eye-slash');
        }
      } else {
        input.type = 'password';
        if (eye) {
          eye.classList.remove('fa-eye-slash');
          eye.classList.add('fa-eye');
        }
      }
    }
  }
</script>
@endsection

