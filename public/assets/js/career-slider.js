(() => {
  const track = document.getElementById('careerSliderTrack');
  if (!track) return;
  const slider = track.parentElement;
  const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');
  let timer;
  let hovering = false;
  let touching = false;
  let mouseDown = false;
  let dragged = false;
  let dragStartX = 0;
  let dragStartScroll = 0;
  const stop = () => clearInterval(timer);
  const move = (direction, wrap = false) => {
    const first = track.firstElementChild;
    if (!first) return;
    const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
    const end = track.scrollWidth - track.clientWidth;
    const destination = wrap && track.scrollLeft >= end - 2
      ? 0 : track.scrollLeft + direction * (first.getBoundingClientRect().width + gap);
    track.scrollTo({ left: destination, behavior: reducedMotion.matches ? 'instant' : 'smooth' });
  };
  const start = () => {
    stop();
    if (reducedMotion.matches || document.hidden || hovering || touching || mouseDown ||
        slider.contains(document.activeElement) || track.scrollWidth <= track.clientWidth + 2) return;
    timer = setInterval(() => move(1, true), 4500);
  };
  slider.addEventListener('mouseenter', () => { hovering = true; stop(); });
  slider.addEventListener('mouseleave', () => { hovering = false; start(); });
  slider.addEventListener('focusin', stop);
  slider.addEventListener('focusout', () => setTimeout(start, 0));
  track.addEventListener('touchstart', () => { touching = true; stop(); }, { passive: true });
  const endTouch = () => { touching = false; start(); };
  track.addEventListener('touchend', endTouch, { passive: true });
  track.addEventListener('touchcancel', endTouch, { passive: true });
  track.addEventListener('mousedown', (event) => {
    if (event.button !== 0 || track.scrollWidth <= track.clientWidth + 2) return;
    mouseDown = true;
    dragged = false;
    dragStartX = event.clientX;
    dragStartScroll = track.scrollLeft;
    stop();
  });
  track.addEventListener('dragstart', (event) => event.preventDefault());
  window.addEventListener('mousemove', (event) => {
    if (!mouseDown) return;
    const distance = event.clientX - dragStartX;
    if (!dragged && Math.abs(distance) < 6) return;
    dragged = true;
    track.classList.add('is-dragging');
    event.preventDefault();
    track.scrollLeft = dragStartScroll - distance;
  });
  const endDrag = () => {
    if (!mouseDown) return;
    mouseDown = false;
    track.classList.remove('is-dragging');
    if (dragged && track.firstElementChild) {
      const step = track.firstElementChild.getBoundingClientRect().width +
        (parseFloat(getComputedStyle(track).columnGap) || 0);
      track.scrollTo({
        left: Math.round(track.scrollLeft / step) * step,
        behavior: reducedMotion.matches ? 'instant' : 'smooth'
      });
    }
    setTimeout(() => { dragged = false; }, 0);
    start();
  };
  window.addEventListener('mouseup', endDrag);
  window.addEventListener('blur', endDrag);
  // A drag over an application link must not open that link on release.
  track.addEventListener('click', (event) => {
    if (!dragged) return;
    event.preventDefault();
    event.stopPropagation();
  }, true);
  track.addEventListener('keydown', (event) => {
    if (event.target !== track || !['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
    event.preventDefault();
    move(event.key === 'ArrowRight' ? 1 : -1);
  });
  document.addEventListener('visibilitychange', start);
  window.addEventListener('resize', start);
  reducedMotion.addEventListener('change', start);
  start();
})();
