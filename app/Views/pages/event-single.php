<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<?php
$gradient = $event['card_gradient'] ?? 'linear-gradient(135deg,#0A1628,#1a3a6b)';
$icon = $event['card_icon'] ?? 'bi-calendar-event';
$dateLabel = dms_cms_format_date($event['event_date'] ?? ($event['published_at'] ?? null));
?>
<style>
.article-wrap{max-width:820px;margin:0 auto}
.article-cat-badge{display:inline-block;background:var(--teal);color:#fff;font-size:.72rem;font-weight:700;padding:5px 12px;border-radius:999px;margin-bottom:12px;text-transform:uppercase;letter-spacing:.06em}
.article-title{font-family:var(--font-head);font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;color:var(--navy);line-height:1.25;margin-bottom:16px}
.article-byline{display:flex;flex-wrap:wrap;gap:10px 16px;color:var(--gray-mid);font-size:.88rem;margin-bottom:24px}
.event-hero-placeholder{height:280px;border-radius:var(--r-lg);display:flex;flex-direction:column;align-items:center;justify-content:center;color:#fff;margin-bottom:28px;gap:8px}
.event-hero-placeholder i{font-size:3rem;opacity:.85}
.event-meta-bar{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:28px}
.emb-item{display:flex;gap:10px;align-items:flex-start;background:var(--off-white);border:1px solid var(--border);border-radius:var(--r-md);padding:12px}
.emb-icon{color:var(--teal);font-size:1.2rem}
.emb-label{display:block;font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:var(--gray-mid);font-weight:700}
.emb-value{font-family:var(--font-head);font-weight:700;color:var(--navy);font-size:.9rem}
.article-body{font-size:1.05rem;line-height:1.75;color:var(--gray-dark)}
.article-body h3,.article-body h4{font-family:var(--font-head);color:var(--navy);margin:1.6em 0 .6em}
.article-body ul,.article-body ol{padding-left:1.2em;margin-bottom:1em}
.article-body blockquote{border-left:4px solid var(--teal);padding:12px 18px;margin:1.4em 0;background:var(--off-white)}
.related-card{display:block;border:1px solid var(--border);border-radius:var(--r-md);overflow:hidden;text-decoration:none;color:inherit;height:100%}
.related-card .rc-img{height:110px;display:flex;align-items:center;justify-content:center;color:#fff}
.related-card .rc-body{padding:14px}
.related-card .rc-cat{font-size:.7rem;font-weight:700;color:var(--teal);text-transform:uppercase}
.related-card .rc-title{font-family:var(--font-head);font-weight:700;color:var(--navy);font-size:.95rem;margin:6px 0}
.related-card .rc-date{font-size:.78rem;color:var(--gray-mid)}
</style>

<div class="page-hero" style="padding:18px 0">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <div class="breadcrumb-pmc">
      <a href="./">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="events">Events</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current"><?= htmlspecialchars(dms_cms_category_label((string) ($event['category'] ?? ''))) ?></span>
    </div>
  </div>
</div>

<section class="pmc-section">
  <div class="container">
    <article class="article-wrap">
      <div class="article-cat-badge"><?= htmlspecialchars(dms_cms_category_label((string) ($event['category'] ?? ''))) ?></div>
      <h1 class="article-title"><?= htmlspecialchars($event['title'] ?? '') ?></h1>
      <div class="article-byline">
        <?php if (!empty($event['author'])): ?><span><i class="bi bi-person-circle"></i> <?= htmlspecialchars($event['author']) ?></span><?php endif; ?>
        <?php if ($dateLabel): ?><span><i class="bi bi-calendar3"></i> <?= htmlspecialchars($dateLabel) ?></span><?php endif; ?>
        <?php if (!empty($event['read_minutes'])): ?><span><i class="bi bi-clock"></i> <?= (int) $event['read_minutes'] ?> min read</span><?php endif; ?>
      </div>

      <?php if (!empty($event['cover_image'])): ?>
        <img src="<?= htmlspecialchars($event['cover_image']) ?>" alt="" style="width:100%;border-radius:var(--r-lg);margin-bottom:28px;object-fit:cover;max-height:420px">
      <?php else: ?>
        <div class="event-hero-placeholder" style="background:<?= htmlspecialchars($gradient) ?>">
          <i class="bi <?= htmlspecialchars($icon) ?>"></i>
          <span><?= htmlspecialchars($event['title'] ?? '') ?></span>
        </div>
      <?php endif; ?>

      <div class="event-meta-bar">
        <?php if ($dateLabel): ?>
        <div class="emb-item">
          <div class="emb-icon"><i class="bi bi-calendar-event"></i></div>
          <div><span class="emb-label">Date</span><span class="emb-value"><?= htmlspecialchars($dateLabel) ?></span></div>
        </div>
        <?php endif; ?>
        <?php if (!empty($event['venue'])): ?>
        <div class="emb-item">
          <div class="emb-icon"><i class="bi bi-geo-alt"></i></div>
          <div><span class="emb-label">Venue</span><span class="emb-value"><?= htmlspecialchars($event['venue']) ?></span></div>
        </div>
        <?php endif; ?>
        <div class="emb-item">
          <div class="emb-icon"><i class="bi bi-tag"></i></div>
          <div><span class="emb-label">Category</span><span class="emb-value"><?= htmlspecialchars(dms_cms_category_label((string) ($event['category'] ?? ''))) ?></span></div>
        </div>
      </div>

      <div class="article-body">
        <?= $event['body_html'] ?: ('<p>' . htmlspecialchars($event['excerpt'] ?? '') . '</p>') ?>
      </div>

      <?php if ($related): ?>
        <div class="mt-5">
          <h3 style="font-family:var(--font-head);font-size:1.12rem;font-weight:800;color:var(--navy);margin-bottom:20px;">Related Events</h3>
          <div class="row g-3">
            <?php foreach ($related as $r): ?>
              <div class="col-md-4">
                <a class="related-card" href="<?= htmlspecialchars(dms_cms_public_href($r, 'event-single')) ?>">
                  <div class="rc-img" style="background:<?= htmlspecialchars($r['card_gradient'] ?? $gradient) ?>"><i class="bi <?= htmlspecialchars($r['card_icon'] ?? 'bi-calendar-event') ?>"></i></div>
                  <div class="rc-body">
                    <div class="rc-cat"><?= htmlspecialchars(dms_cms_category_label((string) ($r['category'] ?? ''))) ?></div>
                    <div class="rc-title"><?= htmlspecialchars($r['title'] ?? '') ?></div>
                    <div class="rc-date"><i class="bi bi-calendar3"></i> <?= htmlspecialchars(dms_cms_format_date($r['event_date'] ?? ($r['published_at'] ?? null))) ?></div>
                  </div>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <div class="mt-4">
        <a href="events" class="btn-pmc btn-pmc-outline"><i class="bi bi-arrow-left"></i> All events</a>
      </div>
    </article>
  </div>
</section>

<?= $this->endSection() ?>
