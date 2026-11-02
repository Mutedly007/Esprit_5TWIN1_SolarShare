{{-- resources/views/partials/solartec/footer.blade.php --}}
<footer class="ss-footer">
  <div class="ss-container">
    <div class="row">

      <!-- Brand & Mission Column -->
      <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
        <div class="ss-footer-brand">
          <a href="{{ url('/') }}" class="ss-brand" aria-label="SolarShare home">
            <svg class="ss-brand-mark" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <circle cx="18" cy="18" r="7" fill="#FFB020" />
              <path d="M18 2V7M18 29V34M2 18H7M29 18H34M6.68 6.68L10.22 10.22M25.78 25.78L29.32 29.32M6.68 29.32L10.22 25.78M25.78 10.22L29.32 6.68" stroke="#FF6B3D" stroke-width="2.6" stroke-linecap="round" />
            </svg>
            Solar<span style="color: #FFB020;">Share</span>
          </a>
          <p>{{ __('messages.footer.tagline') }}</p>
        </div>
      </div>

      <!-- Links Column 1: Platform -->
      <div class="col-lg-2 col-md-4 col-6 mb-4 mb-md-0">
        <h5 class="ss-footer-title">{{ __('messages.footer.platform') }}</h5>
        <ul class="ss-footer-links">
          <li><a href="{{ route('marketplace') }}" class="ss-footer-link">{{ __('messages.footer.browse_gear') }}</a></li>
          <li><a href="{{ url('/#how-it-works') }}" class="ss-footer-link">{{ __('messages.footer.how_it_works') }}</a></li>
          <li><a href="{{ url('/#community') }}" class="ss-footer-link">{{ __('messages.footer.solarshield') }}</a></li>
          <li><a href="{{ url('/#community') }}" class="ss-footer-link">{{ __('messages.footer.lender_earnings') }}</a></li>
          <li><a href="{{ url('/#faq') }}" class="ss-footer-link">{{ __('messages.footer.safety') }}</a></li>
        </ul>
      </div>

      <!-- Links Column 2: Community -->
      <div class="col-lg-2 col-md-4 col-6 mb-4 mb-md-0">
        <h5 class="ss-footer-title">{{ __('messages.footer.community') }}</h5>
        <ul class="ss-footer-links">
          <li><a href="{{ url('/#community') }}" class="ss-footer-link">{{ __('messages.footer.stories') }}</a></li>
          <li><a href="{{ url('/#impact') }}" class="ss-footer-link">{{ __('messages.footer.impact') }}</a></li>
          <li><a href="{{ route('hubs.index') }}" class="ss-footer-link">{{ __('messages.footer.hubs') }}</a></li>
          <li><a href="{{ route('energy-kits.index') }}" class="ss-footer-link">{{ __('messages.footer.guides') }}</a></li>
          <li><a href="{{ url('/#faq') }}" class="ss-footer-link">{{ __('messages.footer.emergency') }}</a></li>
        </ul>
      </div>

      <!-- Column 3: Newsletter Signup -->
      <div class="col-lg-4 col-md-4">
        <h5 class="ss-footer-title">{{ __('messages.footer.newsletter_title') }}</h5>
        <p style="font-size: 0.88rem; color: #94A3B8; margin-bottom: 0.85rem;">{{ __('messages.footer.newsletter_desc') }}</p>

        <form id="ss-newsletter-form" class="ss-newsletter-form" novalidate>
          <div class="ss-newsletter-group">
            <input type="email" id="ss-newsletter-email" class="ss-newsletter-input" placeholder="you@example.com" aria-label="Email address" required>
            <button type="submit" class="ss-btn ss-btn-primary ss-btn-sm" style="flex-shrink: 0;">{{ __('messages.footer.subscribe') }}</button>
          </div>
          <div id="ss-newsletter-msg" class="ss-newsletter-msg" aria-live="polite"></div>
        </form>
      </div>

    <!-- Watermark row -->
    <div class="ss-footer-watermark-row ss-reveal">
      <div class="ss-footer-watermark">SolarShare</div>
      <div class="ss-footer-solar-thumb">
        <img src="{{ asset('assets/images/solar-1.avif') }}" alt="SolarShare clean power" loading="lazy">
      </div>
    </div>

    <!-- Footer Bottom Bar -->
    <div class="ss-footer-bottom">
      <div style="font-size: 0.85rem; color: #64748B;">
        &copy; {{ date('Y') }} SolarShare Inc. All rights reserved. Peer-to-peer clean power network.
        <span style="display:inline-block; margin-left:1rem; font-size:0.8rem; color:#94A3B8;">
          Template : <a href="https://htmlcodex.com/renewable-energy-website-template" target="_blank" rel="noopener" style="color: #FFB020; text-decoration: underline;">Solartec</a> par HTML Codex / ThemeWagon
        </span>
      </div>

      <div class="ss-social-links">
        <!-- X / Twitter -->
        <a href="#" class="ss-social-btn" aria-label="Follow SolarShare on X">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
          </svg>
        </a>

        <!-- Instagram -->
        <a href="#" class="ss-social-btn" aria-label="Follow SolarShare on Instagram">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
          </svg>
        </a>

        <!-- GitHub -->
        <a href="#" class="ss-social-btn" aria-label="SolarShare GitHub repository">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
          </svg>
        </a>

        <!-- LinkedIn -->
        <a href="#" class="ss-social-btn" aria-label="Connect with SolarShare on LinkedIn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
          </svg>
        </a>
      </div>
    </div>

  </div>
</footer>
