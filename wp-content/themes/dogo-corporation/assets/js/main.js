/* Dogo Corporation — front-end interactions */
(function () {
  'use strict';

  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

  /* ---- Theme toggle (light/dark) ---- */
  const themeBtn = $('.theme-toggle');
  const html = document.documentElement;
  const META_THEME = $('meta[name="theme-color"]');
  const bgFor = { dark: '#0f0e0b', light: '#f2f1ed' };

  const applyMetaTheme = (mode) => {
    if (META_THEME) META_THEME.setAttribute('content', bgFor[mode] || bgFor.dark);
  };
  applyMetaTheme(html.getAttribute('data-theme') || 'dark');

  if (themeBtn) {
    themeBtn.addEventListener('click', () => {
      const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      html.setAttribute('data-theme', next);
      applyMetaTheme(next);
      try { localStorage.setItem('dogo-theme', next); } catch (e) {}
    });
  }

  /* ---- Mobile menu toggle ---- */
  const toggle = $('.site-header__toggle');
  const nav = $('.site-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(open));
    });

    $$('.site-nav__link, .site-nav .menu-item > a').forEach((a) => {
      a.addEventListener('click', () => {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  /* ---- Sticky header shadow on scroll ---- */
  const header = $('.site-header');
  if (header) {
    const onScroll = () => {
      header.classList.toggle('is-scrolled', window.scrollY > 8);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ---- Back-to-top ---- */
  const backTop = $('.back-to-top');
  if (backTop) {
    const onScroll = () => {
      backTop.classList.toggle('is-visible', window.scrollY > 480);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
    backTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ---- Reveal-on-scroll for sections / cards ---- */
  if ('IntersectionObserver' in window) {
    const targets = $$('.service, .pillar, .stats__item, .hero__card, .about__media, .cta');
    targets.forEach((el) => el.classList.add('reveal'));
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {
          if (e.isIntersecting) {
            e.target.classList.add('is-revealed');
            io.unobserve(e.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );
    targets.forEach((el) => io.observe(el));
  }

  /* ---- Lazy-load heavy embeds when they near the viewport ---- */
  // 1. Cloudflare Turnstile — defer the ~80 KB script + its fingerprinting
  //    until the contact form is within ~600px of the user.
  const captchaSlot = $('.form-captcha');
  if (captchaSlot && captchaSlot.dataset.sitekey && 'IntersectionObserver' in window) {
    const ts = new IntersectionObserver((entries) => {
      if (entries.some((e) => e.isIntersecting)) {
        ts.disconnect();
        const s = document.createElement('script');
        s.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js';
        s.async = true;
        s.defer = true;
        document.head.appendChild(s);
      }
    }, { rootMargin: '600px 0px' });
    ts.observe(captchaSlot);
  }

  // 2. Google Maps iframe — render only when the map is near the viewport.
  //    Until then we show a static placeholder card.
  const mapHolder = $('.contact__map[data-map-src]');
  if (mapHolder && 'IntersectionObserver' in window) {
    const mio = new IntersectionObserver((entries) => {
      if (entries.some((e) => e.isIntersecting)) {
        mio.disconnect();
        const iframe = document.createElement('iframe');
        iframe.src = mapHolder.dataset.mapSrc;
        iframe.width = '100%';
        iframe.height = mapHolder.dataset.mapHeight || '420';
        iframe.loading = 'lazy';
        iframe.referrerPolicy = 'no-referrer-when-downgrade';
        iframe.title = mapHolder.dataset.mapTitle || 'Map';
        iframe.style.border = '0';
        const placeholder = mapHolder.querySelector('.contact__map-skeleton');
        if (placeholder) placeholder.replaceWith(iframe);
        else mapHolder.prepend(iframe);
      }
    }, { rootMargin: '400px 0px' });
    mio.observe(mapHolder);
  }

  /* ---- Contact form (AJAX with status inline + Turnstile) ---- */
  $$('form[data-dogo-contact]').forEach((form) => {
    const status  = form.querySelector('.form-status');
    const btn     = form.querySelector('button[type="submit"]');
    const label   = btn?.querySelector('.btn__label');
    const captcha = form.querySelector('.form-captcha');
    const i18n    = (window.DogoContact && DogoContact.i18n) || {};

    // Render Turnstile when its API arrives, react to theme changes
    let captchaWidgetId = null;
    const renderCaptcha = () => {
      if (!captcha || !captcha.dataset.sitekey || !window.turnstile) return;
      if (captchaWidgetId !== null) return; // already rendered
      const isDark = html.getAttribute('data-theme') === 'dark';
      captchaWidgetId = window.turnstile.render(captcha, {
        sitekey: captcha.dataset.sitekey,
        action:  captcha.dataset.action || 'contact',
        theme:   isDark ? 'dark' : 'light',
        size:    'flexible',
      });
    };
    // Turnstile API loads async/defer. Poll until ready (cheap; resolves fast).
    const t0 = Date.now();
    const wait = setInterval(() => {
      if (window.turnstile) { clearInterval(wait); renderCaptcha(); }
      else if (Date.now() - t0 > 8000) clearInterval(wait); // give up
    }, 80);

    // Re-render captcha when user toggles theme so it matches
    if (themeBtn) {
      themeBtn.addEventListener('click', () => {
        if (window.turnstile && captchaWidgetId !== null) {
          window.turnstile.remove(captchaWidgetId);
          captchaWidgetId = null;
          // re-render after data-theme attr is updated by handler above
          setTimeout(renderCaptcha, 0);
        }
      });
    }

    form.addEventListener('submit', async (e) => {
      // Only intercept if our config + fetch are available
      if (!window.DogoContact || !('fetch' in window)) return;
      e.preventDefault();

      // Reset status
      if (status) {
        status.hidden = false;
        status.textContent = '';
        status.classList.remove('form-status--ok', 'form-status--err');
      }

      // Toggle button into "sending" state
      if (btn && label) {
        btn.disabled = true;
        btn.dataset.originalLabel = label.textContent;
        label.textContent = i18n.sending || 'Sending…';
      }

      const fd = new FormData(form);
      // Replace the admin-post action target with admin-ajax (same payload works)
      try {
        const res = await fetch(DogoContact.ajaxUrl + '?action=dogo_contact', {
          method: 'POST',
          body: fd,
          credentials: 'same-origin',
          headers: { 'Accept': 'application/json' },
        });
        const data = await res.json().catch(() => ({ ok: false, message: 'Network error' }));
        const ok = res.ok && data.ok === true;

        if (status) {
          status.classList.add(ok ? 'form-status--ok' : 'form-status--err');
          status.textContent = data.message || (ok ? 'Sent.' : 'Error.');
        }
        if (ok) {
          form.reset();
          // Reset captcha so a new token is required for the next message
          if (window.turnstile && captchaWidgetId !== null) {
            window.turnstile.reset(captchaWidgetId);
          }
        }
      } catch (err) {
        if (status) {
          status.classList.add('form-status--err');
          status.textContent = 'Network error — please try again.';
        }
      } finally {
        if (btn && label) {
          btn.disabled = false;
          label.textContent = btn.dataset.originalLabel || (i18n.submit || 'Send message');
        }
      }
    });
  });

  /* ---- Subtle parallax on hero floating card ---- */
  const floatCard = $('.hero__card--float');
  if (floatCard && window.matchMedia('(min-width: 1024px)').matches) {
    const heroVisual = $('.hero__visual');
    heroVisual?.addEventListener('mousemove', (e) => {
      const rect = heroVisual.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;
      floatCard.style.transform = `translate(${x * 12}px, ${y * 8}px)`;
    });
    heroVisual?.addEventListener('mouseleave', () => {
      floatCard.style.transform = '';
    });
  }
})();
