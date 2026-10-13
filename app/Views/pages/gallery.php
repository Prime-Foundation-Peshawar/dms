<?php
/** Auto-ported content body from legacy gallery.php */
?>
<style>
    /* ══ FILTER TABS ══ */
    .gallery-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 40px;
      justify-content: center;
    }
    .gf-btn {
      font-family: var(--font-head);
      font-size: .78rem;
      font-weight: 700;
      padding: 9px 22px;
      border-radius: 100px;
      border: 2px solid var(--border);
      background: white;
      color: var(--gray-dark);
      cursor: pointer;
      transition: all .2s;
      letter-spacing: .02em;
    }
    .gf-btn:hover  { border-color: var(--teal); color: var(--teal); }
    .gf-btn.active { background: var(--teal); border-color: var(--teal); color: white; }

    /* ══ GALLERY GRID (justified rows, Google Images style) ══ */
    .gallery-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-content: flex-start;
    }
    .gallery-item {
      position: relative;
      flex: 0 0 auto;
      box-sizing: border-box;
      height: 200px;
      width: 200px;
      max-width: 100%;
      border-radius: var(--r-md);
      overflow: hidden;
      cursor: pointer;
      background: var(--gray-light);
      transition: transform .3s, box-shadow .3s;
    }
    .gallery-item:hover { transform: scale(1.015); box-shadow: var(--shadow-lg); z-index: 1; }

    .gallery-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform .4s;
    }
    .gallery-item:hover .gallery-img { transform: scale(1.04); }

    /* Placeholder (when no image) */
    .gallery-placeholder {
      width: 100%; height: 100%;
      background: linear-gradient(135deg, var(--navy), var(--navy-mid));
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      gap: 10px;
      transition: filter .3s;
    }
    .gallery-placeholder i    { font-size: 2rem; color: rgba(255,255,255,.5); }
    .gallery-placeholder span { font-family: var(--font-head); font-size: .75rem; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .08em; }

    /* Overlay */
    .gallery-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(to top, rgba(10,22,40,.85) 0%, transparent 55%);
      opacity: 0;
      transition: opacity .25s;
      display: flex; align-items: flex-end;
      padding: 18px;
    }
    .gallery-item:hover .gallery-overlay { opacity: 1; }
    .gallery-overlay-text {
      font-family: var(--font-head);
      font-size: .82rem;
      font-weight: 700;
      color: white;
      line-height: 1.3;
    }
    .gallery-overlay-cat {
      font-family: var(--font-body);
      font-size: .68rem;
      font-weight: 700;
      color: var(--teal-light);
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: 3px;
    }
    /* Zoom icon badge */
    .gallery-zoom {
      position: absolute;
      top: 12px; right: 12px;
      width: 32px; height: 32px;
      background: rgba(255,255,255,.15);
      backdrop-filter: blur(8px);
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      color: white; font-size: .9rem;
      opacity: 0; transition: opacity .25s;
    }
    .gallery-item:hover .gallery-zoom { opacity: 1; }

    /* ══ LIGHTBOX ══ */
    .pmc-lightbox {
      position: fixed; inset: 0;
      background: rgba(6,14,26,.96);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
    }
    .pmc-lightbox.open { display: flex; animation: lbFade .25s ease; }
    @keyframes lbFade { from{opacity:0} to{opacity:1} }

    .lb-inner {
      position: relative;
      max-width: 90vw;
      max-height: 90vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .lb-img-wrap {
      position: relative;
      border-radius: var(--r-md);
      overflow: hidden;
      box-shadow: 0 24px 80px rgba(0,0,0,.6);
    }
    .lb-img {
      max-width: 88vw;
      max-height: 76vh;
      object-fit: contain;
      display: block;
    }
    /* Placeholder in lightbox */
    .lb-placeholder {
      width: 600px; max-width: 88vw;
      height: 400px; max-height: 76vh;
      background: linear-gradient(135deg, var(--navy), var(--navy-mid));
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      gap: 14px;
    }
    .lb-placeholder i    { font-size: 3rem; color: rgba(255,255,255,.4); }
    .lb-placeholder span { font-family:var(--font-head); font-size:.8rem; color:rgba(255,255,255,.35); }

    .lb-caption {
      margin-top: 16px;
      text-align: center;
    }
    .lb-caption-title {
      font-family: var(--font-head);
      font-size: .95rem;
      font-weight: 700;
      color: white;
      margin-bottom: 3px;
    }
    .lb-caption-cat {
      font-family: var(--font-body);
      font-size: .75rem;
      color: var(--teal-light);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .07em;
    }
    .lb-counter {
      font-family: var(--font-body);
      font-size: .75rem;
      color: rgba(255,255,255,.35);
      margin-top: 6px;
    }

    /* Controls */
    .lb-close {
      position: absolute;
      top: -48px; right: 0;
      width: 40px; height: 40px;
      background: rgba(255,255,255,.1);
      border: none; border-radius: 8px;
      color: white; font-size: 1.1rem;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      transition: background .2s;
    }
    .lb-close:hover { background: rgba(255,255,255,.2); }

    .lb-prev, .lb-next {
      position: fixed;
      top: 50%; transform: translateY(-50%);
      width: 48px; height: 48px;
      background: rgba(255,255,255,.1);
      border: 1px solid rgba(255,255,255,.15);
      border-radius: 50%;
      color: white; font-size: 1.2rem;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      transition: background .2s;
    }
    .lb-prev { left: 20px; }
    .lb-next { right: 20px; }
    .lb-prev:hover, .lb-next:hover { background: rgba(194,65,12,.5); }

    /* ══ ALBUM SECTION HEADING ══ */
    .album-heading {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 22px;
      padding-bottom: 14px;
      border-bottom: 2px solid var(--border);
    }
    .album-heading-icon {
      width: 44px; height: 44px;
      background: var(--navy);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      color: var(--gold); font-size: 1.2rem;
      flex-shrink: 0;
    }
    .album-title {
      font-family: var(--font-head);
      font-size: 1.1rem;
      font-weight: 800;
      color: var(--navy);
    }
    .album-count {
      font-family: var(--font-body);
      font-size: .75rem;
      font-weight: 700;
      color: white;
      background: var(--teal);
      padding: 2px 10px;
      border-radius: 100px;
    }

    /* ══ EMPTY STATE ══ */
    .gallery-empty {
      text-align: center;
      padding: 70px 0;
    }
    .gallery-empty i { font-size: 3rem; color: var(--gray-light); margin-bottom: 14px; display: block; }

    /* ══ BACK TO TOP ══ */
    #backToTop {
      position: fixed; bottom: 28px; right: 28px;
      width: 44px; height: 44px; background: var(--teal);
      color: white; border: none; border-radius: 10px; font-size: 1.1rem;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; z-index: 999; opacity: 0; transform: translateY(10px);
      transition: opacity .25s, transform .25s, background .2s;
      box-shadow: 0 4px 18px rgba(194,65,12,.35);
    }
    #backToTop.visible { opacity: 1; transform: translateY(0); }
    #backToTop:hover   { background: var(--navy); }

    /* ══ RESPONSIVE ══ */
    @media (max-width: 767.98px) {
      .gallery-grid { gap: 8px; }
      .lb-prev, .lb-next { display: none; }
    }
  </style>
<?php
if (empty($page_title)) {
  $page_title = 'Photo Gallery | Department of Dental Sciences';
}
if (empty($page_description)) {
  $page_description = 'Campus and facility photos from Peshawar Dental College and the Department of Dental Sciences at Riphah Peshawar Campus.';
}
if (empty($albums) || ! is_array($albums)) {
  $albums = [];
}
?>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow">Department of Dental Sciences</span>
    <h1>Photo Gallery</h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Gallery</span>
    </div>
  </div>
</div>

<section class="pmc-section bg-off">
  <div class="container">
    <div class="row mb-5">
      <div class="col-lg-7">
        <span class="sec-eyebrow">Visual Journey</span>
        <h2 class="sec-title">Life at Peshawar Dental College</h2>
        <p class="sec-desc">Explore PDC campus buildings and shared campus facilities that support dental education at Riphah International University – Peshawar Campus.</p>
      </div>
    </div>

    <div class="gallery-filters">
      <button class="gf-btn active" data-filter="all" type="button"><i class="bi bi-grid-3x3-gap me-1"></i> All Albums</button>
      <?php foreach ($albums as $category => $album): ?>
      <button class="gf-btn" data-filter="<?= esc($category) ?>" type="button"><i class="bi <?= esc($album['icon'] ?? 'bi-images') ?> me-1"></i> <?= esc($album['title'] ?? ucfirst((string) $category)) ?></button>
      <?php endforeach; ?>
    </div>
    <?php if (! $albums): ?>
    <div class="gallery-empty"><i class="bi bi-images"></i><p>Gallery albums will appear here once published.</p></div>
    <?php endif; ?>

<?php foreach ($albums as $category => $album):
  $count = count($album['items']);
?>
    <div class="album-section mb-5" data-category="<?= htmlspecialchars($category) ?>">
      <div class="album-heading">
        <div class="album-heading-icon"><i class="bi <?= htmlspecialchars($album['icon']) ?>"></i></div>
        <div>
          <div class="album-title"><?= htmlspecialchars($album['title']) ?></div>
          <span class="album-count"><?= (int)$count ?> Photos</span>
        </div>
        <div class="ms-auto">
          <button class="gf-btn" type="button" style="font-size:.72rem;padding:6px 14px;" onclick="openAlbumLightbox('<?= htmlspecialchars($category) ?>')">
            <i class="bi bi-images me-1"></i> View Album
          </button>
        </div>
      </div>
      <div class="gallery-grid" id="grid-<?= htmlspecialchars($category) ?>">
<?php foreach ($album['items'] as $item):
  $src = $item['src'];
  $title = $item['title'];
  $caption = $item['caption'] ?? '';
?>
        <div class="gallery-item fu"
             data-category="<?= htmlspecialchars($category) ?>"
             data-title="<?= htmlspecialchars($title) ?>"
             data-caption="<?= htmlspecialchars($caption) ?>"
             data-img="<?= esc(base_url($src)) ?>"
             onclick="openLightbox(this)">
          <img src="<?= esc(base_url($src)) ?>"
               alt="<?= htmlspecialchars($title) ?>"
               class="gallery-img" loading="lazy" decoding="async" />
          <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
          <div class="gallery-overlay">
            <div>
              <div class="gallery-overlay-cat"><?= htmlspecialchars(ucfirst($category)) ?></div>
              <div class="gallery-overlay-text"><?= htmlspecialchars($title) ?></div>
            </div>
          </div>
        </div>
<?php endforeach; ?>
      </div>
    </div>
<?php endforeach; ?>

  </div>
</section>


<!-- ═══ LIGHTBOX ═══ -->
<div class="pmc-lightbox" id="pmcLightbox" onclick="closeLightboxOnBackdrop(event)">
  <div class="lb-inner" id="lbInner">
    <button class="lb-close" onclick="closeLightbox()" aria-label="Close"><i class="bi bi-x-lg"></i></button>
    <div class="lb-img-wrap" id="lbImgWrap">
      <!-- content injected by JS -->
    </div>
    <div class="lb-caption" id="lbCaption"></div>
    <div class="lb-counter" id="lbCounter"></div>
  </div>
  <button class="lb-prev" onclick="lbNav(-1)" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
  <button class="lb-next" onclick="lbNav(1)"  aria-label="Next"><i class="bi bi-chevron-right"></i></button>
</div>

<!-- Recognition Strip -->
<section class="pmc-section-sm recog-strip">
  <div class="container">
    <div class="recog-grid">
      <div class="recog-cell"><div class="recog-ico"><i class="bi bi-patch-check-fill"></i></div><div class="recog-name">PM&DC</div></div>
      <div class="recog-cell"><div class="recog-ico"><i class="bi bi-building-fill"></i></div><div class="recog-name">Riphah University</div></div>
      <div class="recog-cell"><div class="recog-ico"><i class="bi bi-award-fill"></i></div><div class="recog-name">CPSP</div></div>
      <div class="recog-cell"><div class="recog-ico"><i class="bi bi-hospital-fill"></i></div><div class="recog-name">Ministry of Health</div></div>
      <div class="recog-cell" style="border-right:none;"><div class="recog-ico"><i class="bi bi-globe-americas"></i></div><div class="recog-name">WHO</div></div>
    </div>
  </div>
</section>

<!-- ═══ FOOTER ═══ -->


<button id="backToTop" aria-label="Back to top"><i class="bi bi-chevron-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/pmc-global.js') ?>"></script>
<script>
/* ══════════════════════════════════════════════
   GALLERY PAGE SCRIPTS
══════════════════════════════════════════════ */

// ── Placeholder HTML generator ──────────────────────────────────
function galleryPlaceholder(label, icon) {
  return `<div class="gallery-placeholder">
    <i class="bi ${icon || 'bi-image'}"></i>
    <span>${label}</span>
  </div>
  <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
  <div class="gallery-overlay">
    <div><div class="gallery-overlay-text">${label}</div></div>
  </div>`;
}

// ── Justified layout (Google Images style) ───────────────────────
function galleryTargetRowHeight() {
  const w = window.innerWidth;
  if (w < 576) return 120;
  if (w < 768) return 150;
  if (w < 992) return 180;
  return 220;
}

function galleryAspect(item) {
  if (item._ar && item._ar > 0) return item._ar;
  const img = item.querySelector('img.gallery-img');
  if (img && img.naturalWidth > 0 && img.naturalHeight > 0) {
    item._ar = img.naturalWidth / img.naturalHeight;
    return item._ar;
  }
  return 1;
}

function justifyGalleryGrid(grid) {
  if (!grid || grid.offsetParent === null && grid.closest('.album-section')?.style.display === 'none') return;
  const items = [...grid.querySelectorAll('.gallery-item')];
  if (!items.length) return;

  const gap = parseFloat(getComputedStyle(grid).gap) || 10;
  const W = grid.clientWidth;
  if (W < 40) return;
  // Fixed row height keeps every tile the same size; widths flex to fill each row.
  const H = galleryTargetRowHeight();

  let row = [];
  let widthSum = 0;

  const flush = (isLast) => {
    if (!row.length) return;
    const gaps = gap * Math.max(row.length - 1, 0);
    const avail = Math.max(W - gaps, 1);

    // Single leftover photo: keep natural width at row height (no giant stretch).
    if (isLast && row.length === 1) {
      const ar = galleryAspect(row[0]);
      row[0].style.height = H + 'px';
      row[0].style.width = Math.min(avail, Math.max(1, Math.round(H * ar))) + 'px';
      row = [];
      widthSum = 0;
      return;
    }

    // Always fill the row width so the last row matches earlier rows in height.
    const scale = avail / Math.max(widthSum, 1);
    let used = 0;
    row.forEach((item, i) => {
      let w;
      if (i === row.length - 1) {
        w = Math.max(1, avail - used);
      } else {
        w = Math.max(1, Math.floor(H * galleryAspect(item) * scale));
        used += w;
      }
      item.style.height = H + 'px';
      item.style.width = w + 'px';
    });
    row = [];
    widthSum = 0;
  };

  items.forEach((item) => {
    const w = H * galleryAspect(item);
    if (row.length && widthSum + w + gap * row.length > W) {
      flush(false);
    }
    row.push(item);
    widthSum += w;
  });
  flush(true);
}

function justifyAllGalleries() {
  document.querySelectorAll('.gallery-grid').forEach(justifyGalleryGrid);
}

function prepareGalleryImages() {
  document.querySelectorAll('.gallery-item img.gallery-img').forEach((img) => {
    const item = img.closest('.gallery-item');
    const apply = () => {
      if (img.naturalWidth > 0 && img.naturalHeight > 0) {
        item._ar = img.naturalWidth / img.naturalHeight;
      }
      justifyGalleryGrid(item.closest('.gallery-grid'));
    };
    if (img.complete && img.naturalWidth) apply();
    else img.addEventListener('load', apply, { once: true });
  });
  justifyAllGalleries();
}

let galleryResizeTimer;
window.addEventListener('resize', () => {
  clearTimeout(galleryResizeTimer);
  galleryResizeTimer = setTimeout(justifyAllGalleries, 120);
});

// ── Filter ───────────────────────────────────────────────────────
const filterBtns   = document.querySelectorAll('.gf-btn[data-filter]');
const albumSections = document.querySelectorAll('.album-section');

filterBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    filterBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const filter = btn.dataset.filter;

    albumSections.forEach(section => {
      const cat = section.dataset.category;
      const show = filter === 'all' || cat === filter;
      section.style.display = show ? '' : 'none';
      if (show) section.style.animation = 'none';
    });

    // Scroll to first visible album
    const first = [...albumSections].find(s => s.style.display !== 'none');
    if (first && filter !== 'all') first.scrollIntoView({ behavior: 'smooth', block: 'start' });
    requestAnimationFrame(justifyAllGalleries);
  });
});

prepareGalleryImages();

// ── Lightbox ─────────────────────────────────────────────────────
let lbItems   = [];
let lbCurrent = 0;

function openLightbox(el) {
  // Collect all gallery items currently visible
  lbItems = [...document.querySelectorAll('.gallery-item[data-img]')].filter(e => {
    return e.closest('.album-section').style.display !== 'none';
  });
  lbCurrent = lbItems.indexOf(el);
  renderLightbox();
  document.getElementById('pmcLightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function openAlbumLightbox(category) {
  lbItems = [...document.querySelectorAll(`.gallery-item[data-category="${category}"][data-img]`)];
  lbCurrent = 0;
  renderLightbox();
  document.getElementById('pmcLightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeLightbox() {
  document.getElementById('pmcLightbox').classList.remove('open');
  document.body.style.overflow = '';
}

function closeLightboxOnBackdrop(e) {
  if (e.target === document.getElementById('pmcLightbox')) closeLightbox();
}

function lbNav(dir) {
  lbCurrent = ((lbCurrent + dir) + lbItems.length) % lbItems.length;
  renderLightbox();
}

function renderLightbox() {
  const el    = lbItems[lbCurrent];
  const img   = el.dataset.img   || '';
  const title = el.dataset.title || '';
  const cap   = el.dataset.caption || '';
  const cat   = el.dataset.category || '';

  const wrap = document.getElementById('lbImgWrap');
  const isFallback = !img || img.endsWith('/');

  if (img) {
    wrap.innerHTML = `<img src="${img}" class="lb-img" alt="${title}"
      onerror="this.parentElement.innerHTML='<div class=\\'lb-placeholder\\'><i class=\\'bi bi-image\\'></i><span>${title}</span></div>'"
    />`;
  } else {
    wrap.innerHTML = `<div class="lb-placeholder"><i class="bi bi-image"></i><span>${title}</span></div>`;
  }

  document.getElementById('lbCaption').innerHTML = `
    <div class="lb-caption-cat">${cat}</div>
    <div class="lb-caption-title">${title}</div>
    ${cap ? `<div style="font-family:var(--font-body);font-size:.8rem;color:rgba(255,255,255,.5);margin-top:3px;">${cap}</div>` : ''}
  `;
  document.getElementById('lbCounter').textContent = `${lbCurrent + 1} / ${lbItems.length}`;
}

// Keyboard navigation
document.addEventListener('keydown', e => {
  const lb = document.getElementById('pmcLightbox');
  if (!lb.classList.contains('open')) return;
  if (e.key === 'ArrowRight' || e.key === 'ArrowDown') lbNav(1);
  if (e.key === 'ArrowLeft'  || e.key === 'ArrowUp')   lbNav(-1);
  if (e.key === 'Escape') closeLightbox();
});

// ── Fade-up observer ─────────────────────────────────────────────
const fuEls = document.querySelectorAll('.fu');
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); obs.unobserve(e.target); } });
}, { threshold: 0.08 });
fuEls.forEach(el => obs.observe(el));

// ── Load More (placeholder) ─────────────────────────────────────
function loadMore() {
  document.getElementById('loadMoreBtn').innerHTML = '<i class="bi bi-check-circle"></i> All Photos Loaded';
  document.getElementById('loadMoreBtn').disabled = true;
}

// ── Back to top ──────────────────────────────────────────────────
const btt = document.getElementById('backToTop');
window.addEventListener('scroll', () => btt.classList.toggle('visible', scrollY > 500));
btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

// ── Navbar scroll ────────────────────────────────────────────────
const nav = document.getElementById('mainNav');
window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 40));
</script>