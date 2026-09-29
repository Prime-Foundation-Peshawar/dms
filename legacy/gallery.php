<?php
require_once __DIR__ . '/includes/cms-content.php';
$albums = dms_gallery_albums_with_images();
$categories = [];
foreach ($albums as $a) {
    $cat = (string) ($a['category'] ?? '');
    if ($cat !== '' && !isset($categories[$cat])) {
        $categories[$cat] = [
            'key'  => $cat,
            'icon' => (string) ($a['icon'] ?? 'bi-images'),
        ];
    }
}
?>
<style>
    .gallery-filters {
      display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 40px; justify-content: center;
    }
    .gf-btn {
      font-family: var(--font-head); font-size: .78rem; font-weight: 700;
      padding: 9px 22px; border-radius: 100px; border: 2px solid var(--border);
      background: white; color: var(--gray-dark); cursor: pointer; transition: all .2s; letter-spacing: .02em;
    }
    .gf-btn:hover  { border-color: var(--teal); color: var(--teal); }
    .gf-btn.active { background: var(--teal); border-color: var(--teal); color: white; }
    .gallery-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
    .gallery-item {
      position: relative; border-radius: var(--r-md); overflow: hidden; aspect-ratio: 1;
      cursor: pointer; background: var(--gray-light); transition: transform .3s, box-shadow .3s;
    }
    .gallery-item:hover { transform: scale(1.02); box-shadow: var(--shadow-lg); }
    .gallery-item.span-2     { grid-column: span 2; aspect-ratio: 2/1; }
    .gallery-item.span-2-row { grid-row: span 2; aspect-ratio: 1/2; }
    .gallery-img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .4s; }
    .gallery-item:hover .gallery-img { transform: scale(1.06); }
    .gallery-placeholder {
      width: 100%; height: 100%; background: linear-gradient(135deg, var(--navy), var(--navy-mid));
      display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px;
    }
    .gallery-placeholder i { font-size: 2rem; color: rgba(255,255,255,.5); }
    .gallery-placeholder span { font-family: var(--font-head); font-size: .75rem; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: .08em; }
    .gallery-overlay {
      position: absolute; inset: 0; background: linear-gradient(to top, rgba(10,22,40,.85) 0%, transparent 55%);
      opacity: 0; transition: opacity .25s; display: flex; align-items: flex-end; padding: 18px;
    }
    .gallery-item:hover .gallery-overlay { opacity: 1; }
    .gallery-overlay-text { font-family: var(--font-head); font-size: .82rem; font-weight: 700; color: white; line-height: 1.3; }
    .gallery-overlay-cat { font-family: var(--font-body); font-size: .68rem; font-weight: 700; color: var(--teal-light); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 3px; }
    .gallery-zoom {
      position: absolute; top: 12px; right: 12px; width: 32px; height: 32px;
      background: rgba(255,255,255,.15); backdrop-filter: blur(8px); border-radius: 8px;
      display: flex; align-items: center; justify-content: center; color: white; font-size: .9rem;
      opacity: 0; transition: opacity .25s;
    }
    .gallery-item:hover .gallery-zoom { opacity: 1; }
    .pmc-lightbox {
      position: fixed; inset: 0; background: rgba(6,14,26,.96); z-index: 9999;
      display: none; align-items: center; justify-content: center;
    }
    .pmc-lightbox.open { display: flex; animation: lbFade .25s ease; }
    @keyframes lbFade { from{opacity:0} to{opacity:1} }
    .lb-inner { position: relative; max-width: 90vw; max-height: 90vh; display: flex; flex-direction: column; align-items: center; }
    .lb-img-wrap { position: relative; border-radius: var(--r-md); overflow: hidden; box-shadow: 0 24px 80px rgba(0,0,0,.6); }
    .lb-img { max-width: 88vw; max-height: 76vh; object-fit: contain; display: block; }
    .lb-placeholder {
      width: 600px; max-width: 88vw; height: 400px; max-height: 76vh;
      background: linear-gradient(135deg, var(--navy), var(--navy-mid));
      display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 14px;
    }
    .lb-placeholder i { font-size: 3rem; color: rgba(255,255,255,.4); }
    .lb-placeholder span { font-family:var(--font-head); font-size:.8rem; color:rgba(255,255,255,.35); }
    .lb-caption { margin-top: 16px; text-align: center; }
    .lb-caption-title { font-family: var(--font-head); font-size: .95rem; font-weight: 700; color: white; margin-bottom: 3px; }
    .lb-caption-cat { font-family: var(--font-body); font-size: .75rem; color: var(--teal-light); font-weight: 600; text-transform: uppercase; letter-spacing: .07em; }
    .lb-counter { font-family: var(--font-body); font-size: .75rem; color: rgba(255,255,255,.35); margin-top: 6px; }
    .lb-close {
      position: absolute; top: -48px; right: 0; width: 40px; height: 40px;
      background: rgba(255,255,255,.1); border: none; border-radius: 8px; color: white; font-size: 1.1rem;
      cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .2s;
    }
    .lb-close:hover { background: rgba(255,255,255,.2); }
    .lb-prev, .lb-next {
      position: fixed; top: 50%; transform: translateY(-50%); width: 48px; height: 48px;
      background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15); border-radius: 50%;
      color: white; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background .2s;
    }
    .lb-prev { left: 20px; }
    .lb-next { right: 20px; }
    .lb-prev:hover, .lb-next:hover { background: rgba(21,95,122,.5); }
    .album-heading {
      display: flex; align-items: center; gap: 14px; margin-bottom: 22px; padding-bottom: 14px; border-bottom: 2px solid var(--border);
    }
    .album-heading-icon {
      width: 44px; height: 44px; background: var(--navy); border-radius: 10px;
      display: flex; align-items: center; justify-content: center; color: var(--gold); font-size: 1.2rem; flex-shrink: 0;
    }
    .album-title { font-family: var(--font-head); font-size: 1.1rem; font-weight: 800; color: var(--navy); }
    .album-count { font-family: var(--font-body); font-size: .75rem; font-weight: 700; color: white; background: var(--teal); padding: 2px 10px; border-radius: 100px; }
    .gallery-empty { text-align: center; padding: 70px 0; }
    .gallery-empty i { font-size: 3rem; color: var(--gray-light); margin-bottom: 14px; display: block; }
    #backToTop {
      position: fixed; bottom: 28px; right: 28px; width: 44px; height: 44px; background: var(--teal);
      color: white; border: none; border-radius: 10px; font-size: 1.1rem;
      display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 999;
      opacity: 0; transform: translateY(10px); transition: opacity .25s, transform .25s, background .2s;
      box-shadow: 0 4px 18px rgba(21,95,122,.35);
    }
    #backToTop.visible { opacity: 1; transform: translateY(0); }
    #backToTop:hover { background: var(--navy); }
    @media (max-width: 991.98px) {
      .gallery-grid { grid-template-columns: repeat(3,1fr); }
      .gallery-item.span-2 { grid-column: span 2; }
      .gallery-item.span-2-row { grid-row: span 1; aspect-ratio: 1; }
    }
    @media (max-width: 767.98px) {
      .gallery-grid { grid-template-columns: repeat(2,1fr); }
      .gallery-item.span-2 { grid-column: span 2; }
      .gallery-item.span-2-row { grid-row: span 1; aspect-ratio: 1; }
      .lb-prev, .lb-next { display: none; }
    }
    @media (max-width: 480px) {
      .gallery-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
    }
</style>
<?php include('includes/header.php'); ?>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Photo Gallery</h1>
    <div class="breadcrumb-pmc">
      <a href="./">Home</a>
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
        <h2 class="sec-title">Life at Campus</h2>
        <p class="sec-desc">Explore our campus, events, clinical training, research activities, student societies, and memorable moments captured across our 20+ year journey.</p>
      </div>
    </div>

    <div class="gallery-filters">
      <button class="gf-btn active" data-filter="all">
        <i class="bi bi-grid-3x3-gap me-1"></i> All Albums
      </button>
      <?php foreach ($categories as $cat): ?>
      <button class="gf-btn" data-filter="<?= htmlspecialchars($cat['key']) ?>">
        <i class="bi <?= htmlspecialchars($cat['icon']) ?> me-1"></i> <?= htmlspecialchars(ucfirst($cat['key'])) ?>
      </button>
      <?php endforeach; ?>
    </div>

    <?php if (empty($albums)): ?>
      <div class="gallery-empty"><i class="bi bi-images"></i><p>No gallery albums published yet.</p></div>
    <?php endif; ?>

    <?php foreach ($albums as $album): ?>
      <?php
        $cat = (string) ($album['category'] ?? '');
        $slug = (string) ($album['slug'] ?? $cat);
        $title = (string) ($album['title'] ?? '');
        $icon = (string) ($album['icon'] ?? 'bi-images');
        $images = is_array($album['images'] ?? null) ? $album['images'] : [];
        $count = count($images);
      ?>
      <div class="album-section mb-5" data-category="<?= htmlspecialchars($cat) ?>">
        <div class="album-heading">
          <div class="album-heading-icon"><i class="bi <?= htmlspecialchars($icon) ?>"></i></div>
          <div>
            <div class="album-title"><?= htmlspecialchars($title) ?></div>
            <span class="album-count"><?= $count ?> Photo<?= $count === 1 ? '' : 's' ?></span>
          </div>
          <?php if ($count > 0): ?>
          <div class="ms-auto">
            <button type="button" class="gf-btn" style="font-size:.72rem;padding:6px 14px;"
                    onclick="openAlbumLightbox('<?= htmlspecialchars($cat, ENT_QUOTES) ?>')">
              <i class="bi bi-play-fill me-1"></i> View Slideshow
            </button>
          </div>
          <?php endif; ?>
        </div>
        <div class="gallery-grid" id="grid-<?= htmlspecialchars($slug) ?>">
          <?php foreach ($images as $img): ?>
            <?php
              $span = trim((string) ($img['span_class'] ?? ''));
              $itemClass = 'gallery-item fu' . ($span !== '' ? ' ' . preg_replace('/[^a-z0-9\-]/', '', $span) : '');
              $imgPath = (string) ($img['image_path'] ?? '');
              $imgTitle = (string) ($img['title'] ?? '');
              $imgCap = (string) ($img['caption'] ?? '');
            ?>
            <div class="<?= htmlspecialchars($itemClass) ?>"
                 data-category="<?= htmlspecialchars($cat) ?>"
                 data-title="<?= htmlspecialchars($imgTitle) ?>"
                 data-caption="<?= htmlspecialchars($imgCap) ?>"
                 data-img="<?= htmlspecialchars($imgPath) ?>"
                 onclick="openLightbox(this)">
              <img src="<?= htmlspecialchars($imgPath) ?>"
                   onerror="this.parentElement.innerHTML=galleryPlaceholder(this.alt,'bi-image')"
                   class="gallery-img" loading="lazy" decoding="async" alt="<?= htmlspecialchars($imgTitle) ?>"/>
              <div class="gallery-zoom"><i class="bi bi-zoom-in"></i></div>
              <div class="gallery-overlay">
                <div>
                  <div class="gallery-overlay-cat"><?= htmlspecialchars(ucfirst($cat)) ?></div>
                  <div class="gallery-overlay-text"><?= htmlspecialchars($imgTitle) ?></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<div class="pmc-lightbox" id="pmcLightbox" onclick="closeLightboxOnBackdrop(event)">
  <div class="lb-inner" id="lbInner">
    <button class="lb-close" onclick="closeLightbox()" aria-label="Close"><i class="bi bi-x-lg"></i></button>
    <div class="lb-img-wrap" id="lbImgWrap"></div>
    <div class="lb-caption" id="lbCaption"></div>
    <div class="lb-counter" id="lbCounter"></div>
  </div>
  <button class="lb-prev" onclick="lbNav(-1)" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
  <button class="lb-next" onclick="lbNav(1)" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
</div>

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

<?php include('includes/footer.php'); ?>

<button id="backToTop" aria-label="Back to top"><i class="bi bi-chevron-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/pmc-global.js"></script>
<script>
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

const filterBtns = document.querySelectorAll('.gf-btn[data-filter]');
const albumSections = document.querySelectorAll('.album-section');
filterBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    filterBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const filter = btn.dataset.filter;
    albumSections.forEach(section => {
      const cat = section.dataset.category;
      section.style.display = (filter === 'all' || cat === filter) ? '' : 'none';
    });
    const first = [...albumSections].find(s => s.style.display !== 'none');
    if (first && filter !== 'all') first.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });
});

let lbItems = [];
let lbCurrent = 0;

function openLightbox(el) {
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
  if (!lbItems.length) return;
  lbCurrent = ((lbCurrent + dir) + lbItems.length) % lbItems.length;
  renderLightbox();
}

function renderLightbox() {
  const el = lbItems[lbCurrent];
  if (!el) return;
  const img = el.dataset.img || '';
  const title = el.dataset.title || '';
  const cap = el.dataset.caption || '';
  const cat = el.dataset.category || '';
  const wrap = document.getElementById('lbImgWrap');
  if (img) {
    wrap.innerHTML = `<img src="${img}" class="lb-img" alt="${title}"
      onerror="this.parentElement.innerHTML='<div class=\\'lb-placeholder\\'><i class=\\'bi bi-image\\'></i><span>${title}</span></div>'"/>`;
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

document.addEventListener('keydown', e => {
  const lb = document.getElementById('pmcLightbox');
  if (!lb.classList.contains('open')) return;
  if (e.key === 'ArrowRight' || e.key === 'ArrowDown') lbNav(1);
  if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') lbNav(-1);
  if (e.key === 'Escape') closeLightbox();
});

const fuEls = document.querySelectorAll('.fu');
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); obs.unobserve(e.target); } });
}, { threshold: 0.08 });
fuEls.forEach(el => obs.observe(el));

const btt = document.getElementById('backToTop');
window.addEventListener('scroll', () => btt.classList.toggle('visible', scrollY > 500));
btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

const nav = document.getElementById('mainNav');
if (nav) window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 40));
</script>
