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

  /* ── 5. SMOOTH ANCHOR SCROLL ── */
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const href = a.getAttribute('href');
      if (href === '#') return;
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        const navH = document.querySelector('.pmc-nav')?.offsetHeight || 0;
        const top = target.getBoundingClientRect().top + window.scrollY - navH - 16;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });

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
  const prog   = slider.querySelector('.slide-progress');
  const prevBtn = slider.querySelector('.slider-prev');
  const nextBtn = slider.querySelector('.slider-next');

  if (!slides.length) return;

  let current = 0;
  let autoTimer = null;
  const INTERVAL = 6000;

  function goTo(n) {
    slides[current].classList.remove('active');
    if (dots[current]) dots[current].classList.remove('active');
    current = ((n % slides.length) + slides.length) % slides.length;
    slides[current].classList.add('active');
    if (dots[current]) dots[current].classList.add('active');
    // restart progress bar
    if (prog) {
      prog.style.animation = 'none';
      void prog.offsetWidth; // reflow
      prog.style.animation = `slideProgress ${INTERVAL}ms linear forwards`;
    }
  }

  function startAuto() {
    stopAuto();
    autoTimer = setInterval(() => goTo(current + 1), INTERVAL);
  }
  function stopAuto() {
    if (autoTimer) clearInterval(autoTimer);
  }

  // Init first slide
  goTo(0);
  startAuto();

  // Controls
  if (nextBtn) nextBtn.addEventListener('click', () => { goTo(current + 1); startAuto(); });
  if (prevBtn) prevBtn.addEventListener('click', () => { goTo(current - 1); startAuto(); });

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => { goTo(i); startAuto(); });
  });

  // Touch/swipe support
  let touchStartX = 0;
  slider.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
  slider.addEventListener('touchend', e => {
    const diff = touchStartX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) { diff > 0 ? goTo(current + 1) : goTo(current - 1); startAuto(); }
  });

  // Pause on hover
  slider.addEventListener('mouseenter', stopAuto);
  slider.addEventListener('mouseleave', startAuto);
}
