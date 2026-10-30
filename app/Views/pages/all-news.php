<?php
/** @var list<array<string,mixed>> $news_posts */
$news_posts = is_array($news_posts ?? null) ? $news_posts : [];

// Neutral placeholder when a post has no cover upload.
$defaultCover = 'assets/images/placeholders/event-placeholder.jpg';
if (! is_file(FCPATH . $defaultCover)) {
    $defaultCover = 'assets/images/slider/hero-slide-1.jpg';
}
$coverUrl = static function (?string $path) use ($defaultCover): string {
    $path = trim((string) $path);
    if ($path === '') {
        $path = $defaultCover;
    }

    return preg_match('#^https?://#i', $path) ? $path : base_url(ltrim($path, '/'));
};
$catSlug = static function (string $cat): string {
    $s = strtolower(trim($cat));
    $s = preg_replace('/[^a-z0-9]+/', '-', $s) ?? '';

    return trim($s, '-') ?: 'general';
};
$postUrl = static fn (array $p): string => site_url('single-news') . '?slug=' . rawurlencode((string) ($p['slug'] ?? ''));
$fmt = static function (?string $d, string $format): string {
    $ts = $d ? strtotime($d) : false;

    return $ts ? date($format, $ts) : '';
};
$excerptOf = static function (array $p, int $max = 160): string {
    $text = trim((string) ($p['excerpt'] ?? ''));
    if ($text === '') {
        $text = trim((string) ($p['body_text'] ?? ''));
    }

    return mb_strlen($text) > $max ? mb_substr($text, 0, $max - 3) . '…' : $text;
};

$catCounts = [];
$years     = [];
foreach ($news_posts as $p) {
    $cat = trim((string) ($p['category'] ?? '')) ?: 'General';
    $catCounts[$cat] = ($catCounts[$cat] ?? 0) + 1;
    if (preg_match('/^(\d{4})/', (string) ($p['published_at'] ?? ''), $m)) {
        $years[(int) $m[1]] = true;
    }
}
krsort($years);
$years    = array_keys($years);
$featured = $news_posts[0] ?? null;

$upcomingEvents = [];
try {
    $upcomingEvents = model(\App\Models\EventModel::class)
        ->where('status', 'published')
        ->where('event_date >=', date('Y-m-d'))
        ->orderBy('event_date', 'ASC')
        ->findAll(5);
} catch (\Throwable $e) {
    log_message('error', 'All-news upcoming events failed: ' . $e->getMessage());
}
?>
<?= $this->include('pages/partials/news_events_styles') ?>

<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <h1>News &amp; Updates</h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current">News</span>
    </div>
  </div>
</div>

<section class="pmc-section bg-off">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">

        <?php if ($news_posts): ?>
        <div class="events-filter-bar fu">
          <div class="ef-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="ef-search" id="evSearch" placeholder="Search news…" />
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
        <?php endif; ?>

        <?php if ($featured):
          $fCat = trim((string) ($featured['category'] ?? '')) ?: 'General';
        ?>
        <div class="featured-event fu" id="featuredEvent">
          <div class="featured-event-img" style="background:url('<?= esc($coverUrl($featured['cover_image'] ?? null), 'attr') ?>') center/cover no-repeat;min-height:220px;"></div>
          <div class="featured-event-body">
            <div class="featured-badge"><i class="bi bi-star-fill"></i> Latest</div>
            <h2 class="featured-event-title"><?= esc($featured['title'] ?? '') ?></h2>
            <p class="featured-event-excerpt"><?= esc($excerptOf($featured, 220)) ?></p>
            <div class="featured-event-meta">
              <?php if (! empty($featured['published_at'])): ?>
              <div class="fem-item"><i class="bi bi-calendar3"></i> <?= esc($fmt($featured['published_at'], 'M j, Y')) ?></div>
              <?php endif; ?>
              <div class="fem-item"><i class="bi bi-tag"></i> <?= esc($fCat) ?></div>
            </div>
            <a href="<?= esc($postUrl($featured)) ?>" class="btn-pmc btn-pmc-gold" style="font-size:.85rem;padding:11px 22px;align-self:flex-start;">
              <i class="bi bi-arrow-right-circle"></i> Read Full Story
            </a>
          </div>
        </div>
        <?php endif; ?>

        <div class="row g-4" id="eventsGrid">
          <?php foreach ($news_posts as $p):
            $cat  = trim((string) ($p['category'] ?? '')) ?: 'General';
            $date = (string) ($p['published_at'] ?? '');
          ?>
          <div class="col-md-6 ev-item fu" data-cat="<?= esc($catSlug($cat)) ?>" data-year="<?= esc($fmt($date, 'Y')) ?>" data-date="<?= esc($date) ?>">
            <a class="event-card" href="<?= esc($postUrl($p)) ?>">
              <div class="ec-img">
                <img src="<?= esc($coverUrl($p['cover_image'] ?? null), 'attr') ?>" alt="<?= esc($p['title'] ?? '') ?>" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
              </div>
              <div style="position:relative;">
                <div class="ec-badges" style="position:relative;padding:8px 12px;background:transparent;bottom:auto;left:auto;right:auto;">
                  <span class="ec-cat-badge cat-<?= esc($catSlug($cat)) ?>"><?= esc($cat) ?></span>
                  <?php if ($date !== ''): ?>
                  <div class="ec-date-badge"><span class="ec-date-day"><?= esc($fmt($date, 'd')) ?></span><span class="ec-date-month"><?= esc($fmt($date, 'M')) ?></span></div>
                  <?php endif; ?>
                </div>
              </div>
              <div class="ec-body">
                <div class="ec-title"><?= esc($p['title'] ?? '') ?></div>
                <div class="ec-excerpt"><?= esc($excerptOf($p)) ?></div>
                <div class="ec-footer">
                  <span class="ec-meta-item"><i class="bi bi-calendar3"></i> <?= esc($fmt($date, 'M j, Y')) ?></span>
                  <span class="ec-read-link">Read More <i class="bi bi-arrow-right"></i></span>
                </div>
              </div>
            </a>
          </div>
          <?php endforeach; ?>
        </div>

        <div id="evNoResults" style="<?= $news_posts ? 'display:none;' : '' ?>text-align:center;padding:60px 0;">
          <i class="bi bi-newspaper" style="font-size:3rem;color:var(--gray-light);display:block;margin-bottom:14px;"></i>
          <h5 style="font-family:var(--font-head);color:var(--navy);">No news found</h5>
          <p style="font-family:var(--font-body);color:var(--gray-mid);font-size:.9rem;"><?= $news_posts ? 'Try different filters or search terms.' : 'Please check back soon for campus updates.' ?></p>
          <?php if ($news_posts): ?>
          <button onclick="resetFilters()" class="btn-pmc btn-pmc-outline mt-3" type="button" style="font-size:.85rem;padding:9px 20px;">
            <i class="bi bi-x-circle"></i> Clear Filters
          </button>
          <?php endif; ?>
        </div>

      </div>

      <div class="col-lg-4">
        <div class="sidebar-widget mb-4 fu">
          <div class="sw-head"><i class="bi bi-calendar-event"></i> Upcoming Events</div>
          <div class="sw-body" style="padding:16px;">
            <?php if ($upcomingEvents): ?>
              <?php foreach ($upcomingEvents as $ev):
                $d = (string) ($ev['event_date'] ?? '');
              ?>
            <a class="upcoming-card" href="<?= esc(site_url('event-single') . '?slug=' . rawurlencode((string) ($ev['slug'] ?? ''))) ?>">
              <div class="upcoming-date-block">
                <span class="udb-day"><?= esc($fmt($d, 'd') ?: '—') ?></span>
                <span class="udb-month"><?= esc($fmt($d, 'M')) ?></span>
              </div>
              <div class="upcoming-body">
                <div class="ub-title"><?= esc($ev['title'] ?? '') ?></div>
                <div class="ub-venue"><i class="bi bi-geo-alt-fill"></i> <?= esc(($ev['location'] ?? '') ?: $fmt($d, 'M j, Y')) ?></div>
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

        <div class="sidebar-widget fu">
          <div class="sw-head"><i class="bi bi-link-45deg"></i> Quick Links</div>
          <div class="sw-body">
            <a class="sw-link" href="<?= site_url('events') ?>"><i class="bi bi-calendar-event"></i>All Events</a>
            <a class="sw-link" href="<?= site_url('admissions') ?>"><i class="bi bi-door-open"></i>Admissions</a>
            <a class="sw-link" href="<?= site_url('gallery') ?>"><i class="bi bi-images"></i>Photo Gallery</a>
            <a class="sw-link" href="<?= site_url('contact') ?>"><i class="bi bi-envelope"></i>Contact</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->include('pages/partials/news_events_filter') ?>
