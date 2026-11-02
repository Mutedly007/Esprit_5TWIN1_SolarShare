@extends('layouts.solartec')

@section('title', 'Set New Password — SolarShare')
@section('meta_description', 'Choose a new secure password for your SolarShare account.')

@section('content')
<section class="ss-auth-section">
  <!-- Ambient Solar Glow Orbs in Background -->
  <div class="ss-auth-orb ss-auth-orb-amber" aria-hidden="true"></div>
  <div class="ss-auth-orb ss-auth-orb-teal" aria-hidden="true"></div>

  <div class="ss-container ss-auth-container">

    <!-- Top Breadcrumb / Return Link -->
    <div class="ss-auth-topbar">
      <a href="{{ route('login') }}" class="ss-auth-back-link">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Back to Sign In</span>
      </a>
      <div class="ss-auth-trust-badge">
        <span class="ss-pulse-dot"></span>
        <span>Secure Password Update</span>
      </div>
    </div>

    <!-- Main Split Glass Auth Card -->
    <div class="ss-auth-card">

      <!-- Left Column: Visual Brand & Guidance -->
      <div class="ss-auth-showcase">
        <div class="ss-auth-showcase-content">
          <div class="ss-auth-badge">
            <svg class="ss-auth-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
            <span>Password Protection</span>
          </div>

          <h1 class="ss-auth-showcase-title">
            Choose a new password.<br>
            <span class="ss-text-gradient">Strong &amp; safe.</span>
          </h1>

          <p class="ss-auth-showcase-desc">
            Make sure your new password is at least 8 characters long and includes a mix of letters, numbers, and symbols.
          </p>

          <ul class="ss-auth-perks">
            <li class="ss-auth-perk-item">
              <div class="ss-auth-perk-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
              </div>
              <div class="ss-auth-perk-text">
                <strong>Au moins 8 caractères</strong>
                <span>Lettres majuscules et minuscules</span>
              </div>
            </li>
            <li class="ss-auth-perk-item">
              <div class="ss-auth-perk-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
              </div>
              <div class="ss-auth-perk-text">
                <strong>Chiffres &amp; Symboles</strong>
                <span>Pour une sécurité renforcée de votre compte</span>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Right Column: New Password Form -->
      <div class="ss-auth-hub">
        
        <div class="ss-auth-header">
          <h2 class="ss-auth-title">Create new password</h2>
          <p class="ss-auth-subtitle">Enter your email and define your new secure password</p>
        </div>

        {{-- Error Banner --}}
        @if ($errors->any())
          <div class="ss-form-error-banner" style="background:#FFF1F0; border:1px solid #FF6B3D; color:#C2410C; border-radius:10px; padding:0.75rem 1rem; font-size:0.85rem; margin-bottom:1.25rem;">
            {{ $errors->first() }}
          </div>
        @endif

        <form class="ss-auth-form" method="POST" action="{{ route('password.update') }}" novalidate>
          @csrf

          <!-- Password Reset Token -->
          <input type="hidden" name="token" value="{{ $token }}">

          <!-- Email Input -->
          <div class="ss-form-group">
            <label for="ss-reset-email" class="ss-form-label">Email address</label>
            <div class="ss-input-wrap">
              <span class="ss-input-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                  <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
              </span>
              <input 
                type="email" 
                id="ss-reset-email" 
                name="email" 
                value="{{ old('email', $email) }}"
                class="ss-form-input" 
                placeholder="name@example.com" 
                autocomplete="email"
                required
              >
            </div>
          </div>

          <!-- New Password Input -->
          <div class="ss-form-group">
            <div class="ss-form-label-row">
              <label for="ss-new-password" class="ss-form-label">New password</label>
            </div>
            <div class="ss-input-wrap">
              <span class="ss-input-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
              </span>
              <input 
                type="password" 
                id="ss-new-password" 
                name="password" 
                class="ss-form-input" 
                placeholder="At least 8 characters" 
                autocomplete="new-password"
                required
              >
              <button type="button" class="ss-pwd-toggle" aria-label="Toggle password visibility" data-target="ss-new-password">
                <svg class="ss-eye-show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
              </button>
            </div>
          </div>

          <!-- Confirm Password Input -->
          <div class="ss-form-group">
            <div class="ss-form-label-row">
              <label for="ss-new-password-confirm" class="ss-form-label">Confirm new password</label>
            </div>
            <div class="ss-input-wrap">
              <span class="ss-input-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
              </span>
              <input 
                type="password" 
                id="ss-new-password-confirm" 
                name="password_confirmation" 
                class="ss-form-input" 
                placeholder="Repeat new password" 
                autocomplete="new-password"
                required
              >
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="ss-btn ss-btn-primary ss-btn-submit" id="ss-btn-reset-submit">
            <span class="ss-btn-text">Reset Password &amp; Sign In</span>
            <svg class="ss-btn-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </button>

        </form>

        <div class="ss-auth-switch-prompt">
          <span>Remember your password?</span>
          <a href="{{ route('login') }}" class="ss-switch-link">
            Back to sign in
          </a>
        </div>

      </div>

    </div>

  </div>
</section>
@endsection

