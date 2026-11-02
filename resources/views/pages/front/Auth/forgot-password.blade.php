@extends('layouts.solartec')

@section('title', 'Forgot Password — SolarShare')
@section('meta_description', 'Reset your SolarShare account password quickly and securely.')

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
        <span>256-Bit Encrypted Security</span>
      </div>
    </div>

    <!-- Main Split Glass Auth Card -->
    <div class="ss-auth-card">

      <!-- Left Column: Visual Brand & Security Showcase -->
      <div class="ss-auth-showcase">
        <div class="ss-auth-showcase-content">
          <div class="ss-auth-badge">
            <svg class="ss-auth-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <span>Account Security</span>
          </div>

          <h1 class="ss-auth-showcase-title">
            Forgot your password?<br>
            <span class="ss-text-gradient">We've got you covered.</span>
          </h1>

          <p class="ss-auth-showcase-desc">
            No worries! Just enter your registered email address, and we will send you a secure, time-sensitive reset link.
          </p>

          <ul class="ss-auth-perks">
            <li class="ss-auth-perk-item">
              <div class="ss-auth-perk-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
              </div>
              <div class="ss-auth-perk-text">
                <strong>Token avec expiration (60 min)</strong>
                <span>Le lien expire après 60 minutes pour votre sécurité</span>
              </div>
            </li>
            <li class="ss-auth-perk-item">
              <div class="ss-auth-perk-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
              </div>
              <div class="ss-auth-perk-text">
                <strong>Usage unique &amp; chiffré</strong>
                <span>Chaque jeton est invalidé immédiatement après utilisation</span>
              </div>
            </li>
            <li class="ss-auth-perk-item">
              <div class="ss-auth-perk-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                  <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
              </div>
              <div class="ss-auth-perk-text">
                <strong>Envoi direct par email</strong>
                <span>Réception instantanée de votre lien d'accès</span>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Right Column: Reset Request Form -->
      <div class="ss-auth-hub">
        
        <div class="ss-auth-header">
          <h2 class="ss-auth-title">Reset your password</h2>
          <p class="ss-auth-subtitle">Enter your email and we'll send you instructions to reset your password.</p>
        </div>

        {{-- Success Status Banner --}}
        @if (session('status'))
          <div class="alert alert-success d-flex align-items-center rounded-3 shadow-sm mb-4" role="alert" style="background:#F0FDF4; border:1px solid #10B981; color:#065F46;">
            <svg class="me-2 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div>
              <strong>Email envoyé !</strong> {{ session('status') }}
            </div>
          </div>
        @endif

        {{-- Error Banner --}}
        @if ($errors->any())
          <div class="ss-form-error-banner" style="background:#FFF1F0; border:1px solid #FF6B3D; color:#C2410C; border-radius:10px; padding:0.75rem 1rem; font-size:0.85rem; margin-bottom:1.25rem;">
            {{ $errors->first() }}
          </div>
        @endif

        @if (config('mail.default') === 'log')
          <div class="alert alert-info py-2 px-3 small rounded-3 mb-4" style="background: rgba(14, 165, 233, 0.08); border: 1px solid rgba(14, 165, 233, 0.3); color: #0369a1;">
            <i class="bi bi-info-circle me-1"></i>
            <strong>Mode local (driver log) :</strong> L'email de réinitialisation avec son lien sécurisé est enregistré dans <code>storage/logs/laravel.log</code>.
          </div>
        @endif

        <form class="ss-auth-form" method="POST" action="{{ route('password.email') }}" novalidate>
          @csrf

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
                value="{{ old('email') }}"
                class="ss-form-input" 
                placeholder="name@example.com" 
                autocomplete="email"
                required
                autofocus
              >
            </div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="ss-btn ss-btn-primary ss-btn-submit" id="ss-btn-reset-request">
            <span class="ss-btn-text">Send Password Reset Link</span>
            <svg class="ss-btn-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </button>

        </form>

        <!-- Back to login prompt -->
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

