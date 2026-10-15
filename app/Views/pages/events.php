<?php
/** Auto-ported content body from legacy events.php */
?>
<style>
  /* ══ FILTER BAR ══ */
  .events-filter-bar {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: 22px 28px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 36px;
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
  }

  .ef-search-wrap {
    position: relative;
    flex: 1;
    min-width: 220px;
  }

  .ef-search-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-mid);
    font-size: 1rem;
    pointer-events: none;
  }

  .ef-search {
    font-family: var(--font-body);
    font-size: .9rem;
    font-weight: 500;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    padding: 11px 16px 11px 42px;
    width: 100%;
    color: var(--text);
    background: var(--off-white);
    transition: border-color .2s, box-shadow .2s;
  }

  .ef-search:focus {
    outline: none;
    border-color: var(--teal);
    background: white;
    box-shadow: 0 0 0 3px rgba(194,65,12, .12);
  }

  .ef-select {
    font-family: var(--font-body);
    font-size: .88rem;
    font-weight: 600;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    padding: 11px 16px;
    min-width: 160px;
    background: var(--off-white);
    color: var(--text);
    transition: border-color .2s;
    cursor: pointer;
  }

  .ef-select:focus {
    outline: none;
    border-color: var(--teal);
  }

  /* ══ CATEGORY PILLS ══ */
  .cat-pills {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 30px;
  }

  .cat-pill {
    font-family: var(--font-head);
    font-size: .72rem;
    font-weight: 700;
    padding: 6px 16px;
    border-radius: 100px;
    cursor: pointer;
    border: 2px solid var(--border);
    background: white;
    color: var(--gray-dark);
    transition: all .2s;
    text-decoration: none;
  }

  .cat-pill:hover {
    border-color: var(--teal);
    color: var(--teal);
  }

  .cat-pill.active {
    background: var(--navy);
    border-color: var(--navy);
    color: white;
  }

  /* ══ FEATURED EVENT ══ */
  .featured-event {
    background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
    border-radius: var(--r-lg);
    overflow: hidden;
    margin-bottom: 40px;
    position: relative;
    min-height: 340px;
    display: flex;
    align-items: stretch;
  }

  .featured-event-img {
    flex: 0 0 48%;
    background-image: var(--bg-img, none);
    background-size: cover;
    background-position: center;
    position: relative;
    min-height: 340px;
  }

  .featured-event-img::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, transparent 60%, var(--navy) 100%);
  }

  .featured-event-img-placeholder {
    flex: 0 0 48%;
    background: linear-gradient(135deg, #122040, #0d3060);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 340px;
  }

  .featured-event-img-placeholder i {
    font-size: 4rem;
    color: rgba(255, 255, 255, .15);
  }

  .featured-event-body {
    flex: 1;
    padding: 44px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .featured-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-family: var(--font-head);
    font-size: .65rem;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--gold-light);
    background: rgba(201, 168, 76, .14);
    border: 1px solid rgba(201, 168, 76, .3);
    padding: 4px 12px;
    border-radius: 100px;
    margin-bottom: 16px;
    align-self: flex-start;
  }

  .featured-event-title {
    font-family: var(--font-head);
    font-size: clamp(1.4rem, 3vw, 1.9rem);
    font-weight: 800;
    color: white;
    line-height: 1.2;
    margin-bottom: 14px;
  }

  .featured-event-excerpt {
    font-family: var(--font-body);
    font-size: .92rem;
    color: rgba(255, 255, 255, .65);
    line-height: 1.75;
    margin-bottom: 22px;
    font-weight: 500;
  }

  .featured-event-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 26px;
  }

  .fem-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-family: var(--font-body);
    font-size: .8rem;
    font-weight: 600;
    color: rgba(255, 255, 255, .55);
  }

  .fem-item i {
    color: var(--teal-light);
    font-size: .9rem;
  }

  /* ══ EVENT CARDS ══ */
  .event-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: transform .25s, box-shadow .25s, border-color .25s;
    text-decoration: none;
    color: inherit;
  }

  .event-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--teal);
    color: inherit;
  }

  /* Card image */
  .ec-img {
    height: 200px;
    background-image: var(--bg-img, linear-gradient(135deg, var(--navy), var(--navy-mid)));
    background-size: cover;
    background-position: center;
    position: relative;
    flex-shrink: 0;
  }

  .ec-img-placeholder {
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 8px;
    flex-shrink: 0;
  }

  .ec-img-placeholder i {
    font-size: 2.5rem;
    color: rgba(255, 255, 255, .5);
  }

  .ec-img-placeholder span {
    font-family: var(--font-head);
    font-size: .72rem;
    color: rgba(255, 255, 255, .35);
    text-transform: uppercase;
    letter-spacing: .06em;
  }

  /* Gradient on images */
  .ec-img::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 40%, rgba(10, 22, 40, .5) 100%);
  }

  /* Category + Date badge overlay */
  .ec-badges {
    position: absolute;
    bottom: 12px;
    left: 12px;
    right: 12px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    z-index: 2;
  }

  .ec-cat-badge {
    font-family: var(--font-head);
    font-size: .65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
    padding: 3px 10px;
    border-radius: 100px;
    color: white;
  }

  .cat-admissions {
    background: rgba(194,65,12, .85);
  }

  .cat-research {
    background: rgba(10, 22, 40, .85);
  }

  .cat-achievement {
    background: rgba(201, 168, 76, .9);
    color: var(--navy) !important;
  }

  .cat-conference {
    background: rgba(30, 80, 200, .85);
  }

  .cat-society {
    background: rgba(130, 0, 160, .85);
  }

  .cat-general {
    background: rgba(60, 70, 80, .85);
  }

  /* Date pill */
  .ec-date-badge {
    background: white;
    border-radius: 10px;
    padding: 6px 10px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .2);
    min-width: 46px;
  }

  .ec-date-day {
    font-family: var(--font-head);
    font-size: 1.1rem;
    font-weight: 900;
    color: var(--navy);
    display: block;
    line-height: 1;
  }

  .ec-date-month {
    font-family: var(--font-body);
    font-size: .58rem;
    font-weight: 700;
    color: var(--teal);
    text-transform: uppercase;
    letter-spacing: .06em;
    display: block;
  }

  /* Card body */
  .ec-body {
    padding: 22px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .ec-title {
    font-family: var(--font-head);
    font-size: .95rem;
    font-weight: 700;
    color: var(--navy);
    line-height: 1.4;
    margin-bottom: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .ec-excerpt {
    font-family: var(--font-body);
    font-size: .84rem;
    color: var(--gray-mid);
    line-height: 1.65;
    font-weight: 500;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .ec-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid var(--border);
  }

  .ec-meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-family: var(--font-body);
    font-size: .74rem;
    font-weight: 600;
    color: var(--gray-mid);
  }

  .ec-meta-item i {
    color: var(--teal);
    font-size: .82rem;
  }

  .ec-read-link {
    font-family: var(--font-head);
    font-size: .76rem;
    font-weight: 700;
    color: var(--teal);
    display: flex;
    align-items: center;
    gap: 5px;
    transition: gap .2s, color .2s;
  }

  .event-card:hover .ec-read-link {
    gap: 9px;
    color: var(--navy);
  }

  /* ══ UPCOMING EVENTS SIDEBAR ══ */
  .upcoming-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--r-md);
    overflow: hidden;
    margin-bottom: 12px;
    display: flex;
    transition: border-color .2s, transform .2s;
    text-decoration: none;
    color: inherit;
  }

  .upcoming-card:hover {
    border-color: var(--teal);
    transform: translateX(4px);
    color: inherit;
  }

  .upcoming-date-block {
    flex: 0 0 64px;
    background: var(--navy);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 12px 8px;
  }

  .udb-day {
    font-family: var(--font-head);
    font-size: 1.4rem;
    font-weight: 900;
    color: var(--gold-light);
    display: block;
    line-height: 1;
  }

  .udb-month {
    font-family: var(--font-body);
    font-size: .6rem;
    font-weight: 700;
    color: rgba(255, 255, 255, .5);
    text-transform: uppercase;
    letter-spacing: .06em;
    display: block;
    margin-top: 3px;
  }

  .upcoming-body {
    padding: 12px 14px;
    flex: 1;
  }

  .ub-title {
    font-family: var(--font-head);
    font-size: .82rem;
    font-weight: 700;
    color: var(--navy);
    line-height: 1.35;
    margin-bottom: 4px;
  }

  .ub-venue {
    font-family: var(--font-body);
    font-size: .72rem;
    color: var(--gray-mid);
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .ub-venue i {
    color: var(--teal);
    font-size: .78rem;
  }

  /* ══ NEWSLETTER WIDGET ══ */
  .nl-widget {
    background: linear-gradient(135deg, var(--navy), var(--navy-mid));
    border-radius: var(--r-md);
    padding: 28px;
  }

  .nl-widget h5 {
    font-family: var(--font-head);
    color: white;
    font-size: 1rem;
    font-weight: 800;
    margin-bottom: 8px;
  }

  .nl-widget p {
    font-family: var(--font-body);
    color: rgba(255, 255, 255, .55);
    font-size: .84rem;
    line-height: 1.6;
    margin-bottom: 18px;
  }

  .nl-input {
    font-family: var(--font-body);
    font-size: .88rem;
    border: 1.5px solid rgba(255, 255, 255, .15);
    border-radius: var(--r-sm);
    padding: 11px 14px;
    width: 100%;
    color: white;
    background: rgba(255, 255, 255, .07);
    margin-bottom: 10px;
    transition: border-color .2s;
  }

  .nl-input::placeholder {
    color: rgba(255, 255, 255, .3);
  }

  .nl-input:focus {
    outline: none;
    border-color: var(--teal);
    background: rgba(255, 255, 255, .1);
  }

  /* ══ PAGINATION ══ */
  .events-pagination {
    display: flex;
    gap: 6px;
    justify-content: center;
    margin-top: 44px;
    flex-wrap: wrap;
  }

  .pg-btn {
    width: 40px;
    height: 40px;
    border: 1.5px solid var(--border);
    border-radius: var(--r-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-head);
    font-size: .82rem;
    font-weight: 700;
    color: var(--navy);
    background: white;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
  }

  .pg-btn:hover {
    border-color: var(--teal);
    color: var(--teal);
  }

  .pg-btn.active {
    background: var(--teal);
    border-color: var(--teal);
    color: white;
  }

  .pg-btn.disabled {
    opacity: .4;
    pointer-events: none;
  }

  /* ══ BACK TO TOP ══ */
  #backToTop {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 44px;
    height: 44px;
    background: var(--teal);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 999;
    opacity: 0;
    transform: translateY(10px);
    transition: opacity .25s, transform .25s, background .2s;
    box-shadow: 0 4px 18px rgba(194,65,12, .35);
  }

  #backToTop.visible {
    opacity: 1;
    transform: translateY(0);
  }

  #backToTop:hover {
    background: var(--navy);
  }

  /* ══ RESPONSIVE ══ */
  @media (max-width: 767.98px) {
    .featured-event {
      flex-direction: column;
    }

    .featured-event-img,
    .featured-event-img-placeholder {
      flex: none;
      min-height: 220px;
    }

    .featured-event-img::after {
      background: linear-gradient(to bottom, transparent 60%, var(--navy) 100%);
    }

    .featured-event-body {
      padding: 28px 22px;
    }
  }
</style>

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
<script>
  const catPills = document.querySelectorAll('.cat-pill[data-cat]');
  let activeCat = 'all';

  catPills.forEach(pill => {
    pill.addEventListener('click', e => {
      e.preventDefault();
      activeCat = pill.dataset.cat;
      catPills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      applyFilters();
    });
  });

  function filterCat(cat) {
    activeCat = cat;
    catPills.forEach(p => p.classList.toggle('active', p.dataset.cat === cat));
    applyFilters();
    const grid = document.getElementById('eventsGrid');
    if (grid) grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  document.querySelectorAll('[data-filter-cat]').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      filterCat(link.getAttribute('data-filter-cat'));
    });
  });

  const evSearch = document.getElementById('evSearch');
  const evYear = document.getElementById('evYear');
  const evSort = document.getElementById('evSort');
  if (evSearch) evSearch.addEventListener('input', applyFilters);
  if (evYear) evYear.addEventListener('change', applyFilters);
  if (evSort) evSort.addEventListener('change', applyFilters);

  function applyFilters() {
    const query = (evSearch && evSearch.value.trim().toLowerCase()) || '';
    const year = (evYear && evYear.value) || '';
    const sort = (evSort && evSort.value) || 'newest';
    const grid = document.getElementById('eventsGrid');
    const items = [...document.querySelectorAll('.ev-item')];
    let visible = 0;

    items.sort((a, b) => {
      const da = a.dataset.date || '';
      const db = b.dataset.date || '';
      return sort === 'oldest' ? da.localeCompare(db) : db.localeCompare(da);
    }).forEach(item => {
      if (grid) grid.appendChild(item);
    });

    items.forEach(item => {
      const cat = item.dataset.cat;
      const itemYear = item.dataset.year;
      const text = item.innerText.toLowerCase();
      const matchCat = activeCat === 'all' || cat === activeCat;
      const matchYear = !year || itemYear === year;
      const matchQ = !query || text.includes(query);
      const show = matchCat && matchYear && matchQ;
      item.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    const empty = document.getElementById('evNoResults');
    if (empty) empty.style.display = visible === 0 ? 'block' : 'none';
  }

  function resetFilters() {
    if (evSearch) evSearch.value = '';
    if (evYear) evYear.value = '';
    activeCat = 'all';
    catPills.forEach(p => p.classList.toggle('active', p.dataset.cat === 'all'));
    applyFilters();
  }

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
