@props(['equipment'])

<div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden equipment-card" style="transition: transform 0.2s ease, box-shadow 0.2s ease; background: #FFFFFF;">
    <!-- Image Container -->
    <div style="height: 220px; position: relative; overflow: hidden; background: #0F172A;">
        @if($equipment->image_url)
            <img src="{{ $equipment->image_url }}" alt="{{ $equipment->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
        @else
            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0F5C6B, #083E49); color: #FFB020; font-size: 3rem;">
                ☀️
            </div>
        @endif

        <!-- Category Badge -->
        <span style="position: absolute; top: 12px; inset-inline-start: 12px; background: rgba(16, 32, 43, 0.85); backdrop-filter: blur(8px); color: #FFFFFF; font-size: 0.74rem; font-weight: 700; padding: 0.3rem 0.75rem; border-radius: 9999px;">
            {{ $equipment->category?->localized_name ?? __('messages.marketplace.default_category') }}
        </span>

        <!-- Availability Badge -->
        <span style="position: absolute; top: 12px; inset-inline-end: 12px; background: {{ $equipment->is_available ? '#DCFCE7' : '#FEE2E2' }}; color: {{ $equipment->is_available ? '#166534' : '#991B1B' }}; font-size: 0.74rem; font-weight: 700; padding: 0.3rem 0.65rem; border-radius: 9999px; display: inline-flex; align-items: center; gap: 0.3rem;">
            <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $equipment->is_available ? '#16A34A' : '#EF4444' }};"></span>
            {{ $equipment->is_available ? __('messages.marketplace.badge_available') : __('messages.marketplace.badge_reserved') }}
        </span>
    </div>

    <!-- Card Body -->
    <div class="card-body p-4 d-flex flex-column justify-content-between">
        <div>
            <!-- Brand -->
            <div style="font-size: 0.78rem; font-weight: 700; color: #0F5C6B; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.3rem;">
                {{ $equipment->brand ?: __('messages.marketplace.certified_gear') }}
            </div>

            <!-- Name -->
            <h5 class="card-title fw-bold text-dark mb-2" style="font-size: 1.15rem; line-height: 1.35;">
                {{ $equipment->name }}
            </h5>

            <!-- Description snippet -->
            <p class="text-muted small mb-3" style="line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $equipment->description ?: __('messages.marketplace.no_description') }}
            </p>

            <!-- Specs Pills -->
            <div style="display: flex; gap: 0.4rem; flex-wrap: wrap; margin-bottom: 1.25rem;">
                @if($equipment->power_watts)
                    <span style="display: inline-flex; align-items: center; gap: 0.3rem; background: #F1F5F9; color: #1E293B; font-size: 0.76rem; font-weight: 700; padding: 0.25rem 0.55rem; border-radius: 8px;">
                        ⚡ {{ $equipment->power_watts }} W
                    </span>
                @endif
                @if($equipment->capacity_wh)
                    <span style="display: inline-flex; align-items: center; gap: 0.3rem; background: #F1F5F9; color: #1E293B; font-size: 0.76rem; font-weight: 700; padding: 0.25rem 0.55rem; border-radius: 8px;">
                        🔋 {{ $equipment->capacity_wh }} Wh
                    </span>
                @endif
            </div>
        </div>

        <!-- Pricing & Action -->
        <div style="border-top: 1px solid #F1F5F9; padding-top: 1rem; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="display: flex; align-items: baseline; gap: 0.25rem;">
                    <span style="font-size: 1.35rem; font-weight: 800; color: #10202B;">
                        {{ number_format($equipment->daily_rate, 2) }}
                    </span>
                    <span style="font-size: 0.8rem; color: #64748B; font-weight: 600;">{{ __('messages.marketplace.tnd_per_day') }}</span>
                </div>
                <span style="display: block; font-size: 0.75rem; color: #94A3B8;">
                    {{ __('messages.marketplace.deposit_card', ['amount' => number_format($equipment->deposit_amount, 2)]) }}
                </span>
            </div>

            <a href="{{ route('marketplace.show', $equipment) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2" style="font-weight: 700; font-size: 0.85rem; border-color: #0F5C6B; color: #0F5C6B;">
                {{ __('messages.marketplace.details_btn') }} {!! app()->getLocale() === 'ar' ? '&larr;' : '&rarr;' !!}
            </a>
        </div>
    </div>

    <!-- Card Footer: Owner Info -->
    <div style="background: #FAFCFE; border-top: 1px solid #F1F5F9; padding: 0.65rem 1.25rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.78rem; color: #64748B;">
        <div style="display: flex; align-items: center; gap: 0.45rem;">
            <span style="width: 22px; height: 22px; border-radius: 50%; background: linear-gradient(135deg, #FFB020, #FF6B3D); color: #FFFFFF; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700;">
                {{ mb_strtoupper(mb_substr($equipment->user?->name ?? 'P', 0, 1)) }}
            </span>
            <span>{{ $equipment->user?->name ?? __('messages.marketplace.partner_lender') }}</span>
        </div>
        <span style="font-size: 0.72rem; color: #94A3B8;">
            {{ $equipment->created_at?->locale(app()->getLocale())->diffForHumans() }}
        </span>
    </div>
</div>
