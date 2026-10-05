{{-- resources/views/pages/front/auth.blade.php --}}
@extends('layouts.solartec')

@section('title', 'Sign In or Join SolarShare — Clean Energy Sharing')
@section('meta_description', 'Sign in to SolarShare or register a new account to rent portable solar panels, home batteries, and small wind turbines from neighbors.')

@section('content')
<section class="ss-auth-section" data-initial-tab="{{ $initialTab ?? 'login' }}">
  <!-- Ambient Solar Glow Orbs in Background -->
  <div class="ss-auth-orb ss-auth-orb-amber" aria-hidden="true"></div>
  <div class="ss-auth-orb ss-auth-orb-teal" aria-hidden="true"></div>

  <div class="ss-container ss-auth-container">

    <!-- Top Breadcrumb / Return Link -->
    <div class="ss-auth-topbar">
      <a href="{{ url('/') }}" class="ss-auth-back-link">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Back to SolarShare</span>
      </a>
      <div class="ss-auth-trust-badge">
        <span class="ss-pulse-dot"></span>
        <span>100% Verified Neighborhood Sharing</span>
      </div>
    </div>

    <!-- Main Split Glass Auth Card -->
    <div class="ss-auth-card">

      <!-- Left Column: Visual Brand & Community Value Showcase -->
      <div class="ss-auth-showcase">
        <div class="ss-auth-showcase-slides">

          <!-- Slide 1: shown on LOGIN tab -->
          <div class="ss-auth-showcase-slide is-active" data-showcase="login">
            <div class="ss-auth-showcase-content">
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
                <span>The SolarShare Network</span>
              </div>

              <h1 class="ss-auth-showcase-title">
                Welcome back.<br>
                <span class="ss-text-gradient">The sun kept working.</span>
              </h1>

              <p class="ss-auth-showcase-desc">
                Your dashboard, active bookings, and energy earnings are right where you left them. Sign in and pick up the charge.
              </p>
            </div>
          </div>

          <!-- Slide 2: shown on REGISTER tab -->
          <div class="ss-auth-showcase-slide" data-showcase="register">
            <div class="ss-auth-showcase-content">
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
                <span>Join 12,000+ Sharers</span>
              </div>

              <h1 class="ss-auth-showcase-title">
                Borrow the sun.<br>
                <span class="ss-text-gradient">Power your world.</span>
              </h1>

              <p class="ss-auth-showcase-desc">
                Create a free account to rent verified solar gear from neighbors — or list your own and earn while it sits idle.
              </p>

              <ul class="ss-auth-perks">
                <li class="ss-auth-perk-item">
                  <div class="ss-auth-perk-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                      <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                  </div>
                  <div class="ss-auth-perk-text">
                    <strong>SolarShield™ protected</strong>
                    <span>Every rental covered up to $3,000</span>
                  </div>
                </li>
                <li class="ss-auth-perk-item">
                  <div class="ss-auth-perk-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                      <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                      <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                  </div>
                  <div class="ss-auth-perk-text">
                    <strong>Earn passive income</strong>
                    <span>Average lender makes $180/month</span>
                  </div>
                </li>
                <li class="ss-auth-perk-item">
                  <div class="ss-auth-perk-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                      <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                      <circle cx="9" cy="7" r="4"></circle>
                      <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                      <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                  </div>
                  <div class="ss-auth-perk-text">
                    <strong>Verified community</strong>
                    <span>ID-checked renters &amp; lenders only</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>

        </div>
      </div>

      <!-- Right Column: Interactive Tabbed Form (Login / Register) -->
      <div class="ss-auth-hub">
        
        <!-- Tab Switcher Navigation -->
        <div class="ss-auth-tab-nav" role="tablist" aria-label="Sign in or Sign up">
          <div class="ss-auth-tab-glider" id="ss-auth-glider" aria-hidden="true"></div>
          <button 
            type="button" 
            role="tab" 
            class="ss-auth-tab-btn {{ ($initialTab ?? 'login') === 'login' ? 'is-active' : '' }}" 
            id="ss-tab-btn-login" 
            aria-selected="{{ ($initialTab ?? 'login') === 'login' ? 'true' : 'false' }}" 
            aria-controls="ss-auth-panel-login"
            data-tab="login"
          >
            Log In
          </button>
          <button 
            type="button" 
            role="tab" 
            class="ss-auth-tab-btn {{ ($initialTab ?? 'login') === 'register' ? 'is-active' : '' }}" 
            id="ss-tab-btn-register" 
            aria-selected="{{ ($initialTab ?? 'login') === 'register' ? 'true' : 'false' }}" 
            aria-controls="ss-auth-panel-register"
            data-tab="register"
          >
            Create Account
          </button>
        </div>

        <!-- Animated Panel Viewport: height morphs smoothly between Login / Register -->
        <div class="ss-auth-panel-viewport" id="ss-auth-panel-viewport">

        <!-- ==========================================
             PANEL 1: LOGIN FORM
             ========================================== -->
        <div 
          id="ss-auth-panel-login" 
          class="ss-auth-panel {{ ($initialTab ?? 'login') === 'login' ? 'is-active' : '' }}" 
          role="tabpanel" 
          aria-labelledby="ss-tab-btn-login"
        >
          <div class="ss-auth-header">
            <h2 class="ss-auth-title">Welcome back</h2>
            <p class="ss-auth-subtitle">Enter your credentials to access your SolarShare dashboard</p>
          </div>

          <!-- Social Quick Sign-In -->
          <div class="ss-auth-socials">
            <a href="{{ route('oauth.redirect', ['provider' => 'google']) }}" class="ss-auth-oauth-btn" aria-label="Sign in with Google">
              <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.6l3.1-3.1C17.3 1.8 14.8 1 12 1 7.5 1 3.7 3.6 1.9 7.3l3.7 2.9C6.5 7.4 9 5 12 5z"/>
                <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.7-.2-2.3H12v4.5h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5.1 3.7-8.8z"/>
                <path fill="#FBBC05" d="M5.6 14.8c-.2-.7-.4-1.5-.4-2.3s.1-1.6.4-2.3L1.9 7.3C.7 9.7 0 12.3 0 15.2c0 2.8.7 5.5 1.9 7.8l3.7-2.9z"/>
                <path fill="#34A853" d="M12 23.5c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3 0-5.5-2-6.4-4.8L1.9 17C3.7 20.7 7.5 23.5 12 23.5z"/>
              </svg>
              <span>Google</span>
            </a>
            <a href="{{ route('oauth.redirect', ['provider' => 'github']) }}" class="ss-auth-oauth-btn" aria-label="Sign in with GitHub">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
              </svg>
              <span>GitHub</span>
            </a>
          </div>

          <div class="ss-auth-divider">
            <span>or sign in with email</span>
          </div>

          @if ($errors->any())
            <div class="ss-form-error-banner" style="background:#FFF1F0; border:1px solid #FF6B3D; color:#C2410C; border-radius:10px; padding:0.75rem 1rem; font-size:0.85rem; margin-bottom:1.25rem;">
              {{ $errors->first() }}
            </div>
          @endif

          <!-- Login Form -->
          <form class="ss-auth-form" id="ss-login-form" method="POST" action="{{ route('login.attempt') }}" novalidate>
            @csrf

            <!-- Email Input -->
            <div class="ss-form-group">
              <label for="ss-login-email" class="ss-form-label">Email address</label>
              <div class="ss-input-wrap">
                <span class="ss-input-icon">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                  </svg>
                </span>
                <input 
                  type="email" 
                  id="ss-login-email" 
                  name="email" 
                  value="{{ old('email') }}"
                  class="ss-form-input" 
                  placeholder="name@example.com" 
                  autocomplete="email"
                  required
                >
              </div>

              <!-- Live Email Validation Checks -->
              <div class="ss-field-checks" data-bound-to="ss-login-email">
                <div class="ss-field-check" data-rule="email-at">
                  <span class="ss-check-bullet">
                    <svg viewBox="0 0 12 10" fill="none" aria-hidden="true"><polyline class="ss-tick" points="1.5 5 4.5 8 10.5 2"></polyline><path class="ss-cross" d="M2.5 2.5 L9.5 8.5 M9.5 2.5 L2.5 8.5"></path></svg>
                  </span>
                  <span class="ss-check-text">Contains an @ symbol</span>
                </div>
                <div class="ss-field-check" data-rule="email-domain">
                  <span class="ss-check-bullet">
                    <svg viewBox="0 0 12 10" fill="none" aria-hidden="true"><polyline class="ss-tick" points="1.5 5 4.5 8 10.5 2"></polyline><path class="ss-cross" d="M2.5 2.5 L9.5 8.5 M9.5 2.5 L2.5 8.5"></path></svg>
                  </span>
                  <span class="ss-check-text">Valid domain ending (.com, .fr, .tn&hellip;)</span>
                </div>
              </div>
            </div>

            <!-- Password Input with Toggle -->
            <div class="ss-form-group">
              <div class="ss-form-label-row">
                <label for="ss-login-password" class="ss-form-label">Password</label>
                <!-- Line for Forgot Password -->
                <button type="button" class="ss-forgot-pwd-link" id="ss-btn-forgot-password">
                  Forgot password?
                </button>
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
                  id="ss-login-password" 
                  name="password" 
                  class="ss-form-input" 
                  placeholder="Enter your password" 
                  autocomplete="current-password"
                  required
                >
                <button type="button" class="ss-pwd-toggle" aria-label="Toggle password visibility" data-target="ss-login-password">
                  <svg class="ss-eye-show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                  <svg class="ss-eye-hide d-none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Remember Me Row -->
            <div class="ss-form-options">
              <label class="ss-checkbox-label">
                <input type="checkbox" id="ss-remember-me" name="remember" value="1" class="ss-checkbox-input">
                <span class="ss-checkbox-custom">
                  <svg width="12" height="10" viewBox="0 0 12 10" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="1.5 5 4.5 8 10.5 2"></polyline>
                  </svg>
                </span>
                <span class="ss-checkbox-text">Remember me for 30 days</span>
              </label>
            </div>

            <!-- Submit Button with Animation -->
            <button type="submit" class="ss-btn ss-btn-primary ss-btn-submit" id="ss-btn-login-submit">
              <span class="ss-btn-text">Sign In to SolarShare</span>
              <span class="ss-btn-spinner d-none" aria-hidden="true"></span>
              <svg class="ss-btn-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
              </svg>
            </button>

          </form>

          <!-- Bottom Switch Prompt -->
          <div class="ss-auth-switch-prompt">
            <span>Don't have an account yet?</span>
            <button type="button" class="ss-switch-link" data-switch-to="register">
              Create an account
            </button>
          </div>

        </div>


        <!-- ==========================================
             PANEL 2: SIGN UP / REGISTER FORM
             ========================================== -->
        <div 
          id="ss-auth-panel-register" 
          class="ss-auth-panel {{ ($initialTab ?? 'login') === 'register' ? 'is-active' : '' }}" 
          role="tabpanel" 
          aria-labelledby="ss-tab-btn-register"
        >
          <div class="ss-auth-header">
            <h2 class="ss-auth-title">Create your account</h2>
            <p class="ss-auth-subtitle">Join 3,150+ neighbors sharing clean energy gear</p>
          </div>

          <!-- Social Quick Sign-In -->
          <div class="ss-auth-socials">
            <a href="{{ route('oauth.redirect', ['provider' => 'google']) }}" class="ss-auth-oauth-btn" aria-label="Sign up with Google">
              <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.6l3.1-3.1C17.3 1.8 14.8 1 12 1 7.5 1 3.7 3.6 1.9 7.3l3.7 2.9C6.5 7.4 9 5 12 5z"/>
                <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.7-.2-2.3H12v4.5h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5.1 3.7-8.8z"/>
                <path fill="#FBBC05" d="M5.6 14.8c-.2-.7-.4-1.5-.4-2.3s.1-1.6.4-2.3L1.9 7.3C.7 9.7 0 12.3 0 15.2c0 2.8.7 5.5 1.9 7.8l3.7-2.9z"/>
                <path fill="#34A853" d="M12 23.5c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3 0-5.5-2-6.4-4.8L1.9 17C3.7 20.7 7.5 23.5 12 23.5z"/>
              </svg>
              <span>Google</span>
            </a>
            <a href="{{ route('oauth.redirect', ['provider' => 'github']) }}" class="ss-auth-oauth-btn" aria-label="Sign up with GitHub">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
              </svg>
              <span>GitHub</span>
            </a>
          </div>

          <div class="ss-auth-divider">
            <span>or continue with email</span>
          </div>

          @if ($errors->any())
            <div class="ss-form-error-banner" style="background:#FFF1F0; border:1px solid #FF6B3D; color:#C2410C; border-radius:10px; padding:0.75rem 1rem; font-size:0.85rem; margin-bottom:1.25rem;">
              {{ $errors->first() }}
            </div>
          @endif

          <!-- Sign Up Form -->
          <form class="ss-auth-form" id="ss-register-form" method="POST" action="{{ route('register.store') }}" novalidate>
            @csrf

            <!-- Full Name Input -->
            <div class="ss-form-group">
              <label for="ss-register-name" class="ss-form-label">Full name</label>
              <div class="ss-input-wrap">
                <span class="ss-input-icon">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                  </svg>
                </span>
                <input 
                  type="text" 
                  id="ss-register-name" 
                  name="name" 
                  value="{{ old('name') }}"
                  class="ss-form-input" 
                  placeholder="e.g. Alex Morgan" 
                  autocomplete="name"
                  required
                >
              </div>
            </div>

            <!-- Email Input -->
            <div class="ss-form-group">
              <label for="ss-register-email" class="ss-form-label">Email address</label>
              <div class="ss-input-wrap">
                <span class="ss-input-icon">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                  </svg>
                </span>
                <input 
                  type="email" 
                  id="ss-register-email" 
                  name="email" 
                  value="{{ old('email') }}"
                  class="ss-form-input" 
                  placeholder="name@example.com" 
                  autocomplete="email"
                  required
                >
              </div>

              <!-- Live Email Validation Checks -->
              <div class="ss-field-checks" data-bound-to="ss-register-email">
                <div class="ss-field-check" data-rule="email-at">
                  <span class="ss-check-bullet">
                    <svg viewBox="0 0 12 10" fill="none" aria-hidden="true"><polyline class="ss-tick" points="1.5 5 4.5 8 10.5 2"></polyline><path class="ss-cross" d="M2.5 2.5 L9.5 8.5 M9.5 2.5 L2.5 8.5"></path></svg>
                  </span>
                  <span class="ss-check-text">Contains an @ symbol</span>
                </div>
                <div class="ss-field-check" data-rule="email-domain">
                  <span class="ss-check-bullet">
                    <svg viewBox="0 0 12 10" fill="none" aria-hidden="true"><polyline class="ss-tick" points="1.5 5 4.5 8 10.5 2"></polyline><path class="ss-cross" d="M2.5 2.5 L9.5 8.5 M9.5 2.5 L2.5 8.5"></path></svg>
                  </span>
                  <span class="ss-check-text">Valid domain ending (.com, .fr, .tn&hellip;)</span>
                </div>
              </div>
            </div>

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
                    <span>Lend & earn</span>
                  </span>
                </label>
              </div>
            </div>

            <!-- Password Input with Strength Meter -->
            <div class="ss-form-group">
              <label for="ss-register-password" class="ss-form-label">Create password</label>
              <div class="ss-input-wrap">
                <span class="ss-input-icon">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                  </svg>
                </span>
                <input 
                  type="password" 
                  id="ss-register-password" 
                  name="password" 
                  class="ss-form-input" 
                  placeholder="At least 8 characters" 
                  autocomplete="new-password"
                  required
                >
                <button type="button" class="ss-pwd-toggle" aria-label="Toggle password visibility" data-target="ss-register-password">
                  <svg class="ss-eye-show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                  <svg class="ss-eye-hide d-none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                  </svg>
                </button>
              </div>

              <!-- Animated Password Strength Bar -->
              <div class="ss-pwd-meter-wrap" id="ss-pwd-meter-container">
                <div class="ss-pwd-meter-track">
                  <div class="ss-pwd-meter-fill" id="ss-pwd-meter-fill"></div>
                </div>
                <div class="ss-pwd-meter-hint" id="ss-pwd-meter-hint">Password strength: Empty</div>
              </div>

              <!-- Live Password Requirement Checklist -->
              <div class="ss-field-checks" data-bound-to="ss-register-password">
                <div class="ss-field-check" data-rule="pw-length">
                  <span class="ss-check-bullet">
                    <svg viewBox="0 0 12 10" fill="none" aria-hidden="true"><polyline class="ss-tick" points="1.5 5 4.5 8 10.5 2"></polyline><path class="ss-cross" d="M2.5 2.5 L9.5 8.5 M9.5 2.5 L2.5 8.5"></path></svg>
                  </span>
                  <span class="ss-check-text">At least 8 characters</span>
                </div>
                <div class="ss-field-check" data-rule="pw-upper">
                  <span class="ss-check-bullet">
                    <svg viewBox="0 0 12 10" fill="none" aria-hidden="true"><polyline class="ss-tick" points="1.5 5 4.5 8 10.5 2"></polyline><path class="ss-cross" d="M2.5 2.5 L9.5 8.5 M9.5 2.5 L2.5 8.5"></path></svg>
                  </span>
                  <span class="ss-check-text">One uppercase letter (A&ndash;Z)</span>
                </div>
                <div class="ss-field-check" data-rule="pw-lower">
                  <span class="ss-check-bullet">
                    <svg viewBox="0 0 12 10" fill="none" aria-hidden="true"><polyline class="ss-tick" points="1.5 5 4.5 8 10.5 2"></polyline><path class="ss-cross" d="M2.5 2.5 L9.5 8.5 M9.5 2.5 L2.5 8.5"></path></svg>
                  </span>
                  <span class="ss-check-text">One lowercase letter (a&ndash;z)</span>
                </div>
                <div class="ss-field-check" data-rule="pw-number">
                  <span class="ss-check-bullet">
                    <svg viewBox="0 0 12 10" fill="none" aria-hidden="true"><polyline class="ss-tick" points="1.5 5 4.5 8 10.5 2"></polyline><path class="ss-cross" d="M2.5 2.5 L9.5 8.5 M9.5 2.5 L2.5 8.5"></path></svg>
                  </span>
                  <span class="ss-check-text">One number (0&ndash;9)</span>
                </div>
                <div class="ss-field-check" data-rule="pw-symbol">
                  <span class="ss-check-bullet">
                    <svg viewBox="0 0 12 10" fill="none" aria-hidden="true"><polyline class="ss-tick" points="1.5 5 4.5 8 10.5 2"></polyline><path class="ss-cross" d="M2.5 2.5 L9.5 8.5 M9.5 2.5 L2.5 8.5"></path></svg>
                  </span>
                  <span class="ss-check-text">One symbol (!@#$&hellip;)</span>
                </div>
              </div>
            </div>

            <!-- Confirm Password Input -->
            <div class="ss-form-group">
              <label for="ss-register-confirm" class="ss-form-label">Confirm password</label>
              <div class="ss-input-wrap">
                <span class="ss-input-icon">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="20 6 9 17 4 12"></polyline>
                  </svg>
                </span>
                <input 
                  type="password" 
                  id="ss-register-confirm" 
                  name="password_confirmation" 
                  class="ss-form-input" 
                  placeholder="Repeat your password" 
                  autocomplete="new-password"
                  required
                >
              </div>

              <!-- Live Match Check -->
              <div class="ss-field-checks" data-bound-to="ss-register-confirm">
                <div class="ss-field-check" data-rule="pw-match" data-match-target="ss-register-password">
                  <span class="ss-check-bullet">
                    <svg viewBox="0 0 12 10" fill="none" aria-hidden="true"><polyline class="ss-tick" points="1.5 5 4.5 8 10.5 2"></polyline><path class="ss-cross" d="M2.5 2.5 L9.5 8.5 M9.5 2.5 L2.5 8.5"></path></svg>
                  </span>
                  <span class="ss-check-text">Both passwords match</span>
                </div>
              </div>
            </div>

            <!-- Terms & Community Guidelines Checkbox -->
            <div class="ss-form-options">
              <label class="ss-checkbox-label">
                <input type="checkbox" id="ss-terms-agree" name="terms" class="ss-checkbox-input" checked required>
                <span class="ss-checkbox-custom">
                  <svg width="12" height="10" viewBox="0 0 12 10" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="1.5 5 4.5 8 10.5 2"></polyline>
                  </svg>
                </span>
                <span class="ss-checkbox-text">
                  I agree to SolarShare's <a href="#" class="ss-inline-link">Terms</a> and <a href="#" class="ss-inline-link">Community Safety Guarantee</a>
                </span>
              </label>
            </div>

            <!-- Submit Button with Animation -->
            <button type="submit" class="ss-btn ss-btn-primary ss-btn-submit" id="ss-btn-register-submit">
              <span class="ss-btn-text">Create SolarShare Account</span>
              <span class="ss-btn-spinner d-none" aria-hidden="true"></span>
              <svg class="ss-btn-sun-spark" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
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
            </button>

          </form>

          <!-- Bottom Switch Prompt -->
          <div class="ss-auth-switch-prompt">
            <span>Already have an account?</span>
            <button type="button" class="ss-switch-link" data-switch-to="login">
              Sign in instead
            </button>
          </div>

        </div>

        </div><!-- /ss-auth-panel-viewport -->

      </div>

    </div>

  </div>

  <!-- Interactive Feedback Toast Notification -->
  <div id="ss-auth-toast" class="ss-auth-toast" role="status" aria-live="polite">
    <div class="ss-auth-toast-icon" id="ss-toast-icon">☀️</div>
    <div class="ss-auth-toast-body">
      <div class="ss-auth-toast-title" id="ss-toast-title">Success</div>
      <div class="ss-auth-toast-desc" id="ss-toast-desc">Demo mode: Action simulated successfully.</div>
    </div>
    <button type="button" class="ss-auth-toast-close" id="ss-toast-close" aria-label="Close message">×</button>
  </div>
</section>
@endsection
