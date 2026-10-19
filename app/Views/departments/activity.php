<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
  $photos = is_array($activity['photos'] ?? null) ? $activity['photos'] : [];
?>
<div class="page-hero">
  <div class="page-hero-grid"></div>
  <div class="container page-hero-content">
    <span class="page-hero-eyebrow"><?= esc($dept['name'] ?? '') ?></span>
    <h1><?= esc($activity['title'] ?? 'Activity') ?></h1>
    <div class="breadcrumb-pmc">
      <a href="<?= site_url() ?>">Home</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="<?= site_url('departments') ?>">Departments</a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <a href="<?= site_url('department?slug=' . rawurlencode($slug)) ?>"><?= esc($dept['name'] ?? '') ?></a>
      <span class="sep"><i class="bi bi-chevron-right"></i></span>
      <span class="current"><?= esc($activity['title'] ?? '') ?></span>
    </div>
  </div>
</div>
<section class="pmc-section">
  <div class="container" style="max-width:820px;">
    <?php if (! empty($activity['date'])): ?>
      <p class="dept-activity-detail-meta">
        <span><i class="bi bi-calendar3"></i> <?= esc($activity['date']) ?></span>
      </p>
    <?php endif; ?>

    <div class="dept-activity-detail-text page-content">
      <p><?= nl2br(esc($activity['text'] ?? '')) ?></p>
    </div>

    <?php if ($photos !== []): ?>
      <div class="dept-activity-gallery">
        <?php foreach ($photos as $i => $src): ?>
          <a class="dept-activity-gallery-item" href="<?= esc(base_url(ltrim((string) $src, '/'))) ?>" target="_blank" rel="noopener">
            <img src="<?= esc(base_url(ltrim((string) $src, '/'))) ?>" alt="<?= esc(($activity['title'] ?? 'Activity') . ' image ' . ((int) $i + 1)) ?>">
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <a href="<?= site_url('department?slug=' . rawurlencode($slug)) ?>" class="btn-pmc btn-pmc-outline mt-4"><i class="bi bi-arrow-left"></i> Back to department</a>
  </div>
</section>
<?= $this->endSection() ?>
