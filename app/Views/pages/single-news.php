<?php
/** @var array<string,mixed> $news_post */
/** @var list<array<string,mixed>> $news_media */
$news_post  = is_array($news_post ?? null) ? $news_post : [];
$news_media = is_array($news_media ?? null) ? $news_media : [];
$title      = (string) ($news_post['title'] ?? 'News');
$category   = trim((string) ($news_post['category'] ?? '')) ?: 'News';
$published  = (string) ($news_post['published_at'] ?? '');
$dateLabel  = $published !== '' ? date('F j, Y', strtotime($published)) : '';
$deadline   = $category === 'Admissions' && ! empty($news_post['expires_at'])
    ? date('F j, Y', strtotime((string) $news_post['expires_at'])) : '';
$eventDay   = (string) ($news_post['event_date'] ?? '');
$eventFrom  = (string) ($news_post['start_date'] ?? '');
$eventLabel = $eventDay === '' ? '' : ($eventFrom !== '' && $eventFrom !== $eventDay ? date('F j', strtotime($eventFrom)) . ' to ' : '') . date('F j, Y', strtotime($eventDay));
$location   = trim((string) ($news_post['location'] ?? ''));
$excerpt    = trim((string) ($news_post['excerpt'] ?? ''));
$bodyHtml   = (string) ($news_post['body_html'] ?? '');
$bodyText   = trim((string) ($news_post['body_text'] ?? ''));
// Don't repeat the excerpt when the body already opens with the same text.
$plainBody  = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($bodyHtml !== '' ? $bodyHtml : $bodyText))) ?? '');
$showExcerpt = $excerpt !== '' && ! str_contains($plainBody, preg_replace('/\s+/', ' ', $excerpt) ?? $excerpt);

$asset = static function (string $path): string {
    $path = trim($path);
    if ($path === '') {
        return '';
    }

    return preg_match('#^https?://#i', $path) ? $path : base_url(ltrim($path, '/'));
};
$cover = $asset((string) ($news_post['cover_image'] ?? ''));

$link      = trim((string) ($news_post['link_url'] ?? ''));
$linkUrl   = $link === '' ? '' : (preg_match('#^https?://#i', $link) ? $link : site_url(ltrim($link, '/')));
$linkLabel = trim((string) ($news_post['link_label'] ?? '')) ?: 'Learn more';
$linkExt   = $linkUrl !== '' && parse_url($linkUrl, PHP_URL_HOST) !== parse_url(site_url('/'), PHP_URL_HOST);

$more = [];
try {
    foreach (model(\App\Models\NewsPostModel::class)->published(8) as $row) {
        if (($row['slug'] ?? '') !== ($news_post['slug'] ?? '') && count($more) < 5) {
            $more[] = $row;
        }
    }
} catch (\Throwable $e) {
    log_message('error', 'Single-news sidebar failed: ' . $e->getMessage());
}
?>
<?= $this->include('pages/partials/article_styles') ?>
<style>
  .article-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 28px; }
  .article-actions .btn-pmc { font-size: .85rem; padding: 11px 22px; }
</style>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>News &amp; Events</h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="<?= site_url('all-news') ?>">News &amp; Events</a>
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
          <div class="article-cat-badge"><?= esc($category) ?></div>
          <h1 class="article-title"><?= esc($title) ?></h1>
          <div class="article-byline">
            <?php if ($dateLabel !== ''): ?>
            <span class="byline-item"><i class="bi bi-calendar3"></i> Published <?= esc($dateLabel) ?></span>
            <?php endif; ?>
            <?php if ($deadline !== ''): ?>
            <span class="byline-item"><i class="bi bi-clock"></i> Apply by <?= esc($deadline) ?></span>
            <?php endif; ?>
            <?php if ($location !== ''): ?>
            <span class="byline-item"><i class="bi bi-geo-alt"></i> <?= esc($location) ?></span>
            <?php endif; ?>
          </div>
        </div>

        <?php if ($cover !== ''): ?>
          <img class="event-hero-img" src="<?= esc($cover, 'attr') ?>" alt="<?= esc($title) ?>">
        <?php endif; ?>

        <div class="event-meta-bar">
          <?php if ($eventLabel !== ''): ?>
          <div class="emb-item">
            <div class="emb-icon"><i class="bi bi-calendar-event"></i></div>
            <div>
              <span class="emb-label">Event date</span>
              <span class="emb-value"><?= esc($eventLabel) ?></span>
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
          <?php if ($dateLabel !== ''): ?>
          <div class="emb-item">
            <div class="emb-icon"><i class="bi bi-calendar-check"></i></div>
            <div>
              <span class="emb-label">Published</span>
              <span class="emb-value"><?= esc($dateLabel) ?></span>
            </div>
          </div>
          <?php endif; ?>
          <?php if ($deadline !== ''): ?>
          <div class="emb-item">
            <div class="emb-icon"><i class="bi bi-hourglass-split"></i></div>
            <div>
              <span class="emb-label">Apply by</span>
              <span class="emb-value"><?= esc($deadline) ?></span>
            </div>
          </div>
          <?php endif; ?>
          <div class="emb-item">
            <div class="emb-icon"><i class="bi bi-tag"></i></div>
            <div>
              <span class="emb-label">Category</span>
              <span class="emb-value"><?= esc($category) ?></span>
            </div>
          </div>
        </div>

        <?php if ($linkUrl !== '' || $cover !== ''): ?>
        <div class="article-actions">
          <?php if ($linkUrl !== ''): ?>
            <a href="<?= esc($linkUrl, 'attr') ?>" class="btn-pmc btn-pmc-primary"<?= $linkExt ? ' target="_blank" rel="noopener"' : '' ?>>
              <?= esc($linkLabel) ?> <i class="bi bi-arrow-right"></i>
            </a>
          <?php endif; ?>
          <?php if ($cover !== ''): ?>
            <a href="<?= esc($cover, 'attr') ?>" class="btn-pmc btn-pmc-outline" target="_blank" rel="noopener">
              <i class="bi bi-download"></i> Advertisement
            </a>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="article-body">
          <?php if ($showExcerpt): ?>
            <p><strong><?= esc($excerpt) ?></strong></p>
          <?php endif; ?>
          <?php if ($bodyHtml !== ''): ?>
            <?= $bodyHtml ?>
          <?php elseif ($bodyText !== ''): ?>
            <p><?= nl2br(esc($bodyText)) ?></p>
          <?php elseif ($excerpt === ''): ?>
            <p class="text-muted mb-0">Full details will be published soon.</p>
          <?php endif; ?>
          <?php if ($news_media): ?>
          <div class="event-media-grid">
            <?php foreach ($news_media as $m):
              $src = $asset((string) ($m['path'] ?? ''));
              if ($src === '') {
                  continue;
              }
            ?>
              <a href="<?= esc($src, 'attr') ?>" target="_blank" rel="noopener">
                <?php if (preg_match('/\.(jpe?g|png|gif|webp)$/i', $src)): ?>
                  <img src="<?= esc($src, 'attr') ?>" alt="<?= esc($m['caption'] ?? '') ?>">
                <?php else: ?>
                  <span class="d-flex align-items-center justify-content-center gap-2 h-100 border rounded-3 p-3 small"><i class="bi bi-file-earmark-pdf"></i> Download</span>
                <?php endif; ?>
              </a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <div class="mt-4">
          <a href="<?= site_url('all-news') ?>" class="btn-pmc btn-pmc-outline" style="font-size:.85rem;padding:10px 18px;">
            <i class="bi bi-arrow-left"></i> Back to News
          </a>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="sidebar-widget mb-4">
          <div class="sw-head"><i class="bi bi-newspaper"></i> More News &amp; Events</div>
          <div class="sw-body" style="padding:16px;">
            <?php if ($more): ?>
              <?php foreach ($more as $row):
                $d = (string) ($row['published_at'] ?? '');
              ?>
              <a class="upcoming-card" href="<?= esc(site_url('single-news') . '?slug=' . rawurlencode((string) ($row['slug'] ?? ''))) ?>">
                <div class="upcoming-date-block">
                  <span class="udb-day"><?= $d ? esc(date('d', strtotime($d))) : '—' ?></span>
                  <span class="udb-month"><?= $d ? esc(date('M', strtotime($d))) : '' ?></span>
                </div>
                <div>
                  <div class="ub-title"><?= esc($row['title'] ?? '') ?></div>
                  <div class="ub-venue"><?= esc(($row['category'] ?? '') ?: 'News') ?><?= $d ? ' · ' . esc(date('Y', strtotime($d))) : '' ?></div>
                </div>
              </a>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="small text-muted mb-0">Nothing else yet.</p>
            <?php endif; ?>
          </div>
        </div>
        <div class="sidebar-widget">
          <div class="sw-head"><i class="bi bi-link-45deg"></i> Quick Links</div>
          <div class="sw-body">
            <a class="sw-link" href="<?= site_url('all-news') ?>"><i class="bi bi-newspaper"></i>All News &amp; Events</a>
            <a class="sw-link" href="<?= site_url('admissions') ?>"><i class="bi bi-door-open"></i>Admissions</a>
            <a class="sw-link" href="<?= site_url('contact') ?>"><i class="bi bi-envelope"></i>Contact</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
