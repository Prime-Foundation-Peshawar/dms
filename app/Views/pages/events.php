<?php
/** Auto-ported content body from legacy events.php */
?>
<?= $this->include('pages/partials/news_events_styles') ?>

<?php
$event_rows = is_array($event_rows ?? null) ? $event_rows : [];
$today = date('Y-m-d');
// Neutral placeholder when an event has no cover upload.
$defaultEventCover = 'assets/images/placeholders/event-placeholder.jpg';
if (! is_file(FCPATH . $defaultEventCover)) {
    $defaultEventCover = 'assets/images/slider/hero-slide-1.jpg';
}
$eventCover = static function (?string $path) use ($defaultEventCover): string {
    $path = trim((string) $path);

    return $path !== '' ? $path : $defaultEventCover;
};
$catCounts = [];
$years = [];
foreach ($event_rows as $ev) {
    $cat = trim((string) ($ev['category'] ?? 'General'));
    $catCounts[$cat] = ($catCounts[$cat] ?? 0) + 1;
    $d = (string) ($ev['event_date'] ?? '');
    if ($d !== '' && preg_match('/^(\d{4})/', $d, $m)) {
        $years[(int) $m[1]] = true;
    }
}
krsort($years);
$years = array_keys($years);

$catSlug = static function (string $cat): string {
    $s = strtolower(trim($cat));
    $s = preg_replace('/[^a-z0-9]+/', '-', $s) ?? '';
    return trim($s, '-') ?: 'general';
};

$featured = $event_rows[0] ?? null;
$upcoming = [];
foreach ($event_rows as $ev) {
    $d = (string) ($ev['event_date'] ?? '');
    if ($d !== '' && $d >= $today) {
        $upcoming[] = $ev;
    }
}
$upcomingIsFuture = $upcoming !== [];
if ($upcomingIsFuture) {
    usort($upcoming, static fn ($a, $b) => strcmp((string) ($a['event_date'] ?? ''), (string) ($b['event_date'] ?? '')));
    $upcoming = array_slice($upcoming, 0, 5);
} else {
    // No future-dated rows — show most recent instead (labeled Recent).
    $upcoming = array_slice($event_rows, 0, 5);
}

$fmtDay = static function (?string $d): string {
    if (! $d) {
        return '';
    }
    $ts = strtotime($d);

    return $ts ? date('d', $ts) : '';
};
$fmtMon = static function (?string $d): string {
    if (! $d) {
        return '';
    }
    $ts = strtotime($d);

    return $ts ? date('M', $ts) : '';
};
$fmtLong = static function (?string $d): string {
    if (! $d) {
        return '';
    }
    $ts = strtotime($d);

    return $ts ? date('M j, Y', $ts) : '';
};
?>

<!-- ═══ PAGE HERO ═══ -->
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>News &amp; Events</h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">Events</span>
    </div>
  </div>
</div>

<section class="pmc-section bg-off">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">

        <div class="events-filter-bar fu">
          <div class="ef-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="ef-search" id="evSearch" placeholder="Search events…" />
          </div>
          <select class="ef-select" id="evYear">
            <option value="" selected>All Years</option>
            <?php foreach ($years as $y): ?>
            <option value="<?= (int) $y ?>"><?= (int) $y ?></option>
            <?php endforeach; ?>
          </select>
          <select class="ef-select" id="evSort">
            <option value="newest">Newest First</option>
            <option value="oldest">Oldest First</option>
          </select>
        </div>

        <div class="cat-pills fu">
          <a class="cat-pill active" data-cat="all" href="#">All</a>
          <?php foreach ($catCounts as $cat => $count): ?>
          <a class="cat-pill" data-cat="<?= esc($catSlug($cat)) ?>" href="#"><?= esc($cat) ?></a>
          <?php endforeach; ?>
        </div>

        <?php if ($featured):
          $fCat = trim((string) ($featured['category'] ?? 'General'));
          $fHref = site_url('event-single') . '?slug=' . rawurlencode((string) $featured['slug']);
          $fCover = $eventCover($featured['cover_image'] ?? null);
        ?>
        <div class="featured-event fu" id="featuredEvent">
          <div class="featured-event-img" style="background:url('<?= esc(base_url($fCover)) ?>') center/cover no-repeat;min-height:220px;"></div>
          <div class="featured-event-body">
            <div class="featured-badge"><i class="bi bi-star-fill"></i> Latest</div>
            <h2 class="featured-event-title"><?= esc($featured['title'] ?? '') ?></h2>
            <p class="featured-event-excerpt"><?= esc($featured['excerpt'] ?? '') ?></p>
            <div class="featured-event-meta">
              <?php if (! empty($featured['event_date'])): ?>
              <div class="fem-item"><i class="bi bi-calendar3"></i> <?= esc($fmtLong($featured['event_date'] ?? null)) ?></div>
              <?php endif; ?>
              <?php if ($fCat !== ''): ?>
              <div class="fem-item"><i class="bi bi-tag"></i> <?= esc($fCat) ?></div>
              <?php endif; ?>
              <?php if (! empty($featured['location'])): ?>
              <div class="fem-item"><i class="bi bi-geo-alt"></i> <?= esc($featured['location']) ?></div>
              <?php endif; ?>
            </div>
            <a href="<?= esc($fHref) ?>" class="btn-pmc btn-pmc-gold" style="font-size:.85rem;padding:11px 22px;align-self:flex-start;">
              <i class="bi bi-arrow-right-circle"></i> Read Full Story
            </a>
          </div>
        </div>
        <?php endif; ?>

        <div class="row g-4" id="eventsGrid">
          <?php foreach ($event_rows as $ev):
            $cat = trim((string) ($ev['category'] ?? 'General'));
            $cSlug = $catSlug($cat);
            $date = (string) ($ev['event_date'] ?? '');
            $year = $date !== '' && preg_match('/^(\d{4})/', $date, $ym) ? $ym[1] : '';
            $href = site_url('event-single') . '?slug=' . rawurlencode((string) ($ev['slug'] ?? ''));
            $cover = $eventCover($ev['cover_image'] ?? null);
            $excerpt = trim((string) ($ev['excerpt'] ?? ''));
            if ($excerpt === '') {
                $excerpt = trim((string) ($ev['body_text'] ?? ''));
            }
            if (mb_strlen($excerpt) > 160) {
                $excerpt = mb_substr($excerpt, 0, 157) . '…';
            }
          ?>
          <div class="col-md-6 ev-item fu" data-cat="<?= esc($cSlug) ?>" data-year="<?= esc($year) ?>" data-date="<?= esc($date) ?>">
            <a class="event-card" href="<?= esc($href) ?>">
              <div class="ec-img">
                <img src="<?= esc(base_url($cover)) ?>" alt="<?= esc($ev['title'] ?? '') ?>" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
              </div>
              <div style="position:relative;">
                <div class="ec-badges" style="position:relative;padding:8px 12px;background:transparent;bottom:auto;left:auto;right:auto;">
                  <span class="ec-cat-badge cat-<?= esc($cSlug) ?>"><?= esc($cat) ?></span>
                  <?php if ($date !== ''): ?>
                  <div class="ec-date-badge"><span class="ec-date-day"><?= esc($fmtDay($date)) ?></span><span class="ec-date-month"><?= esc($fmtMon($date)) ?></span></div>
                  <?php endif; ?>
                </div>
              </div>
              <div class="ec-body">
                <div class="ec-title"><?= esc($ev['title'] ?? '') ?></div>
                <div class="ec-excerpt"><?= esc($excerpt) ?></div>
                <div class="ec-footer">
                  <?php if (! empty($ev['location'])): ?>
                  <span class="ec-meta-item"><i class="bi bi-geo-alt"></i> <?= esc($ev['location']) ?></span>
                  <?php else: ?>
                  <span class="ec-meta-item"><i class="bi bi-calendar3"></i> <?= esc($fmtLong($date)) ?></span>
                  <?php endif; ?>
                  <span class="ec-read-link">Read More <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>

        <div id="evNoResults" style="<?= $event_rows ? 'display:none;' : '' ?>text-align:center;padding:60px 0;">
          <i class="bi bi-search" style="font-size:3rem;color:var(--gray-light);display:block;margin-bottom:14px;"></i>
          <h5 style="font-family:var(--font-head);color:var(--navy);"><?= $event_rows ? 'No events found' : 'No events published yet' ?></h5>
          <p style="font-family:var(--font-body);color:var(--gray-mid);font-size:.9rem;"><?= $event_rows ? 'Try different filters or search terms.' : 'Check back soon for upcoming campus events.' ?></p>
          <?php if ($event_rows): ?>
          <button onclick="resetFilters()" class="btn-pmc btn-pmc-outline mt-3" type="button" style="font-size:.85rem;padding:9px 20px;">
            <i class="bi bi-x-circle"></i> Clear Filters
          </button>
          <?php endif; ?>
        </div>

      </div>

      <div class="col-lg-4">
        <div class="sidebar-widget mb-4 fu">
          <div class="sw-head"><i class="bi bi-calendar-event"></i> <?= $upcomingIsFuture ? 'Upcoming Events' : 'Recent Events' ?></div>
          <div class="sw-body" style="padding:16px;">
            <?php if ($upcoming): ?>
              <?php foreach ($upcoming as $ev):
                $date = (string) ($ev['event_date'] ?? '');
                $href = site_url('event-single') . '?slug=' . rawurlencode((string) ($ev['slug'] ?? ''));
              ?>
            <a class="upcoming-card" href="<?= esc($href) ?>">
              <div class="upcoming-date-block">
                <span class="udb-day"><?= esc($fmtDay($date) ?: '—') ?></span>
                <span class="udb-month"><?= esc($fmtMon($date) ?: '') ?></span>
              </div>
              <div class="upcoming-body">
                <div class="ub-title"><?= esc($ev['title'] ?? '') ?></div>
                <div class="ub-venue"><i class="bi bi-geo-alt-fill"></i> <?= esc($ev['location'] ?? $fmtLong($date)) ?></div>
              </div>
            </a>
              <?php endforeach; ?>
            <?php else: ?>
            <p class="small text-muted mb-0">No upcoming events listed.</p>
            <?php endif; ?>
          </div>
        </div>

        <?php if ($catCounts): ?>
        <div class="sidebar-widget mb-4 fu">
          <div class="sw-head"><i class="bi bi-tags-fill"></i> Browse by Category</div>
          <div class="sw-body">
            <?php foreach ($catCounts as $cat => $count): ?>
            <a class="sw-link" href="#" data-filter-cat="<?= esc($catSlug($cat)) ?>"><i class="bi bi-tag"></i><?= esc($cat) ?>
              <span class="ms-auto pmc-tag" style="font-size:.68rem;"><?= (int) $count ?></span></a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <div class="sidebar-widget mt-4 fu">
          <div class="sw-head"><i class="bi bi-link-45deg"></i> Quick Links</div>
          <div class="sw-body">
            <a class="sw-link" href="<?= site_url('admissions') ?>"><i class="bi bi-door-open"></i>Admissions</a>
            <a class="sw-link" href="<?= site_url('gallery') ?>"><i class="bi bi-images"></i>Photo Gallery</a>
            <a class="sw-link" href="<?= site_url('all-news') ?>"><i class="bi bi-newspaper"></i>Campus News</a>
            <a class="sw-link" href="<?= site_url('contact') ?>"><i class="bi bi-envelope"></i>Contact</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

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

<button id="backToTop" aria-label="Back to top"><i class="bi bi-chevron-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/pmc-global.js') ?>"></script>
<?= $this->include('pages/partials/news_events_filter') ?>
<script>
  document.querySelectorAll('.fu').forEach(el => {
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('vis'); obs.unobserve(e.target); } });
    }, { threshold: 0.08 });
    obs.observe(el);
  });

  const btt = document.getElementById('backToTop');
  if (btt) {
    window.addEventListener('scroll', () => btt.classList.toggle('visible', scrollY > 500));
    btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }
</script>
