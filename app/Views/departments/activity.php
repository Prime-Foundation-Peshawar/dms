<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
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
    <p class="text-muted mb-3"><i class="bi bi-calendar3"></i> <?= esc($activity['date'] ?? '') ?></p>
    <div class="page-content">
      <p><?= esc($activity['text'] ?? '') ?></p>
    </div>
    <a href="<?= site_url('department?slug=' . rawurlencode($slug)) ?>" class="btn-pmc btn-pmc-outline mt-4"><i class="bi bi-arrow-left"></i> Back to department</a>
  </div>
</section>
<?= $this->endSection() ?>
