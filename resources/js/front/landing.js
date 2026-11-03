/**
 * SolarShare — Interactive Front-End Logic
 * Vanilla ES Module / Universal Script
 * Smooth performance using IntersectionObserver, RAF, and CSS custom property updates
 */

/* ── Bootstrap JS (Collapse, Dropdown, etc.) ── */
import 'bootstrap';

/* ── Landing page CSS (Vite bundles this alongside the JS) ── */
import '../../css/front/landing.css';

(function initSolarShare() {
  function start() {
    const isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const isPointerFine = window.matchMedia('(pointer: fine)').matches;

    // Signal that JS is ready to activate progressive enhancement reveals
    document.documentElement.classList.add('ss-js-ready');

    /* ==========================================================================
       1. THE SUN IS THE NARRATOR: Dynamic Sky & Arc Sun
       ========================================================================== */
    const skyBackdrop = document.getElementById('ss-sky-backdrop');
    const skyStars = document.getElementById('ss-sky-stars');
    const narratorSun = document.getElementById('ss-narrator-sun');

    function updateNarratorSunAndSky() {
      const scrollY = window.scrollY || window.pageYOffset;
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      const progress = Math.max(0, Math.min(1, docHeight > 0 ? scrollY / docHeight : 0));

      if (!isReducedMotion && narratorSun) {
        const winW = window.innerWidth;
        const winH = window.innerHeight;

        const sunX = 30 + progress * (winW - 120);
        const arcHeight = Math.min(winH * 0.45, 300);
        const sunY = (1 - Math.sin(progress * Math.PI)) * arcHeight + 35;

        narratorSun.style.transform = `translate3d(${sunX}px, ${sunY}px, 0)`;

        if (progress < 0.28) {
          narratorSun.style.background = 'radial-gradient(circle, #FFF4CE 0%, #FFB020 60%, #FF6B3D 100%)';
          narratorSun.style.boxShadow = '0 0 28px rgba(255, 176, 32, 0.65), 0 0 70px rgba(255, 176, 32, 0.35)';
        } else if (progress < 0.68) {
          narratorSun.style.background = 'radial-gradient(circle, #FFFFFF 0%, #FFF5D0 40%, #FFB020 100%)';
          narratorSun.style.boxShadow = '0 0 36px rgba(255, 230, 150, 0.8), 0 0 85px rgba(255, 176, 32, 0.45)';
        } else if (progress < 0.88) {
          narratorSun.style.background = 'radial-gradient(circle, #FFE2B8 0%, #FF8A3D 60%, #E2481F 100%)';
          narratorSun.style.boxShadow = '0 0 32px rgba(255, 107, 61, 0.7), 0 0 75px rgba(255, 107, 61, 0.35)';
        } else {
          narratorSun.style.background = 'radial-gradient(circle, #FF9565 0%, #DF4D26 70%, #0E1B2C 100%)';
          narratorSun.style.boxShadow = '0 0 20px rgba(255, 107, 61, 0.4)';
        }
      }

      if (skyBackdrop) {
        if (progress < 0.28) {
          document.documentElement.style.setProperty('--ss-sky-top', '#FDE4CC');
          document.documentElement.style.setProperty('--ss-sky-bottom', '#FFF9F2');
          document.documentElement.style.setProperty('--ss-sky-radial', 'rgba(255, 176, 32, 0.28)');
          if (skyStars) skyStars.classList.remove('is-visible');
        } else if (progress < 0.68) {
          document.documentElement.style.setProperty('--ss-sky-top', '#CDEBF5');
          document.documentElement.style.setProperty('--ss-sky-bottom', '#F5FBFE');
          document.documentElement.style.setProperty('--ss-sky-radial', 'rgba(205, 235, 245, 0.35)');
          if (skyStars) skyStars.classList.remove('is-visible');
        } else if (progress < 0.88) {
          document.documentElement.style.setProperty('--ss-sky-top', '#FCD692');
          document.documentElement.style.setProperty('--ss-sky-bottom', '#FBE5D6');
          document.documentElement.style.setProperty('--ss-sky-radial', 'rgba(255, 107, 61, 0.22)');
          if (skyStars) skyStars.classList.remove('is-visible');
        } else {
          document.documentElement.style.setProperty('--ss-sky-top', '#0E1B2C');
          document.documentElement.style.setProperty('--ss-sky-bottom', '#070F1A');
          document.documentElement.style.setProperty('--ss-sky-radial', 'rgba(15, 92, 107, 0.2)');
          if (skyStars) skyStars.classList.add('is-visible');
        }
      }

      updateHowItWorksLine();
    }

    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        window.requestAnimationFrame(() => {
          updateNarratorSunAndSky();
          updateNavbarOnScroll();
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });

    window.addEventListener('resize', () => {
      updateNarratorSunAndSky();
      repositionNavIndicator();
    }, { passive: true });

    /* ==========================================================================
       2. NAVBAR: Frosted Glass, Active Indicator & Mobile Menu
       ========================================================================== */
    const navbar = document.getElementById('ss-navbar');
    const navLinks = document.querySelectorAll('.ss-nav-link');
    const navIndicator = document.getElementById('ss-nav-sun-indicator');
    const hamburger = document.getElementById('ss-hamburger');
    const mobileMenu = document.getElementById('ss-mobile-menu');
    const mobileLinks = document.querySelectorAll('.ss-mobile-nav-link');

    let lastScrollY = Math.max(0, window.scrollY);
    let isNavHidden = false;
    let scrollAccumulator = 0;
    const NAV_SCROLL_THRESHOLD = 30;
    const TOGGLE_THRESHOLD = 10;

    function updateNavbarOnScroll() {
      if (!navbar) return;
      const currentScrollY = Math.max(0, window.scrollY);
      const delta = currentScrollY - lastScrollY;

      // Keep navbar visible if mobile menu is open
      if (mobileMenu && mobileMenu.classList.contains('is-open')) {
        navbar.classList.remove('ss-navbar-hidden');
        isNavHidden = false;
        scrollAccumulator = 0;
        lastScrollY = currentScrollY;
        return;
      }

      // At the top of the page: transparent background and naturally visible
      if (currentScrollY <= 10) {
        navbar.classList.remove('ss-scrolled');
        navbar.classList.remove('ss-navbar-hidden');
        isNavHidden = false;
        scrollAccumulator = 0;
        lastScrollY = currentScrollY;
        return;
      }

      // Frosted glass background when scrolled past 10px so content never shows through
      navbar.classList.add('ss-scrolled');

      // Beyond threshold: hide when scrolling down, show when scrolling up
      if (currentScrollY > NAV_SCROLL_THRESHOLD) {
        if ((delta > 0 && scrollAccumulator < 0) || (delta < 0 && scrollAccumulator > 0)) {
          scrollAccumulator = 0;
        }
        scrollAccumulator += delta;

        if (scrollAccumulator > TOGGLE_THRESHOLD && !isNavHidden) {
          // Scrolling down: navbar slides up out of view (doesn't follow)
          navbar.classList.add('ss-navbar-hidden');
          isNavHidden = true;
          scrollAccumulator = 0;
        } else if (scrollAccumulator < -TOGGLE_THRESHOLD && isNavHidden) {
          // Scrolling back up: navbar appears with smooth animation
          navbar.classList.remove('ss-navbar-hidden');
          isNavHidden = false;
          scrollAccumulator = 0;
        }
      } else {
        navbar.classList.remove('ss-navbar-hidden');
        isNavHidden = false;
        scrollAccumulator = 0;
      }

      lastScrollY = currentScrollY;
    }

    let activeSectionId = '';
    const sections = document.querySelectorAll('section[id]');

    function updateActiveNavLink(id) {
      if (!id || activeSectionId === id) return;
      activeSectionId = id;

      let activeLink = null;
      navLinks.forEach(link => {
        const href = link.getAttribute('href') || '';
        if (href === `#${id}` || href.endsWith(`#${id}`)) {
          activeLink = link;
        }
      });

      if (activeLink && navIndicator) {
        const linkRect = activeLink.getBoundingClientRect();
        const parentRect = activeLink.closest('.ss-nav-menu').getBoundingClientRect();
        const offsetLeft = linkRect.left - parentRect.left + (linkRect.width / 2) - 4;
        navIndicator.style.transform = `translate3d(${offsetLeft}px, 0, 0)`;
        navIndicator.classList.add('is-active');
      } else if (navIndicator) {
        navIndicator.classList.remove('is-active');
      }
    }

    function repositionNavIndicator() {
      if (activeSectionId) {
        let activeLink = null;
        navLinks.forEach(link => {
          const href = link.getAttribute('href') || '';
          if (href === `#${activeSectionId}` || href.endsWith(`#${activeSectionId}`)) {
            activeLink = link;
          }
        });
        if (activeLink && navIndicator) {
          const linkRect = activeLink.getBoundingClientRect();
          const parentRect = activeLink.closest('.ss-nav-menu').getBoundingClientRect();
          const offsetLeft = linkRect.left - parentRect.left + (linkRect.width / 2) - 4;
          navIndicator.style.transform = `translate3d(${offsetLeft}px, 0, 0)`;
        }
      }
    }

    const sectionObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          updateActiveNavLink(entry.target.id);
        }
      });
    }, {
      rootMargin: '-25% 0px -45% 0px',
      threshold: 0.1
    });

    sections.forEach(sec => sectionObserver.observe(sec));

    if (hamburger && mobileMenu) {
      const focusableElements = mobileMenu.querySelectorAll('a, button');
      const firstFocusable = focusableElements[0];
      const lastFocusable = focusableElements[focusableElements.length - 1];

      function toggleMobileMenu(open) {
        const isOpen = open !== undefined ? open : !mobileMenu.classList.contains('is-open');
        hamburger.classList.toggle('is-open', isOpen);
        hamburger.setAttribute('aria-expanded', isOpen);
        mobileMenu.classList.toggle('is-open', isOpen);
        document.body.style.overflow = isOpen ? 'hidden' : '';

        if (isOpen && firstFocusable) {
          firstFocusable.focus();
        }
      }

      hamburger.addEventListener('click', () => toggleMobileMenu());

      mobileLinks.forEach(link => {
        link.addEventListener('click', () => toggleMobileMenu(false));
      });

      document.addEventListener('keydown', (e) => {
        if (mobileMenu.classList.contains('is-open')) {
          if (e.key === 'Escape') {
            toggleMobileMenu(false);
            hamburger.focus();
          } else if (e.key === 'Tab') {
            if (e.shiftKey && document.activeElement === firstFocusable) {
              e.preventDefault();
              lastFocusable.focus();
            } else if (!e.shiftKey && document.activeElement === lastFocusable) {
              e.preventDefault();
              firstFocusable.focus();
            }
          }
        }
      });
    }

    /* ==========================================================================
       3. HERO INTERACTION & SOLAR PANEL TRACKING
       ========================================================================== */
    const heroVisual = document.getElementById('ss-hero-visual');
    const panelGroup = document.getElementById('ss-panel-tilt-group');
    const floatCards = document.querySelectorAll('.ss-float-card');

    if (isPointerFine && heroVisual && panelGroup && !isReducedMotion) {
      heroVisual.addEventListener('mousemove', (e) => {
        const rect = heroVisual.getBoundingClientRect();
        const mouseX = e.clientX - rect.left;
        const mouseY = e.clientY - rect.top;
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        const deltaX = (mouseX - centerX) / centerX;
        const tiltAngle = Math.max(-20, Math.min(20, deltaX * 20));
        panelGroup.style.transform = `rotate(${tiltAngle}deg)`;

        floatCards.forEach((card, i) => {
          const depth = (i + 1) * 9;
          const moveX = (mouseX - centerX) / depth;
          const moveY = (mouseY - centerY) / depth;
          card.style.transform = `translate3d(${moveX}px, ${moveY}px, 0)`;
        });
      });

      heroVisual.addEventListener('mouseleave', () => {
        panelGroup.style.transform = 'rotate(0deg)';
        floatCards.forEach(card => {
          card.style.transform = 'translate3d(0, 0, 0)';
        });
      });
    }

    /* ==========================================================================
       4. HOW IT WORKS: Scroll-Driven Line & Step Charge
       ========================================================================== */
    const howSection = document.getElementById('how-it-works');
    const howPathActive = document.getElementById('ss-how-path-active');
    const stepCards = document.querySelectorAll('.ss-step-card');

    function updateHowItWorksLine() {
      if (!howSection || !howPathActive || isReducedMotion) return;
      const rect = howSection.getBoundingClientRect();
      const winHeight = window.innerHeight;

      if (rect.top < winHeight && rect.bottom > 0) {
        const visibleRange = winHeight + rect.height;
        const scrolledInto = winHeight - rect.top;
        const scrollPct = Math.max(0, Math.min(1, scrolledInto / visibleRange));

        const totalLength = 900;
        const offset = totalLength * (1 - scrollPct * 1.35);
        howPathActive.style.strokeDashoffset = Math.max(0, offset);

        stepCards.forEach((card, idx) => {
          const threshold = 0.18 + idx * 0.18;
          if (scrollPct >= threshold) {
            card.classList.add('is-charged');
          } else {
            card.classList.remove('is-charged');
          }
        });
      }
    }

    /* ==========================================================================
       5. EQUIPMENT: 3D Tilt Effect on Bento Cards
       ========================================================================== */
    const bentoCards = document.querySelectorAll('.ss-bento-card');
    if (isPointerFine && !isReducedMotion) {
      bentoCards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
          const rect = card.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;
          const centerX = rect.width / 2;
          const centerY = rect.height / 2;

          const rotateX = ((centerY - y) / centerY) * 4.5;
          const rotateY = ((x - centerX) / centerX) * 4.5;

          card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
        });

        card.addEventListener('mouseleave', () => {
          card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0)';
        });
      });
    }

    /* ==========================================================================
       6. IMPACT: Count-up Statistics, Gauge & Bar Chart Animation
       ========================================================================== */
    const impactSection = document.getElementById('impact');
    const countElements = document.querySelectorAll('.ss-count-val');
    const gaugeFill = document.getElementById('ss-gauge-fill');
    const chartBars = document.querySelectorAll('.ss-bar');
    let impactAnimated = false;

    function runCountUp(el) {
      const target = parseFloat(el.getAttribute('data-target'));
      const isFloat = el.getAttribute('data-float') === 'true';
      const duration = 1600;
      const startTime = performance.now();

      function updateCount(now) {
        const elapsed = now - startTime;
        const progress = Math.min(1, elapsed / duration);
        const ease = 1 - Math.pow(1 - progress, 3);
        const current = target * ease;

        if (isFloat) {
          el.textContent = current.toFixed(1);
        } else {
          el.textContent = Math.round(current).toLocaleString();
        }

        if (progress < 1) {
          requestAnimationFrame(updateCount);
        } else {
          if (isFloat) {
            el.textContent = target.toFixed(1);
          } else {
            el.textContent = target.toLocaleString();
          }
        }
      }

      requestAnimationFrame(updateCount);
    }

    if (impactSection) {
      const impactObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting && !impactAnimated) {
            impactAnimated = true;

            countElements.forEach(el => {
              if (!isReducedMotion) {
                runCountUp(el);
              } else {
                const target = el.getAttribute('data-target');
                el.textContent = parseFloat(target).toLocaleString();
              }
            });

            if (gaugeFill) {
              gaugeFill.style.strokeDashoffset = '26.4';
            }

            chartBars.forEach(bar => {
              const height = bar.getAttribute('data-height');
              bar.style.height = `${height}%`;
            });

            impactObserver.disconnect();
          }
        });
      }, { threshold: 0.2 });

      impactObserver.observe(impactSection);
    }

    /* ==========================================================================
       7. COMMUNITY: Two-Tab Switch (Rent vs Lend)
       ========================================================================== */
    const tabButtons = document.querySelectorAll('.ss-tab-btn');
    const tabPanels = document.querySelectorAll('.ss-tab-panel');

    tabButtons.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = btn.getAttribute('aria-controls');

        tabButtons.forEach(b => {
          b.setAttribute('aria-selected', 'false');
          b.setAttribute('tabindex', '-1');
        });
        btn.setAttribute('aria-selected', 'true');
        btn.setAttribute('tabindex', '0');

        tabPanels.forEach(panel => {
          if (panel.id === targetId) {
            panel.classList.add('is-active');
          } else {
            panel.classList.remove('is-active');
          }
        });
      });

      btn.addEventListener('keydown', (e) => {
        let index = Array.from(tabButtons).indexOf(btn);
        if (e.key === 'ArrowRight') {
          e.preventDefault();
          const next = tabButtons[(index + 1) % tabButtons.length];
          next.focus();
          next.click();
        } else if (e.key === 'ArrowLeft') {
          e.preventDefault();
          const prev = tabButtons[(index - 1 + tabButtons.length) % tabButtons.length];
          prev.focus();
          prev.click();
        }
      });
    });

    /* ==========================================================================
       8. FAQ: Accordion Smooth Height (CSS Grid trick)
       ========================================================================== */
    const accordionTriggers = document.querySelectorAll('.ss-accordion-trigger');

    accordionTriggers.forEach(trigger => {
      trigger.addEventListener('click', () => {
        const item = trigger.closest('.ss-accordion-item');
        const isExpanded = trigger.getAttribute('aria-expanded') === 'true';

        accordionTriggers.forEach(otherTrigger => {
          if (otherTrigger !== trigger) {
            otherTrigger.setAttribute('aria-expanded', 'false');
            otherTrigger.closest('.ss-accordion-item').classList.remove('is-open');
          }
        });

        trigger.setAttribute('aria-expanded', !isExpanded);
        item.classList.toggle('is-open', !isExpanded);
      });
    });

    /* ==========================================================================
       9. FINAL CTA: Dusk Sunset Observer
       ========================================================================== */
    const ctaDusk = document.getElementById('ss-cta-dusk');
    if (ctaDusk) {
      const duskObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            ctaDusk.classList.add('is-visible');
          }
        });
      }, { threshold: 0.15 });
      duskObserver.observe(ctaDusk);
    }

    /* ==========================================================================
       10. FOOTER NEWSLETTER: Inline Validation & Feedback
       ========================================================================== */
    const newsletterForm = document.getElementById('ss-newsletter-form');
    const newsletterInput = document.getElementById('ss-newsletter-email');
    const newsletterMsg = document.getElementById('ss-newsletter-msg');

    if (newsletterForm && newsletterInput && newsletterMsg) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      newsletterInput.addEventListener('input', () => {
        const val = newsletterInput.value.trim();
        if (val === '') {
          newsletterInput.classList.remove('is-valid', 'is-invalid');
          newsletterMsg.textContent = '';
        } else if (emailRegex.test(val)) {
          newsletterInput.classList.add('is-valid');
          newsletterInput.classList.remove('is-invalid');
          newsletterMsg.style.color = 'var(--ss-success)';
          newsletterMsg.textContent = '✓ Ready to receive clean energy alerts';
        } else {
          newsletterInput.classList.add('is-invalid');
          newsletterInput.classList.remove('is-valid');
          newsletterMsg.style.color = 'var(--ss-ember-coral)';
          newsletterMsg.textContent = 'Please enter a valid email address';
        }
      });

      newsletterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const val = newsletterInput.value.trim();
        if (emailRegex.test(val)) {
          newsletterInput.value = '';
          newsletterInput.classList.remove('is-valid');
          newsletterMsg.style.color = 'var(--ss-sun-amber)';
          newsletterMsg.textContent = '★ Thank you! You are subscribed to SolarShare alerts.';
        } else {
          newsletterInput.classList.add('is-invalid');
          newsletterMsg.style.color = 'var(--ss-ember-coral)';
          newsletterMsg.textContent = 'Please provide a valid email before subscribing.';
          newsletterInput.focus();
        }
      });
    }

    /* ==========================================================================
       11. MICRO-INTERACTIONS: Magnetic Pull on Desktop Buttons
       ========================================================================== */
    const magneticButtons = document.querySelectorAll('.ss-btn');
    if (isPointerFine && !isReducedMotion) {
      magneticButtons.forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
          const rect = btn.getBoundingClientRect();
          const x = e.clientX - rect.left - rect.width / 2;
          const y = e.clientY - rect.top - rect.height / 2;
          btn.style.transform = `translate3d(${x * 0.16}px, ${y * 0.16}px, 0)`;
        });

        btn.addEventListener('mouseleave', () => {
          btn.style.transform = 'translate3d(0, 0, 0)';
        });
      });
    }

    /* ==========================================================================
       12. SCROLL ENTRANCE REVEALS (Once, Unobserve on Reveal)
       ========================================================================== */
    const revealElements = document.querySelectorAll('.ss-reveal');
    if (!isReducedMotion) {
      const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      }, {
        threshold: 0.1,
        rootMargin: '0px 0px -30px 0px'
      });

      revealElements.forEach(el => revealObserver.observe(el));
    } else {
      revealElements.forEach(el => el.classList.add('is-visible'));
    }

    /* ==========================================================================
       13. ARVORA CURTAIN / SHUTTER IMAGE REVEALS ON SCROLL
       ========================================================================== */
    const curtainWraps = document.querySelectorAll('.ss-reveal-wrap');
    if (!isReducedMotion) {
      const curtainObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-revealed');
            observer.unobserve(entry.target);
          }
        });
      }, {
        threshold: 0.15,
        rootMargin: '0px 0px -40px 0px'
      });

      curtainWraps.forEach(wrap => curtainObserver.observe(wrap));
    } else {
      curtainWraps.forEach(wrap => wrap.classList.add('is-revealed'));
    }

    /* ==========================================================================
       13b. SHOWCASE CARDS STACKED-TO-SPREAD ANIMATION
       ========================================================================== */
    const showcaseGrid = document.querySelector('.ss-showcase-cards-grid');
    if (showcaseGrid) {
      if (!isReducedMotion) {
        const spreadObserver = new IntersectionObserver((entries, observer) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-spread');
              observer.unobserve(entry.target);
            }
          });
        }, {
          threshold: 0.2,
          rootMargin: '0px 0px -60px 0px'
        });

        spreadObserver.observe(showcaseGrid);
      } else {
        showcaseGrid.classList.add('is-spread');
      }
    }

    /* ==========================================================================
       14. STICKY PROCESS STEP TRACKER
       ========================================================================== */
    const processCards = document.querySelectorAll('.ss-process-card');
    const processIndicator = document.getElementById('ss-process-current-step');
    if (processCards.length > 0) {
      const processObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const stepNum = entry.target.getAttribute('data-step') || '01';
            if (processIndicator) {
              processIndicator.textContent = `Step ${stepNum} of 03`;
            }
          }
        });
      }, {
        threshold: 0.5,
        rootMargin: '-10% 0px -40% 0px'
      });

      processCards.forEach(card => processObserver.observe(card));
    }

    /* ==========================================================================
       15. AUTHENTICATION: Tab Glider, Password Toggles, Strength Meter & Demo Toast
       ========================================================================== */
    const authSection = document.querySelector('.ss-auth-section');
    if (authSection) {
      const tabNav = document.querySelector('.ss-auth-tab-nav');
      const tabBtns = document.querySelectorAll('.ss-auth-tab-btn');
      const loginPanel = document.getElementById('ss-auth-panel-login');
      const registerPanel = document.getElementById('ss-auth-panel-register');
      const switchLinks = document.querySelectorAll('.ss-switch-link');
      const toastEl = document.getElementById('ss-auth-toast');
      const toastTitle = document.getElementById('ss-toast-title');
      const toastDesc = document.getElementById('ss-toast-desc');
      const toastIcon = document.getElementById('ss-toast-icon');
      const toastClose = document.getElementById('ss-toast-close');
      let toastTimer = null;

      function showToast(title, desc, icon = '☀️') {
        if (!toastEl) return;
        if (toastTitle) toastTitle.textContent = title;
        if (toastDesc) toastDesc.textContent = desc;
        if (toastIcon) toastIcon.textContent = icon;
        toastEl.classList.add('is-visible');

        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
          toastEl.classList.remove('is-visible');
        }, 5000);
      }

      if (toastClose) {
        toastClose.addEventListener('click', () => {
          if (toastEl) toastEl.classList.remove('is-visible');
          if (toastTimer) clearTimeout(toastTimer);
        });
      }

      const viewport = document.getElementById('ss-auth-panel-viewport');
      const authCard = document.querySelector('.ss-auth-card');
      const showcaseSlides = document.querySelectorAll('.ss-auth-showcase-slide');
      const activePanelFor = (tab) => (tab === 'register' ? registerPanel : loginPanel);

      // Keep the viewport height locked to the active panel so the card morphs smoothly
      function syncViewportHeight(tab) {
        const panel = activePanelFor(tab);
        if (viewport && panel && panel.offsetHeight > 0) {
          viewport.style.height = panel.offsetHeight + 'px';
        }
      }

      function switchAuthTab(targetTab) {
        if (!tabNav || !loginPanel || !registerPanel) return;

        const isRegister = targetTab === 'register';
        const entering = isRegister ? registerPanel : loginPanel;
        const leaving = isRegister ? loginPanel : registerPanel;

        // Tab buttons
        tabBtns.forEach(btn => {
          const isTarget = btn.getAttribute('data-tab') === targetTab;
          btn.classList.toggle('is-active', isTarget);
          btn.setAttribute('aria-selected', isTarget ? 'true' : 'false');
        });
        tabNav.classList.toggle('is-register', isRegister);

        // Showcase slide crossfade + card ambience
        if (authCard) authCard.classList.toggle('is-register-mode', isRegister);
        showcaseSlides.forEach(slide => {
          slide.classList.toggle('is-active', slide.getAttribute('data-showcase') === targetTab);
        });

        // Directional panel transition (register enters from the right, login from the left)
        if (!entering.classList.contains('is-active')) {
          const enterX = isRegister ? 'translateX(56px)' : 'translateX(-56px)';
          const exitX = isRegister ? 'translateX(-56px)' : 'translateX(56px)';

          // Park both panels at their start positions before flipping classes
          entering.style.transition = 'none';
          entering.style.transform = enterX;
          leaving.style.transition = 'none';
          leaving.style.transform = exitX;
          void entering.offsetWidth; // force reflow so the new start position applies

          entering.style.transition = '';
          leaving.style.transition = '';

          leaving.classList.remove('is-active');
          entering.classList.add('is-active');
          entering.style.transform = '';
        }

        // Morph the card height to fit the incoming form
        if (viewport) {
          const fromHeight = leaving && leaving.offsetHeight > 0 ? leaving.offsetHeight : entering.offsetHeight;
          viewport.style.height = fromHeight + 'px';
          void viewport.offsetWidth;
          viewport.style.height = entering.offsetHeight + 'px';
        }

        window.history.replaceState(null, '', isRegister ? '/register' : '/login');
      }

      // Initial tab detection: check URL or section data attribute
      const initialTab = authSection.getAttribute('data-initial-tab') ||
        (window.location.pathname.includes('register') ? 'register' : 'login');

      // On first paint, match classes to the initial tab without animating
      {
        const startPanel = activePanelFor(initialTab);
        const startIsRegister = initialTab === 'register';
        loginPanel.classList.toggle('is-active', !startIsRegister);
        registerPanel.classList.toggle('is-active', startIsRegister);
        tabBtns.forEach(btn => {
          const isTarget = btn.getAttribute('data-tab') === initialTab;
          btn.classList.toggle('is-active', isTarget);
          btn.setAttribute('aria-selected', isTarget ? 'true' : 'false');
        });
        tabNav.classList.toggle('is-register', startIsRegister);
        if (authCard) authCard.classList.toggle('is-register-mode', startIsRegister);
        showcaseSlides.forEach(slide => {
          slide.classList.toggle('is-active', slide.getAttribute('data-showcase') === initialTab);
        });
        syncViewportHeight(initialTab);
      }
      if (initialTab === 'register') {
        window.history.replaceState(null, '', '/register');
      }

      // Re-measure the active panel when layout shifts (fonts, responsive, resize)
      window.addEventListener('resize', () => syncViewportHeight(initialTab === 'register' ? 'register' : (tabNav && tabNav.classList.contains('is-register') ? 'register' : 'login')));
      if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(() => {
          const current = tabNav && tabNav.classList.contains('is-register') ? 'register' : 'login';
          syncViewportHeight(current);
        });
      }
      window.addEventListener('load', () => {
        const current = tabNav && tabNav.classList.contains('is-register') ? 'register' : 'login';
        syncViewportHeight(current);
      });

      // When the active panel's height changes at runtime (e.g. a validation
      // checklist expanding under a field), morph the viewport to follow it.
      if (window.ResizeObserver && viewport) {
        const panelResizeObserver = new ResizeObserver((entries) => {
          const activeTab = tabNav && tabNav.classList.contains('is-register') ? 'register' : 'login';
          entries.forEach(entry => {
            const panel = entry.target;
            if (panel === activePanelFor(activeTab) && panel.offsetHeight > 0) {
              viewport.style.height = panel.offsetHeight + 'px';
            }
          });
        });
        panelResizeObserver.observe(loginPanel);
        panelResizeObserver.observe(registerPanel);
      }

      // Tab button clicks
      tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          const tab = btn.getAttribute('data-tab');
          if (tab) switchAuthTab(tab);
        });
      });

      // In-form switch links ("Don't have an account? Create one" / "Already have an account? Sign in")
      switchLinks.forEach(link => {
        link.addEventListener('click', () => {
          const target = link.getAttribute('data-switch-to');
          if (target) switchAuthTab(target);
        });
      });

      // Password visibility toggles
      const pwdToggles = document.querySelectorAll('.ss-pwd-toggle');
      pwdToggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
          const targetId = toggle.getAttribute('data-target');
          const input = document.getElementById(targetId);
          if (!input) return;

          const isPassword = input.type === 'password';
          input.type = isPassword ? 'text' : 'password';

          const eyeShow = toggle.querySelector('.ss-eye-show');
          const eyeHide = toggle.querySelector('.ss-eye-hide');

          if (eyeShow && eyeHide) {
            eyeShow.classList.toggle('d-none', isPassword);
            eyeHide.classList.toggle('d-none', !isPassword);
          }
        });
      });

      // Password Strength Meter for Registration
      const regPwdInput = document.getElementById('ss-register-password');
      const meterFill = document.getElementById('ss-pwd-meter-fill');
      const meterHint = document.getElementById('ss-pwd-meter-hint');

      const METER_STEPS = [
        { width: '0%',   color: 'transparent', label: 'Password strength: Empty' },
        { width: '20%',  color: '#FF6B3D',     label: 'Password strength: Weak' },
        { width: '40%',  color: '#FFB020',     label: 'Password strength: Fair' },
        { width: '60%',  color: '#0F5C6B',     label: 'Password strength: Good' },
        { width: '80%',  color: '#0E8F87',     label: 'Password strength: Strong' },
        { width: '100%', color: '#10B981',     label: 'Password strength: Excellent & Secure! ✨' },
      ];

      function updateStrengthMeter(val) {
        if (!meterFill || !meterHint) return;
        const score =
          (val.length >= 8 ? 1 : 0) +
          (/[A-Z]/.test(val) ? 1 : 0) +
          (/[a-z]/.test(val) ? 1 : 0) +
          (/[0-9]/.test(val) ? 1 : 0) +
          (/[^A-Za-z0-9\s]/.test(val) ? 1 : 0);
        const step = METER_STEPS[score];
        meterFill.style.width = step.width;
        meterFill.style.backgroundColor = step.color;
        meterHint.textContent = step.label;
        meterHint.style.color = score === 0 ? 'var(--ss-ink-subtle)' : step.color;
      }

      if (regPwdInput) {
        regPwdInput.addEventListener('input', () => updateStrengthMeter(regPwdInput.value));
      }

      // Live Field Validation ("entity control") — checklists under email / password / confirm
      const FIELD_RULES = {
        'email-at':     (v) => /^[^@\s]+@[^@\s]+$/.test(v),
        'email-domain': (v) => /^[^@\s]+@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*\.[A-Za-z]{2,}$/.test(v),
        'pw-length':    (v) => v.length >= 8,
        'pw-upper':     (v) => /[A-Z]/.test(v),
        'pw-lower':     (v) => /[a-z]/.test(v),
        'pw-number':    (v) => /[0-9]/.test(v),
        'pw-symbol':    (v) => /[^A-Za-z0-9\s]/.test(v),
      };

      // Shared API so submit handlers can force fail/red state or query results
      const checkAPI = new Map();

      document.querySelectorAll('.ss-field-checks').forEach(container => {
        const input = document.getElementById(container.getAttribute('data-bound-to'));
        if (!input) return;

        const checks = Array.from(container.querySelectorAll('.ss-field-check'));
        const wrap = input.closest('.ss-input-wrap');
        const matchCheck = checks.find(c => c.getAttribute('data-rule') === 'pw-match');
        const state = { attempted: false, errored: false };
        let partnerInput = null;
        let lastAllPass = false;
        let lastFilled = false;

        if (matchCheck) {
          partnerInput = document.getElementById(matchCheck.getAttribute('data-match-target'));
        }

        function evaluate() {
          const val = input.value;
          lastFilled = val.length > 0;
          let allPass = true;

          checks.forEach(check => {
            const ruleName = check.getAttribute('data-rule');
            let ok = false;

            if (ruleName === 'pw-match') {
              ok = lastFilled && !!partnerInput && partnerInput.value === val;
            } else if (FIELD_RULES[ruleName]) {
              ok = lastFilled && FIELD_RULES[ruleName](val);
            }

            check.classList.toggle('is-pass', ok);
            if (!ok) allPass = false;
          });
          lastAllPass = allPass;

          container.classList.toggle('all-pass', lastFilled && allPass);

          if (wrap) {
            const showBad = lastFilled ? (!allPass && state.attempted) : state.errored || state.attempted;
            wrap.classList.toggle('is-valid', lastFilled && allPass);
            wrap.classList.toggle('is-invalid', showBad);

            if (lastFilled && !allPass && !state.attempted) {
              // neutral gray while typing (before any submit attempt)
            }
          }
        }

        input.addEventListener('focus', () => container.classList.add('is-open'));
        input.addEventListener('input', () => {
          container.classList.add('is-open');
          // once the user fixes a previously failed field, drop the red state
          if (state.errored && input.value) state.errored = false;
          evaluate();
        });
        input.addEventListener('blur', () => {
          if (!input.value && document.activeElement !== input) {
            // left the field empty -> show red ✗ requirement list
            state.errored = true;
            container.classList.add('is-open', 'show-fail');
            evaluate();
          }
        });

        // Keep the match check live when the source password changes too
        if (partnerInput) {
          partnerInput.addEventListener('input', evaluate);
        }

        evaluate();
        if (input.value) container.classList.add('is-open'); // pre-filled (e.g. after a validation error)

        checkAPI.set(input.id, {
          fail() {
            state.attempted = true;
            container.classList.add('is-open', 'show-fail');
            evaluate();
          },
          reset() {
            state.attempted = false;
            state.errored = false;
            container.classList.remove('show-fail');
            evaluate();
          },
          allPass() { return lastFilled && lastAllPass; },
        });
      });

      // "Forgot Password" link allows natural navigation to password.request

      // Real form submissions: validate first (styled ✗ errors, no native tooltips), then loading state + POST
      const ERROR_SVG =
        '<svg viewBox="0 0 12 10" fill="none" aria-hidden="true"><path class="ss-cross" d="M2.5 2.5 L9.5 8.5 M9.5 2.5 L2.5 8.5"></path></svg>';

      function showFieldError(group, message) {
        let err = group.nextElementSibling;
        if (!err || !err.classList.contains('ss-field-error')) {
          err = document.createElement('div');
          err.className = 'ss-field-error';
          err.innerHTML = '<span class="ss-check-bullet">' + ERROR_SVG + '</span><span class="ss-error-text"></span>';
          group.insertAdjacentElement('afterend', err);
        }
        err.querySelector('.ss-error-text').textContent = message;
      }

      function clearFieldError(wrap) {
        const err = wrap.nextElementSibling;
        if (err && err.classList.contains('ss-field-error')) err.remove();
      }

      function shakeWrap(wrap) {
        wrap.classList.remove('ss-shake');
        void wrap.offsetWidth; // restart the animation
        wrap.classList.add('ss-shake');
        setTimeout(() => wrap.classList.remove('ss-shake'), 600);
      }

      // Styled "required" messages for empty fields (replaces native browser tooltips)
      const REQUIRED_MSGS = {
        'ss-login-email':       'Email address is required — please fill it in',
        'ss-login-password':    'Please enter your password',
        'ss-register-email':    'Email address is required — please fill it in',
        'ss-register-password': 'Password is required — please fill it in',
        'ss-register-confirm':  'Please confirm your password',
      };

      // Clear injected errors as soon as the user starts fixing the field
      Object.keys(REQUIRED_MSGS).forEach(id => {
        const input = document.getElementById(id);
        if (!input) return;
        input.addEventListener('input', () => {
          if (input.value.trim()) {
            const wrap = input.closest('.ss-input-wrap');
            clearFieldError(wrap);
            wrap.classList.remove('is-invalid');
          }
        });
      });

      // Validate one field: empty -> red ✗ + styled required message; filled -> its checklist must pass
      function gateField(id, invalid) {
        const input = document.getElementById(id);
        if (!input) return;
        const api = checkAPI.get(id);
        const wrap = input.closest('.ss-input-wrap');

        if (!input.value.trim()) {
          if (api) api.fail(); // flips the checklist bullets to red ✗
          wrap.classList.add('is-invalid');
          showFieldError(wrap, REQUIRED_MSGS[id] || 'This field is required — please fill it in');
          shakeWrap(wrap);
          invalid.push(input);
          return;
        }

        if (api && !api.allPass()) {
          api.fail();
          shakeWrap(wrap);
          invalid.push(input);
        }
      }

      const loginBtn = document.getElementById('ss-btn-login-submit');
      const loginForm = document.getElementById('ss-login-form');
      if (loginForm && loginBtn) {
        loginForm.addEventListener('submit', (e) => {
          const invalid = [];
          gateField('ss-login-email', invalid);
          gateField('ss-login-password', invalid);

          if (invalid.length) {
            e.preventDefault();
            invalid[0].focus();
            invalid[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
          }

          const spinner = loginBtn.querySelector('.ss-btn-spinner');
          const arrow = loginBtn.querySelector('.ss-btn-arrow');
          const btnText = loginBtn.querySelector('.ss-btn-text');

          if (spinner) spinner.classList.remove('d-none');
          if (arrow) arrow.classList.add('d-none');
          if (btnText) btnText.textContent = 'Signing in...';
          loginBtn.disabled = true;
        });
      }

      const registerBtn = document.getElementById('ss-btn-register-submit');
      const registerForm = document.getElementById('ss-register-form');
      if (registerForm && registerBtn) {
        const nameInput = document.getElementById('ss-register-name');
        const nameWrap = nameInput ? nameInput.closest('.ss-input-wrap') : null;
        const termsInput = document.getElementById('ss-terms-agree');
        const termsGroup = termsInput ? termsInput.closest('.ss-form-options') : null;

        if (nameInput && nameWrap) {
          nameInput.addEventListener('input', () => {
            clearFieldError(nameWrap);
            nameWrap.classList.remove('is-invalid');
          });
        }
        if (termsInput && termsGroup) {
          termsInput.addEventListener('change', () => {
            if (termsInput.checked) clearFieldError(termsGroup);
          });
        }

        registerForm.addEventListener('submit', (e) => {
          const invalid = [];

          if (nameInput && nameWrap && !nameInput.value.trim()) {
            nameWrap.classList.add('is-invalid');
            showFieldError(nameWrap, 'Full name is required — please fill it in');
            shakeWrap(nameWrap);
            invalid.push(nameInput);
          }

          // Empty -> styled ✗ "required" message + red checklist; filled -> checklist must pass
          ['ss-register-email', 'ss-register-password', 'ss-register-confirm'].forEach(id => {
            gateField(id, invalid);
          });

          if (termsInput && termsGroup && !termsInput.checked) {
            showFieldError(termsGroup, 'Please agree to the Terms & Community Safety Guarantee to continue');
            invalid.push(termsInput);
          }

          // Google reCAPTCHA v2 validation
          const recaptchaWrap = registerForm.querySelector('.ss-recaptcha-wrap');
          const recaptchaErr = document.getElementById('ss-recaptcha-error');
          if (typeof grecaptcha !== 'undefined') {
            const recaptchaVal = grecaptcha.getResponse();
            if (!recaptchaVal || recaptchaVal.trim().length === 0) {
              if (recaptchaErr) recaptchaErr.classList.remove('d-none');
              if (recaptchaWrap) shakeWrap(recaptchaWrap);
              invalid.push(recaptchaErr || registerForm);
            } else {
              if (recaptchaErr) recaptchaErr.classList.add('d-none');
            }
          }

          if (invalid.length) {
            e.preventDefault();
            const first = invalid[0];
            if (first && typeof first.focus === 'function') {
              first.focus();
            }
            if (first && typeof first.scrollIntoView === 'function') {
              first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
          }

          const spinner = registerBtn.querySelector('.ss-btn-spinner');
          const spark = registerBtn.querySelector('.ss-btn-sun-spark');
          const btnText = registerBtn.querySelector('.ss-btn-text');

          if (spinner) spinner.classList.remove('d-none');
          if (spark) spark.classList.add('d-none');
          if (btnText) btnText.textContent = 'Creating account...';
          registerBtn.disabled = true;
        });
      }

      // Social buttons are real OAuth links now (see AuthController::redirectToProvider)
    }

    /* ==========================================================================
       16. USER MENU DROPDOWN (Navbar)
       ========================================================================== */
    const userMenuBtn = document.getElementById('ss-user-menu-btn');
    const userDropdown = document.getElementById('ss-user-dropdown');
    if (userMenuBtn && userDropdown) {
      userMenuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isOpen = userDropdown.style.display === 'block';
        userDropdown.style.display = isOpen ? 'none' : 'block';
        userMenuBtn.setAttribute('aria-expanded', String(!isOpen));
      });

      document.addEventListener('click', (e) => {
        if (!userDropdown.contains(e.target) && e.target !== userMenuBtn) {
          userDropdown.style.display = 'none';
          userMenuBtn.setAttribute('aria-expanded', 'false');
        }
      });
    }

    // Initial trigger
    updateNarratorSunAndSky();
    updateNavbarOnScroll();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', start);
  } else {
    start();
  }
})();
