/* ═══════════════════════════════════════════════════
   PMC GLOBAL JAVASCRIPT
   - Navbar scroll state
   - Mega menu hover/touch
   - Fade-up scroll observer
   - Counter animation
   - Smooth anchor scroll
   - Back to top
═══════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', () => {

  /* ── 1. NAVBAR SCROLL ── */
  const nav = document.querySelector('.pmc-nav');
  if (nav) {
    const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 40);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ── 2. MEGA MENU — keyboard / touch support ── */
  document.querySelectorAll('.mega-menu-wrapper').forEach(wrapper => {
    const menu = wrapper.querySelector('.mega-menu, .dropdown-menu');
    if (!menu) return;

    let leaveTimer;

    wrapper.addEventListener('mouseenter', () => {
      clearTimeout(leaveTimer);
      menu.style.display = 'block';
    });
    wrapper.addEventListener('mouseleave', () => {
      leaveTimer = setTimeout(() => { menu.style.display = ''; }, 120);
    });

    // Touch toggle
    const trigger = wrapper.querySelector('.nav-link');
    if (trigger) {
      trigger.addEventListener('click', e => {
        if (window.innerWidth < 992) return; // let Bootstrap handle mobile
        e.preventDefault();
        const visible = menu.style.display === 'block';
        // close all others
        document.querySelectorAll('.mega-menu, .dropdown-menu.plain-dd').forEach(m => m.style.display = '');
        menu.style.display = visible ? '' : 'block';
      });
    }
  });

  // Click outside closes menus
  document.addEventListener('click', e => {
    if (!e.target.closest('.mega-menu-wrapper')) {
      document.querySelectorAll('.mega-menu, .dropdown-menu.plain-dd').forEach(m => m.style.display = '');
    }
  });

  /* ── 3. FADE-UP OBSERVER ── */
  const fuEls = document.querySelectorAll('.fu');
  if (fuEls.length) {
    const fuObs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('vis'); fuObs.unobserve(e.target); }
      });
    }, { threshold: 0.1 });
    fuEls.forEach(el => fuObs.observe(el));
  }

  /* ── 4. COUNTER ANIMATION ── */
  const counters = document.querySelectorAll('[data-count]');
  if (counters.length) {
    const cObs = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        const target = parseInt(el.dataset.count, 10);
        const suffix = el.dataset.suffix || '';
        const duration = 1600;
        const step = target / (duration / 16);
        let current = 0;
        const timer = setInterval(() => {
          current += step;
          if (current >= target) { current = target; clearInterval(timer); }
          el.textContent = Math.floor(current) + suffix;
        }, 16);
        cObs.unobserve(el);
      });
    }, { threshold: 0.5 });
    counters.forEach(c => cObs.observe(c));
  }

  /* ── 5. SMOOTH ANCHOR SCROLL (hash + page.php#id menu links) ── */
  function navOffset() {
    const navEl = document.querySelector('.pmc-nav');
    return (navEl ? navEl.offsetHeight : 0) + 16;
  }

  function scrollToHash(hash, smooth = true) {
    if (!hash || hash === '#') return false;
    let target = null;
    try { target = document.querySelector(hash); } catch (_) { return false; }
    if (!target) return false;
    const top = target.getBoundingClientRect().top + window.scrollY - navOffset();
    window.scrollTo({ top: Math.max(0, top), behavior: smooth ? 'smooth' : 'auto' });
    return true;
  }

  function closeMenus() {
    document.querySelectorAll('.mega-menu, .dropdown-menu.plain-dd').forEach(m => { m.style.display = ''; });
    const collapse = document.getElementById('navMain');
    if (collapse && collapse.classList.contains('show') && window.bootstrap?.Collapse) {
      bootstrap.Collapse.getOrCreateInstance(collapse).hide();
    }
  }

  document.querySelectorAll('a[href*="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const href = a.getAttribute('href');
      if (!href || href === '#') return;

      const url = new URL(href, window.location.href);
      const hash = url.hash;
      if (!hash || hash === '#') return;

      // Same-page hash (e.g. #eligibility or admissions.php#eligibility)
      const samePage =
        url.pathname.replace(/\/+$/, '') === window.location.pathname.replace(/\/+$/, '') ||
        url.pathname.endsWith(window.location.pathname.split('/').pop());

      if (!samePage) return; // let browser navigate to other page + hash

      const target = document.querySelector(hash);
      if (!target) return;

      e.preventDefault();
      closeMenus();
      history.pushState(null, '', hash);
      scrollToHash(hash, true);
    });
  });

  // Landing on page with hash (from Admissions mega menu)
  if (window.location.hash) {
    const hash = window.location.hash;
    // Defer until layout/sticky nav height is ready
    requestAnimationFrame(() => {
      setTimeout(() => scrollToHash(hash, false), 50);
    });
  }

  /* ── 6. BACK TO TOP ── */
  const btt = document.getElementById('backToTop');
  if (btt) {
    window.addEventListener('scroll', () => {
      btt.classList.toggle('visible', window.scrollY > 500);
    }, { passive: true });
    btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  /* ── 7. HERO SLIDER ── */
  initSlider();

});

/* ══ SLIDER ENGINE ══ */
function initSlider() {
  const slider = document.getElementById('heroSlider');
  if (!slider) return;

  const slides = slider.querySelectorAll('.hero-slide');
  const dots   = slider.querySelectorAll('.slider-dot');
  const prevBtn = slider.querySelector('.slider-prev');
  const nextBtn = slider.querySelector('.slider-next');

  if (!slides.length) return;

  let current = 0;
  let autoTimer = null;
  const INTERVAL = 6000;

  const lazyBgs = slider.querySelectorAll('.hero-slide[data-lazy-bg]');
  let lazyBgLoaded = false;
  function loadLazyBgs() {
    if (lazyBgLoaded) return;
    lazyBgLoaded = true;
    lazyBgs.forEach(slide => {
      const media = slide.querySelector('.slide-media') || slide;
      media.classList.add('bg-ready');
      slide.removeAttribute('data-lazy-bg');
    });
  }
  if (lazyBgs.length) {
    if ('requestIdleCallback' in window) {
      requestIdleCallback(loadLazyBgs, { timeout: 2500 });
    } else {
      setTimeout(loadLazyBgs, 1200);
    }
  }

  function restartDotProgress(activeDot) {
    if (!activeDot) return;
    const bar = activeDot.querySelector('.slider-dot-bar');
    if (!bar) return;
    bar.style.animation = 'none';
    void bar.offsetWidth;
    bar.style.animation = `slideProgress ${INTERVAL}ms linear forwards`;
  }

  function goTo(n) {
    if (n !== current) loadLazyBgs();
    slides[current].classList.remove('active');
    if (dots[current]) {
      dots[current].classList.remove('active');
      dots[current].setAttribute('aria-selected', 'false');
      const prevBar = dots[current].querySelector('.slider-dot-bar');
      if (prevBar) {
        prevBar.style.animation = 'none';
        prevBar.style.width = '0';
      }
    }
    current = ((n % slides.length) + slides.length) % slides.length;
    void slides[current].offsetWidth;
    slides[current].classList.add('active');
    if (dots[current]) {
      dots[current].classList.add('active');
      dots[current].setAttribute('aria-selected', 'true');
      restartDotProgress(dots[current]);
    }
  }

  function startAuto() {
    stopAuto();
    autoTimer = setInterval(() => goTo(current + 1), INTERVAL);
  }
  function stopAuto() {
    if (autoTimer) clearInterval(autoTimer);
  }

  goTo(0);
  startAuto();

  if (nextBtn) nextBtn.addEventListener('click', () => { goTo(current + 1); startAuto(); });
  if (prevBtn) prevBtn.addEventListener('click', () => { goTo(current - 1); startAuto(); });

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => { goTo(i); startAuto(); });
  });

  let touchStartX = 0;
  slider.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
  slider.addEventListener('touchend', e => {
    const diff = touchStartX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) { diff > 0 ? goTo(current + 1) : goTo(current - 1); startAuto(); }
  });

  slider.addEventListener('mouseenter', stopAuto);
  slider.addEventListener('mouseleave', startAuto);
}
