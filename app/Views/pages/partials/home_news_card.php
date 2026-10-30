<?php
/**
 * Homepage news / admissions card.
 *
 * @var array<string,mixed> $card    from Home::card()
 * @var int                 $delay   fade-in delay step (1-3)
 * @var string              $variant admissions|campus
 */
$isAdmissions = ($variant ?? '') === 'admissions';
$target = $card['external'] ? ' target="_blank" rel="noopener"' : '';
?>
<div class="col-lg-4 col-md-6 fu fu-delay-<?= min(3, max(1, (int) $delay)) ?>">
  <article class="news-card news-card-text news-card--<?= $isAdmissions ? 'admissions' : 'campus' ?>">
    <div class="nc-body">
      <div class="nc-meta">
        <span class="nc-cat <?= $isAdmissions ? 'nc-cat-admissions' : 'nc-cat-news' ?>"><?= esc($card['category'] ?: ($isAdmissions ? 'Admissions' : 'News')) ?></span>
        <span class="nc-date"><i class="bi bi-calendar3"></i> <?= esc($card['date']) ?></span>
      </div>
      <h3 class="nc-title"><?= esc($card['title']) ?></h3>
      <?php if ($card['deadline'] !== ''): ?>
        <p class="nc-deadline"><i class="bi bi-clock"></i> Apply by <strong><?= esc($card['deadline']) ?></strong></p>
      <?php endif; ?>
      <?php if ($card['excerpt'] !== ''): ?>
        <p class="nc-excerpt"><?= esc($card['excerpt']) ?></p>
      <?php endif; ?>
      <div class="nc-actions<?= $card['poster'] !== '' ? ' nc-actions-split' : '' ?>">
        <a href="<?= esc($card['url'], 'attr') ?>"<?= $target ?> class="nc-btn nc-btn-primary"><?= esc($card['label']) ?> <i class="bi bi-arrow-right"></i></a>
        <?php if ($card['poster'] !== ''): ?>
          <a target="_blank" rel="noopener" href="<?= esc($card['poster'], 'attr') ?>" class="nc-btn nc-btn-form"><i class="bi bi-download"></i> Advertisement</a>
        <?php endif; ?>
      </div>
    </div>
  </article>
</div>
