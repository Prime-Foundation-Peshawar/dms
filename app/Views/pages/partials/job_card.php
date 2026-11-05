<?php
$title = (string) ($job['job_title'] ?? 'Career opportunity');
$description = trim(html_entity_decode(strip_tags((string) ($job['job_description'] ?? '')), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
$expiry = strtotime((string) ($job['job_expiry'] ?? ''));
$advert = (string) ($job['jd_file'] ?? '');
$hasAdvert = filter_var($advert, FILTER_VALIDATE_URL) && in_array(strtolower((string) parse_url($advert, PHP_URL_SCHEME)), ['http', 'https'], true);
?>
<div class="col-lg-4 col-md-6">
  <article class="news-card news-card-text news-card--career career-job-card h-100">
    <div class="nc-body">
      <div class="nc-meta d-flex flex-wrap gap-2">
        <span class="nc-cat nc-cat-career">Open</span>
      </div>
      <h3 class="nc-title"><?= esc($title) ?></h3>
      <?php if ($description !== ''): ?><p class="nc-excerpt"><?= esc(mb_strimwidth($description, 0, 120, '...')) ?></p><?php endif; ?>
      <ul class="list-unstyled small mb-3">
        <?php if ($expiry): ?><li><i class="bi bi-calendar-event me-2" aria-hidden="true"></i>Apply by <?= esc(date('d M Y', $expiry)) ?></li><?php endif; ?>
      </ul>
      <div class="nc-actions d-flex flex-wrap gap-2">
        <a href="https://careers.riphahpsh.edu.pk/login" target="_blank" rel="noopener" class="nc-btn nc-btn-primary" aria-label="<?= esc('Apply for ' . $title . ' on the Career Portal', 'attr') ?>">Apply Online <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i></a>
        <?php if ($hasAdvert): ?><a href="<?= esc($advert, 'attr') ?>" target="_blank" rel="noopener" class="nc-btn nc-btn-form" aria-label="<?= esc('View advertisement for ' . $title, 'attr') ?>">Advertisement <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i></a><?php endif; ?>
      </div>
    </div>
  </article>
</div>
