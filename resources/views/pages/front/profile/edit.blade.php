{{-- resources/views/pages/front/profile/edit.blade.php --}}
@extends('layouts.solartec')

@section('title', __('messages.profile.title') . ' — SolarShare')
@section('meta_description', __('messages.profile.subtitle'))

@section('content')
<div class="ss-profile-page py-5">
  <div class="container">

    {{-- Breadcrumb & Title --}}
    <div class="row mb-4">
      <div class="col-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent p-0 mb-2">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--ss-ink-muted, #64748B);">{{ __('messages.nav.home') }}</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ __('messages.nav.profile') }}</li>
          </ol>
        </nav>
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
          <div>
            <h1 class="h2 fw-bold text-dark mb-1" style="font-family: 'Sora', 'Cairo', sans-serif;">{{ __('messages.profile.title') }}</h1>
            <p class="text-muted mb-0">{{ __('messages.profile.subtitle') }}</p>
          </div>
          <div class="d-flex align-items-center gap-2">
            @if($user->isAdmin())
              <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                <i class="fas fa-shield-alt me-1"></i> {{ __('messages.nav.admin_dashboard') }}
              </a>
            @endif
            @if($user->isLender())
              <a href="{{ route('lender.dashboard') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                <i class="fas fa-solar-panel me-1"></i> {{ __('messages.nav.lender_space') }}
              </a>
            @endif
            @if($user->isRenter())
              <a href="{{ route('rentals.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold">
                <i class="fas fa-clock me-1"></i> {{ __('messages.nav.my_rentals') }}
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert" style="background: #ECFDF5; color: #065F46; border-left: 5px solid #10B981 !important;">
        <div class="d-flex align-items-center">
          <i class="fas fa-check-circle fs-5 me-2" style="color: #10B981;"></i>
          <strong>{{ session('success') }}</strong>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.common.close') }}"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert" style="background: #FEF2F2; color: #991B1B; border-left: 5px solid #EF4444 !important;">
        <div class="d-flex align-items-center">
          <i class="fas fa-exclamation-triangle fs-5 me-2" style="color: #EF4444;"></i>
          <strong>{{ session('error') }}</strong>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.common.close') }}"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert" style="background: #FEF2F2; color: #991B1B; border-left: 5px solid #EF4444 !important;">
        <div class="d-flex align-items-center mb-2">
          <i class="fas fa-exclamation-circle fs-5 me-2" style="color: #EF4444;"></i>
          <strong>{{ __('messages.common.error') }} :</strong>
        </div>
        <ul class="mb-0 ps-4">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.common.close') }}"></button>
      </div>
    @endif

    <div class="row g-4">

      {{-- Left Column: Identity Overview Card + Quick Stats --}}
      <div class="col-lg-4">
        
        {{-- Profile Summary Card --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #ffffff; border: 1px solid rgba(16, 32, 43, 0.08) !important;">
          <div class="card-header border-0 text-center pt-4 pb-0" style="background: linear-gradient(135deg, rgba(255, 176, 32, 0.12), rgba(255, 107, 61, 0.08));">
            <div class="position-relative d-inline-block mb-3">
              @if($user->hasAvatar())
                <img id="ss-avatar-display" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle shadow-sm" style="width: 110px; height: 110px; object-fit: cover; border: 4px solid #ffffff;">
              @else
                <div id="ss-avatar-fallback" class="rounded-circle shadow-sm text-white d-inline-flex align-items-center justify-content-center" style="width: 110px; height: 110px; background: linear-gradient(135deg, #FFB020, #FF6B3D); font-size: 2.5rem; font-weight: 700; border: 4px solid #ffffff;">
                  {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <img id="ss-avatar-display" src="" alt="Aperçu" class="rounded-circle shadow-sm d-none" style="width: 110px; height: 110px; object-fit: cover; border: 4px solid #ffffff;">
              @endif

              {{-- Online / Verified Indicator Badge --}}
              <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 22px; height: 22px; border-width: 3px !important;" title="{{ __('messages.common.available') }}"></span>
            </div>

            <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
            <p class="text-muted small mb-2">{{ $user->email }}</p>

            <div class="mb-3 d-flex flex-wrap justify-content-center gap-1">
              @if($user->isAdmin())
                <span class="badge rounded-pill bg-danger px-3 py-1">👑 {{ __('messages.profile.admin_notice') ? 'Admin' : 'Admin' }}</span>
              @endif
              <span class="badge rounded-pill px-3 py-1" style="background: #0F5C6B; color: #ffffff;">
                {{ $user->solar_role_label }}
              </span>
              @if($user->provider)
                <span class="badge rounded-pill bg-light text-dark border px-2 py-1">
                  <i class="fab fa-{{ $user->provider }} me-1"></i> {{ ucfirst($user->provider) }}
                </span>
              @endif
            </div>
          </div>

          <div class="card-body pt-3">
            <ul class="list-group list-group-flush small">
              <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                <span class="text-muted"><i class="fas fa-calendar-alt me-2 text-primary"></i>{{ __('messages.profile.member_since') }}</span>
                <span class="fw-semibold text-dark">{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                <span class="text-muted"><i class="fas fa-solar-panel me-2 text-warning"></i>{{ __('messages.profile.listed_equipment') }}</span>
                <span class="badge rounded-pill bg-light text-dark fw-bold border">{{ $equipmentCount }}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                <span class="text-muted"><i class="fas fa-handshake me-2 text-info"></i>{{ __('messages.profile.past_rentals') }}</span>
                <span class="badge rounded-pill bg-light text-dark fw-bold border">{{ $rentalsCount }}</span>
              </li>
              @if($user->isLender())
                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                  <span class="text-muted"><i class="fas fa-charging-station me-2 text-success"></i>{{ __('messages.profile.honored_loans') }}</span>
                  <span class="badge rounded-pill bg-light text-dark fw-bold border">{{ $lentRentalsCount }}</span>
                </li>
              @endif
            </ul>
          </div>
        </div>

        {{-- Photo de Profil Upload Card --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #ffffff; border: 1px solid rgba(16, 32, 43, 0.08) !important;">
          <div class="card-header bg-white border-0 pt-4 pb-2">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center">
              <span class="d-inline-flex align-items-center justify-content-center rounded-circle me-2" style="width: 32px; height: 32px; background: rgba(255, 176, 32, 0.2); color: #FFB020;">
                <i class="fas fa-camera"></i>
              </span>
              {{ __('messages.profile.avatar_title') }}
            </h5>
            <small class="text-muted">{{ __('messages.profile.avatar_desc') }}</small>
          </div>
          <div class="card-body">
            <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" id="ss-avatar-form">
              @csrf

              <div class="mb-3">
                <label for="avatar-input" class="form-label small fw-bold text-dark">{{ __('messages.profile.select_photo') }}</label>
                <input type="file" name="avatar" id="avatar-input" class="form-control form-control-sm @error('avatar') is-invalid @enderror" accept="image/png, image/jpeg, image/jpg, image/webp, image/gif" required>
                @error('avatar')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text small text-muted">
                  {{ __('messages.profile.avatar_formats') }}
                </div>
              </div>

              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-sm btn-primary rounded-pill fw-bold py-2 shadow-sm">
                  <i class="fas fa-upload me-1"></i> {{ __('messages.profile.save_avatar') }}
                </button>
              </div>
            </form>

            @if($user->hasAvatar())
              <div class="mt-3 pt-3 border-top text-center">
                <form action="{{ route('profile.avatar.destroy') }}" method="POST" onsubmit="return confirm('{{ __('messages.profile.delete_avatar_confirm') }}');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none p-0 fw-semibold">
                    <i class="fas fa-trash-alt me-1"></i> {{ __('messages.profile.delete_avatar') }}
                  </button>
                </form>
              </div>
            @endif
          </div>
        </div>

      </div>

      {{-- Right Column: Informations Personnelles + Mot de passe + Zone Danger --}}
      <div class="col-lg-8">

        {{-- 1. Informations Personnelles --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #ffffff; border: 1px solid rgba(16, 32, 43, 0.08) !important;">
          <div class="card-header bg-white border-0 pt-4 pb-2">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center">
              <span class="d-inline-flex align-items-center justify-content-center rounded-circle me-2" style="width: 32px; height: 32px; background: rgba(15, 92, 107, 0.15); color: #0F5C6B;">
                <i class="fas fa-user-edit"></i>
              </span>
              {{ __('messages.profile.personal_info') }}
            </h5>
            <small class="text-muted">{{ __('messages.profile.personal_info_desc') }}</small>
          </div>
          <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST">
              @csrf
              @method('PATCH')

              <div class="row g-3">
                {{-- Nom complet --}}
                <div class="col-md-6">
                  <label for="name" class="form-label small fw-bold text-dark">{{ __('messages.profile.full_name') }} <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-user"></i></span>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control border-start-0 @error('name') is-invalid @enderror" placeholder="{{ __('messages.profile.full_name') }}" required>
                    @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                {{-- Adresse email --}}
                <div class="col-md-6">
                  <label for="email" class="form-label small fw-bold text-dark">{{ __('messages.profile.email_address') }} <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control border-start-0 @error('email') is-invalid @enderror" placeholder="name@example.com" required>
                    @error('email')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  @if($user->provider)
                    <div class="form-text small text-info">
                      <i class="fas fa-info-circle me-1"></i> {{ ucfirst($user->provider) }}
                    </div>
                  @endif
                </div>

                {{-- Rôle SolarShare --}}
                <div class="col-12 mt-4">
                  <label class="form-label small fw-bold text-dark d-block">{{ __('messages.profile.solar_role_label') }}</label>
                  
                  @if($user->isAdmin())
                    <div class="alert alert-secondary py-2 px-3 small rounded-3 mb-0">
                      <i class="fas fa-lock me-1"></i> {{ __('messages.profile.admin_notice') }}
                    </div>
                  @else
                    <div class="row g-2">
                      <div class="col-md-4">
                        <label class="card h-100 p-3 border rounded-3 cursor-pointer text-center ss-role-card {{ old('solar_role', $user->solar_role) === 'rent' ? 'border-primary bg-light' : '' }}" style="cursor: pointer;">
                          <input type="radio" name="solar_role" value="rent" class="d-none ss-role-radio" {{ old('solar_role', $user->solar_role) === 'rent' ? 'checked' : '' }}>
                          <div class="fs-3 mb-1">⚡</div>
                          <strong class="d-block text-dark small">{{ __('messages.profile.borrower') }}</strong>
                          <span class="text-muted" style="font-size: 0.75rem;">{{ __('messages.profile.borrower_desc') }}</span>
                        </label>
                      </div>

                      <div class="col-md-4">
                        <label class="card h-100 p-3 border rounded-3 cursor-pointer text-center ss-role-card {{ old('solar_role', $user->solar_role) === 'lend' ? 'border-primary bg-light' : '' }}" style="cursor: pointer;">
                          <input type="radio" name="solar_role" value="lend" class="d-none ss-role-radio" {{ old('solar_role', $user->solar_role) === 'lend' ? 'checked' : '' }}>
                          <div class="fs-3 mb-1">☀️</div>
                          <strong class="d-block text-dark small">{{ __('messages.profile.lender') }}</strong>
                          <span class="text-muted" style="font-size: 0.75rem;">{{ __('messages.profile.lender_desc') }}</span>
                        </label>
                      </div>

                      <div class="col-md-4">
                        <label class="card h-100 p-3 border rounded-3 cursor-pointer text-center ss-role-card {{ old('solar_role', $user->solar_role) === 'both' ? 'border-primary bg-light' : '' }}" style="cursor: pointer;">
                          <input type="radio" name="solar_role" value="both" class="d-none ss-role-radio" {{ old('solar_role', $user->solar_role) === 'both' ? 'checked' : '' }}>
                          <div class="fs-3 mb-1">🔁</div>
                          <strong class="d-block text-dark small">{{ __('messages.profile.mixed') }}</strong>
                          <span class="text-muted" style="font-size: 0.75rem;">{{ __('messages.profile.mixed_desc') }}</span>
                        </label>
                      </div>
                    </div>
                  @endif
                </div>

                <div class="col-12 text-end mt-4">
                  <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-save me-1"></i> {{ __('messages.profile.save_info') }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        {{-- 2. Sécurité & Mot de passe --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #ffffff; border: 1px solid rgba(16, 32, 43, 0.08) !important;">
          <div class="card-header bg-white border-0 pt-4 pb-2">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center">
              <span class="d-inline-flex align-items-center justify-content-center rounded-circle me-2" style="width: 32px; height: 32px; background: rgba(16, 185, 129, 0.15); color: #10B981;">
                <i class="fas fa-lock"></i>
              </span>
              {{ __('messages.profile.security_title') }}
            </h5>
            <small class="text-muted">{{ __('messages.profile.security_desc') }}</small>
          </div>
          <div class="card-body">
            <form action="{{ route('profile.password.update') }}" method="POST">
              @csrf
              @method('PUT')

              <div class="row g-3">
                {{-- Mot de passe actuel --}}
                @if($user->hasSetPassword())
                  <div class="col-12">
                    <label for="current_password" class="form-label small fw-bold text-dark">{{ __('messages.profile.current_password') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-key"></i></span>
                      <input type="password" name="current_password" id="current_password" class="form-control border-start-0 border-end-0 @error('current_password') is-invalid @enderror" placeholder="••••••••" required autocomplete="current-password">
                      <button type="button" class="input-group-text bg-light border-start-0 text-muted ss-toggle-pwd" data-target="current_password" aria-label="Toggle">
                        <i class="fas fa-eye"></i>
                      </button>
                      @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                @else
                  <div class="col-12">
                    <div class="alert alert-info py-2 px-3 small rounded-3 mb-1 border-0" style="background: #EFF6FF; color: #1E40AF;">
                      <i class="fas fa-info-circle me-1"></i> {{ __('messages.profile.oauth_no_pwd_notice') }}
                    </div>
                  </div>
                @endif

                {{-- Nouveau mot de passe --}}
                <div class="col-md-6">
                  <label for="new_password" class="form-label small fw-bold text-dark">{{ __('messages.profile.new_password') }} <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" id="new_password" class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror" placeholder="{{ __('messages.profile.new_password') }}" required autocomplete="new-password">
                    <button type="button" class="input-group-text bg-light border-start-0 text-muted ss-toggle-pwd" data-target="new_password" aria-label="Toggle">
                      <i class="fas fa-eye"></i>
                    </button>
                    @error('password')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-text small text-muted">
                    {{ __('messages.profile.password_rules') }}
                  </div>
                </div>

                {{-- Confirmation nouveau mot de passe --}}
                <div class="col-md-6">
                  <label for="password_confirmation" class="form-label small fw-bold text-dark">{{ __('messages.profile.confirm_password') }} <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-check-circle"></i></span>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-start-0 border-end-0" placeholder="{{ __('messages.profile.confirm_password') }}" required autocomplete="new-password">
                    <button type="button" class="input-group-text bg-light border-start-0 text-muted ss-toggle-pwd" data-target="password_confirmation" aria-label="Toggle">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                </div>

                <div class="col-12 text-end mt-4">
                  <button type="submit" class="btn btn-outline-success rounded-pill px-4 fw-bold">
                    <i class="fas fa-shield-alt me-1"></i> {{ __('messages.profile.update_password') }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>

        {{-- 3. Zone de Danger : Suppression du compte --}}
        <div class="card border-0 shadow-sm rounded-4" style="background: #ffffff; border: 1px solid rgba(239, 68, 68, 0.25) !important;">
          <div class="card-header border-0 pt-4 pb-2" style="background: rgba(254, 242, 242, 0.6);">
            <h5 class="fw-bold text-danger mb-0 d-flex align-items-center">
              <span class="d-inline-flex align-items-center justify-content-center rounded-circle me-2 bg-danger text-white" style="width: 32px; height: 32px;">
                <i class="fas fa-exclamation-triangle"></i>
              </span>
              {{ __('messages.profile.danger_zone') }}
            </h5>
            <small class="text-muted">{{ __('messages.profile.danger_desc') }}</small>
          </div>
          <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
              <div>
                <p class="mb-1 text-dark fw-semibold">{{ __('messages.profile.delete_account') }}</p>
                <p class="text-muted small mb-0">{{ __('messages.profile.delete_account_confirm_desc') }}</p>
              </div>
              <div>
                <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                  <i class="fas fa-trash-alt me-1"></i> {{ __('messages.profile.delete_account') }}
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
</div>

{{-- Modal de confirmation pour la suppression de compte --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-danger d-flex align-items-center" id="deleteAccountModalLabel">
          <i class="fas fa-exclamation-circle me-2"></i> {{ __('messages.profile.delete_account_confirm_title') }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.common.close') }}"></button>
      </div>
      <form action="{{ route('profile.destroy') }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body py-3">
          <p class="text-muted mb-3">
            {{ __('messages.profile.delete_account_confirm_desc') }}
          </p>

          @if($user->hasSetPassword())
            <div class="mb-3">
              <label for="delete_password" class="form-label small fw-bold text-dark">
                {{ __('messages.profile.confirm_pwd_for_delete') }}
              </label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" id="delete_password" class="form-control border-start-0" placeholder="••••••••" required autocomplete="current-password">
              </div>
            </div>
          @else
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="confirm_deletion" value="1" id="confirm_deletion" required>
              <label class="form-check-label small text-dark fw-semibold" for="confirm_deletion">
                {{ __('messages.profile.confirm_checkbox_for_delete') }}
              </label>
            </div>
          @endif
        </div>
        <div class="modal-footer border-0 pt-0 d-flex justify-content-end gap-2">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
          <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
            <i class="fas fa-trash me-1"></i> {{ __('messages.profile.delete_permanent') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .ss-role-card:hover {
    border-color: #0F5C6B !important;
    background-color: rgba(15, 92, 107, 0.04) !important;
    transform: translateY(-2px);
    transition: all 0.2s ease;
  }
  .ss-role-card.is-selected {
    border-color: #0F5C6B !important;
    background-color: rgba(15, 92, 107, 0.08) !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // 1. Live Avatar Preview before submission
    const avatarInput = document.getElementById('avatar-input');
    const avatarDisplay = document.getElementById('ss-avatar-display');
    const avatarFallback = document.getElementById('ss-avatar-fallback');

    if (avatarInput && avatarDisplay) {
      avatarInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function (event) {
            avatarDisplay.src = event.target.result;
            avatarDisplay.classList.remove('d-none');
            if (avatarFallback) {
              avatarFallback.classList.add('d-none');
            }
          };
          reader.readAsDataURL(file);
        }
      });
    }

    // 2. Interactive Role Card Selector
    const roleRadios = document.querySelectorAll('.ss-role-radio');
    const roleCards = document.querySelectorAll('.ss-role-card');

    roleRadios.forEach(radio => {
      radio.addEventListener('change', function () {
        roleCards.forEach(card => card.classList.remove('is-selected', 'border-primary', 'bg-light'));
        if (this.checked) {
          const parentCard = this.closest('.ss-role-card');
          if (parentCard) {
            parentCard.classList.add('is-selected');
          }
        }
      });
    });

    // 3. Toggle password visibility
    const toggleButtons = document.querySelectorAll('.ss-toggle-pwd');
    toggleButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target');
        const targetInput = document.getElementById(targetId);
        const icon = this.querySelector('i');

        if (targetInput) {
          if (targetInput.type === 'password') {
            targetInput.type = 'text';
            if (icon) {
              icon.classList.remove('fa-eye');
              icon.classList.add('fa-eye-slash');
            }
          } else {
            targetInput.type = 'password';
            if (icon) {
              icon.classList.remove('fa-eye-slash');
              icon.classList.add('fa-eye');
            }
          }
        }
      });
    });
  });
</script>
@endsection
