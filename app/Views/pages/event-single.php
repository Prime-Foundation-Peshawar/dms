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
<?= $this->include('pages/partials/article_styles') ?>

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
