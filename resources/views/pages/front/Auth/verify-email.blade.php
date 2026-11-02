@extends('layouts.solartec')

@section('title', 'Verify Your Email — SolarShare')
@section('meta_description', 'Verify your email address to activate your full SolarShare account.')

@section('content')
<section class="ss-auth-section">
  <!-- Ambient Solar Glow Orbs in Background -->
  <div class="ss-auth-orb ss-auth-orb-amber" aria-hidden="true"></div>
  <div class="ss-auth-orb ss-auth-orb-teal" aria-hidden="true"></div>

  <div class="ss-container ss-auth-container">

    <!-- Top Breadcrumb / Return Link -->
    <div class="ss-auth-topbar" style="justify-content: center;">
      <div class="ss-auth-trust-badge">
        <span class="ss-pulse-dot"></span>
        <span>Account Verification Required</span>
      </div>
    </div>

    <!-- Main Split Glass Auth Card -->
    <div class="ss-auth-card">

      <!-- Left Column: Visual Brand & Guidance -->
      <div class="ss-auth-showcase">
        <div class="ss-auth-showcase-content">
          <div class="ss-auth-badge">
            <svg class="ss-auth-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
              <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            <span>Email Verification</span>
          </div>

          <h1 class="ss-auth-showcase-title">
            Check your inbox.<br>
            <span class="ss-text-gradient">Verify your email.</span>
          </h1>

          <p class="ss-auth-showcase-desc">
            We sent an email verification link to <strong>{{ auth()->user()->email ?? 'your email' }}</strong>. Please click the link inside to verify your account and start sharing clean energy!
          </p>

          <ul class="ss-auth-perks">
            <li class="ss-auth-perk-item">
              <div class="ss-auth-perk-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
              </div>
              <div class="ss-auth-perk-text">
                <strong>Protection de la communauté</strong>
                <span>Tous les membres vérifient leur email</span>
              </div>
            </li>
            <li class="ss-auth-perk-item">
              <div class="ss-auth-perk-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
              </div>
              <div class="ss-auth-perk-text">
                <strong>Activation immédiate</strong>
                <span>Accès complet aux réservations dès confirmation</span>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Right Column: Verification Action Hub -->
      <div class="ss-auth-hub">
        
        <div class="ss-auth-header">
          <h2 class="ss-auth-title">Verify your email address</h2>
          <p class="ss-auth-subtitle">Didn't receive the email? We can easily send you another link.</p>
        </div>

        {{-- Verification link sent status --}}
        @if (session('status') == 'verification-link-sent')
          <div class="alert alert-success d-flex align-items-center rounded-3 shadow-sm mb-4" role="alert" style="background:#F0FDF4; border:1px solid #10B981; color:#065F46;">
            <svg class="me-2 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div>
              <strong>Nouveau lien envoyé !</strong> Un nouveau lien de vérification a été transmis à votre adresse email.
            </div>
          </div>
        @endif

        @if (config('mail.default') === 'log')
          <div class="alert alert-info py-2 px-3 small rounded-3 mb-4" style="background: rgba(14, 165, 233, 0.08); border: 1px solid rgba(14, 165, 233, 0.3); color: #0369a1;">
            <i class="bi bi-info-circle me-1"></i>
            <strong>Mode local (driver log) :</strong> L'email de vérification avec son lien sécurisé est enregistré dans <code>storage/logs/laravel.log</code>.
          </div>
        @endif

        <!-- Resend Form -->
        <form class="ss-auth-form" method="POST" action="{{ route('verification.send') }}">
          @csrf

          <button type="submit" class="ss-btn ss-btn-primary ss-btn-submit">
            <span class="ss-btn-text">Resend Verification Email</span>
            <svg class="ss-btn-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </button>
        </form>

        <!-- Sign-out option -->
        <div class="ss-auth-switch-prompt">
          <span>Wrong account?</span>
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

