<?php
/** @var array<string,mixed> $event_row */
/** @var list<array<string,mixed>> $event_media */
$event_row   = is_array($event_row ?? null) ? $event_row : [];
$event_media = is_array($event_media ?? null) ? $event_media : [];
$title       = (string) ($event_row['title'] ?? 'Event');
$category    = trim((string) ($event_row['category'] ?? 'Event'));
$dateRaw     = (string) ($event_row['event_date'] ?? '');
$dateLabel   = $dateRaw !== '' ? date('F j, Y', strtotime($dateRaw)) : '';
$location    = trim((string) ($event_row['location'] ?? ''));
$cover       = trim((string) ($event_row['cover_image'] ?? ''));
if ($cover === '') {
    foreach (['assets/images/placeholders/event-placeholder.jpg', 'assets/images/slider/hero-slide-1.jpg'] as $candidate) {
        if (is_file(FCPATH . $candidate)) {
            $cover = $candidate;
            break;
        }
    }
}
$excerpt     = trim((string) ($event_row['excerpt'] ?? ''));
$bodyHtml    = (string) ($event_row['body_html'] ?? '');
$bodyText    = trim((string) ($event_row['body_text'] ?? ''));
?>
<style>
  .article-header { margin-bottom: 22px; }
  .article-cat-badge {
    display: inline-block; font-family: var(--font-head); font-size: .72rem; font-weight: 800;
    letter-spacing: .06em; text-transform: uppercase; color: var(--teal); background: var(--teal-pale);
    padding: 5px 12px; border-radius: 100px; margin-bottom: 12px;
  }
  .article-title {
    font-family: var(--font-head); font-size: 1.85rem; font-weight: 900; color: var(--navy);
    line-height: 1.25; margin-bottom: 14px;
  }
  .article-byline {
    display: flex; flex-wrap: wrap; gap: 10px 16px; align-items: center;
    font-family: var(--font-body); font-size: .85rem; color: var(--gray-mid);
  }
  .byline-item { display: inline-flex; align-items: center; gap: 6px; }
  .byline-item i { color: var(--teal); }
  .event-hero-img {
    width: 100%; max-height: 420px; object-fit: cover; border-radius: var(--r-lg);
    margin: 22px 0; box-shadow: var(--shadow-sm);
  }
  .event-hero-placeholder {
    width: 100%; min-height: 220px; border-radius: var(--r-lg); margin: 22px 0;
    background: linear-gradient(135deg, var(--navy), var(--navy-mid));
    display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px;
    color: rgba(255,255,255,.55);
  }
  .event-hero-placeholder i { font-size: 2.4rem; }
  .event-meta-bar {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px;
    background: white; border: 1px solid var(--border); border-radius: var(--r-lg);
    padding: 18px; margin-bottom: 28px;
  }
  .emb-item { display: flex; gap: 12px; align-items: flex-start; }
  .emb-icon {
    width: 40px; height: 40px; border-radius: 10px; background: var(--teal-pale); color: var(--teal);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  }
  .emb-label {
    display: block; font-size: .7rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .06em; color: var(--gray-mid); margin-bottom: 2px;
  }
  .emb-value { font-family: var(--font-head); font-weight: 700; color: var(--navy); font-size: .92rem; }
  .article-body {
    font-family: var(--font-body); font-size: .98rem; line-height: 1.75; color: var(--gray-dark);
    background: white; border: 1px solid var(--border); border-radius: var(--r-lg); padding: 28px 30px;
  }
  .article-body p { margin-bottom: 1rem; }
  .article-body h2, .article-body h3, .article-body h4 {
    font-family: var(--font-head); color: var(--navy); margin-top: 1.4rem; margin-bottom: .7rem;
  }
  .event-media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; margin-top: 22px; }
  .event-media-grid img {
    width: 100%; height: 140px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border);
  }
  .upcoming-card {
    display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border); text-decoration: none; color: inherit;
  }
  .upcoming-card:last-child { border-bottom: none; }
  .upcoming-date-block {
    width: 52px; border-radius: 10px; background: var(--navy); color: white; text-align: center;
    padding: 8px 4px; flex-shrink: 0;
  }
  .udb-day { display: block; font-family: var(--font-head); font-weight: 900; font-size: 1.1rem; line-height: 1; }
  .udb-month { display: block; font-size: .68rem; font-weight: 700; text-transform: uppercase; opacity: .8; margin-top: 2px; }
  .ub-title { font-family: var(--font-head); font-weight: 700; font-size: .9rem; color: var(--navy); margin-bottom: 3px; }
  .ub-venue { font-size: .75rem; color: var(--gray-mid); }
</style>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>Events &amp; News</h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="<?= site_url('events') ?>">Events</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current"><?= esc($title) ?></span>
    </div>
  </div>
</div>

<section class="pmc-section bg-off">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <div class="article-header">
          <?php if ($category !== ''): ?>
          <div class="article-cat-badge"><?= esc($category) ?></div>
          <?php endif; ?>
          <h1 class="article-title"><?= esc($title) ?></h1>
          <div class="article-byline">
            <?php if ($dateLabel !== ''): ?>
            <span class="byline-item"><i class="bi bi-calendar3"></i> <?= esc($dateLabel) ?></span>
            <?php endif; ?>
            <?php if ($location !== ''): ?>
            <span class="byline-item"><i class="bi bi-geo-alt"></i> <?= esc($location) ?></span>
            <?php endif; ?>
          </div>
        </div>

        <?php if ($cover !== ''): ?>
          <img class="event-hero-img" src="<?= esc(base_url($cover)) ?>" alt="<?= esc($title) ?>">
        <?php endif; ?>

        <?php if ($dateLabel !== '' || $location !== ''): ?>
        <div class="event-meta-bar">
          <?php if ($dateLabel !== ''): ?>
          <div class="emb-item">
            <div class="emb-icon"><i class="bi bi-calendar-event"></i></div>
            <div>
              <span class="emb-label">Date</span>
              <span class="emb-value"><?= esc($dateLabel) ?></span>
            </div>
          </div>
          <?php endif; ?>
          <?php if ($location !== ''): ?>
          <div class="emb-item">
            <div class="emb-icon"><i class="bi bi-geo-alt"></i></div>
            <div>
              <span class="emb-label">Venue</span>
              <span class="emb-value"><?= esc($location) ?></span>
            </div>
          </div>
          <?php endif; ?>
          <?php if ($category !== ''): ?>
          <div class="emb-item">
            <div class="emb-icon"><i class="bi bi-tag"></i></div>
            <div>
              <span class="emb-label">Category</span>
              <span class="emb-value"><?= esc($category) ?></span>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="article-body">
          <?php if ($excerpt !== ''): ?>
            <p><strong><?= esc($excerpt) ?></strong></p>
          <?php endif; ?>
          <?php if ($bodyHtml !== ''): ?>
            <?= $bodyHtml ?>
          <?php elseif ($bodyText !== ''): ?>
            <p><?= nl2br(esc($bodyText)) ?></p>
          <?php elseif ($excerpt === ''): ?>
            <p class="text-muted mb-0">Full details for this event will be published soon.</p>
          <?php endif; ?>

          <?php if ($event_media): ?>
          <div class="event-media-grid">
            <?php foreach ($event_media as $m): ?>
              <a href="<?= esc(base_url($m['path'] ?? '')) ?>" target="_blank" rel="noopener">
                <img src="<?= esc(base_url($m['path'] ?? '')) ?>" alt="<?= esc($m['caption'] ?? '') ?>">
              </a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <div class="mt-4">
          <a href="<?= site_url('events') ?>" class="btn-pmc btn-pmc-outline" style="font-size:.85rem;padding:10px 18px;">
            <i class="bi bi-arrow-left"></i> Back to Events
          </a>
        </div>
      </div>

      <div class="col-lg-4">
        <?php
          $more = model(\App\Models\EventModel::class)
              ->where('status', 'published')
              ->where('slug !=', (string) ($event_row['slug'] ?? ''))
              ->orderBy('event_date', 'DESC')
              ->findAll(5);
        ?>
        <div class="sidebar-widget mb-4">
          <div class="sw-head"><i class="bi bi-calendar-event"></i> More Events</div>
          <div class="sw-body" style="padding:16px;">
            <?php if ($more): ?>
              <?php foreach ($more as $ev):
                $d = (string) ($ev['event_date'] ?? '');
                $href = site_url('event-single') . '?slug=' . rawurlencode((string) ($ev['slug'] ?? ''));
              ?>
              <a class="upcoming-card" href="<?= esc($href) ?>">
                <div class="upcoming-date-block">
                  <span class="udb-day"><?= $d ? esc(date('d', strtotime($d))) : '—' ?></span>
                  <span class="udb-month"><?= $d ? esc(date('M', strtotime($d))) : '' ?></span>
                </div>
                <div>
                  <div class="ub-title"><?= esc($ev['title'] ?? '') ?></div>
                  <div class="ub-venue"><?= esc($ev['location'] ?? ($d ? date('M j, Y', strtotime($d)) : '')) ?></div>
                </div>
              </a>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="small text-muted mb-0">No other events yet.</p>
            <?php endif; ?>
          </div>
        </div>

        <div class="sidebar-widget">
          <div class="sw-head"><i class="bi bi-link-45deg"></i> Quick Links</div>
          <div class="sw-body">
            <a class="sw-link" href="<?= site_url('events') ?>"><i class="bi bi-calendar-event"></i>All Events</a>
            <a class="sw-link" href="<?= site_url('all-news') ?>"><i class="bi bi-newspaper"></i>Campus News</a>
            <a class="sw-link" href="<?= site_url('gallery') ?>"><i class="bi bi-images"></i>Photo Gallery</a>
            <a class="sw-link" href="<?= site_url('contact') ?>"><i class="bi bi-envelope"></i>Contact</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
