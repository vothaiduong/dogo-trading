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
